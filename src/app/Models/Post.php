<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * 投稿の所有者（User）とのリレーション：多対1
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 投稿についたいいねとのリレーション：1対多
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * 投稿へのコメントとのリレーション：1対多
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
