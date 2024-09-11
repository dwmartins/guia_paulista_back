<?php

use App\Http\Route;
use App\Middleware\UserMiddleware;

Route::post('/listing/category', 'ListingCategoryController@create', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::post('/listing/category/update', 'ListingCategoryController@update', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::delete('/listing/category/{id}', 'ListingCategoryController@delete', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::post('/listing/category/delete-multiples', 'ListingCategoryController@deleteMultiples', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::post('/listing/category/files/{id}', 'ListingCategoryController@updatePhotoAndIcon', [
    [UserMiddleware::class, 'isAdmin'],
    [UserMiddleware::class, 'contents']
]);

Route::get('/listing/category', 'ListingCategoryController@fetch');