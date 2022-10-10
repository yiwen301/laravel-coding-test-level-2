<?php

declare(strict_types=1);

namespace App\Classes\Services\Authentication;

use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ExtractsTokenFromRequestHeader {
    private const TOKEN_PREFIX = 'Bearer';

    /**
     * @param Request $request
     *
     * @return string
     */
    public function execute(Request $request): string {
        $authorisationString = $request->header('Authorization');

        // extract the bearer token from the authorization header
        return trim(str_replace(self::TOKEN_PREFIX, '', $authorisationString));
    }
}
