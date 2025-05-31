<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExperienceTypeEnum extends Migration
{
    public function up(): void
    {
        // 
        DB::statement("ALTER TABLE experiences MODIFY COLUMN type ENUM('internship', 'full_time', 'part_time', 'freelance', 'organization') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE experiences MODIFY COLUMN type ENUM('internship', 'full_time', 'freelance', 'organization') NOT NULL");
    }
}