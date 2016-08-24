<?php
namespace basexxl\lib\base;
abstract class BaseFactoryAbstract extends  BaseObject{
	
	//共有创建对象
	public static function createObject($str,$arge);
	
	//私有真实创建对象
	protected  function factory($str,$arge);
	
	
}



?>