<?php
namespace app\index\validate;
use think\Validate;

class Product extends Validate{
	protected $rule = [
		'product_name' => 'require|max:50|min:1',
		'price' => 'require|float|between:0.0, 9999.9',
		'detail' => 'require|min:1',
	];

	protected $message = [
		'product_name.require' => '请填写物品名称',
		'product_name.min' => '请填写物品名称',
		'product_name.max' => '物品名称不能超过50个字',
		'price.require' => '请填写价格',
		'price.between' => '价格大于0',
		'detail.require' => '请填写物品描述',
		'detail.min' => '请填写物品描述',
	];
}
?>