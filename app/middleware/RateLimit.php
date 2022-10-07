<?php
declare (strict_types=1);

namespace app\middleware;


use plugin\CheckCaptcha;
use think\facade\Validate;

class RateLimit
{
    public function handle($request, \Closure $next)
    {

        $class = '\\plugin\\' . plugin_class_get(plugin_alias_get()) . '\\App';

        if (new $class() instanceof CheckCaptcha && $class::CHECK_CAPTCHA) {
            try {
                Validate::failException(true)->check([
                    'captcha' => request()->param('captcha')
                ], [
                    'captcha|验证码' => 'require|captcha'
                ]);
            } catch (\Exception $e) {
                return error($e->getMessage());
            }
        }
        return $next($request);
    }
}
