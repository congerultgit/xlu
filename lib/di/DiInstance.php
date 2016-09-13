<?php
namespace xlu\lib\di;
use xlu\lib\base\BaseComponent;
use ReflectionClass;
//对象容器
class DiInstance extends  BaseComponent{
	
	
	public static  $selfClass;
	
	public $definitions = array();
	
	

    private $_reflections = [];

    private $_dependencies = [];
	
	public static function createObject($class='',$param=array(),$array=array()){
		
		$self_class = '';
		if(empty(self::$selfClass)){
			self::$selfClass = new self();
			$self_class = self::$selfClass;
		}else{
			$self_class = self::$selfClass;
		}
		$object = $self_class->build($class,$param,$array);
		
		return $object;
		
		
		
	}
	
	
	private function build($class='',$param=array(),$array=array()){
		
		//获得反射对象，已经所需的构造函数
		list ($reflection, $dependencies) = $this->getDependencies($class);
		//var_dump($reflection);
		//var_dump($dependencies);
		//exit;
		
		foreach ($param as $index => $value) {
            $dependencies[$index] = $value;
        }
		
		
        $dependencies = $this->resolveDependencies($dependencies, $reflection);
        if (empty($array)) {
            return $reflection->newInstanceArgs($dependencies);
        }		
		
		
	}
	
    protected function resolveDependencies($dependencies, $reflection = null)
    {
        foreach ($dependencies as $index => $dependency) {
            if ($dependency instanceof Instance) {
                if ($dependency->id !== null) {
                    $dependencies[$index] = $this->get($dependency->id);
                } elseif ($reflection !== null) {
                    $name = $reflection->getConstructor()->getParameters()[$index]->getName();
                    $class = $reflection->getName();
                    throw new InvalidConfigException("Missing required parameter \"$name\" when instantiating \"$class\".");
                }
            }
        }
        return $dependencies;
    }	
	 
	
	
	/*
	 * 获得类的反射对象以及构造函数
	 * 
	 * */
	
	
    protected function getDependencies($class)
    {

        $dependencies = [];
		//获得反射类
        $reflection = new ReflectionClass($class);

		//获得反射的构造函数对象
        $constructor = $reflection->getConstructor();
		
		
        if ($constructor !== null) {
        	//获得函数参数反射对象
            foreach ($constructor->getParameters() as $param) {
            	//判断是否有默认值
                if ($param->isDefaultValueAvailable()) {
                	//取得默认值，顺序放入默认值
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();
                    $dependencies[] = $c === null ? null : $c->getName();
                }
            }
        }
		//保存
        $this->_reflections[$class] = $reflection;
        $this->_dependencies[$class] = $dependencies;
	
        return [$reflection, $dependencies];
    }
	
}



?>