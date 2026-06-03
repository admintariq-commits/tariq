<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$basePath = dirname(__DIR__);
$cachePath = $basePath.'/bootstrap/cache';

if (! is_dir($cachePath)) {
    mkdir($cachePath, 0775, true);
}

if (is_dir($cachePath) && ! is_writable($cachePath)) {
    @chmod($cachePath, 0775);
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
