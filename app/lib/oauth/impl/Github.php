<?php


namespace app\lib\oauth\impl;

use app\lib\oauth\Oauth;
use think\Response;

class Github implements Oauth
{
    private $config;
    private $params;
    private $redirect_uri;

    public function __construct($config, $params)
    {
        $this->config = $config;
        $this->params = $params;
        $this->redirect_uri = (string)url('/oauth/callback/github', [], '', true);;
    }

    public function oauth(): Response
    {
        return redirect('https://github.com/login/oauth/authorize?client_id=' . $this->config->client_id . '&redirect_uri=' . $this->redirect_uri);
    }

    public function callback(): array
    {

        if (empty($this->params->code)) {
            return [];
        }
        $url = 'https://github.com/login/oauth/access_token';
        $arr = [
            'client_id' => $this->config->client_id,
            'client_secret' => $this->config->client_secret,
            'code' => $this->params->code,
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