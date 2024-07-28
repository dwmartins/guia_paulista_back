<?php

use App\Http\Route;

Route::get('/', 'HomeController@index');
Route::get('/test', 'HomeController@test');