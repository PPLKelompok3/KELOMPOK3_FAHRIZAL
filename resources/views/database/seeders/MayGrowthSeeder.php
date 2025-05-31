<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\JobApplication;

class MayGrowthSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 4; // your known test user
        $jobIds = range(11, 23); // adjust this to your real job_id range

        foreach ($jobIds as $jobId) {
            $applicationCount = rand(10, 20); // random number of applications per job

            for ($i = 0; $i < $applicationCount; $i++) {
                $timestamp = Carbon::create(2025, 5, 1, rand(0, 6), rand(0, 59), 0); // 1 May before 06:30

                JobApplication::create([
                    'job_id' => $jobId,
                    'user_id' => $userId,
                    'status' => 'Pending',
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }

        echo "🌱 MayGrowthSeeder complete: Applications seeded for 1 May 2025 before 06:30 AM.\n";
    }
}