<?php


namespace app\controller\master;


use app\controller\Base;
use app\model\Request;
use think\facade\Cache;

class Analysis extends Base
{
    public function console()
    {
        $arr = Cache::get(__METHOD__, []);
        if (empty($arr)) {
            $date = date("Y-m-d");
            //注册用户
            $arr['increase_user'] = \app\model\User::whereDay('create_time', $date)->count('id');
            //活跃用户
            $arr['active_user'] = \app\model\User::whereDay('update_time', $date)->count('id');
            //活跃用户
            $arr['request_count'] = Request::whereDay('create_time', $date)->max('request_count');
            $arr['max_request_count'] = Request::max('request_count');
            $arr['user_count'] = \app\model\User::count('id');
            $arr['plugin_count']['total'] = \app\model\Plugin::count('id');
            Cache::set(__METHOD__, $arr);
        }

        return success($arr);
    }


    public function traffic_trends()
    {
        $arr = Cache::get(__METHOD__, []);
        if (empty($arr)) {
            for ($i = 6; $i >= 0; $i--) {
                $date = date("Y-m-d", strtotime("-$i day"));
                $arr['plugin_total_request_count'][$date] = Request::whereDay('create_time', $date)->sum('request_count');
                $arr['plugin_max_request_count'][$date] = Request::whereDay('create_time', $date)->max('request_count');
                $arr['user_active_count'][$date] = \app\model\User::whereDay('update_time', $date)->count('id');
                $arr['user_increase_count'][$date] = \app\model\User::whereDay('create_time', $date)->count('id');
            }
        }
        return success($arr);
    }

    public function statistics()
    {

        //       ['product', '2012', '2013', '2014', '2015', '2016', '2017'],
        //       ['Milk Tea', 56.5, 82.1, 88.7, 70.1, 53.4, 85.1],
        //       ['Matcha Latte', 51.1, 51.4, 55.1, 53.3, 73.8, 68.7],
        //       ['Cheese Cocoa', 40.1, 62.2, 69.5, 36.4, 45.2, 32.5],
        //       ['Walnut Brownie', 25.2, 37.1, 41.2, 18, 33.9, 49.1],
        //9 = 10天
        $DAYS = request()->param('days', 10) - 1;
        //返回series(折线)最大个数，其他列为普通
        $COUNT = request()->param('count', 10);

        $arr = Cache::get(__METHOD__ . '_' . $DAYS . '_' . $COUNT);
        if (!empty($arr)) {
            return success($arr);
        }

        $arr = [
            ['date'],
        ];
        $collections = Request::whereTime('create_time', '-10 day')
            ->select();
        $tmp = [];
        foreach ($collections as $v) {
            $tmp[$v->plugin_id]['id'] = $v->plugin_id;
            $tmp[$v->plugin_id][date('Y-m-d', strtotime($v->create_time))] = $v->request_count;
        }
        for ($i = $DAYS; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i day"));
            $arr[0][] = $date;
            foreach ($tmp as $k => $v) {
                if (!isset($v[$date])) {
                    $tmp[$k][$date] = 0;
                }
            }
        }
        usort($tmp, function ($a, $b) {
            $av = end($a);
            $bv = end($b);
            if ($av == $bv) return 0;
            return ($av > $bv) ? -1 : 1;
        });
        $others = ['其他'];
        for ($i = $DAYS; $i >= 0; $i--) {
            $others[] = 0;
        }
        foreach ($tmp as $v) {
            if ($COUNT === 0) {
                for ($i = 0; $i < count($others) - 1; $i++) {
                    $date = date('Y-m-d', strtotime("-$i day"));
                    $others[$i + 1] += $v[$date];
                }
                continue;
            }
            $title = \app\model\Plugin::where('id', $v['id'])->value('title');
            $a = [$title];
            for ($i = $DAYS; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i day"));
                $a[] = $v[$date];
            }
            $arr[] = $a;
            $COUNT--;
        }
        $arr[] = $others;

        usort($tmp, function ($a, $b) {
            $av = end($a);
            $bv = end($b);
            if ($av == $bv) return 0;
            return ($av > $bv) ? -1 : 1;
        });
        Cache::set(__METHOD__ . '_' . $DAYS . '_' . $COUNT, $arr);
        return success($arr);
    }

}