<?php
declare (strict_types=1);

namespace app\middleware;


use app\model\Category;

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
        $categories = Category::select();

        \think\facade\View::assign([
            "app" => [
                "title" => config_get('global.title'),
                "subTitle" => config_get("global.subtitle")
            ],
            'categories' => $categories,
            'user' => get_user(),
        ]);
        \think\facade\View::config(['view_path' => template_path_get()]);
        return $next($request);
    }
}
