<?php
declare (strict_types=1);

namespace app\service;

use think\facade\Validate;

class ValidateService extends \think\Service
{
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
        //
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        //
        Validate::maker(function ($validate) {
            $validate->extend('is_json', function ($value) {
                if (!is_string($value)) {
                    $value = json_encode($value);
                }
                json_decode($value);
                return json_last_error() == JSON_ERROR_NONE;
            });
            $validate->extend('is_legal_plugin_class', function ($value) {
                if (!is_string($value)){
                    return false;
                }
                $arr = explode('\\', $value);
                return count($arr) === 2;
            });
        });
    }
}
