<?php

namespace app\controller\master;


use app\BaseController;

class Clear extends BaseController
{
    public function get()
    {
        clear_cache(true);
        return success();
    }
}
