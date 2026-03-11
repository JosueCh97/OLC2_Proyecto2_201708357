<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;

class Bloque extends Instruction {
    /** @var Instruction[] */
    private array $instrucciones;

    public function __construct(int $linea, int $columna, array $instrucciones) {
        parent::__construct($linea, $columna, TipoInstruccion::BLOQUE_INSTRUCCIONES);
        $this->instrucciones = $instrucciones;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // Creamos el nuevo entorno hijo. Su padre es el entorno que entra por parámetro.
        $nuevoEntorno = new Entorno($entorno, "Bloque Local");

        foreach ($this->instrucciones as $instruccion) {
            if ($instruccion === null) {
                Salida::$salidasConsola[] = "⚠️ Advertencia [Línea {$this->linea}]: Se omitió una instrucción nula en bloque.";
                continue;
            }

            try {
                $resultado = $instruccion->ejecutar($nuevoEntorno);

                // Si la instrucción devolvió algo (Break, Continue, Retorno), 
                // cortamos la ejecución del bloque y propagamos el valor hacia arriba.
                if ($resultado !== null) {
                    return $resultado;
                }
            } catch (\Exception $e) {
                // Manejo de errores de ejecución (puedes conectarlo a tu clase Salida)
            }
        }

        // Si terminó todas las instrucciones normalmente, no devuelve nada
        return null;
    }
}