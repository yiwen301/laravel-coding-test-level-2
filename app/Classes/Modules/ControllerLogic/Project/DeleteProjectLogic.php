<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Classes\Services\Project\VerifiesProjectBelongsToProductOwner;
use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteProjectLogic {
    /** @var Projects */
    private Projects $projects;

    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var VerifiesProjectBelongsToProductOwner */
    private VerifiesProjectBelongsToProductOwner $verifiesProjectBelongsToProductOwner;

    /**
     * DeleteProjectLogic constructor.
     *
     * @param IdentifiesUserFromRequest            $identifiesUserFromRequest
     * @param VerifiesProjectBelongsToProductOwner $verifiesProjectBelongsToProductOwner
     * @param Projects                             $projects
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        VerifiesProjectBelongsToProductOwner $verifiesProjectBelongsToProductOwner,
        Projects $projects
    ) {
        $this->identifiesUserFromRequest            = $identifiesUserFromRequest;
        $this->verifiesProjectBelongsToProductOwner = $verifiesProjectBelongsToProductOwner;
        $this->projects                             = $projects;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $projectId = $request->route('project_id');

            $user = $this->identifiesUserFromRequest->execute($request);

            $this->verifiesProjectBelongsToProductOwner->execute($projectId, $user);

            // Determine if there is any tasks belongs to the project before delete
            $project = $this->projects->getById($projectId);
            if (count($project->tasks) > 0) {
                throw new PreconditionFailedHttpException('There are tasks belongs to this project. You are not allowed to delete this project.');
            }

            $this->projects->delete('id', $projectId);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Project id: %s does not exist.',
                $request->route('project_id')));
        }
    }
}
