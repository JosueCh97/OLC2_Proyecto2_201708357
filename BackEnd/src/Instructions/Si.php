<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Tipo;
use App\Utilities\Salida;

class Si extends Instruction {
    private Expresion $condicion;
    private Instruction $bloqueIf; // Siempre será un objeto de tipo Bloque
    private ?Instruction $bloqueElse; // Puede ser un Bloque (else), un Si (else if), o null

    public function __construct(int $linea, int $columna, Expresion $condicion, Instruction $bloqueIf, ?Instruction $bloqueElse = null) {
        parent::__construct($linea, $columna, TipoInstruccion::SI);
        $this->condicion = $condicion;
        $this->bloqueIf = $bloqueIf;
        $this->bloqueElse = $bloqueElse;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // 1. Evaluamos la condición matemática/lógica
        $resultadoCondicion = $this->condicion->ejecutar($entorno);

        // 2. Validamos que la condición sea estrictamente un Booleano
        if ($resultadoCondicion->tipo !== Tipo::BOOLEANO) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: La condición del 'if' debe ser un booleano.";
            Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: La condición del 'if' debe ser un booleano.";
            return null;
        }

        // 3. Ejecutamos el bloque correspondiente
        if ($resultadoCondicion->valor === true) {
            // Se cumple el IF
            return $this->bloqueIf->ejecutar($entorno);
        } else if ($this->bloqueElse !== null) {
            // No se cumple el IF, pero tenemos un ELSE (que puede ser otro Si o un Bloque)
            return $this->bloqueElse->ejecutar($entorno);
        }

        return null;
    }
}