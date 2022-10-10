<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateUserTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('user', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('username', 30)->unique();
            $table->string('password', 60);
            $table->unsignedInteger('role_id');
            $table->timestamps();

            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('user');
    }
}
