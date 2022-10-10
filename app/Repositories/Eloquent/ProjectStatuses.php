<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\ProjectStatus;
use App\Repositories\BaseRepository;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ProjectStatuses extends BaseRepository {
    public function model(): string {
        return ProjectStatus::class;
    }
}
