<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class adminPostsController extends Controller
{
    public function adminGetUserPosts(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        return Post::where('user_id', $user->id)->withCount('likes')->orderByDesc('created_at')->paginate(20);
    }

    public function adminDeletePost(Request $request)
    {
        Post::where('id', $request->id)->first()->delete();
        $user = User::where('username', $request->username)->first();
        return Post::where('user_id', $user->id)->withCount('likes')->orderByDesc('created_at')->paginate(20);
    }
}
