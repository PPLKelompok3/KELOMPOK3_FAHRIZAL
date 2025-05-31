<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $fillable = ['job_id', 'user_id', 'status', 'cover_letter','interview_date','score','feedback'];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }
    public function job()
{
    return $this->belongsTo(JobPost::class, 'job_id');
}
}