<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', [
    'uses' => 'HomeController@index',
    'as' => 'index',
]);

Auth::routes();
Route::resource('accounts', 'AccountController');
Route::get('accounts/{id}/login', [
    'uses' => 'AccountController@login',
    'as' => 'accounts.login',
]);
