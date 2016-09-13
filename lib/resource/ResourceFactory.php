<?php
namespace xlu\lib\resource;
use xlu;
use xlu\lib\base as lb;
/**
操作文件资源
**/

class ResourceFactory extends lb\BaseObject{
		
	public $res_name = '';
	
	public $res_type = '';
	
	public $res_allow_type = array('Url','File');
	
	
	/**
	 * 
	 * $param 
	 * $param['res_name']
	 * $param['res_type']
	 * 
	 */
		
	public function  __construct ($param = array()) {

     	if(empty($param['res_name']) || empty($param['res_type']))throw new lb\BaseErrorException( __CLASS__.' construct initialization failed');



		$res_type = ucfirst(strtolower($param['res_type']));
				
		if(array_search($res_type, $this->res_allow_type) === false)throw new lb\BaseErrorException( __CLASS__.' resource type not support');	

		$this->resName = $param['res_name'];
		$this->type = $param['res_type'];

		
		$for_class = 'Resource'.$res_type;
		
		$object = xlu::object(__NAMESPACE__.'\\'.$for_class);
	
		echo 123;var_dump($object);
		return $object;   
	   
	   
	}
	
	
	public  static function createObject($file,$fileinfo=null){
		
	}
	
	private  function factory($file,$fileinfo=null){
		
	}
	
	
	
	
	
}




?>