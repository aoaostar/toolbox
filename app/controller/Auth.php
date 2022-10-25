<?php


namespace app\controller;


use app\model\User;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;
use think\helper\Str;

class Auth extends Base
{
    private $instance;
    private $mode;
    private $user;

    protected function initialize()
    {
        if (in_array(Request::action(), ['oauth', 'callback'])) {
            $this->mode = mb_strtolower(Request::param('mode', 'github'));
            if (!in_array($this->mode, get_enabled_oauth_mode())) {
                abort(400, '不支持该认证方式 ' . Str::studly($this->mode));
            }
            $class = '\\app\\lib\\oauth\\impl\\' . Str::studly($this->mode);
            if (!class_exists($class)) {
                abort(400, '不支持该认证方式 ' . Str::studly($this->mode));
            }
            $this->user = get_user();
            $this->instance = new $class((object)config_get("oauth.$this->mode."), (object)Request::param());
        }
    }


    public function oauth()
    {
        //绑定账号
        if (Request::param('action') === 'bind') {
            $this->user = get_user();
            if (!empty($this->user->oauth[$this->mode])) {
                return $this->callback_view('error', "已绑定，请勿重复绑定");
            }
            Session::set('BindAuth', true);
        }
        return $this->instance->oauth();
    }

    public function callback(): \think\response\View
    {
        $bind = Session::pull('BindAuth');

        $user = (object)$this->instance->callback();

        if (!empty($user) && !empty($user->id)) {
            if ($bind === true) {
                $model = User::json(['oauth'])
                    ->where("oauth->$this->mode", $user->id)
                    ->setFieldType(["oauth->$this->mode" => 'int'])
                    ->findOrEmpty();
                if (!$model->isExists()) {
                    $this->user->oauth = array_merge((array)$this->user->oauth, [
                        $this->mode => $user->id,
                    ]);
                    $this->user->save();
                    return $this->callback_view('ok', "绑定成功");
                } else {
                    return $this->callback_view('error', "绑定失败，该账号已绑定，请联系管理员进行解绑后方可再次绑定");
                }
            }
            $model = User::json(['oauth'])
                ->where("oauth->$this->mode", $user->id)
                ->setFieldType(["oauth->$this->mode" => 'int'])
                ->findOrEmpty();
            if ($model->isEmpty()) {
                $model = new User();
                $model->oauth = array_merge((array)$model->oauth, [
                    $this->mode => $user->id,
                ]);
                $model->avatar = $user->avatar;
                $model->ip = client_ip();
                $model->stars = [];
                if (User::getByUsername($user->username)->isExists()) {
                    $user->username .= '_' . uniqid();
                }
                $model->username = $user->username;
            }
            $model->update_time = format_date();
            $model->save();
            $instance = (new \app\lib\Jwt());
            $jwt = $instance->generate_token($model->id, $model);
            Session::set('user', $model);
            return $this->callback_view('ok', "登录成功", [
                'access_token' => $jwt,
                'expire' => $instance->getExpire(),
            ]);
        }
        return $this->callback_view('error', "登录失败，请重试");
    }

    private function callback_view($status, $message, $data = [])
    {

        View::assign('data', [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
        return view('auth/callback');
    }

    public function login()
    {
        if (is_login()) {
            return redirect('/user');
        }
        return view();
    }

    public function logout()
    {
        Session::clear();
        return redirect('/');
    }

}