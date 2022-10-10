<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class UserRole extends Model {
    public const ADMIN_ROLE = 1;

    public const PRODUCT_OWNER_ROLE = 2;

    public const TEAM_MEMBER_ROLE = 3;

    public $incrementing = true;

    protected $table = 'user_role';

    protected $fillable = [
        'name',
        'description',
    ];
}
