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
		'dns'=>'mysql:host=192.168.3.110;dbname=edu_video',
		'charset' =>'utf8'
	));
$tmp = $db->createCommand('select * from edu_user');
$data = $tmp->queryAll();
exit;

//var_dump($a->test());



?>