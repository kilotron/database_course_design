<?php
namespace app\index\validate;
use think\Validate;

class Category extends Validate{
	protected $rule = [
		'product-category-name' => 'require|max:10|min:1',
		'info' => 'max:100'
	];

	protected $message = [
		'product-category-name.require' => '请填写分类名称',
		'product-category-name.max' 	=> '分类名称的长度不能超过10个字',
		'product-category-name.min'		=> '请填写分类名称',
		'info.max' => '备注不能超过100个字'
	];
}
?>