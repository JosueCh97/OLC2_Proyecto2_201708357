<?php
namespace App\Utilities;

// Equivalente a: export type TipoRetorno = {valor: any, tipo: Tipo};
use App\Utilities\Tipo;

class TipoRetorno {
    public mixed $valor;
    public Tipo $tipo;

    public function __construct(mixed $valor, Tipo $tipo) {
        $this->valor = $valor;
        $this->tipo = $tipo;
    }
}