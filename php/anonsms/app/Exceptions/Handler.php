<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class, // %PSG: page hangs if we don't have this (???)
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];


    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        //return parent::render($request, $e);


        /*
        // https://laravel.io/forum/02-19-2015-laravel-5-custom-error-view-for-500
        // https://stackoverflow.com/questions/30605506/custom-error-page-not-showing-on-laravel-5
        //Handle TokenMismatch Error/session('csrf_error')
        if ($e instanceof TokenMismatchException) {
            return response()->view('auth.login', ['message' => 'any custom message'] );
        }
    
        if ($this->isHttpException($e)){       
            if($e instanceof NotFoundHttpException){
                return response()->view("errors.404");
            }
            return $this->renderHttpException($e);
        }
    
        return response()->view("errors.500");
         */

        /*
        if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }
         */

        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            // we don't want to use whoops for this...
            return parent::render($request, $e);
        }

        // https://mattstauffer.com/blog/bringing-whoops-back-to-laravel-5/
        if (config('app.debug')) {
            return $this->renderExceptionWithWhoops($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    /*
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
     */

}
