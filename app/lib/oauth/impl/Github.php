<?php


namespace app\lib\oauth\impl;

use app\lib\oauth\Oauth;
use GuzzleHttp\Exception\GuzzleException;
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
            return [
                'error' => '回调code异常',
            ];
        }
        $url = 'https://github.com/login/oauth/access_token';
        $arr = [
            'client_id' => $this->config->client_id,
            'client_secret' => $this->config->client_secret,
            'code' => $this->params->code,
        ];

        try {
            $resp = aoaostar_post($url, $arr);
            if (!empty($resp) && !empty($resp->access_token)) {
                $resp = aoaostar_get('https://api.github.com/user', [
                    'Authorization' => "token $resp->access_token"
                ]);

                if (!empty($resp) && !empty($resp->id)) {
                    return [
                        'id' => $resp->id,
                        'username' => $resp->login,
                        'avatar' => $resp->avatar_url,
                    ];
                }
            }
            if (!empty($resp->error_description)) {
                return [
                    'error' => $resp->error_description,
                ];
            }
            if (!empty($resp->error)) {
                return [
                    'error' => $resp->error,
                ];
            }
            return [
                'error' => '未知异常',
            ];
        } catch (GuzzleException $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

}