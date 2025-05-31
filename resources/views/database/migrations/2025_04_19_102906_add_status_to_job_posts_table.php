<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->enum('status', ['Active', 'Not Active'])->default('Active')->after('experience_level');
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};