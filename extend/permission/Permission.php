<?php


namespace permission;


use app\model\Plugin;

interface Permission
{
    public static function check(Plugin $plugin);
}