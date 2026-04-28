<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo; // IMPORTANTE: Agregado para poder leer la clase
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;

class Asignacion extends Instruction {
    // Usamos Union Types para soportar x = 5 o x,y = 5,7
    public string|array $id;
    public Expresion|array $valor;

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
        $valoresEvaluados = [];
        
        // 1. Aseguramos que $this->valor sea un arreglo iterativo
        $expresiones = is_array($this->valor) ? $this->valor : [$this->valor];
        
        foreach ($expresiones as $exp) {
            $resultado = $exp->ejecutar($entorno);
            
            // Compatibilidad: retorno multiple antiguo como array directo.
            if (is_array($resultado)) {
                foreach ($resultado as $res) {
                    $valoresEvaluados[] = $res;
                }
            // Retorno multiple actual: TipoRetorno con tipo LISTA.
            } elseif (
                $resultado instanceof TipoRetorno
                && $resultado->tipo === Tipo::LISTA
                && is_array($resultado->valor)
            ) {
                foreach ($resultado->valor as $res) {
                    $valoresEvaluados[] = $res;
                }
            } else {
                $valoresEvaluados[] = $resultado;
            }
        }

        // 2. Aseguramos que $this->id sea un arreglo
        $destinos = is_array($this->id) ? $this->id : [$this->id];

        // 3. Verificamos la cantidad ya con los valores reales desempacados
        if (count($destinos) !== count($valoresEvaluados)) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: Destinos (" . count($destinos) . ") no coincide con valores devueltos (" . count($valoresEvaluados) . ").";
            return null;
        }

        // 4. Asignamos uno por uno en la tabla de símbolos
        for ($i = 0; $i < count($destinos); $i++) {
            $nombreVar = $destinos[$i];
            $valorEval = $valoresEvaluados[$i];
            
            $simbolo = $entorno->getVariable($nombreVar);
            if ($simbolo === null) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: La variable '{$nombreVar}' no existe en este entorno.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                continue;
            }

            if ($simbolo->isConst === true) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: No se puede modificar '{$nombreVar}' porque es constante.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                continue;
            }

            $entorno->setVariable($nombreVar, $valorEval->valor);
        }
        
        return null;
    }

   
}// FIN DE CLASE