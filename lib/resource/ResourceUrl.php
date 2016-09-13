<?php
namespace xlu\lib\resource;
use xlu;
use xlu\lib\base as lb;
/**
操作文件资源
**/

class ResourceUrl extends lb\BaseObject implements  ResourceInterface{

	public $resName = '';
		
	public function  __construct ($resName) {
       
	   $this->resName = $resName;
	   
	}
	
	public 	function read($start='',$size=''){
		
		$tmp_start = '';
		$tmp_size = '';
		$tmp_url = $this->resName;

		if($start ==''&&$size !=''){
			$tmp_start = 0;
			$tmp_size = $size;
			$content = file_get_contents($tmp_url);
		}		
		if($start ==''&&$size ==''){
			$content = file_get_contents($tmp_url);
		}

		if($start !=''&&$size ==''){
			$tmp_start = $start;
			$content = file_get_contents($tmp_url,null,null,$tmp_start);
		}		
		
		if($start !=''&&$size !=''){
			$tmp_start = $start;
			$tmp_size = $size;
			$content = file_get_contents($tmp_url,null,null,$tmp_start,$tmp_size);
		}	
		
		return $content;
		
	}
	
	public function write($filename,$content){
		
	}
	
	public function baseInfo(){
		
		
	}
	
	
}




?>