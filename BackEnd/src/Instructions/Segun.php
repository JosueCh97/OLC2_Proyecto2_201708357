<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;

class Segun extends Instruction {
    public Expresion $condicionPrincipal;
    /** @var Caso[] */
    public array $casos;
    public ?Bloque $bloqueDefault;

    public function __construct(int $linea, int $columna, Expresion $condicionPrincipal, array $casos, ?Bloque $bloqueDefault = null) {
        // Agrega SEGUN o SWITCH a tu Enum TipoInstruccion
        parent::__construct($linea, $columna, TipoInstruccion::SEGUN);
        $this->condicionPrincipal = $condicionPrincipal;
        $this->casos = $casos;
        $this->bloqueDefault = $bloqueDefault;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // 1. Creamos un entorno local para todo el Switch
        $entornoLocal = new Entorno($entorno, "Bloque Switch");
        
        // 2. Evaluamos la expresión principal (ej: day)
        $valorPrincipal = $this->condicionPrincipal->ejecutar($entornoLocal);
        
        $casoEjecutado = false;

        // 3. Recorremos los casos para buscar coincidencias
        foreach ($this->casos as $caso) {
            foreach ($caso->condiciones as $cond) {
                $valorCaso = $cond->ejecutar($entornoLocal);
                
                // Si el valor coincide (ej: day == 1 o day == 2)
                if ($valorPrincipal->valor == $valorCaso->valor) {
                    
                    // Ejecutamos el bloque del caso
                    $resultado = $caso->bloque->ejecutar($entornoLocal);
                    $casoEjecutado = true;
                    
                    // Si el bloque devolvió un Return, Continue o Break de un for padre, lo propagamos
                    if ($resultado !== null) {
                        return $resultado;
                    }
                    
                    // En Go, salimos del switch al terminar un caso coincidente (no hay fallthrough automático)
                    break 2; // Rompe ambos 'foreach'
                }
            }
        }

        // 4. Si ningún caso coincidió y existe un default, lo ejecutamos
        if (!$casoEjecutado && $this->bloqueDefault !== null) {
            return $this->bloqueDefault->ejecutar($entornoLocal);
        }

        return null;
    }
}