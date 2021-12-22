<?php
declare (strict_types=1);

namespace app\middleware;


class Auth
{
    public function handle($request, \Closure $next)
    {
        if (!is_login()) {
            if ($request->isAjax() || !$request->isGet()) {
                return msg('error','请登录');
            }
            return redirect((string)url('/auth/login'));
        }
        return $next($request);
    }
}
