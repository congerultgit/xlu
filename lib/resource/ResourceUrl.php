<?php
namespace xlu\lib\resource;

/**
操作文件资源
**/

class ResourceUrl extends ResourceAbstract{
		
	public function  __construct ($resName) {
       
	   $this->resName = $resName;
	   
	}
	
	public 	function read($file,$start,$end){
		
		
	}
	
	public function write($filename,$content){
		
	}
	
	public function baseInfo(){
		
		
	}
	
	
}




?>