<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SupportController;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register/email', [RegisterController::class, 'registerEmail']);
Route::post('register/number', [RegisterController::class, 'registerNumber']);

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'user_auth'], function ($router) {
    Route::put('update', [UserController::class, 'update']);
    Route::post('review', [UserController::class, 'review']);
    Route::post('favorite', [UserController::class, 'favorite']);
    Route::get('favorites', [UserController::class, 'indexFavorite']);
    Route::get('reviews', [UserController::class, 'indexReview']);
    Route::post('upload/photo', [UserController::class, 'uploadPhoto']);
    Route::get('user/photo', [UserController::class, 'photo']);
    Route::get('user', [UserController::class, 'user']);
    Route::post('support/create', [SupportController::class, 'create']);
});

Route::get('cards', [CardController::class, 'index']);
Route::get('cards/{id}', [CardController::class, 'show']);
Route::get('cards/category/{id}', [CardController::class, 'showIndex']);
Route::get('cards/photo/{id}/{page}', [CardController::class, 'photo']);




