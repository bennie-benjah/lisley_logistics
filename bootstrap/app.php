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
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware
        $middleware->web(append: [
            // \App\Http\Middleware\LocalizationMiddleware::class,
        ]);

        $middleware->api(prepend: [
            // \App\Http\Middleware\CorsMiddleware::class,
            // \App\Http\Middleware\ThrottleApiRequests::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\AuthenticatedMiddleware::class, // For normal users
            // 'customer' => \App\Http\Middleware\CustomerMiddleware::class, // Alias for user
            'guest' => \App\Http\Middleware\GuestMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();