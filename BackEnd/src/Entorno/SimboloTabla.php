<?php
namespace App\Entorno;

use App\Utilities\Tipo;

class SimboloTabla {
    public int $indice;
    public int $linea;
    public int $columna;
    public bool $isVariable;
    public bool $isPrimitive;
    public mixed $valor;
    public Tipo $tipo;
    public string $id;
    public string $nombreEntorno;

    public function __construct(
        int $linea, 
        int $columna, 
        bool $isVariable, 
        bool $isPrimitive, 
        mixed $valor, 
        Tipo $tipo, 
        string $id, 
        string $nombreEntorno
    ) {
        $this->linea = $linea;
        $this->columna = $columna;
        $this->isVariable = $isVariable;
        $this->isPrimitive = $isPrimitive;
        $this->valor = $valor;
        $this->tipo = $tipo;
        $this->id = $id;
        $this->nombreEntorno = $nombreEntorno;
        $this->indice = 0;
    }

    public function toString(): string {
        // str_pad es el equivalente a padEnd() en TypeScript
        return '║ ' . str_pad($this->id, 20) . 
               ' ║ ' . str_pad($this->getTipo($this->tipo), 10) . 
               ' ║ ' . str_pad($this->nombreEntorno, 15) . 
               ' ║ ' . str_pad((string)$this->linea, 5) . 
               ' ║ ' . str_pad((string)$this->columna, 7) . ' ║ ';
    }

    public function hash(): string {
        // Convertimos booleanos a texto para que el hash sea exacto
        $isVar = $this->isVariable ? 'true' : 'false';
        $isPrim = $this->isPrimitive ? 'true' : 'false';
        $tipoStr = $this->tipo->name; // Obtiene el nombre del Enum en texto

        return "{$this->id}_{$tipoStr}_{$this->nombreEntorno}_{$this->linea}_{$this->columna}_{$isVar}_{$isPrim}";
    }

    public function getTipo(Tipo $tipo): string {
        // match es la versión moderna y limpia del switch en PHP 8+
        return match($tipo) {
            Tipo::ENTERO => "entero",
            Tipo::DECIMAL => "decimal",
            Tipo::BOOLEANO => "booleano",
            Tipo::CARACTER => "caracter",
            Tipo::CADENA => "cadena",
            Tipo::NULL => "null",
            default => "desconocido",
        };
    }
}