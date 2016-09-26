<?php
include './xlu.php';//test程

//var_dump( strcmp ('124','123')); 
//var_dump(strncmp('\abc','\\',1));

$a = trim('\a\b\c','\\');
//echo $a;
//testend
/*
 *期望 对象由对象工厂直接获得
 * 对象工厂，按类型划分，世界上应该不会有单一的元素归于单独的一个类型
 *  
 */
 
//ma dan lao zi hao lei 

//$res = xlu::object('xlu\lib\resource');

$res = xlu::object('xlu\lib\resource',array(array('res_name'=>'http://www.baidu.com','res_type'=>'url')));

$obj_res = $res->getObject();


//var_dump($obj_res->read());

//test123
$db = xlu::object(array(
		'class'=>'xlu\lib\db\DbConnection',
		'username' =>'root',
		'password'  => '',
		'dsn'=>'mysql:host=127.0.0.1;dbname=test',
		'charset' =>'utf8'
	));
$tmp = $db->createCommand('select * from fun_ssq limit 10');
$data = $tmp->queryAll();

var_dump($data);

//数据插入练习
//insert = 'insert into fun_ssq(sys_number,red_1,red_2,red_3,red_4,red_5,red_6,blue_1,create_time) values("1",1,1,1,1,1,1,1,'.time().')';
//$tmp = $db->createCommand($insert);
//$num = $tmp->execute();

var_dump($num);
exit;

//var_dump($a->test());



?>