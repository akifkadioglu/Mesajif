<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => "required",
            'username' => "required|unique:users",
            'password' => "required|confirmed",
            'email' => "required|email|unique:users"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Bilgileri kontrol ediniz.'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'access_token' => $token,
            'is_admin' => $user->is_admin,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Kullanıcı adı ve şifre eşleşmiyor. Bilgileri kontrol ediniz.'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
