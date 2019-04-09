<?php

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

Route::pattern('user', '^([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}){1}$');
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/', 'UserController@index')->name('index');
    Route::get('/{user}', 'UserController@show')->name('show');
    Route::post('/', 'UserController@store')->middleware(['auth.client:register-user'])->name('store');
    Route::put('/{user}', 'UserController@update')->name('update');
    Route::put('/{user}/profile', 'UserController@updateProfile')->name('profile.update');
    Route::put('/{user}/trash', 'UserController@trash')->name('trash');
    Route::put('/{user}/restore', 'UserController@restore')->name('restore');
    Route::delete('/{user}', 'UserController@destroy')->name('destroy');
});

// Illuminate\Routing\Router@auth
Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => ''], function () {

    // Or - UserController@store
    // Route::post('register', 'RegisterController@register')->middleware(['auth.client:register-user']);

    // Authentication
    Route::post('login', 'LoginController@login'); // ->middleware(['auth.client:authenticate-user']);
    Route::post('logout', 'LoginController@logout'); // ->middleware(['auth.client:authenticate-user']);

    // Generate password reset token
    Route::post('password/token', 'PasswordController@token')->middleware(['auth.client:reset-password']);
    Route::put('password/reset', 'PasswordController@reset')->middleware(['auth.client:reset-password']);
});
