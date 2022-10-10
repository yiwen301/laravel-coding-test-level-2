<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Classes\Constants;
use App\Classes\Services\Authentication\ExtractsTokenFromRequestHeader;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
final class ValidatesAuthorizationToken {
    /** @var ExtractsTokenFromRequestHeader */
    private ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader;

    /**
     * ValidatesAuthorizationToken constructor.
     *
     * @param ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader
     */
    public function __construct(ExtractsTokenFromRequestHeader $extractsTokenFromRequestHeader) {
        $this->extractsTokenFromRequestHeader = $extractsTokenFromRequestHeader;
    }

    public function handle(Request $request, Closure $next) {
        if ($request->hasHeader('Authorization') === false || $request->header('Authorization') === null) {
            return new JsonResponse(['data' => 'Authorization Token is missing from the request header.'],
                Response::HTTP_UNAUTHORIZED);
        }

        // extract the bearer token from the authorization header
        $token = $this->extractsTokenFromRequestHeader->execute($request);

        // create the validator
        $validator = new Validator();

        // parse the authorisation token
        $parser             = new Parser(new JoseEncoder());
        $authorisationToken = $parser->parse($token);

        // verify the token if it is signed with the expected signer and key
        $isSigned = $validator->validate($authorisationToken,
            new SignedWith(new Sha256(), InMemory::plainText(config('secure.auth.authorization_token_key'))));

        // verify the token if it is issued by the expected issuer
        $isAuthorised = $validator->validate($authorisationToken, new IssuedBy(Constants::TOKEN_ISSUER));

        // if the token is not signed or authorised, the process cannot continue
        if ($isSigned === false || $isAuthorised === false) {
            return new JsonResponse(['data' => 'Authorization Token is not authorised. It is probably expired, kindly regenerate a new token.'],
                Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
