<?php


namespace app\controller\master;


use app\controller\Base;
use think\facade\Request;
use think\facade\Validate;

class User extends Base
{

    public function all()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'page' => 'integer',
            'limit' => 'integer',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }

        $select = \app\model\User::pagination($params);

        return msg("ok", "success", $select);
    }

    public function get()
    {

        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require|integer',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }
        $plugin = \app\model\User::get($params['id']);
        return success($plugin);
    }

    public function update()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require',
            'username|用户名' => 'require|max:26|graph',
            'stars' => 'is_json',
            'oauth' => 'is_json',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }
        $plugin = \app\model\User::get($params['id']);
        if (empty($params['stars'])) {
            $params['stars'] = [];
        } else {
            $params['stars'] = array_values($params['stars']);
        }
        $plugin->allowField([
            'username',
            'stars',
            'oauth',
        ])->data($params)->save();
        return success($plugin);
    }

    public function delete()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require|integer',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }
        $delete = \app\model\User::get($params['id'])->delete();
        if ($delete) {
            return msg('ok', '删除失败');
        }
        return success();
    }

}