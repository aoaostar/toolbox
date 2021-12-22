<?php

use think\facade\Route;



Route::group('api', function () {
    Route::any('plugins', 'plugin/all');
    Route::any('plugin/star', 'plugin/star');
    Route::any('categories', 'category/all');
})->prefix('api.');