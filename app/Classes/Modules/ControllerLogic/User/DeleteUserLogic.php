<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
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
    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var Users */
    private Users $users;

    /**
     * DeleteUserLogic constructor.
     *
     * @param IdentifiesUserFromRequest $identifiesUserFromRequest
     * @param Users                     $users
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        Users $users
    ) {
        $this->identifiesUserFromRequest = $identifiesUserFromRequest;
        $this->users                     = $users;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $requestUser = $this->identifiesUserFromRequest->execute($request);
            $userId      = $request->route('user_id');

            // Make sure admin does not remove him/herself
            if ($requestUser->id === $userId) {
                throw new \InvalidArgumentException('You are not allowed to remove yourself.');
            }

            // Verify target user does not have any project or task before being removed
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
