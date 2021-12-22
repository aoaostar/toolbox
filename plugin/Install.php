<?php


namespace plugin;

use app\model\Plugin;

interface Install
{
    public function Install(Plugin $model);

    public function UnInstall(Plugin $model);
}