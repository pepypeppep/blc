<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\Role::class,
            'role.admin' => \App\Http\Middleware\RoleAdmin::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'translation' => \App\Http\Middleware\SetLocaleMiddleware::class,
            'approved.instructor' => \App\Http\Middleware\ApprovedInstructorMiddleware::class
        ]);

        $middleware->group('api', [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
