<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
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
	//	dump($data);
	//	print_r($data);
		$validate = validate('Category');
		if(!($validate->check($data))){
			$this->error($validate->getError());
		}
		$cat_name = $data['product-category-name'];
		$cat_info = $data['info'];
		$result = Db::execute('SELECT * FROM category WHERE name=?', [$cat_name]);
		if (empty($result)) {
			$result = Db::execute('INSERT INTO category VALUES (null, ?, ?)', [$cat_name, $cat_info]);
			return $this->success('添加成功');
		} else {
			return $this->error('已经存在的分类');
		}
	}

}
