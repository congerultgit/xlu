<?php
namespace xlu\lib\di;
use xlu\lib\base\BaseComponent;
//对象容器
class Di extends  BaseComponent{

	//容器对象实例
	private $singletons = [];
	//容器对应定义
	private $definitions = [];		
	
	public function get($type,$param=array(),$array=array()){
		
		$tmp_obj = null;
		if(is_string($type)){
			if(is_set($this->singletons[$type])){
				return $this->singletons[$type];
			}
			$tmp_obj = $this->set($type,$param,$array);
			$this->singletons[$type] = $tmp_obje;
			return $tmp_obj;
			
		}
		if(is_array($type)){
			if(isset($type['class'])){
				$type_class = $type['class'];
				unset($type['class']);
				$tmp_obj = $this->set($type,$param,$array);
				$this->singletons[$type] = $tmp_obje;
				return $tmp_obj;				
			}			
		}
		
		return false;
		
	}
	
	
	public function set($type,$param=array(),$array=array()){
		
		$class_name = $type;
		$constructor_param = $param;
		$public_param = $array();
		
	}	
	
}



?>