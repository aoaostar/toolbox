<?php
declare (strict_types=1);

namespace app\middleware;

class PluginCheck
{
    public function handle($request, \Closure $next)
    {
        $plugin = plugin_info_get(plugin_alias_get());
        if (!$plugin->isExists()) {
            abort(400, '该插件不存在');
        }
        if ($plugin->enable !== 1) {
            abort(400, '该插件已禁用');
        }
        return $next($request);
    }
}
