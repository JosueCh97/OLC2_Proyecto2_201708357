<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$compiler = $root . '/compilar.php';
$testDir = __DIR__ . '/archivos prueba';
$tmpDir = sys_get_temp_dir() . '/golampi_suite_' . getmypid();

if (!is_dir($tmpDir) && !mkdir($tmpDir, 0777, true) && !is_dir($tmpDir)) {
    fwrite(STDERR, "No se pudo crear el directorio temporal: {$tmpDir}\n");
    exit(1);
}

function runCommand(string $command, ?string $cwd = null): array
{
    $descriptorSpec = [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptorSpec, $pipes, $cwd);
    if (!is_resource($process)) {
        return [1, '', 'No se pudo ejecutar el comando'];
    }

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exitCode = proc_close($process);

    return [$exitCode, $stdout, $stderr];
}

function extractExpectedOutput(string $source): ?array
{
    if (!preg_match('/\/\*([\s\S]*?)\*\/\s*$/', $source, $matches)) {
        return null;
    }

    $block = trim($matches[1], "\r\n");
    $lines = preg_split('/\R/', $block) ?: [];
    $expected = [];

    foreach ($lines as $line) {
        $line = preg_replace('/^\s*\* ?/', '', $line);
        $expected[] = rtrim((string)$line, "\r");
    }

    return $expected;
}

function normalizeOutput(string $output): array
{
    $output = rtrim($output, "\r\n");
    if ($output === '') {
        return [];
    }

    return preg_split('/\R/', $output) ?: [];
}

function lineMatches(string $expected, string $actual): bool
{
    if (str_contains($expected, '<NOW>')) {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', trim($actual));
    }

    return rtrim($expected) === rtrim($actual);
}

$files = glob($testDir . '/*.go') ?: [];
sort($files, SORT_NATURAL);

$total = 0;
$passed = 0;
$failed = 0;

foreach ($files as $file) {
    $total++;
    $base = pathinfo($file, PATHINFO_FILENAME);
    $asmFile = $tmpDir . '/' . $base . '.s';
    $objFile = $tmpDir . '/' . $base . '.o';
    $binFile = $tmpDir . '/' . $base;

    [$exitCode, $asmOut, $asmErr] = runCommand('php ' . escapeshellarg($compiler) . ' ' . escapeshellarg($file));
    if ($exitCode !== 0) {
        $failed++;
        echo "[FAIL] {$base} - compilación\n";
        echo trim($asmOut . PHP_EOL . $asmErr) . PHP_EOL;
        continue;
    }

    file_put_contents($asmFile, $asmOut);

    [$exitCode, $asOut, $asErr] = runCommand('aarch64-linux-gnu-as ' . escapeshellarg($asmFile) . ' -o ' . escapeshellarg($objFile));
    if ($exitCode !== 0) {
        $failed++;
        echo "[FAIL] {$base} - ensamblado\n";
        echo trim($asOut . PHP_EOL . $asErr) . PHP_EOL;
        continue;
    }

    [$exitCode, $ldOut, $ldErr] = runCommand('aarch64-linux-gnu-ld ' . escapeshellarg($objFile) . ' -o ' . escapeshellarg($binFile));
    if ($exitCode !== 0) {
        $failed++;
        echo "[FAIL] {$base} - link\n";
        echo trim($ldOut . PHP_EOL . $ldErr) . PHP_EOL;
        continue;
    }

    [$exitCode, $runOut, $runErr] = runCommand('qemu-aarch64 ' . escapeshellarg($binFile));
    $actualLines = normalizeOutput($runOut);
    $source = file_get_contents($file);
    $expectedLines = is_string($source) ? extractExpectedOutput($source) : null;

    if ($expectedLines === null) {
        $passed++;
        echo "[OK]   {$base} - sin salida esperada embebida; salida capturada:\n";
        echo $runOut . PHP_EOL;
        continue;
    }

    $match = true;
    $diffLine = null;

    if (count($expectedLines) !== count($actualLines)) {
        $match = false;
        $diffLine = 'cantidad de líneas diferente (esperadas ' . count($expectedLines) . ', obtenidas ' . count($actualLines) . ')';
    } else {
        foreach ($expectedLines as $index => $expectedLine) {
            $actualLine = $actualLines[$index] ?? '';
            if (!lineMatches($expectedLine, $actualLine)) {
                $match = false;
                $diffLine = 'línea ' . ($index + 1) . "\n  esperado: {$expectedLine}\n  actual:   {$actualLine}";
                break;
            }
        }
    }

    if ($match) {
        $passed++;
        echo "[OK]   {$base}\n";
    } else {
        $failed++;
        echo "[FAIL] {$base}\n";
        echo $diffLine . PHP_EOL;
        echo "--- salida real ---\n";
        echo $runOut . PHP_EOL;
    }
}

echo PHP_EOL;
echo "Resumen: {$passed} ok, {$failed} fallos, {$total} total\n";
exit($failed === 0 ? 0 : 1);
