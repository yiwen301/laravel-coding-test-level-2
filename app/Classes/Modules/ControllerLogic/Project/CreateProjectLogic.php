<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Services\Authentication\ExtractsTokenFromRequestHeader;
use App\Repositories\Eloquent\Projects;
use App\Repositories\Eloquent\Sessions;
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
    /** @var ExtractsTokenFromRequestHeader */
    private ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader;

    /** @var Sessions */
    private Sessions $sessions;

    /** @var Projects */
    private Projects $projects;

    /**
     * CreateProjectLogic constructor.
     *
     * @param ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader
     * @param Sessions                       $sessions
     * @param Projects                       $projects
     */
    public function __construct(
        ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader,
        Sessions $sessions,
        Projects $projects
    ) {
        $this->extractsTokenFromRequestHeader = $extractsTokenFromRequestHeader;
        $this->sessions                       = $sessions;
        $this->projects                       = $projects;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        $token       = $this->extractsTokenFromRequestHeader->execute($request);
        $session     = $this->sessions->getValidSessionsByToken($token)->first();
        $projectName = $request->get('name');

        try {
            // Throw exception if project name is already exists.
            $this->projects->findBy('name', $projectName);

            throw new ConflictHttpException(sprintf('Project name: %s is already exists.', $projectName));
        } catch (ModelNotFoundException $exception) {
            $this->projects->create([
                'id'            => Uuid::uuid4(),
                'name'          => $projectName,
                'owner_user_id' => $session->user_id,
                'status_id'     => $request->get('status_id'),
                'remark'        => $request->get('remark')
            ]);

            return new JsonResponse(null, Response::HTTP_CREATED);
        }
    }
}
