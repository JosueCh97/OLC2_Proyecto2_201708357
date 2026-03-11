<?php
namespace App\Instructions;
use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;

class Romper extends Instruction {
    public function __construct(int $linea, int $columna) {
        parent::__construct($linea, $columna, TipoInstruccion::DETENER ?? 'BREAK');
    }
    public function ejecutar(Entorno $entorno): mixed {
        // Devolvemos una señal que el FOR va a atrapar
        return ["control" => "BREAK"];
    }
}