<?php

namespace app\controller;


use think\facade\View;

class User extends Base
{
    public function index()
    {
        $oauth_modes = get_enabled_oauth_mode();

        $arr = [];
        $user = get_user();
        foreach ($oauth_modes as $v) {
            if (!empty($user->oauth[$v])) {
                $arr[$v] = $user->oauth[$v];
            } else {
                $arr[$v] = 0;
            }
        }
        View::assign('oauth', $arr);
        return view();
    }
}
