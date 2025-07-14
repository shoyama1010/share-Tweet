<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    /**
     * いいねを押したユーザーとのリレーション：多対1
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 対象の投稿（Post）とのリレーション：多対1
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
