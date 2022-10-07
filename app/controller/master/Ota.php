<?php


namespace app\controller\master;


use app\controller\Base;
use app\lib\ExecSQL;
use app\model\Migration;
use think\facade\Request;

class Ota extends Base
{
    private $RELEASE_API = '';

    public function initialize()
    {
        reset_opcache();
        $this->RELEASE_API = base64_decode('aHR0cHM6Ly90b29sLWNsb3VkLmFvYW9zdGFyLmNvbS9vcGVuL3JlbGVhc2U=');
    }

    private function get_last_release()
    {

        $res = aoaostar_get($this->RELEASE_API, [
            'referer:' . Request::domain(),
        ]);
        $json = json_decode($res);

        if (empty($json) || empty($json->data)) {
            if (!empty($json->message)) {
                throw new \Exception($json->message);
            }
            throw new \Exception('"连接云中心失败，请检查网络连通性是否正常"');
        }
        return $json;
    }

    public function check()
    {
        try {
            $release = $this->get_last_release();
            $release->data->current_version = get_version();

        } catch (\Exception $e) {
            return error($e->getMessage());

        }
        return success($release->data);
    }

    public function update()
    {
        try {
            $release = $this->get_last_release();

        } catch (\Exception $e) {
            return error($e->getMessage());

        }
        $get = aoaostar_get($release->data->download_url);
        if (empty($get) || str_starts_with($get, 'CURL Error:')) {
            return error('下载更新包失败，请检查网络连通性是否正常');
        }
        $tmpFilename = app()->getRuntimePath() . '/tmp/' . uniqid() . '.zip';
        if (!file_exists(dirname($tmpFilename))) {
            mkdir(dirname($tmpFilename), 0755, true);
        }
        try {
            if (!file_put_contents($tmpFilename, $get)) {
                return error('保存文件失败，请检查是否有写入权限');
            }
            $rootPath = app()->getRootPath();
            if (!unzip($tmpFilename, $rootPath)) {
                return error('解压压缩包失败');
            }
            return msg('ok', '资源包解压成功', $release->data);
        } finally {
            @unlink($tmpFilename);
        }
    }

    public function updateDatabase()
    {
        $glob = glob(app()->getRuntimePath() . '/update/sql/*.sql');
        if (empty($glob)) {
            return msg('ok', '未发现数据库更新文件');
        }
        foreach ($glob as $value) {
            $result[$value] = [];
            $basename = basename($value);
            $migration = Migration::where('filename', $basename)
                ->findOrEmpty();
            if ($migration->isExists()) {
                $result[$value][] = '该文件已经执行过了，已跳过';
                goto end;
            }
            $lines = file($value);
            $execSQL = new ExecSQL();
            $lines = $execSQL->purify($lines);
            $number = $execSQL->exec($lines);
            $result[$value] = array_merge($result[$value], $execSQL->getErrors());

            if ($number > 0) {
                $result[$value][] = "影响的记录数 $number";
            }
            Migration::create([
                'filename' => $basename,
            ]);
            $result[$value][] = '创建数据库升级记录成功';
            end:
            unlink($value);
            $result[$value] = "[$basename]：" . implode("\n", $result[$value]);
        }
        return msg('ok', "数据库执行结果：\n" . implode("\n", $result));
    }

    public function updateScript()
    {
        $glob = glob(app()->getRuntimePath() . '/update/script/*.php');
        if (empty($glob)) {
            return msg('ok', '未发现更新脚本');
        }
        foreach ($glob as $value) {
            $result[$value] = [];
            $basename = basename($value);
            $migration = Migration::where('filename', $basename)
                ->findOrEmpty();
            if ($migration->isExists()) {
                $result[$value][] = '该文件已经执行过了，已跳过';
                goto end;
            }
            try {
                require $value;
                $class = 'UpdateScript';
                if (!class_exists($class)) {
                    throw new \Exception("更新脚本不存在[$class]类");
                }
                $instance = new $class();
                $boot = 'main';
                if (!method_exists($instance, $boot)) {
                    throw new \Exception("更新脚本不存在[$boot]方法");
                }
                $instance->main();
                $result[$value] = array_merge($result[$value], $instance->getResult());
            } catch (\Exception $e) {
                $result[$value][] = $e->getMessage();
            }
            Migration::create([
                'filename' => $basename,
            ]);
            $result[$value][] = '创建更新脚本升级记录成功';
            end:
            unlink($value);
            $result[$value] = "[$basename]：" . implode("\n", $result[$value]);
        }
        return msg('ok', "更新脚本执行结果：\n" . implode("\n", $result));
    }
}