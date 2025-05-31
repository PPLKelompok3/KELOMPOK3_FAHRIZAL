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
        Schema::create('referral_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('code')->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('job_posts')->onDelete('cascade');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->boolean('applied_via_referral')->default(false)->after('cover_letter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_codes');

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('applied_via_referral');
        });
    }
};