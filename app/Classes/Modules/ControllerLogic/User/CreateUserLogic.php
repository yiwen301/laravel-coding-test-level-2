<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Repositories\Eloquent\Users;
use App\Traits\GeneratesPasswordHash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateUserLogic {
    use GeneratesPasswordHash;

    /** @var Users */
    private Users $users;

    /**
     * CreateUserLogic constructor.
     *
     * @param Users $users
     */
    public function __construct(Users $users) {
        $this->users = $users;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function execute(Request $request): JsonResponse {
        $passwordHash = $this->generatePasswordHash($request->get('password'));
        $username     = $request->get('username');

        try {
            // Throw exception if username is already exists.
            $this->users->findBy('username', $username);

            throw new ConflictHttpException(sprintf('Username: %s is already exists.', $username));
        } catch (ModelNotFoundException $exception) {
            $this->users->create([
                'id'       => Uuid::uuid4(),
                'username' => $username,
                'password' => $passwordHash,
                'role_id'  => $request->get('role_id'),
            ]);

            return new JsonResponse(null, Response::HTTP_CREATED);
        }
    }
}
