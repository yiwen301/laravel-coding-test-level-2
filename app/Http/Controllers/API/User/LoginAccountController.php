<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\LoginAccountLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class LoginAccountController extends Controller {
    /**
     * @param LoginAccountLogic $logic
     * @param Request           $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\CssSelector\Exception\InternalErrorException
     */
    public function execute(LoginAccountLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return [
            'username' => 'required|string',
            'password' => 'required|string|min:8'
        ];
    }
}
