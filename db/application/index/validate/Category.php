<?php
namespace app\index\validate;
use think\Validate;

class Category extends Validate{
	protected $rule = [
		['name','require|max:10', '请填写分类名|分类名不超过10个字符'],
	];
}
?>