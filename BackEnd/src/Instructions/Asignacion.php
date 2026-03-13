<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo; // IMPORTANTE: Agregado para poder leer la clase

class Asignacion extends Instruction {
    // Usamos Union Types para soportar x = 5 o x,y = 5,7
    private string|array $id;
    private Expresion|array $valor;

    public function __construct(int $linea, int $columna, string|array $id, Expresion|array $valor) {
        parent::__construct($linea, $columna, TipoInstruccion::ASIGNACION);
        $this->id = $id;
        $this->valor = $valor;
    }

    // --- NUEVA FUNCION DE FORMATEO SEGURO ---
    // Convierte arreglos, booleanos y nulos a un texto que no rompa la consola de PHP
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

    public function ejecutar(Entorno $entorno): mixed {
        
        // --- CASO 1: Asignación simple (ej: x = 5) ---
        if (is_string($this->id) && $this->valor instanceof Expresion) {
            $valorEvaluado = $this->valor->ejecutar($entorno);
            
            // Verificar si la variable existe
            $simbolo = $entorno->getVariable($this->id);
            
            if ($simbolo === null) {
                Salida::$salidasConsola[] = "❌ Error: La variable '{$this->id}' no existe [Línea {$this->linea}]";
                return null;
            }
            
            if ($simbolo->isConst === true) {
                Salida::$salidasConsola[] = "❌ Error: No se puede reasignar la constante '{$this->id}' [Línea {$this->linea}]";
                return null;
            }
            
            // APLICAMOS EL FORMATEO SEGURO ANTES DE IMPRIMIR
            $valorAImprimir = $this->formatearSalida($valorEvaluado->valor);
            
            // Actualizar el valor
            $entorno->setVariable($this->id, $valorEvaluado->valor);
            Salida::$salidasConsola[] = "→ Asignación: '{$this->id}' = {$valorAImprimir}";
            Salida::$salidasConsola[] = "✓ Variable actualizada exitosamente\n";
        }
        
        // --- CASO 2: Asignación múltiple (ej: x, y = 5, 7) ---
        elseif (is_array($this->id) && is_array($this->valor)) {
            
            if (count($this->id) !== count($this->valor)) {
                Salida::$salidasConsola[] = "❌ Error: La cantidad de variables (" . count($this->id) . ") no coincide con la cantidad de valores (" . count($this->valor) . ") [Línea {$this->linea}]";
                return null;
            }
            
            Salida::$salidasConsola[] = "→ Asignación múltiple:";
            
            for ($i = 0; $i < count($this->id); $i++) {
                $nombreVar = $this->id[$i];
                $expresionValor = $this->valor[$i];
                $num = $i + 1;
                
                $valorEvaluado = $expresionValor->ejecutar($entorno);
                
                // Verificar si existe
                $simbolo = $entorno->getVariable($nombreVar);
                
                if ($simbolo === null) {
                    Salida::$salidasConsola[] = "  [$num] ❌ '{$nombreVar}' - No existe";
                    continue;
                }
                
                if ($simbolo->isConst === true) {
                    Salida::$salidasConsola[] = "  [$num] ❌ '{$nombreVar}' - Es una constante";
                    continue;
                }
                
                // APLICAMOS EL FORMATEO SEGURO ANTES DE IMPRIMIR
                $valorAImprimir = $this->formatearSalida($valorEvaluado->valor);
                
                $entorno->setVariable($nombreVar, $valorEvaluado->valor);
                Salida::$salidasConsola[] = "  [$num] '{$nombreVar}' = {$valorAImprimir}";
                Salida::$salidasConsola[] = "       ✓ Actualizada exitosamente";
            }
            
            Salida::$salidasConsola[] = "✓ Proceso completado\n";
        }

        return null;
    }
}