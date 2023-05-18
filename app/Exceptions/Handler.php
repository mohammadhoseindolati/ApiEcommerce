<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser ;
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException){

            DB::rollback();
            return $this->errorResponse($e->getMessage() , 404 ) ;
        }

        if ($e instanceof Error){

            DB::rollback();
            return $this->errorResponse($e->getMessage() , 500 ) ;
        }

        if ($e instanceof NotFoundHttpException) {
            DB::rollback();
            return $this->errorResponse($e->getMessage() , 404 ) ;
        }

        if ($e instanceof MethodNotAllowedHttpException) {

            DB::rollback();
            return $this->errorResponse($e->getMessage() , 500 ) ;
        }

        DB::rollback();
        return $this->errorResponse($e->getMessage() , 500) ;
    }
}
