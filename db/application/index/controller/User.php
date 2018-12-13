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
	public function checkIn()
	{
		isset($_SESSION) or session_start();
        if (isset($_SESSION['name'])) {
            if ($_SESSION['status'] == 1) {
                return true;
            }
        }
        return false;
	}
	public function unset()
	{
		isset($_SESSION) or session_start();
		unset($_SESSION['name']);
		unset($_SESSION['id']);
		$_SESSION['status'] = 0;
		return $this->fetch("Index/index");
	}
	public function login(){
		isset($_SESSION) or session_start();
		if(isset($_SESSION) && isset($_SESSION['name'])){
			return $this->fetch("Index/index");
		} 
		return $this->fetch("login");
	}
	public function registered(){
		isset($_SESSION) or session_start();
		if(isset($_SESSION) && isset($_SESSION['name'])){
			return $this->fetch("Index/index");
		} 
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
			
			$ret;
			Db::startTrans();
			try{
				Db::execute('insert into user_main(name,email,password) value("'.$name.'","'.$email.'","'.$pwd.'")');
				$id = Db::query('select id from user_main where email="'.$email.'"');
				$id = $id[0]['id'];
				$create_time = time();
				Db::execute('insert into user_detail(id,sex,create_time,update_time) value("'.$id.'","'.$sex.'","'.$create_time.'","'.$create_time.'")');
				Db::commit();
				$_SESSION['id'] = $id;
				$_SESSION['email'] = $email;
				$_SESSION['name'] = $name;
				$_SESSION['status'] = 1;
				$ret = array("status" => "success");
			}catch(\Exception $e){
				$ret = array("status" => "failed", "reason" => "未知错误");
				Db::rollback();
			}

			return $ret;
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

	public function getBasicInfo(){
		$ret;
		if($this->checkIn()){
			$result = Db::query('select * from user_main where id='.$_SESSION['id'].'');
			$result2 = Db::query('select * from user_detail where id='.$_SESSION['id'].'');
			if(empty($result) || empty($result2)){
				$ret = array('status' => 'failed','reason' => 'notReg');
			}		
			else{
				$ret = array('status' => 'success','id' => $result[0]['id'], 'name'=> $result[0]['name'], 'email'=> $result[0]['email'], 'sex'=> $result2[0]['sex'], 'money'=> $result2[0]['balance']);
			}
		}
		else{
			$ret = array('status' => 'failed','reason' => 'notIn');
		} 

		return $ret;
	}

	public function changeBasicInfo(){
		$ret;
		$name = $_POST['name'];
		$email = $_POST['email'];
		$sex = $_POST['sex'];
		$update_time = time();
		if($this->checkIn()){
			Db::startTrans();
			try{

				// $a = Db::execute('update user_main set name ="'.$name.'",email="'.$email.'" where id='.$_SESSION['id'].'');
				// $b = Db::execute('update user_detail set sex = "'.$sex.'",update_time="'.time().' where id='.$_SESSION['id'].'');
				Db::table('user_main')
				->where('id', $_SESSION['id'])
				->update(['name' => $name,'email' => $email]);
				
				Db::table('user_detail')
				->where('id', $_SESSION['id'])
				->update(['sex' => $sex,'update_time' => $update_time]);

				Db::commit();
				$_SESSION['email'] = $email;
				$_SESSION['name'] = $name;
				$_SESSION['status'] = 1;
				$ret = array("status" => "success");
			}catch(\Exception $e){
				$ret = array("status" => "failed", "reason" => "未知错误");
				Db::rollback();
			}
		}
		else{
			$ret = array('status' => 'failed','reason' => 'notIn');
		} 

		return $ret;
	}

	public function changePwd(){
		$ret;
		$oldpwd = $_POST['oldpwd'];
		$newpwd = $_POST['pwd'];

		if($this->checkIn()){
			$realPwd = Db::table('user_main')
						->field('password')
						->where('id', $_SESSION['id'])
						->select();
			if(empty($realPwd)){
				$ret = array('status' => 'failed','reason' => 'notReg');
			}
			else if($realPwd[0]['password'] != $oldpwd){
				$ret = array('status' => 'failed','reason' => 'wrongPwd');
			}
			else{
				Db::table('user_main')
				->where('id', $_SESSION['id'])
				->update(['password' => $newpwd]);
				$ret = array('status' => 'success');
			}
		}
		else{
			$ret = array('status' => 'failed','reason' => 'notIn');
		} 

		return $ret;
	}

	public function changeProfilePicture(){
		// if($this->checkIn()){
		// 	$base64_image_content = $_POST['pic'];
		// 	$path = ROOT_PATH."/public/static/images/profile_pictures";
		// 	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
		// 		$type = $result[2];
		// 		$new_file = $path."/".$_SESSION['id'].".jpg";
		// 		if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
		// 			return  array('status' => 'success','id' => $_SESSION['id']);
		// 		}else{
		// 			return  array('status' => 'failed','reason' => 'createFail');
		// 		}
		// 	}else{
		// 		return  array('status' => 'failed','reason' => 'noMatch');;
		// 	}
		// }
		// else return array('status' => 'failed','reason' => 'notIn');
		
		if($this->checkIn()){
			$image = $_POST['pic'];
			$path = ROOT_PATH."/public/static/images/profile_pictures/";
			$new_file = $path.$_SESSION['id'].".jpg";
			if (strstr($image,",")){
				$image = explode(',',$image);
				$image = $image[1];
			}
			if (!is_dir($path)){ //判断目录是否存在 不存在就创建
				mkdir($path,0777,true);
			}
			if (file_put_contents($new_file,base64_decode($image))){
				return  array('status' => 'success','id' => $_SESSION['id']);
			}else{
				return  array('status' => 'failed','reason' => 'createFail');
			}
		}
		else return array('status' => 'failed','reason' => 'notIn');
	}

	public function getMoney(){
		if($this->checkIn()){
			try{
				Db::execute("update user_detail set balance=balance+999 where id = " .$_SESSION['id']);

				return array('status' => 'success');
			}
			catch(exception $e){
				return array('status' => 'failed','reason' => 'wrong data');
			}
		}
		else return array('status' => 'failed','reason' => 'notIn');
	}
}
