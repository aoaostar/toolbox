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

            return msg('error', $validate->getError());
        }
        $plugins = \app\model\Plugin::all($params);

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

    public function record()
    {

        $id = Request::param('id');
        $validate = Validate::rule([
            'id' => 'require|number'
        ]);
        if (!$validate->check(['id' => $id])) {
            return msg('error', $validate->getError());
        }
        if (Cache::has(__METHOD__ . client_ip())) {
            //多次请求不记录
            return msg('ok', 'Recorded');
        }
        $plugin = \app\model\Plugin::where('id', $id)->field('id,request_count')->findOrEmpty();
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
        return msg();
    }
}