<?php

namespace plugin\aoaostar_com\example;

use plugin\Drive;

class App implements Drive
{

    public function Index()
    {
        return msg("ok","success",plugin_info_get());
    }
}