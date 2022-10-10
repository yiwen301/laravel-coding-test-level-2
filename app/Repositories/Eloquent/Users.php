<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class Users extends BaseRepository {
    public function model(): string {
        return User::class;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection {
        return $this->model->get();
    }

    /**
     * @param string $userId
     *
     * @return Model
     */
    public function getById(string $userId): Model {
        return $this->findWhere(['id' => $userId]);
    }
}
