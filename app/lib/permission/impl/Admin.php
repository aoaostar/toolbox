<?php


namespace app\lib\permission\impl;


use app\lib\permission\Permission;

class Admin implements Permission
{
    public static function check($plugin)
    {
        if (!is_admin()){
            return '无权访问，请升级权限后重试';
        }
        return true;
    }
}