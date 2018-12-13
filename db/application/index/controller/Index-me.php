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
				return array("status" => "alreadyIn", "name" => $_SESSION['name'], "id" => $_SESSION["id"]);
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
		$price = $result[0]['price'];
		$oprice = 999999;
		$detail = $result[0]['detail'];
		$quantity = $result[0]['quantity'];
		$likes = $result[0]['likes'];
		$category_no = $result[0]['category_no'];
		$result = Db::table('user_product')->where('product_id', $pid)->select();
		$seller_id = $result[0]['user_id'];
		$pictures[0] = "/db/public/static/images/product_pictures/".$pid.".jpg";
		$seller_profile = "/db/public/static/images/profile_pictures/".$seller_id.".jpg";
		$seller_product_num = Db::table('user_product')->where('user_id', $seller_id)->count();
		$seller_order_num = Db::table('orders')->where('buyer_id', $seller_id)->count();
		$ret = array ("status" => "success",
					"productName" => $product_name,
					"price" => $price,
					"oprice" => $oprice,
					"productDetail" => $detail,
					"quantity" => $quantity,
					"likes" => $likes,
					"sellerProfile" => $seller_profile,
					"sellerProductNum" => $seller_product_num,
					"sellerOrderNum" => $seller_order_num,"pictures" => $pictures);
		return $ret;
	}

	public function queryClassItem(){
		$lists = Db::table('product')->where('category_no',$_GET['cid'])->select();
		return $lists;
	}

	public function orders(){
		return $this->fetch();
	}


	public function queryFavorItem(){
		$c = $this->CheckLogin();
		if($c['status'] == "alreadyIn"){
			$lists = Db::table('favorites')->alias('f')->join('product p','f.product_id=p.product_id')->select();
			return $lists;
		}
		else{
			return 0;
		}
	}
}
