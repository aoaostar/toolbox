<?php


namespace app\controller\api;


use app\controller\Base;
use think\facade\Validate;

class User extends Base
{

    public function get()
    {
        return success(get_user());
    }

    public function update()
    {
        $validate = Validate::rule([
            'username|用户名' => 'require|max:26|graph',
        ]);
        $params = request()->only(['username']);
        if (!$validate->check($params)) {
            return error($validate->getError());
        }
        if (\app\model\User::getByUsername($params['username'])->isExists()) {
            return error('该用户名已存在');
        }
        $model = get_user();
        $model->username = trim($params['username']);
        $model->allowField(['username'])->save();
        return success($model);
    }
}