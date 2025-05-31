<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
{
    DB::statement("ALTER TABLE users MODIFY role ENUM('applicant', 'recruiter', 'admin') NOT NULL");

    Schema::table('users', function (Blueprint $table) {
        $table->enum('is_verified', ['pending', 'approved', 'rejected'])->default('pending');
    });
}

public function down(): void
{
    // Rollback ENUM to original values
    DB::statement("ALTER TABLE users MODIFY role ENUM('applicant', 'recruiter') NOT NULL");

    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_verified');
    });
}

};