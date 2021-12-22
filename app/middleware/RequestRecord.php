<?php
declare (strict_types=1);

namespace app\middleware;

use app\model\Plugin;
use app\model\Request;

class RequestRecord
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
        $data = $response->getData();
        if (!empty($data['status']) && $data['status'] === 'ok') {
            $alias = plugin_alias_get();
            $plugin = Plugin::where('alias', $alias)->field('id,request_count')->findOrEmpty();
            if ($plugin->isExists()) {
                $model = Request::whereDay('create_time')->where('plugin_id', $plugin->id)->findOrEmpty();
                if (!$model->isExists()) {
                    $model = new Request;
                    $model->plugin_id = $plugin->id;
                }
                $model->request_count++;
                $plugin->request_count++;
                $model->save();
                $plugin->save();
            }
        }

        return $response;
    }
}
