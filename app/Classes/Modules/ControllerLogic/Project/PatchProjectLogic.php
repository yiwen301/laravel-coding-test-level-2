<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Repositories\Eloquent\Projects;
use App\Traits\ExtractsDataToBeUpdatedFromRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class PatchProjectLogic {
    use ExtractsDataToBeUpdatedFromRequest;

    /** @var Projects */
    private Projects $projects;

    /**
     * PatchProjectLogic constructor.
     *
     * @param Projects $projects
     */
    public function __construct(Projects $projects) {
        $this->projects = $projects;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $properties = ['name' => 'name', 'status_id' => 'status_id', 'remarks' => 'remarks'];

            // extract the request parameters that match the properties and are not null
            $dataToUpdate = $this->extractData($request, $properties);

            $this->projects->updateWhere(['id' => $request->route('project_id')], $dataToUpdate);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Project id: %s does not exist.',
                $request->route('project_id')));
        }
    }
}
