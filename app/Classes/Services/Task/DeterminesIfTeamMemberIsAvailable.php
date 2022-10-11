<?php

declare(strict_types=1);

namespace App\Classes\Services\Task;

use App\Repositories\Eloquent\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeterminesIfTeamMemberIsAvailable {
    /** @var Tasks */
    private Tasks $tasks;

    /**
     * DeterminesIfTeamMemberIsAvailable constructor.
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks) {
        $this->tasks = $tasks;
    }

    /**
     * @param string      $projectId
     * @param string      $userId
     * @param string|null $taskId
     *
     * @return bool
     */
    public function execute(string $projectId, string $userId, ?string $taskId = null): bool {
        try {
            $task = $this->tasks->findWhere(['project_id' => $projectId, 'user_id' => $userId]);

            if (isset($taskId) && $task->id !== $taskId) {
                throw new \InvalidArgumentException(sprintf('Team member already has Task ID: %s in Project ID: %s',
                    $task->id, $projectId));
            }

            return true;
        } catch (ModelNotFoundException $exception) {
            return true;
        }
    }
}
