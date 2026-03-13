<?php
namespace App\Expressions;

use App\Utilities\Tipo;

class Parametro {
    public string $id;
    public Tipo $tipoBase;
    public array $dimensiones;
    public bool $isPuntero;

    public function __construct(string $id, Tipo $tipoBase, array $dimensiones, bool $isPuntero) {
        $this->id = $id;
        $this->tipoBase = $tipoBase;
        $this->dimensiones = $dimensiones;
        $this->isPuntero = $isPuntero;
    }
}