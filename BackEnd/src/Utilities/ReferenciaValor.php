<?php
namespace App\Utilities;

class ReferenciaValor {
    public string $id;
    /** @var int[] */
    public array $indices;

    public function __construct(string $id, array $indices = []) {
        $this->id = $id;
        $this->indices = $indices;
    }
}