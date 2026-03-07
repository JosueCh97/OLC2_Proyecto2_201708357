<?php
// filepath: /home/josuelts/Escritorio/OLC2P1/BackEnd/src/Interprete/CustomErrorListener.php
namespace App\Interprete;

use Antlr\Antlr4\Runtime\Error\Listeners\BaseErrorListener;
use Antlr\Antlr4\Runtime\Recognizer;

class CustomErrorListener extends BaseErrorListener
{
    public bool $hayErrores = false;

    public function syntaxError(
        Recognizer $recognizer,
        $offendingSymbol,
        int $line,
        int $charPositionInLine,
        string $msg,
        $e = null
    ): void {
        $this->hayErrores = true;
        echo "❌ ERROR DE SINTAXIS [Línea $line, Columna $charPositionInLine]: $msg\n";
    }
}