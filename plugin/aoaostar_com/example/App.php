<?php

namespace plugin\aoaostar_com\example;

use plugin\Drive;

class App implements Drive
{
    # 访问/api/example
    public function Index()
    {
        return msg("ok","success");
    }
    # 访问/api/example/upload
    public function Upload()
    {
        return msg("ok","success");
    }
}