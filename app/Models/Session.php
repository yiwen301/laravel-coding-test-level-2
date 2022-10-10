<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class Session extends Model {
    use HasFactory;

    public $incrementing = true;

    protected $table = 'session';

    protected $fillable = [
        'user_id',
        'token_hash',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
