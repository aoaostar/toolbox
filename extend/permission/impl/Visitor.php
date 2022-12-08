<?php


namespace permission\impl;


use permission\Permission;

class Visitor implements Permission
{
    public static function check($plugin): bool
    {
        return true;
    }
}