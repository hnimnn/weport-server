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
        $table->dateTime('saved_at');
        });
        Schema::table('users-liked', function (Blueprint $table) {
        $table->integer('project_id');
        $table->dateTime('liked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users-saved');
        Schema::dropIfExists('users-liked');
    }
};
