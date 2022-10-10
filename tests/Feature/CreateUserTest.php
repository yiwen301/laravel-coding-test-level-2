<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Classes\Services\Authentication\GeneratesTokenForUserAccess;
use App\Http\Middleware\IdentifiesActiveSession;
use App\Http\Middleware\ValidatesAdminRole;
use App\Traits\GeneratesPasswordHash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateUserTest extends TestCase {
    use GeneratesPasswordHash;

    /**
     * Test creating user
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testItCreatesUser(): void {
        $this->withoutMiddleware([ValidatesAdminRole::class, IdentifiesActiveSession::class]);

        $token = $this->app->make(GeneratesTokenForUserAccess::class)->execute();

        $payload = [
            'username' => Str::random(8),
            'password' => Str::random(8),
            'role_id'  => rand(1, 3)
        ];

        $response = $this->post('/api/v1/users', $payload, ['Authorization' => 'Bearer ' . $token->toString()]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
