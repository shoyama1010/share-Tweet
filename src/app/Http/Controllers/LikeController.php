<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // いいね追加
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $like = Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
        ]);

        return response()->json($like, 201);
    }

    // いいね解除
    public function destroy(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $like = Like::where('user_id', Auth::id())
            ->where('post_id', $request->post_id)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like removed']);
        }

        return response()->json(['error' => 'Like not found'], 404);
    }
}
