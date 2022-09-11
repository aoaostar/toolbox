<?php

use think\facade\Route;


Route::group('auth', function () {
    Route::get('/', 'login');
    Route::get('/login', 'login');
    Route::get('/logout', 'logout');
    Route::get('/oauth/[:mode]', 'Auth/oauth');
})->prefix('Auth/')
    ->middleware(\app\middleware\View::class);

Route::get('/oauth/callback/[:mode]', 'Auth/callback')
    ->middleware(\app\middleware\BindAuth::class)
    ->middleware(\app\middleware\View::class);
