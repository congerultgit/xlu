<?php
namespace xlu\lib\base;

class BaseComponent extends  Baseobject{
	
	
	//间接变量赋值	
	public function __set($name, $value){
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
		}
	}
	
	public function __get($name){
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
		}
	}
}



?>