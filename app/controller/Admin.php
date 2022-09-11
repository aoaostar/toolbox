<?php


namespace app\controller;


use app\BaseController;

class Admin extends BaseController
{
    protected function initialize()
    {
//        View::config(['view_path' => '']);
    }
    public function index(){
        return view();
    }
}