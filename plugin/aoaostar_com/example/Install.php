<?php

namespace plugin\aoaostar_com\example;


use app\model\Plugin;

class Install implements \plugin\Install
{

    public function Install(Plugin $model)
    {
        $model->title = "Hello，Pluto";
        $model->class = plugin_current_class_get(__NAMESPACE__);
        $model->alias = basename(__NAMESPACE__);
        $model->desc = 'If you see this message, it means that your program is running properly.';
        $model->version = 'v1.0';
    }

    public function UnInstall(Plugin $model)
    {

    }
}
