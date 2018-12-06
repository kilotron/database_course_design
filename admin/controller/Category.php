<?php
namespace app\admin\controller;
use think\Controller;
class Category extends Controller
{
	public function index(){
		isset($_SESSION) or session_start();
		if (isset($_SESSION['username']))
		{
			return $this->fetch();
		}
        // return $this->fetch('/db/application/index/view/user/login.html');
		return $this->fetch();
	}
	
	public function add(){
		return $this->fetch("product-category-add");
	}
