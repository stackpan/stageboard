<?php

namespace App\Exceptions;

use App\Http\Resources\UserResource;
use Throwable;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $this->renderable(function (AbstractApplicationErrorException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $this->renderable(function (HttpException $e, Request $request) {
            if (!app()->environment(['local', 'testing']) && in_array($e->getStatusCode(), [403, 404, 500, 503])) {
                return Inertia::render('Error', [
                    'auth' => [
                        'user' => is_null($request->user()) ? null : new UserResource($request->user()),
                    ],
                    'status' => $e->getStatusCode()
                ])
                ->toResponse($request)
                ->setStatusCode($e->getStatusCode());
            } else if ($e->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'The page is expired, please try again',
                ]);
            }
        });
    }

    public function render($request, Throwable $e): JsonResponse | Response | RedirectResponse | InertiaResponse
    {
        if ($request->is('api/*')) {
            if ($e instanceof RecordsNotFoundException) {
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
