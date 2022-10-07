<?php

use think\facade\Route;

Route::group('api', function () {
    Route::any('plugins', 'Plugin/all');
    Route::any('categories', 'Category/all');
    Route::any('record', 'Plugin/record');
    Route::any('captcha', function (){
        return captcha();
    });
})->prefix('api.');



Route::group('api', function () {
    Route::any('plugin/star', 'Plugin/star');
    Route::get('mine', 'User/get');
    Route::get('user', 'User/get');
    Route::post('user', 'User/update');
})->prefix('api.')
    ->middleware(\app\middleware\Auth::class);


