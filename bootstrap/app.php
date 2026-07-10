<?php

use App\Exceptions\DuplicateAssociationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                    'code' => 'VALIDATION_ERROR',
                ], 422);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found.',
                    'errors' => null,
                    'code' => 'NOT_FOUND',
                ], 404);
            }
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            $sqlState = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;
            $isUniqueViolation = in_array($sqlState, ['23000', '23505'], true)
                || in_array($driverCode, [1062, 19], true)
                || str_contains(strtolower($e->getMessage()), 'unique');

            if ($isUniqueViolation) {
                return (new DuplicateAssociationException(
                    'Uniqueness violation: record or association already exists.'
                ))->render();
            }

            return response()->json([
                'message' => 'Database error.',
                'errors' => null,
                'code' => 'DATABASE_ERROR',
            ], 500);
        });
    })->create();
