<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Repositories\Eloquent\Users;
use App\Traits\GeneratesPasswordHash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UpdateUserLogic {
    use GeneratesPasswordHash;

    /** @var Users */
    private Users $users;

    /**
     * UpdateUserLogic constructor.
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
        try {
            $this->users->updateWhere(['id' => $request->route('user_id')], [
                'username' => $request->get('username'),
                'password' => $this->generatePasswordHash($request->get('password')),
                'role_id'  => $request->get('role_id'),
            ]);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('User id: %s does not exist.', $request->route('user_id')));
        }
    }
}
