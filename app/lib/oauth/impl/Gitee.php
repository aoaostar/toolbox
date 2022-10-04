<?php


namespace app\lib\oauth\impl;

use app\lib\oauth\Oauth;
use think\Response;

class Gitee implements Oauth
{
    private $config;
    private $params;
    private $redirect_uri;

    public function __construct($config, $params)
    {
        $this->config = $config;
        $this->params = $params;
        $this->redirect_uri = (string)url('/oauth/callback/gitee', [], '', true);;
    }

    public function oauth(): Response
    {
        return redirect('https://gitee.com/oauth/authorize?client_id=' . $this->config->client_id . '&redirect_uri=' . $this->redirect_uri . '&response_type=code');
    }

    public function callback(): array
    {
        if (empty($this->params->code)) {
            return [];
        }
        $url = 'https://gitee.com/oauth/token?grant_type=authorization_code';
        $arr = [
            'client_id' => $this->config->client_id,
            'client_secret' => $this->config->client_secret,
            'code' => $this->params->code,
            'redirect_uri' => $this->redirect_uri,
        ];

        $res = aoaostar_post($url, $arr, [
            'Accept: application/json',
        ]);
        $json_decode = json_decode($res);

        if (!empty($json_decode) && !empty($json_decode->access_token)) {
            $aoaostar_get = aoaostar_get('https://gitee.com/api/v5/user', [
                "Authorization: token $json_decode->access_token"
            ]);

            $json_decode = json_decode($aoaostar_get);
            if (!empty($json_decode) && !empty($json_decode->id)) {
                return [
                    'id' => $json_decode->id,
                    'username' => $json_decode->login,
                    'avatar' => $json_decode->avatar_url,
                ];
            }
        }
        return [];
    }

}