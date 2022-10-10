<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Project;

use App\Classes\Modules\ControllerLogic\Project\DeleteProjectLogic;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteProjectController extends Controller {
    /**
     * @param DeleteProjectLogic $logic
     * @param Request            $request
     *
     * @return JsonResponse
     */
    public function execute(DeleteProjectLogic $logic, Request $request): JsonResponse {
        return $logic->execute($request);
    }
}
