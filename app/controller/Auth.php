<?php


namespace app\controller;


use app\model\User;
use think\facade\Request;
use think\facade\Session;

class Auth extends Base
{

    public function login()
    {
        return view();
    }

    public function oauth()
    {
        $url = (string)url('/oauth/callback', [], '', true);
        return redirect('https://github.com/login/oauth/authorize?client_id=' . config_get('oauth.client_id') . '&redirect_uri=' . $url);
    }

    public function callback()
    {

        $code = Request::param("code");
        if (empty($code)) {
            return redirect("/auth/login");
        }
        $url = 'https://github.com/login/oauth/access_token';
        $arr = [
            'client_id' => config_get('oauth.client_id'),
            'client_secret' => config_get('oauth.client_secret'),
            'code' => $code,
        ];

        $res = aoaostar_post($url, $arr, [
            'Accept: application/json',
        ]);
        $json_decode = json_decode($res);
        if (!empty($json_decode) && !empty($json_decode->access_token)) {
            $aoaostar_get = aoaostar_get('https://api.github.com/user', [
                "Authorization: token $json_decode->access_token"
            ]);

            $json_decode = json_decode($aoaostar_get);
            if (!empty($json_decode) && !empty($json_decode->login)) {
                $model = User::where('id', $json_decode->id)->findOrEmpty();
                if ($model->isEmpty()) {
                    $model = new User();
                    $model->id = $json_decode->id;
                    $model->avatar_url = $json_decode->avatar_url;
                    $model->stars = '[]';
                }
                $model->update_time = format_date();
                $model->username = $json_decode->login;
                $model->save();
                Session::set('user', $model);
                return redirect("/");
            }
        }
        return abort(500, '登录超时，请重试');
    }

    public function logout()
    {
        Session::clear();
        return redirect('/');
    }

}