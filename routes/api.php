<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SupportController;

Route::post('register/email', [RegisterController::class, 'register']);
//Route::post('register/number', [RegisterController::class, 'registerNumber']);

//Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
//    ->name('verification.verify');
//
//Route::post('/email/resend', [VerificationController::class, 'resend'])
//    ->name('verification.resend');

Route::post('/send-verification-code', [VerificationController::class, 'sendVerificationCode']);
Route::post('/verify-code', [VerificationController::class, 'verifyCode']);

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'user_auth'], function ($router) {
    Route::group(['middleware' => 'verify'], function ($router) {
        Route::put('update', [UserController::class, 'update']);
        Route::post('review', [UserController::class, 'review']);
        Route::post('favorite', [UserController::class, 'favorite']);
        Route::get('favorites', [UserController::class, 'indexFavorite']);
        Route::get('reviews', [UserController::class, 'indexReview']);
        Route::post('upload/photo', [UserController::class, 'uploadPhoto']);
        Route::get('user/photo', [UserController::class, 'photo']);
        Route::get('user', [UserController::class, 'user']);
        Route::post('support/create', [SupportController::class, 'create']);
        Route::post('reaction', [CardController::class, 'reaction']);
        Route::post('reaction/img', [CardController::class, 'reactionImage']);
        Route::post('update/email', [UserController::class, 'updateEmail']);
        Route::post('verify-code/email', [UserController::class, 'verifyCodeEmail']);
    });
});

//Route::get('cards/photo/{cat_id}/{id}/{page}', [CardController::class, 'photoCards']);
Route::get('/images', [CardController::class, 'imagesAll']);
Route::get('/images/reviews', [CardController::class, 'imagesAll']);

Route::get('cards/attractions', [CardController::class, 'indexAttractions']);
Route::get('cards/attractions/{id}', [CardController::class, 'showAttractions']);

Route::get('cards/foods', [CardController::class, 'indexFoods']);
Route::get('cards/foods/{id}', [CardController::class, 'showFoods']);

Route::get('cards/routers', [CardController::class, 'indexRouters']);
Route::get('cards/routers/{id}', [CardController::class, 'showRouters']);

Route::get('cards/shopings', [CardController::class, 'indexShopings']);
Route::get('cards/shopings/{id}', [CardController::class, 'showShopings']);

Route::get('cards/posters', [CardController::class, 'indexPosters']);
Route::get('cards/posters/{id}', [CardController::class, 'showPosters']);


