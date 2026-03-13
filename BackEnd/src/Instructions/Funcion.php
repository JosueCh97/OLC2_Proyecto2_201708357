<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Instructions\Bloque;

class Funcion extends Instruction {
    public string $nombre;
    /** @var \App\Expressions\Parametro[] */
    public array $parametros;
    public array $tiposRetorno; 
    public Bloque $bloque;

    public function __construct(int $linea, int $columna, string $nombre, array $parametros, array $tiposRetorno, Bloque $bloque) {
        parent::__construct($linea, $columna, TipoInstruccion::DECLARAR_FUNCION ?? 'DECLARAR_FUNCION');
        $this->nombre = $nombre;
        $this->parametros = $parametros;
        $this->tiposRetorno = $tiposRetorno;
        $this->bloque = $bloque;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // Guardamos la función en la memoria global para poder llamarla después
        $entorno->guardarFuncion($this->nombre, $this);
        return null;
    }
}