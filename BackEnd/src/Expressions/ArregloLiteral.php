<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo;

class ArregloLiteral extends Expresion {
    public Tipo $tipoBase;
    public array $dimensionesExpr;
    public array $valoresExpr; // Puede ser un arreglo anidado de expresiones

    public function __construct(int $linea, int $columna, Tipo $tipoBase, array $dimensionesExpr, array $valoresExpr) {
        parent::__construct($linea, $columna, TipoExpresion::ARREGLO_LITERAL ?? 'ARREGLO_LITERAL');
        $this->tipoBase = $tipoBase;
        $this->dimensionesExpr = $dimensionesExpr;
        $this->valoresExpr = $valoresExpr;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        // 1. Evaluamos cuánto valen las dimensiones (ej: [2][2] -> 2 y 2)
        $dimensiones = [];
        foreach ($this->dimensionesExpr as $exp) {
            $res = $exp->ejecutar($entorno);
            $dimensiones[] = $res->valor;
        }

        // 2. Evaluamos la lista de valores recursivamente
        $valoresEvaluados = $this->evaluarValores($this->valoresExpr, $entorno);

        // 3. Empaquetamos todo en el objeto que ya creamos antes
        $arregloObj = new ValorArreglo($this->tipoBase, $dimensiones, $valoresEvaluados);
        
        // Devolvemos el tipo base (en Go se considera que devuelve el tipo del arreglo)
        return new TipoRetorno($arregloObj, $this->tipoBase);
    }

    private function evaluarValores(array $expresiones, Entorno $entorno): array {
        $resultado = [];
        foreach ($expresiones as $exp) {
            if (is_array($exp)) {
                // Es un sub-arreglo (ej: las llaves internas de {{1, 2}, {3, 4}})
                $resultado[] = $this->evaluarValores($exp, $entorno);
            } else {
                // Es una expresión normal (ej: 1, 2, "hola", variableX)
                $res = $exp->ejecutar($entorno);
                
                if ($res->tipo !== $this->tipoBase) {
                    Salida::$errores[] = "❌ Error [Línea {$this->linea}]: Todos los elementos del arreglo deben ser de tipo {$this->tipoBase->name}.";
                    Salida::$salidasConsola[] = "❌ Error [Línea {$this->linea}]: Todos los elementos del arreglo deben ser de tipo {$this->tipoBase->name}.";
                }
                
                $resultado[] = $res->valor;
            }
        }
        return $resultado;
    }
}