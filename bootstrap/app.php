<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\Http\Middleware\Lang::class,
        ]);
        $middleware->alias([
            'isActive' => \App\Http\Middleware\IsActive::class,
            'lang' => \App\Http\Middleware\Lang::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // custom unauthenticated exception
        if (request()->is('api/*')) {
            $exceptions->render(function (AuthenticationException $exception) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            });
            $exceptions->render(function (Exception $exception) {
                if (!$exception instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'message' => $exception->getMessage(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                    ], 500);
                }
            });
        }
    })->create();
