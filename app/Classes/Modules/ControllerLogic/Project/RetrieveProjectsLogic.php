<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\ProjectTransformer;
use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Models\UserRole;
use App\Repositories\Eloquent\Projects;
use App\Repositories\Eloquent\Tasks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveProjectsLogic {
    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var Projects */
    private Projects $projects;

    /** @var Tasks */
    private Tasks $tasks;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveProjectsLogic constructor.
     *
     * @param IdentifiesUserFromRequest $identifiesUserFromRequest
     * @param Projects                  $projects
     * @param Tasks                     $tasks
     * @param HandlesApiResponseData    $handlesApiResponseData
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        Projects $projects,
        Tasks $tasks,
        HandlesApiResponseData $handlesApiResponseData
    ) {
        $this->identifiesUserFromRequest = $identifiesUserFromRequest;
        $this->projects                  = $projects;
        $this->tasks                     = $tasks;
        $this->handlesApiResponseData    = $handlesApiResponseData;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        $user = $this->identifiesUserFromRequest->execute($request);

        $projects = $this->projects->getAll();

        // Team member only have visibility to their projects.
        if ($user->role_id === UserRole::TEAM_MEMBER_ROLE) {
            $projectIds = $this->tasks->findAllWhere(['user_id' => $user->id])->pluck('project_id')->unique()
                                      ->toArray();

            $projects = $projects->filter(function ($project) use ($projectIds) {
                return in_array($project->id, $projectIds);
            });
        }

        return new JsonResponse($this->handlesApiResponseData->returnMany($projects, new ProjectTransformer()));
    }
}
