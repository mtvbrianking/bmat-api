<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::pattern('user', '^([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}){1}$');
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/', 'UserController@index')->name('index');
    Route::get('/{user}', 'UserController@show')->name('show');
    Route::post('/auth', 'UserController@authenticate')->middleware('auth.client')->name('auth');
    Route::post('/logout', 'UserController@logout')->name('auth.logout');
    Route::get('/create', 'UserController@create')->name('create');
    Route::post('/', 'UserController@store')->middleware('auth.client')->name('store');
    Route::get('/{user}/edit', 'UserController@edit')->name('edit');
    Route::put('/{user}', 'UserController@update')->name('update');
    Route::put('/{user}/profile', 'UserController@updateProfile')->name('profile.update');
    Route::put('/{user}/trash', 'UserController@trash')->name('trash');
    Route::put('/{user}/restore', 'UserController@restore')->name('restore');
    Route::delete('/{user}', 'UserController@destroy')->name('destroy');
});
