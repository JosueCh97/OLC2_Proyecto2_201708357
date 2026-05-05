<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\Salida;

class ForRango extends Instruction {
    public string $id;
    public Expresion $start;
    public Expresion $end;
    public Bloque $bloque;

    public function __construct(int $linea, int $columna, string $id, Expresion $start, Expresion $end, Bloque $bloque) {
        parent::__construct($linea, $columna);
        $this->id     = $id;
        $this->start  = $start;
        $this->end    = $end;
        $this->bloque = $bloque;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $entornoFor = new Entorno($entorno, "Entorno ForRango");

        $startVal = $this->start->ejecutar($entornoFor);
        $endVal   = $this->end->ejecutar($entornoFor);

        if ($startVal->tipo !== Tipo::ENTERO || $endVal->tipo !== Tipo::ENTERO) {
            Salida::$errores[] = "❌ Error [Línea {$this->linea}]: El rango del 'for in' debe ser de tipo int32.";
            Salida::$salidasConsola[] = "❌ Error [Línea {$this->linea}]: El rango del 'for in' debe ser de tipo int32.";
            return null;
        }

        $from = (int)$startVal->valor;
        $to   = (int)$endVal->valor;

        $entornoFor->guardarVariable($this->id, $from, Tipo::ENTERO, $this->linea, $this->columna);

        for ($i = $from; $i <= $to; $i++) {
            $entornoFor->setVariable($this->id, $i);

            $result = $this->bloque->ejecutar($entornoFor);

            if (is_array($result) && isset($result["control"])) {
                if ($result["control"] === "BREAK") {
                    break;
                }
                if ($result["control"] === "CONTINUE") {
                    continue;
                }
                if ($result["control"] === "RETURN") {
                    return $result;
                }
            } elseif ($result !== null) {
                return $result;
            }
        }

        return null;
    }
}
