<?php
namespace xlu\lib\resource;

/**
操作文件资源
**/

class ResourceFactory extends ResourceAbstract{
		
	public function  __construct ($resName) {
       
	   $this->resName = $resName;
	   
	}
	
	public function read($resName,$start,$end){
		
		
	}
	
	public function write($resName,$content){
		
		
	}
	
	public function baseInfo(){
		
		
	}
	
	
	public  static function createObject($file,$fileinfo=null){
		
	}
	
	private  function factory($file,$fileinfo=null){
		
	}
	
	
	
	
	
}




?>