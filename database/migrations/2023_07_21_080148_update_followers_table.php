<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          Schema::table('followers', function (Blueprint $table) {
            $table->dropColumn(['id', 'created_at','updated_at']);
            $table->integer('user_id');
            $table->integer('follower_id');
            $table->dateTime('followed_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
