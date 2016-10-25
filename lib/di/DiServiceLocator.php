<?php
namespace xlu\lib\di;
use xlu;
use xlu\lib\base\BaseComponent;
use xlu\lib\base\BaseErrorException;
/** 
 * service locator 
 * 
 * 
 */
class DiServiceLocator extends  BaseComponent{

	/**
	 * save component config
	 */ 
	protected $_define = array();
	
	/**
	 *save component instance
	 * 
	 */
	protected $_components = array();
	
	
	/**
	 * set component name  and component config
	 * 
	 */
	public  function set($id,$define){
		
		if(isset($this->_define[$id]) == false){
			$this->_define[$id] = $defind;
		}

	}
	
	/**
	 * get component instance
	 * if component not set throw exception 
	 * if component is not be  instance ,now  initialize it，return 
	 * 
	 */
	public function get($id){
		
		if(isset($this->_components[$id])){
			return $this->_components[$id];
		}		
		if(isset($this->_components[$id]) == false)throw new BaseErrorException("component:".$id." not defined");	
		
		$this->_components[$id] = xlu::object($this->_define[$id]);
		
		return $this->_components[$id];
			
	} 
	
	
	
	/**
	 * batch set components
	 */
	public  function setComponents($components){
		
		foreach ($components  as $id => $define) {
			$this->set($id,$define);			
		}	
		
	}
	
	
	
	
}



?>