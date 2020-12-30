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

Route::get('/profile', function(){
    return view('profile');
});

Route::get('/profile/editar', function(){
    return "Estas editando";
});


Route::get('/ver/{id}', 'ProfileController@index');

Route::group(['prefix'=>'admin','as'=> 'admin'], function(){
    Route::get('/','AdminController@index');
    Route::get('/usuarios', 'UsersController@index');
    Route::post('/usuarios/edit', 'UsersController@');
    Route::get('/productos','ProductosController@index');
    Route::post('/productos/all','ProductosController@all');
    Route::get('/productos/imprimir','ProductosController@imprimir');
    
    Route::resource('usuarios','UsersController');
    Route::resource('productos','ProductosController');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
