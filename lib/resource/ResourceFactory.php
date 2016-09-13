<?php
namespace xlu\lib\resource;
use xlu;
use xlu\lib\base as lb;
/**
操作文件资源
**/

class ResourceFactory extends lb\BaseObject{
		
	public $resName = '';
	
	public $type = '';
	
	public $res_type = array('Url','File');
	
	

		
	public function  __construct ($resname='',$type='url') {
		
     	if(empty($resname) || empty($type))throw new lb\BaseErrorException( __CLASS__.' construct initialization failed');



		$res_type = ucfirst(strtolower($type));
				
		if(array_search($res_type, $this->res_type) === false)throw new lb\BaseErrorException( __CLASS__.' resource type not support');	

		$this->resName = $resname;
		$this->type = $type;

		
		$for_class = 'Resource'.$res_type;
		
		$object = xlu::object(__NAMESPACE__.'\\'.$for_class);
	
		return $object;   
	   
	   
	}
	
	
	public  static function createObject($file,$fileinfo=null){
		
	}
	
	private  function factory($file,$fileinfo=null){
		
	}
	
	
	
	
	
}




?>