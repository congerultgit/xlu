<?php
namespace basexxl\lib\resource;

abstract class ResourceAbstract{
	
	public $resName = null;
	
	public $resType = null;
	
	public function read($resName,$start,$end);
	
	public function write($resName,$content);
	
	public function baseInfo();
	
}



?>