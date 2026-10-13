<?php

use App\Http\Middleware\ApplyRedirects;
use App\Http\Middleware\SecurityHeaders;
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
        $middleware->web(append: [SecurityHeaders::class]);
        $middleware->web(prepend: [ApplyRedirects::class]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->trustProxies(at: '*'); // Hostinger terminates TLS at a proxy
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
