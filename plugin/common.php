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

function plugin_path_get($class = null)
{
    if (is_null($class)) {
        $class = plugin_class_get();
    }
    return str_replace('\\', '/', app()->getRootPath() . "plugin/$class");
}

function plugin_logo_path_get($alias)
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    return plugin_path_get(plugin_class_get($alias)) . "/logo.png";
}

function plugin_logo_relative_path_get($alias)
{
    return $alias . '/logo.png';
}

function plugin_template_path_get($class): string
{
    return plugin_path_get($class) . '/index.html';
}

function plugin_info_get($alias = null): \app\model\Plugin
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    return \app\model\Plugin::getByAlias($alias);
}

function plugin_relative_path_get($alias = null)
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    return str_replace('\\', '/', plugin_class_get($alias));
}

function plugin_class_get($alias = null)
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    $model = plugin_info_get($alias);
    if (!$model->isExists()) {
        return '';
    }
    return $model->class;
}

function plugin_config_get($alias = null)
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    $model = plugin_info_get($alias);
    if (!$model->isExists()) {
        return null;
    }
    return $model->config;
}

function plugin_static($alias = null)
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    if (is_string($alias)) {
        return "/$alias/static";
    }
    return "/$alias->alias/static";
}
function plugin_api($alias = null)
{
    if (is_null($alias)) {
        $alias = plugin_alias_get();
    }
    if (is_string($alias)) {
        return "/api/$alias";
    }
    return "/api/$alias->alias";
}