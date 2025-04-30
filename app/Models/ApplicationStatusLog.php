<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatusLog extends Model
{
    protected $fillable = ['job_application_id', 'status', 'changed_by_user_id', 'note'];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}