<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function addFriend(Request $request)
    {
        $request->validate([
            'username' => 'required'
        ]);
        $friend = User::where('username', $request->username)->first();
        if (count(Friend::where('friend_id', $friend->id)->where('user_id', $request->user()->id)->get()) != 0 || count(Friend::where('user_id', $friend->id)->where('friend_id', $request->user()->id)->get()) != 0) {
            foreach (Friend::where('friend_id', $request->user()->id)->where('user_id', $friend->id)->get() as $item) {
                $item->delete();
            }
            foreach (Friend::where('user_id', $request->user()->id)->where('friend_id', $friend->id)->get() as $item) {
                $item->delete();
            }
        } else {
            $data = new Friend();
            $data->user_id = $request->user()->id;
            $data->friend_id = $friend->id;
            $data->save();
        }
    }

    public function isFriend(Request $request)
    {
        if ($request->username == $request->user()->username) {
            return null;
        }
        $friend = User::where('username', $request->username)->first();
        $isFriend = Friend::where('friend_id', $friend->id)->where('user_id', $request->user()->id)->first();
        if (empty($isFriend)) {
            return 2;
        } else if ($isFriend->is_active) {
            return 1;
        } else if ($isFriend->is_active == 0) {
            return 0;
        } else {
            return 2;
        }
    }

    public function getRequests(Request $request)
    {
        return Friend::where('friend_id', $request->user()->id)->where('is_active', 0)->with('user')->get();
    }

    public function acceptFriend(Request $request)
    {
        $friendRequest = Friend::where('id', $request->id)->first();
        if (Friend::where('friend_id', $request->user()->id)->where('user_id', $friendRequest->user_id)->first() && Friend::where('user_id', $request->user()->id)->where('friend_id', $friendRequest->user_id)->first()) {
            return Friend::where('friend_id', $request->user()->id)->where('is_active', 0)->with('user')->get();
        }
        $friendRequest->is_active = 1;
        $friendRequest->save();

        $data = new Friend();
        $data->friend_id = $friendRequest->user_id;
        $data->user_id = $request->user()->id;
        $data->is_active = 1;
        $data->save();

        return Friend::where('friend_id', $request->user()->id)->where('is_active', 0)->with('user')->get();
    }

    public function rejectFriend(Request $request)
    {
        $friendRequest = Friend::where('id', $request->id)->first();
        Friend::where('friend_id', $request->user()->id)->where('user_id', $friendRequest->user_id)->first()->delete();
        return Friend::where('friend_id', $request->user()->id)->where('is_active', 0)->with('user')->get();
    }

    public function deleteFriend(Request $request)
    {
        $friend = User::where('username', $request->username)->first();
        foreach (Friend::where('friend_id', $request->user()->id)->where('user_id', $friend->id)->get() as $item) {
            $item->delete();
        }
        foreach (Friend::where('user_id', $request->user()->id)->where('friend_id', $friend->id)->get() as $item) {
            $item->delete();
        }
    }
}
