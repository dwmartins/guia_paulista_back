<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/user', 'UserController@create');
Route::post('/user/update-image', 'UserController@setPhoto', [
    [UserMiddleware::class, 'isAuth']
]);
