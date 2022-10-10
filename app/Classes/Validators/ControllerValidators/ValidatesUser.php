<?php

declare(strict_types=1);

namespace App\Classes\Validators\ControllerValidators;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ValidatesUser {
    public static function getRulesForPostAndPutMethod(): array {
        return [
            'username' => 'required|string|min:4',
            'password' => 'required|string|min:8',
            'role_id'  => 'required|int|min:1|max:3'
        ];
    }

    public static function getRulesForPatchMethod(): array {
        return [
            'username' => 'sometimes|string|min:4',
            'password' => 'sometimes|string|min:8',
            'role_id'  => 'sometimes|int|min:1|max:3'
        ];
    }
}
