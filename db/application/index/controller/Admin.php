<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Admin extends Controller
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
		unset($_SESSION['id']);
		$_SESSION['status'] = 0;
		return $this->fetch("Index/index");
	}
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
	public function save_product(Request $request){
		$c = CheckLogin();
		if($c.status == "alreadyIn");
			$data = input('post.');
			$validate = validate('Product');
			if(!($validate->check($data))){
				$this->error($validate->getError());
			}
			//dump($data);
			$name = $data['product_name'];
			$price = $data['price'];
			$quantity = $data['quantity'];
			$detail = $data['detail'];
			$cat_no = $data['cat'];
			$file = $request->file('file');
			
			if (empty($file)) {
				$this->error('请选择上传图片');
			}

			$result = Db::query('SELECT * FROM product WHERE product_name=?', [$name]);
			if (!empty($result))
				$this->error('已经添加过此物品');

			$result = Db::execute('INSERT INTO product VALUES (null, ?, ?, ?, ?, 0, ?)', [$name, $detail, $price, $quantity, $cat_no]);
			if (empty($result))
				return $this->error('添加失败');
			
			$path = ROOT_PATH.'public/static/images/product_pictures/';
			$info = $file->rule('md5')->move($path);
			if (!$info){
				return $this->error($file->getError());
			}
			$path = $info->getSaveName();
			$product_id = Db::query('SELECT product_id FROM product WHERE product_name=?', [$name]);
			$product_id = $product_id[0]['product_id'];
			$result = Db::execute('INSERT INTO product_picture VALUES (null,?,?)', [$product_id, $path]);
			if (!empty($result))
				return $this->success('添加成功');
		else $this->error('未登录');
	}

	public function delete_product(){
		$ret;
		$prod_id = $_POST['product_id'];
		
		try {
			$result = Db::execute('DELETE FROM product WHERE product_id=?', [$prod_id]);
		} catch (\Exception $e) {
			$ret = array("status" => "failure");
		}
		
		if(!empty(($result)))
			$ret = array("status" => "success");
		else
			$ret = array("status" => "failure");
		return $ret;
	}

}
