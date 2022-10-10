<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Repositories\Eloquent\ProjectMembers;
use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateProjectLogic {
    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var Projects */
    private Projects $projects;

    /** @var ProjectMembers */
    private ProjectMembers $projectMembers;

    /**
     * CreateProjectLogic constructor.
     *
     * @param IdentifiesUserFromRequest $identifiesUserFromRequest
     * @param Projects                  $projects
     * @param ProjectMembers            $projectMembers
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        Projects $projects,
        ProjectMembers $projectMembers
    ) {
        $this->identifiesUserFromRequest = $identifiesUserFromRequest;
        $this->projects                  = $projects;
        $this->projectMembers            = $projectMembers;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        $user        = $this->identifiesUserFromRequest->execute($request);
        $projectName = $request->get('name');

        try {
            // Throw exception if project name is already exists.
            $this->projects->findBy('name', $projectName);

            throw new ConflictHttpException(sprintf('Project name: %s is already exists.', $projectName));
        } catch (ModelNotFoundException $exception) {
            $projectId = Uuid::uuid4();
            $this->projects->create([
                'id'            => $projectId,
                'name'          => $projectName,
                'owner_user_id' => $user->id,
                'status_id'     => $request->get('status_id'),
                'remark'        => $request->get('remark')
            ]);

            if ($request->has('members') && count($request->get('members')) > 0) {
                foreach ($request->get('members') as $member) {
                    $this->projectMembers->create(['project_id' => $projectId, 'user_id' => $member]);
                }
            }

            return new JsonResponse(null, Response::HTTP_CREATED);
        }
    }
}
