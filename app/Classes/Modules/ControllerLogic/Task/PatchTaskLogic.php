<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Task;

use App\Repositories\Eloquent\Tasks;
use App\Traits\ExtractsDataToBeUpdatedFromRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class PatchTaskLogic {
    use ExtractsDataToBeUpdatedFromRequest;

    /** @var Tasks */
    private Tasks $tasks;

    /**
     * PatchTaskLogic constructor.
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks) {
        $this->tasks = $tasks;
    }

    public function execute(Request $request): JsonResponse {
        try {
            $properties = [
                'title'       => 'title',
                'description' => 'description',
                'status'      => 'status',
                'project_id'  => 'project_id',
                'user_id'     => 'user_id'
            ];

            // extract the request parameters that match the properties and are not null
            $dataToUpdate = $this->extractData($request, $properties);

            $this->tasks->updateWhere(['id' => $request->route('task_id')], $dataToUpdate);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Task id: %s does not exist.', $request->route('task_id')));
        }
    }
}
