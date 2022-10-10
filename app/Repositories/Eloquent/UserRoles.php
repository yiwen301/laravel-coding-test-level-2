<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\UserRole;
use App\Repositories\BaseRepository;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UserRoles extends BaseRepository {
    public function model(): string {
        return UserRole::class;
    }
}
