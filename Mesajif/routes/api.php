<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PostController;
use \App\Http\Controllers\FriendController;
use \App\Http\Controllers\adminUserController;
use App\Http\Controllers\adminPostsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return $request->user();
});

##################################### Giriş İşlemleri #####################################
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
##################################### Giriş İşlemleri #####################################


##################################### Kullanıcı İşlemleri #####################################
//Birinin profilini açarken kullanılan fonksiyon
Route::post('getUser', [UserController::class, 'getUser'])->middleware('auth:sanctum');

//Arama yaparken tüm kullanıcıları çağırma
Route::post('searchUser', [UserController::class, 'searchUser'])->middleware('auth:sanctum');

//Ayarlarda bilgilerimi değiştirmek için şifre kontrolü
Route::post('checkPassword', [UserController::class, 'checkPassword'])->middleware('auth:sanctum');

//Ayarları güncelleme
Route::post('changeSettings', [UserController::class, 'changeSettings'])->middleware('auth:sanctum');

##################################### Kullanıcı İşlemleri #####################################


##################################### Gönderi İşlemleri #####################################
//gönderi paylaşmak için kullanılan fonksiyon
Route::post('sendPost', [PostController::class, 'sendPost'])->middleware('auth:sanctum');

//Arkadaşlarımın gönderileri
Route::post('getPosts', [PostController::class, 'getPosts'])->middleware('auth:sanctum');

//Bir kullanıcının gönderileri
Route::post('getUserPosts', [PostController::class, 'getUserPosts'])->middleware('auth:sanctum');

//Gönderi beğenme
Route::post('likePost', [PostController::class, 'likePost'])->middleware('auth:sanctum');

//Yalnızca bir gönderiyi çekme
Route::post('getPost', [PostController::class, 'getPost'])->middleware('auth:sanctum');

//Kullanıcının tüm gönderileri
Route::post('deleteUserPosts', [PostController::class, 'deleteMyAllMessage'])->middleware('auth:sanctum');

##################################### Gönderi İşlemleri #####################################


##################################### Arkadaşlık İşlemleri #####################################
//Arkadaş ekleme işlemi
Route::post('addFriend', [FriendController::class, 'addFriend'])->middleware('auth:sanctum');

//Arkadaş mı? değil mi? sorgusu
Route::post('isFriend', [FriendController::class, 'isFriend'])->middleware('auth:sanctum');

//Arkadaşlık istekleri
Route::post('getRequests', [FriendController::class, 'getRequests'])->middleware('auth:sanctum');

//Arkadaşlık isteğini kabul etme
Route::post('acceptFriend', [FriendController::class, 'acceptFriend'])->middleware('auth:sanctum');

//Arkadaşlık isteğini reddetme
Route::post('rejectFriend', [FriendController::class, 'rejectFriend'])->middleware('auth:sanctum');

//Arkadaşlıktan çıkarma
Route::post('deleteFriend', [FriendController::class, 'deleteFriend'])->middleware('auth:sanctum');

##################################### Arkadaşlık İşlemleri #####################################


##################################### Mesajlaşma İşlemleri #####################################
//Mesajlaşacak kullanıcıları çekme
Route::post('contacts', [MessageController::class, 'contacts'])->middleware('auth:sanctum');

//Mesajları çekme
Route::post('messages', [MessageController::class, 'messages'])->middleware('auth:sanctum');

//Mesaj gönderme
Route::post('sendMessage', [MessageController::class, 'sendMessage'])->middleware('auth:sanctum');

##################################### Mesajlaşma İşlemleri #####################################


##################################### Admin Kullanıcı İşlemleri #####################################

//adminde tüm kullanıcıları getirmek için kullanılan fonksiyon
Route::post('allUsers', [adminUserController::class, 'allUsers'])->middleware('role')->middleware('auth:sanctum');


//adminde bir kullanıcıyı düzenlemek için kullanılan fonksiyon
Route::post('editUser', [adminUserController::class, 'editUser'])->middleware('role')->middleware('auth:sanctum');

//Adminde bir kullanıcıyı düzenleme
Route::post('updatedUser', [adminUserController::class, 'updatedUser'])->middleware('role')->middleware('auth:sanctum');

//Adminde bir kullanıcıyı silme
Route::post('deleteUser', [adminUserController::class, 'deleteUser'])->middleware('role')->middleware('auth:sanctum');

//Adminde kullanıcı arama işlemi
Route::post('adminSearchUser', [adminUserController::class, 'adminSearchUser'])->middleware('role')->middleware('auth:sanctum');

##################################### Admin Kullanıcı İşlemleri #####################################


##################################### Admin Gönderi İşlemleri #####################################
//Adminde kullanıcının tüm gönderileri
Route::post('adminGetUserPosts', [adminPostsController::class, 'adminGetUserPosts'])->middleware('role')->middleware('auth:sanctum');

//Adminde Gönderi sil
Route::post('adminDeletePost', [adminPostsController::class, 'adminDeletePost'])->middleware('role')->middleware('auth:sanctum');
##################################### Admin Gönderi İşlemleri #####################################


##################################### Admin Site Ayarları #####################################

Route::get('settings', [SettingController::class, "settings"])->middleware('auth:sanctum');

Route::post('editsettings', [SettingController::class, "editsettings"])->middleware('role')->middleware('auth:sanctum');

##################################### Admin Gönderi İşlemleri #####################################
