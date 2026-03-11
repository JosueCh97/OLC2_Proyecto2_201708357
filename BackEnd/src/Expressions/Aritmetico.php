<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\OperacionDominante;
use App\Utilities\Salida;

class Aritmetico extends Expresion {
    public ?Expresion $exp1; // Puede ser null para el operador unario negativo
    public string $signo;
    public Expresion $exp2;

    public function __construct(int $linea, int $columna, ?Expresion $exp1, string $signo, Expresion $exp2) {
        parent::__construct($linea, $columna, TipoExpresion::ARITMETICO);
        $this->exp1 = $exp1;
        $this->signo = $signo;
        $this->exp2 = $exp2;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        return match($this->signo) {
            '+' => $this->suma($entorno),
            '-' => $this->exp1 !== null ? $this->resta($entorno) : $this->negacionUnaria($entorno),
            '*' => $this->multiplicacion($entorno),
            '/' => $this->division($entorno),
            '%' => $this->modulo($entorno),
            '^' => $this->potencia($entorno),
            default => $this->error("Operador aritmético no reconocido: {$this->signo}")
        };
    }

    private function suma(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);
        $tipoRes = OperacionDominante::getTipo($valor1->tipo, $valor2->tipo, '+');

        if ($tipoRes === Tipo::NIL) {
            return $this->error("No se pueden sumar los tipos {$valor1->tipo->name} y {$valor2->tipo->name}");
        }

        if ($tipoRes === Tipo::CADENA) {
            $resultado = (string)$valor1->valor . (string)$valor2->valor;
            $this->registrarOperacion($valor1->valor, '+', $valor2->valor, $resultado);
            return new TipoRetorno($resultado, $tipoRes);
        }

        $resultado = $valor1->valor + $valor2->valor;
        $this->registrarOperacion($valor1->valor, '+', $valor2->valor, $resultado);
        return new TipoRetorno($resultado, $tipoRes);
    }

    private function resta(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);
        $tipoRes = OperacionDominante::getTipo($valor1->tipo, $valor2->tipo, '-');

        if ($tipoRes === Tipo::NIL) {
            return $this->error("No se pueden restar los tipos {$valor1->tipo->name} y {$valor2->tipo->name}");
        }

        $resultado = $valor1->valor - $valor2->valor;
        $this->registrarOperacion($valor1->valor, '-', $valor2->valor, $resultado);
        return new TipoRetorno($resultado, $tipoRes);
    }

    private function multiplicacion(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);
        $tipoRes = OperacionDominante::getTipo($valor1->tipo, $valor2->tipo, '*');

        if ($tipoRes === Tipo::NIL) {
            return $this->error("No se pueden multiplicar los tipos {$valor1->tipo->name} y {$valor2->tipo->name}");
        }

        // Multiplicación de Cadenas (Ej: 3 * "hola" o "hola" * 3)
        if ($tipoRes === Tipo::CADENA) {
            if ($valor1->tipo === Tipo::CADENA && $valor2->tipo === Tipo::ENTERO) {
                $resultado = str_repeat((string)$valor1->valor, (int)$valor2->valor);
                $this->registrarOperacion($valor1->valor, '*', $valor2->valor, $resultado);
                return new TipoRetorno($resultado, $tipoRes);
            }
            if ($valor1->tipo === Tipo::ENTERO && $valor2->tipo === Tipo::CADENA) {
                $resultado = str_repeat((string)$valor2->valor, (int)$valor1->valor);
                $this->registrarOperacion($valor1->valor, '*', $valor2->valor, $resultado);
                return new TipoRetorno($resultado, $tipoRes);
            }
        }

        $resultado = $valor1->valor * $valor2->valor;
        $this->registrarOperacion($valor1->valor, '*', $valor2->valor, $resultado);
        return new TipoRetorno($resultado, $tipoRes);
    }

    private function division(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);
        
        if ($valor2->valor == 0) {
            return $this->error("División por cero detectada");
        }

        $tipoRes = OperacionDominante::getTipo($valor1->tipo, $valor2->tipo, '/');

        if ($tipoRes === Tipo::NIL) {
            return $this->error("No se pueden dividir los tipos {$valor1->tipo->name} y {$valor2->tipo->name}");
        }

        // División de enteros devuelve entero
        if ($tipoRes === Tipo::ENTERO) {
            $resultado = intdiv((int)$valor1->valor, (int)$valor2->valor);
            $this->registrarOperacion($valor1->valor, '/', $valor2->valor, $resultado);
            return new TipoRetorno($resultado, $tipoRes);
        }

        $resultado = $valor1->valor / $valor2->valor;
        $this->registrarOperacion($valor1->valor, '/', $valor2->valor, $resultado);
        return new TipoRetorno($resultado, $tipoRes);
    }

    private function modulo(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);

        if ($valor2->valor == 0) {
            return $this->error("Módulo por cero detectado");
        }

        $tipoRes = OperacionDominante::getTipo($valor1->tipo, $valor2->tipo, '%');

        if ($tipoRes === Tipo::NIL) {
            return $this->error("No se puede aplicar módulo a los tipos {$valor1->tipo->name} y {$valor2->tipo->name}");
        }

        $resultado = $valor1->valor % $valor2->valor;
        $this->registrarOperacion($valor1->valor, '%', $valor2->valor, $resultado);
        return new TipoRetorno($resultado, $tipoRes);
    }

    private function potencia(Entorno $entorno): TipoRetorno {
        $valor1 = $this->exp1->ejecutar($entorno);
        $valor2 = $this->exp2->ejecutar($entorno);
        
        $tipoRes = $valor1->tipo === Tipo::DECIMAL || $valor2->tipo === Tipo::DECIMAL ? Tipo::DECIMAL : Tipo::ENTERO;

        if ($tipoRes === Tipo::ENTERO) {
            $resultado = (int) pow($valor1->valor, $valor2->valor);
            $this->registrarOperacion($valor1->valor, '^', $valor2->valor, $resultado);
            return new TipoRetorno($resultado, $tipoRes);
        }

        $resultado = pow($valor1->valor, $valor2->valor);
        $this->registrarOperacion($valor1->valor, '^', $valor2->valor, $resultado);
        return new TipoRetorno($resultado, $tipoRes);
    }

    private function negacionUnaria(Entorno $entorno): TipoRetorno {
        $valor = $this->exp2->ejecutar($entorno);
        
        if ($valor->tipo === Tipo::ENTERO || $valor->tipo === Tipo::DECIMAL || $valor->tipo === Tipo::CARACTER) {
            $resultado = -$valor->valor;
            $this->registrarOperacionUnaria('-', $valor->valor, $resultado);
            return new TipoRetorno($resultado, $valor->tipo);
        }

        return $this->error("Operación unaria (-) no soportada para el tipo {$valor->tipo->name}");
    }

    // Método centralizado para manejar errores semánticos dentro de operaciones
    private function error(string $mensaje): TipoRetorno {
        $msgError = "❌ Error Semántico [Línea {$this->linea}]: $mensaje";
        Salida::$errores[] = $msgError;
        Salida::$salidasConsola[] = $msgError;
        
        return new TipoRetorno(null, Tipo::NIL);
    }

    // Método para registrar las operaciones aritméticas en consola
    private function registrarOperacion($operando1, string $operador, $operando2, $resultado): void {
        $detalleOperacion = "📊 Operación [Línea {$this->linea}]: {$operando1} {$operador} {$operando2} = {$resultado}";
        Salida::$salidasConsola[] = $detalleOperacion;
    }

    // Método para registrar operaciones unarias
    private function registrarOperacionUnaria(string $operador, $operando, $resultado): void {
        $detalleOperacion = "📊 Operación Unaria [Línea {$this->linea}]: {$operador}{$operando} = {$resultado}";
        Salida::$salidasConsola[] = $detalleOperacion;
    }
}
