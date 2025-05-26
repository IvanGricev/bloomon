<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return $this->handleApiException($e);
            }

            return $this->handleWebException($e);
        });
    }

    protected function handleApiException(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Something went wrong.',
            'error' => $e->getMessage(),
        ], 500);
    }

    protected function handleWebException(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        if ($e instanceof AuthenticationException) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        if ($e instanceof ModelNotFoundException) {
            return redirect()->back()
                ->with('error', 'The requested resource was not found.');
        }

        if ($e instanceof NotFoundHttpException) {
            return redirect()->back()
                ->with('error', 'The requested page was not found.');
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->back()
                ->with('error', 'Your session has expired. Please try again.');
        }

        return redirect()->back()
            ->with('error', 'An unexpected error occurred. Please try again later.');
    }
} 