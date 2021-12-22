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

            return msg('error', $validate->getError());
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

            return msg('error', $validate->getError());
        }
        $plugin = \app\model\User::get($params['id']);
        return msg('ok', 'success', $plugin);
    }

    public function update()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require',
            'stars' => 'is_json',
        ]);
        if (!$validate->check($params)) {

            return msg('error', $validate->getError());
        }
        $plugin = \app\model\User::get($params['id']);
        if (empty($params['stars'])){
            $params['stars'] = [];
        }else{
            $params['stars'] = array_values($params['stars']);
        }
        $plugin->allowField([
            'stars',
        ])->data($params)->save();
        return msg('ok', 'success', $plugin);
    }
    public function delete()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require|integer',
        ]);
        if (!$validate->check($params)) {

            return msg('error', $validate->getError());
        }
        $delete = \app\model\User::where('id',$params['id'])->delete();
        if ($delete){
            return msg('ok', '删除失败');
        }
        return msg('ok', 'success');
    }

}