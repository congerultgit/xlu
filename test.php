<?php
include './xlu.php';
//test程序

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

$res = xlu::object('xlu\lib\resourceabc');

var_dump($res);

exit;

//var_dump($a->test());



?>