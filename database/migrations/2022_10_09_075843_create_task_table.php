<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateTaskTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('task', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('status', 30);
            $table->uuid('project_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->index('project_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('task');
    }
}
