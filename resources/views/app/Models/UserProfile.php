<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserProfile extends Model
{
    protected $fillable = ['user_id', 'bio', 'cv_url', 'location', 'birth_date', 'phone', 'profile_picture',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}