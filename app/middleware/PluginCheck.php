<?php
declare (strict_types=1);

namespace app\middleware;

use think\helper\Str;

class PluginCheck
{
    public function handle($request, \Closure $next, $api)
    {
        $plugin = plugin_info_get(plugin_alias_get());
        if (!$plugin->isExists()) {
            abort(400, '该插件不存在');
        }
        if ($plugin->enable !== 1) {
            abort(400, '该插件已禁用');
        }

        if (!empty($plugin->permission) && !is_admin()) {
            $permission = Str::studly(strtolower($plugin->permission));
            $class = "\\permission\\impl\\{$permission}";
            if (!class_exists($class)) {
                abort(400, "该 permission[{$plugin->permission}] 未实现，请实现后重试");
            }
            $check = $class::check($plugin);

            if ($check === false || ($check instanceof \think\response\View && $api)) {
                abort(403, "无权访问，请授权后重试");
            }

            if ($check instanceof \think\Response) {
                if ($check instanceof \think\response\View) {
                    //执行后续中间件
                    $next($request);
                }
                return $check;
            }
            if (is_string($check)) {
                abort(400, $check);
            }
        }

        return $next($request);
    }
}
