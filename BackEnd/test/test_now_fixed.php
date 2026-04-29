<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use App\Language\GolampiLexer;
use App\Language\GolampiParser;
use App\Interprete\CustomVisitor;
use App\Compiler\ARM64Generator;

$source = <<<'GO'
func main() {
    fmt.Println(now())
}
GO;

putenv('GOLAMPI_TEST_NOW_SEC=1609459200');

$input = InputStream::fromString($source);
$lexer = new GolampiLexer($input);
$tokens = new CommonTokenStream($lexer);
$parser = new GolampiParser($tokens);
$tree = $parser->inicio();

$visitor = new CustomVisitor();
$instrucciones = $visitor->visit($tree);

$gen = new ARM64Generator();
$asm = $gen->generar($instrucciones);

$tmpDir = sys_get_temp_dir() . '/golampi_now_test_' . getmypid();
if (!is_dir($tmpDir) && !mkdir($tmpDir, 0777, true) && !is_dir($tmpDir)) {
    fwrite(STDERR, "No se pudo crear el directorio temporal\n");
    exit(1);
}

$sFile = $tmpDir . '/test.s';
$oFile = $tmpDir . '/test.o';
$binFile = $tmpDir . '/test.bin';
file_put_contents($sFile, $asm);

exec('aarch64-linux-gnu-as ' . escapeshellarg($sFile) . ' -o ' . escapeshellarg($oFile), $asOut, $asCode);
if ($asCode !== 0) {
    fwrite(STDERR, "Fallo as\n" . implode("\n", $asOut) . "\n");
    exit($asCode);
}

exec('aarch64-linux-gnu-ld ' . escapeshellarg($oFile) . ' -o ' . escapeshellarg($binFile), $ldOut, $ldCode);
if ($ldCode !== 0) {
    fwrite(STDERR, "Fallo ld\n" . implode("\n", $ldOut) . "\n");
    exit($ldCode);
}

exec('qemu-aarch64 ' . escapeshellarg($binFile), $runOut, $runCode);
$output = implode("\n", $runOut);
if ($runCode !== 0) {
    fwrite(STDERR, "Fallo qemu\n" . $output . "\n");
    exit($runCode);
}

if (strpos($output, '2021-01-01 00:00:00') === false) {
    fwrite(STDERR, "Salida inesperada: {$output}\n");
    exit(1);
}

echo $output . PHP_EOL;
