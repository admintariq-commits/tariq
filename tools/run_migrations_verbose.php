<?php
// Minimal helper to run artisan migrate programmatically and save output to storage/logs/migrate-run.log
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
ob_start();
$status = $kernel->call('migrate', ['--force' => true, '--verbose' => true]);
$output = ob_get_clean() . "\n" . $kernel->output();
$logPath = __DIR__ . '/../storage/logs/migrate-run.log';
file_put_contents($logPath, "STATUS={$status}\n" . $output);
echo "WROTE:" . $logPath . PHP_EOL;
exit($status);
