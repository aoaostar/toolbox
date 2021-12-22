<?php

namespace plugin\aoaostar_com\example;


use app\model\Plugin;

class Install implements \plugin\Install
{

    public function Install(Plugin $model)
    {
        $model->title = "官方示例工具";
        $model->class = plugin_current_class_get(__NAMESPACE__);
        $model->alias = basename(__NAMESPACE__);
    }

    public function UnInstall(Plugin $model)
    {

    }
}
