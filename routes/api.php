<?php

use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->group(function () {
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::apiResource('/tokens', 'TokenController', ['except' => ['show', 'update']]);
        Route::apiResource('/users', 'UserController');
        Route::apiResource('/users/{user}/friends', 'UserFriendController', ['except' => ['show']]);
        Route::apiResource('/users/{user}/notifications', 'NotificationController', ['except' => ['store', 'show']]);
        Route::apiResource('/conversations', 'ConversationController');
        Route::apiResource('/conversations/{conversation}/users', 'ConversationUserController', ['except' => ['show']]);
        Route::apiResource('/conversations/{conversation}/messages', 'MessageController', [
            'except' => ['show', 'update']
        ]);
        Route::apiResource('/conversations/{conversation}/messages/{message}/users', 'MessageUserController', [
            'only' => ['store']
        ]);
    });
    Route::post('/tokens', 'TokenController@store')->name('tokens.store');
    Route::post('/users', 'UserController@store')->name('users.store');

    Route::get('/oauth/redirect', 'TokenController@redirect')->name('oauth.redirect');

    Route::post('/password/forgot', 'PasswordController@forgot')->name('password.forgot');
    Route::post('/password/reset', 'PasswordController@reset')->name('password.reset');
});
