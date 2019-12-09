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

use App\Bullpen1;

Route::get('/bullpen1', 'Bullpen1Controller@getAllBullpens');

Route::post('/addsheep','Bullpen1Controller@addSheep');

Route::post('/killsheep','Bullpen1Controller@killSheep');

Route::post('/relocatesheep','Bullpen1Controller@relocateSheep');
