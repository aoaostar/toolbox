<?php
declare (strict_types=1);

namespace app\model;

use think\facade\Cache;
use think\Model;


/**
 * Class app\model\Plugin
 *
 * @property int $category_id 分类
 * @property int $enable 是否启用
 * @property int $id
 * @property int $request_count 接口请求次数
 * @property int $weight 权重
 * @property string $alias 插件名
 * @property string $class 插件类
 * @property string $config 配置信息
 * @property string $create_time 安装时间
 * @property string $desc 插件描述
 * @property string $logo 插件logo
 * @property string $template
 * @property string $title 插件标题
 * @property string $update_time 更新时间
 * @property string $version 版本
 * @property-read \app\model\Category $category
 */
class Plugin extends Base
{
    protected $json = ['config'];

    protected $searchField = ['author', 'class', 'tag', 'title', 'alias'];

    public static function onAfterRead(Model $model)
    {
        $model->logo = "/$model->alias/logo.png";
    }

    public static function getByAlias($alias = '')
    {
        if (empty($alias)) {
            $alias = plugin_alias_get();
        }
        return Cache::remember(__METHOD__ . '__' . $alias, function () use ($alias) {
            return self::where('alias', $alias)->with(['category'])->findOrEmpty();
        });
    }

    public static function getByClass($class)
    {
        return Cache::remember(__METHOD__ . '__' . $class, function () use ($class) {
            return self::where('class', $class)->with(['category'])->findOrEmpty();
        });
    }

    public static function get($id)
    {
        return self::where('id', $id)->with(['category'])->withCache(true)->findOrEmpty();
    }

    public static function all($param)
    {
        $where = [];
        if (!empty($param['category_id'])) {
            $where[] = ['category_id', '=', $param['category_id']];
        }

        if (!empty($param['enable'])) {
            $where[] = ['enable', '=', 1];
        }

        $selects = (new Plugin)->pagination($param, $where);
        $selects['items']->hidden(['config', 'class'])->load(['category']);

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
