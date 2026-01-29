<?php

use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if (! ($request->expectsJson() || $request->is('api/*'))) {
                return null;
            }

            if ($exception instanceof ValidationException) {
                $errors = [];
                foreach ($exception->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $errors[] = ['field' => $field, 'message' => $message];
                    }
                }

                return ApiResponse::error('Validation failed.', 422, $errors, 'VALIDATION_ERROR');
            }

            if ($exception instanceof AuthenticationException) {
                return ApiResponse::error('Unauthenticated.', 401, [], 'UNAUTHENTICATED');
            }

            if ($exception instanceof AuthorizationException) {
                return ApiResponse::error('Forbidden.', 403, [], 'FORBIDDEN');
            }

            if ($exception instanceof ModelNotFoundException) {
                return ApiResponse::error('Resource not found.', 404, [], 'NOT_FOUND');
            }

            if ($exception instanceof NotFoundHttpException) {
                return ApiResponse::error('Route not found.', 404, [], 'NOT_FOUND');
            }

            if ($exception instanceof HttpExceptionInterface) {
                return ApiResponse::error(
                    $exception->getMessage() ?: 'Request error.',
                    $exception->getStatusCode(),
                    [],
                    'HTTP_ERROR'
                );
            }

            if (config('app.debug')) {
                return ApiResponse::error($exception->getMessage(), 500, [], 'SERVER_ERROR');
            }

            return ApiResponse::error('Server error.', 500, [], 'SERVER_ERROR');
        });
    })->create();
