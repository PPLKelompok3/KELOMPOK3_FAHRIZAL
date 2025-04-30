<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'employment_type',
        'location',
        'salary_min',
        'salary_max',
        'experience_level',
        'skills',
        'category',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}