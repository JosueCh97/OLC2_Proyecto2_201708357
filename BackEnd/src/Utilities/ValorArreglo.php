<?php
namespace App\Utilities;

class ValorArreglo {
    public Tipo $tipoBase;
    public array $dimensiones; // Ej: [2, 3] para una matriz 2x3
    public array $valores;     // El arreglo anidado de PHP

    public function __construct(Tipo $tipoBase, array $dimensiones, array $valores) {
        $this->tipoBase = $tipoBase;
        $this->dimensiones = $dimensiones;
        $this->valores = $valores;
    }
}