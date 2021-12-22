<?php


namespace app\lib;



class EnvOperation
{

    private $env;
    private $exampleEnv;
    public function __construct($exampleEnv)
    {
        $this->exampleEnv = $exampleEnv;
    }

    /**
     * @return mixed
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param mixed $env
     */
    public function setEnv($env): void
    {
        $this->env = $env;
    }

    public function purify($env = [])
    {
        preg_match_all('#{{(.+?)}}#', $this->env, $matches, PREG_SET_ORDER);
        foreach ($matches as $v) {
            $list = explode(':', $v[1]);
            $value = isset($env[$list[0]]) ? $env[$list[0]] : '';
            $defaultValue = isset($list[1]) ? $list[1] : '';
            $type = isset($list[2]) ? $list[2] : '';
            if ($type === 'bool') {
                $value = var_export(boolval($value), 1);
            }
            if (empty($value)) {
                $value = $defaultValue;
            }
            $this->env = preg_replace('#' . $v[0] . '#', $value, $this->env);
        }
    }

    public function set($key, $newValue)
    {
        if (is_null($this->env)) {
            $this->env = preg_replace('#{{' . $key . '}}#', $newValue, $this->exampleEnv);
        } else {
            $this->env = preg_replace('#{{' . $key . '}}#', $newValue, $this->env);
        }
    }

    public function save()
    {
        return file_put_contents(app()->getRootPath() . '.env', $this->env);
    }

}