<?php
namespace xlu\lib\db;
use xlu;
use xlu\lib\base\BaseErrorException;
use xlu\lib\base\BaseComponent;
/*
 * 执行SQL，和DbConnection合作
 * PDO主要在这里完成执行
 * 
 * 
 * 
 * */
 
 class DbCommand extends  BaseComponent{
 	
	public $db = '';
	
	public $sql = '';
	
	//
	public $params = array();
	
	//
	private $_pendingParams = array();
	
	/*
	 * 
	 * 
	 * 
	 * */
    public function bindValues($values)
    {
        if (empty($values)) {
            return $this;
        }

        $schema = $this->db->getSchema();
        foreach ($values as $name => $value) {
            if (is_array($value)) {
                $this->_pendingParams[$name] = $value;
                $this->params[$name] = $value[0];
            } else {
                $type = $schema->getPdoType($value);
                $this->_pendingParams[$name] = [$value, $type];
                $this->params[$name] = $value;
            }
        }

        return $this;
    }	
	
	
	
 }	
	
	
?>