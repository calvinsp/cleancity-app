<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// tambahkan use untuk middleware kustom kalau mau, optional:
// use App\Http\Middleware\AdminMiddleware;
// use App\Http\Middleware\PetugasMiddleware;
// use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Alias middleware route (dipanggil di routes dengan nama string)
        $middleware->alias([
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
            'petugas'  => \App\Http\Middleware\PetugasMiddleware::class,
            'user'     => \App\Http\Middleware\UserMiddleware::class,
            'setlocale'=> SetLocale::class, // <— ini yang baru
        ]);

        // Kalau nanti mau tambah global middleware / group middleware,
        // bisa juga diatur di sini (append, appendToGroup, dll). [web:102][web:111]
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
