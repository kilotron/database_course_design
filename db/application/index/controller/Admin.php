<?php
namespace app\index\controller;
use think\Controller;
class Admin extends Controller
{
	public function index(){
		return $this->fetch();
	}
	public function category(){
		return $this->fetch();
	}
	public function category_add(){
		return $this->fetch();
	}
	public function welcome(){
		return $this->fetch();
	}
	public function product_add(){
		return $this->fetch();
	}
	public function product_brand(){
		return $this->fetch();
	}	
	public function product_list(){
		return $this->fetch();
	}	
	public function save_category(){
		$data =input('post.');
//		print_r($data);
		$validate = validate('Category');
		if(!($validate->check($data))){
			$this->error($validate->getError());
		}
	}

}
