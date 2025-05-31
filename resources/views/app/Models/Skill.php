<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Article;
class Skill extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function users()
{
    return $this->belongsToMany(User::class, 'user_skills');
}
public function articles()
{
    return $this->belongsToMany(Article::class, 'article_skill');
}


}