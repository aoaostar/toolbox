<?php
// +----------------------------------------------------------------------
// | 会话设置
// +----------------------------------------------------------------------

return [
    // session name
    'name'           => 'AOAOSTAR_SESSID',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => env('session.driver', 'file') === 'file'? 'file': 'cache',
    // 存储连接标识 当type使用cache的时候有效
    'store'          => 'session_' . env('session.driver', 'redis'),
    // 过期时间
    'expire'         => env('session.expire', 86400 * 7),
    // 前缀
    'prefix'         => '',
];
