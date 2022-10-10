<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Task;

use App\Classes\Modules\ControllerLogic\Task\RetrieveTaskLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveTaskController extends Controller {
    /**
     * @param RetrieveTaskLogic $logic
     * @param Request           $request
     *
     * @return JsonResponse
     */
    public function execute(RetrieveTaskLogic $logic, Request $request): JsonResponse {
        return $logic->execute($request->route('task_id'));
    }
}
