<?php


namespace app\controller;


use app\BaseController;
use think\response\View;

class Admin extends BaseController
{
    public function index(): View
    {
        return view();
    }
}