<?php

declare(strict_types=1);

namespace App\Classes\Validators\ControllerValidators;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ValidatesProject {
    public static function getRulesForPostAndPutMethod(): array {
        return [
            'name'      => 'required|string|min:5',
            'status_id' => 'required|int|min:1|max:5',
            'remarks'   => 'sometimes|string|nullable',
            'members'   => 'sometimes|array|nullable',
            'members.*' => 'required|string|exists:App\Models\User,id',
        ];
    }

    public static function getRulesForPatchMethod(): array {
        return [
            'name'      => 'sometimes|string|min:5',
            'status_id' => 'sometimes|int|min:1|max:5',
            'remarks'   => 'sometimes|string|nullable',
        ];
    }
}
