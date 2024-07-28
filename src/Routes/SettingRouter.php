<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/settings', 'SettingsController@update', [
    [UserMiddleware::class, 'isAuth'],
    [UserMiddleware::class, 'settings']
]);