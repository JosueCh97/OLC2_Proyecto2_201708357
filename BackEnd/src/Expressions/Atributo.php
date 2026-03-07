<?php
namespace App\Expresiones;

class Atributo {
    public string $id;
    public string $tipo;
    public mixed $valor;

    public function __construct(string $id, string $tipo, mixed $valor = null) {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->valor = $valor;
    }
}