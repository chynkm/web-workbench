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
    Route::get('/', 'SchemaController@index')->name('home');
    Route::get('schemas', ['as' => 'schemas.index', 'uses' => 'SchemaController@index']);
    Route::post('schemas', ['as' => 'schemas.store', 'uses' => 'SchemaController@store']);
    Route::get('schemas/{schema}', ['as' => 'schemas.show', 'uses' => 'SchemaController@show']);

    Route::get('schemas/{schema}/tables', ['as' => 'schemaTables.index', 'uses' => 'SchemaTableController@index']);
    Route::post('schemas/{schema}/tables', ['as' => 'schemaTables.store', 'uses' => 'SchemaTableController@store']);

    Route::get('schemaTables/{schemaTable}/columns', ['as' => 'schemaTables.columns', 'uses' => 'SchemaTableController@columns']);
    Route::post('schemaTables/{schemaTable}/columns', ['as' => 'schemaTables.updateColumns', 'uses' => 'SchemaTableController@updateColumns']);
});

Route::fallback(function(){
    return view('errors.404');
});
