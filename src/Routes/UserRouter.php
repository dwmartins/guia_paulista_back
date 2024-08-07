<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/user', 'UserController@create');

Route::put('/user', 'UserController@update', [
    [UserMiddleware::class, 'isAuth']
]);

Route::post('/user/update-image', 'UserController@setPhoto', [
    [UserMiddleware::class, 'isAuth']
]);
