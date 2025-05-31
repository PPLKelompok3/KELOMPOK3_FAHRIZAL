<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    public $timestamps = false;

    protected $fillable = ['job_post_id', 'skill_id'];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}