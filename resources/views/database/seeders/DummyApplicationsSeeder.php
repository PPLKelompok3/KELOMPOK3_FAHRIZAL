<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use Carbon\Carbon;

class DummyApplicationsSeeder extends Seeder
{
    public function run()
    {
        $userId = 4;
        $jobIds = range(11, 23); // job_id 11 to 23

        foreach ($jobIds as $jobId) {
            foreach (['January', 'February', 'March', 'April'] as $monthName) {
                $applicationsThisMonth = rand(15, 30); // 🔥 Random between 15 to 30 apps for this month

                for ($i = 0; $i < $applicationsThisMonth; $i++) {
                    $month = [
                        'January' => 1,
                        'February' => 2,
                        'March' => 3,
                        'April' => 4,
                    ][$monthName];

                    $day = rand(1, 28); // Safe range for day
                    $hour = rand(0, 23);
                    $minute = rand(0, 59);

                    $createdAt = Carbon::create(2025, $month, $day, $hour, $minute);

                    // Protect April 29 limit
                    if ($createdAt->greaterThan(Carbon::create(2025, 4, 28, 23, 59))) {
                        $createdAt = Carbon::create(2025, 4, rand(1, 28), rand(0, 23), rand(0, 59));
                    }

                    // Status logic based on month
                    $statusPool = match ($month) {
                        1 => ['Pending', 'Pending', 'Pending', 'Rejected'],  // Jan mostly pending
                        2 => ['Pending', 'Pending', 'Rejected', 'Rejected'],  // Feb mixed
                        3 => ['Processed', 'Accepted', 'Processed', 'Rejected'], // Mar more processed/accepted
                        4 => ['Accepted', 'Accepted', 'Pending'], // Apr more accepted
                    };

                    $status = collect($statusPool)->random();

                    JobApplication::create([
                        'job_id' => $jobId,
                        'user_id' => $userId,
                        'status' => $status,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }
    }
}