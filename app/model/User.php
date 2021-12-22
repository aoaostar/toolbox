<?php
declare (strict_types=1);

namespace app\model;

use think\Model;

/**
 * @property array $stars 标星
 * @property int $id
 * @property string $avatar_url 头像
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $username 用户名
 * @mixin \think\Model
 */
class User extends Model
{
    protected $json = ['stars'];
    protected $jsonAssoc = true;

    public static function pagination($param)
    {
        $where = [];
        if (!empty($param['username'])) {
            $where[] = ["title", 'like', '%' . $param['title'] . '%'];
        }
        if (!empty($param['id'])) {
            $where[] = ["id", 'like', '%' . $param['id'] . '%'];
        }
        $page = 1;
        $limit = 10;
        if (!empty($param['page'])) {
            $page = intval($param['page']);
        }
        if (!empty($param['limit'])) {
            $limit = intval($param['limit']);
        }

        $plugin = new User();
        $total = $plugin->count();
        $select = $plugin->page($page)->limit($limit)->where($where)->select();

        $data = [
            'total' => $total,
            'items' => $select,
        ];
        return $data;
    }

    public static function get($id)
    {

        $model = User::where('id', $id)->findOrEmpty();

        return $model;
    }

}
