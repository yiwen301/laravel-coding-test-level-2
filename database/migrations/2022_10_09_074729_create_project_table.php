<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateProjectTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('project', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->uuid('owner_user_id')->comment('User id of product owner');
            $table->unsignedInteger('status_id');
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->index('owner_user_id');
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('project');
    }
}
