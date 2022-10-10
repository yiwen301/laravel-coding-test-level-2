<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Services\Authentication\ExtractsTokenFromRequestHeader;
use App\Repositories\Eloquent\Sessions;
use App\Repositories\Eloquent\Users;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class DeleteUserLogic {
    /** @var ExtractsTokenFromRequestHeader */
    private ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader;

    /** @var Sessions */
    private Sessions $sessions;

    /** @var Users */
    private Users $users;

    /**
     * DeleteUserLogic constructor.
     *
     * @param ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader
     * @param Sessions                       $sessions
     * @param Users                          $users
     */
    public function __construct(
        ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader,
        Sessions $sessions,
        Users $users
    ) {
        $this->extractsTokenFromRequestHeader = $extractsTokenFromRequestHeader;
        $this->sessions                       = $sessions;
        $this->users                          = $users;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $token  = $this->extractsTokenFromRequestHeader->execute($request);
            $userId = $request->route('user_id');

            // Make sure admin does not remove him/herself
            $session = $this->sessions->getValidSessionsByToken($token)->first();
            if ($session->user_id === $userId) {
                throw new \InvalidArgumentException('You are not allowed to remove yourself.');
            }

            // Verify user does not have any project or task before being removed
            $user = $this->users->getById($userId);
            if (count($user->projects) > 0 && count($user->tasks) > 0) {
                throw new PreconditionFailedHttpException('User is having one or more projects or tasks, he or she cannot be removed.');
            }

            $this->users->delete('id', $userId);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('User id: %s does not exist.', $request->route('user_id')));
        }
    }
}
