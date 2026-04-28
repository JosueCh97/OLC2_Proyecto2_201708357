<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\ValorArreglo;

class Imprimir extends Instruction {
    /** @var Expresion[] */
    public array $expresiones;

    /** true = fmt.Println (agrega \n), false = fmt.Print (sin \n) */
    public bool $esNewLine;

    public function __construct(int $linea, int $columna, array $expresiones, bool $esNewLine = true) {
        parent::__construct($linea, $columna, TipoInstruccion::IMPRIMIR);
        $this->expresiones = $expresiones;
        $this->esNewLine   = $esNewLine;
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
                $valoresAImprimir[] = $this->formatearValor($resultado->valor);
            }
        }

        // Unimos todos los valores con un espacio (al estilo fmt.Println de Go)
        $salidaFinal = implode(" ", $valoresAImprimir);

        // Lo mandamos a tu clase estática de Salida
        Salida::$salidasConsola[] = "\t " . $salidaFinal;

        return null;
    }

    private function formatearValor(mixed $valor): string {
        if ($valor instanceof ValorArreglo) {
            return $this->formatearArreglo($valor->valores);
        }

        if (is_bool($valor)) {
            return $valor ? 'true' : 'false';
        }

        if ($valor === null) {
            return 'nil';
        }

        if (is_array($valor)) {
            return $this->formatearArreglo($valor);
        }

        if (is_object($valor)) {
            return '[objeto]';
        }

        return (string) $valor;
    }

    private function formatearArreglo(array $arreglo): string {
        $partes = [];

        foreach ($arreglo as $item) {
            if ($item instanceof ValorArreglo) {
                $partes[] = $this->formatearArreglo($item->valores);
                continue;
            }

            if (is_array($item)) {
                $partes[] = $this->formatearArreglo($item);
                continue;
            }

            if (is_bool($item)) {
                $partes[] = $item ? 'true' : 'false';
                continue;
            }

            if ($item === null) {
                $partes[] = 'nil';
                continue;
            }

            $partes[] = (string) $item;
        }

        return '[' . implode(', ', $partes) . ']';
    }
}