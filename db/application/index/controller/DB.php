<?php
namespace app\index\controller;

use think\Db;

class Index 
{
    public function index()
    {
        // 后面的数据库查询代码都放在这个位置
		$result = Db::execute('insert into think_data (id, name ,status) values (5, "thinkphp",1)');
		dump($result);
    }
}