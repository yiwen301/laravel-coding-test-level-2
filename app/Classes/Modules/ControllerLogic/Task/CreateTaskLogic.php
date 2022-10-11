<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Classes\Services\Task\DeterminesIfTeamMemberIsAvailable;
use App\Models\Task;
use App\Repositories\Eloquent\Tasks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateTaskLogic {
    /** @var DeterminesIfTeamMemberIsAvailable */
    private DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable;

    /** @var Tasks */
    private Tasks $tasks;

    /**
     * CreateTaskLogic constructor.
     *
     * @param DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable
     * @param Tasks                             $tasks
     */
    public function __construct(
        DeterminesIfTeamMemberIsAvailable $determinesIfTeamMemberIsAvailable,
        Tasks $tasks
    ) {
        $this->tasks                             = $tasks;
        $this->determinesIfTeamMemberIsAvailable = $determinesIfTeamMemberIsAvailable;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        $this->determinesIfTeamMemberIsAvailable->execute($request->get('project_id'), $request->get('user_id'));

        $this->tasks->create([
            'id'          => Uuid::uuid4(),
            'title'       => $request->get('title'),
            'description' => $request->get('description'),
            'status'      => Task::NOT_STARTED_STATUS,
            'project_id'  => $request->get('project_id'),
            'user_id'     => $request->get('user_id')
        ]);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
