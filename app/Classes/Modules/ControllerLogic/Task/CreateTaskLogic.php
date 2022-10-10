<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Repositories\Eloquent\Tasks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateTaskLogic {
    /** @var Tasks */
    private Tasks $tasks;

    /**
     * CreateTaskLogic constructor.
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks) {
        $this->tasks = $tasks;
    }

    public function execute(Request $request): JsonResponse {
        $this->tasks->create([
            'id'          => Uuid::uuid4(),
            'title'       => $request->get('title'),
            'description' => $request->get('description'),
            'status'      => $request->get('status'),
            'project_id'  => $request->get('project_id'),
            'user_id'     => $request->get('user_id')
        ]);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
