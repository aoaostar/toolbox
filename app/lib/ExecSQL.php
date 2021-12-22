<?php


namespace app\lib;


use think\facade\Db;

class ExecSQL
{
    private $errors = [];

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param $sql array|string SQL语句 传入字符串类型需以\n分割
     * @return int 返回影响行数
     */
    public function exec($sql)
    {
        $sql = $this->purify($sql);
        $number = 0;
        foreach ($sql as $key => $line) {
            try {
                $number += Db::execute($line);
            } catch (\Exception $e) {
                $this->errors[] = '第' . $key . '行：' . iconv('utf-8','utf-8//IGNORE',$e->getMessage());
            }
        }
        return $number;
    }

    public function purify($sql)
    {
        $tmp = '';
        $purify = [];
        if (!is_array($sql)) {
            $sql = explode("\n", $sql);
        }
        foreach ($sql as $key => &$line) {

            $line = trim($line);
            if (substr($line, 0, 2) == '--' || $line == '' || substr($line, 0, 2) == '/*') {
                unset($sql[$key]);
                continue;
            }
            $tmp .= $line;
            if (substr($line, -1, 1) == ';') {
                unset($sql[$key]);
                $purify[] = $tmp;
                $tmp = '';
            }
            unset($sql[$key]);
        }
        return $purify;
    }

}