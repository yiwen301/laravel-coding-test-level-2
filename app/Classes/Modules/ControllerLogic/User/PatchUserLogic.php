<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Repositories\Eloquent\Users;
use App\Traits\ExtractsDataToBeUpdatedFromRequest;
use App\Traits\GeneratesPasswordHash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class PatchUserLogic {
    use GeneratesPasswordHash, ExtractsDataToBeUpdatedFromRequest;

    /** @var Users */
    private Users $users;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * PatchUserLogic constructor.
     *
     * @param Users                  $users
     * @param HandlesApiResponseData $handlesApiResponseData
     */
    public function __construct(
        Users $users,
        HandlesApiResponseData $handlesApiResponseData
    ) {
        $this->users                  = $users;
        $this->handlesApiResponseData = $handlesApiResponseData;
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
            $properties = ['username' => 'username', 'role_id' => 'role_id'];

            // extract the request parameters that match the properties and are not null
            $dataToUpdate = $this->extractData($request, $properties);

            if ($request->has('password')) {
                $dataToUpdate['password'] = $this->generatePasswordHash($request->get('password'));
            }

            $this->users->updateWhere(['id' => $request->route('user_id')], $dataToUpdate);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('User id: %s does not exist.', $request->route('user_id')));
        }
    }
}
