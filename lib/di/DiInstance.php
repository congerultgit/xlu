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
		
		
		
		
		
	}
	
	
	private function build($class='',$param=array(),$array=array()){
		
		
		list ($reflection, $dependencies) = $this->getDependencies($class);
		var_dump($refection);
		var_dump($dependencies);
		exit;
		
		
	} 
	
	

    protected function getDependencies($class)
    {

        $dependencies = [];
		echo $class;
		echo '<br>';
		//echo 'xlu\lib\resource\ResourceFactory';
		$reflection = new ReflectionClass('xlu\lib\resource\ResourceFactory');
		//var_dump($reflection);
		//$class = 'xlu\lib\resource\ResourceFactory';
        $reflection = new ReflectionClass($class);
		var_dump($reflection);
		exit;
		
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();
                    $dependencies[] = Instance::of($c === null ? null : $c->getName());
                }
            }
        }

        $this->_reflections[$class] = $reflection;
        $this->_dependencies[$class] = $dependencies;

        return [$reflection, $dependencies];
    }
	
}



?>