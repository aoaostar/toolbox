<?php

namespace app\controller;


use think\facade\View;

class Plugin extends Base
{
    public function api()
    {
        $alias = plugin_alias_get();
        $method = plugin_method_get();
        $class = '\\plugin\\' . plugin_class_get($alias) . '\\App';
        if (!class_exists($class)) {
            return msg("error", "该Api不存在");
        }
        $app = new $class();

        if (!method_exists($app, $method)) {
            return msg("error", "该Api不存在该方法");
        }
        return $app->$method();
    }

    public function static()
    {
        $alias = plugin_alias_get();
        $class_get = plugin_class_get($alias);
        $filename = plugin_path_get() . substr($class_get, 0, strpos($class_get, '\\')) . '/' . request()->pathinfo();

        if (!is_file($filename)) {
            abort(404, 'not found');
        }
        $mime = get_content_type($filename);
        return response(file_get_contents($filename))->contentType($mime)->cacheControl('max-age=86400');
    }

    public function logo()
    {
        if (request()->ext() !== 'png') {
            abort(403, 'not allow');
        }
        $alias = plugin_alias_get();
        $filename = plugin_logo_path_get($alias);
        if (!is_file($filename)) {
            $filename = public_path() . '/static/images/plugin_default.png';
        }
        $mime = get_content_type($filename);
        return response(file_get_contents($filename))->contentType($mime)->cacheControl('max-age=86400');
    }

    public function index()
    {
        $alias = plugin_alias_get();
        $path = plugin_relative_path_get($alias);
        if (empty($path)) {
            abort(404, '页面异常');
        }
        $template = plugin_template_path_get($path);
        $model = plugin_info_get($alias);
        View::assign([
            "plugin" => $model
        ]);
        if ($model->template !== 'default') {
            $template = template_path_get() . 'template/' . $model->template . '.html';
        }
        return view($template);
    }
}
