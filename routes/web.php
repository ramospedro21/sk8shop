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

Route::get('/', 'Site\HomeController@index');

Auth::routes();

// ROTA DA HOME
Route::get('/home', 'HomeController@index')->name('home');

// ROTAS DO PAINEL
Route::prefix('/painel')->group(function(){

    // ROTA DE ESTOQUES
    Route::get('/stocks', 'Painel\StocksController@view')->middleware('auth');
    Route::resource('/stock', 'Painel\StocksController');
    Route::get('/stocks/all', 'Painel\StocksController@all');

    // ROTA DE FORNECEDORES
    Route::get('/providers', 'Painel\ProvidersController@view')->middleware('auth');
    Route::resource('/provider', 'Painel\ProvidersController');

    // ROTA DE COMPRAS
    Route::get('/purchases', 'Painel\PurchasesController@view')->middleware('auth');
    Route::resource('/purchase', 'Painel\PurchasesController');

    // ROTA DE USUÁRIOS
    Route::get('/users', 'Painel\UsersController@view')->middleware('auth');
    Route::resource('/user', 'Painel\UsersController');

    // ROTA DOS MODULOS
    Route::get('/user_types', 'Painel\UserTypeController@view')->middleware('auth');
    Route::resource('/user_type', 'Painel\UserTypeController');
    Route::get('/type/all', 'Painel\UserTypeController@all');
    Route::get('/modules/all', 'Painel\UserTypeController@allModules');

    // ROTA DE CENTRAL DE OPÇÕES
    Route::get('/options', 'Painel\OptionsController@view')->middleware('auth');
    Route::delete('/option/{id}/{type}', 'Painel\OptionsController@destroy');
    Route::resource('/option', 'Painel\OptionsController');
    Route::get('/options/all', 'Painel\OptionsController@all');

    // ROTA DE CATEGORIAS
    Route::get('/categories', 'Painel\CategoriesController@view')->middleware('auth');
    Route::resource('/category', 'Painel\CategoriesController');

    // ROTA DE PRODUTOS
    Route::get('/products', 'Painel\ProductsController@view')->middleware('auth');
    Route::get('/product/new', 'Painel\ProductsController@create');
    Route::resource('/product', 'Painel\ProductsController');

    // ROTA DE EMBALAGENS
    Route::get('/boxes', 'Painel\BoxesController@view')->middleware('auth');
    Route::resource('/box', 'Painel\BoxesController');

    // ROTA DE CUPONS DE DESCONTO
    Route::get('/coupons', 'Painel\CouponsController@view')->middleware('auth');
    Route::resource('/coupon', 'Painel\CouponsController');

    // ROTA DE CLIENTES
    Route::get('/clients', 'Painel\ClientsController@view')->middleware('auth');
    Route::resource('/client', 'Painel\ClientsController');

});

// ROTAS DA LOJA
Route::post('/register', 'Site\RegisterController@create');
Route::get('/p/{slug}', 'Site\ProductsController@show');
Route::post('/carrinho', 'Site\CartController@store');
Route::get('/carrinho', 'Site\CartController@index');
Route::get('/carrinho/details', 'Site\CartController@details');
Route::patch('/carrinho/details', 'Site\CartController@update');
Route::get('/checkout', 'Site\PaymentController@index');
Route::post('/calculo-frete', 'Site\ShippingsController@calculate');
Route::post('/payments', 'Site\PaymentController@store');
