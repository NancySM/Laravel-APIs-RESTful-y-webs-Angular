<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Rutas a mano para el controlador User
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');

//Ruta automática  para el controlador Car
Route::resource('api/cars','CarController');