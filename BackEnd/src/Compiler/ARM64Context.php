<?php

declare(strict_types=1);

namespace App\Compiler;

/**
 * Contexto de generación de código ARM64 (AArch64).
 *
 * Mantiene el estado mutable durante el recorrido del AST:
 *  - Contador global de etiquetas (evita colisiones)
 *  - Sección .data: strings literales del programa
 *  - Mapa variable → offset en el stack frame de la función actual
 *
 * Cada función llama a resetFrame() al inicio para limpiar los offsets
 * del frame anterior.
 */
class ARM64Context
{
    /**
     * Número de slots de scratch pre-reservados en cada frame.
     * Slots  : [x29 + 16], [x29 + 24], …, [x29 + 16 + (SCRATCH_SLOTS-1)*8]
     * La primera variable local queda en [x29 + 16 + SCRATCH_SLOTS*8].
     *
     * Usar slots en el frame (en lugar de registros de scratch) garantiza que
     * los valores intermedios de expresiones binarias sobreviven a las llamadas
     * a funciones (bl), que pueden clobar x9-x15 según AAPCS64.
     */
    public const SCRATCH_SLOTS = 6;

    // ── Contadores globales ────────────────────────────────────────────────────
    private int $labelCounter  = 0;
    private int $stringCounter = 0;

    // ── Stack frame de la función actual ──────────────────────────────────────
    /**
     * Bytes ya asignados a variables locales dentro del frame.
     * Se inicializa en SCRATCH_SLOTS*8 para reservar los slots de scratch.
     */
    private int $stackOffset = 0;

    /** nombre_variable => offset positivo desde x29 (después del prólogo). */
    private array $varOffsets = [];

    /** nombre_variable => tipo Golampi ('ENTERO', 'CADENA', 'BOOLEANO', …). */
    private array $varTypes = [];

    /** nombre_variable => array de dimensiones [N] o [R, C]. */
    private array $arrayDims = [];

    /** Conjunto de variables que son punteros a arreglo (param isPuntero con dims). */
    private array $ptrArrayVars = [];

    /** Conjunto de variables que son punteros escalares (*int32, *bool, etc.). */
    private array $ptrScalarVars = [];

    /** nombre_variable => offset del slot que almacena la longitud de la cadena (para substr). */
    private array $strLenSlots = [];

    // ── Sección .data ─────────────────────────────────────────────────────────
    /** label => directiva .ascii completa (con comillas). */
    private array $dataSection = [];

    // ── Etiquetas ──────────────────────────────────────────────────────────────

    /**
     * Genera una etiqueta única con el prefijo dado.
     * Ejemplo: newLabel('if') → 'if_0', 'if_1', …
     */
    public function newLabel(string $prefix = 'L'): string
    {
        return $prefix . '_' . ($this->labelCounter++);
    }

    // ── Sección .data ──────────────────────────────────────────────────────────

    /**
     * Registra una cadena en la sección .data con salto de línea final
     * y devuelve su etiqueta.
     */
    public function addString(string $rawValue): string
    {
        $label   = '_s' . ($this->stringCounter++);
        $escaped = $this->escapeAsm($rawValue);
        $this->dataSection[$label] = '"' . $escaped . '\n"';
        return $label;
    }

    /**
     * Registra una cadena en la sección .data SIN salto de línea final
     * y devuelve su etiqueta.
     */
    public function addStringRaw(string $rawValue): string
    {
        $label   = '_s' . ($this->stringCounter++);
        $escaped = $this->escapeAsm($rawValue);
        // \0 al final permite que __strlen_setx1 encuentre el terminador
        $this->dataSection[$label] = '"' . $escaped . '\\0"';
        return $label;
    }

    /** Devuelve todas las entradas registradas en la sección .data. */
    public function getDataSection(): array
    {
        return $this->dataSection;
    }

    // ── Stack frame ────────────────────────────────────────────────────────────

    /**
     * Reserva 8 bytes para una variable local y devuelve su offset desde x29.
     *
     * Layout del frame (x29 = frame base tras el prólogo):
     *   [x29 +  0]  x29 del llamador  (stp x29, x30)
     *   [x29 +  8]  x30 del llamador
     *   [x29 + 16]  scratch slot 0   ← getScratchSaveOffset(0)
     *   [x29 + 24]  scratch slot 1
     *   …
     *   [x29 + 56]  scratch slot 5   ← getScratchSaveOffset(5)
     *   [x29 + 64]  primera variable local  ← primera allocVar()
     *   [x29 + 72]  segunda variable local
     *   …
     *
     * resetFrame() inicializa $stackOffset = SCRATCH_SLOTS * 8 = 48.
     * Primera allocVar: stackOffset=56, offset=56+8=64. ✓
     */
    public function allocVar(string $name, string $tipo = 'ENTERO'): int
    {
        $this->stackOffset += 8;
        $offset = $this->stackOffset + 8;   // x29/x30 ocupa [x29+0..x29+15]
        $this->varOffsets[$name] = $offset;
        $this->varTypes[$name]   = $tipo;
        return $offset;
    }

    /**
     * Reserva slots contiguos para un arreglo y devuelve el offset del elemento [0].
     *
     * Layout: elemento[i] → [x29, #(baseOffset + i*8)]
     * Para 2D [R][C]: elemento[i][j] → [x29, #(baseOffset + (i*C + j)*8)]
     *
     * Nota: la fórmula espeja allocVar() para que los offsets sean consistentes.
     */
    public function allocArray(string $name, array $dims, string $tipo = 'ENTERO'): int
    {
        $total = max(1, (int)array_product($dims));
        // El primer elemento queda en stackOffset+16 (mismo razonamiento que allocVar)
        $baseOffset = $this->stackOffset + 16;
        $this->stackOffset += $total * 8;
        $this->varOffsets[$name] = $baseOffset;
        $this->varTypes[$name]   = $tipo;
        $this->arrayDims[$name]  = $dims;
        return $baseOffset;
    }

    /**
     * Devuelve las dimensiones de un arreglo, o null si la variable no es arreglo.
     */
    public function getArrayDims(string $name): ?array
    {
        return $this->arrayDims[$name] ?? null;
    }

    /** Marca una variable como puntero a arreglo (parámetro isPuntero con dims). */
    public function markPtrArray(string $name, array $dims): void
    {
        $this->ptrArrayVars[$name] = $dims;
    }

    /** Devuelve true si la variable es un puntero a arreglo. */
    public function isPtrArray(string $name): bool
    {
        return isset($this->ptrArrayVars[$name]);
    }

    /** Devuelve las dimensiones del arreglo al que apunta, o null. */
    public function getPtrArrayDims(string $name): ?array
    {
        return $this->ptrArrayVars[$name] ?? null;
    }

    /** Marca una variable como puntero escalar (*int32, *bool, etc.). */
    public function markPtrScalar(string $name): void
    {
        $this->ptrScalarVars[$name] = true;
    }

    /** Devuelve true si la variable es un puntero escalar. */
    public function isPtrScalar(string $name): bool
    {
        return isset($this->ptrScalarVars[$name]);
    }

    /**
     * Devuelve el offset de una variable en el frame actual,
     * o null si no existe.
     */
    public function getVarOffset(string $name): ?int
    {
        return $this->varOffsets[$name] ?? null;
    }

    /**
     * Devuelve el tipo Golampi de una variable ('ENTERO', 'CADENA', etc.),
     * o 'ENTERO' si no se tiene información de tipo.
     */
    public function getVarType(string $name): string
    {
        return $this->varTypes[$name] ?? 'ENTERO';
    }

    /**
     * Devuelve el offset desde x29 para el scratch slot en el nivel $depth.
     *
     * depth 0 → [x29 + 16]
     * depth 1 → [x29 + 24]
     * …
     * depth 5 → [x29 + 56]
     *
     * Los slots están pre-reservados en el frame. Usarlos en lugar de
     * registros de scratch (x9-x15) garantiza que los operandos intermedios
     * de expresiones binarias sobreviven a las llamadas (bl) que cloban
     * los registros caller-saved.
     */
    public function getScratchSaveOffset(int $depth): int
    {
        $d = $depth % self::SCRATCH_SLOTS;
        return $d * 8 + 16;
    }

    /**
     * Tamaño total del frame redondeado al múltiplo de 16 más próximo.
     * Incluye 16 bytes para x29/x30 y SCRATCH_SLOTS*8 para scratch.
     */
    public function frameSize(): int
    {
        $raw = $this->stackOffset + 16;
        return (int)(ceil($raw / 16) * 16);
    }

    /**
     * Calcula el frame size a partir del número de variables (locales + params)
     * sin modificar el estado interno.
     * Añade automáticamente espacio para x29/x30 y los SCRATCH_SLOTS.
     */
    public function calcularFrameSize(int $numVars): int
    {
        $raw = ($numVars + self::SCRATCH_SLOTS) * 8 + 16;
        return (int)(ceil($raw / 16) * 16);
    }

    /**
     * Reinicia el estado del frame al entrar a una nueva función.
     * Inicializa $stackOffset = SCRATCH_SLOTS * 8 para que la primera
     * allocVar() asigne el offset 64 (tras los 6 scratch slots).
     * No toca el contador de etiquetas ni la sección .data.
     */
    /** Registra el offset del slot de longitud para una variable cadena con substr. */
    public function setStrLenSlot(string $name, int $lenSlot): void
    {
        $this->strLenSlots[$name] = $lenSlot;
    }

    /** Devuelve el offset del slot de longitud, o null si no aplica. */
    public function getStrLenSlot(string $name): ?int
    {
        return $this->strLenSlots[$name] ?? null;
    }

    public function resetFrame(): void
    {
        $this->stackOffset  = self::SCRATCH_SLOTS * 8;
        $this->varOffsets   = [];
        $this->varTypes     = [];
        $this->arrayDims    = [];
        $this->ptrArrayVars  = [];
        $this->ptrScalarVars = [];
        $this->strLenSlots   = [];
    }

    // ── Helpers internos ──────────────────────────────────────────────────────

    /** Escapa caracteres especiales para usarlos en .ascii del ensamblador GNU. */
    private function escapeAsm(string $s): string
    {
        return str_replace(
            ['\\',   "\n",  "\t",  '"'],
            ['\\\\', '\\n', '\\t', '\\"'],
            $s
        );
    }
}
