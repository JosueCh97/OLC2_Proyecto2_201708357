<?php

declare(strict_types=1);

namespace App\Compiler;

use App\Instructions\Asignacion;
use App\Instructions\Bloque;
use App\Instructions\DeclaracionCorta;
use App\Instructions\DeclaracionID;
use App\Instructions\Funcion;
use App\Instructions\Para;
use App\Instructions\Segun;
use App\Instructions\Si;
use App\Instructions\AsignacionArreglo;
use App\Instructions\AsignacionPuntero;
use App\Expressions\AccesoArreglo;
use App\Expressions\ArregloLiteral;
use App\Expressions\Casteo;
use App\Expressions\Llamada;
use App\Expressions\Referencia;
use App\Expressions\Desreferencia;

/**
 * Generador de código ensamblador ARM64 (AArch64) — Fase 4.
 *
 * ─── Cobertura acumulada ────────────────────────────────────────────────────
 *  Fase 2 · Variables, aritmética, asignación, ++/--, fmt.Print/Println,
 *           expresiones relacionales y lógicas, AsignacionCompuesta.
 *  Fase 3 · if / else if / else, for, switch/case/default, break, continue.
 *  Fase 4 · Funciones con parámetros y retorno (return).
 *           Llamadas a funciones como instrucción y como expresión.
 *           Potencia (^) mediante helper __pow_int.
 *  Fase 5 · Funciones embebidas: len(), typeOf(), now(), substr().
 *           Tracking de tipo por variable (CADENA vs ENTERO, etc.).
 *           Strings null-terminados en .data → __strlen_setx1.
 *           Syscall clock_gettime para now() → helper __now.
 *           Sección .bss para buffer dinámico de now().
 *
 * ─── Layout del stack frame ─────────────────────────────────────────────────
 *  [x29 +  0]  x29 del llamador    ← stp x29, x30, [sp, #0]
 *  [x29 +  8]  x30 del llamador
 *  [x29 + 16]  scratch save slot 0  ← getScratchSaveOffset(0)
 *  [x29 + 24]  scratch save slot 1
 *  [x29 + 32]  scratch save slot 2
 *  [x29 + 40]  scratch save slot 3
 *  [x29 + 48]  scratch save slot 4
 *  [x29 + 56]  scratch save slot 5
 *  [x29 + 64]  parámetro 0 / primera variable local
 *  [x29 + 72]  parámetro 1 / segunda variable ...
 *  ...
 *
 * ─── Por qué frame-slots en vez de scratch registers ────────────────────────
 *  AAPCS64 declara x0-x15 como "caller-saved": el callee puede usarlos
 *  libremente, de modo que un `bl` clobaría x9..x15 que empleábamos como
 *  scratch.  Al guardar los operandos intermedios en slots del propio frame
 *  (direccionados con x29, que es callee-saved) sobreviven a cualquier bl.
 *
 * ─── Convención de registros ────────────────────────────────────────────────
 *  Resultado entero / bool / char → x0
 *  Resultado cadena               → x0 = puntero,  x1 = longitud en bytes
 *  Argumentos de función          → x0 … x7 (AAPCS64)
 *  Temporales de ops binarias     → [x29 + getScratchSaveOffset(depth)]
 *  x9                             → único temporal de registro (post-carga)
 *  x10                            → temporal para cociente en mod (%)
 * ─────────────────────────────────────────────────────────────────────────────
 */
class ARM64Generator
{
    private ARM64Context $ctx;

    /** @var string[] Líneas de código ensamblador */
    private array $textLines = [];

    /** Indica si el helper __print_int_raw debe emitirse */
    private bool $needsPrintInt  = false;

    /** Indica si el helper __print_bool_raw debe emitirse */
    private bool $needsPrintBool = false;

    /** Indica si el helper __pow_int debe emitirse (potencia entera) */
    private bool $needsPow = false;

    /** Indica si el helper __strlen_setx1 debe emitirse */
    private bool $needsStrlen = false;

    /** Indica si el helper __now debe emitirse */
    private bool $needsNow = false;

    /** Indica si el helper __print_float_raw debe emitirse */
    private bool $needsPrintFloat = false;
    /** Indica si debemos emitir helper para imprimir <nil> */
    private bool $needsPrintNil = false;

    /**
     * Mapa funcName → metadata de retornos.
     *
     * Estructura:
     *   [
     *     'returns' => [ ['tipo' => 'ENTERO', 'dims' => [], 'slots' => 1], ... ],
     *     'primary' => 'ENTERO',
     *     'slots'   => 1,
     *   ]
     */
    private array $funcReturnTypes = [];

    /**
     * Profundidad actual del save-slot de scratch.
     * Cada nivel de expresión binaria usa getScratchSaveOffset($scratchDepth)
     * para salvar el operando izquierdo al frame antes de evaluar el derecho.
     */
    private int $scratchDepth = 0;

    /**
     * Etiqueta del epílogo (punto de retorno) de la función que se está
     * generando actualmente.  `return` salta aquí.
     */
    private string $retLabel = '';

    /**
     * Pila de etiquetas destino para `break`.
     * Se hace push al entrar a un for o switch, pop al salir.
     * @var string[]
     */
    private array $breakLabels = [];

    /**
     * Pila de etiquetas destino para `continue`.
     * Se hace push al entrar a un for (NO a switch), pop al salir.
     * @var string[]
     */
    private array $continueLabels = [];

    public function __construct()
    {
        $this->ctx = new ARM64Context();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Punto de entrada público
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Recibe el array de instrucciones del AST y retorna el fuente ARM64 completo.
     *
     * @param  object[] $instrucciones
     * @return string   Contenido listo para escribir en un archivo .s
     */
    public function generar(array $instrucciones): string
    {
        $this->textLines      = [];
        $this->needsPrintInt   = false;
        $this->needsPrintBool  = false;
        $this->needsPow        = false;
        $this->needsStrlen     = false;
        $this->needsNow        = false;
        $this->needsPrintFloat = false;
        $this->needsPrintNil   = false;

        // ── 1. Hoisting: separar Funcion del resto y construir tabla de retornos ─
        /** @var Funcion[] $funciones */
        $funciones = [];
        $this->funcReturnTypes = [];
        foreach ($instrucciones as $instr) {
            if ($instr instanceof Funcion) {
                $funciones[$instr->nombre] = $instr;
                // Registrar lista completa de retornos y el tipo primario
                $retTipos = $instr->tiposRetorno;
                if (!empty($retTipos)) {
                    $returns = [];
                    foreach ($retTipos as $ret) {
                        $tipo = 'ENTERO';
                        $dims = [];

                        if (is_array($ret)) {
                            $tipo = $this->tipoFromDecl($ret['tipoBase'] ?? null);
                            if (!empty($ret['dimensiones'])) {
                                $dims = $this->extractDims($ret['dimensiones']);
                            }
                        } elseif (is_object($ret)) {
                            $tipo = $this->tipoFromDecl($ret->tipoBase ?? null);
                            if (!empty($ret->dimensiones)) {
                                $dims = $this->extractDims($ret->dimensiones);
                            }
                        }

                        $slots = $this->returnSlotsFromDescriptor($tipo, $dims);
                        $returns[] = [
                            'tipo'  => $tipo,
                            'dims'  => $dims,
                            'slots' => $slots,
                        ];
                    }

                    if (!empty($returns)) {
                        $this->funcReturnTypes[$instr->nombre] = [
                            'returns' => $returns,
                            'primary' => $returns[0]['tipo'],
                            'slots'   => array_sum(array_map(static fn (array $ret): int => (int)($ret['slots'] ?? 1), $returns)),
                        ];
                    }
                }
            }
        }

        // ── 2. Encabezado ─────────────────────────────────────────────────────
        $this->emit('# ════════════════════════════════════════════════════════');
        $this->emit('# Código ARM64 generado por el Compilador Golampi — Fase 5');
        $this->emit('# Arquitectura : AArch64 (ARM64)');
        $this->emit('# Convención   : AAPCS64');
        $this->emit('# Ensamblador  : aarch64-linux-gnu-as programa.s -o programa.o');
        $this->emit('#               aarch64-linux-gnu-ld programa.o -o programa');
        $this->emit('# Ejecución    : qemu-aarch64 ./programa');
        $this->emit('# ════════════════════════════════════════════════════════');
        $this->emitBlank();

        // ── 3. .text ──────────────────────────────────────────────────────────
        $this->emit('.section .text');
        $this->emit('.align 2');
        $this->emit('.global _start');
        $this->emitBlank();

        // ── 4. _start ─────────────────────────────────────────────────────────
        $this->emit('# ── Punto de entrada del ejecutable ────────────────────');
        $this->emit('_start:');
        if (isset($funciones['main'])) {
            $this->emit('    bl      main          # llamar a main()');
        } else {
            $this->emit('    # ADVERTENCIA: función main() no encontrada');
        }
        $this->emit('    mov     x0, #0        # exit(0)');
        $this->emit('    mov     x8, #93       # syscall: exit');
        $this->emit('    svc     #0');
        $this->emitBlank();

        // ── 5. Funciones de usuario (no-main primero) ─────────────────────────
        foreach ($funciones as $nombre => $func) {
            if ($nombre !== 'main') {
                $this->generarFuncion($func);
            }
        }

        // ── 6. Función main ───────────────────────────────────────────────────
        if (isset($funciones['main'])) {
            $this->generarFuncion($funciones['main']);
        }

        // ── 7. Helpers de runtime ─────────────────────────────────────────────
        $this->emitirHelpers();

        // ── 8. .data (strings del programa + constantes de runtime) ──────────
        $this->emitBlank();
        $this->emit('# ── Sección .data ───────────────────────────────────────');
        $this->emit('.section .data');
        $this->emit('.align 3');

        // Constantes de runtime siempre presentes
        $this->emit('msg_nil:      .asciz "<nil>"');
        $this->emit('__str_true:   .ascii "true"');
        $this->emit('__str_false:  .ascii "false"');
        $this->emit('__str_space:  .ascii " "');
        $this->emit('__str_nl:     .ascii "\n"');
        $this->emit('__str_empty:  .ascii "\0"     // cadena vacía por defecto');

        // Strings registrados durante la generación
        foreach ($this->ctx->getDataSection() as $label => $value) {
            $this->emit("{$label}: .ascii {$value}");
        }

        // ── 9. .bss (buffers mutables de runtime) ────────────────────────────
        if ($this->needsNow) {
            $this->emitBlank();
            $this->emit('# ── Sección .bss ────────────────────────────────────────');
            $this->emit('.section .bss');
            $this->emit('.align 3');
            $this->emit('__now_buf: .skip 24    # buffer para timestamp (now())');
        }

        // ── Post-procesado: convertir # comentario → // comentario ──────────────
        // En AArch64 GAS el carácter de comentario es //, no #.
        // El # solo es válido como prefijo de inmediato: #0, #64, #-1, etc.
        // Excepto en directivas .ascii donde el contenido está entre comillas.
        $lines = $this->textLines;
        foreach ($lines as &$ln) {
            if (strpos($ln, '.ascii') === false && strpos($ln, '.byte') === false) {
                // Reemplazar # cuando NO precede a un dígito o a -dígito (inmediatos)
                $ln = preg_replace('/#(?!-?\d)/', '//', $ln);
            }
        }
        unset($ln);

        return implode("\n", $lines);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Generación de funciones
    // ══════════════════════════════════════════════════════════════════════════

    private function generarFuncion(Funcion $func): void
    {
        $nombre = $func->nombre;
        $this->ctx->resetFrame();
        $this->scratchDepth   = 0;
        $this->breakLabels    = [];
        $this->continueLabels = [];

        // Pre-scan: variables del cuerpo + parámetros
        // Todos los parámetros (escalares, arrays por valor o por puntero) = 1 slot de puntero/valor
        $numVars   = $this->contarVariables($func->bloque) + count($func->parametros);
        $frameSize = $this->ctx->calcularFrameSize($numVars);

        // Etiqueta del epílogo (destino de todos los `return`)
        $this->retLabel = $this->ctx->newLabel('ret');

        $sep = str_repeat('─', max(0, 48 - strlen($nombre)));
        $this->emit("# ┌── función: {$nombre} {$sep}┐");
        $this->emit("{$nombre}:");
        $this->emitBlank();

        // ── Prólogo ───────────────────────────────────────────────────────────
        $this->emit('    # Prólogo');
        $this->emit("    sub     sp, sp, #{$frameSize}     # reservar frame ({$frameSize} bytes)");
        $this->emit('    stp     x29, x30, [sp, #0]        # guardar fp y lr');
        $this->emit('    mov     x29, sp                   # fp = frame base');
        $this->emitBlank();

        // ── Parámetros: ligar x0-x7 a slots del frame ─────────────────────────
        if (!empty($func->parametros)) {
            $this->emit('    // Parámetros → frame');
            foreach ($func->parametros as $i => $param) {
                if ($i >= 8) break;
                // Usar el tipo real del parámetro para que generarAccesoID emita FPU correctamente
                $tipoParam = $this->tipoFromDecl($param->tipoBase ?? null);
                if (!empty($param->dimensiones)) {
                    // Array por valor o puntero: recibir como dirección base (1 slot)
                    $dims   = $this->extractDims($param->dimensiones);
                    $offset = $this->ctx->allocVar($param->id, $tipoParam);
                    $this->ctx->markPtrArray($param->id, $dims);
                } else {
                    $offset = $this->ctx->allocVar($param->id, $tipoParam);
                }
                $this->emit("    str     x{$i}, [x29, #{$offset}]  // param {$param->id} ({$tipoParam})");
            }
            $this->emitBlank();
        }

        // ── Cuerpo ────────────────────────────────────────────────────────────
        $this->generarBloque($func->bloque);
        $this->emitBlank();

        // ── Epílogo (destino de todos los return) ─────────────────────────────
        $this->emit("{$this->retLabel}:               # epílogo");
        $this->emit('    # Epílogo');
        $this->emit('    mov     sp, x29               # restaurar sp desde fp');
        $this->emit('    ldp     x29, x30, [sp, #0]    # restaurar fp y lr');
        $this->emit("    add     sp, sp, #{$frameSize} # liberar frame");
        $this->emit('    ret');
        $this->emitBlank();

        $sep2 = str_repeat('─', max(0, 50 - strlen($nombre)));
        $this->emit("# └── fin: {$nombre} {$sep2}┘");
        $this->emitBlank();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Pre-scan: contar variables locales para dimensionar el frame
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Cuenta las variables declaradas en un Bloque y todos sus sub-bloques
     * (incluyendo ramas if/else, cuerpo de for, init de for y casos de switch).
     * Se usa para pre-calcular el frame size antes de emitir el prólogo.
     */
    private function contarVariables(object $bloque): int
    {
        if (!isset($bloque->instrucciones) || !is_array($bloque->instrucciones)) {
            return 0;
        }
        $count = 0;
        foreach ($bloque->instrucciones as $instr) {
            if ($instr !== null) {
                $count += $this->contarEnInstruccion($instr);
            }
        }
        return $count;
    }

    /**
     * Cuenta variables declaradas dentro de una instrucción cualquiera,
     * recursando en sus sub-bloques de manera específica para cada tipo.
     */
    private function contarEnInstruccion(object $instr): int
    {
        // Declaraciones directas
        if ($instr instanceof DeclaracionID) {
            if (is_string($instr->id) && !empty($instr->dimensionesExpr)) {
                return $this->extractDimsProduct($instr->dimensionesExpr);
            }
            if (is_string($instr->id)) {
                // Cadenas desde substr necesitan 2 slots (ptr + len)
                $val = $instr->valor;
                if (is_object($val)) {
                    $vc = basename(str_replace('\\', '/', get_class($val)));
                    if ($val instanceof Llamada && $val->nombre === 'substr') {
                        return 2;
                    }
                }
                return 1;
            }
            return is_array($instr->id) ? count($instr->id) : 1;
        }
        if ($instr instanceof DeclaracionCorta) {
            $total = 0;
            foreach ($instr->ids as $i => $id) {
                $val = $instr->valores[$i] ?? null;
                if ($val instanceof ArregloLiteral && !empty($val->dimensionesExpr)) {
                    $total += $this->extractDimsProduct($val->dimensionesExpr);
                } else {
                    $total += 1;
                }
            }
            return $total;
        }

        // if / else if / else
        if ($instr instanceof Si) {
            $n  = $this->contarVariables($instr->bloqueIf);
            if ($instr->bloqueElse !== null) {
                // bloqueElse puede ser Bloque o Si (else if)
                $n += ($instr->bloqueElse instanceof Bloque)
                    ? $this->contarVariables($instr->bloqueElse)
                    : $this->contarEnInstruccion($instr->bloqueElse);
            }
            return $n;
        }

        // for clásico / while / infinito
        if ($instr instanceof Para) {
            $n = 0;
            if ($instr->init !== null) {
                $n += $this->contarEnInstruccion($instr->init);
            }
            $n += $this->contarVariables($instr->bloque);
            return $n;
        }

        // for rango: 1 slot para el iterador + variables del bloque
        if ($instr instanceof \App\Instructions\ForRango) {
            return 1 + $this->contarVariables($instr->bloque);
        }

        // switch
        if ($instr instanceof Segun) {
            $n = 0;
            foreach ($instr->casos as $caso) {
                $n += $this->contarVariables($caso->bloque);
            }
            if ($instr->bloqueDefault !== null) {
                $n += $this->contarVariables($instr->bloqueDefault);
            }
            return $n;
        }

        // Bloque genérico (p.ej. else { })
        if ($instr instanceof Bloque) {
            return $this->contarVariables($instr);
        }

        // Asignación múltiple que actúa como declaración implícita
        // (ej: var a, b, c = multiReturnFunc() parseado por error-recovery como Asignacion)
        if ($instr instanceof Asignacion && is_array($instr->id) && !is_array($instr->valor)) {
            return count($instr->id);
        }

        return 0;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Bloques e instrucciones
    // ══════════════════════════════════════════════════════════════════════════

    private function generarBloque(object $bloque): void
    {
        if (!isset($bloque->instrucciones) || !is_array($bloque->instrucciones)) {
            return;
        }

        foreach ($bloque->instrucciones as $instr) {
            if ($instr !== null) {
                $this->generarInstruccion($instr);
            }
        }
    }

    private function generarInstruccion(object $instr): void
    {
        $clase = basename(str_replace('\\', '/', get_class($instr)));

        switch ($clase) {
            case 'DeclaracionID':
                $this->generarDeclaracionID($instr);
                break;
            case 'DeclaracionCorta':
                $this->generarDeclaracionCorta($instr);
                break;
            case 'Asignacion':
                $this->generarAsignacion($instr);
                break;
            case 'AsignacionArreglo':
                $this->generarAsignacionArreglo($instr);
                break;
            case 'AsignacionPuntero':
                $this->generarAsignacionPuntero($instr);
                break;
            case 'AsignacionCompuesta':
                $this->generarAsignacionCompuesta($instr);
                break;
            case 'IncDec':
                $this->generarIncDec($instr);
                break;
            case 'Imprimir':
                $this->generarImprimir($instr);
                break;
            case 'Si':
                $this->generarSi($instr);
                break;
            case 'Para':
                $this->generarPara($instr);
                break;
            case 'ForRango':
                $this->generarForRango($instr);
                break;
            case 'Segun':
                $this->generarSegun($instr);
                break;
            case 'Romper':
                $this->generarRomper();
                break;
            case 'Continuar':
                $this->generarContinuar();
                break;
            case 'Retorno':
                $this->generarRetorno($instr);
                break;
            case 'LlamadaInstr':
                $this->generarLlamadaInstr($instr);
                break;
            case 'Bloque':
                $this->generarBloque($instr);
                break;
            case 'Funcion':
                $this->emit("    # [IGNORADO] función anidada: {$instr->nombre}");
                break;
            default:
                $this->emit("    # [TODO] instrucción: {$clase}");
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Declaraciones
    // ══════════════════════════════════════════════════════════════════════════

    private function generarDeclaracionID(object $instr): void
    {
        // Caso 1a: arreglo  →  var arr [N]tipo  o  var arr [N]tipo = [N]tipo{...}
        if (is_string($instr->id) && !empty($instr->dimensionesExpr)) {
            // Intentar extraer dimensiones en tiempo de compilación
            $constDims = $this->extractDimsOrNull($instr->dimensionesExpr);
            $tipo  = $this->tipoFromDecl($instr->tipo);

            if ($constDims !== null) {
                // Dimensiones constantes: reserva estática en el frame
                $dims  = $constDims;
                $total = max(1, (int)array_product($dims));
                $base  = $this->ctx->allocArray($instr->id, $dims, $tipo);
                $this->emit("    # var {$instr->id} [{$this->dimsStr($dims)}]{$tipo} @ [x29, #{$base}] ({$total} slots)");
                if ($instr->valor instanceof ArregloLiteral) {
                    $this->generarInicializarArregloLiteral($base, $dims, $instr->valor);
                } else {
                    $this->emit('    mov     x0, #0             # arreglo → ceros');
                    for ($i = 0; $i < $total; $i++) {
                        $off = $base + $i * 8;
                        $this->emit("    str     x0, [x29, #{$off}]  # {$instr->id}[{$i}] = 0");
                    }
                }
            } else {
                // Dimensiones dinámicas: asignar región runtime en el stack y
                // almacenar su puntero en una variable local (markPtrArray).
                $dimCount = count($instr->dimensionesExpr);
                $ptrSlot  = $this->ctx->allocVar($instr->id, $tipo); // slot para puntero
                // Registrar como ptrArray con placeholders para el número de dimensiones
                $this->ctx->markPtrArray($instr->id, array_fill(0, $dimCount, 1));

                // Calcular producto runtime de dimensiones → x19
                $this->emit('    mov     x19, #1              # producto dims runtime');
                foreach ($instr->dimensionesExpr as $dexpr) {
                    $this->generarExpresion($dexpr);   // → x0
                    $this->emit('    mul     x19, x19, x0');
                }

                // bytes = slots * 8
                $this->emit('    mov     x20, #8');
                $this->emit('    mul     x19, x19, x20       # bytes a reservar');
                // Alinear tamaño a 16 bytes para evitar fallas de bus
                $this->emit('    add     x21, x19, #15');
                $this->emit('    bic     x21, x21, #15      # x21 = align16(x19)');

                // Elegir stack allocation (si pequeño) o malloc (si grande)
                $lblMalloc = $this->ctx->newLabel('malloc_call');
                $lblUseStack = $this->ctx->newLabel('use_stack');
                $this->emit("    mov     x22, #4096");
                $this->emit("    cmp     x21, x22");
                $this->emit("    bge     {$lblMalloc}");
                $this->emit("{$lblUseStack}:");
                $this->emit('    sub     sp, sp, x21');
                $this->emit('    mov     x0, sp');
                $this->emit("    str     x0, [x29, #{$ptrSlot}]  # ptr dinámico {$instr->id} (stack)");
                $this->emit("    b       __after_alloc_{$lblUseStack}");
                $this->emit("{$lblMalloc}:");
                $this->emit('    mov     x0, x21');
                $this->emit('    bl      malloc');
                $this->emit("    str     x0, [x29, #{$ptrSlot}]  # ptr dinámico {$instr->id} (heap)");
                $this->emit("__after_alloc_{$lblUseStack}:");
            }
            return;
        }

        // Caso 1b: una sola variable  →  var x int = expr
        if (is_string($instr->id)) {
            // valor puede ser: Expresion (con init), [] empty array (sin init), o null
            $tipoDecl = $this->tipoFromDecl($instr->tipo);
            $isSubstr = false;
            if ($instr->valor !== null && !is_array($instr->valor)) {
                // Detectar si el valor es substr() para guardar x1 (longitud)
                if (is_object($instr->valor)) {
                    $vc = basename(str_replace('\\', '/', get_class($instr->valor)));
                    $isSubstr = ($vc === 'Llamada' && $instr->valor->nombre === 'substr');
                }
                $tipoDecl = $this->generarExpresion($instr->valor);  // → x0 (y x1 si substr)
            } else {
                $this->emitDefaultValue($tipoDecl);
            }
            $offset = $this->ctx->allocVar($instr->id, $tipoDecl);
            $this->emit("    // var {$instr->id} ({$tipoDecl}) @ [x29, #{$offset}]");
            $this->emit("    str     x0, [x29, #{$offset}]");
            if ($isSubstr && $tipoDecl === 'CADENA') {
                // Guardar x1 (longitud de la subcadena) en el siguiente slot
                $lenOff = $this->ctx->allocVar($instr->id . '__slen', 'ENTERO');
                $this->emit("    str     x1, [x29, #{$lenOff}]  // longitud substr para {$instr->id}");
                $this->ctx->setStrLenSlot($instr->id, $lenOff);
            }
            return;
        }

        // Caso 2: múltiples variables  →  var x, y int = 1, 2
        if (is_array($instr->id)) {
            $ids     = $instr->id;
            $tipoBase = $this->tipoFromDecl(is_array($instr->tipo) ? ($instr->tipo[0] ?? null) : $instr->tipo);

            // Sub-caso: 1 Llamada como valor → múltiple retorno
            if (is_object($instr->valor)) {
                $claseVal = basename(str_replace('\\', '/', get_class($instr->valor)));
                if ($claseVal === 'Llamada') {
                    $this->generarLlamadaExpr($instr->valor);
                    $this->emitRetornoMultipleHaciaIds($ids, $instr->valor->nombre, $tipoBase, false);
                    return;
                }
            }

            $valores = is_array($instr->valor) ? $instr->valor : [];
            for ($i = 0; $i < count($ids); $i++) {
                $nombre = $ids[$i];
                $tipo   = $tipoBase;
                if (isset($valores[$i]) && is_object($valores[$i])) {
                    $tipo = $this->generarExpresion($valores[$i]);  // → x0
                } else {
                    $this->emitDefaultValue($tipo);
                }
                $offset = $this->ctx->allocVar($nombre, $tipo);
                $this->emit("    // var {$nombre} ({$tipo}) @ [x29, #{$offset}]");
                $this->emit("    str     x0, [x29, #{$offset}]");
            }
        }
    }

    private function generarDeclaracionCorta(object $instr): void
    {
        $ids     = $instr->ids;
        $valores = $instr->valores;

        // Detección de retorno de arreglo: 1 id, 1 Llamada que retorna arreglo
        if (count($ids) === 1 && count($valores) === 1) {
            $valor    = $valores[0];
            $claseVal = basename(str_replace('\\', '/', get_class($valor)));
            if ($claseVal === 'Llamada') {
                $retInfo = $this->getFunctionReturnInfo($valor->nombre);
                $retDims = $retInfo['returns'][0]['dims'] ?? [];
                $retTipo = $retInfo['returns'][0]['tipo'] ?? 'ENTERO';
                if (!empty($retDims)) {
                    // Función retorna arreglo: valores vienen en x0..xN-1
                    $this->generarLlamadaExpr($valor);
                    $total  = $this->returnSlotsFromDescriptor($retTipo, $retDims);
                    $base   = $this->ctx->allocArray($ids[0], $retDims, $retTipo);
                    $this->emit("    // {$ids[0]} := [{$this->dimsStr($retDims)}]{$retTipo} @ [x29, #{$base}]");
                    // Preservar los registros de retorno antes de que allocVar cambie el frame
                    // (allocArray ya se hizo, base calculado, ahora guardar desde x0..xN-1)
                    for ($i = 0; $i < min($total, 8); $i++) {
                        $off = $base + $i * 8;
                        // Pero x0..xN-1 ya están en registros; necesitamos stash
                        // Como allocArray no emite código, x0..x(total-1) siguen intactos
                        $this->emit("    str     x{$i}, [x29, #{$off}]  // {$ids[0]}[{$i}]");
                    }
                    return;
                }
            }
        }

        // Detección de retorno múltiple: N ids, 1 valor que es una Llamada a función
        if (count($ids) > 1 && count($valores) === 1) {
            $valor = $valores[0];
            $claseVal = basename(str_replace('\\', '/', get_class($valor)));
            if ($claseVal === 'Llamada') {
                // Llamar a la función — los N valores quedan en x0..xN-1
                $this->generarLlamadaExpr($valor);
                $this->emitRetornoMultipleHaciaIds($ids, $valor->nombre, 'ENTERO', true);
                return;
            }
        }

        for ($i = 0; $i < count($ids); $i++) {
            $nombre = $ids[$i];
            $valor  = $valores[$i] ?? null;

            if ($valor instanceof ArregloLiteral && !empty($valor->dimensionesExpr)) {
                // Arreglo: arr := [N]tipo{...}
                $dims  = $this->extractDims($valor->dimensionesExpr);
                $tipo  = $this->tipoFromTipoEnum($valor->tipoBase);
                $base  = $this->ctx->allocArray($nombre, $dims, $tipo);
                $this->emit("    // {$nombre} := [{$this->dimsStr($dims)}]{$tipo} @ [x29, #{$base}]");
                $this->generarInicializarArregloLiteral($base, $dims, $valor);
            } else {
                $tipo = 'ENTERO';
                if ($valor !== null) {
                    $tipo = $this->generarExpresion($valor);  // → x0
                } else {
                    $this->emit('    mov     x0, #0');
                }
                $offset = $this->ctx->allocVar($nombre, $tipo);
                $this->emit("    // {$nombre} ({$tipo}) := ... @ [x29, #{$offset}]");
                $this->emit("    str     x0, [x29, #{$offset}]");
            }
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Asignación e IncDec
    // ══════════════════════════════════════════════════════════════════════════

    private function generarAsignacion(object $instr): void
    {
        $ids    = is_array($instr->id)    ? $instr->id    : [$instr->id];
        $valores = is_array($instr->valor) ? $instr->valor : [$instr->valor];

        // Retorno múltiple: N ids, 1 valor que es Llamada
        if (count($ids) > 1 && count($valores) === 1) {
            $valor    = $valores[0];
            $claseVal = basename(str_replace('\\', '/', get_class($valor)));
            if ($claseVal === 'Llamada') {
                $this->generarLlamadaExpr($valor);   // bl → x0..xN-1
                $this->emitRetornoMultipleHaciaIds($ids, $valor->nombre, 'ENTERO', false);
                return;
            }
        }

        for ($i = 0; $i < count($ids); $i++) {
            $nombre = $ids[$i];
            $offset = $this->ctx->getVarOffset($nombre);

            if ($offset === null) {
                $this->emit("    // [ERROR] variable '{$nombre}' no encontrada en frame");
                continue;
            }

            if (isset($valores[$i])) {
                $this->generarExpresion($valores[$i]);
            } else {
                $this->emit('    mov     x0, #0');
            }

            $this->emit("    str     x0, [x29, #{$offset}]  // {$nombre} = ...");
        }
    }

    private function generarIncDec(object $instr): void
    {
        $nombre   = $instr->id;
        $operador = $instr->operador;
        $offset   = $this->ctx->getVarOffset($nombre);

        if ($offset === null) {
            $this->emit("    # [ERROR] variable '{$nombre}' no encontrada en frame");
            return;
        }

        $this->emit("    ldr     x0, [x29, #{$offset}]  # cargar {$nombre}");
        if ($operador === '++') {
            $this->emit('    add     x0, x0, #1');
        } else {
            $this->emit('    sub     x0, x0, #1');
        }
        $this->emit("    str     x0, [x29, #{$offset}]  # {$nombre}{$operador}");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Control de flujo — if / else if / else
    // ══════════════════════════════════════════════════════════════════════════

    private function generarSi(object $instr): void
    {
        $hasElse = $instr->bloqueElse !== null;
        $lblEnd  = $this->ctx->newLabel('if_end');
        $lblElse = '';   // se asigna abajo si $hasElse

        // Evaluar condición → x0
        $this->generarExpresion($instr->condicion);

        if ($hasElse) {
            $lblElse = $this->ctx->newLabel('if_else');
            $this->emit("    cbz     x0, {$lblElse}      # si falso → else");
        } else {
            $this->emit("    cbz     x0, {$lblEnd}       # si falso → fin if");
        }

        // Cuerpo del if
        $this->generarBloque($instr->bloqueIf);

        if ($hasElse) {
            $this->emit("    b       {$lblEnd}");
            $this->emit("{$lblElse}:");

            // else puede ser un Bloque normal o un Si (else if)
            if ($instr->bloqueElse instanceof Bloque) {
                $this->generarBloque($instr->bloqueElse);
            } else {
                // else if: recurrir directamente (genera sus propias etiquetas)
                $this->generarSi($instr->bloqueElse);
            }
        }

        $this->emit("{$lblEnd}:");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Control de flujo — for
    // ══════════════════════════════════════════════════════════════════════════

    private function generarPara(object $instr): void
    {
        $lblCond = $this->ctx->newLabel('for_cond');
        $lblPost = $this->ctx->newLabel('for_post');
        $lblEnd  = $this->ctx->newLabel('for_end');

        // Registrar destinos de break y continue para este ciclo
        $this->breakLabels[]    = $lblEnd;
        $this->continueLabels[] = $lblPost;

        // ── init ──────────────────────────────────────────────────────────────
        if ($instr->init !== null) {
            $this->emit('    # for init');
            $this->generarInstruccion($instr->init);
        }

        // ── condición ─────────────────────────────────────────────────────────
        $this->emit("{$lblCond}:");
        if ($instr->condicion !== null) {
            $this->generarExpresion($instr->condicion);
            $this->emit("    cbz     x0, {$lblEnd}       # condición falsa → fin for");
        }

        // ── cuerpo ────────────────────────────────────────────────────────────
        $this->generarBloque($instr->bloque);

        // ── post (i++, i--, etc.) ─────────────────────────────────────────────
        $this->emit("{$lblPost}:");
        if ($instr->post !== null) {
            $this->generarInstruccion($instr->post);
        }
        $this->emit("    b       {$lblCond}             # siguiente iteración");

        // ── fin del for ───────────────────────────────────────────────────────
        $this->emit("{$lblEnd}:");

        array_pop($this->breakLabels);
        array_pop($this->continueLabels);
    }

    private function generarForRango(object $instr): void
    {
        $lblCond = $this->ctx->newLabel('fr_cond');
        $lblPost = $this->ctx->newLabel('fr_post');
        $lblEnd  = $this->ctx->newLabel('fr_end');

        $this->breakLabels[]    = $lblEnd;
        $this->continueLabels[] = $lblPost;

        // Allocate iterator variable slot
        $offset = $this->ctx->allocVar($instr->id, 'ENTERO');

        // Evaluate start → store in iterator slot
        $this->emit("    // for {$instr->id} in range (inclusive)");
        $this->generarExpresion($instr->start);
        $this->emit("    str     x0, [x29, #{$offset}]   // {$instr->id} = start");

        // Condition: load iterator, evaluate end, compare i <= end
        $this->emit("{$lblCond}:");
        $this->generarExpresion($instr->end);
        $this->emit("    mov     x9, x0                  // x9 = end");
        $this->emit("    ldr     x0, [x29, #{$offset}]   // x0 = {$instr->id}");
        $this->emit("    cmp     x0, x9");
        $this->emit("    b.gt    {$lblEnd}                // i > end → salir");

        // Body
        $this->generarBloque($instr->bloque);

        // Post: i++
        $this->emit("{$lblPost}:");
        $this->emit("    ldr     x0, [x29, #{$offset}]   // x0 = {$instr->id}");
        $this->emit("    add     x0, x0, #1              // i++");
        $this->emit("    str     x0, [x29, #{$offset}]   // store {$instr->id}");
        $this->emit("    b       {$lblCond}               // siguiente iteración");

        $this->emit("{$lblEnd}:");

        array_pop($this->breakLabels);
        array_pop($this->continueLabels);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Control de flujo — switch / case / default
    // ══════════════════════════════════════════════════════════════════════════

    private function generarSegun(object $instr): void
    {
        $lblEnd = $this->ctx->newLabel('sw_end');

        // El valor del switch se salva en un slot del frame (no en un registro
        // de scratch) para que sobreviva a cualquier bl dentro de los casos.
        $swSlot = $this->ctx->getScratchSaveOffset($this->scratchDepth);
        $this->scratchDepth++;

        $this->emit('    # switch — evaluar expresión principal');
        $this->generarExpresion($instr->condicionPrincipal);
        $this->emit("    str     x0, [x29, #{$swSlot}]   # guardar valor switch");

        // ── generar saltos de comparación ─────────────────────────────────────
        $lblCasos = [];
        foreach ($instr->casos as $i => $caso) {
            $lblBody = $this->ctx->newLabel('sw_case');
            $lblCasos[] = $lblBody;
            foreach ($caso->condiciones as $cond) {
                $this->generarExpresion($cond);
                $this->emit("    ldr     x9, [x29, #{$swSlot}]  # recargar valor switch");
                $this->emit("    cmp     x9, x0");
                $this->emit("    beq     {$lblBody}");
            }
        }

        // Si ningún caso coincide → default o fin
        $lblDefault = '';   // se asigna abajo si hay default
        if ($instr->bloqueDefault !== null) {
            $lblDefault = $this->ctx->newLabel('sw_default');
            $this->emit("    b       {$lblDefault}");
        } else {
            $this->emit("    b       {$lblEnd}");
        }

        // ── cuerpos de los casos ──────────────────────────────────────────────
        $this->breakLabels[] = $lblEnd;   // break en switch → fin switch

        foreach ($instr->casos as $i => $caso) {
            $this->emit("{$lblCasos[$i]}:");
            $this->generarBloque($caso->bloque);
            $this->emit("    b       {$lblEnd}            # fin caso");
        }

        // ── default ───────────────────────────────────────────────────────────
        if ($instr->bloqueDefault !== null) {
            $this->emit("{$lblDefault}:");
            $this->generarBloque($instr->bloqueDefault);
        }

        $this->emit("{$lblEnd}:");

        array_pop($this->breakLabels);
        $this->scratchDepth--;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Control de flujo — break / continue
    // ══════════════════════════════════════════════════════════════════════════

    private function generarRomper(): void
    {
        if (empty($this->breakLabels)) {
            $this->emit('    # [ERROR] break fuera de ciclo o switch');
            return;
        }
        $lbl = end($this->breakLabels);
        $this->emit("    b       {$lbl}               # break");
    }

    private function generarContinuar(): void
    {
        if (empty($this->continueLabels)) {
            $this->emit('    # [ERROR] continue fuera de ciclo');
            return;
        }
        $lbl = end($this->continueLabels);
        $this->emit("    b       {$lbl}               # continue");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Fase 4 — return
    // ══════════════════════════════════════════════════════════════════════════

    private function generarRetorno(object $instr): void
    {
        $exprs = $instr->expresiones;
        if (empty($exprs)) {
            $this->emit('    mov     x0, #0             // return void');
        } elseif (count($exprs) === 1) {
            $expr     = $exprs[0];
            $claseExp = basename(str_replace('\\', '/', get_class($expr)));
            // Si retornamos un arreglo local: emitir todos sus elementos en x0..xN-1
            if ($claseExp === 'AccesoID') {
                $nombre = $expr->id;
                $dims   = $this->ctx->getArrayDims($nombre);
                if ($dims !== null) {
                    $base  = $this->ctx->getVarOffset($nombre);
                    $total = max(1, (int)array_product($dims));
                    for ($i = 0; $i < min($total, 8); $i++) {
                        $off = $base + $i * 8;
                        $this->emit("    ldr     x{$i}, [x29, #{$off}]  // ret arr[{$i}]");
                    }
                    $this->emit("    b       {$this->retLabel}      // return arreglo");
                    return;
                }
                // Si es ptrArray (arreglo pasado como puntero): re-pasar el puntero en x0..xN
                if ($this->ctx->isPtrArray($nombre)) {
                    $base  = $this->ctx->getVarOffset($nombre);
                    $pDims = $this->ctx->getPtrArrayDims($nombre) ?? [1];
                    $total = max(1, (int)array_product($pDims));
                    $ptr   = $this->ctx->getVarOffset($nombre);
                    $this->emit("    ldr     x9, [x29, #{$ptr}]   // base del arreglo ptr");
                    for ($i = 0; $i < min($total, 8); $i++) {
                        $off = $i * 8;
                        $this->emit("    ldr     x{$i}, [x9, #{$off}]   // ret ptrArr[{$i}]");
                    }
                    $this->emit("    b       {$this->retLabel}      // return ptrArr");
                    return;
                }
            }
            $this->generarExpresion($expr);
        } else {
            // Múltiple retorno: reservar staging y preservar el ancho real de cada expresión.
            $returnMeta = [];
            $totalSlots = 0;
            foreach ($exprs as $expr) {
                $slots = $this->returnSlotsFromReturnExpression($expr);
                $returnMeta[] = ['slots' => $slots];
                $totalSlots += $slots;
            }

            $aligned = (int)(ceil(max(1, $totalSlots) * 8 / 16) * 16);
            $this->emit("    sub     sp, sp, #{$aligned}    // staging retorno múltiple");

            $slotIndex = 0;
            foreach ($exprs as $i => $expr) {
                $slots = $returnMeta[$i]['slots'];
                $this->generarExpresion($expr);
                for ($j = 0; $j < $slots; $j++, $slotIndex++) {
                    $this->emit("    str     x{$j}, [sp, #" . ($slotIndex * 8) . "]  // ret[{$slotIndex}]");
                }
            }

            for ($i = 0; $i < $slotIndex; $i++) {
                $this->emit("    ldr     x{$i}, [sp, #" . ($i * 8) . "]  // x{$i} ← ret[{$i}]");
            }
            $this->emit("    add     sp, sp, #{$aligned}    // liberar staging");
        }
        $this->emit("    b       {$this->retLabel}      // return → epílogo");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Fase 4 — llamadas a función
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * LlamadaInstr envuelve una Llamada (expresión) usada como instrucción.
     * Simplemente delega al generador de expresión y descarta el valor en x0.
     */
    private function generarLlamadaInstr(object $instr): void
    {
        $this->generarLlamadaExpr($instr->llamada);
        // El resultado en x0 se ignora (instrucción, no expresión)
    }

    /**
     * Llamada a función como expresión: evalúa argumentos, los pasa en x0-x7,
     * emite `bl nombre` y deja el valor de retorno en x0.
     *
     * ─── Paso de argumentos ───────────────────────────────────────────────────
     *  1. Se reserva espacio temporal en el stack (alineado a 16 bytes) para
     *     los valores de los argumentos — así el sp dinámico no afecta los
     *     accesos a [x29+offset] de las variables locales durante la evaluación.
     *  2. Cada argumento se evalúa → x0 → [sp + i*8].
     *  3. Se cargan de vuelta en x0..x(n-1).
     *  4. `bl nombre`  (x30 guardado en el frame del callee).
     *  5. Se libera el espacio temporal.
     *
     * Retorna el tipo inferido ('ENTERO' por defecto en Fase 4).
     */
    private function generarLlamadaExpr(object $expr): string
    {
        $nombre = $expr->nombre;
        $args   = $expr->args;
        $n      = count($args);

        // ── Funciones embebidas (Fase 5) ──────────────────────────────────────
        if ($nombre === 'len')    return $this->generarBuiltinLen($args);
        if ($nombre === 'typeOf') return $this->generarBuiltinTypeOf($args);
        if ($nombre === 'now')    return $this->generarBuiltinNow();
        if ($nombre === 'substr') return $this->generarBuiltinSubstr($args);

        if ($n === 0) {
            // Sin argumentos: llamada directa
            $this->emit("    bl      {$nombre}");
            return 'ENTERO';
        }

        // ── Staging area para argumentos ──────────────────────────────────────
        $argSpace = (int)(ceil($n * 8 / 16) * 16);
        $this->emit("    sub     sp, sp, #{$argSpace}    // staging {$n} arg(s)");

        for ($i = 0; $i < $n && $i < 8; $i++) {
            $arg     = $args[$i];
            $byteOff = $i * 8;
            $claseArg = basename(str_replace('\\', '/', get_class($arg)));

            // Para arreglos pasados por valor: pasar dirección base en vez del primer elemento
            if ($claseArg === 'AccesoID') {
                $argNombre = $arg->id;
                $argDims   = $this->ctx->getArrayDims($argNombre);
                if ($argDims !== null) {
                    // Array local: pasar base address
                    $argBase = $this->ctx->getVarOffset($argNombre);
                    $this->emit("    add     x0, x29, #{$argBase}   // &{$argNombre}[0] (arr arg)");
                    $this->emit("    str     x0, [sp, #{$byteOff}]  // staging arg[{$i}]");
                    continue;
                }
                if ($this->ctx->isPtrArray($argNombre)) {
                    // Puntero a arreglo: pasar el puntero mismo
                    $argOff = $this->ctx->getVarOffset($argNombre);
                    $this->emit("    ldr     x0, [x29, #{$argOff}]  // puntero {$argNombre}");
                    $this->emit("    str     x0, [sp, #{$byteOff}]  // staging arg[{$i}]");
                    continue;
                }
            }

            $this->generarExpresion($arg);              // resultado → x0
            $this->emit("    str     x0, [sp, #{$byteOff}]  // staging arg[{$i}]");
        }

        // Cargar en registros de argumento (de mayor a menor para no pisar)
        for ($i = min($n, 8) - 1; $i >= 0; $i--) {
            $byteOff = $i * 8;
            $this->emit("    ldr     x{$i}, [sp, #{$byteOff}]  // arg[{$i}] → x{$i}");
        }

        $this->emit("    bl      {$nombre}");
        $this->emit("    add     sp, sp, #{$argSpace}    // liberar staging");

        $retInfo = $this->getFunctionReturnInfo($nombre);
        return $retInfo['primary'] ?? 'ENTERO';
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Fase 5 — funciones embebidas
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * len(s)  →  x0 = longitud
     *  · Si el arg ya devuelve 'CADENA' (literal o variable CADENA),
     *    la longitud está en x1 por convenio → mov x0, x1.
     *  · De lo contrario (puntero opaco) → __strlen_setx1 + mov x0, x1.
     */
    private function generarBuiltinLen(array $args): string
    {
        if (count($args) !== 1) {
            $this->emit('    mov     x0, #0  # len(): argumento incorrecto');
            return 'ENTERO';
        }

        // Compile-time length for arrays: inspect the argument node directly
        $arg   = $args[0];
        $clase = basename(str_replace('\\', '/', get_class($arg)));
        if ($clase === 'AccesoID') {
            $dims = $this->ctx->getArrayDims($arg->id);
            if ($dims !== null && !empty($dims)) {
                $len = (int)$dims[0];
                $this->emit("    mov     x0, #{$len}         # len(array) = {$len}");
                return 'ENTERO';
            }
        }

        $tipo = $this->generarExpresion($arg);

        if ($tipo === 'CADENA') {
            // x0 = ptr, x1 = len (puesto por el literal o por __strlen_setx1)
            $this->emit('    mov     x0, x1              # len(string)');
        } else {
            // x0 podría ser un puntero a cadena; calculamos strlen en runtime
            $this->emit('    bl      __strlen_setx1      # len(ptr)');
            $this->emit('    mov     x0, x1');
            $this->needsStrlen = true;
        }
        return 'ENTERO';
    }

    /**
     * typeOf(expr)  →  x0 = ptr cadena, x1 = len
     * Evalúa el argumento sólo para inferir el tipo Golampi en tiempo de
     * compilación; descarta el valor y devuelve un literal de texto estático.
     */
    private function generarBuiltinTypeOf(array $args): string
    {
        if (count($args) !== 1) {
            $lbl = $this->ctx->addStringRaw('unknown');
            $this->emit("    adrp    x0, {$lbl}");
            $this->emit("    add     x0, x0, :lo12:{$lbl}");
            $this->emit('    mov     x1, #7');
            return 'CADENA';
        }

        $tipo = $this->generarExpresion($args[0]);  // valor descartado

        $typeStr = match ($tipo) {
            'ENTERO'   => 'int32',
            'DECIMAL'  => 'float32',
            'BOOLEANO' => 'bool',
            'CARACTER' => 'rune',
            'CADENA'   => 'string',
            default    => 'any',
        };

        $lbl = $this->ctx->addStringRaw($typeStr);
        $len = strlen($typeStr);
        $this->emit("    adrp    x0, {$lbl}");
        $this->emit("    add     x0, x0, :lo12:{$lbl}");
        $this->emit("    mov     x1, #{$len}             # typeOf → \"{$typeStr}\"");
        return 'CADENA';
    }

    /**
     * now()  →  x0 = ptr al timestamp como cadena decimal, x1 = len
     * Usa syscall clock_gettime (x8=113) para obtener segundos Unix y los
     * formatea como decimales ASCII en el buffer estático __now_buf.
     */
    private function generarBuiltinNow(): string
    {
        $this->emit('    bl      __now               # now() → x0=ptr, x1=len');
        $this->needsNow    = true;
        $this->needsStrlen = true;   // __now usa el helper interno
        return 'CADENA';
    }

    /**
     * substr(s, inicio, longitud)  →  x0 = ptr + inicio, x1 = longitud
     * Sólo realiza aritmética de punteros; no copia ni agrega terminador.
     * Limitación: si el resultado se almacena en una variable, len() sobre
     * esa variable usará strlen hasta el \0 original (no el largo pedido).
     */
    private function generarBuiltinSubstr(array $args): string
    {
        if (count($args) !== 3) {
            $this->emit('    mov     x0, #0  # substr(): 3 argumentos requeridos');
            $this->emit('    mov     x1, #0');
            return 'CADENA';
        }

        // Guardar operandos en slots de scratch del frame
        $slotPtr   = $this->ctx->getScratchSaveOffset($this->scratchDepth);
        $slotStart = $this->ctx->getScratchSaveOffset($this->scratchDepth + 1);
        $slotLen   = $this->ctx->getScratchSaveOffset($this->scratchDepth + 2);
        $this->scratchDepth += 3;

        // arg0: string → x0 = ptr (x1 = len, ignorado aquí)
        $this->generarExpresion($args[0]);
        $this->emit("    str     x0, [x29, #{$slotPtr}]   # substr: ptr");

        // arg1: inicio → x0 = int
        $this->generarExpresion($args[1]);
        $this->emit("    str     x0, [x29, #{$slotStart}] # substr: inicio");

        // arg2: longitud → x0 = int
        $this->generarExpresion($args[2]);
        $this->emit("    str     x0, [x29, #{$slotLen}]   # substr: longitud");

        // Calcular ptr + inicio, devolver longitud pedida
        $this->emit("    ldr     x0, [x29, #{$slotPtr}]");
        $this->emit("    ldr     x9, [x29, #{$slotStart}]");
        $this->emit('    add     x0, x0, x9               # ptr + inicio');
        $this->emit("    ldr     x1, [x29, #{$slotLen}]   # longitud pedida");

        $this->scratchDepth -= 3;
        return 'CADENA';
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Fase 6 — Arreglos
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * arr[i] o m[i][j] como expresión → x0 = valor
     *
     * 1D: x0 = arr[i]
     *   lsl x9, idx, #3         → byte offset = i*8
     *   add x10, x29, #baseOff  → &arr[0]
     *   ldr x0, [x10, x9]
     *
     * 2D: x0 = m[i][j]  (dims=[R,C])
     *   flat = i*C + j  → byte offset = flat*8
     */
    private function generarAccesoArreglo(object $expr): string
    {
        $nombre  = $expr->id;
        $indices = $expr->expresionesIndices;
        $base    = $this->ctx->getVarOffset($nombre);
        $dims    = $this->ctx->getArrayDims($nombre);
        $tipo    = $this->ctx->getVarType($nombre);

        if ($base === null) {
            $this->emit("    # [ERROR] arreglo '{$nombre}' no en frame");
            $this->emit('    mov     x0, #0');
            return 'DESCONOCIDO';
        }

        // Caso especial: string[i] → acceso de 1 byte al string
        if ($tipo === 'CADENA' && $dims === null && !$this->ctx->isPtrArray($nombre) && count($indices) === 1) {
            $this->generarExpresion($indices[0]);          // i → x0
            $this->emit("    ldr     x9, [x29, #{$base}]   // cargar puntero string {$nombre}");
            $this->emit('    ldrb    w0, [x9, x0]           // byte texto[i]');
            $this->emit('    and     x0, x0, #0xFF          // extender a 64 bits sin signo');
            return 'CARACTER';
        }

        $isPtrArr = $this->ctx->isPtrArray($nombre);
        $dimsCalc = $isPtrArr ? ($this->ctx->getPtrArrayDims($nombre) ?? [1]) : ($dims ?? [1]);

        if (count($indices) === 0) {
            $this->emit('    mov     x0, #0');
        } else {
            $this->emitirOffsetRowMajor($indices, $dimsCalc);
            if ($isPtrArr) {
                $this->emit("    ldr     x10, [x29, #{$base}]   // cargar puntero {$nombre}");
            } else {
                $this->emit("    add     x10, x29, #{$base}      // &{$nombre}[0]");
            }
            $this->emit('    ldr     x0, [x10, x9]           // carga elemento');
        }

        // Para CADENA: x0 = puntero, calcular longitud con __strlen_setx1
        if ($tipo === 'CADENA') {
            $this->emit('    bl      __strlen_setx1      // x1 = strlen(elem)');
            $this->needsStrlen = true;
        }

        return $tipo;
    }

    /**
     * arr[i] = val  o  m[i][j] = val
     */
    private function generarAsignacionArreglo(object $instr): void
    {
        $nombre  = $instr->id;
        $indices = $instr->indices;
        $base    = $this->ctx->getVarOffset($nombre);
        $dims    = $this->ctx->getArrayDims($nombre);

        if ($base === null) {
            $this->emit("    # [ERROR] arreglo '{$nombre}' no en frame");
            return;
        }

        // Evaluar el nuevo valor y guardarlo en scratch
        $slotVal = $this->ctx->getScratchSaveOffset($this->scratchDepth);
        $this->scratchDepth++;

        $this->generarExpresion($instr->valorNuevo);         // val → x0
        $this->emit("    str     x0, [x29, #{$slotVal}]      // save new value");

        $isPtrArr = $this->ctx->isPtrArray($nombre);
        $ptrDims  = $isPtrArr ? ($this->ctx->getPtrArrayDims($nombre) ?? [1]) : ($dims ?? [1]);

        if (count($indices) === 0) {
            $this->emit("    // [TODO] asignacion arreglo sin indices");
        } else {
            $this->emitirOffsetRowMajor($indices, $ptrDims);
            if ($isPtrArr) {
                $this->emit("    ldr     x10, [x29, #{$base}]   // cargar puntero {$nombre}");
            } else {
                $this->emit("    add     x10, x29, #{$base}");
            }
            $this->emit("    ldr     x0, [x29, #{$slotVal}]  // reload value");
            $this->emit('    str     x0, [x10, x9]           // asignacion elemento');
        }

        $this->scratchDepth--;
    }

    /**
     * Emite inicialización de un arreglo literal en el stack frame.
     * Para 1D: valores directos; para 2D: arreglos anidados en row-major order.
     */
    private function generarInicializarArregloLiteral(int $base, array $dims, object $arregloLit): void
    {
        $valoresExpr = $arregloLit->valoresExpr;

        if (count($dims) === 1) {
            for ($i = 0; $i < count($valoresExpr); $i++) {
                $off = $base + $i * 8;
                $this->generarExpresion($valoresExpr[$i]);   // val → x0
                $this->emit("    str     x0, [x29, #{$off}]  # literal[{$i}]");
            }
        } elseif (count($dims) === 2) {
            $cols = (int)$dims[1];
            $row  = 0;
            foreach ($valoresExpr as $rowExpr) {
                if (!is_array($rowExpr)) continue;
                for ($j = 0; $j < count($rowExpr); $j++) {
                    $flat = $row * $cols + $j;
                    $off  = $base + $flat * 8;
                    $this->generarExpresion($rowExpr[$j]);   // val → x0
                    $this->emit("    str     x0, [x29, #{$off}]  # literal[{$row}][{$j}]");
                }
                $row++;
            }
        } elseif (count($dims) === 3) {
            // 3D: dims=[D0, D1, D2], flat = k*D1*D2 + i*D2 + j
            $d1 = (int)$dims[1];
            $d2 = (int)$dims[2];
            $k  = 0;
            foreach ($valoresExpr as $planeExpr) {
                if (!is_array($planeExpr)) continue;
                $i = 0;
                foreach ($planeExpr as $rowExpr) {
                    if (!is_array($rowExpr)) continue;
                    for ($j = 0; $j < count($rowExpr); $j++) {
                        $flat = $k * $d1 * $d2 + $i * $d2 + $j;
                        $off  = $base + $flat * 8;
                        $this->generarExpresion($rowExpr[$j]);
                        $this->emit("    str     x0, [x29, #{$off}]  // literal[{$k}][{$i}][{$j}]");
                    }
                    $i++;
                }
                $k++;
            }
        } else {
            $this->emit('    // [TODO] inicialización literal >3D');
        }
    }

    // ── Helpers de dimensiones ─────────────────────────────────────────────────

    /**
     * Extrae valores enteros de las expresiones de dimensión.
     * Sólo soporta Primitivo(ENTERO); usa 16 como fallback para dimensiones dinámicas.
     */
    private function extractDims(array $dimensionesExpr): array
    {
        $dims = [];
        foreach ($dimensionesExpr as $expr) {
            $valorConst = $this->evaluarEnteroConstante($expr);
            $dims[] = $valorConst !== null ? max(1, $valorConst) : 1;
        }
        return $dims ?: [1];
    }

    /**
     * Intenta extraer dimensiones constantes; retorna null si alguna es dinámica.
     */
    private function extractDimsOrNull(array $dimensionesExpr): ?array
    {
        $dims = [];
        foreach ($dimensionesExpr as $expr) {
            $valorConst = $this->evaluarEnteroConstante($expr);
            if ($valorConst === null) return null;
            $dims[] = max(1, $valorConst);
        }
        return $dims ?: [1];
    }

    /**
     * Evalúa una expresión entera constante usada en dimensiones de arreglos.
     * Solo acepta literales y aritmética simple sobre literales.
     */
    private function evaluarEnteroConstante(object $expr): ?int
    {
        $clase = basename(str_replace('\\', '/', get_class($expr)));

        if ($clase === 'Primitivo' && ($expr->tipo->name ?? '') === 'ENTERO') {
            return (int)$expr->valor;
        }

        if ($clase === 'Aritmetico') {
            $izq = $expr->exp1 !== null ? $this->evaluarEnteroConstante($expr->exp1) : null;
            $der = $this->evaluarEnteroConstante($expr->exp2);

            if ($expr->exp1 === null) {
                return $der !== null ? -$der : null;
            }

            if ($izq === null || $der === null) {
                return null;
            }

            return match ($expr->signo) {
                '+' => $izq + $der,
                '-' => $izq - $der,
                '*' => $izq * $der,
                '/' => $der !== 0 ? intdiv($izq, $der) : null,
                '%' => $der !== 0 ? ($izq % $der) : null,
                default => null,
            };
        }

        return null;
    }

    /** Producto de dimensiones para el pre-scan de variables. */
    private function extractDimsProduct(array $dimensionesExpr): int
    {
        return max(1, (int)array_product($this->extractDims($dimensionesExpr)));
    }

    /** Cadena legible de dimensiones, p.ej. [3] o [2][3]. */
    private function dimsStr(array $dims): string
    {
        return implode('][', $dims);
    }

    /** Devuelve metadata de retorno para una función, con valores por defecto. */
    private function getFunctionReturnInfo(string $nombre): array
    {
        if (!isset($this->funcReturnTypes[$nombre])) {
            return [
                'returns' => [[
                    'tipo'  => 'ENTERO',
                    'dims'  => [],
                    'slots' => 1,
                ]],
                'primary' => 'ENTERO',
                'slots'   => 1,
            ];
        }

        $info = $this->funcReturnTypes[$nombre];
        if (isset($info['returns']) && is_array($info['returns'])) {
            return $info;
        }

        $tipo = $info['tipo'] ?? 'ENTERO';
        $dims = $info['dims'] ?? [];
        $slots = $this->returnSlotsFromDescriptor($tipo, $dims);
        return [
            'returns' => [[
                'tipo'  => $tipo,
                'dims'  => $dims,
                'slots' => $slots,
            ]],
            'primary' => $tipo,
            'slots'   => $slots,
        ];
    }

    /** Devuelve la lista de descriptores de retorno para una función. */
    private function getFunctionReturnDescriptors(string $nombre): array
    {
        return $this->getFunctionReturnInfo($nombre)['returns'] ?? [];
    }

    /**
     * Calcula el ancho de un valor de retorno en registros ARM64.
     * CADENA ocupa 2 (ptr + len); los arreglos ocupan el producto de sus dimensiones.
     */
    private function returnSlotsFromDescriptor(string $tipo, array $dims = []): int
    {
        if ($tipo === 'CADENA') {
            return 2;
        }

        if (!empty($dims)) {
            return max(1, (int)array_product($dims));
        }

        return 1;
    }

    /**
     * Infiere cuántos registros ocupa una expresión al retornar.
     * Se usa en `return a, b, ...` para guardar/restaurar el tuple completo.
     */
    private function returnSlotsFromReturnExpression(object $expr): int
    {
        $clase = basename(str_replace('\\', '/', get_class($expr)));

        if ($clase === 'Llamada') {
            $retInfo = $this->getFunctionReturnInfo($expr->nombre);
            return (int)($retInfo['slots'] ?? 1);
        }

        if ($clase === 'AccesoID') {
            $dims = $this->ctx->getArrayDims($expr->id);
            if ($dims !== null && !empty($dims)) {
                return max(1, (int)array_product($dims));
            }

            if ($this->ctx->getVarType($expr->id) === 'CADENA') {
                return 2;
            }
        }

        if ($clase === 'Primitivo' && ($expr->tipo->name ?? '') === 'CADENA') {
            return 2;
        }

        return 1;
    }

    /**
     * Emite x9 = offset en bytes para un acceso row-major de N dimensiones.
     * Deja el índice evaluado en slots temporales del frame para sobrevivir a BL.
     */
    private function emitirOffsetRowMajor(array $indices, array $dims): void
    {
        $count = count($indices);
        if ($count === 0) {
            $this->emit('    mov     x9, #0');
            return;
        }

        $baseDepth = $this->scratchDepth;
        $this->scratchDepth += $count;

        $slotOffsets = [];
        for ($i = 0; $i < $count; $i++) {
            $slotOffsets[$i] = $this->ctx->getScratchSaveOffset($baseDepth + $i);
            $this->generarExpresion($indices[$i]);
            $this->emit("    str     x0, [x29, #{$slotOffsets[$i]}]  // idx[{$i}]");
        }

        $this->emit('    mov     x9, #0');
        $dimCount = count($dims);
        for ($i = 0; $i < $count; $i++) {
            $factor = 1;
            for ($j = $i + 1; $j < $dimCount; $j++) {
                $factor *= max(1, (int)($dims[$j] ?? 1));
            }

            $this->emit("    ldr     x10, [x29, #{$slotOffsets[$i]}]  // idx[{$i}]");
            if ($factor !== 1) {
                $this->emit("    mov     x11, #{$factor}");
                $this->emit('    mul     x10, x10, x11             // idx * factor');
            }
            $this->emit('    add     x9, x9, x10              // acumulado row-major');
        }

        $this->scratchDepth = $baseDepth;
        $this->emit('    lsl     x9, x9, #3              // bytes = idx * 8');
    }

    /**
     * Guarda los registros de retorno de una llamada en los IDs destino.
     * Si un retorno es string, solo persiste el puntero en la variable.
     */
    private function emitRetornoMultipleHaciaIds(array $ids, string $funcName, string $fallbackTipo, bool $allowAllocateMissing): void
    {
        $retInfo = $this->getFunctionReturnInfo($funcName);
        $returns = $retInfo['returns'] ?? [];
        $totalSlots = max(1, (int)($retInfo['slots'] ?? 1));
        $aligned = (int)(ceil($totalSlots * 8 / 16) * 16);
        $this->emit("    sub     sp, sp, #{$aligned}    // staging retorno múltiple");

        for ($i = 0; $i < $totalSlots; $i++) {
            if ($i >= 8) {
                break;
            }
            $this->emit("    str     x{$i}, [sp, #" . ($i * 8) . "]  // save ret[{$i}]");
        }

        $srcIndex = 0;
        foreach ($ids as $i => $nombre) {
            $desc = $returns[$i] ?? ['tipo' => $fallbackTipo, 'dims' => [], 'slots' => 1];
            $tipo = $desc['tipo'] ?? $fallbackTipo;
            $dims = $desc['dims'] ?? [];
            $slots = max(1, (int)($desc['slots'] ?? 1));

            $offset = $this->ctx->getVarOffset($nombre);
            if ($offset === null && $allowAllocateMissing) {
                if (!empty($dims)) {
                    $offset = $this->ctx->allocArray($nombre, $dims, $tipo);
                } else {
                    $offset = $this->ctx->allocVar($nombre, $tipo);
                }
            } elseif ($offset === null) {
                $offset = $this->ctx->allocVar($nombre, $tipo);
            }

            if (!empty($dims)) {
                $baseOffset = $offset;
                for ($j = 0; $j < $slots && ($srcIndex + $j) < $totalSlots; $j++) {
                    $this->emit("    ldr     x0, [sp, #" . (($srcIndex + $j) * 8) . "]  // ret[" . ($srcIndex + $j) . "]");
                    $this->emit("    str     x0, [x29, #" . ($baseOffset + ($j * 8)) . "]  // {$nombre}[{$j}]");
                }
            } else {
                $this->emit("    ldr     x0, [sp, #" . ($srcIndex * 8) . "]  // ret[{$srcIndex}]");
                $this->emit("    str     x0, [x29, #{$offset}]  // {$nombre}");
            }

            $srcIndex += $slots;
        }

        $this->emit("    add     sp, sp, #{$aligned}    // liberar staging");
    }

    /**
     * Emite la carga de una constante float64 en x0 usando movz/movk.
     * Los bits se extraen del valor IEEE 754 double de PHP (endian nativo = LE).
     */
    private function emitFloat64Const(float $value): void
    {
        $bytes = pack('d', $value);           // 8 bytes little-endian (x86/ARM64 LE)
        $words = unpack('v4', $bytes);        // 4 × uint16 LE: words[1]=b15:0 … words[4]=b63:48
        $w0 = $words[1]; $w1 = $words[2]; $w2 = $words[3]; $w3 = $words[4];

        $this->emit(sprintf('    movz    x0, #0x%04X         // float64 bits', $w0));
        if ($w1) $this->emit(sprintf('    movk    x0, #0x%04X, lsl #16', $w1));
        if ($w2) $this->emit(sprintf('    movk    x0, #0x%04X, lsl #32', $w2));
        if ($w3) $this->emit(sprintf('    movk    x0, #0x%04X, lsl #48', $w3));
    }

    /** Convierte el campo $tipo de DeclaracionID a string Golampi. */
    private function tipoFromDecl(mixed $tipo): string
    {
        if ($tipo === null) return 'ENTERO';
        $name = is_object($tipo) ? ($tipo->name ?? '') : (string)$tipo;
        return match ($name) {
            'DECIMAL'  => 'DECIMAL',
            'BOOLEANO' => 'BOOLEANO',
            'CARACTER' => 'CARACTER',
            'CADENA'   => 'CADENA',
            default    => 'ENTERO',
        };
    }

    /** Convierte un enum Tipo a string Golampi. */
    private function tipoFromTipoEnum(mixed $tipo): string
    {
        return $this->tipoFromDecl($tipo);
    }

    /**
     * Emite el valor por defecto de un tipo en x0.
     * CADENA: puntero a __str_empty (cadena vacía nul-terminada).
     * DECIMAL/ENTERO/BOOLEANO/CARACTER: 0.
     */
    private function emitDefaultValue(string $tipo): void
    {
        if ($tipo === 'CADENA') {
            $this->emit('    adrp    x0, __str_empty');
            $this->emit('    add     x0, x0, :lo12:__str_empty');
        } else {
            $this->emit("    mov     x0, #0             // default {$tipo}");
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // fmt.Println / fmt.Print
    // ══════════════════════════════════════════════════════════════════════════

    private function generarImprimir(object $instr): void
    {
        $exprs = $instr->expresiones;
        $count = count($exprs);

        $this->emit('    # fmt.Println(...)');

        for ($i = 0; $i < $count; $i++) {
            $expr = $exprs[$i];
            $tipo = $this->generarExpresion($expr);

            $claseExpr = is_object($expr) ? basename(str_replace('\\', '/', get_class($expr))) : '';

            switch ($tipo) {
                case 'CADENA':
                    // x0 = ptr, x1 = len  →  write(1, ptr, len)
                    // nil se resuelve justo antes de preparar la syscall.
                    $lblReal = $this->ctx->newLabel('print_real');
                    $lblWrite = $this->ctx->newLabel('print_write');
                    $this->emit('    cmp     x0, #0');
                    $this->emit('    b.ne    ' . $lblReal);
                    $this->emit('    adrp    x1, msg_nil');
                    $this->emit('    add     x1, x1, :lo12:msg_nil');
                    $this->emit('    mov     x2, #5');
                    $this->emit('    b       ' . $lblWrite);
                    $this->emit($lblReal . ':');
                    $this->emit('    mov     x2, x1          # longitud');
                    $this->emit('    mov     x1, x0          # puntero');
                    $this->emit($lblWrite . ':');
                    $this->emit('    mov     x0, #1          # stdout');
                    $this->emit('    mov     x8, #64         # syscall write');
                    $this->emit('    svc     #0');
                    $this->needsPrintNil = true;
                    break;

                case 'NIL':
                    $this->emit('    adrp    x1, msg_nil');
                    $this->emit('    add     x1, x1, :lo12:msg_nil');
                    $this->emit('    mov     x2, #5          # len("<nil>")');
                    $this->emit('    mov     x0, #1          # stdout');
                    $this->emit('    mov     x8, #64         # syscall write');
                    $this->emit('    svc     #0');
                    $this->needsPrintNil = true;
                    break;

                case 'ENTERO':
                case 'CARACTER':
                    $this->emit('    bl      __print_int_raw');
                    $this->needsPrintInt = true;
                    break;

                case 'BOOLEANO':
                    $this->emit('    bl      __print_bool_raw');
                    $this->needsPrintBool = true;
                    break;

                case 'DECIMAL':
                    $this->emit('    bl      __print_float_raw');
                    $this->needsPrintFloat = true;
                    $this->needsPrintInt   = true;
                    break;

                default:
                    $this->emit("    # [TODO] imprimir tipo: {$tipo}");
            }

            // Espacio entre argumentos (estilo fmt.Println de Go)
            if ($i < $count - 1) {
                $this->emit('    mov     x0, #1');
                $this->emit('    adrp    x1, __str_space');
                $this->emit('    add     x1, x1, :lo12:__str_space');
                $this->emit('    mov     x2, #1');
                $this->emit('    mov     x8, #64');
                $this->emit('    svc     #0');
            }
        }

        // Salto de línea solo para fmt.Println
        if ($instr->esNewLine) {
            $this->emit('    mov     x0, #1');
            $this->emit('    adrp    x1, __str_nl');
            $this->emit('    add     x1, x1, :lo12:__str_nl');
            $this->emit('    mov     x2, #1');
            $this->emit('    mov     x8, #64');
            $this->emit('    svc     #0');
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Asignación compuesta  (+=, -=, *=, /=, %=)
    // ══════════════════════════════════════════════════════════════════════════

    private function generarAsignacionCompuesta(object $instr): void
    {
        $nombre = $instr->id;
        $op     = $instr->operador;
        $offset = $this->ctx->getVarOffset($nombre);

        if ($offset === null) {
            $this->emit("    # [ERROR] variable '{$nombre}' no encontrada en frame");
            return;
        }

        // 1. Evaluar RHS → x0
        $tipoRHS = 'ENTERO';
        if ($instr->expresion !== null) {
            $tipoRHS = $this->generarExpresion($instr->expresion);
        } else {
            $this->emit('    mov     x0, #1');
        }

        // 2. Cargar valor actual → x9
        $tipoVar  = $this->ctx->getVarType($nombre);
        $isFloat  = ($tipoVar === 'DECIMAL' || $tipoRHS === 'DECIMAL');
        $this->emit("    ldr     x9, [x29, #{$offset}]  // cargar {$nombre}");

        if ($isFloat) {
            // Operandos: x0=RHS bits, x9=var bits → FPU
            if ($tipoRHS === 'DECIMAL') $this->emit('    fmov    d0, x0            // RHS float → d0');
            else                        $this->emit('    scvtf   d0, x0            // RHS int→float');
            if ($tipoVar === 'DECIMAL') $this->emit('    fmov    d1, x9            // var float → d1');
            else                        $this->emit('    scvtf   d1, x9            // var int→float');
            switch ($op) {
                case '+=': $this->emit('    fadd    d0, d1, d0'); break;
                case '-=': $this->emit('    fsub    d0, d1, d0'); break;
                case '*=': $this->emit('    fmul    d0, d1, d0'); break;
                case '/=': $this->emit('    fdiv    d0, d1, d0'); break;
                default:   $this->emit("    // [TODO] Compuesta float {$op}"); break;
            }
            $this->emit('    fmov    x0, d0            // resultado → x0');
        } else {
            switch ($op) {
                case '+=': $this->emit('    add     x0, x9, x0'); break;
                case '-=': $this->emit('    sub     x0, x9, x0'); break;
                case '*=': $this->emit('    mul     x0, x9, x0'); break;
                case '/=': $this->emit('    sdiv    x0, x9, x0'); break;
                case '%=':
                    $this->emit('    sdiv    x10, x9, x0');
                    $this->emit('    msub    x0,  x10, x0, x9');
                    break;
                default:
                    $this->emit("    // [TODO] AsignacionCompuesta: {$op}");
                    $this->emit('    mov     x0, x9');
            }
        }

        $this->emit("    str     x0, [x29, #{$offset}]  // {$nombre} {$op} ...");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Expresiones — resultado en x0 (cadenas: x0=ptr, x1=len)
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Genera código para una expresión.
     * Retorna el nombre del tipo Golampi: 'ENTERO', 'CADENA', 'BOOLEANO', etc.
     */
    private function generarExpresion(object $expr): string
    {
        $clase = basename(str_replace('\\', '/', get_class($expr)));

        switch ($clase) {
            case 'Primitivo':
                return $this->generarPrimitivo($expr);
            case 'AccesoID':
                return $this->generarAccesoID($expr);
            case 'Aritmetico':
                return $this->generarAritmetico($expr);
            case 'Relacional':
                return $this->generarRelacional($expr);
            case 'Rango':
                return $this->generarRango($expr);
            case 'Logico':
                return $this->generarLogico($expr);
            case 'Llamada':
                return $this->generarLlamadaExpr($expr);
            case 'AccesoArreglo':
                return $this->generarAccesoArreglo($expr);
            case 'Casteo':
                return $this->generarCasteo($expr);
            case 'Referencia':
                return $this->generarReferencia($expr);
            case 'Desreferencia':
                return $this->generarDesreferencia($expr);
            default:
                $this->emit("    # [TODO expr] {$clase}");
                $this->emit('    mov     x0, #0');
                return 'DESCONOCIDO';
        }
    }

    private function generarPrimitivo(object $expr): string
    {
        $tipo  = $expr->tipo->name ?? 'DESCONOCIDO';
        $valor = $expr->valor;

        switch ($tipo) {
            case 'ENTERO':
                $v = (int)$valor;
                $this->emitMovImm($v);
                return 'ENTERO';

            case 'DECIMAL':
                // Carga IEEE 754 double bits en x0 (FPU real)
                $this->emitFloat64Const((float)$valor);
                return 'DECIMAL';

            case 'BOOLEANO':
                $b = ((string)$valor === 'true' || (string)$valor === 'verdadero') ? 1 : 0;
                $this->emit("    mov     x0, #{$b}          # bool {$valor}");
                return 'BOOLEANO';

            case 'CARACTER':
                $code = ord(substr((string)$valor, 0, 1));
                $this->emit("    mov     x0, #{$code}        # char '{$valor}'");
                return 'CARACTER';

            case 'CADENA':
                $raw   = (string)$valor;
                $label = $this->ctx->addStringRaw($raw);
                $len   = strlen($raw);
                $this->emit("    adrp    x0, {$label}");
                $this->emit("    add     x0, x0, :lo12:{$label}");
                $this->emit("    mov     x1, #{$len}         # longitud cadena");
                return 'CADENA';

            case 'NIL':
                $this->emit('    mov     x0, #0');
                return 'NIL';

            default:
                $this->emit('    mov     x0, #0');
                return 'DESCONOCIDO';
        }
    }

    private function generarAccesoID(object $expr): string
    {
        $nombre = $expr->id;
        $offset = $this->ctx->getVarOffset($nombre);

        if ($offset === null) {
            $this->emit("    # [ERROR] '{$nombre}' no encontrada en frame local");
            $this->emit('    mov     x0, #0');
            return 'DESCONOCIDO';
        }

        $tipo = $this->ctx->getVarType($nombre);
        $this->emit("    ldr     x0, [x29, #{$offset}]  // acceso {$nombre}");

        if ($tipo === 'CADENA') {
            $lenSlot = $this->ctx->getStrLenSlot($nombre);
            if ($lenSlot !== null) {
                // Longitud almacenada (p.ej. de substr) — usar en lugar de strlen
                $this->emit("    ldr     x1, [x29, #{$lenSlot}]  // len guardada para {$nombre}");
            } else {
                $this->emit('    bl      __strlen_setx1      // x1 = len(string)');
                $this->needsStrlen = true;
            }
        }

        return $tipo;
    }

    private function generarCasteo(object $expr): string
    {
        $tipoDestino = $this->tipoFromDecl($expr->tipoDestino);
        $tipoFuente  = $this->generarExpresion($expr->expresion);  // valor en x0

        if ($tipoFuente === 'DECIMAL' && ($tipoDestino === 'ENTERO' || $tipoDestino === 'CARACTER')) {
            $this->emit('    fmov    d0, x0         // float bits → FPU');
            $this->emit('    fcvtzs  x0, d0         // float → int (truncar)');
        } elseif (($tipoFuente === 'ENTERO' || $tipoFuente === 'CARACTER') && $tipoDestino === 'DECIMAL') {
            $this->emit('    scvtf   d0, x0         // int → float');
            $this->emit('    fmov    x0, d0         // float bits → x0');
        }
        // CARACTER ↔ ENTERO: mismos bits, sin conversión necesaria

        return $tipoDestino;
    }

    /**
     * &variable  →  x0 = dirección en stack del slot de la variable.
     * Para arreglos pasados por puntero: x0 = base del arreglo en el frame.
     */
    private function generarReferencia(object $expr): string
    {
        $nombre = $expr->id;
        $offset = $this->ctx->getVarOffset($nombre);
        if ($offset === null) {
            $this->emit("    // [ERROR] &{$nombre}: variable no encontrada");
            $this->emit('    mov     x0, #0');
            return 'ENTERO';
        }
        // add x0, x29, #offset  →  dirección del slot
        $this->emit("    add     x0, x29, #{$offset}   // &{$nombre}");
        return 'ENTERO';  // puntero tratado como entero opaco
    }

    /**
     * *puntero  →  x0 = valor apuntado (ldr x0, [ptr]).
     * La variable local contiene la dirección (pasada como argumento).
     */
    private function generarDesreferencia(object $expr): string
    {
        $nombre = $expr->id;
        $offset = $this->ctx->getVarOffset($nombre);
        if ($offset === null) {
            $this->emit("    // [ERROR] *{$nombre}: variable no encontrada");
            $this->emit('    mov     x0, #0');
            return 'ENTERO';
        }
        $tipo = $this->ctx->getVarType($nombre) ?? 'ENTERO';
        $this->emit("    ldr     x9, [x29, #{$offset}]  // cargar puntero {$nombre}");
        $this->emit('    ldr     x0, [x9]               // desreferenciar *' . $nombre);
        return $tipo;
    }

    /**
     * *puntero = valor  →  guarda el valor en la dirección apuntada.
     */
    private function generarAsignacionPuntero(object $instr): void
    {
        $nombre = $instr->idPuntero;
        $offset = $this->ctx->getVarOffset($nombre);
        if ($offset === null) {
            $this->emit("    // [ERROR] *{$nombre}: puntero no encontrado");
            return;
        }
        $this->generarExpresion($instr->valor);              // valor → x0
        $this->emit("    ldr     x9, [x29, #{$offset}]  // cargar puntero {$nombre}");
        $this->emit('    str     x0, [x9]               // *' . $nombre . ' = valor');
    }

    private function generarAritmetico(object $expr): string
    {
        $signo = $expr->signo;

        // Negación unaria (exp1 == null)
        if ($expr->exp1 === null) {
            $tipo = $this->generarExpresion($expr->exp2);
            if ($tipo === 'DECIMAL') {
                $this->emit('    fmov    d0, x0');
                $this->emit('    fneg    d0, d0');
                $this->emit('    fmov    x0, d0');
            } else {
                $this->emit('    neg     x0, x0');
            }
            return $tipo;
        }

        // Binario: el operando izquierdo se salva en un slot del frame
        // (no en un registro de scratch) para sobrevivir a un bl en el lado derecho.
        $slot = $this->ctx->getScratchSaveOffset($this->scratchDepth);
        $this->scratchDepth++;

        $tipoL = $this->generarExpresion($expr->exp1);
        $this->emit("    str     x0, [x29, #{$slot}]   # left → slot");

        $tipoR = $this->generarExpresion($expr->exp2);
        // slot=left (frame),  x0=right

        $this->scratchDepth--;
        $isFloat = ($tipoL === 'DECIMAL' || $tipoR === 'DECIMAL');

        if ($isFloat) {
            // Operación FPU: mover operandos a d0 (right) y d1 (left)
            if ($tipoR === 'DECIMAL') {
                $this->emit('    fmov    d0, x0            // right float → d0');
            } else {
                $this->emit('    scvtf   d0, x0            // right int→float');
            }
            $this->emit("    ldr     x9, [x29, #{$slot}]   // left bits");
            if ($tipoL === 'DECIMAL') {
                $this->emit('    fmov    d1, x9            // left float → d1');
            } else {
                $this->emit('    scvtf   d1, x9            // left int→float');
            }
            switch ($signo) {
                case '+': $this->emit('    fadd    d0, d1, d0'); break;
                case '-': $this->emit('    fsub    d0, d1, d0'); break;
                case '*': $this->emit('    fmul    d0, d1, d0'); break;
                case '/': $this->emit('    fdiv    d0, d1, d0'); break;
                default:  $this->emit("    // [TODO] float op: {$signo}");
            }
            $this->emit('    fmov    x0, d0            // resultado → x0');
            return 'DECIMAL';
        }

        $this->emit("    ldr     x9, [x29, #{$slot}]   // x9 ← left");

        switch ($signo) {
            case '+':
                if ($tipoL === 'CADENA' || $tipoR === 'CADENA') {
                    $this->emit('    // [TODO] concatenación de cadenas');
                    return 'CADENA';
                }
                $this->emit('    add     x0, x9, x0');
                break;
            case '-':
                $this->emit('    sub     x0, x9, x0');
                break;
            case '*':
                $this->emit('    mul     x0, x9, x0');
                break;
            case '/':
                $this->emit('    sdiv    x0, x9, x0');
                break;
            case '%':
                $this->emit('    sdiv    x10, x9, x0');
                $this->emit('    msub    x0,  x10, x0, x9   // x9 mod x0');
                break;
            case '^':
                // x9=base, x0=exp → __pow_int(x0=base, x1=exp)
                $this->emit('    mov     x1, x0            // exponente');
                $this->emit('    mov     x0, x9            // base');
                $this->emit('    bl      __pow_int');
                $this->needsPow = true;
                break;
            default:
                $this->emit("    // [TODO] operador aritmético: {$signo}");
        }

        return 'ENTERO';
    }

    private function generarRelacional(object $expr): string
    {
        $slot = $this->ctx->getScratchSaveOffset($this->scratchDepth);
        $this->scratchDepth++;

        $tipoL = $this->generarExpresion($expr->exp1);
        $this->emit("    str     x0, [x29, #{$slot}]   // left → slot");

        $tipoR = $this->generarExpresion($expr->exp2);

        $this->scratchDepth--;

        $cond = match ($expr->signo) {
            '==' => 'eq',
            '!=' => 'ne',
            '<'  => 'lt',
            '<=' => 'le',
            '>'  => 'gt',
            '>=' => 'ge',
            default => 'eq',
        };

        if ($tipoL === 'DECIMAL' || $tipoR === 'DECIMAL') {
            // Comparación FPU
            $this->emit('    fmov    d0, x0            // right float');
            $this->emit("    ldr     x9, [x29, #{$slot}]");
            $this->emit('    fmov    d1, x9            // left float');
            $this->emit('    fcmp    d1, d0');
        } else {
            $this->emit("    ldr     x9, [x29, #{$slot}]   // x9 ← left");
            $this->emit('    cmp     x9, x0');
        }

        $this->emit("    cset    x0, {$cond}           // {$expr->signo} → 0/1");
        return 'BOOLEANO';
    }

    private function generarRango(object $expr): string
    {
        $lblFalse = $this->ctx->newLabel('rng_false');
        $lblEnd   = $this->ctx->newLabel('rng_end');

        $slot = $this->ctx->getScratchSaveOffset($this->scratchDepth);
        $this->scratchDepth++;

        // 1. Evaluar el valor a comparar → guardarlo en slot
        $this->generarExpresion($expr->valor);
        $this->emit("    str     x0, [x29, #{$slot}]   // valor del rango");

        // 2. Comparar valor >= inicio
        $this->generarExpresion($expr->inicio);
        $this->emit("    ldr     x9, [x29, #{$slot}]   // x9 = valor");
        $this->emit("    cmp     x9, x0                 // valor vs inicio");
        $this->emit("    b.lt    {$lblFalse}             // valor < inicio → false");

        // 3. Comparar valor <= fin
        $this->generarExpresion($expr->fin);
        $this->emit("    ldr     x9, [x29, #{$slot}]   // x9 = valor");
        $this->emit("    cmp     x9, x0                 // valor vs fin");
        $this->emit("    b.gt    {$lblFalse}             // valor > fin → false");

        // 4. Está en rango → true
        $this->emit("    mov     x0, #1");
        $this->emit("    b       {$lblEnd}");
        $this->emit("{$lblFalse}:");
        $this->emit("    mov     x0, #0");
        $this->emit("{$lblEnd}:");

        // 5. Invertir si es "not in"
        if ($expr->negado) {
            $this->emit("    eor     x0, x0, #1         // not in → invertir");
        }

        $this->scratchDepth--;
        return 'BOOLEANO';
    }

    private function generarLogico(object $expr): string
    {
        $signo = $expr->signo;

        // NOT unario
        if ($signo === '!' || $expr->exp1 === null) {
            $this->generarExpresion($expr->exp2);
            $this->emit('    cmp     x0, #0');
            $this->emit('    cset    x0, eq           # !bool');
            return 'BOOLEANO';
        }

        // AND / OR binario con cortocircuito real
        $labelFalse = $this->ctx->newLabel($signo === '&&' ? 'and_false' : 'or_false');
        $labelTrue  = $this->ctx->newLabel($signo === '&&' ? 'and_true' : 'or_true');
        $labelEnd   = $this->ctx->newLabel($signo === '&&' ? 'and_end' : 'or_end');

        $this->generarExpresion($expr->exp1);
        $this->emit('    cmp     x0, #0');

        if ($signo === '&&') {
            $this->emit("    beq     {$labelFalse}      // left == false → short-circuit");
            $this->generarExpresion($expr->exp2);
            $this->emit('    cmp     x0, #0');
            $this->emit('    cset    x0, ne              // right != 0');
            $this->emit("    b       {$labelEnd}");
            $this->emit("{$labelFalse}:");
            $this->emit('    mov     x0, #0');
            $this->emit("{$labelEnd}:");
        } else {
            $this->emit("    bne     {$labelTrue}       // left != false → short-circuit");
            $this->generarExpresion($expr->exp2);
            $this->emit('    cmp     x0, #0');
            $this->emit('    cset    x0, ne              // right != 0');
            $this->emit("    b       {$labelEnd}");
            $this->emit("{$labelTrue}:");
            $this->emit('    mov     x0, #1');
            $this->emit("{$labelEnd}:");
        }

        return 'BOOLEANO';
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Helpers de runtime embebidos
    // ══════════════════════════════════════════════════════════════════════════

    private function emitirHelpers(): void
    {
        // ── __print_int_raw ───────────────────────────────────────────────────
        // Entrada : x0  = valor int64 a imprimir
        // Efecto  : escribe los dígitos decimales en stdout (sin newline)
        // Altera  : x0..x14 (caller-saved según AAPCS64)
        if ($this->needsPrintInt) {
            $this->emitBlank();
            $this->emit('# ── __print_int_raw : int64 en x0 → dígitos en stdout ──');
            $this->emit('__print_int_raw:');
            $this->emit('    sub     sp,  sp,  #48');
            $this->emit('    stp     x29, x30, [sp, #0]');
            $this->emit('    mov     x29, sp');
            $this->emitBlank();
            $this->emit('    mov     x9,  x0          # valor a convertir');
            $this->emit('    mov     x10, #10         # divisor decimal');
            $this->emitBlank();
            $this->emit('    # Manejar signo negativo');
            $this->emit('    cmp     x9,  #0');
            $this->emit('    bge     __pi_positive');
            $this->emit('    mov     w11, #45         # ASCII \'-\'');
            $this->emit('    strb    w11, [sp, #16]');
            $this->emit('    mov     x0,  #1          # stdout');
            $this->emit('    add     x1,  sp,  #16');
            $this->emit('    mov     x2,  #1');
            $this->emit('    mov     x8,  #64         # write');
            $this->emit('    svc     #0');
            $this->emit('    neg     x9,  x9');
            $this->emitBlank();
            $this->emit('__pi_positive:');
            $this->emit('    add     x11, sp,  #44    # fin del buffer de dígitos');
            $this->emit('    mov     x12, #0          # contador de dígitos');
            $this->emitBlank();
            $this->emit('    # Caso especial: valor == 0');
            $this->emit('    cbnz    x9,  __pi_loop');
            $this->emit('    mov     w13, #48         # ASCII \'0\'');
            $this->emit('    sub     x11, x11, #1');
            $this->emit('    strb    w13, [x11]');
            $this->emit('    add     x12, x12, #1');
            $this->emit('    b       __pi_write');
            $this->emitBlank();
            $this->emit('__pi_loop:');
            $this->emit('    cbz     x9,  __pi_write');
            $this->emit('    udiv    x13, x9,  x10    # cociente');
            $this->emit('    msub    x14, x13, x10, x9 # resto = x9 - x13*10');
            $this->emit('    add     x14, x14, #48    # dígito → ASCII');
            $this->emit('    sub     x11, x11, #1');
            $this->emit('    strb    w14, [x11]');
            $this->emit('    add     x12, x12, #1');
            $this->emit('    mov     x9,  x13');
            $this->emit('    b       __pi_loop');
            $this->emitBlank();
            $this->emit('__pi_write:');
            $this->emit('    mov     x0,  #1          # stdout');
            $this->emit('    mov     x1,  x11         # inicio de dígitos');
            $this->emit('    mov     x2,  x12         # número de dígitos');
            $this->emit('    mov     x8,  #64');
            $this->emit('    svc     #0');
            $this->emitBlank();
            $this->emit('    ldp     x29, x30, [sp, #0]');
            $this->emit('    add     sp,  sp,  #48');
            $this->emit('    ret');
            $this->emitBlank();
        }

        // ── __print_bool_raw ──────────────────────────────────────────────────
        // Entrada : x0 = 0 → "false",  x0 != 0 → "true"
        // Efecto  : escribe el texto en stdout (sin newline)
        if ($this->needsPrintBool) {
            $this->emit('# ── __print_bool_raw : bool en x0 → "true"/"false" en stdout ──');
            $this->emit('__print_bool_raw:');
            $this->emit('    sub     sp,  sp,  #16');
            $this->emit('    stp     x29, x30, [sp, #0]');
            $this->emit('    mov     x29, sp');
            $this->emit('    cmp     x0,  #0');
            $this->emit('    beq     __pb_false');
            $this->emit('    adrp    x1,  __str_true');
            $this->emit('    add     x1,  x1, :lo12:__str_true');
            $this->emit('    mov     x2,  #4          # len("true")');
            $this->emit('    b       __pb_write');
            $this->emit('__pb_false:');
            $this->emit('    adrp    x1,  __str_false');
            $this->emit('    add     x1,  x1, :lo12:__str_false');
            $this->emit('    mov     x2,  #5          # len("false")');
            $this->emit('__pb_write:');
            $this->emit('    mov     x0,  #1          # stdout');
            $this->emit('    mov     x8,  #64');
            $this->emit('    svc     #0');
            $this->emit('    ldp     x29, x30, [sp, #0]');
            $this->emit('    add     sp,  sp,  #16');
            $this->emit('    ret');
            $this->emitBlank();
        }

        // ── __print_nil ────────────────────────────────────────────────────
        if ($this->needsPrintNil) {
            $this->emitBlank();
            $this->emit('# ── __print_nil : escribe msg_nil sin newline ──');
            $this->emit('__print_nil:');
            $this->emit('    adrp    x1, msg_nil');
            $this->emit('    add     x1, x1, :lo12:msg_nil');
            $this->emit('    mov     x2, #5              # len("<nil>")');
            $this->emit('    mov     x0, #1              # stdout');
            $this->emit('    mov     x8, #64');
            $this->emit('    svc     #0');
            $this->emit('    ret');
            $this->emitBlank();
        }

        // ── __print_float_raw ─────────────────────────────────────────────────
        // Entrada : x0 = float64 bits (IEEE 754 double)
        // Efecto  : imprime representación decimal mínima (estilo Go %g)
        // Usa     : FPU (d0-d4), x9-x15
        if ($this->needsPrintFloat) {
            $this->emitBlank();
            $this->emit('// ── __print_float_raw : x0=f64bits → decimal en stdout ──');
            $this->emit('// Stack: [sp+0/8]=x29/x30  [sp+16]=char  [sp+24]=sign  [sp+32]=intpart  [sp+40]=frac  [sp+48..56]=9digbuf');
            $this->emit('__print_float_raw:');
            $this->emit('    sub     sp,  sp,  #80');
            $this->emit('    stp     x29, x30, [sp, #0]');
            $this->emit('    mov     x29, sp');
            $this->emit('    fmov    d0, x0              // bits → FPU');
            // Caso cero
            $this->emit('    fcmp    d0, #0.0');
            $this->emit('    bne     __pf_nonzero');
            $this->emit('    mov     w9, #48             // ASCII \'0\'');
            $this->emit('    strb    w9, [sp, #16]');
            $this->emit('    mov     x0, #1');
            $this->emit('    add     x1, sp, #16');
            $this->emit('    mov     x2, #1');
            $this->emit('    mov     x8, #64');
            $this->emit('    svc     #0');
            $this->emit('    b       __pf_done');
            $this->emit('__pf_nonzero:');
            // Guardar signo, luego trabajar con valor absoluto
            $this->emit('    fmov    x9, d0');
            $this->emit('    lsr     x9, x9, #63         // 1 si negativo');
            $this->emit('    str     x9, [sp, #24]       // guardar signo');
            $this->emit('    cbz     x9, __pf_abs');
            $this->emit('    fneg    d0, d0              // abs');
            $this->emit('__pf_abs:');
            // Separar parte entera y fraccionaria
            $this->emit('    frintz  d1, d0              // d1 = trunc(d0)');
            $this->emit('    fcvtzs  x10, d1             // x10 = parte entera');
            $this->emit('    fsub    d2, d0, d1          // d2 = fracción');
            $this->emit('    str     x10, [sp, #32]      // salvar entero (emitFloat64Const clobbers x0)');
            // Calcular dígitos fraccionarios
            $this->emitFloat64Const(1000000000.0);
            $this->emit('    fmov    d3, x0              // d3 = 1e9');
            $this->emit('    fmul    d4, d2, d3          // d4 = frac * 1e9');
            $this->emit('    fcvtas  x13, d4             // x13 = round(frac*1e9)');
            $this->emitMovImmReg('x12', 1000000000);
            $this->emit('    cmp     x13, x12');
            $this->emit('    b.ne    __pf_frac_ok');
            $this->emit('    ldr     x10, [sp, #32]');
            $this->emit('    add     x10, x10, #1        // carry: entero += 1');
            $this->emit('    str     x10, [sp, #32]');
            $this->emit('    mov     x13, #0             // fraccion = 0');
            $this->emit('__pf_frac_ok:');
            $this->emit('    str     x13, [sp, #40]      // guardar fraccion');
            // Imprimir signo si negativo
            $this->emit('    ldr     x9, [sp, #24]');
            $this->emit('    cbz     x9, __pf_print_int');
            $this->emit('    mov     w9, #45             // ASCII \'-\'');
            $this->emit('    strb    w9, [sp, #16]');
            $this->emit('    mov     x0, #1');
            $this->emit('    add     x1, sp, #16');
            $this->emit('    mov     x2, #1');
            $this->emit('    mov     x8, #64');
            $this->emit('    svc     #0');
            $this->emit('__pf_print_int:');
            // Imprimir parte entera primero
            $this->emit('    ldr     x0, [sp, #32]');
            $this->emit('    bl      __print_int_raw');
            // Si fraccion == 0, terminamos
            $this->emit('    ldr     x13, [sp, #40]');
            $this->emit('    cbz     x13, __pf_done');
            // Imprimir '.' (una sola vez, entre entero y fracción)
            $this->emit('    mov     w9, #46             // ASCII \'.\'');
            $this->emit('    strb    w9, [sp, #16]');
            $this->emit('    mov     x0, #1');
            $this->emit('    add     x1, sp, #16');
            $this->emit('    mov     x2, #1');
            $this->emit('    mov     x8, #64');
            $this->emit('    svc     #0');
            // Generar dígitos fraccionarios en buffer [sp+48..56]
            $this->emit('    add     x11, sp, #48        // buffer 9 bytes');
            $this->emit('    mov     x12, #9');
            $this->emit('__pf_digit_loop:');
            $this->emit('    mov     x14, x13');
            $this->emit('    mov     x15, #10');
            $this->emit('    udiv    x13, x13, x15');
            $this->emit('    msub    x14, x13, x15, x14');
            $this->emit('    add     x14, x14, #48');
            $this->emit('    subs    x12, x12, #1');
            $this->emit('    strb    w14, [x11, x12]');
            $this->emit('    b.ne    __pf_digit_loop');
            $this->emit('    mov     x12, #9');
            $this->emit('__pf_trim_loop:');
            $this->emit('    cmp     x12, #1');
            $this->emit('    ble     __pf_trim_done');
            $this->emit('    sub     x14, x12, #1');
            $this->emit('    ldrb    w15, [x11, x14]');
            $this->emit('    cmp     w15, #48');
            $this->emit('    b.ne    __pf_trim_done');
            $this->emit('    sub     x12, x12, #1');
            $this->emit('    b       __pf_trim_loop');
            $this->emit('__pf_trim_done:');
            $this->emit('    mov     x0, #1');
            $this->emit('    mov     x1, x11');
            $this->emit('    mov     x2, x12');
            $this->emit('    mov     x8, #64');
            $this->emit('    svc     #0');
            $this->emit('__pf_done:');
            $this->emit('    ldp     x29, x30, [sp, #0]');
            $this->emit('    add     sp,  sp,  #80');
            $this->emit('    ret');
            $this->emitBlank();
        }

        // ── __strlen_setx1 ────────────────────────────────────────────────────
        // Entrada : x0 = puntero a cadena terminada en \0
        // Salida  : x0 = puntero (sin cambios), x1 = longitud en bytes
        // Altera  : x9 (temporal de byte)
        if ($this->needsStrlen) {
            $this->emitBlank();
            $this->emit('# ── __strlen_setx1 : x0=ptr → x1=strlen(ptr), x0 intacto ──');
            $this->emit('__strlen_setx1:');
            $this->emit('    mov     x1,  #0');
            $this->emit('__sl_loop:');
            $this->emit('    ldrb    w9,  [x0, x1]');
            $this->emit('    cbz     w9,  __sl_done');
            $this->emit('    add     x1,  x1, #1');
            $this->emit('    b       __sl_loop');
            $this->emit('__sl_done:');
            $this->emit('    ret');
            $this->emitBlank();
        }

        // ── __now ─────────────────────────────────────────────────────────────
        // Obtiene segundos Unix con clock_gettime(CLOCK_REALTIME) y los
        // convierte a cadena ASCII con formato YYYY-MM-DD HH:MM:SS.
        // Salida : x0 = puntero al primer carácter, x1 = longitud (= 19)
        if ($this->needsNow) {
            $this->emitBlank();
            $this->emit('# ── __now : () → x0=ptr fecha, x1=len ──────────────────');
            $this->emit('__now:');
            $this->emit('    sub     sp,  sp,  #96');
            $this->emit('    stp     x29, x30, [sp, #0]');
            $this->emit('    mov     x29, sp');
            $this->emitBlank();

            $fixedNow = getenv('GOLAMPI_TEST_NOW_SEC');
            if ($fixedNow !== false && is_numeric($fixedNow)) {
                $this->emit('    // now() test override via GOLAMPI_TEST_NOW_SEC');
                $this->emitMovImmReg('x9', (int)$fixedNow);
            } else {
                $this->emit('    # clock_gettime(CLOCK_REALTIME=0, &timespec @ [sp+48])');
                $this->emit('    mov     x0,  #0');
                $this->emit('    add     x1,  sp,  #48        # timespec buffer (16 bytes)');
                $this->emit('    mov     x8,  #113             # sys_clock_gettime');
                $this->emit('    svc     #0');
                $this->emit('    ldr     x9,  [sp, #48]       # tv_sec (unix timestamp)');
            }

            $this->emitBlank();
            $this->emit('    // x9 = seconds desde epoch; x10..x18 son temporales');
            $this->emitMovImmReg('x10', 86400);
            $this->emit('    udiv    x11, x9, x10         # x11 = days since epoch');
            $this->emit('    msub    x12, x11, x10, x9    # x12 = seconds within day');
            $this->emitMovImmReg('x13', 3600);
            $this->emit('    udiv    x14, x12, x13        # x14 = hour');
            $this->emit('    msub    x15, x14, x13, x12   # x15 = seconds remaining');
            $this->emitMovImmReg('x16', 60);
            $this->emit('    udiv    x17, x15, x16        # x17 = minute');
            $this->emit('    msub    x18, x17, x16, x15   # x18 = second');
            $this->emit('    mov     x3,  x14             # hora');
            $this->emit('    mov     x4,  x17             # minuto');
            $this->emit('    mov     x5,  x18             # segundo');

            $this->emitBlank();
            $this->emit('    // convertir days restantes a año/mes/día por acumulación');
            $this->emit('    mov     x10, x11            # days restantes');
            $this->emit('    mov     x14, #1970          # year');
            $this->emit('__now_year_loop:');
            $this->emit('    mov     x15, #365          # yearDays base');
            $this->emit('    mov     x16, #4');
            $this->emit('    udiv    x17, x14, x16');
            $this->emit('    msub    x17, x17, x16, x14   # year % 4');
            $this->emit('    cbnz    x17, __now_year_check_done');
            $this->emit('    mov     x16, #100');
            $this->emit('    udiv    x17, x14, x16');
            $this->emit('    msub    x17, x17, x16, x14   # year % 100');
            $this->emit('    cbnz    x17, __now_year_leap');
            $this->emit('    mov     x16, #400');
            $this->emit('    udiv    x17, x14, x16');
            $this->emit('    msub    x17, x17, x16, x14   # year % 400');
            $this->emit('    cbz     x17, __now_year_leap');
            $this->emit('    b       __now_year_check_done');
            $this->emit('__now_year_leap:');
            $this->emit('    mov     x15, #366');
            $this->emit('__now_year_check_done:');
            $this->emit('    cmp     x10, x15');
            $this->emit('    blt     __now_year_done');
            $this->emit('    sub     x10, x10, x15');
            $this->emit('    add     x14, x14, #1');
            $this->emit('    b       __now_year_loop');
            $this->emit('__now_year_done:');
            $this->emit('    mov     x15, #0            # leap flag');
            $this->emit('    mov     x16, #4');
            $this->emit('    udiv    x17, x14, x16');
            $this->emit('    msub    x17, x17, x16, x14   # year % 4');
            $this->emit('    cbnz    x17, __now_month_init');
            $this->emit('    mov     x16, #100');
            $this->emit('    udiv    x17, x14, x16');
            $this->emit('    msub    x17, x17, x16, x14   # year % 100');
            $this->emit('    cbnz    x17, __now_leap_true');
            $this->emit('    mov     x16, #400');
            $this->emit('    udiv    x17, x14, x16');
            $this->emit('    msub    x17, x17, x16, x14   # year % 400');
            $this->emit('    cbnz    x17, __now_month_init');
            $this->emit('__now_leap_true:');
            $this->emit('    mov     x15, #1');
            $this->emit('__now_month_init:');
            $this->emit('    mov     x17, #1            # month');
            $this->emit('__now_month_loop:');
            $this->emit('    cmp     x17, #1');
            $this->emit('    beq     __now_month_len_1');
            $this->emit('    cmp     x17, #2');
            $this->emit('    beq     __now_month_len_2');
            $this->emit('    cmp     x17, #3');
            $this->emit('    beq     __now_month_len_3');
            $this->emit('    cmp     x17, #4');
            $this->emit('    beq     __now_month_len_4');
            $this->emit('    cmp     x17, #5');
            $this->emit('    beq     __now_month_len_5');
            $this->emit('    cmp     x17, #6');
            $this->emit('    beq     __now_month_len_6');
            $this->emit('    cmp     x17, #7');
            $this->emit('    beq     __now_month_len_7');
            $this->emit('    cmp     x17, #8');
            $this->emit('    beq     __now_month_len_8');
            $this->emit('    cmp     x17, #9');
            $this->emit('    beq     __now_month_len_9');
            $this->emit('    cmp     x17, #10');
            $this->emit('    beq     __now_month_len_10');
            $this->emit('    cmp     x17, #11');
            $this->emit('    beq     __now_month_len_11');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_1:');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_2:');
            $this->emit('    mov     x18, #28');
            $this->emit('    add     x18, x18, x15');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_3:');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_4:');
            $this->emit('    mov     x18, #30');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_5:');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_6:');
            $this->emit('    mov     x18, #30');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_7:');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_8:');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_9:');
            $this->emit('    mov     x18, #30');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_10:');
            $this->emit('    mov     x18, #31');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_11:');
            $this->emit('    mov     x18, #30');
            $this->emit('    b       __now_month_len_done');
            $this->emit('__now_month_len_done:');
            $this->emit('    cmp     x10, x18');
            $this->emit('    blt     __now_day_done');
            $this->emit('    sub     x10, x10, x18');
            $this->emit('    add     x17, x17, #1');
            $this->emit('    b       __now_month_loop');
            $this->emit('__now_day_done:');
            $this->emit('    add     x18, x10, #1       # day');

            $this->emitBlank();
            $this->emit('    // Formatear buffer YYYY-MM-DD HH:MM:SS');
            $this->emit('    // x14 = year, x17 = month, x18 = day, x3/hour, x4/min, x5/sec');
            $this->emit('    adrp    x10, __now_buf');
            $this->emit('    add     x10, x10, :lo12:__now_buf');

            // Year (x14) → [0..3]
            $this->emit('    mov     x12, #1000');
            $this->emit('    udiv    x11, x14, x12');
            $this->emit('    msub    x9, x11, x12, x14');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #0]');
            $this->emit('    mov     x14, x9');
            $this->emit('    mov     x12, #100');
            $this->emit('    udiv    x11, x14, x12');
            $this->emit('    msub    x9, x11, x12, x14');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #1]');
            $this->emit('    mov     x14, x9');
            $this->emit('    mov     x12, #10');
            $this->emit('    udiv    x11, x14, x12');
            $this->emit('    msub    x9, x11, x12, x14');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #2]');
            $this->emit('    add     x9, x9, #48');
            $this->emit('    strb    w9, [x10, #3]');
            $this->emit('    mov     w12, #45');
            $this->emit('    strb    w12, [x10, #4]');

            // Month (x17) → [5..6]
            $this->emit('    mov     x12, #10');
            $this->emit('    udiv    x11, x17, x12');
            $this->emit('    msub    x14, x11, x12, x17');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #5]');
            $this->emit('    add     x14, x14, #48');
            $this->emit('    strb    w14, [x10, #6]');
            $this->emit('    mov     w12, #45');
            $this->emit('    strb    w12, [x10, #7]');

            // Day (x18) → [8..9]
            $this->emit('    mov     x12, #10');
            $this->emit('    udiv    x11, x18, x12');
            $this->emit('    msub    x9, x11, x12, x18');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #8]');
            $this->emit('    add     x9, x9, #48');
            $this->emit('    strb    w9, [x10, #9]');
            $this->emit('    mov     w12, #32');
            $this->emit('    strb    w12, [x10, #10]');

            // Hour (x3)
            $this->emit('    mov     x12, #10');
            $this->emit('    udiv    x11, x3, x12');
            $this->emit('    msub    x14, x11, x12, x3');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #11]');
            $this->emit('    add     x14, x14, #48');
            $this->emit('    strb    w14, [x10, #12]');
            $this->emit('    mov     w12, #58');
            $this->emit('    strb    w12, [x10, #13]');

            // Minute (x4)
            $this->emit('    mov     x12, #10');
            $this->emit('    udiv    x11, x4, x12');
            $this->emit('    msub    x14, x11, x12, x4');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #14]');
            $this->emit('    add     x14, x14, #48');
            $this->emit('    strb    w14, [x10, #15]');
            $this->emit('    mov     w12, #58');
            $this->emit('    strb    w12, [x10, #16]');

            // Second (x5)
            $this->emit('    mov     x12, #10');
            $this->emit('    udiv    x11, x5, x12');
            $this->emit('    msub    x14, x11, x12, x5');
            $this->emit('    add     x11, x11, #48');
            $this->emit('    strb    w11, [x10, #17]');
            $this->emit('    add     x14, x14, #48');
            $this->emit('    strb    w14, [x10, #18]');
            $this->emit('    mov     w12, #0');
            $this->emit('    strb    w12, [x10, #19]');
            $this->emit('    mov     x0,  x10             # ptr al buffer');
            $this->emit('    mov     x1,  #19             # longitud fija');
            $this->emitBlank();
            $this->emit('    ldp     x29, x30, [sp, #0]');
            $this->emit('    add     sp,  sp,  #96');
            $this->emit('    ret');
            $this->emitBlank();
        }

        if ($this->needsPow) {
            $this->emit('# ── __pow_int : x0=base, x1=exp → x0 = base^exp ──────');
            $this->emit('__pow_int:');
            $this->emit('    sub     sp,  sp,  #32');
            $this->emit('    stp     x29, x30, [sp, #0]');
            $this->emit('    mov     x29, sp');
            $this->emit('    mov     x9,  x0           # base');
            $this->emit('    mov     x10, x1           # exp');
            $this->emit('    mov     x0,  #1           # result = 1');
            $this->emit('    cmp     x10, #0');
            $this->emit('    ble     __pow_done');
            $this->emit('__pow_loop:');
            $this->emit('    mul     x0,  x0,  x9     # result *= base');
            $this->emit('    sub     x10, x10, #1');
            $this->emit('    cbnz    x10, __pow_loop');
            $this->emit('__pow_done:');
            $this->emit('    ldp     x29, x30, [sp, #0]');
            $this->emit('    add     sp,  sp,  #32');
            $this->emit('    ret');
            $this->emitBlank();
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Utilidades internas
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Emite una instrucción mov que funciona con inmediatos grandes.
     * ARM64 solo acepta movz/movk para valores fuera de 16 bits.
     */
    private function emitMovImm(int $v): void
    {
        if ($v === 0) {
            $this->emit('    mov     x0, #0');
        } elseif ($v >= -32768 && $v <= 65535) {
            $this->emit("    mov     x0, #{$v}");
        } else {
            // Descomponer en chunks de 16 bits
            $low  = $v & 0xFFFF;
            $high = ($v >> 16) & 0xFFFF;
            $this->emit("    movz    x0, #{$low}");
            if ($high !== 0) {
                $this->emit("    movk    x0, #{$high}, lsl #16");
            }
        }
    }

    /** Emite un inmediato positivo en el registro ARM64 indicado. */
    private function emitMovImmReg(string $reg, int $v): void
    {
        if ($v >= 0 && $v <= 65535) {
            $this->emit("    mov     {$reg}, #{$v}");
            return;
        }

        $low  = $v & 0xFFFF;
        $mid1 = ($v >> 16) & 0xFFFF;
        $mid2 = ($v >> 32) & 0xFFFF;
        $mid3 = ($v >> 48) & 0xFFFF;

        $this->emit(sprintf('    movz    %s, #0x%04X', $reg, $low));
        if ($mid1 !== 0) {
            $this->emit(sprintf('    movk    %s, #0x%04X, lsl #16', $reg, $mid1));
        }
        if ($mid2 !== 0) {
            $this->emit(sprintf('    movk    %s, #0x%04X, lsl #32', $reg, $mid2));
        }
        if ($mid3 !== 0) {
            $this->emit(sprintf('    movk    %s, #0x%04X, lsl #48', $reg, $mid3));
        }
    }

    private function emit(string $line): void
    {
        $this->textLines[] = $line;
    }

    private function emitBlank(): void
    {
        $this->textLines[] = '';
    }
}
