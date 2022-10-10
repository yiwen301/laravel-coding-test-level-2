<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Project;

use App\Classes\Modules\ControllerLogic\Project\RetrieveProjectsLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveProjectsController extends Controller {
    /**
     * @param RetrieveProjectsLogic $logic
     *
     * @return JsonResponse
     */
    public function execute(RetrieveProjectsLogic $logic): JsonResponse {
        return $logic->execute();
    }
}
