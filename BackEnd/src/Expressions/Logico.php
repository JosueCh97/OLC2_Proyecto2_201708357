<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;

class Logico extends Expresion {
    public ?Expresion $exp1; // Puede ser null para el operador NOT (!)
    public string $signo;
    public Expresion $exp2;

    public function __construct(int $linea, int $columna, ?Expresion $exp1, string $signo, Expresion $exp2) {
        // Asegúrate de tener LOGICO en tu Enum de TipoExpresion
        parent::__construct($linea, $columna, TipoExpresion::LOGICO); 
        $this->exp1 = $exp1;
        $this->signo = $signo;
        $this->exp2 = $exp2;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        return match($this->signo) {
            '&&' => $this->and($entorno),
            '||' => $this->or($entorno),
            '!'  => $this->not($entorno),
            default => $this->error("Operador lógico no reconocido: {$this->signo}")
        };
    }

    private function and(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        if ($valor1->tipo !== Tipo::BOOLEANO) {
            return $this->error("Tipos incompatibles para &&. Se esperaban valores BOOLEANOS.");
        }

        // Cortocircuito real: si el lado izquierdo es false, no evaluamos el derecho.
        if ($valor1->valor === false) {
            $this->imprimirConsola(false, '&&', false, false);
            return new TipoRetorno(false, Tipo::BOOLEANO);
        }

        $valor2 = $this->exp2->ejecutar($entorno);
        if ($valor2->tipo !== Tipo::BOOLEANO) {
            return $this->error("Tipos incompatibles para &&. Se esperaban valores BOOLEANOS.");
        }

        $resultado = $valor1->valor && $valor2->valor;
        $this->imprimirConsola($valor1->valor, '&&', $valor2->valor, $resultado);
        
        return new TipoRetorno($resultado, Tipo::BOOLEANO);
    }

    private function or(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        if ($valor1->tipo !== Tipo::BOOLEANO) {
            return $this->error("Tipos incompatibles para ||. Se esperaban valores BOOLEANOS.");
        }

        // Cortocircuito real: si el lado izquierdo es true, no evaluamos el derecho.
        if ($valor1->valor === true) {
            $this->imprimirConsola(true, '||', true, true);
            return new TipoRetorno(true, Tipo::BOOLEANO);
        }

        $valor2 = $this->exp2->ejecutar($entorno);
        if ($valor2->tipo !== Tipo::BOOLEANO) {
            return $this->error("Tipos incompatibles para ||. Se esperaban valores BOOLEANOS.");
        }

        $resultado = $valor1->valor || $valor2->valor;
        $this->imprimirConsola($valor1->valor, '||', $valor2->valor, $resultado);
        
        return new TipoRetorno($resultado, Tipo::BOOLEANO);
    }

    private function not(Entorno $entorno): TipoRetorno {
        $valor = $this->exp2->ejecutar($entorno);

        if ($valor->tipo !== Tipo::BOOLEANO) {
            return $this->error("Tipo incompatible para !. Se esperaba BOOLEANO.");
        }

        $resultado = !$valor->valor;
        
        $strVal = $valor->valor ? 'true' : 'false';
        $strRes = $resultado ? 'true' : 'false';
      //  Salida::$salidasConsola[] = "🧠 Lógica [Línea {$this->linea}]: !{$strVal} = {$strRes}";
        
        return new TipoRetorno($resultado, Tipo::BOOLEANO);
    }

    private function imprimirConsola(bool $val1, string $op, bool $val2, bool $res): void {
        $str1 = $val1 ? 'true' : 'false';
        $str2 = $val2 ? 'true' : 'false';
        $strRes = $res ? 'true' : 'false';
       // Salida::$salidasConsola[] = "🧠 Lógica [Línea {$this->linea}]: {$str1} {$op} {$str2} = {$strRes}";
    }

    private function error(string $mensaje): TipoRetorno {
        $msgError = "❌ Error Semántico [Línea {$this->linea}]: $mensaje";
        Salida::$errores[] = $msgError;
        Salida::$salidasConsola[] = $msgError;
        return new TipoRetorno(null, Tipo::NIL);
    }
}