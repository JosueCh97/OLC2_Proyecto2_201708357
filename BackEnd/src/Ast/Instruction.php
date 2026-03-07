<?php
namespace App\Ast;

use App\Entorno\Entorno;

abstract class Instruction{
    public int $linea;
    public int $columna;

    public function __construct(int $linea, int $columna) {
        $this->linea = $linea;
        $this->columna = $columna;
    }

    // Las instrucciones normalmente ejecutan acciones y devuelven mixed o null
    abstract public function ejecutar(Entorno $entorno): mixed;
}