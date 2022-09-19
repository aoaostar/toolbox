<?php
declare (strict_types=1);

namespace app\middleware;


use app\model\Category;
use think\facade\Cache;

class View
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $categories = Cache::remember(__METHOD__ . '__category', function () {
            return Category::all();
        });
        $get_user = get_user();
        $hidden = ['ip', 'update_time', 'oauth'];
        foreach ($hidden as $k) {
            if (in_array($k, $hidden)) {
                unset($get_user[$k]);
            }
        }
        \think\facade\View::assign([
            "app" => config_get('global.'),
            'categories' => $categories,
            'user' => $get_user,
        ]);
        \think\facade\View::config(['view_path' => template_path_get()]);
        return $response;
    }
}
