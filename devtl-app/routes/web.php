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

Route::get('link-login/{token}', ['as' => 'link.login', 'uses' => 'Auth\LinkLoginController@login']);
Route::post('link-login', ['as' => 'link.sendLoginEmail', 'uses' => 'Auth\LinkLoginController@sendLinkLoginEmail']);

Auth::routes(['reset' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/', 'SchemaController@index');
    Route::get('/schemas', 'SchemaController@index')->name('home');
});

Route::fallback(function(){
    return view('errors.404');
});
