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
          Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'remember_token']);
            $table->string('phone', 50);
            $table->string('avatar')->nullable();
            $table->integer('role')->default(2);
            $table->string('refresh_token')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropColumn('role');
            $table->dropColumn('refresh_token');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
