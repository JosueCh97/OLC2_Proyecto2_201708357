<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;

class DeclaracionCorta extends Instruction {
    /** @var string[] */
    public array $ids;
    /** @var Expresion[] */
    public array $valores;

    public function __construct(int $linea, int $columna, array $ids, array $valores) {
        parent::__construct($linea, $columna, TipoInstruccion::CREAR_VARIABLE ?? 'DECLARACION_CORTA');
        $this->ids = $ids;
        $this->valores = $valores;
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

    public function ejecutar(Entorno $entorno): mixed {
        // 1. Validar alcance (No puede ser global)
        // Usualmente el entorno global se llama "Global"
        if (strtolower($entorno->nombre) === "global") {
            Salida::$errores[] = "❌ Error [Línea {$this->linea}]: La declaración corta (:=) no puede usarse a nivel global.";
            Salida::$salidasConsola[] = "❌ Error [Línea {$this->linea}]: La declaración corta (:=) no puede usarse a nivel global.";
            return null;
        }

        // 2. Evaluar y aplanar valores por si hay retorno multiple de una funcion.
        $valoresEvaluados = [];
        foreach ($this->valores as $expresion) {
            $resultado = $expresion->ejecutar($entorno);

            // Compatibilidad: retorno multiple antiguo como array directo.
            if (is_array($resultado)) {
                foreach ($resultado as $res) {
                    $valoresEvaluados[] = $res;
                }
                continue;
            }

            // Retorno multiple actual: TipoRetorno con tipo LISTA.
            if (
                $resultado instanceof TipoRetorno
                && $resultado->tipo === Tipo::LISTA
                && is_array($resultado->valor)
            ) {
                foreach ($resultado->valor as $res) {
                    $valoresEvaluados[] = $res;
                }
                continue;
            }

            $valoresEvaluados[] = $resultado;
        }

        // 3. Validar misma cantidad de variables que de valores evaluados
        if (count($this->ids) !== count($valoresEvaluados)) {
            Salida::$errores[] = "❌ Error [Línea {$this->linea}]: La cantidad de variables no coincide con la cantidad de valores.";
            Salida::$salidasConsola[] = "❌ Error [Línea {$this->linea}]: La cantidad de variables no coincide con la cantidad de valores.";
            return null;
        }

        // 4. Validar regla de Go: Al menos una variable debe ser nueva
        $alMenosUnaNueva = false;
        foreach ($this->ids as $id) {
            // Regla de Go: la novedad se evalua contra el scope actual, no contra ancestros.
            if (!array_key_exists($id, $entorno->ids)) {
                $alMenosUnaNueva = true;
                break;
            }
        }

        if (!$alMenosUnaNueva) {
            Salida::$errores[] = "❌ Error [Línea {$this->linea}]: Al menos una variable en la declaración corta debe ser nueva.";
            Salida::$salidasConsola[] = "❌ Error [Línea {$this->linea}]: Al menos una variable en la declaración corta debe ser nueva.";
            return null;
        }

      // 5. Ejecutar la declaración / asignación
        for ($i = 0; $i < count($this->ids); $i++) {
            $nombreVar = $this->ids[$i];
            $resultadoEval = $valoresEvaluados[$i];
            $valorFinal = $resultadoEval->valor;
            $tipoInferido = $resultadoEval->tipo; // Inferencia automática de tipo
            if ($valorFinal instanceof ValorArreglo) {
                $tipoInferido = Tipo::ARREGLO;
            }

            if (array_key_exists($nombreVar, $entorno->ids)) {
                $entorno->setVariable($nombreVar, $valorFinal);
            } else {
                $entorno->guardarVariable(
                    $nombreVar,
                    $valorFinal,
                    $tipoInferido,
                    $this->linea,
                    $this->columna,
                    false
                );
            }
            
            $valorImpreso = $this->formatearSalida($valorFinal);
          //  Salida::$salidasConsola[] = "→ Declaración Corta: '{$nombreVar}' = {$valorImpreso} (Tipo Inferido: {$tipoInferido->name})";
        }

        return null;
    }
}