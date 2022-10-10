<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Services\Authentication\CreatesSessionForAuthenticationToken;
use App\Classes\Services\Authentication\DeletesExpiredSession;
use App\Classes\Services\Authentication\GeneratesTokenForUserAccess;
use App\Models\User;
use App\Repositories\Eloquent\Sessions;
use App\Repositories\Eloquent\Users;
use App\Traits\GeneratesPasswordHash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class LoginAccountLogic {
    use GeneratesPasswordHash;

    /** @var Sessions */
    private Sessions $sessions;

    /** @var Users */
    private Users $users;

    /** @var GeneratesTokenForUserAccess */
    private GeneratesTokenForUserAccess $generatesTokenForUserAccess;

    /** @var DeletesExpiredSession */
    private DeletesExpiredSession $deletesExpiredSession;

    /** @var CreatesSessionForAuthenticationToken */
    private CreatesSessionForAuthenticationToken $createsSessionForAuthenticationToken;

    /**
     * LoginAccountLogic constructor.
     *
     * @param Sessions                             $sessions
     * @param Users                                $users
     * @param GeneratesTokenForUserAccess          $generatesTokenForUserAccess
     * @param DeletesExpiredSession                $deletesExpiredSession
     * @param CreatesSessionForAuthenticationToken $createsSessionForAuthenticationToken
     */
    public function __construct(
        Sessions $sessions,
        Users $users,
        GeneratesTokenForUserAccess $generatesTokenForUserAccess,
        DeletesExpiredSession $deletesExpiredSession,
        CreatesSessionForAuthenticationToken $createsSessionForAuthenticationToken
    ) {
        $this->sessions                             = $sessions;
        $this->users                                = $users;
        $this->generatesTokenForUserAccess          = $generatesTokenForUserAccess;
        $this->deletesExpiredSession                = $deletesExpiredSession;
        $this->createsSessionForAuthenticationToken = $createsSessionForAuthenticationToken;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\CssSelector\Exception\InternalErrorException
     */
    public function execute(Request $request): JsonResponse {
        try {
            $passwordHash = $this->generatePasswordHash($request->get('password'));

            /** @var User $user */
            $user = $this->users->findWhere([
                'username' => $request->get('username'),
                'password' => $passwordHash
            ]);

            // Determine if user has any active session
            $sessions = $this->sessions->getValidSessionsByUserId($user->id);
            if (count($sessions) !== 0) {
                throw new ConflictHttpException('Duplicate login. There is already an active session.');
            }

            $token = $this->generatesTokenForUserAccess->execute();

            $this->deletesExpiredSession->execute($user);

            $this->createsSessionForAuthenticationToken->execute($user, $token->toString());

            return new JsonResponse(['token' => $token->toString()]);
        } catch (ModelNotFoundException $exception) {
            throw new UnauthorizedException($exception->getMessage());
        }
    }
}
