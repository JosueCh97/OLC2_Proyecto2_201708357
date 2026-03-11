<?php
namespace App\Instructions;

use App\Ast\Expresion;

class Caso {
    /** @var Expresion[] */
    public array $condiciones;
    public Bloque $bloque;

    public function __construct(array $condiciones, Bloque $bloque) {
        $this->condiciones = $condiciones;
        $this->bloque = $bloque;
    }
}