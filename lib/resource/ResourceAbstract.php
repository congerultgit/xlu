<?php
namespace basexxl\lib\resource;

abstract class ResourceAbstract{
	
	public $resName = null;
	
	public $resType = null;
	
	abstract public function read($resName,$start,$end);
	
	abstract public function write($resName,$content);
	
	abstract public function baseInfo();
	
}



?>