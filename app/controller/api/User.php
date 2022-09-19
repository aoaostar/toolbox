<?php


namespace app\controller\api;


use app\controller\Base;
use think\facade\Validate;

class User extends Base
{

    public function get()
    {
        return msg('ok', 'success', get_user());
    }

    public function update()
    {
        $validate = Validate::rule([
            'username' => 'require|max:26|graph',
        ]);
        $params = request()->only(['username']);
        if (!$validate->check($params)) {
            return msg('error', $validate->getError());
        }
        if (\app\model\User::getByUsername($params['username'])->isExists()) {
            return msg('error', '该用户名已存在');
        }
        $model = get_user();
        $model->username = trim($params['username']);
        $model->allowField(['username'])->save();
        return msg('ok', 'success', $model);
    }
}