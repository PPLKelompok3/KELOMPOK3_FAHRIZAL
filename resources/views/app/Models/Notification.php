<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'type', 'content', 'is_read','company_logo_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}