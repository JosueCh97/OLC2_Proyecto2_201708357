<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Expressions\Llamada;

class LlamadaInstr extends Instruction {
    private Llamada $llamada;

    public function __construct(int $linea, int $columna, Llamada $llamada) {
        parent::__construct($linea, $columna, TipoInstruccion::LLAMADA_INSTR ?? 'LLAMADA_INSTR');
        $this->llamada = $llamada;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // Ejecutamos la llamada pero ignoramos lo que retorna
        $this->llamada->ejecutar($entorno);
        return null;
    }
}