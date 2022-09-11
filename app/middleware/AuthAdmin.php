<?php
declare (strict_types=1);

namespace app\middleware;


use app\lib\Jwt;
use think\facade\Request;
use think\facade\Session;

class AuthAdmin
{
    public function handle($request, \Closure $next)
    {
        if (!is_admin()) {
            if ($request->isAjax() || !$request->isGet()) {
                $token = Request::header('Authorization','');
                if (empty($token)){
                    return msg('error', '请登录')->code(401);
                }
                $validate_token = (new Jwt())->validate_token($token);
                if ($validate_token['status']) {
                    if (!is_admin($validate_token['data']['username'])) {
                        return msg('error', '没有管理权限')->code(401);
                    }
                    return $next($request);
                }
                return msg('error', $validate_token['message'])->code(401);
            }
            return redirect((string)url('/auth/login'));
        }
        return $next($request);
    }
}
