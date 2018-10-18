<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/verify/{user}', 'VerificationController')
    ->name('auth.verify');

Route::match(['get', 'post'], '/password/forgot', 'PasswordResetController@forgot')
    ->name('password.forgot');

Route::get('/password/reset/{token}', 'PasswordResetController@reset')
    ->name('password.reset');

Route::get('/{any}', 'IndexController')
    ->where('any', '.*')
    ->name('index');
