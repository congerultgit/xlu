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
 	
 	
 	/**
 	 *db存储dbconnection对象 
 	 *@var object 
 	 */
	public $db = '';
	
	/**
	 *执行的SQL
	 * @var string 
	 */
	private $_sql = '';
	
	//
	public $params = array();
	
	//
	private $_pendingParams = array();
	
	/**
	 *pdo产生的预处理对象 
	 * @var object
	 */
	public $pdoStatement;


	/**
	 *绑定变量 
	 * 
	 */
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
	
	/**
	 *查询获取所有数据 
	 */
    public function queryAll($fetchMode = null)
    {
        return $this->queryInternal('fetchAll', $fetchMode);
    }
    
    
    /**
     * 获取一条数据
     * */
     public function queryOne($fetchMode = null)
    {
        return $this->queryInternal('fetch', $fetchMode);
    }   
	
	/**
	 * 获得SQL
	 */
    public function getRawSql()
    {
        if (empty($this->params)) {
            return $this->_sql;
        }
        $params = [];
        foreach ($this->params as $name => $value) {
            if (is_string($value)) {
                $params[$name] = $this->db->quoteValue($value);
            } elseif ($value === null) {
                $params[$name] = 'NULL';
            } elseif (!is_object($value) && !is_resource($value)) {
                $params[$name] = $value;
            }
        }
        if (!isset($params[1])) {
            return strtr($this->_sql, $params);
        }
        $sql = '';
        foreach (explode('?', $this->_sql) as $i => $part) {
            $sql .= (isset($params[$i]) ? $params[$i] : '') . $part;
        }

        return $sql;
    }
	
    /**
     * 
     * component 魔术方法调用
     */
    public function setSql($sql)
    {
        if ($sql !== $this->_sql) {
            //$this->cancel();
            $this->_sql = $this->db->quoteSql($sql);
            $this->_pendingParams = [];
            $this->params = [];
        }

        return $this;
    }
	
	/**
	 *设置SQL 
	 */
	public function getSql(){
		return $this->_sql;
	}	
	
	/**
	 *核心查询函数
	 * 
	 * */
    protected function queryInternal($method, $fetchMode = null){
        $rawSql = $this->getRawSql();

        $this->prepare(true);

        $token = $rawSql;
        try {
           

            $this->pdoStatement->execute();

  
	        if ($fetchMode === null) {
	            $fetchMode = $this->fetchMode;
	        }
	        $result = call_user_func_array([$this->pdoStatement, $method], (array) $fetchMode);
	        $this->pdoStatement->closeCursor();


        } catch (\Exception $e) {
            throw new BaseErrorException(' sql:'.$rawSql.' exec error');
        }


        return $result;
    }	
	
	/**
	 * 这里才会调用dbconnection的方法创建pdo对象
	 * 
	 * 
	 */
    public function prepare($forRead = null){
        if ($this->pdoStatement) {
            $this->bindPendingParams();
            return;
        }

        $sql = $this->getSql();

//      if ($this->db->getTransaction()) {
//          // master is in a transaction. use the same connection.
//          $forRead = false;
//      }

//      if ($forRead || $forRead === null && $this->db->getSchema()->isReadQuery($sql)) {
//          $pdo = $this->db->getSlavePdo();
//      } else {
//          $pdo = $this->db->getMasterPdo();
//      }
		//实例化PDO对象
		$pdo = $this->db->getMasterPdo();
        try {
            $this->pdoStatement = $pdo->prepare($sql);
            $this->bindPendingParams();
        } catch (\Exception $e) {
            $message = $e->getMessage() . "\nFailed to prepare SQL: $sql";
		    throw new BaseErrorException($message);
        }
    }
	
	
    protected function bindPendingParams(){
        foreach ($this->_pendingParams as $name => $value) {
            $this->pdoStatement->bindValue($name, $value[0], $value[1]);
        }
        $this->_pendingParams = [];
    }
	
    public function execute(){
        $sql = $this->getSql();

        $rawSql = $this->getRawSql();


        if ($sql == '') {
            return 0;
        }

        $this->prepare(false);

        $token = $rawSql;
        try {
            $this->pdoStatement->execute();
            $n = $this->pdoStatement->rowCount();
            return $n;
        } catch (\Exception $e) {
            $message = $e->getMessage() . "\nFailed to exec SQL: $sql";
		    throw new BaseErrorException($message);
        }
    }	
	
	
 }	
	
	
?>