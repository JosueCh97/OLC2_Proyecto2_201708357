<?php
namespace App\Instructions;


use App\Ast\Instruction;
use App\Entorno\Entorno;
// use App\Expresiones\Parametro; // Descomentar cuando crees Parametro.php
use App\Utilities\Tipo;
use App\Utilities\TipoInstruccion;

class Funcion extends Instruction {
    public string $nombreFuncion;
    public Tipo $tipo;
    public array $parametros; // Arreglo de objetos Parametro
    public array $instrucciones; // Arreglo de objetos Instruccion

    public function __construct(
        int $linea, 
        int $columna, 
        string $nombreFuncion, 
        Tipo $tipo, 
        array $parametros, 
        array $instrucciones
    ) {
        // Llamada al constructor de la clase abstracta Instruccion
        parent::__construct($linea, $columna, TipoInstruccion::DECLARAR_FUNCION);
        
        $this->nombreFuncion = $nombreFuncion;
        $this->tipo = $tipo;
        $this->parametros = $parametros;
        $this->instrucciones = $instrucciones;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $entorno->guardarFuncion($this->nombreFuncion, $this);
        return null;
    }
}