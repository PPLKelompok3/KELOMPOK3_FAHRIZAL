<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition()
    {
        return [
            'job_id' => JobPost::factory(), // atau id static seperti 1
            'user_id' => User::factory(),
            'status' => 'Pending',
            'cover_letter' => $this->faker->paragraph,
        ];
    }
}