<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/user', 'UserController@create');

Route::put('/user', 'UserController@update', [
    [UserMiddleware::class, 'isAuth']
]);

Route::put('/user/address', 'UserController@updateAddress', [
    [UserMiddleware::class, 'isAuth']
]);

Route::put('/user/settings', 'UserController@updateSettings', [
    [UserMiddleware::class, 'isAuth']
]);

Route::post('/user/update-image', 'UserController@setPhoto', [
    [UserMiddleware::class, 'isAuth']
]);
