<?php
require __DIR__ . '/vendor/autoload.php';
try {
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "APP_OK\n";
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
