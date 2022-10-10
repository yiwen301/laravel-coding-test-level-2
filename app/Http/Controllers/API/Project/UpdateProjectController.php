<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Project;

use App\Classes\Modules\ControllerLogic\Project\UpdateProjectLogic;
use App\Classes\Validators\ControllerValidators\ValidatesProject;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UpdateProjectController extends Controller {
    /**
     * @param UpdateProjectLogic $logic
     * @param Request            $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(UpdateProjectLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return ValidatesProject::getRulesForPostAndPutMethod();
    }
}
