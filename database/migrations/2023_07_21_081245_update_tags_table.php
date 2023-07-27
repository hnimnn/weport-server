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
     Schema::table('tags', function (Blueprint $table) {
        $table->dropColumn(['created_at','updated_at']);
        $table->string('tag_name');
        $table->integer('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('tag_name');
            $table->dropColumn('project_id');
            $table->timestamps();
        });
    }
};
