<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\Tipo;

class Imprimir extends Instruction {
    /** @var Expresion[] */
    private array $expresiones;

    public function __construct(int $linea, int $columna, array $expresiones) {
        parent::__construct($linea, $columna, TipoInstruccion::IMPRIMIR);
        $this->expresiones = $expresiones;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $valoresAImprimir = [];

        foreach ($this->expresiones as $exp) {
            $resultado = $exp->ejecutar($entorno);

            // Manejamos los valores nulos o errores
            if ($resultado->tipo === Tipo::NIL) {
                $valoresAImprimir[] = "null";
                continue;
            }

            // En PHP, los booleanos al imprimirse se vuelven "1" o "", 
            // así que forzamos que se impriman como "true" o "false"
            if ($resultado->tipo === Tipo::BOOLEANO) {
                $valoresAImprimir[] = $resultado->valor ? "true" : "false";
            } else {
                $valoresAImprimir[] = (string)$resultado->valor;
            }
        }

        // Unimos todos los valores con un espacio (al estilo fmt.Println de Go)
        $salidaFinal = implode(" ", $valoresAImprimir);

        // Lo mandamos a tu clase estática de Salida
        Salida::$salidasConsola[] = "🖨️ " . $salidaFinal;

        return null;
    }
}