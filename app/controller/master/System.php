<?php

namespace app\controller\master;


use app\BaseController;
use app\model\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\Validate;

class System extends BaseController
{
    public function all()
    {
        $all = Config::all();
        return msg('ok', 'success', $all);
    }

    public function get()
    {
        $key = Request::param('key');
        $all = config_get($key);
        return msg('ok', 'success', $all);
    }

    public function update()
    {

        $params = Request::param();

        foreach ($params as $v) {
            if (empty($v['key'])) {
                continue;
            }
            $model = \app\model\Config::getByKey($v['key']);
            $model->data([
                'key' => $v['key'],
                'value' => $v['value'],
            ])->save();
        }
        $all = Config::all();
        return msg('ok', 'success', $all);
    }

    public function info()
    {

        $tmp = 'version()';
        $mysqlVersion = Db::query("select version()")[0][$tmp];
        $data = [
            'app_name' => base64_decode('5YKy5pif5bel5YW3566x'),
            'author' => base64_decode('UGx1dG8='),
            'version' => get_version(),
            'framework_version' => app()::VERSION,
            'php_version' => PHP_VERSION,
            'mysql_version' => $mysqlVersion,
            'os' => php_uname(),
            'host' => GetHostByName(env('SERVER_NAME')),
            'date' => date("Y-m-d H:i:s"),
        ];
        return msg('ok', 'success', $data);

    }

    public function templates()
    {
        $glob = glob(app()->getRootPath() . config("view.view_dir_name") . '/index/*');
        $arr = [];
        foreach ($glob as $v) {
            if (is_dir($v)) {
                array_push($arr, basename($v));
            }
        }
        return msg('ok', 'success', $arr);

    }
    public function plugin_templates()
    {
        $glob = glob(template_path_get() . '/template/*');
        $arr = [];
        foreach ($glob as $v) {
            if (is_file($v)) {
                array_push($arr, basename($v,'.html'));
            }
        }
        return msg('ok', 'success', $arr);

    }
}
