<?php

declare(strict_types=1);

namespace App\Classes\Services\Authentication;

use App\Models\User;
use App\Repositories\Eloquent\Sessions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class IdentifiesUserFromRequest {
    /** @var ExtractsTokenFromRequestHeader */
    private ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader;

    /** @var Sessions */
    private Sessions $sessions;

    /**
     * IdentifiesUserFromRequest constructor.
     *
     * @param ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader
     * @param Sessions                       $sessions
     */
    public function __construct(ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader, Sessions $sessions) {
        $this->extractsTokenFromRequestHeader = $extractsTokenFromRequestHeader;
        $this->sessions                       = $sessions;
    }

    /**
     * @param Request $request
     *
     * @return User
     */
    public function execute(Request $request): User {
        try {
            $token = $this->extractsTokenFromRequestHeader->execute($request);

            return $this->sessions->getSessionByToken($token)->user;
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException('User not found for this request.');
        }
    }
}
