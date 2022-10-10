<?php

declare(strict_types=1);

namespace App\Classes\Modules\StructuredData\Transformers;

use Illuminate\Validation\ValidationException;
use League\Fractal\TransformerAbstract;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ErrorTransformer extends TransformerAbstract {
    /** @var int */
    private int $statusCode;

    /** @var string|null */
    private ?string $message;

    /**
     * ErrorTransformer constructor.
     *
     * @param int         $statusCode
     * @param string|null $message
     */
    public function __construct(int $statusCode, ?string $message = null) {
        $this->statusCode = $statusCode;
        $this->message    = $message;
    }

    public function transform(\Throwable $throwable): array {
        $array = [
            'http_status_code'  => $this->statusCode,
            'message'           => $this->message,
            'exception_message' => $throwable->getMessage(),
        ];

        if ($throwable instanceof ValidationException) {
            $array['validation_errors'] = $throwable->errors();
        }

        return $array;
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }
}
