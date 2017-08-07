<?php 
namespace a;
use b;
use inc;
use test;
use Exception;
class aa{
	
	public function aaa(){
		echo 'aaa';
		inc::init();
		test::testa();
		$e = new Exception();
	}
	
}

namespace b;
class bb{
	public function bbb(){
		echo 'bbb';
	}
	
	public function loada($class){
		var_dump($class);exit;
	}
	
}



?>