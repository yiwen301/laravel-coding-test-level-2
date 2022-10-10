<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Task;

use App\Classes\Modules\ControllerLogic\Task\DeleteTaskLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteTaskController extends Controller {
    /**
     * @param DeleteTaskLogic $logic
     * @param Request         $request
     *
     * @return JsonResponse
     */
    public function execute(DeleteTaskLogic $logic, Request $request): JsonResponse {
        return $logic->execute($request->route('task_id'));
    }
}
