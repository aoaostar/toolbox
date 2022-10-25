<?php
declare (strict_types=1);

namespace app\model;

use app\lib\Jwt;
use think\facade\Session;
use think\Model;

/**
 * @property \stdClass $oauth oauth信息json
 * @property array $stars 标星
 * @property int $id
 * @property string $avatar 头像
 * @property string $create_time 创建时间
 * @property string $ip ip
 * @property string $update_time 更新时间
 * @property string $username 用户名
 * @mixin \think\Model
 */
class User extends Base
{
    protected $json = ['stars', 'oauth'];
    protected $jsonAssoc = true;

    protected $searchField = ['username'];

    public function getAvatarAttr($value)
    {
        return avatar_cdn($value);
    }

    public static function get($id)
    {
        return User::where('id', $id)->cache(30)->findOrEmpty();
    }

    public static function getByUsername($username)
    {
        return User::where('username', $username)->findOrEmpty();
    }

    public static function isLogin(): bool
    {

        $user = self::getUser();
        return !empty($user->id) && $user->isExists();
    }

    public static function isAdmin($user = null): bool
    {
        $user = $user ?? self::getUser();
        return $user !== null && !empty($user->id) && $user->id === 1 && $user->isExists();
    }

    public static function getUser(): User
    {

        $user = Session::get("user");
        if (empty($user)) {
            $access_token = \think\facade\Request::header('Authorization');
            if (!empty($access_token)) {
                $resp = (new Jwt())->validate_token($access_token);
                $user = (object)$resp['data'];
            }
        }

        if (!empty($user->id)) {
            $user = User::get($user->id);
            if ($user->isExists()) {
                return $user;
            }
        }
        return User::visitor();
    }

    public static function visitor(): User
    {
        return new User([
            'id' => 0,
            'username' => '',
            'stars' => [],
            'avatar' => '',
            'create_time' => '',
            'ip' => '',
            'oauth' => [],
            'update_time' => '',
        ]);
    }

}
