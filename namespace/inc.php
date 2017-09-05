<?php 
include './baseInc.php';
class inc extends inc\baseInc{
	
	public $a='123';
	public $b='456';
	public function inita(){
		echo 'init';
	}
	static function init(){
		var_dump('abc:init');
	}
	

	
	
}
class test {
	static public function testa(){
		echo 'global class call';
	}
	
	
}

function showArray($param){
	echo '<pre>';
	var_dump($param);
	echo '</pre>';
}
spl_autoload_register(['inc', 'load'], true, true);



?>