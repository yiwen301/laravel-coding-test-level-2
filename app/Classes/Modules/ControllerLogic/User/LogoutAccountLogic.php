<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\User;

use App\Classes\Services\Authentication\ExtractsTokenFromRequestHeader;
use App\Repositories\Eloquent\Sessions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class LogoutAccountLogic {
    /** @var Sessions */
    private Sessions $sessions;

    /** @var ExtractsTokenFromRequestHeader */
    private ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader;

    /**
     * LogoutAccountLogic constructor.
     *
     * @param Sessions                       $sessions
     * @param ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader
     */
    public function __construct(
        Sessions $sessions,
        ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader
    ) {
        $this->sessions                       = $sessions;
        $this->extractsTokenFromRequestHeader = $extractsTokenFromRequestHeader;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $token = $this->extractsTokenFromRequestHeader->execute($request);

            $this->sessions->findWhere(['token_hash' => md5($token)])->delete();

            return new JsonResponse(null);
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException('Session not found.');
        }
    }
}
