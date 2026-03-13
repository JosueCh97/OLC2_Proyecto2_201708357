<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo;

class AccesoArreglo extends Expresion {
    private string $id;
    /** @var Expresion[] */
    private array $expresionesIndices;

    public function __construct(int $linea, int $columna, string $id, array $expresionesIndices) {
        parent::__construct($linea, $columna, TipoExpresion::ACCESO_ARREGLO ?? 'ACCESO_ARREGLO');
        $this->id = $id;
        $this->expresionesIndices = $expresionesIndices;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $simbolo = $entorno->getVariable($this->id);

        if ($simbolo === null) {
            return $this->error("La variable '{$this->id}' no existe.");
        }

        // Verificamos que sea un arreglo
        if (!($simbolo->valor instanceof ValorArreglo)) {
            return $this->error("La variable '{$this->id}' no es un arreglo.");
        }

        $arregloObj = $simbolo->valor;
        $valoresActuales = $arregloObj->valores;

        // Validamos la cantidad de dimensiones
        if (count($this->expresionesIndices) !== count($arregloObj->dimensiones)) {
            return $this->error("Cantidad de índices incorrecta para el arreglo '{$this->id}'. Se esperaban " . count($arregloObj->dimensiones));
        }

        // Navegamos por los índices
        foreach ($this->expresionesIndices as $i => $expIndice) {
            $resultadoIndice = $expIndice->ejecutar($entorno);

            if ($resultadoIndice->tipo !== Tipo::ENTERO) {
                return $this->error("Los índices de un arreglo deben ser de tipo ENTERO.");
            }

            $indice = $resultadoIndice->valor;

            // Verificamos límites (Out of Bounds)
            if ($indice < 0 || $indice >= $arregloObj->dimensiones[$i]) {
                return $this->error("Índice {$indice} fuera de los límites en la dimensión {$i} para '{$this->id}'.");
            }

            // Entramos un nivel más profundo
            $valoresActuales = $valoresActuales[$indice];
        }

        // Al final, $valoresActuales tendrá el valor primitivo final (ej: 10, "hola", true)
        return new TipoRetorno($valoresActuales, $arregloObj->tipoBase);
    }

    private function error(string $mensaje): TipoRetorno {
        $msg = "❌ Error Semántico [Línea {$this->linea}]: " . $mensaje;
        Salida::$errores[] = $msg;
        Salida::$salidasConsola[] = $msg;
        return new TipoRetorno(null, Tipo::NIL);
    }

    // Método para agregar índices cuando hay múltiples dimensiones m[i][j]
    public function agregarIndice(Expresion $indice) {
        $this->expresionesIndices[] = $indice;
    }
}