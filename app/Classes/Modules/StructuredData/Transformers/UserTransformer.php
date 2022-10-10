<?php

declare(strict_types=1);

namespace App\Classes\Modules\StructuredData\Transformers;

use App\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UserTransformer extends TransformerAbstract {
    /**
     * @param User $user
     *
     * @return array
     * @throws \Exception
     */
    public function transform(User $user): array {
        return [
            'id'         => $user->id,
            'username'   => $user->username,
            'password'   => $user->password,
            'role_id'    => $user->role_id,
            'role_name'  => $user->role->name ?? '',
            'created_at' => (new Carbon($user->created_at))->toDateTimeString(),
            'updated_at' => (new Carbon($user->updated_at))->toDateTimeString()
        ];
    }
}
