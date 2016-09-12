<?php
namespace xlu\lib\di;
use xlu\lib\base\BaseComponent;
//对象容器
class DiContainer extends  BaseComponent{

	//容器对象实例
	private $singletons = [];
	//容器对应定义
	private $definitions = [];
		
	
	public function get($type,$param=array(),$array=array()){
		
		//生成唯一的key
		$key = $type.base64_encode(json_encode($param)).base64_encode(json_encode($array));
		
		$tmp_obj = null;
		if(is_string($type)){
			$tmp_obj = $this->set($type,$param,$array);
			$this->singletons[$key] = $tmp_obj;
			return $tmp_obj;
			
		}
		if(is_array($type)){
			if(isset($type['class'])){
				$type_class = $type['class'];
				unset($type['class']);
				$tmp_obj = $this->set($type,$param,$array);
				$this->singletons[$key] = $tmp_obj;
				return $tmp_obj;				
			}			
		}
		
		return false;
		
	}
	
	
	public function set($type,$param=array(),$array=array()){

		$key = $type.base64_encode(json_encode($param)).base64_encode(json_encode($array));
	
		//echo $type;
		if(class_exists($type) == FALSE){
			$this->singletons[$key] = $param;
			return $param;
		}
		
		$class_name = $type;
		$constructor_param = $param;
		$public_param = $array;
		
		$tmp_object = DiInstance::createObject($type,$param,$array);
		
		return $tmp_object;
		
	}	
	
}



?>