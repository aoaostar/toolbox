<?php
declare (strict_types = 1);

namespace app\model;


/**
 * @property int $id
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

        $model = self::where('id', $id)->with(['plugins'])->findOrEmpty();

        return $model;
    }

    public function plugins()
    {
        return $this->hasMany(Plugin::class);
    }
}
