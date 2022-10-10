<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\UpdateUserLogic;
use App\Classes\Validators\ControllerValidators\ValidatesUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UpdateUserController extends Controller {
    /**
     * @param UpdateUserLogic $logic
     * @param Request         $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function execute(UpdateUserLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return ValidatesUser::getRulesForPostAndPutMethod();
    }
}
