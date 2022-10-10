<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateSessionTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('session', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('user_id', 50);
            $table->string('token_hash', 32);
            $table->timestamps();

            $table->index('token_hash');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('session');
    }
}
