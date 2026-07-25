<?php
require __DIR__.'/vendor/autoload.php';
try {
    $app = require __DIR__.'/bootstrap/app.php';
    echo "BOOT_OK\n";
    file_put_contents(__DIR__.'/run_bootstrap2_out.txt', "BOOT_OK\n");
} catch (Throwable $e) {
    $s = get_class($e).": ". $e->getMessage() . "\n" . $e->getTraceAsString();
    echo $s;
    file_put_contents(__DIR__.'/run_bootstrap2_out.txt', $s);
    exit(1);
}
