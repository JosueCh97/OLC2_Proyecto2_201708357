<?php
namespace App\Ast;

use App\Entorno\Entorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;


abstract class Expresion {
    public int $linea;
    public int $columna;
    public TipoExpresion $tipoExpresion;
    public TipoExpresion $tipoExpresion2;

    public function __construct(int $linea, int $columna, TipoExpresion $tipoExpresion, TipoExpresion $tipoExpresion2 = TipoExpresion::NULL) {
        $this->linea = $linea;
        $this->columna = $columna;
        $this->tipoExpresion = $tipoExpresion;
        $this->tipoExpresion2 = $tipoExpresion2;
    }

    // Toda expresión debe retornar un valor y su tipo
    abstract public function ejecutar(Entorno $entorno): TipoRetorno;
}