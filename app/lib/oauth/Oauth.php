<?php


namespace app\lib\oauth;


use think\Response;

interface Oauth
{
    public function __construct($config, $params);

    public function oauth(): Response;

    public function callback(): array;
}