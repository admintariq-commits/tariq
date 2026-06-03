<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$basePath = dirname(__DIR__);
$requiredDirectories = [
    $basePath.'/bootstrap/cache',
    $basePath.'/storage/framework/cache/data',
    $basePath.'/storage/framework/sessions',
    $basePath.'/storage/framework/views',
    $basePath.'/storage/logs',
];

foreach ($requiredDirectories as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }

    if (is_dir($dir) && ! is_writable($dir)) {
        @chmod($dir, 0775);
    }
}

return Application::configure(basePath: $basePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
