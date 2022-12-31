<?php

use App\Http\Controllers\CSVController;
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

Route::get('/maincall', 'App\Http\Controllers\MainController@main')->name('maincall');

Route::get('csv-file','App\Http\Controllers\CSVController@index')->name('csv-file');
Route::post('csv-store','App\Http\Controllers\CSVController@store')->name('csv-store');

Route::get('csv/{name}','App\Http\Controllers\CSVController@show')->name('csv.show');