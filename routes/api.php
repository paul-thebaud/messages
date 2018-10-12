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
        Route::delete('/auth/token', 'AuthController@unauthenticate');

        Route::apiResource('/users', 'UserController');
        Route::apiResource('/users/{user}/friends', 'UserController@friends');
        Route::apiResource('/conversations', 'ConversationController');
    });

    Route::post('/auth/register', 'AuthController@register');
    Route::get('/auth/redirect', 'AuthController@redirect');
    Route::post('/auth/token', 'AuthController@authenticate');
});
