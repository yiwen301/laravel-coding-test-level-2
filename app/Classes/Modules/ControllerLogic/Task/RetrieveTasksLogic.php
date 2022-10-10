<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\TaskTransformer;
use App\Repositories\Eloquent\Tasks;
use Illuminate\Http\JsonResponse;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveTasksLogic {
    /** @var Tasks */
    private Tasks $tasks;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveTasksLogic constructor.
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
     * @return JsonResponse
     */
    public function execute(): JsonResponse {
        $tasks = $this->tasks->getAll();

        return new JsonResponse($this->handlesApiResponseData->returnMany($tasks, new TaskTransformer()));
    }
}
