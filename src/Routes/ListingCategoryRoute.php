<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/listing/category', 'ListingCategoryController@create', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::put('/listing/category', 'ListingCategoryController@update', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::post('/listing/category/files/{id}', 'ListingCategoryController@updatePhotoAndIcon', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);