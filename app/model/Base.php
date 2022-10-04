<?php


namespace app\model;


use think\Model;

class Base extends Model
{
    protected $searchField = [];

    public function pagination($param, $where = [])
    {
        $whereOr = [];
        foreach ($this->searchField as $v) {
            if (!empty($param[$v])) {
                $whereOr[] = [$v, 'like', '%' . $param[$v] . '%'];
            }
        }
        $model = new $this;

        $model = $model->where($where)->whereOr($whereOr);
        $total = $model->count();
        if (!empty($param['page'])) {
            $page = intval($param['page']);
            $model = $model->page($page);
        }
        if (!empty($param['limit'])) {
            $limit = intval($param['limit']);
            $model = $model->limit($limit);
        }
        if (isset($param['column']) && isset($param['order'])) {
            $model = $model->order($param['column'], $param['order']);
        }
        $select = $model->select();

        $data = [
            'total' => $total,
            'items' => $select,
        ];
        return $data;
    }

}