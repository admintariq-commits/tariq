<?php
$dir = new RecursiveDirectoryIterator(__DIR__);
$it = new RecursiveIteratorIterator($dir);
$pattern = '/\benv\b(?!\s*\()/i';
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $name = $file->getPathname();
    if (!str_ends_with($name, '.php')) continue;
    if (str_contains($name, DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR)) continue;
    $lines = file($name);
    foreach ($lines as $i => $line) {
        if (preg_match($pattern, $line)) {
            echo "$name:" . ($i+1) . ": " . trim($line) . PHP_EOL;
        }
    }
}
