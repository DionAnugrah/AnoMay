<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /*
        |----------------------------------------------------------------------
        | Alias Middleware Custom
        |----------------------------------------------------------------------
        | auth.json → ganti redirect login bawaan Laravel dengan JSON 401
        | role       → cek role user, pakai: middleware('role:admin')
        |----------------------------------------------------------------------
        */
        $middleware->alias([
            'auth.json' => \App\Http\Middleware\EnsureAuthenticated::class,
            'role'      => \App\Http\Middleware\CheckRole::class,
        ]);

        // hapus kalau sudah ada frontend nya
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
            'admin/*',
            'penjual/*',  // ← tambah ini
            'boss/*',     // ← tambah ini
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();