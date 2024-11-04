<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Session\Middleware\StartSession; // Import the session middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(RateLimitMiddleware::class); // Register your custom rate limiting middleware
        $middleware->append(StartSession::class); // Register the session middleware
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
