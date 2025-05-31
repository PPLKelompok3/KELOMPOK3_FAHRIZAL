<?php

namespace Database\Factories;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    protected $model = JobPost::class;

    public function definition()
    {
        return [
            'company_id' => 5, // you may override this when calling ->create()
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'employment_type' => 'Full-time',
            'location' => $this->faker->city,
            'salary_min' => 5000000,
            'salary_max' => 10000000,
            'experience_level' => 'Junior',
            'status' => 'Active',
            'skills' => json_encode(['Aws,Sql']),
            'category' => 'IT',
        ];
    }
}