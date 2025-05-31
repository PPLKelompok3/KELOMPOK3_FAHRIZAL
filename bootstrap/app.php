<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'applicant' => \App\Http\Middleware\EnsureUserIsApplicant::class,
            'recruiter' => \App\Http\Middleware\EnsureUserIsRecruiter::class,
            'admin' => App\Http\Middleware\EnsureAdmin::class,
            'verified.recruiter' => App\Http\Middleware\EnsureRecruiterVerified::class,
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();