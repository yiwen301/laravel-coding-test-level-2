<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Traits\GeneratesPasswordHash;
use Faker\Provider\Uuid;
use Illuminate\Database\Seeder;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UserSeeder extends Seeder {
    use GeneratesPasswordHash;

    /**
     * Run the database seeds.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function run(): void {
        \App\Models\User::truncate();

        $this->generateUser();
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function generateUser(): void {
        \App\Models\User::create([
            'id'       => Uuid::uuid(),
            'username' => 'admin',
            'password' => $this->generatePasswordHash('12345Abc'),
            'role_id'  => \App\Models\UserRole::ADMIN_ROLE
        ]);
    }
}
