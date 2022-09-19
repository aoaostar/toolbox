<?php
declare (strict_types=1);

namespace app\model;


/**
 * @property int $id
 * @property int $weight 权重
 * @property string $create_time 安装时间
 * @property string $title 标题
 * @property string $update_time 更新时间
 * @property-read \app\model\Plugin[] $plugins
 * @mixin \think\Model
 */
class Category extends Base
{

    protected $searchField = ['title',];

    public static function get($id)
    {
        return self::where('id', $id)->with(['plugins'])->findOrEmpty();
    }

    public static function all($params = [])
    {
        $where = [];
        foreach (['name', 'title'] as $v) {
            if (!empty($params[$v])) {
                $where[] = [$v, 'like', '%' . $params[$v] . '%'];
            }
        }
        return self::where($where)->order('weight', 'desc')->select();
    }

    public function plugins()
    {
        return $this->hasMany(Plugin::class);
    }
}
