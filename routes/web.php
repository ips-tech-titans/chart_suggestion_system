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

Route::get('/maincall', 'App\Http\Controllers\MainController@setdatabase')->name('maincall');
Route::get('/getalltables', 'App\Http\Controllers\MainController@getDataFromSelectedDB')->name('getalltables');
Route::get('/getalltablesdata', 'App\Http\Controllers\MainController@getDataFromSelectedTables')->name('getalltablesdata');


