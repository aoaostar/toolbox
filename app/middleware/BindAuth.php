<?php
declare (strict_types=1);

namespace app\middleware;


use think\facade\Session;

class BindAuth
{
    public function handle($request, \Closure $next)
    {

        $bind = Session::get('BindAuth');
        if ($bind === true && !is_login()) {
            return redirect((string)url('/auth/login'));
        }
        return $next($request);
    }
}
