<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\DeleteUserLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteUserController extends Controller {
    /**
     * @param DeleteUserLogic $logic
     * @param Request         $request
     *
     * @return JsonResponse
     */
    public function execute(DeleteUserLogic $logic, Request $request): JsonResponse {
        return $logic->execute($request);
    }
}
