<?php

declare(strict_types=1);

namespace App\Classes\Services\Project;

use App\Models\User;
use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class VerifiesProjectBelongsToProductOwner {
    /** @var Projects */
    private Projects $projects;

    /**
     * VerifiesProjectBelongsToProductOwner constructor.
     *
     * @param Projects $projects
     */
    public function __construct(Projects $projects) {
        $this->projects = $projects;
    }

    /**
     * @param string $projectId
     * @param User   $user
     *
     * @return bool
     */
    public function execute(string $projectId, User $user): bool {
        try {
            $this->projects->findWhere(['id' => $projectId, 'owner_user_id' => $user->id]);

            return true;
        } catch (ModelNotFoundException $exception) {
            throw new UnauthorizedException(sprintf('Project Id: %s does not belong to User Id: %s', $projectId,
                $user->id));
        }
    }
}
