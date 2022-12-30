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
Route::get('/loadchart', 'App\Http\Controllers\MainController@loadchart')->name('loadchart');
Route::post('/getcolumnsfromdatabase', 'App\Http\Controllers\MainController@getcolumnsfromdatabase')->name('getcolumnsfromdatabase');
Route::get('/setdatabase', 'App\Http\Controllers\MainController@setdatabase')->name('setdatabase');
Route::get('/getalltables', 'App\Http\Controllers\MainController@getDataFromSelectedDB')->name('getalltables');
Route::post('/getalltablesdata', 'App\Http\Controllers\MainController@getDataFromSelectedTables')->name('getalltablesdata');
Route::post('/getDataFromSelectedTableswithDb', 'App\Http\Controllers\MainController@getDataFromSelectedTableswithDb')->name('getDataFromSelectedTableswithDb');


