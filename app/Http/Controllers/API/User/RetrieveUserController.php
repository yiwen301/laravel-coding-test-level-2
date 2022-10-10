<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\RetrieveUserLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveUserController extends Controller {
    /**
     * @param RetrieveUserLogic $logic
     * @param Request           $request
     *
     * @return JsonResponse
     */
    public function execute(RetrieveUserLogic $logic, Request $request): JsonResponse {
        return $logic->execute($request->route('user_id'));
    }
}
