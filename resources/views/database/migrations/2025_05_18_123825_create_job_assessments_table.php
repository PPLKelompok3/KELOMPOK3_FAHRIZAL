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
        Schema::create('job_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_posts')->onDelete('cascade');
            $table->enum('type', ['quiz', 'essay', 'file']);
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_assessments');
    }
};