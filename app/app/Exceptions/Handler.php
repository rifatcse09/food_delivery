<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Illuminate\Http\Exceptions\PostTooLargeException;
use PDOException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson() && $request->is('api/*')) {
            // Handle API-specific errors
            return $this->apiException($exception);
        }

        return parent::render($request, $exception);

    }

    public function apiException($exception)
    {

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage() ?: 'You are not authorized to access this resource', 403);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->errorResponse('Entry not found', 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL can\'t be found', 404);
        }

        if ($exception instanceof ValidationException) {
            return $this->errorResponse($exception->getMessage(), 422, $exception->errors());
        }

        if ($exception instanceof PostTooLargeException) {
            return $this->errorResponse('File too large', $exception->getStatusCode());
        }

        if ($exception instanceof PDOException) {
            return $this->errorResponse('Database error', 422);
        }

        return $this->errorResponse('Something went wrong in the API request', 500);
    }


    protected function errorResponse($message, $statusCode, $errors = [])
    {
        return api([
            'errors' => $errors
        ])->fails($message, $statusCode);
    }
}
