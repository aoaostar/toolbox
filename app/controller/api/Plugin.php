<?php


namespace app\controller\api;


use app\controller\Base;
use think\facade\Cache;
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

            return error($validate->getError());
        }
        $params['enable'] = 1;
        $plugins = \app\model\Plugin::all($params);

        return success($plugins);
    }

    public function star()
    {
        $alias = Request::param('alias');
        $action = Request::param('action', 'add');

        if (empty($alias)) {
            return error('alias不得为空');
        }
        $model = \app\model\Plugin::where('alias', $alias)->with(['category'])->findOrEmpty();
        if ($model->isEmpty()) {
            return error('该工具不存在');
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
        return success($user->stars);
    }

    public function record()
    {

        $id = Request::param('id');
        $validate = Validate::rule([
            'id' => 'require|number'
        ]);
        if (!$validate->check(['id' => $id])) {
            return error($validate->getError());
        }
        if (Cache::has(__METHOD__ . client_ip())) {
            //多次请求不记录
            return success([], 'Recorded');
        }
        $plugin = \app\model\Plugin::get($id);
        if ($plugin->isExists()) {
            $model = \app\model\Request::whereDay('create_time')->where('plugin_id', $plugin->id)->findOrEmpty();
            if (!$model->isExists()) {
                $model = new \app\model\Request();
                $model->plugin_id = $plugin->id;
            }
            $model->request_count++;
            $plugin->request_count++;
            $model->save();
            $plugin->save();
        }
        Cache::set(__METHOD__ . client_ip(), time(), 3600);
        return success();
    }
}