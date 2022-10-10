<?php

declare(strict_types=1);

namespace App\Classes\Services\Authentication;

use App\Classes\Constants;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class GeneratesTokenForUserAccess {

    /**
     * @return Token
     * @throws InternalErrorException
     */
    public function execute(): Token {
        try {
            $now = new DateTimeImmutable();

            // sign the token with the secure key
            $configuration = Configuration::forSymmetricSigner(new Sha256(),
                InMemory::plainText(config('secure.auth.authorization_token_key')));

            // generate token for the user with token builder
            $tokenBuilder = $configuration->builder();
            $tokenBuilder->issuedBy(Constants::TOKEN_ISSUER);
            $tokenBuilder->issuedAt($now);
            $tokenBuilder->expiresAt($now->modify('+1 hour'));

            $tokenBuilder->withClaim('type', Constants::TOKEN_TYPE);

            return $tokenBuilder->getToken($configuration->signer(), $configuration->signingKey());

        } catch (\BadMethodCallException $exception) {
            throw new InternalErrorException($exception->getMessage());
        }
    }
}
