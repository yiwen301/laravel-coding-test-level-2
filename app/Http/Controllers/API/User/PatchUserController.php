<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\PatchUserLogic;
use App\Classes\Validators\ControllerValidators\ValidatesUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class PatchUserController extends Controller {
    /**
     * @param PatchUserLogic $logic
     * @param Request        $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function execute(PatchUserLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return ValidatesUser::getRulesForPatchMethod();
    }
}
