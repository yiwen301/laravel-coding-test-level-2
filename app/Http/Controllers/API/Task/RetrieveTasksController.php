<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Task;

use App\Classes\Modules\ControllerLogic\Task\RetrieveTasksLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveTasksController extends Controller {
    /**
     * @param RetrieveTasksLogic $logic
     *
     * @return JsonResponse
     */
    public function execute(RetrieveTasksLogic $logic): JsonResponse {
        return $logic->execute();
    }
}
