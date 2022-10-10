<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class Tasks extends BaseRepository {
    public function model(): string {
        return Task::class;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection {
        return $this->model->get();
    }

    /**
     * @param string $taskId
     *
     * @return Model
     */
    public function getById(string $taskId): Model {
        return $this->findWhere(['id' => $taskId]);
    }
}
