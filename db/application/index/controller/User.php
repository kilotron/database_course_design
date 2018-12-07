<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class User extends Controller
{
	public function CheckLogin()
	{
		isset($_SESSION) or session_start();
		if (isset($_SESSION['name']))
		{
			if ($_SESSION['status'] == 1)
			{
				return array("status" => "alreadyIn", "name" => $_SESSION['name']);
			}
		}
		return array("status" => "notIn");
	}
	public function unset()
	{
		isset($_SESSION) or session_start();
		unset($_SESSION['name']);
		$_SESSION['status'] = 0;
		return $this->fetch("Index/index");
	}
	public function login(){
		return $this->fetch("login");
	}
	public function registered(){
		return $this->fetch("registered");
	}
	public function person_center(){
		return $this->fetch();
	}
	public function Check_register(){
		if(request()->isPost()){
			// $m = M("user_main");
//			dump($_POST);
			$name = $_POST['name'];
			$email = $_POST['email'];
			$pwd = $_POST['pwd'];
			$sex = $_POST['sex'];
			
			$result = Db::query('select name from user_main where name="'.$name.'"');
			if(!empty($result)){
				return array("status" => "failed", "reason" => "multName");
			}
			
			$result = Db::query('select email from user_main where email="'.$email.'"');
			if(!empty($result)){
				return array("status" => "failed", "reason" => "multEmail");
			}
			
			$result = Db::execute('insert into user_main(name,email,password) value("'.$name.'","'.$email.'","'.$pwd.'")');
			if($result == 0){
				return array("status" => "failed", "reason" => "未知错误");
			}
			return array("status" => "success");
		}
		else return $this->fetch();
	}
	
	public function Check_login(){
		isset($_SESSION) or session_start();
		if(request()->isPost()){
			// $m = M("user_main");
//			dump($_POST);
			$email = $_POST['email'];
			$pwd = $_POST['pwd'];
			
			$result = Db::query('select * from user_main where email="'.$email.'"');
			if(empty($result)){
				return array("status" => "failed", "reason" => "nullEmail");
			}
			else if($result[0]['password'] != $pwd){
				return array("status" => "failed", "reason" => "wrongPwd");
			}
			else{
				$_SESSION['id'] = $result[0]['id'];
				$_SESSION['email'] = $email;
				$_SESSION['name'] = $result[0]['name'];
				$_SESSION['status'] = 1;
				return array("status" => "success");
			}
		}
		else return $this->fetch();
	}
}
