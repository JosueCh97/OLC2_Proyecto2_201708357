<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;

class Relacional extends Expresion {
    public Expresion $exp1;
    public string $signo;
    public Expresion $exp2;

    public function __construct(int $linea, int $columna, Expresion $exp1, string $signo, Expresion $exp2) {
        parent::__construct($linea, $columna, TipoExpresion::RELACIONAL); // Asegúrate de tener RELACIONAL en tu enum
        $this->exp1 = $exp1;
        $this->signo = $signo;
        $this->exp2 = $exp2;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);

        // Si alguna variable no existe o hubo un error previo, abortamos la comparación
        if ($valor1->tipo === Tipo::NIL || $valor2->tipo === Tipo::NIL) {
            return $this->error("No se pueden comparar valores nulos o inexistentes.");
        }

        $val1 = $valor1->valor;
        $val2 = $valor2->valor;
        $resultado = false;

        // VERIFICACIÓN DE TIPOS (Golampi / Go es estricto)
        // Permitimos comparar números entre sí (Entero, Decimal, Runa)
        $esNumero1 = in_array($valor1->tipo, [Tipo::ENTERO, Tipo::DECIMAL, Tipo::CARACTER]);
        $esNumero2 = in_array($valor2->tipo, [Tipo::ENTERO, Tipo::DECIMAL, Tipo::CARACTER]);

        if (($esNumero1 && $esNumero2) || ($valor1->tipo === $valor2->tipo)) {
            // Evaluamos la comparación matemática o de cadenas
            $resultado = match($this->signo) {
                '==' => $val1 == $val2,
                '!=' => $val1 != $val2,
                '<'  => $val1 < $val2,
                '<=' => $val1 <= $val2,
                '>'  => $val1 > $val2,
                '>=' => $val1 >= $val2,
                default => false
            };
        } else {
            return $this->error("Tipos incompatibles para comparación: {$valor1->tipo->name} {$this->signo} {$valor2->tipo->name}");
        }

        // --- FORMATEO PARA LA CONSOLA ---
        // En PHP, el booleano 'false' se imprime como vacío y 'true' como '1'. Esto lo arregla:
        $strVal1 = is_bool($val1) ? ($val1 ? 'true' : 'false') : (string)$val1;
        $strVal2 = is_bool($val2) ? ($val2 ? 'true' : 'false') : (string)$val2;
        $strRes  = $resultado ? 'true' : 'false';

        // Imprimimos en el formato exacto que pediste
        Salida::$salidasConsola[] = "📊 Comparación [Línea {$this->linea}]: {$strVal1} {$this->signo} {$strVal2} = {$strRes}";

        // Toda operación relacional devuelve siempre un BOOLEANO
        return new TipoRetorno($resultado, Tipo::BOOLEANO);
    }

    private function error(string $mensaje): TipoRetorno {
        $msgError = "❌ Error Semántico [Línea {$this->linea}]: $mensaje";
        Salida::$errores[] = $msgError;
        Salida::$salidasConsola[] = $msgError;
        return new TipoRetorno(null, Tipo::NIL);
    }
}