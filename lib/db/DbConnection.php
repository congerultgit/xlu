<?php
namespace xlu\lib\db;
use xlu;
use xlu\lib\base\BaseComponent;
use xlu\lib\base\BaseErrorException;
/*
 * 
 * 处理DB的连接类
 * 
 * 
 * 
 * 
 */
class DbConnection extends  BaseComponent{
	

	public $dsn = '';
	
	public $username = '';
	
	public $password = '';
	
	//PDO配置信息
	public $attributes = '';
	
	//保存数据库连接实例
	private $_pdo = null;
	
	public $master = null;
	
	//默认字符集
	public $charset = 'utf8';
		
	//POD类名
	public $pdoClass = '';
	
	//伪预处理 应该为false
	public $emulatePrepare;
	
	//
	private $_schema = '';
	
	//?
	public $_transaction;

	
	//
    public $schemaMap = [
        'pgsql' => 'xlu\lib\db\pgsql\Schema', // PostgreSQL
        'mysqli' => 'xlu\lib\db\mysql\MysqlSchema', // MySQL
        'mysql' => 'xlu\lib\db\mysql\MysqlSchema', // MySQL
        'sqlite' => 'xlu\lib\db\sqlite\Schema', // sqlite 3
        'sqlite2' => 'xlu\lib\db\sqlite\Schema', // sqlite 2
        'sqlsrv' => 'xlu\lib\db\mssql\Schema', // newer MSSQL driver on MS Windows hosts
        'oci' => 'xlu\lib\db\oci\Schema', // Oracle driver
        'mssql' => 'xlu\lib\db\mssql\Schema', // older MSSQL driver on MS Windows hosts
        'dblib' => 'xlu\lib\db\mssql\Schema', // dblib drivers on GNU/Linux (and maybe other OSes) hosts
        'cubrid' => 'xlu\lib\db\cubrid\Schema', // CUBRID
    ];	

	
	//创建连接
	private function open(){
		
		if($this->_pdo !== null){
			
			return $this->_pdo;
			
		}
		
		//主从
		if(!empty($this->master)){
			
		}
		
        if (empty($this->dsn)) {
            throw new BaseErrorException('DB dsn is null.');
        }		
		try{
			$this->_pdo = $this->createPdoInstance();
			//配置
	        $this->initConnection();
		}catch( BaseErrorException $e ){
			echo $e->getMessage();
			exit;
		}
		
		
	}
	
    public function getSlavePdo($fallbackToMaster = true)
    {
        $db = $this->getSlave(false);
        if ($db === null) {
            return $fallbackToMaster ? $this->getMasterPdo() : null;
        } else {
            return $db->pdo;
        }
    }

    public function getMasterPdo()
    {
        $this->open();
        return $this->pdo;
    }
	
	//真实创建连接,由不同的devices选用不同的PDO类
    protected function createPdoInstance(){
        $pdoClass = $this->pdoClass;
        if ($pdoClass === null) {
            $pdoClass = 'PDO';
            if ($this->_driverName !== null) {
                $driver = $this->_driverName;
            } elseif (($pos = strpos($this->dsn, ':')) !== false) {
                $driver = strtolower(substr($this->dsn, 0, $pos));
            }
            if (isset($driver) && ($driver === 'mssql' || $driver === 'dblib' || $driver === 'sqlsrv')) {
                $pdoClass = 'xlu\db\mssql\PDO';
            }
			$this->pdoClass = $pdoClass;
        }

        return new $pdoClass($this->dsn, $this->username, $this->password, $this->attributes);
    }
	
    protected function initConnection()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($this->emulatePrepare !== null && constant('PDO::ATTR_EMULATE_PREPARES')) {
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, $this->emulatePrepare);
        }
        if ($this->charset !== null && in_array($this->getDriverName(), ['pgsql', 'mysql', 'mysqli', 'cubrid'])) {
            $this->pdo->exec('SET NAMES ' . $this->pdo->quote($this->charset));
        }
        //$this->trigger(self::EVENT_AFTER_OPEN);
    }   		
	
	/*
	 * 执行SQL
	 * 
	 * */
    public function createCommand($sql = null, $params = [])
    {
        $command = new DbCommand([
            'db' => $this,
            'sql' => $sql,
        ]);

        return $command->bindValues($params);
    }
	
	//获得设备名称
    public function getDriverName()
    {
        if ($this->_driverName === null) {
            if (($pos = strpos($this->dsn, ':')) !== false) {
                $this->_driverName = strtolower(substr($this->dsn, 0, $pos));
            } else {
                $this->_driverName = strtolower($this->getSlavePdo()->getAttribute(PDO::ATTR_DRIVER_NAME));
            }
        }
        return $this->_driverName;
    }
	
	/*
	 * 
	 * 
	 * 
	 * */
    public function getSchema()
    {
        if ($this->_schema !== null) {
            return $this->_schema;
        } else {
            $driver = $this->getDriverName();
            if (isset($this->schemaMap[$driver])) {
                $config = !is_array($this->schemaMap[$driver]) ? ['class' => $this->schemaMap[$driver]] : $this->schemaMap[$driver];
                $config['db'] = $this;

                return $this->_schema = Yii::createObject($config);
            } else {
                throw new NotSupportedException("Connection does not support reading schema information for '$driver' DBMS.");
            }
        }
    }
	
    public function quoteSql($sql)
    {
        return preg_replace_callback(
            '/(\\{\\{(%?[\w\-\. ]+%?)\\}\\}|\\[\\[([\w\-\. ]+)\\]\\])/',
            function ($matches) {
                if (isset($matches[3])) {
                    return $this->quoteColumnName($matches[3]);
                } else {
                    return str_replace('%', $this->tablePrefix, $this->quoteTableName($matches[2]));
                }
            },
            $sql
        );
    }

    public function quoteColumnName($name)
    {
        return $this->getSchema()->quoteColumnName($name);
    }
	
    public function getTransaction()
    {
        return $this->_transaction && $this->_transaction->getIsActive() ? $this->_transaction : null;
    }	
	
}



?>