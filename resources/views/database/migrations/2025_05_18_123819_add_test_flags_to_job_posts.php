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
        Schema::table('job_posts', function (Blueprint $table) {
            $table->boolean('has_quiz')->default(false)->after('status');
            $table->boolean('has_essay')->default(false)->after('has_quiz');
            $table->boolean('requires_file')->default(false)->after('has_essay');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            //
        });
    }
};