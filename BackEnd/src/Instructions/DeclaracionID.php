<?php
namespace App\Instructions;
use App\Utilities\ValorArreglo;
use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;

class DeclaracionID extends Instruction {
    // Usamos Union Types de PHP 8 para permitir un ID individual o un arreglo de IDs
    public string|array $id;
    public Tipo|array|null $tipo;
    public Expresion|array|null $valor;
    public bool $isconstid;

    public function __construct(
        int $linea, 
        int $columna, 
        string|array $id, 
        Tipo|array| null $tipo, 
        Expresion|array|null $valor = null,
        bool $isconstid = false
    ) {
        parent::__construct($linea, $columna, TipoInstruccion::CREAR_VARIABLE);
        $this->id = $id;
        $this->tipo = $tipo;
        $this->valor = $valor;
        $this->isconstid = $isconstid;
        

    }

    public function ejecutar(Entorno $entorno): mixed {
        $tipoDeclaracion = $this->isconstid ? "CONSTANTE" : "VARIABLE";
        
        // --- CASO 1: Es una sola variable (ej: var x int32 = 10) ---
        if (is_string($this->id) && $this->tipo instanceof Tipo) {
            $tipoStr = $this->tipo->name;
            
            // Verificar si ya existe
            if ($entorno->getVariable($this->id) !== null) {
                Salida::$salidasConsola[] = "❌ Error: La variable '{$this->id}' ya existe en el entorno '{$entorno->nombre}' [Línea {$this->linea}]";
                return null;
            }
            
            // Mostrar lo que se está asignando
            if ($this->valor !== null && $this->valor instanceof Expresion) {
                $valorEvaluado = $this->valor->ejecutar($entorno);
                
                Salida::$salidasConsola[] = "→ Asignando {$tipoDeclaracion}: '{$this->id}'";
                Salida::$salidasConsola[] = "  • Tipo: {$tipoStr}";
                $valorAImprimir = $this->formatearSalida($valorEvaluado->valor);
                Salida::$salidasConsola[] = "  • Valor: {$valorAImprimir}";
                Salida::$salidasConsola[] = "  • Es constante: " . ($this->isconstid ? "Sí" : "No");
                
                // Validación de tipos
                if ($valorEvaluado->tipo === $this->tipo || $valorEvaluado->tipo === Tipo::LISTA) {
                    $entorno->guardarVariable($this->id, $valorEvaluado->valor, $this->tipo, $this->linea, $this->columna, $this->isconstid);
                    Salida::$salidasConsola[] = "✓ Variable '{$this->id}' agregada exitosamente\n";
                } else {
                    Salida::$salidasConsola[] = "❌ Error de tipo: Se esperaba {$tipoStr}, pero se recibió {$valorEvaluado->tipo->name} [Línea {$this->linea}]\n";
                }
            } else {
                // Sin valor asignado
                Salida::$salidasConsola[] = "→ Declarando {$tipoDeclaracion}: '{$this->id}'";
                Salida::$salidasConsola[] = "  • Tipo: {$tipoStr}";
                Salida::$salidasConsola[] = "  • Valor: nill";
                Salida::$salidasConsola[] = "  • Es constante: " . ($this->isconstid ? "Sí" : "No");
                
                $entorno->guardarVariable($this->id, null, $this->tipo, $this->linea, $this->columna, $this->isconstid);
                Salida::$salidasConsola[] = "✓ Variable '{$this->id}' declarada exitosamente\n";
            }
        } 
        // --- CASO 2: Son múltiples variables (ej: var x, y int32 = 1, 2) ---
        elseif (is_array($this->id)) {
            $tipoStr = $this->tipo instanceof Tipo ? $this->tipo->name : 'múltiple';
            
            Salida::$salidasConsola[] = "→ Asignando lista de {$tipoDeclaracion}S:";
            
            // Validar cantidad de valores
            if (is_array($this->valor) && count($this->id) !== count($this->valor)) {
                Salida::$salidasConsola[] = "❌ Error: Se declararon " . count($this->id) . " variables pero se asignaron " . count($this->valor) . " valores [Línea {$this->linea}]\n";
                return null;
            }
            
            // Procesar cada variable
            for ($i = 0; $i < count($this->id); $i++) {
                $nombreVar = $this->id[$i];
                $tipoVar = is_array($this->tipo) ? $this->tipo[$i] : $this->tipo;
                $num = $i + 1; // Índice para mostrar (1, 2, 3...)
                
                // Verificar si ya existe
                if ($entorno->getVariable($nombreVar) !== null) {
                    Salida::$salidasConsola[] = "  [$num] ❌ '{$nombreVar}' - Ya existe en el entorno";
                    continue;
                }
                
                if (is_array($this->valor) && isset($this->valor[$i])) {
                    $valorEvaluado = $this->valor[$i]->ejecutar($entorno);
                    
                    Salida::$salidasConsola[] = "  [$num] '{$nombreVar}' → Tipo: {$tipoVar->name}, Valor: {$valorEvaluado->valor}";
                    
                    if ($valorEvaluado->tipo === $tipoVar) {
                        $entorno->guardarVariable($nombreVar, $valorEvaluado->valor, $tipoVar, $this->linea, $this->columna, $this->isconstid);
                        Salida::$salidasConsola[] = "       ✓ Agregada exitosamente";
                    } else {
                        Salida::$salidasConsola[] = "       ❌ Error de tipo: Se esperaba {$tipoVar->name}, recibió {$valorEvaluado->tipo->name}";
                    }
                } else {
                    Salida::$salidasConsola[] = "  [$num] '{$nombreVar}' → Tipo: {$tipoVar->name}, Valor: (sin asignar)";
                    $entorno->guardarVariable($nombreVar, null, $tipoVar, $this->linea, $this->columna, $this->isconstid);
                    Salida::$salidasConsola[] = "       ✓ Declarada exitosamente";
                }
            }
            
            Salida::$salidasConsola[] = "✓ Proceso completado\n";
        }

        return null;
    }


    /**
     * Devuelve el valor por defecto de Go según el tipo de dato
     */
    private function getValorPorDefecto(Tipo $tipo) {
        return match($tipo) {
            Tipo::ENTERO => 0,
            Tipo::DECIMAL => 0.0,
            Tipo::BOOLEANO => false,
            Tipo::CADENA => "",
            Tipo::CARACTER => 0, // En Go, las runas son alias de int32 (por defecto 0)
            default => null
        };
    }

    /**
     * Función recursiva que construye matrices multidimensionales vacías
     * Ej: Si $dimensiones = [2, 3], creará una matriz de 2 filas y 3 columnas llena de valores por defecto
     */
    private function generarArregloPorDefecto(Tipo $tipoBase, array $dimensiones, int $nivelActual = 0): array {
        $arreglo = [];
        $tamañoDimensionActual = $dimensiones[$nivelActual];

        for ($i = 0; $i < $tamañoDimensionActual; $i++) {
            // Si no estamos en la última dimensión, hacemos recursividad (creamos otro arreglo adentro)
            if ($nivelActual < count($dimensiones) - 1) {
                $arreglo[] = $this->generarArregloPorDefecto($tipoBase, $dimensiones, $nivelActual + 1);
            } else {
                // Si es la última dimensión, lo llenamos con los valores por defecto
                $arreglo[] = $this->getValorPorDefecto($tipoBase);
            }
        }

        return $arreglo;
    }


    private function formatearSalida($valor) {
        if ($valor instanceof ValorArreglo) {
            $dims = implode("x", $valor->dimensiones);
            return "[Arreglo {$dims} de tipo {$valor->tipoBase->name}]";
        } elseif (is_bool($valor)) {
            return $valor ? "true" : "false";
        } elseif (is_null($valor)) {
            return "nil";
        }
        return (string)$valor;
    }

}