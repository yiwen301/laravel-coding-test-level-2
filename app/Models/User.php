<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class User extends Model {
    protected $table = 'user';

    protected $fillable = [
        'id',
        'username',
        'password',
        'role_id',
    ];

    protected $hidden = ['password'];

    protected $casts = ['id' => 'string'];

    public function role(): BelongsTo {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

    public function projects(): HasMany {
        return $this->hasMany(Project::class, 'owner_user_id', 'id');
    }
}
