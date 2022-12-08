<?php

namespace app\controller\master;


use app\BaseController;
use think\response\Json;

class Clear extends BaseController
{
    public function get(): Json
    {
        clear_cache(true);
        return success();
    }
}
