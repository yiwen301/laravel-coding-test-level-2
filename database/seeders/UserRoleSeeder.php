<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UserRoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        \App\Models\UserRole::truncate();

        $this->generateUserRoles();
    }

    private function generateUserRoles(): void {
        foreach ([
                     'ADMIN'         => 'Can use User API.',
                     'PRODUCT OWNER' => 'Can create project and task and assign task.',
                     'TEAM MEMBER'   => 'Can update task status assigned to them.',
                 ] as $name => $description) {
            \App\Models\UserRole::create([
                'name'        => $name,
                'description' => $description
            ]);
        }
    }
}
