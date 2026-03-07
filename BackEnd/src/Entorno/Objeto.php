<?php
namespace App\Entorno;

use App\Expresiones\Atributo;

class Objeto {
    public string $id;
    // En lugar de Map<string, Atributo> y Map<string, Metodo> usamos array
    public array $atributos; 
    public array $metodos;

    public function __construct(string $id) {
        $this->id = $id;
        $this->atributos = [];
        $this->metodos = [];
    }
}