<?php


namespace oauth\impl;

use GuzzleHttp\Exception\GuzzleException;
use oauth\Oauth;
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
            return [
                'error' => '回调code异常',
            ];
        }
        $url = 'https://gitee.com/oauth/token?grant_type=authorization_code';
        $arr = [
            'client_id' => $this->config->client_id,
            'client_secret' => $this->config->client_secret,
            'code' => $this->params->code,
            'redirect_uri' => $this->redirect_uri,
        ];

        try {
            $resp = aoaostar_post($url, $arr);
            if (!empty($resp) && !empty($resp->access_token)) {
                $json_decode = aoaostar_get('https://gitee.com/api/v5/user', [
                    'Authorization' => "token $resp->access_token"
                ]);

                if (!empty($json_decode) && !empty($json_decode->id)) {
                    return [
                        'id' => $json_decode->id,
                        'username' => $json_decode->login,
                        'avatar' => $json_decode->avatar_url,
                    ];
                }
            }
            if (!empty($json_decode->error_description)) {
                return [
                    'error' => $json_decode->error_description,
                ];
            }
            if (!empty($json_decode->error)) {
                return [
                    'error' => $json_decode->error,
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