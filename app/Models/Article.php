<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArticleComment;

class Article extends Model
{
    public function skills()
{
    return $this->belongsToMany(Skill::class, 'article_skill');
}

public function author()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function comments()
{
    return $this->hasMany(ArticleComment::class);
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}