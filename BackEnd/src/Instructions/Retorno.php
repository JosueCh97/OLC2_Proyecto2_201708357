<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;

class Retorno extends Instruction {
    /** @var Expresion[] */
    public array $expresiones;

    public function __construct(int $linea, int $columna, array $expresiones) {
        parent::__construct($linea, $columna, TipoInstruccion::RETORNAR ?? 'RETORNO');
        $this->expresiones = $expresiones;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $valoresEvaluados = [];

        // Evaluamos cada expresión que haya después de la palabra "return"
        foreach ($this->expresiones as $exp) {
            $valoresEvaluados[] = $exp->ejecutar($entorno); // Guardamos el TipoRetorno completo
        }

        // Devolvemos una señal que tu clase Bloque o la Llamada atraparán
        return [
            "control" => "RETURN",
            "valores" => $valoresEvaluados
        ];
    }
}