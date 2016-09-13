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
	
	public $object = '';
	/**
	 * 初始化参数由一个数组控制
	 * $param 
	 * $param['res_name']
	 * $param['res_type']
	 * 
	 */
		
	public function  __construct ($param = array('res_name'=>'','res_type'=>'url')) {

		$res_type = ucfirst(strtolower($param['res_type']));
		$this->res_name = $param['res_name'];
		$this->res_type = $res_type;

     	if(empty($param['res_name']) || empty($param['res_type']))throw new lb\BaseErrorException( __CLASS__.' construct initialization failed');	
		
		if(array_search($res_type, $this->res_allow_type) === false)throw new lb\BaseErrorException( __CLASS__.' resource type not support');	
		


		if($this->object){
			return $this->object;
		}
		
		$for_class = 'Resource'.$res_type;
		
		$object = xlu::object(__NAMESPACE__.'\\'.$for_class,array($this->res_name));
	
		$this->object = $object;
	
		return $object;   
	   
	   
	}
	
	
	public  function getObject(){

	
		return $this->object;  

		
	}
	
	private  function factory($file,$fileinfo=null){
		
	}
	
	
	
	
	
}




?>