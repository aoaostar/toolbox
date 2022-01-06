<?php

use think\facade\Route;

Route::any('/stars', 'Index/stars')
    ->middleware(\app\middleware\Auth::class)
    ->middleware(\app\middleware\View::class);

Route::get('/install/database', 'Install/database');
Route::get('/install/init_data', 'Install/init_data');
Route::get('/install/oauth', 'Install/oauth');
Route::get('/install', 'Install/index');

Route::get('/', 'Index/index')
    ->middleware(\app\middleware\View::class);