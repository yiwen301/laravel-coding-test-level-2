<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\TaskTransformer;
use App\Repositories\Eloquent\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveTaskLogic {
    /** @var Tasks */
    private Tasks $tasks;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveTaskLogic constructor.
     *
     * @param Tasks                  $tasks
     * @param HandlesApiResponseData $handlesApiResponseData
     */
    public function __construct(
        Tasks $tasks,
        HandlesApiResponseData $handlesApiResponseData
    ) {
        $this->tasks                  = $tasks;
        $this->handlesApiResponseData = $handlesApiResponseData;
    }

    /**
     * @param string $taskId
     *
     * @return JsonResponse
     */
    public function execute(string $taskId): JsonResponse {
        try {
            $task = $this->tasks->getById($taskId);

            return new JsonResponse($this->handlesApiResponseData->returnOne($task, new TaskTransformer()));
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Task id: %s does not exist.', $taskId));
        }
    }
}
