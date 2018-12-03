<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
	public function index(){
        return $this->fetch();
	}
	public function login(){
// 		$model = controller("User");
// 		$model->login();
		return $this->fetch();
	}
}
