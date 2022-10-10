<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Task;

use App\Classes\Modules\ControllerLogic\Task\PatchTaskLogic;
use App\Classes\Validators\ControllerValidators\ValidatesTask;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class PatchTaskController extends Controller {
    /**
     * @param PatchTaskLogic $logic
     * @param Request        $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function execute(PatchTaskLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return ValidatesTask::getRulesForPatchMethod();
    }
}
