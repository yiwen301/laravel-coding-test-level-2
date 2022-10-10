<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\UserTransformer;
use App\Repositories\Eloquent\Users;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveUserLogic {
    /** @var Users */
    private Users $users;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveUserLogic constructor.
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
     * @param string $userId
     *
     * @return JsonResponse
     */
    public function execute(string $userId): JsonResponse {
        try {
            $user = $this->users->getById($userId);

            return new JsonResponse($this->handlesApiResponseData->returnOne($user, new UserTransformer()));
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('User id: %s does not exist', $userId));
        }
    }
}
