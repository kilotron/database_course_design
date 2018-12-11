<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
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
	public function index(){
        // return $this->fetch('/db/application/index/view/user/login.html');
		return $this->fetch();
	}
	public function login(){
// 		$model = controller("User");
// 		$model->login();
		return $this->fetch();
	}

	public function single()
	{
		return $this->fetch("single");	
	}

	public function getProductInfo()
	{
		$ret;
		$pid = $_POST['pid'];
		echo 'hellp';
		$result = Db::query('SELECT * FROM product, user_product WHERE product.product_id=user_product.product_id AND product.product_id=?', [$pid]);
		$dump($result);
		$product_name = $result[0]['product_name'];
		//$pictures = ?;
		//$seller_profile = ?;
		$price = $result[0]['price'];
		$oprice = 9999;
		$detail = $result[0]['detail'];
		$quantity = $result[0]['quantity'];
		$likes = $result[0]['likes'];
		$category_no = $result[0]['category_no'];
		$seller_id = $result[0]['user_id'];
		$result = Db::query('SELECT COUNT(*) FROM user_product WHERE user_id=?', [$seller_id]);
		dump($result);
		//$seller_product_num = $result[0]['']
		$ret = array ("status" => "success",
					  "productName" => $product_name,
					"price" => $price,
					"oprice" => $oprice,
					"productDetail" => $detail,
					"quantity" => $quantity,
					"likes" => $likes,
					"category_no" => $category_no);
		return $ret;
	}
}
