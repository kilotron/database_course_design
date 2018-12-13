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
		$comments = Db::table('product_comment')->alias('c')->join('user_main u','c.user_id=u.id')->where('product_id',$pid)->select();
		$ret['comments'] = $comments;
		return $ret;
	}

	public function queryClassItem(){
		$lists = Db::table('product')->where('category_no',$_GET['cid'])->select();
		return $lists;
	}

	public function orders(){
		return $this->fetch();
	}
	public function buyProduct(){
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return array("status" => "failed", "reason" => "因为你还未登录");
		}
		$user_id = $c['id'];
		$prod_id = $_POST['pid'];
		$result = Db::execute('SELECT * FROM orders WHERE buyer_id=? AND product_id=?', [$user_id, $prod_id]);
		if (!empty($result)) { // 只能买一件
			return array("status" => "failed", "reason" => "因为你已经够买过该物品");
		}
		$result = Db::execute("call create_order(?, ?, 1, @msg)", [$user_id, $prod_id]); // 1是数量
		$msg = Db::query("SELECT @msg as msg");
		$msg = $msg[0]['msg'];
		if ($msg == "success")
			return array("status" => "success");
		else
			return array("status" => "failed", "reason" => $msg);
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

	public function queryBuyerOrder(){
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return 0;
		}
		$user_id = $c['id'];
		try {
			$list = Db::query('SELECT user_main.name as seller_name, product_name, product.product_id as product_id, orders.price as price, status FROM orders, product, user_main, user_product WHERE orders.product_id=product.product_id AND product.product_id=user_product.product_id AND user_main.id=user_product.user_id AND orders.buyer_id=?', [$user_id]);
			return $list;
		} catch (\Exception $e) {
			return 0;
		}
		
	}

	public function addFavorite(){
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return array("status" => "failed", "reason" => "因为你还未登录");
		}
		$user_id = $c['id'];
		$prod_id = $_POST['pid'];
		try {
			$result = Db::execute('INSERT INTO favorites VALUES (?, ?)', [$prod_id, $user_id]);
			if (!empty($result))
				return array("status" => "success");
		} catch (\Exception $e) {
			return array("status" => "failed", "reason" => "已经收藏过该物品");
		}
		
	}

	public function delFavorite() {
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return array("status" => "failed", "reason" => "因为你还未登录");
		}
		$user_id = $c['id'];
		$prod_id = $_POST['pid'];
		try {
			$result = Db::execute('DELETE FROM favorites WHERE user_id=? AND product_id=?', [$user_id, $prod_id]);
			if (!empty($result))
				return array("status" => "success");
		} catch (\Exception $e) {
			return array("status" => "failed", "reason" => $e->getMessage());
		}
	} 

	public function confirmOrder() {
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return array("status" => "failed", "reason" => "因为你还未登录");
		}
		$user_id = $c['id'];
		$prod_id = $_POST['pid'];
		try {
			$result = Db::query('SELECT status FROM orders WHERE buyer_id=? AND product_id=?', [$user_id, $prod_id]);
			if ($result[0]['status'] == '已完成')
				return array("status" => "failed", "reason" => "已经确认过订单");
			if ($result[0]['status'] == '已取消')
				return array("status" => "failed", "reason" => "已经取消过订单");
			$result = Db::execute('UPDATE orders SET status=\'已完成\' WHERE buyer_id=? AND product_id=?', [$user_id, $prod_id]);
			if (!empty($result))
				return array("status" => "success");
		} catch (\Exception $e) {
			return array("status" => "failed", "reason" => $e->getMessage());
		}
	}

	public function cancelOrder() {
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return array("status" => "failed", "reason" => "因为你还未登录");
		}
		$user_id = $c['id'];
		$prod_id = $_POST['pid'];
		try {
			$result = Db::query('SELECT status FROM orders WHERE buyer_id=? AND product_id=?', [$user_id, $prod_id]);
			if ($result[0]['status'] == '已完成')
				return array("status" => "failed", "reason" => "因为订单已完成");
			if ($result[0]['status'] == '已取消')
				return array("status" => "failed", "reason" => "因为已经取消过订单");
			$result = Db::execute('UPDATE orders SET status=\'已取消\' WHERE buyer_id=? AND product_id=?', [$user_id, $prod_id]);
			if (!empty($result))
				return array("status" => "success");
		} catch (\Exception $e) {
			return array("status" => "failed", "reason" => $e->getMessage());
		}
	}

	public function writeComment(){
		$c = $this->CheckLogin();
		if ($c['status'] == "notIn") {
			return array("status" => "failed", "reason" => "因为你还未登录");
		}
		$user_id = $c['id'];
		$prod_id = $_POST['pid'];
		$content = $_POST['content'];
		try {
			$data = ['user_id' => $user_id, "product_id" => $prod_id, 'content' => $content, 'comment_time' => time()];
			$result = Db::table('product_comment')->insert($data);
			return array("status" => "success");
		} catch (\Exception $e) {
			echo $e;
			return array("status" => "failed", "reason" => "数据错误");
		}
		
	}
}
