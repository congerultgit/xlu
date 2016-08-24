<?php
include './base.php';
//test程序

//var_dump( strcmp ('124','123')); 
//var_dump(strncmp('\abc','\\',1));

$a = trim('\a\b\c','\\');
//echo $a;
//testend
$a = new  basexxl\base();
/*
 *期望 对象由对象工厂直接获得
 * 对象工厂，按类型划分，世界上应该不会有单一的元素归于单独的一个类型
 *  
 */


$res = $a->object('basexxl\lib\resource');

//var_dump($a->test());



?>