<?php

namespace app\controller;


use app\lib\EnvOperation;
use app\lib\ExecSQL;
use PDO;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Validate;
use think\facade\View;

class Install extends Base
{

    public function __destruct()
    {
        Cache::clear();
        reset_opcache();
    }
    public function initialize()
    {
        // 检测是否已安装
        if (file_exists(app()->getRootPath() . 'install.lock')) {
            exit('你已安装成功，需要重新安装请删除 install.lock 文件');
        }
        Cache::clear();
        reset_opcache();
    }

    public function index()
    {
        // 检查安装环境
        $requirements = [
            'php_version' => PHP_VERSION >= 7.1,
            'pdo_mysql' => extension_loaded("pdo_mysql"),
//            'zend_opcache' => extension_loaded("Zend OPcache"),
            'curl' => extension_loaded("curl"),
            'fileinfo' => extension_loaded("fileinfo"),
            'ziparchive' => class_exists("ZipArchive"),
            'is_writable' => is_writable(app()->getRuntimePath()) && is_writable(app()->getRootPath() . 'public'),
        ];
        reset_opcache();
        $step = Request::param('step');
        View::assign([
            'step' => $step,
            'requirements' => $requirements,
        ]);
        return view('../view/install/index.html');
    }

    public function database()
    {
        $params = Request::param();
        $rules = [
            'database' => 'require',
            'hostname' => 'require',
            'username' => 'require',
            'password' => 'require',
            'hostport' => 'require|integer',
        ];
        $validate = Validate::rule($rules);
        if (!$validate->check($params)) {
            return msg('error', $validate->getError());
        }

        $dsn = 'mysql:host=' . $params['hostname'] . ';dbname=' . $params['database'] . ';port=' . $params['hostport'] . ';charset=utf8';
        try {
            new PDO($dsn, $params['username'], $params['password']);
        } catch (\Exception $e) {
            return msg('error', $e->getMessage());
        }
        try {
            $envFile = file_get_contents(app()->getRootPath() . '.example.env');
            $envOperation = new EnvOperation($envFile);
            foreach (array_keys($rules) as $value) {
                $envOperation->set(mb_strtoupper($value), $params[$value]);
            }
            $envOperation->save();
        } catch (\Exception $e) {
            return msg('error', $e->getMessage());
        }
        return msg();
    }

    public function init_data()
    {
        try {

            $filename = app()->getRootPath() . 'install.sql';
            if (!is_file($filename)) {
                throw new Exception('数据库 install.sql 文件不存在');
            }
            $install_sql = file($filename);
            //写入数据库
            $execSQL = new ExecSQL();
            $install_sql = $execSQL->purify($install_sql);
            foreach ($install_sql as $sql) {
                $execSQL->exec($sql);
                if (!empty($execSQL->getErrors())) {
                    throw new Exception($execSQL->getErrors()[0]);
                }
            }
        } catch (\Exception $e) {
            return msg('error', $e->getMessage());
        }
        return msg();
    }

    public function oauth()
    {
        $params = Request::param();
        $rules = [
            'auth_path' => 'require|alphaDash',
            'client_id' => 'require|alphaNum',
            'client_secret' => 'require|alphaNum',
            'username' => 'require',
        ];
        $validate = Validate::rule($rules);
        if (!$validate->check($params)) {
            return msg('error', $validate->getError());
        }
        foreach (array_keys($rules) as $v) {
            if (!config_set("oauth.$v", $params[$v])) {
                return msg('error', "[$v]保存失败");
            }
        }
        file_put_contents(app()->getRootPath() . 'install.lock', format_date());
        return msg();
    }

}
