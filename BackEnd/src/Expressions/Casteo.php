<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\TipoExpresion;
use App\Utilities\TipoRetorno;

class Casteo extends Expresion {
    public Tipo $tipoDestino;
    public Expresion $expresion;

    public function __construct(int $linea, int $columna, Tipo $tipoDestino, Expresion $expresion) {
        parent::__construct($linea, $columna, TipoExpresion::CASTEO);
        $this->tipoDestino = $tipoDestino;
        $this->expresion = $expresion;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $valor = $this->expresion->ejecutar($entorno);

        return match ($this->tipoDestino) {
            Tipo::ENTERO => $this->aEntero($valor),
            Tipo::DECIMAL => $this->aDecimal($valor),
            Tipo::BOOLEANO => $this->aBooleano($valor),
            Tipo::CADENA => $this->aCadena($valor),
            Tipo::CARACTER => $this->aRune($valor),
            default => $this->error('Tipo de casteo no soportado.'),
        };
    }

    private function aEntero(TipoRetorno $valor): TipoRetorno {
        if ($valor->tipo === Tipo::ENTERO || $valor->tipo === Tipo::CARACTER) {
            return new TipoRetorno((int) $valor->valor, Tipo::ENTERO);
        }

        if ($valor->tipo === Tipo::DECIMAL) {
            return new TipoRetorno((int) $valor->valor, Tipo::ENTERO);
        }

        if ($valor->tipo === Tipo::BOOLEANO) {
            return new TipoRetorno($valor->valor ? 1 : 0, Tipo::ENTERO);
        }

        if ($valor->tipo === Tipo::CADENA && is_numeric($valor->valor)) {
            return new TipoRetorno((int) $valor->valor, Tipo::ENTERO);
        }

        return $this->error('No se puede convertir a int32 desde ' . $valor->tipo->name . '.');
    }

    private function aDecimal(TipoRetorno $valor): TipoRetorno {
        if ($valor->tipo === Tipo::ENTERO || $valor->tipo === Tipo::DECIMAL || $valor->tipo === Tipo::CARACTER) {
            return new TipoRetorno((float) $valor->valor, Tipo::DECIMAL);
        }

        if ($valor->tipo === Tipo::BOOLEANO) {
            return new TipoRetorno($valor->valor ? 1.0 : 0.0, Tipo::DECIMAL);
        }

        if ($valor->tipo === Tipo::CADENA && is_numeric($valor->valor)) {
            return new TipoRetorno((float) $valor->valor, Tipo::DECIMAL);
        }

        return $this->error('No se puede convertir a float32 desde ' . $valor->tipo->name . '.');
    }

    private function aBooleano(TipoRetorno $valor): TipoRetorno {
        if ($valor->tipo === Tipo::BOOLEANO) {
            return new TipoRetorno((bool) $valor->valor, Tipo::BOOLEANO);
        }

        if ($valor->tipo === Tipo::ENTERO || $valor->tipo === Tipo::DECIMAL || $valor->tipo === Tipo::CARACTER) {
            return new TipoRetorno(((float) $valor->valor) != 0.0, Tipo::BOOLEANO);
        }

        if ($valor->tipo === Tipo::CADENA) {
            $normalizado = strtolower(trim((string) $valor->valor));
            if ($normalizado === 'true' || $normalizado === '1') {
                return new TipoRetorno(true, Tipo::BOOLEANO);
            }
            if ($normalizado === 'false' || $normalizado === '0' || $normalizado === '') {
                return new TipoRetorno(false, Tipo::BOOLEANO);
            }
        }

        return $this->error('No se puede convertir a bool desde ' . $valor->tipo->name . '.');
    }

    private function aCadena(TipoRetorno $valor): TipoRetorno {
        if ($valor->tipo === Tipo::CARACTER) {
            return new TipoRetorno(chr((int) $valor->valor), Tipo::CADENA);
        }

        if ($valor->tipo === Tipo::NIL) {
            return new TipoRetorno('nil', Tipo::CADENA);
        }

        return new TipoRetorno((string) $valor->valor, Tipo::CADENA);
    }

    private function aRune(TipoRetorno $valor): TipoRetorno {
        if ($valor->tipo === Tipo::CARACTER || $valor->tipo === Tipo::ENTERO) {
            return new TipoRetorno((int) $valor->valor, Tipo::CARACTER);
        }

        if ($valor->tipo === Tipo::DECIMAL) {
            return new TipoRetorno((int) $valor->valor, Tipo::CARACTER);
        }

        if ($valor->tipo === Tipo::CADENA) {
            $texto = (string) $valor->valor;
            if ($texto !== '') {
                return new TipoRetorno(ord($texto[0]), Tipo::CARACTER);
            }
        }

        return $this->error('No se puede convertir a rune desde ' . $valor->tipo->name . '.');
    }

    private function error(string $mensaje): TipoRetorno {
        $msg = "❌ Error Semántico [Línea {$this->linea}]: {$mensaje}";
        Salida::$errores[] = $msg;
        Salida::$salidasConsola[] = $msg;
        return new TipoRetorno(null, Tipo::NIL);
    }
}
