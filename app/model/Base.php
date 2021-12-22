<?php


namespace app\model;


use think\Model;

class Base extends Model
{
    protected $searchField = [];

    public function pagination($param, $where = [])
    {
        foreach ($this->searchField as $v) {
            if (!empty($param[$v])) {
                $where[] = [$v, 'like', '%' . $param[$v] . '%'];
            }
        }
        $model = new $this;
        $total = $model->count();

        $model = $model->where($where);
        if (!empty($param['page'])) {
            $page = intval($param['page']);
            $model = $model->page($page);
        }
        if (!empty($param['limit'])) {
            $limit = intval($param['limit']);
            $model = $model->limit($limit);
        }
        $select = $model->select();

        $data = [
            'total' => $total,
            'items' => $select,
        ];
        return $data;
    }

}