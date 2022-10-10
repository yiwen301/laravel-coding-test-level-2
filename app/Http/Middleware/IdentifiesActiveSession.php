<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Classes\Services\Authentication\ExtractsTokenFromRequestHeader;
use App\Repositories\Eloquent\Sessions;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class IdentifiesActiveSession {
    /** @var ExtractsTokenFromRequestHeader */
    private ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader;

    /** @var Sessions */
    private Sessions $sessions;

    /**
     * IdentifiesActiveSession constructor.
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
     * @param Closure $next
     *
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next) {
        $token = $this->extractsTokenFromRequestHeader->execute($request);

        $sessions = $this->sessions->getValidSessionsByToken($token);

        // if there is no valid session, the process cannot continue
        if (count($sessions) === 0) {
            return new JsonResponse(['data' => 'Please login first before you can perform any action. Your session might be expired.'],
                Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
