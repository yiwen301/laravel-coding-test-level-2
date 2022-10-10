<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class Session extends Model {
    public $incrementing = true;

    protected $table = 'session';

    protected $fillable = [
        'user_id',
        'token_hash',
    ];
}
