<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class ProjectMember extends Model {
    use HasFactory;

    public $incrementing = true;

    protected $table = 'project_member';

    protected $fillable = [
        'id',
        'project_id',
        'user_id'
    ];

    protected $casts = ['project_id' => 'string', 'user_id' => 'string'];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
