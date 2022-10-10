<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\ProjectTransformer;
use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Classes\Services\Project\VerifiesTeamMemberHasAccessToProject;
use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveProjectLogic {
    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var VerifiesTeamMemberHasAccessToProject */
    private VerifiesTeamMemberHasAccessToProject $verifiesTeamMemberHasAccessToProject;

    /** @var Projects */
    private Projects $projects;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveProjectLogic constructor.
     *
     * @param IdentifiesUserFromRequest            $identifiesUserFromRequest
     * @param VerifiesTeamMemberHasAccessToProject $verifiesTeamMemberHasAccessToProject
     * @param Projects                             $projects
     * @param HandlesApiResponseData               $handlesApiResponseData
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        VerifiesTeamMemberHasAccessToProject $verifiesTeamMemberHasAccessToProject,
        Projects $projects,
        HandlesApiResponseData $handlesApiResponseData
    ) {
        $this->identifiesUserFromRequest            = $identifiesUserFromRequest;
        $this->verifiesTeamMemberHasAccessToProject = $verifiesTeamMemberHasAccessToProject;
        $this->projects                             = $projects;
        $this->handlesApiResponseData               = $handlesApiResponseData;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $user = $this->identifiesUserFromRequest->execute($request);

            $this->verifiesTeamMemberHasAccessToProject->execute($request->route('project_id'), $user);

            $project = $this->projects->getById($request->route('project_id'));

            return new JsonResponse($this->handlesApiResponseData->returnOne($project, new ProjectTransformer()));
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Project id: %s does not exist.',
                $request->route('project_id')));
        }
    }
}
