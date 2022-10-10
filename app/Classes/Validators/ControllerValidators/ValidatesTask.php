<?php

declare(strict_types=1);

namespace App\Classes\Validators\ControllerValidators;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ValidatesTask {
    public static function getRulesForPostAndPutMethod(): array {
        return [
            'title'       => 'required|string|min:5',
            'description' => 'sometimes|string|nullable',
            'status'      => 'required|string|in:NOT_STARTED,IN_PROGRESS,READY_FOR_TEST,COMPLETED',
            'project_id'  => 'required|string|exists:App\Models\Project,id',
            'user_id'     => 'required|string|exists:App\Models\User,id',
        ];
    }

    public static function getRulesForPatchMethod(): array {
        return [
            'title'       => 'sometimes|string|min:5',
            'description' => 'sometimes|string|nullable',
            'status'      => 'sometimes|string|in:NOT_STARTED,IN_PROGRESS,READY_FOR_TEST,COMPLETED',
            'project_id'  => 'sometimes|string|exists:App\Models\Project,id',
            'user_id'     => 'sometimes|string|exists:App\Models\User,id',
        ];
    }
}
