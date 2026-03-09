<?php

namespace App\Expressions;


use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;

class Primitivo extends Expresion {
    public mixed $valor;
    public Tipo $tipo;

    public function __construct(int $linea, int $columna, mixed $valor, Tipo $tipo) {
        parent::__construct($linea, $columna, TipoExpresion::PRIMITIVO);
        $this->valor = $valor;
        $this->tipo = $tipo;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        // match es más limpio que switch en PHP 8
        $valorProcesado = match($this->tipo) {
            Tipo::ENTERO => intval($this->valor),
            Tipo::DECIMAL => floatval($this->valor),
            Tipo::BOOLEANO => (string)$this->valor === 'verdadero' || (string)$this->valor === 'true',
            Tipo::CARACTER => ord(substr((string)$this->valor, 0, 1)), // charCodeAt(0)
            Tipo::CADENA => (string)$this->valor,
            default => $this->valor,
        };

        return new TipoRetorno($valorProcesado, $this->tipo);
    }
}