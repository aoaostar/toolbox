<?php
declare (strict_types = 1);

namespace app\model;


/**
 * Class app\model\Request
 *
 * @property int $id
 * @property int $plugin_id 分类
 * @property int $request_count 接口请求次数
 * @property string $create_time 安装时间
 * @property string $update_time 更新时间
 * @property-read \app\model\Plugin $plugin
 */
class Request extends Base
{

    public static function get($id)
    {

        $model = self::where('id', $id)->findOrEmpty();

        return $model;
    }
    public function plugin()
    {
        return $this->belongsTo(Plugin::class);
    }
}
