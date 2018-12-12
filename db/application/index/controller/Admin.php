<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

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
	public function product_edit(){
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
		$data = input('post.');
		$validate = validate('Product');
		if(!($validate->check($data))){
			$this->error($validate->getError());
		}
		$name = $data['product_name'];
		$price = $data['price'];
		//$quantity = $data['quantity'];
		$quantity = 1;
		$detail = $data['detail'];
		$cat_no = $data['cat'];
		$img = $request->file('file');
		
		if (empty($img)) {
			$this->error('请选择上传图片');
		}

		$result = Db::query('SELECT * FROM product WHERE product_name=?', [$name]);
		if (!empty($result))
			$this->error('已经添加过此物品');

		$result = Db::execute('INSERT INTO product VALUES (null, ?, ?, ?, ?, 0, ?)', [$name, $detail, $price, $quantity, $cat_no]);
		if (empty($result))
			return $this->error('添加失败');
		
		$result = Db::query('SELECT * FROM product WHERE product_name=?', [$name]);
		$prod_id = $result[0]['product_id'];
		$file = ROOT_PATH.'public/static/images/product_pictures/';
		$info = $img->move($file, $prod_id.".jpg");
		if ($info)
			return $this->success('添加成功', 'product_list');
	}

	public function delete_product(){
		$ret;
		$prod_id = $_POST['product_id'];
		
		try {
			$result = Db::execute('DELETE FROM product WHERE product_id=?', [$prod_id]);
		} catch (\Exception $e) {
			$ret = array("status" => "failure");
			return $ret;
		}
		
		if(!empty(($result)))
			$ret = array("status" => "success");
		else
			$ret = array("status" => "failure");
		return $ret;
	}

	public function get_product_info(){
		$ret;
		$prod_id = $_POST['pid'];

		try {
			$result = Db::query('SELECT * FROM product WHERE product_id=?', [$prod_id]);
			$product_name = $result[0]['product_name'];
			$price = $result[0]['price'];
			$detail = $result[0]['detail'];
			$cat_no = $result[0]['category_no'];
			$ret = array("status" => "success", "product_name" => $product_name, "price" => $price, "detail" => $detail, "cat_no" => $cat_no);
		} catch(\Exception $e) {
			$ret = array("status" => "failed");
		}
		return $ret;
	}

	public function update_product(Request $request){
		$data = input('post.');
		$validate = validate('Product');
		if(!($validate->check($data))){
			$this->error($validate->getError());
		}
		$name = $data['product_name'];
		$price = $data['price'];
		//$quantity = $data['quantity'];
		$quantity = 1;
		$detail = $data['detail'];
		$cat_no = $data['cat'];
		$img = $request->file('file');

		$result = Db::query('SELECT * FROM product WHERE product_name=?', [$name]);
		$prod_id = $result[0]['product_id'];

		$result = Db::execute('UPDATE product SET product_name=?, detail=?, price=?, quantity=?,category_no=? WHERE product_id=?', [$name, $detail, $price, $quantity, $cat_no, $prod_id]);
		
		$info = true;
		if (!empty($img)) {
			$file = ROOT_PATH.'public/static/images/product_pictures/';
			$info = $img->move($file, $prod_id.".jpg");
		}
		
		if ($info)
			return $this->success('更新成功', 'product_list');
	}

}
