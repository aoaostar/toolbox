<?php

use think\facade\Route;


Route::group('auth', function () {
    Route::get('/', 'login');
    Route::get('/login', 'login')
        ->middleware(\app\middleware\View::class);
    Route::get('/logout', 'logout');
    Route::get('/oauth', 'Auth/oauth');
})->prefix('Auth/');

Route::get('/oauth/callback', 'Auth/callback');
