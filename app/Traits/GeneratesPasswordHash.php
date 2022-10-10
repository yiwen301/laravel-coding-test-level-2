<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
trait GeneratesPasswordHash {
    /**
     * @param string $password
     * @param int    $length
     *
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function generatePasswordHash(string $password, int $length = 50): string {
        return substr(hash_hmac('sha512', $password, config()->get('secure.auth.password_hash_key')), 0, $length);
    }
}
