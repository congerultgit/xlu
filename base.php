<?php
namespace xlu;
use xlu\lib\di\DiContainer;
use xlu\lib\base\BaseErrorException;
define('ROOT',__DIR__);
define('XLUROOT',__DIR__);
define('DIROFF',DIRECTORY_SEPARATOR);

class base {
	
	public static $nickname = array();
	
	public static $container = '';
	
	public function test(){
		return 123;
	}
	
	public function makeObject($name,$array=''){
		self::object($name);		
	}	
	
	public static function object($name,$param=array(),$array=array()){
		
		$classname = $name;
		if(is_string($name)){
			$classname = self::autoload($name);
		}
		if(isset($name['class'])){
			$classname = self::autoload($name['class']);
			
		}
		$object = self::$container->get($classname,$param,$array);
		
		return $object;

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
	 * 1 xlu\lib\resource 目录  去basexxl\lib\resource\RescouceFactory.php 目录寻找 ,暂时去掉自动寻找,怪sb的
	 * 2 lib\resource\ResourceUrl.php 文件
	 * 3 \lib\resource\RscourceUrl 同2直接引用
	 * */
	static public function autoload($classname){
		
		
		$classname = $return_classname = trim($classname,'\\');
				
		$class_array = explode('\\',$classname);		
		
		
		//完全命名空间  文件路径已经解析完全		
		$tmp_file = str_replace('\\',DIROFF,self::getNickname($classname));
		
		//理想类文件
		$class_file = $tmp_file.'.php';
		
		//如果文件以base开始，直接切换进lib\base 全局加载
		
		
		if(file_exists($class_file)){
			include_once $class_file;
		}else{
			
			try{
				if(@scandir($tmp_file) != false){
				//如果没有找见，看是否有默认工厂类
					$base_factory_file = ucfirst($class_array[(count($class_array)-1)]).'Factory.php';
					$return_classname = $return_classname.'\\'.ucfirst($class_array[(count($class_array)-1)]).'Factory';
					$fact_class = $tmp_file.DIROFF.$base_factory_file;
					if(file_exists($fact_class)){
						include_once $fact_class;
					}
				}else{
					throw new  BaseErrorException (" class:[$classname] not found.");
				}			
			}catch(BaseErrorException $bee){
				echo $bee->getMessage();
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