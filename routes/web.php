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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/game','GameController@index');
Route::post('/game/guess','GameController@guess');
Route::post('/game/whole','GameController@whole');
Route::get('/game/{game_id}','GameController@index');
Route::post('/notify','GameController@notifyGame');
Route::post('/opengames','HomeController@openGames');
