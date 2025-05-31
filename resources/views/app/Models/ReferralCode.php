<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{

    protected $fillable = [
        'job_id',
        'code',
        'is_used',
    ];

    public function job()
    {
        return $this->belongsTo(JobPost::class);
    }
}