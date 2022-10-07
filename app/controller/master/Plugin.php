<?php

namespace app\controller\master;


use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\Validate;

class Plugin extends BaseController
{
    public function all()
    {

        $params = Request::param();

        $validate = Validate::rule([
            'page' => 'integer',
            'limit' => 'integer',
            'categoryId' => 'integer',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }
        $plugins = (new \app\model\Plugin)->pagination($params);

        $plugins['items']->load(['category']);

        return success($plugins);
    }

    public function get()
    {

        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require|integer',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }
        $plugin = \app\model\Plugin::get($params['id']);
        return success($plugin);
    }

    public function update()
    {

        $params = Request::param();

        $validate = Validate::rule([
            'id|ID' => 'require',
//            'title|插件标题' => 'chsDash',
            'alias|路由别名' => 'unique:plugin',
            'config|插件配置' => 'is_json',
            'class|插件类名' => 'unique:plugin|is_legal_plugin_class',
            'category_id|分类' => 'integer',
            'enable' => 'integer',
            'weight|权重' => 'integer',
            'permission|权限' => 'alphaDash',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }

        $plugin = \app\model\Plugin::get($params['id']);

        $plugin->allowField([
            'category_id',
            'class',
            'config',
            'desc',
//            'logo',
            'alias',
            'version',
            'title',
            'enable',
            'weight',
            'template',
            'permission',
        ])->data($params)->save();
        return success($plugin);
    }

    public function create()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'title|插件标题' => 'require',
            'alias|路由别名' => 'require|unique:plugin',
            'desc|插件描述' => 'require',
            'config|插件配置' => 'is_json',
            'class|插件类名' => 'require|unique:plugin|is_legal_plugin_class',
            'version|插件版本号' => 'require',
            'category_id|分类' => 'require|integer',
            'enable' => 'integer',
            'weight|权重' => 'integer',
            'permission|权限' => 'alphaDash',
        ]);
        if (!$validate->check($params)) {

            return error($validate->getError());
        }
        $plugin = new \app\model\Plugin();

        $plugin->allowField([
            'category_id',
            'class',
            'config',
            'desc',
            'alias',
            'version',
            'title',
            'enable',
            'weight',
            'template',
            'permission',
        ])->data($params)->save();
        return success($plugin);
    }

    public function delete()
    {
        $params = Request::param();

        $validate = Validate::rule([
            'id' => 'require|integer',
        ]);
        if (!$validate->check($params)) {
            return error($validate->getError());
        }
        $plugin = \app\model\Plugin::get($params['id']);
        if ($plugin->isEmpty()) {
            return error('该插件不存在');
        }
        Db::startTrans();
        try {
            $classPath = plugin_path_get($plugin->class) . "/Install.php";
            if (file_exists($classPath)) {
                require $classPath;
                $class = "\\plugin\\$plugin->class\\Install";
                if (class_exists($class)) {
                    $uninstall = new $class();
                    $uninstall->UnInstall($plugin);
                }
            }
            Db::name('plugin')->delete($params['id']);
            if (!empty($plugin->class)) {
                del_tree(plugin_path_get($plugin->class));
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return error($e->getMessage(), $e);
        }
        return success();
    }

    public function upload()
    {
        $params = Request::file();

        $validate = Validate::rule([
            'file' => 'require|file',
        ]);
        if (!$validate->check($params)) {
            return error($validate->getError());
        }
        $uploadedFile = Request::file('file');
        $plugin = new \app\lib\Plugin();
        $zipFilepath = $plugin->getZipFilepath();
        $uploadedFile->move(dirname($zipFilepath), basename($zipFilepath));
        return $plugin->install();
    }

    public function update_logo()
    {

        $params = Request::param();

        $params['logo'] = Request::file('logo');

        $validate = Validate::rule([
            'id' => 'requireWithout:class|integer',
            'class|插件类名' => 'requireWithout:id|unique:plugin|is_legal_plugin_class',
            'logo' => 'require|fileExt:png,jpg|fileSize:5242880|fileMime:image/png,image/jpeg',
        ]);

        if (!$validate->check($params)) {

            return error($validate->getError());
        }


        if (!empty($params['id'])) {
            $plugin = \app\model\Plugin::get($params['id']);
            $params['class'] = $plugin->class;
        }

        $pluginPath = plugin_path_get($params['class']);
        if (!is_dir($pluginPath)) {
            mkdir($pluginPath, 0755, true);
        }
        $params['logo']->move($pluginPath, 'logo.png');

        return success();
    }
}
