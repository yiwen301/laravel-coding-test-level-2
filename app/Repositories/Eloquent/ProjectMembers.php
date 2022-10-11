<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\ProjectMember;
use App\Repositories\BaseRepository;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ProjectMembers extends BaseRepository {
    public function model(): string {
        return ProjectMember::class;
    }
}
