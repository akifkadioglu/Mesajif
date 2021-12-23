<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function contacts(Request $request)
    {
        $friends = Friend::where('user_id', $request->user()->id)->get();
        $users = User::orderByDesc('created_at');
        foreach ($friends as $friend) {
            if ($friend->is_active) {
                $users->orWhere('id', $friend->friend_id);
            }
        }
        return $users->get();
    }

    public function messages(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        return Message::where('from', $request->user()->id)->where('to', $user->id)->orWhere('from', $user->id)->where('to', $request->user()->id)->get();
    }

    public function sendMessage(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        $message = new Message();
        $message->message = $request->message;
        $message->from = $request->user()->id;
        $message->to = $user->id;
        $message->save();
        return response()->json($message);
    }
}
