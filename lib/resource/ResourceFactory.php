<?php
namespace xlu\lib\resource;

/**
操作文件资源
**/

class ResourceFactory extends ResourceAbstract{
		
	public $resName = '';
	
	const RES_TYPE_URL = 'url';
	
	const RES_TYPE_FILE = 'file';
	
	const RES_TYPE_RES = 'res';
	
	
		
	public function  __construct ($resname='',$type="url") {
       
	   
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