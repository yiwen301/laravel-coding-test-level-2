<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
trait ExtractsDataToBeUpdatedFromRequest {
    /**
     * @param Request $request
     * @param array   $properties
     *
     * @return array
     */
    public function extractData(Request $request, array $properties): array {
        return array_filter(array_map(function ($value) use ($request) {
            return $request->has($value) ? $request->get($value) : null;
        }, $properties));
    }
}
