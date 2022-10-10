<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Classes\Services\Task\DeterminesIfTeamMemberIsAvailable;
use App\Models\UserRole;
use App\Repositories\Eloquent\Tasks;
use App\Traits\ExtractsDataToBeUpdatedFromRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class PatchTaskLogic {
    use ExtractsDataToBeUpdatedFromRequest;

    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var DeterminesIfTeamMemberIsAvailable */
    private DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable;

    /** @var Tasks */
    private Tasks $tasks;

    /**
     * PatchTaskLogic constructor.
     *
     * @param IdentifiesUserFromRequest         $identifiesUserFromRequest
     * @param DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable
     * @param Tasks                             $tasks
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable,
        Tasks $tasks
    ) {
        $this->identifiesUserFromRequest         = $identifiesUserFromRequest;
        $this->determinesIfTeamMemberIsAvailable = $determinesIfTeamMemberIsAvailable;
        $this->tasks                             = $tasks;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $task = $this->tasks->getById($request->route('task_id'));

            if ($request->has('project_id') || $request->has('user_id')) {
                $projectId = $request->has('project_id') ? $request->get('project_id') : $task->project_id;
                $userId    = $request->has('user_id') ? $request->get('user_id') : $task->user_id;

                $this->determinesIfTeamMemberIsAvailable->execute($projectId, $userId, $task->id);
            }

            $properties = [
                'title'       => 'title',
                'description' => 'description',
                'status'      => 'status',
                'project_id'  => 'project_id',
                'user_id'     => 'user_id'
            ];

            // extract the request parameters that match the properties and are not null
            $dataToUpdate = $this->extractData($request, $properties);

            $requester = $this->identifiesUserFromRequest->execute($request);

            // Only allow team member to update the status of his/her task
            if ($requester->role_id === UserRole::TEAM_MEMBER_ROLE) {
                if ((count($dataToUpdate) === 1 && array_key_exists('status',
                            $dataToUpdate) && $task->user_id === $requester->id) === false) {
                    throw new UnauthorizedException('Team member can only update his or her task status.');
                }
            }

            $this->tasks->updateWhere(['id' => $request->route('task_id')], $dataToUpdate);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Task id: %s does not exist.', $request->route('task_id')));
        }
    }
}
