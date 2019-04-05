<?php

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

Auth::routes(['verify' => false]);

Route::get('/', 'FrontController@index')->name('home');

Route::get('/usage', 'FrontController@showApiUsageDoc')->name('doc.usage');

Route::get('/source', 'FrontController@showApiCodeDoc')->name('doc.source');

Route::get('/routes', 'FrontController@showApplicationRoutes')->name('routes');
