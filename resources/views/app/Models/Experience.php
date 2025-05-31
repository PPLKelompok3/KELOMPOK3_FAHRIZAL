<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'company_or_org', 'location', 'start_date', 'end_date', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}