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
        Route::delete('/auth/token', 'AuthController@unauthenticate')->name('auth.token.delete');

        Route::apiResource('/users', 'UserController', [
            'except' => ['store']
        ]);
        Route::apiResource('/users/{user}/notifications', 'NotificationController', [
            'except' => ['store', 'show']
        ]);
        Route::apiResource('/conversations', 'ConversationController');
        Route::apiResource('/conversations/{conversation}/users', 'ConversationUserController', [
            'except' => ['show']
        ]);
        Route::apiResource('/conversations/{conversation}/messages', 'MessageController', [
            'except' => ['show', 'update']
        ]);
        Route::apiResource('/conversations/{conversation}/messages/{message}/users', 'MessageUserController', [
            'only' => ['store']
        ]);
    });

    Route::post('/auth/register', 'AuthController@register')->name('auth.register');
    Route::get('/auth/redirect', 'AuthController@redirect')->name('auth.redirect');
    Route::post('/auth/token', 'AuthController@authenticate')->name('auth.token.create');
});
