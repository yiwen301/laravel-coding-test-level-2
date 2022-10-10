<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\User;

use App\Classes\Modules\ControllerLogic\User\RetrieveUsersLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveUsersController extends Controller {
    /**
     * @param RetrieveUsersLogic $logic
     *
     * @return JsonResponse
     */
    public function execute(RetrieveUsersLogic $logic): JsonResponse {
        return $logic->execute();
    }
}
