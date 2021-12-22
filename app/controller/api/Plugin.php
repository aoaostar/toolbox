<?php


namespace app\controller\api;


use app\controller\Base;
use think\facade\Request;
use think\facade\Validate;

class Plugin extends Base
{

    public function all()
    {

        $params = Request::param();

        $validate = Validate::rule([
            'page' => 'integer',
            'limit' => 'integer',
            'categoryId' => 'integer',
        ]);
        if (!$validate->check($params)) {

            return msg('error', $validate->getError());
        }
        $params['enable'] = 1;
        $plugins = \app\model\Plugin::all($params);

        $plugins['items'] = array_values($plugins['items']->order('weight', 'desc')->toArray());
        return msg('ok', 'success', $plugins);
    }

    public function star()
    {


        $alias = Request::param('alias');
        $action = Request::param('action', 'add');

        if (empty($alias)) {
            return msg('error', 'alias不得为空');
        }
        $model = \app\model\Plugin::where('alias', $alias)->with(['category'])->findOrEmpty();
        if ($model->isEmpty()) {
            return msg('error', '该工具不存在');
        }
        $user = get_user();
        $stars = $user->stars;
        if ($action === 'add') {
            array_push($stars, $alias);
        } else {
            if (($key = array_search($alias, $user->stars)) !== false) {
                unset($stars[$key]);
            }
        }
        $user->stars = array_unique(array_values($stars));
        $user->save();
        return msg("ok", "success", $user->stars);
    }
}