<?php


namespace app\lib\permission\impl;


use app\lib\permission\Permission;

class Login implements Permission
{
    public static function check($plugin)
    {
        if (!is_login()) {
            return redirect((string)url('/auth/login'));
        }
        return true;
    }
}