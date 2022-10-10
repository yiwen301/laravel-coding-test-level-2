<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Project;

use App\Classes\Modules\ControllerLogic\Project\RetrieveProjectLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveProjectController extends Controller {
    /**
     * @param RetrieveProjectLogic $logic
     * @param Request              $request
     *
     * @return JsonResponse
     */
    public function execute(RetrieveProjectLogic $logic, Request $request): JsonResponse {
        return $logic->execute($request->route('project_id'));
    }
}
