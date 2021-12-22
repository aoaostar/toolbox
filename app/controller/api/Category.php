<?php


namespace app\controller\api;


use app\controller\Base;
use think\facade\Request;

class Category extends Base
{

    public function all()
    {
        $param = Request::param();
        $where = [];
        if (!empty($param['name'])) {
            $where[] = ["name", 'like', '%' . Request::param('name"') . '%'];
        }
        if (!empty($param['title'])) {
            $where[] = ["title", 'like', '%' . Request::param('title"') . '%'];
        }

        $select = \app\model\Category::where($where)->order('weight', 'desc')->select();

        return msg("ok", "success", $select);
    }
}