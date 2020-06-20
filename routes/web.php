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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware'=>'auth'],function (){
    Route::get('/game','GameController@show')->name('showGame');
    Route::post('/create','GameController@createGame')->name('createGame');
    Route::post('/takeCard','GameController@takeCard')->name('takeCard');
    Route::post('/startNewTurn','GameController@startNewTurn')->name('startNewTurn');
    Route::post('/stand','GameController@stand')->name('stand');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
