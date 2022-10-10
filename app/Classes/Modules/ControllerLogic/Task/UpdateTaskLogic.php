<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Classes\Services\Task\DeterminesIfTeamMemberIsAvailable;
use App\Repositories\Eloquent\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UpdateTaskLogic {
    /** @var DeterminesIfTeamMemberIsAvailable */
    private DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable;

    /** @var Tasks */
    private Tasks $tasks;

    /**
     * UpdateTaskLogic constructor.
     *
     * @param DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable
     * @param Tasks                             $tasks
     */
    public function __construct(
        DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable,
        Tasks $tasks
    ) {
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

            $this->determinesIfTeamMemberIsAvailable->execute($request->get('project_id'), $request->get('user_id'),
                $task->id);

            $this->tasks->updateWhere(['id' => $request->route('task_id')], [
                'title'       => $request->get('title'),
                'description' => $request->get('description'),
                'status'      => $request->get('status'),
                'project_id'  => $request->get('project_id'),
                'user_id'     => $request->get('user_id')
            ]);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Task id: %s does not exist.', $request->route('task_id')));
        }
    }
}
