<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class adminUserController extends Controller
{
    public function allUsers()
    {
        return User::orderByDesc('created_at')->paginate(5);
    }

    public function editUser(Request $request)
    {
        return User::where('id', $request->id)->first();
    }

    public function updatedUser(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->is_active = $request->active;
        $user->save();
        return User::where('id', $request->id)->first();
    }

    public function deleteUser(Request $request)
    {
        User::where('id', $request->id)->first()->delete();
        return User::orderByDesc('created_at')->paginate(5);
    }

    public function adminSearchUser(Request $request)
    {
        if (strlen($request->username) > 0) {
            $user = User::where('username', 'like', '%' . $request->username . '%')->orderByDesc('created_at')->limit(50)->get();
            return $user;
        }
        return [];
    }
}
