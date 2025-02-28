<?php

declare(strict_types=1);

$pluginFolder = __DIR__ . '/';
$outputFolder = __DIR__ . '/builds/';
$pluginYml = $pluginFolder . 'plugin.yml';

if (!file_exists($pluginYml)) {
    exit("Error: plugin.yml not found!\n");
}

$pluginData = yaml_parse(file_get_contents($pluginYml));
if (!isset($pluginData['name'])) {
    exit("Error: Failed to read plugin name from plugin.yml!\n");
}

$pharName = $pluginData['name'] . '.phar';
$outputPath = $outputFolder . $pharName;

if (!is_dir($outputFolder)) {
    mkdir($outputFolder, 0777, true);
}

if (file_exists($outputPath)) {
    unlink($outputPath);
}

try {
    $phar = new Phar($outputPath);
    $phar->startBuffering();

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pluginFolder));
    foreach ($iterator as $file) {
        if (!$file->isFile()) {
            continue;
        }

        $relativePath = substr($file->getPathname(), strlen($pluginFolder));
        if (str_starts_with($relativePath, 'vendor/')) {
            continue;
        }

        $phar->addFile($file->getPathname(), $relativePath);
    }

    $phar->setStub("<?php __HALT_COMPILER();");
    $phar->stopBuffering();

    echo "Build successful: $outputPath\n";
} catch (Exception $e) {
    exit("Error: " . $e->getMessage() . "\n");
}
