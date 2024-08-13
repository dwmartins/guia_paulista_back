<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/discount-code', 'DiscountCodeController@create', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::put('/discount-code', 'DiscountCodeController@update', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::get('/discount-code', 'DiscountCodeController@fetchAll', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::get('/discount-code/validate', 'DiscountCodeController@validate');