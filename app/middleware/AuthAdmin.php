<?php
declare (strict_types=1);

namespace app\middleware;


class AuthAdmin
{
    public function handle($request, \Closure $next)
    {
        if (!is_admin()) {
            if ($request->isAjax() || !$request->isGet()) {
                return msg('error', '请登录')->code(401);
            }
            return redirect((string)url('/auth/login'));
        }
        return $next($request);
    }
}
