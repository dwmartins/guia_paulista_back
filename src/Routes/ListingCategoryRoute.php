<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/listing/category', 'ListingCategoryController@create', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);