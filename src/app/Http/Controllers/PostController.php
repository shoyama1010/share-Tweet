<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 投稿一覧取得
    public function index()
    {
        $posts = Post::with('user', 'likes', 'comments')->latest()->get();
        return response()->json($posts);
    }

    // 投稿追加
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:120',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(), // ログインユーザー
            'content' => $request->content,
        ]);

        return response()->json($post, 201);
    }

    // 投稿削除
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // 必要なら：投稿者だけ削除可能にする
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Deleted']);
    }

    // 投稿詳細取得（任意）
    public function show($id)
    {
        $post = Post::with('user', 'likes', 'comments')->findOrFail($id);
        return response()->json($post);
    }
}
