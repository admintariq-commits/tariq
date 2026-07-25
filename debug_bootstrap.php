<?php
require __DIR__.'/vendor/autoload.php';

try {
    $app = require __DIR__.'/bootstrap/app.php';
    echo "BOOTSTRAP_OK\n";
} catch (Throwable $e) {
    $out = get_class($e) . ": " . $e->getMessage() . PHP_EOL . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    echo $out;
    @file_put_contents(__DIR__.'/debug_trace.txt', $out);
    exit(1);
}
