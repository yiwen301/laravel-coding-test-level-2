<?php

declare(strict_types=1);

namespace App\Classes\Modules\StructuredData;

use App\Classes\Modules\StructuredData\Transformers\ErrorTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class HandlesExceptionResponse {
    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * HandlesExceptionResponse constructor.
     *
     * @param HandlesApiResponseData $handlesApiResponseData
     */
    public function __construct(HandlesApiResponseData $handlesApiResponseData) {
        $this->handlesApiResponseData = $handlesApiResponseData;
    }

    public function handle(\Throwable $throwable): JsonResponse {
        $transformer = $this->makeTransformer($throwable);
        $message     = $this->handlesApiResponseData->returnError($throwable, $transformer);

        return new JsonResponse($message, $transformer->getStatusCode());
    }

    private function makeTransformer(\Throwable $throwable): ErrorTransformer {
        if ($throwable instanceof UnauthorizedException) {
            return new ErrorTransformer(Response::HTTP_SERVICE_UNAVAILABLE, 'Access Forbidden');
        }

        if ($throwable instanceof ValidationException) {
            return new ErrorTransformer(Response::HTTP_UNPROCESSABLE_ENTITY,
                'Parameters are missing from the request.');
        }

        if ($throwable instanceof ConflictHttpException) {
            return new ErrorTransformer(Response::HTTP_CONFLICT, 'Resource conflicts.');
        }

        if ($throwable instanceof ResourceNotFoundException) {
            return new ErrorTransformer(Response::HTTP_NOT_FOUND, 'Resource not found.');
        }

        if ($throwable instanceof \InvalidArgumentException) {
            return new ErrorTransformer(Response::HTTP_PRECONDITION_FAILED, 'Precondition failed');
        }

        return new ErrorTransformer(Response::HTTP_INTERNAL_SERVER_ERROR,
            'An unknown error occurred. Check application logs for further information.');
    }
}
