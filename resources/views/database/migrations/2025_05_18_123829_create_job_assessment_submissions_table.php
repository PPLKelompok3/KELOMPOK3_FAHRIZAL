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
        Schema::create('job_assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained('job_applications')->onDelete('cascade');
            $table->foreignId('job_assessment_id')->constrained('job_assessments')->onDelete('cascade');

            $table->text('answer')->nullable(); // essay or quiz answers
            $table->string('file_path')->nullable(); // for file upload
            $table->integer('score')->nullable(); // recruiter score

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_assessment_submissions');
    }
};