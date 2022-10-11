<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Session;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class Sessions extends BaseRepository {
    public function model(): string {
        return Session::class;
    }

    /**
     * @param string $userId
     *
     * @return Collection
     */
    public function getValidSessionsByUserId(string $userId): Collection {
        return $this->model->where('user_id', '=', $userId)->where('created_at', '>', Carbon::now()->subHour())->get();
    }

    /**
     * @param string $token
     *
     * @return Collection
     */
    public function getValidSessionsByToken(string $token): Collection {
        return $this->model->where('token_hash', '=', md5($token))->where('created_at', '>', Carbon::now()->subHour())
                           ->get();
    }

    /**
     * @param string $token
     *
     * @return Model
     */
    public function getSessionByToken(string $token): Model {
        return $this->findBy('token_hash', md5($token));
    }

    /**
     * @param string $userId
     *
     * @return Collection
     */
    public function getExpiredSessions(string $userId): Collection {
        return $this->model->where('user_id', '=', $userId)->where('created_at', '<=', Carbon::now()->subHour())->get();
    }
}
