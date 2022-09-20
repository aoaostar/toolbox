<?php

use think\facade\Route;

Route::rule('api/:alias/[:method]', "Plugin/api")
    ->middleware(\app\middleware\PluginCheck::class);


Route::get(':alias/logo', 'Plugin/logo');

Route::group(':alias', function () {

    Route::get('/static/*', 'static');
    Route::get('/[:path]', 'index')
        ->middleware(\app\middleware\View::class);

})->prefix('Plugin/')
    ->middleware(\app\middleware\PluginCheck::class);
