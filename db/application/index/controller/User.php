<?php
namespace app\index\controller;
use think\Controller;
class User extends Controller
{
	public function login(){
		return $this->fetch("login");
	}
	public function registered(){
		return $this->fetch("registered");
	}
	
	public function register(){
		$m = M("user_main");
		$email = $_POST['email'];
		$name = $_POST['name'];
		$sex = $_POST['email'];
	}
}
