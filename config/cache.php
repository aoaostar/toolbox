<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 默认缓存驱动
    'default' => env('cache.driver', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => 'toolbox',
            // 缓存有效期 0表示永久缓存
            'expire'     => env('cache.expire', 3600),
            // 缓存标签前缀
            'tag_prefix' => 'toolbox:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        'redis' => [
            // 驱动方式
            'type'       => 'Redis',
            'host'       =>  env('cache.redis.host', '127.0.0.1'),
            'port'       =>  env('cache.redis.port', 6379),
            'password'   =>  env('cache.redis.password', ''),
            'select'     =>  env('cache.redis.select', 0),
            // 缓存有效期 0表示永久缓存
            'expire'     =>  env('cache.expire', 3600),
            'prefix'     =>  'toolbox_',
        ],
        'session_redis' => [
            // 驱动方式
            'type'       => 'Redis',
            'host'       =>  env('session.redis.host', '127.0.0.1'),
            'port'       =>  env('session.redis.port', 6379),
            'password'   =>  env('session.redis.password', ''),
            'select'     =>  env('session.redis.select', 1),
            // 缓存有效期 0表示永久缓存
            'expire'     =>  env('session.expire', 86400 * 7),
            'prefix'     =>  'session_',
        ],
        // 更多的缓存连接
    ],
];
