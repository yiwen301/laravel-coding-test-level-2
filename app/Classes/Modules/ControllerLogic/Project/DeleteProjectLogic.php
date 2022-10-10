<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteProjectLogic {
    /** @var Projects */
    private Projects $projects;

    /**
     * DeleteProjectLogic constructor.
     *
     * @param Projects $projects
     */
    public function __construct(Projects $projects) {
        $this->projects = $projects;
    }

    /**
     * @param string $projectId
     *
     * @return JsonResponse
     */
    public function execute(string $projectId): JsonResponse {
        try {
            // Determine if there is any tasks belongs to the project before delete
            $project = $this->projects->getById($projectId);
            if (count($project->tasks) > 0) {
                throw new PreconditionFailedHttpException('There are tasks belongs to this project. You are not allowed to delete this project.');
            }

            $this->projects->delete('id', $projectId);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Project id: %s does not exist.', $projectId));
        }
    }
}
