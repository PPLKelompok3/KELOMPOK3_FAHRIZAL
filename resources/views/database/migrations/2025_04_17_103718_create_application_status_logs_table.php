<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('application_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['Pending', 'Processed', 'Interview', 'Accepted', 'Rejected']);
            $table->foreignId('changed_by_user_id')->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status_logs');
    }
};