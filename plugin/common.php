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
    return app()->getRootPath() . "plugin/$class";
}

function plugin_logo_path_get($alias)
{
    return plugin_path_get(plugin_class_get($alias)) . "/logo.png";
}

function plugin_logo_relative_path_get($alias)
{
    return $alias . '/logo.png';
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
    return str_replace('\\', '/', plugin_class_get($alias));
}

function plugin_class_get($alias = '')
{
    $model = plugin_info_get($alias);
    if (!$model->isExists()) {
        return '';
    }
    return $model->class;
}

function plugin_config_get($alias = '')
{
    $model = plugin_info_get($alias);
    if (!$model->isExists()) {
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

function plugin_static($alias)
{
    if (is_string($alias)) {

        return "/$alias/static";
    }
    return "/$alias->alias/static";
}