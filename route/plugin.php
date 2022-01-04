<?php

use think\facade\Route;
use think\facade\View;

Route::rule('api/:alias/[:method]', function () {
    $alias = plugin_alias_get();
    $method = plugin_method_get();
    $class = plugin_class_get($alias);
    if (!class_exists($class)) {
        return msg("error", "该Api不存在");
    }
    $app = new $class();

    if (!method_exists($app, $method)) {
        return msg("error", "该Api不存在该方法");
    }
    return $app->$method();
})->pattern(['alias' => '[\w|\-/]+'])->middleware([\app\middleware\RequestRecord::class]);

Route::get(':alias', function () {
    $alias = plugin_alias_get();
    $path = plugin_relative_path_get($alias);
    if (empty($path)) {
        abort(404, '页面异常');
    }
    $template = plugin_template_path_get($path);
    View::assign([
        "plugin" => plugin_info_get($alias)
    ]);
    return view($template);
})->pattern(['alias' => '[\w|\-/]+'])
    ->middleware(\app\middleware\View::class);