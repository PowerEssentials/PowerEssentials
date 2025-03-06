<?php

/*
 *   ____                        _____                    _   _       _
 *  |  _ \ _____      _____ _ __| ____|___ ___  ___ _ __ | |_(_) __ _| |___
 *  | |_) / _ \ \ /\ / / _ \ '__|  _| / __/ __|/ _ \ '_ \| __| |/ _` | / __|
 *  |  __/ (_) \ V  V /  __/ |  | |___\__ \__ \  __/ | | | |_| | (_| | \__ \
 *  |_|   \___/ \_/\_/ \___|_|  |_____|___/___/\___|_| |_|\__|_|\__,_|_|___/
 *
 *
 * This file is part of PowerEssentials plugins.
 *
 * (c) Angga7Togk <kiplihode123321@gmail.com>
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

declare(strict_types = 1);

$pluginFolder = __DIR__ . '/';
$outputFolder = __DIR__ . '/builds/';
$pluginYml    = $pluginFolder . 'plugin.yml';

if (!file_exists($pluginYml)) {
    exit("Error: plugin.yml not found!\n");
}

$pluginData = yaml_parse(file_get_contents($pluginYml));
if (!isset($pluginData['name'])) {
    exit("Error: Failed to read plugin name from plugin.yml!\n");
}

$pharName   = $pluginData['name'] . '.phar';
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

    $phar->setStub('<?php __HALT_COMPILER();');
    $phar->stopBuffering();

    echo "Build successful: $outputPath\n";
} catch (Exception $e) {
    exit('Error: ' . $e->getMessage() . "\n");
}
