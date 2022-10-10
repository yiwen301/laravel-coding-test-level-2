<?php

declare(strict_types=1);

namespace App\Classes\Services\Authentication;

use App\Models\User;
use App\Repositories\Eloquent\Sessions;
use Illuminate\Database\Eloquent\Model;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreatesSessionForAuthenticationToken {
    /** @var Sessions */
    private Sessions $sessions;

    /**
     * CreatesSessionForAuthenticationToken constructor.
     *
     * @param Sessions $sessions
     */
    public function __construct(Sessions $sessions) {
        $this->sessions = $sessions;
    }

    /**
     * @param User   $user
     * @param string $token
     *
     * @return Model
     */
    public function execute(User $user, string $token): Model {
        return $this->sessions->create([
            'user_id'    => $user->id,
            'token_hash' => md5($token),
        ]);
    }
}
