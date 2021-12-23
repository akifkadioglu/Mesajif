<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function sendPost(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'textColor' => 'required',
            'backGroundColor' => 'required',
        ]);

        $post = new Post();
        $post->user_id = $request->user()->id;
        $post->message = $request->message;
        $post->textColor = $request->textColor;
        $post->backGroundColor = $request->backGroundColor;
        $post->save();
    }

    public function getPosts(Request $request)
    {
        $friends = Friend::where('user_id', $request->user()->id)->get();
        $posts = Post::with('user')->orderByDesc('created_at')->withCount('likes')->where('user_id', $request->user()->id);
        foreach ($friends as $friend) {
            if ($friend->is_active) {
                $posts->orWhere('user_id', $friend->friend_id);
            }
        }
        return $posts->paginate(1);
    }

    public function getUserPosts(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        return Post::where('user_id', $user->id)->withCount('likes')->orderByDesc('created_at')->paginate(20);
    }

    public function likePost(Request $request)
    {
        $like = count(Like::where('user_id', $request->user()->id)->where('post_id', $request->id)->get());
        if ($like > 0) {
            foreach (Like::where('user_id', $request->user()->id)->where('post_id', $request->id)->get() as $item) {
                $item->delete();
            }
            $success = false;

        } else {
            $data = new Like();
            $data->user_id = $request->user()->id;
            $data->post_id = $request->id;
            $data->save();
            $success = true;

        }
        return response()->json(['success' => $success, 'id' => $request->id]);
    }

    public function getPost(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (count(Post::where('id', $request->id)->where('user_id', $user->id)->with('user')->withCount('likes')->get()) > 0) {
            return Post::where('id', $request->id)->where('user_id', $user->id)->with('user')->withCount('likes')->first();
        } else {
            return false;
        }
    }
}
