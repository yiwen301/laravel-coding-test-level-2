<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Models\UserRole;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class ValidatesProductOwnerRole {
    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /**
     * ValidatesProductOwnerRole constructor.
     *
     * @param IdentifiesUserFromRequest $identifiesUserFromRequest
     */
    public function __construct(IdentifiesUserFromRequest $identifiesUserFromRequest) {
        $this->identifiesUserFromRequest = $identifiesUserFromRequest;
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next) {
        $user = $this->identifiesUserFromRequest->execute($request);

        if ($user->role_id !== UserRole::PRODUCT_OWNER_ROLE) {
            return new JsonResponse(['data' => 'Only Product Owner is allowed to manage projects and tasks.'],
                Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
