<?php


namespace app\lib\permission\impl;


use app\lib\permission\Permission;

class Visitor implements Permission
{
    public static function check($plugin)
    {
        return true;
    }
}