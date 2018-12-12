<?php
namespace app\index\controller;
use think\Db;
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

	public function class()
	{
		// $lists = Db::name('product')->where('category_no',$_GET['cid'])->paginate(9);
		// $this->assign('lists', $lists);
		// $page = $lists->render();
		// $this->assign('page', $page);
		return $this->fetch();	
	}

	public function sellerp()
	{
		return $this->fetch();	
	}
	public function checkout()
	{
		return $this->fetch();	
	}

	public function getProductInfo()
	{
		$ret;
		$pid = $_POST['pid'];
		$result = Db::table('product')->where('product_id',$pid)->select();
		$product_name = $result[0]['product_name'];
		//$pictures = ?;
		//$seller_profile = ?;
		$price = $result[0]['price'];
		$oprice = 9999;
		$detail = $result[0]['detail'];
		$quantity = $result[0]['quantity'];
		$likes = $result[0]['likes'];
		$category_no = $result[0]['category_no'];
		// $seller_id = $result[0]['user_id'];
		// $result = Db::query('SELECT COUNT(*) FROM user_product WHERE user_id=?', [$seller_id]);
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

	public function queryClassItem(){
		$lists = Db::table('product')->where('category_no',$_GET['cid'])->select();
		return $lists;
	}
	public function nextPage(){
		return var_dump($this->list);
	}
}
