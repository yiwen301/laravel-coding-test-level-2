<?php

declare(strict_types=1);

namespace App\Classes\Modules\StructuredData\Transformers;

use App\Models\Task;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class TaskTransformer extends TransformerAbstract {
    /**
     * @param Task $task
     *
     * @return array
     * @throws \Exception
     */
    public function transform(Task $task): array {
        return [
            'id'           => $task->id,
            'title'        => $task->title,
            'description'  => $task->description,
            'status'       => $task->status,
            'project_id'   => $task->project_id,
            'project_name' => $task->project->name,
            'user_id'      => $task->user_id,
            'username'     => $task->user->username,
            'created_at'   => (new Carbon($task->created_at))->toDateTimeString(),
            'updated_at'   => (new Carbon($task->updated_at))->toDateTimeString()
        ];
    }
}
