<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Task;

use App\Classes\Modules\ControllerLogic\Task\CreateTaskLogic;
use App\Classes\Validators\ControllerValidators\ValidatesTask;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateTaskController extends Controller {
    /**
     * @param CreateTaskLogic $logic
     * @param Request         $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(CreateTaskLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return ValidatesTask::getRulesForPostAndPutMethod();
    }
}
