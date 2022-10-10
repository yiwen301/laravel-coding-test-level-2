<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Repositories\Eloquent\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteTaskLogic {
    /** @var Tasks */
    private Tasks $tasks;

    /**
     * DeleteTaskLogic constructor.
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks) {
        $this->tasks = $tasks;
    }

    /**
     * @param string $taskId
     *
     * @return JsonResponse
     */
    public function execute(string $taskId): JsonResponse {
        try {
            $this->tasks->delete('id', $taskId);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Task id: %s does not exist.', $taskId));
        }
    }
}
