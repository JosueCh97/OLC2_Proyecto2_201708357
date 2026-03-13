<?php
// filepath: /home/josuelts/Escritorio/OLC2P1/BackEnd/src/Interprete/CustomErrorListener.php
namespace App\Interprete;

use Antlr\Antlr4\Runtime\Error\Listeners\BaseErrorListener;
use Antlr\Antlr4\Runtime\Lexer;
use Antlr\Antlr4\Runtime\Parser;
use Antlr\Antlr4\Runtime\Recognizer;

class CustomErrorListener extends BaseErrorListener
{
    public bool $hayErrores = false;
    public array $errores = [];
    public array $erroresLexicos = [];
    public array $erroresSintacticos = [];

    public function syntaxError(
        Recognizer $recognizer,
        $offendingSymbol,
        int $line,
        int $charPositionInLine,
        string $msg,
        $e = null
    ): void {
        $this->hayErrores = true;

        $entrada = [
            'linea' => $line,
            'columna' => $charPositionInLine,
            'descripcion' => $msg,
        ];

        if ($recognizer instanceof Lexer) {
            $this->erroresLexicos[] = $entrada;
        } elseif ($recognizer instanceof Parser) {
            $this->erroresSintacticos[] = $entrada;
        }

        $this->errores[] = $entrada;
    }
}