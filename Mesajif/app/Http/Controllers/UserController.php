<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //Birinin profilini açarken kullanılan fonksiyon
    public function getUser(Request $request)
    {
        $user = User::select('name', 'username', 'image', 'about', 'image')->where('username', $request->username)->first();
        return $user;
    }

    //Arama yaparken aranan kullanıcıları çağırma
    public function searchUser(Request $request)
    {
        if (strlen($request->username) > 0) {
            $user = User::select('name', 'username', 'image')->where('username', 'like', '%' . $request->username . '%')->limit(5)->get();
            return $user;
        }
        return [];
    }

    //Ayarları güncelleme
    public function changeSettings(Request $request)
    {
        $user = $request->user();
        if ($request->newEmail) {
            $request->validate([
                'email' => 'email|unique:users'
            ]);
            $user->email = $request->email;
        }
        if ($request->newUsername) {
            $request->validate([
                'username' => 'required|unique:users|max:20'
            ]);
            $user->username = Str::snake($request->username);
        }
        if ($request->newName) {
            $request->validate([
                'name' => 'required|max:20'
            ]);
            $user->name = $request->name;
        }
        if ($request->newAbout_me) {
            $request->validate([
                'about_me' => 'required|max:255'
            ]);
            $user->about = $request->about;
        }
        if ($request->newImage) {
            if ($request->file('image')) {
                $user->image = $request->file('image')->store('profilePhoto', 'public');
            }
        }
        if ($request->newPassword) {
            $request->validate([
                'password' => 'required'
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $tokenResult = $user->createToken('Personal Access');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'about_me' => $user->about,
            'email' => $user->email,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    //Ayarlardaki şifreleri değiştir
    public function checkPassword(Request $request)
    {
        if (!Hash::check($request->password, $request->user()->password)) {
            return 0;
        }
        if ($request->email != $request->user()->email) {
            return 0;
        }

        return 1;
    }
}
