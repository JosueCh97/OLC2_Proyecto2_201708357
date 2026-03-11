<?php
namespace App\Instructions;
use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;

class Continuar extends Instruction {
    public function __construct(int $linea, int $columna) {
        parent::__construct($linea, $columna, TipoInstruccion::CONTINUAR ?? 'CONTINUE');
    }
    public function ejecutar(Entorno $entorno): mixed {
        return ["control" => "CONTINUE"];
    }
}