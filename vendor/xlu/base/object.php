<?php 
namespace xlu\base;
use xlu;

class object implements configBase{



	public function __get($name){

		$getFunction = 'get'.$name;
        if (method_exists($this, $getFunction)) {
            return $this->$getFunction();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }

	}


    public function __set($name, $value)
    {
        $setFunction = 'set' . $name;
        if (method_exists($this, $setFunction)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }


}



?>