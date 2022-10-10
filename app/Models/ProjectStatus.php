<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class ProjectStatus extends Model {
    public const NEW_STATUS = 1;

    public const ACTIVE_STATUS = 2;

    public const COMPLETED_STATUS = 3;

    public const CANCELLED_STATUS = 4;

    public const ON_HOLD_STATUS = 5;

    public $incrementing = true;

    protected $table = 'project_status';

    protected $fillable = [
        'name',
        'description',
    ];
}
