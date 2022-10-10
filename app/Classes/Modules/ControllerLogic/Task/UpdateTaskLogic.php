<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Repositories\Eloquent\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UpdateTaskLogic {
    /** @var Tasks */
    private Tasks $tasks;

    /**
     * UpdateTaskLogic constructor.
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks) {
        $this->tasks = $tasks;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
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
