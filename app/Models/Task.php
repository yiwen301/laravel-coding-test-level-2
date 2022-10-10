<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class Task extends Model {
    public const NOT_STARTED_STATUS = 'NOT_STARTED';

    public const IN_PROGRESS_STATUS = 'IN_PROGRESS';

    public const READY_FOR_TEST_STATUS = 'READY_FOR_TEST';

    public const COMPLETED_STATUS = 'COMPLETED';

    protected $table = 'task';

    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'project_id',
        'user_id'
    ];

    protected $casts = ['id' => 'string'];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
