<?php


function plugin_alias_get()
{
    return trim(request()->param("alias"), '\\/');
}

function plugin_method_get()
{
    return ucfirst(request()->param("method", "Index"));
}

function plugin_current_class_get($namespace)
{
    return str_replace('plugin\\', '', $namespace);
}

function plugin_path_get($class = '')
{
    $class = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $class);
    return realpath(app()->getRootPath() . "/plugin/$class");
}

function plugin_template_path_get($pluginClass = ""): string
{
    return plugin_path_get($pluginClass) . '/index.html';
}


function plugin_info_get($alias = '')
{
    return \app\model\Plugin::getByAlias($alias);
}

function plugin_relative_path_get($alias = '')
{
    $model = plugin_info_get($alias);
    if ($model->isEmpty()) {
        return '';
    }
    return $model->class;
}

function plugin_class_get($alias = '')
{
    $model = plugin_info_get($alias);
    if ($model->isEmpty()) {
        return '';
    }
    return "plugin\\$model->class\\App";
}

function plugin_config_get($alias = '')
{
    $model = plugin_info_get($alias);
    if ($model->isEmpty()) {
        return null;
    }
    return $model->config;
}

function plugin_install($options = [])
{
    $model = new \app\model\Plugin();
    $model->allowField([
        'name',
        'alias',
        'path',
        'config',
    ]);
    $model->data($options);
    $model->save();
}