<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            if (request()->expectsJson()) {
                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Resource not found'
                    ], 404);
                }

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Validation failed',
                        'errors' => $e->errors()
                    ], 422);
                }

                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Route not found'
                    ], 404);
                }

                return response()->json([
                    'status' => 'Error',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    }
}