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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::pattern('user', '^\d+$');
Route::group(['prefix' => 'users', 'name' => 'users.'], function () {
    Route::get('/', 'UserController@index')->name('index');
    Route::get('/{user}', 'UserController@show')->name('show');
    Route::get('/create', 'UserController@create')->name('create');
    Route::post('/', 'UserController@store')->middleware('auth.client')->name('store');
    Route::get('/{user}/edit', 'UserController@edit')->name('edit');
    Route::put('/{user}', 'UserController@update')->name('update');
    Route::put('/{user}/trash', 'UserController@trash')->name('trash');
    Route::put('/{user}/restore', 'UserController@restore')->name('restore');
    Route::delete('/{user}/destroy', 'UserController@destroy')->name('destroy');
});
