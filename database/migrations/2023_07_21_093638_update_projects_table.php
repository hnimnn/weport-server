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
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('thumbnail', 750);
            $table->string('tags')->nullable();
            $table->string('description', 750)->nullable();
            $table->integer('view')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('thumbnail');
            $table->dropColumn('tags');
            $table->dropColumn('description');
            $table->dropColumn('view');
        });

    }
};
