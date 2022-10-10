<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class Project extends Model {
    protected $table = 'project';

    protected $fillable = [
        'id',
        'name',
        'owner_user_id',
        'status_id',
        'remarks',
    ];

    protected $casts = ['id' => 'string'];

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_user_id', 'id');
    }

    public function status(): BelongsTo {
        return $this->belongsTo(ProjectStatus::class, 'status_id', 'id');
    }
}
