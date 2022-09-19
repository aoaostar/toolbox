<?php


namespace app\controller\api;


use app\controller\Base;
use think\facade\Request;

class Category extends Base
{

    public function all()
    {
        $params = Request::param();

        $select = \app\model\Category::all($params);

        return msg("ok", "success", $select);
    }
}