<?php


namespace permission\impl;


use permission\Permission;
use think\facade\Session;

class Password implements Permission
{
    public static function check($plugin)
    {
        $key = __METHOD__ . '__' . $plugin->id;
        $pass = Session::get($key);
        if (time() - $pass < 86400) {
            return true;
        }
        if (!empty($plugin->config->password) && $plugin->config->password == request()->param('password')) {
            Session::set($key, time());
            if (request()->isAjax()) {
                return success();
            }
            return true;
        }
        if (request()->isAjax()) {
            if (empty(request()->param('password'))) {
                return '请验证密码后重试';
            }
            return '密码错误';
        }
        return view('permission/password');
    }
}