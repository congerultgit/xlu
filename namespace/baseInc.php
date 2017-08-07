<?php
namespace inc;
class baseInc{

	public static   function load($class){

		var_dump('class:'.$class.'!');
		if($class=='xlu'){
			//echo 'get';
			//include('.\inc.php');
		}
		if($class=='xlu\Xlu'){
			//echo 'get';
			//include('.\inc.php');
		}
		include_once('.\nptest.php');
	
	}	
	
	
	
}


?>