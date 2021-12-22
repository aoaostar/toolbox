<?php


namespace app\controller\master;


use app\controller\Base;
use app\model\Request;

class Analysis extends Base
{
    public function plugin_total_request_count()
    {
        $arr = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date("Y-m-d", strtotime("-$i day"));
            $arr[$date] = Request::whereDay('create_time', $date)->cache()->sum('request_count');
        }
        return msg('ok', 'success', $arr);

    }

    public function plugin_max_request_count()
    {
        $arr = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date("Y-m-d", strtotime("-$i day"));
            $arr[$date] = Request::whereDay('create_time', $date)->cache()->max('request_count');
        }
        return msg('ok', 'success', $arr);

    }

    public function plugin_request_count()
    {
        $date = \think\facade\Request::param('date', date("Y-m-d"));
        $collections = Request::whereDay('create_time', $date)->cache()->with(['plugin'])->select();

        foreach ($collections as $key => &$v) {
            if (!empty($v->plugin)) {
                $v->date = $date;
            }else{
                unset($collections[$key]);
            }
        }
        return msg('ok', 'success', $collections);

    }
//    public function plugins_request_count()
//    {
//        $arr = [];
//        for ($i = 6; $i >= 0; $i--) {
//            $date = date("Y-m-d", strtotime("-$i day"));
//            $arr[$date] = Request::whereDay('create_time', $date)->max('request_count');
//        }
//        return msg('ok', 'success', $arr);
//
//    }

    public function user_active_count()
    {
        $arr = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date("Y-m-d", strtotime("-$i day"));
            $arr[$date] = \app\model\User::whereDay('update_time', $date)->cache()->count('id');
        }
        return msg('ok', 'success', $arr);

    }

    public function user_increase_count()
    {
        $arr = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date("Y-m-d", strtotime("-$i day"));
            $arr[$date] = \app\model\User::whereDay('create_time', $date)->cache()->count('id');
        }
        return msg('ok', 'success', $arr);

    }
}