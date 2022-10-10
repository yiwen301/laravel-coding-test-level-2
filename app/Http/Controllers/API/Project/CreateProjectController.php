<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Project;

use App\Classes\Modules\ControllerLogic\Project\CreateProjectLogic;
use App\Classes\Validators\ControllerValidators\ValidatesProject;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateProjectController extends Controller {
    /**
     * @param CreateProjectLogic $logic
     * @param Request            $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(CreateProjectLogic $logic, Request $request): JsonResponse {
        $this->validate($request, $this->validationRules());

        return $logic->execute($request);
    }

    protected function validationRules(): array {
        return ValidatesProject::getRulesForPostAndPutMethod();
    }
}
