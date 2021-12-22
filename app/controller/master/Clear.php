<?php

namespace app\controller\master;


use app\BaseController;
use think\facade\Cache;

class Clear extends BaseController
{
    public function get(){
        Cache::clear();
        reset_opcache();
        return msg();
    }
}
