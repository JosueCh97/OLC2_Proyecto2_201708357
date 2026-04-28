<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\ReferenciaValor;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\TipoExpresion;
use App\Utilities\TipoRetorno;
use App\Utilities\ValorArreglo;

class Desreferencia extends Expresion {
    public string $id;
    /** @var Expresion[] */
    public array $indices;

    public function __construct(int $linea, int $columna, string $id, array $indices) {
        parent::__construct($linea, $columna, TipoExpresion::DESREFERENCIA);
        $this->id = $id;
        $this->indices = $indices;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $simbolo = $entorno->getVariable($this->id);
        if ($simbolo === null) {
            return $this->error("La referencia '{$this->id}' no existe.");
        }

        if (!($simbolo->valor instanceof ReferenciaValor)) {
            return $this->error("La variable '{$this->id}' no contiene una referencia.");
        }

        $referencia = $simbolo->valor;
        $destino = $entorno->getVariable($referencia->id);
        if ($destino === null) {
            return $this->error("La referencia apunta a una variable inexistente '{$referencia->id}'.");
        }

        if (empty($referencia->indices)) {
            return new TipoRetorno($destino->valor, $destino->tipo);
        }

        if (!($destino->valor instanceof ValorArreglo)) {
            return $this->error("La referencia a '{$referencia->id}' esperaba un arreglo.");
        }

        $actual = $destino->valor->valores;
        foreach ($referencia->indices as $pos => $indice) {
            if (!is_array($actual) || !array_key_exists($indice, $actual)) {
                return $this->error("Índice {$indice} fuera de rango al desreferenciar '{$referencia->id}'.");
            }
            $actual = $actual[$indice];
        }

        return new TipoRetorno($actual, $destino->valor->tipoBase);
    }

    private function error(string $mensaje): TipoRetorno {
        $msg = "❌ Error Semántico [Línea {$this->linea}]: {$mensaje}";
        Salida::$errores[] = $msg;
        Salida::$salidasConsola[] = $msg;
        return new TipoRetorno(null, Tipo::NIL);
    }
}