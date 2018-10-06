<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        $fileName=$e->getFile();
        $errorLine=$e->getLine();

        $trace = $e->getTrace();

        $type = "Exception";

        if (method_exists($e, 'getSeverity')) {
            $severity = $e->getSeverity();
        } else {
            $severity = 0;
        }

        switch ($severity) {
            case E_WARNING:
                $type = 'PHP warning';
                break;
            case E_NOTICE:
                $type = 'PHP notice';
                break;
            case E_USER_ERROR:
                $type = 'User error';
                break;
            case E_USER_WARNING:
                $type = 'User warning';
                break;
            case E_USER_NOTICE:
                $type = 'User notice';
                break;
            case E_RECOVERABLE_ERROR:
                $type = 'Recoverable error';
                break;
            default:
                $type = 'PHP error';
        }

        $error=array(
            'class'=>get_class($e),
            'type'=>$type,
            'errorCode'=>$e->getCode(),
            'message'=>$e->getMessage(),
            'file'=>$fileName,
            'line'=>$errorLine,
            'trace'=>$e->getTraceAsString(),
        );

        $errorText = $error["message"] . "\n```".print_r($error, true). "```";
        

        if (app()->environment('production')) {
            notifyDev("*Exception*: $errorText", ":x:");
        }

        /*
        if (E_NOTICE & $severity || E_WARNING & $severity || E_CORE_WARNING & $severity || E_COMPILE_WARNING & $severity || E_USER_WARNING & $severity || E_USER_NOTICE & $severity){
            return;
        }
        */

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
