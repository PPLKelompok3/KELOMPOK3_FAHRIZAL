<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'user_id',
        'institution_name',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'description',
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}