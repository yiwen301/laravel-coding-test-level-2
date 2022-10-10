<?php

namespace App\Exceptions;

use App\Classes\Modules\StructuredData\HandlesExceptionResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [//
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $throwable
     *
     * @return Response
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    public function render($request, Throwable $throwable): Response {
        // if the request was targeted towards the api, then handle the response differently
        if (strpos($request->getUri(), '/api') !== false) {
            return $this->container->make(HandlesExceptionResponse::class)->handle($throwable);
        }

        return parent::render($request, $throwable);
    }
}
