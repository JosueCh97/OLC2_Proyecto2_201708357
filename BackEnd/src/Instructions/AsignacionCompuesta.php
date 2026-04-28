<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\OperacionDominante;

class AsignacionCompuesta extends Instruction {
    public string $id;
    public string $operador;
    public ?Expresion $expresion; // Puede ser null si es ++ o --

    public function __construct(int $linea, int $columna, string $id, string $operador, ?Expresion $expresion = null) {
        parent::__construct($linea, $columna, TipoInstruccion::INCREMENTO); // O ASIGNACION_COMPUESTA
        $this->id = $id;
        $this->operador = $operador;
        $this->expresion = $expresion;
    }

    public function ejecutar(Entorno $entorno): mixed {
        // 1. Verificar que la variable existe
        $simbolo = $entorno->getVariable($this->id);
        
        if ($simbolo === null) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: La variable '{$this->id}' no existe.";
            Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: La variable '{$this->id}' no existe.";
            return null;
        }

        // 2. Verificar que no sea constante
        if (isset($simbolo->isConst) && $simbolo->isConst === true) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: No se puede modificar '{$this->id}' porque es constante.";
            Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: No se puede modificar '{$this->id}' porque es constante.";
            return null;
        }

        $valorActual = $simbolo->valor;
        $tipoActual = $simbolo->tipo;
        $nuevoValor = null;

        // 3. Casos de Incremento y Decremento (++, --)
        if ($this->operador === '++' || $this->operador === '--') {
            if ($tipoActual !== Tipo::ENTERO && $tipoActual !== Tipo::DECIMAL) {
                Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: Solo se pueden incrementar/decrementar números.";
                return null;
            }
            $nuevoValor = ($this->operador === '++') ? $valorActual + 1 : $valorActual - 1;
            Salida::$salidasConsola[] = "🔄 Modificación [Línea {$this->linea}]: {$this->id}{$this->operador}  -> Nuevo valor: {$nuevoValor}";
        } 
        
        // 4. Casos de Asignación Compuesta (+=, -=, *=, /=)
        else if ($this->expresion !== null) {
            $resultadoExp = $this->expresion->ejecutar($entorno);
            
            // Extraemos solo el signo matemático (+, -, *, /) del operador (+=, -=, etc.)
            $signoMatematico = substr($this->operador, 0, 1); 
            
            // Usamos tu matriz para validar tipos
            $tipoResultado = OperacionDominante::getTipo($tipoActual, $resultadoExp->tipo, $signoMatematico);
            
            if ($tipoResultado === Tipo::NIL) {
                Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: Tipos incompatibles para {$this->operador}";
                return null;
            }

            // Realizamos la matemática
            $nuevoValor = match($this->operador) {
                '+=' => ($tipoResultado === Tipo::CADENA) ? $valorActual . $resultadoExp->valor : $valorActual + $resultadoExp->valor,
                '-=' => $valorActual - $resultadoExp->valor,
                '*=' => $valorActual * $resultadoExp->valor,
                '/=' => $resultadoExp->valor != 0 ? $valorActual / $resultadoExp->valor : null,
                '%=' => $resultadoExp->valor != 0 ? $valorActual % $resultadoExp->valor : null,
                default => null
            };

            if ($nuevoValor === null && ($this->operador === '/=' || $this->operador === '%=')) {
                $detalle = $this->operador === '/=' ? 'División por cero' : 'Módulo por cero';
                Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: {$detalle} en asignación compuesta.";
                Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: {$detalle} en asignación compuesta.";
                return null;
            }

           // Salida::$salidasConsola[] = "🔄 Modificación [Línea {$this->linea}]: {$this->id} {$this->operador} {$resultadoExp->valor}  -> Nuevo valor: {$nuevoValor}";
        }

        // 5. Guardamos el nuevo valor en la memoria
        $entorno->setVariable($this->id, $nuevoValor);
        
        return null;
    }
}