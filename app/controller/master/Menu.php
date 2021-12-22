<?php

namespace app\controller\master;


use app\BaseController;

class Menu extends BaseController
{
    public function get(){

        $homeInfo = [
            'title' => '主页',
            'href' => 'page/dashboard.html',
        ];
        $logoInfo = [
            'title' => 'AOAOSTAR',
            'image' => 'images/logo.png',
            'href' => './',
        ];
        $menuInfo = [
            [
                "title" => "常规管理",
                "icon" => "fa fa-address-book",
                "href" => "",
                "target" => "_self",
                "child" => [
                    [
                        "title" => "主页",
                        "icon" => "fa fa-home",
                        "target" => "_self",
                        "href" => "page/dashboard.html",
                    ],
                    [
                        "title" => "分类管理",
                        "href" => "page/category.html",
                        "icon" => "fa fa-bookmark-o",
                        "target" => "_self",
                    ],
                    [
                        "title" => "用户管理",
                        "href" => "page/user.html",
                        "icon" => "fa fa-user",
                        "target" => "_self",
                    ],
                    [
                        "title" => "插件管理",
                        "href" => "",
                        "icon" => "fa fa-puzzle-piece",
                        "target" => "_self",
                        "child" => [
                            [
                                "title" => "插件列表",
                                "href" => "page/plugin.html",
                                "icon" => "fa fa-list",
                                "target" => "_self"
                            ],
                            [
                                "title" => "插件中心",
                                "href" => "page/store.html",
                                "icon" => "fa fa-shopping-bag",
                                "target" => "_self"
                            ],
                            [
                                "title" => "安装新插件",
                                "href" => "page/plugin/install.html",
                                "icon" => "fa fa-cloud-upload",
                                "target" => "_self"
                            ],
                        ]
                    ],
                    [
                        "title" => "系统配置",
                        "href" => "page/system.html",
                        "icon" => "fa fa-cog",
                        "target" => "_self",
                    ],
                    [
                        "title" => "在线升级",
                        "href" => "page/ota.html",
                        "icon" => "fa fa-cloud-upload",
                        "target" => "_self",
                    ],
                ]
            ],
        ];
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        return json($systemInit);
    }
}
