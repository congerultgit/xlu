<?php
namespace xlu;
define('ROOT',__DIR__);
define('XLUROOT',__DIR__);
define('DIROFF',DIRECTORY_SEPARATOR);
class base {
	
	public static $nickname = array();
	
	public function test(){
		return 123;
	}
	
	public function makeObject($name,$array=''){
		self::object($name);		
	}	
	
	public static function object($name,$array=''){
		
		$classname = self::autoload($name);
		
		$test = new $classname();		
		var_dump($test);
	}
	
	
	
	static public function setNickname($name,$value){
		self::$nickname[$name] = $value;
	}
	
	static public function getNickname($name){
		$tmp_name = trim($name,'\\');
		$tmp_array = explode('\\', $tmp_name);
		$for_name = $tmp_array[0];
		if(isset(self::$nickname[$for_name])){
			$return = array();
			//$return['one'] = preg_replace("/{$for_name}/", $tmp_name, $tmp_name,1);
			return preg_replace("/{$for_name}/", self::$nickname[$for_name], $tmp_name);
			//return $return;
		}else{
			return $name;
		}		
	}
	
	/*
	 * $classname 
	 * 1 lib\resource  去basexxl\lib\resource\RescouceFactory.php 目录寻找 
	 * 2 lib\resource\ResourceUrl.php 文件
	 * 3 \lib\resource\RscourceUrl 同2直接引用
	 * */
	static public function autoload($classname){
		$classname = $return_classname = trim($classname,'\\');
		$class_array = explode('\\',$classname);		
		//完全命名空间
		$tmp_classname = self::getNickname($classname);
		$tmp_file = str_replace('\\',DIROFF,$tmp_classname);
		$class_file = $tmp_file.'.php';
		if(file_exists($class_file)){
			include_once $class_file;
		}else{
			//如果没有找见，看是否有默认工厂类
			$base_fact_file = ucfirst($class_array[(count($class_array)-1)]).'Factory.php';
			$return_classname = $return_classname.'\\'.ucfirst($class_array[(count($class_array)-1)]).'Factory';
			$fact_class = $tmp_file.DIROFF.$base_fact_file;
			if(file_exists($fact_class)){
				include_once $fact_class;
			}else{
				echo $class_file.'<br>';
				echo $fact_class.'<br>';
				echo 'class file no found';exit;
				
			}
			
		}
		return $return_classname;

	}
	
	/*
	 * 
	 * 
	 * */
	static public function analysisClassName($str){
		
		
	}
	
}


?>