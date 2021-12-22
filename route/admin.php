<?php

use think\facade\Route;

Route::group('master', function () {
    Route::get('/menu', 'Menu/get');
    Route::get('/clear', 'Clear/get');
    Route::post('/plugin/install_status', 'Plugin/install_status');
    Route::post('/plugin/upload', 'Plugin/upload');
    Route::get('/plugins', 'Plugin/all');
    Route::get('/plugin', 'Plugin/get');
    Route::post('/plugin', 'Plugin/create');
    Route::put('/plugin', 'Plugin/update');
    Route::delete('/plugin', 'Plugin/delete');

    Route::get('/categories', 'Category/all');
    Route::get('/category', 'Category/get');
    Route::post('/category', 'Category/create');
    Route::put('/category', 'Category/update');
    Route::delete('/category', 'Category/delete');

    Route::get('/system/templates', 'System/templates');
    Route::get('/system/info', 'System/info');
    Route::get('/system', 'System/all');
    Route::post('/system', 'System/update');
    Route::put('/system', 'System/update');

    Route::get('/users', 'User/all');
    Route::get('/user', 'User/get');
    Route::post('/user', 'User/create');
    Route::put('/user', 'User/update');
    Route::delete('/user', 'User/delete');
})->prefix('master.')->middleware(\app\middleware\AuthAdmin::class);


Route::group('master/cloud', function () {
    Route::get('plugins', 'plugins');
    Route::get('plugin', 'plugin_get');
    Route::get('categories', 'categories');
    Route::get('releases', 'releases');
    Route::get('plugin_install', 'plugin_install');
})->prefix('master.Cloud/')->middleware(\app\middleware\AuthAdmin::class);

Route::group('master/ota', function () {
    Route::any('/', 'check');
    Route::any('check', 'check');
    Route::any('update', 'update');
    Route::any('database', 'updateDatabase');
    Route::any('script', 'updateScript');
})->prefix('master.Ota/')->middleware(\app\middleware\AuthAdmin::class);

Route::group('master/analysis', function () {
    Route::get('plugin_total_request_count', 'plugin_total_request_count');
    Route::get('plugin_max_request_count', 'plugin_max_request_count');
    Route::get('plugin_request_count', 'plugin_request_count');
    Route::get('user_active_count', 'user_active_count');
    Route::get('user_increase_count', 'user_increase_count');
})->prefix('master.Analysis/')->middleware(\app\middleware\AuthAdmin::class);
