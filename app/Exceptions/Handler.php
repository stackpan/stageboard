<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse | Response
    {
        if ($request->is('api/*')) {
            if ($e instanceof ModelNotFoundException) {
                $model = Str::ucfirst(ltrim(preg_replace('/[A-Z]/', ' $0', class_basename($e->getModel()))));

                return response()
                    ->json([
                        'message' => $model.' not found.'
                    ], 404);
            }

            if ($e instanceof ValidationException) {
                return response()
                    ->json([
                        'message' => $e->getMessage(),
                    ], 400);
            }
        }

        return parent::render($request, $e);
    }
}
