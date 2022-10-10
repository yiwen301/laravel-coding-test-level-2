<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class Projects extends BaseRepository {
    public function model(): string {
        return Project::class;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection {
        return $this->model->get();
    }

    /**
     * @param string $projectId
     *
     * @return Model
     */
    public function getById(string $projectId): Model {
        return $this->findWhere(['id' => $projectId]);
    }
}
