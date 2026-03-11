<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Tipo;
use App\Utilities\Salida;

class Para extends Instruction {
    private ?Instruction $init;
    private ?Expresion $condicion;
    private ?Instruction $post;
    private Bloque $bloque;

    public function __construct(int $linea, int $columna, ?Instruction $init, ?Expresion $condicion, ?Instruction $post, Bloque $bloque) {
        parent::__construct($linea, $columna, TipoInstruccion::PARA ?? 'FOR');
        $this->init = $init;
        $this->condicion = $condicion;
        $this->post = $post;
        $this->bloque = $bloque;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // 1. Entorno principal del FOR (Aquí vivirá la variable $init si es una declaración)
        $entornoFor = new Entorno($entorno, "Entorno FOR");

        // 2. Ejecutamos la inicialización (ej: var i int32 = 0)
        if ($this->init !== null) {
            $this->init->ejecutar($entornoFor);
        }

        // 3. El Bucle Infinito (Nosotros controlamos cuándo se rompe)
        while (true) {
            
            // 4. Verificamos la condición (si existe)
            if ($this->condicion !== null) {
                $resultadoCondicion = $this->condicion->ejecutar($entornoFor);
                
                if ($resultadoCondicion->tipo !== Tipo::BOOLEANO) {
                    Salida::$errores[] = "❌ Error [Línea {$this->linea}]: La condición del 'for' debe ser booleana.";
                    Salida::$salidasConsola[] = "❌ Error [Línea {$this->linea}]: La condición del 'for' debe ser booleana.";
                    break;
                }
                
                // Si la condición es falsa, salimos del ciclo (como un while normal)
                if ($resultadoCondicion->valor === false) {
                    break;
                }
            }

            // 5. Ejecutamos el bloque de código (el Bloque creará su propio entorno hijo en cada vuelta)
            $resultadoBloque = $this->bloque->ejecutar($entornoFor);

            // 6. Atrapamos las señales de BREAK, CONTINUE o RETURN
            if (is_array($resultadoBloque) && isset($resultadoBloque["control"])) {
                if ($resultadoBloque["control"] === "BREAK") {
                    break; // Rompemos el ciclo while(true)
                }
                if ($resultadoBloque["control"] === "CONTINUE") {
                    // El continue salta directamente al paso de actualización (ej: i++)
                    if ($this->post !== null) {
                        $this->post->ejecutar($entornoFor);
                    }
                    continue; // Pasa a la siguiente iteración del while
                }
            } else if ($resultadoBloque !== null) {
                // Si devuelve otra cosa (ej. Return), lo propagamos hacia afuera
                return $resultadoBloque;
            }

            // 7. Ejecutamos la actualización al final de cada vuelta normal (ej: i++)
            if ($this->post !== null) {
                $this->post->ejecutar($entornoFor);
            }
        }

        return null; // El For terminó
    }
}