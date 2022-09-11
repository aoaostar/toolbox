<?php


namespace app\lib;


use Firebase\JWT\Key;

class Jwt
{

    private $key;
    private $algorithm;
    private $expire;

    public function __construct()
    {
        $this->key = config_get('global.secret_key', request()->domain());
        $this->algorithm = 'HS256';
        $this->expire = time() + 86400 * 7;
    }


    public function generate_token($key, $data)
    {
        $payload = [
            'iss' => 'aoaostar',
            'aud' => $key,
            'iat' => time(),
            'nbf' => time(),
            "exp" => $this->expire,
            "data" => $data,
        ];
        return \Firebase\JWT\JWT::encode($payload, $this->key, $this->algorithm);
    }

    public function validate_token($token)
    {

        $res = [
            'status' => false,
            'message' => '未知错误',
            'data' => [],
        ];
        try {
            \Firebase\JWT\JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = \Firebase\JWT\JWT::decode($token, new Key($this->key, $this->algorithm));
            $arr = (array)$decoded;
            $res['status'] = true;
            $res['message'] = "success";
            $res['data'] = (array)$arr['data'];

        } catch (\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            $res['message'] = "签名不正确";
        } catch (\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            $res['message'] = "token失效";
        } catch (\Firebase\JWT\ExpiredException $e) { // token过期
            $res['message'] = "token过期";
        } catch (\Exception $e) { //其他错误
            $res['message'] = "未知错误";
        }
        return $res;
    }

    /**
     * @return float|int
     */
    public function getExpire()
    {
        return $this->expire;
    }
}