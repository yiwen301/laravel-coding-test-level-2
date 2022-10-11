<?php

declare(strict_types=1);

namespace App\Classes\Services\Project;

use App\Models\User;
use App\Repositories\Eloquent\Tasks;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class VerifiesTeamMemberHasAccessToProject {
    /** @var Tasks */
    private Tasks $tasks;

    /**
     * VerifiesTeamMemberHasAccessToProject constructor.
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks) {
        $this->tasks = $tasks;
    }

    /**
     * @param string $projectId
     * @param User   $user
     *
     * @return bool
     */
    public function execute(string $projectId, User $user): bool {
        try {
            $this->tasks->findWhere(['project_id' => $projectId, 'user_id' => $user->id]);

            return true;
        } catch (ModelNotFoundException $exception) {
            throw new UnauthorizedException(sprintf('User Id: %s does not have the access to Project Id: %s', $user->id,
                $projectId));
        }
    }
}
