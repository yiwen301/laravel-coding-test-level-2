<?php

declare(strict_types=1);

namespace App\Classes\Services\Authentication;

use App\Models\User;
use App\Repositories\Eloquent\Sessions;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeletesExpiredSession {
    /** @var Sessions */
    private Sessions $sessions;

    /**
     * DeletesExpiredSession constructor.
     *
     * @param Sessions $sessions
     */
    public function __construct(Sessions $sessions) {
        $this->sessions = $sessions;
    }

    /**
     * @param User $user
     */
    public function execute(User $user): void {
        $this->sessions->getExpiredSessions($user->id)->map(function ($session) {
            $session->delete();
        });
    }
}
