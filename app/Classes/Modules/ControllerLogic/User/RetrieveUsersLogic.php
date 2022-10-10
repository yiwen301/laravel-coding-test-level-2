<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\UserTransformer;
use App\Repositories\Eloquent\Users;
use Illuminate\Http\JsonResponse;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveUsersLogic {
    /** @var Users */
    private Users $users;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveUsersLogic constructor.
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
     * @return JsonResponse
     */
    public function execute(): JsonResponse {
        $users = $this->users->getAll();

        return new JsonResponse($this->handlesApiResponseData->returnMany($users, new UserTransformer()));
    }
}
