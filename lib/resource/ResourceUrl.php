<?php
namespace xlu\lib\resource;
use xlu;
use xlu\lib\base as lb;
/**
操作文件资源
**/

class ResourceUrl extends lb\BaseObject implements  ResourceInterface{
		
	public function  __construct ($resName) {
       
	   $this->resName = $resName;
	   
	}
	
	public 	function read($file='',$start='',$end=''){
		
		
	}
	
	public function write($filename,$content){
		
	}
	
	public function baseInfo(){
		
		
	}
	
	
}




?>