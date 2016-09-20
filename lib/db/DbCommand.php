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
	
	private $_sql = '';
	
	//
	public $params = array();
	
	//
	private $_pendingParams = array();
	
	//预处理对象
	public $pdoStatement;
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
	
    public function queryAll($fetchMode = null)
    {
        return $this->queryInternal('fetchAll', $fetchMode);
    }
	
	
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
	
	//component 魔术方法调用
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
	
	public function getSql(){
		return $this->_sql;
	}	
	
	/*
	 * 内部查询调用
	 * 
	 * */
    protected function queryInternal($method, $fetchMode = null){
        $rawSql = $this->getRawSql();

//      if ($method !== '') {
//          $info = $this->db->getQueryCacheInfo($this->queryCacheDuration, $this->queryCacheDependency);
//          if (is_array($info)) {
//              /* @var $cache \yii\caching\Cache */
//              $cache = $info[0];
//              $cacheKey = [
//                  __CLASS__,
//                  $method,
//                  $fetchMode,
//                  $this->db->dsn,
//                  $this->db->username,
//                  $rawSql,
//              ];
//              $result = $cache->get($cacheKey);
//              if (is_array($result) && isset($result[0])) {
//                  Yii::trace('Query result served from cache', 'yii\db\Command::query');
//                  return $result[0];
//              }
//          }
//      }

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
	
	
 }	
	
	
?>