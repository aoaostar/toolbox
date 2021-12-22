<?php
declare (strict_types=1);

namespace app\model;

use think\Model;


/**
 * Class app\model\Plugin
 *
 * @property int $category_id 分类
 * @property int $id
 * @property int $request_count 接口请求次数
 * @property string $alias 插件名
 * @property string $class 插件类
 * @property string $config 配置信息
 * @property string $create_time 安装时间
 * @property string $desc 插件描述
 * @property string $logo 插件logo
 * @property string $title 插件标题
 * @property string $update_time 更新时间
 * @property string $version 版本
 * @property-read \app\model\Category $category
 */
class Plugin extends Base
{
    protected $json = ['config'];

    protected $searchField = ['author', 'class', 'tag', 'title',];

    public static function getByAlias($alias = '')
    {
        if (empty($alias)) {
            $alias = plugin_alias_get();
        }
        $model = self::where('alias', $alias)->with(['category'])->findOrEmpty();

        return $model;
    }

    public static function getByClass($class)
    {
        $model = self::where('class', $class)->with(['category'])->findOrEmpty();

        return $model;
    }

    public static function get($id)
    {

        $model = self::where('id', $id)->with(['category'])->findOrEmpty();

        return $model;
    }

    public static function all($param)
    {
        $where = [];
        if (!empty($param['categoryId'])) {
            $where[] = ['category_id', '=', $param['categoryId']];
        }

        if (!empty($param['enable'])) {
            $where[] = ['enable', '=', 1];
        }

        $selects = (new Plugin)->pagination($param, $where);
        $selects['items']->load(['category']);

        if (!empty($param['star']) && $param['star'] == 1) {
            $user = get_user();
            $stars = $user->stars;
        }
        if (isset($stars)) {
            foreach ($selects['items'] as $k => $v) {
                if (!in_array($v->alias, $stars)) {
                    unset($selects['items'][$k]);
                }
            }
        }
        return $selects;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
