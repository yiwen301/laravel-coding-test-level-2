<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ProjectStatusSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        \App\Models\ProjectStatus::truncate();

        $this->generateProjectStatuses();
    }

    private function generateProjectStatuses(): void {
        foreach ([
                     'NEW'       => 'The project is new and is not yet started.',
                     'ACTIVE'    => 'The project is currently being worked on by the project team.',
                     'COMPLETED' => 'Work on the project has finished, and all deliverables/tasks have been completed.',
                     'CANCELLED' => 'The project has not finished, and work on the project will not continue.',
                     'ON HOLD'   => 'The project has not finished, and work on the project has been temporarily suspended.',
                 ] as $name => $description) {
            \App\Models\ProjectStatus::create([
                'name'        => $name,
                'description' => $description
            ]);
        }
    }
}
