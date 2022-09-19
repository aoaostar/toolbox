<?php

use think\facade\Route;

Route::rule('api/:alias/[:method]', "Plugin/api")
    ->pattern([
        'alias' => '[\w|\-/]+',
    ])
    ->middleware(\app\middleware\PluginCheck::class);


Route::get(':alias/logo', 'Plugin/logo');

Route::group(':alias', function () {

    Route::get('/static/:path', 'static');
    Route::get('/', 'index');

})->prefix('Plugin/')
    ->pattern([
        'alias' => '[\w|\-/]+',
        'path' => '[\w|\-/]+',
    ])
    ->middleware(\app\middleware\PluginCheck::class)
    ->middleware(\app\middleware\View::class);
