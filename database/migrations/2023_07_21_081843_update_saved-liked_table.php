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
        Schema::table('users-saved', function (Blueprint $table) {
        $table->integer('project_id');
        $table->string('saved_at')->nullable();
        });
        Schema::table('users-liked', function (Blueprint $table) {
        $table->integer('project_id');
        $table->string('liked_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users-saved', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->dropColumn('saved_at');
        });

        Schema::table('users-liked', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->dropColumn('liked_at');
        });
    }
};
