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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// ROTA DA HOME
Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('/painel')->group(function(){

    // ROTA DE ESTOQUES
    Route::get('/stocks', 'Painel\StocksController@view')->middleware('auth');
    Route::resource('/stock', 'Painel\StocksController');

    // ROTA DOS MODULOS
    Route::get('/user_types', 'Painel\UserTypeController@view')->middleware('auth');
    Route::resource('/user_type', 'Painel\UserTypeController');
    Route::get('/modules/all', 'Painel\UserTypeController@all');


});