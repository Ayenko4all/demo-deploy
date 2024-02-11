<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
//    public function register()
//    {
//        $this->reportable(function (Throwable $e) {
//        //
//        });
//    }

    public function render($request, Throwable $e)
    {
        if($e instanceof NotFoundHttpException) {
            return $this->respondWithError(
                'The requested URI is invalid.',
                Response::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof ModelNotFoundException) {
            /** @var ModelNotFoundException $exception */
            $model = new ReflectionClass($exception->getModel());

            return $this->respondWithError(
                $model->getShortName().' not found.',
                Response::HTTP_NOT_FOUND
            );
        }

        if($e instanceof AuthenticationException) {
            return $this->respondWithError(
                $e->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($e instanceof RequestException) {
            return $this->respondWithError(
                "{$e->getMessage()}",
                $e->getCode()
            );
        }

        if ($e instanceof ValidationException) {
            return $this->respondWithError(
                $e->validator->errors()->all(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($e instanceof AuthorizationException || $e instanceof UnauthorizedException) {
            return $this->respondWithError(
                $e->getMessage(),
                Response::HTTP_FORBIDDEN
            );
        }


        return $this->respondWithError(
            'Something went wrong. Try again',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Returns json response error.
     *
     * @param       $message
     * @param mixed $statusCode
     * @param mixed $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message, $statusCode, $headers = [])
    {
        return response()->json([
            'status'      => 'fail',
            'status_code' => $statusCode,
            'error'       => [
                'message' => $message,
            ],
        ], $statusCode, $headers);
    }
}
