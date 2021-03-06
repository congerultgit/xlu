<?php
namespace xlu\lib\db;
use xlu;
use xlu\lib\base\BaseComponent;
use xlu\lib\base\BaseErrorException;
use PDO;
/*
 * 
 * 处理DB的连接类
 * 
 * 
 * 
 * 
 */
class DbConnection extends  BaseComponent{
	
   /**
	* 数据库连接类型
	*@var string   mysql:host=127.0.0.1;dbname=test 
	* 
	*/
	public $dsn = '';

   /**
	*设备名称   目录区分，不同类型特殊对待
	*@var string mysql
	*/
		
	private $_driverName = null;

   /**
	*用户名 
	* @var string
	*/
	public $username = '';

   /**
	*密码
	*@var string 
	*/	
	public $password = '';


	
	/**
	 *pdo链接时的配置信息
	 *@var string
	 */	
	public $attributes = array();

	
	/**
	 * pdo()函数创建的实例
	 * @var object 
	 */
	public $pdo = null;

	/**
	 *主库
	 * @var array 
	 */	
	public $master = null;

	
	/**
	 *默认字符集 
	 * @var string
	 */
	public $charset = 'utf8';
		
	/**
	 *初始化的类名 
	 * @var string
	 * 
	 */
	public $pdoClass = null;
	

	/**
	 * 启用或禁用预处理语句的模拟。 有些驱动不支持或有限度地支持本地预处理。使用此设置强制PDO总是模拟预处理语句（如果为 TRUE  ），
	 * 或试着使用本地预处理语句（如果为 FALSE ）。如果驱动不能成功预处理当前查询，它将总是回到模拟预处理语句上。 需要 bool  类型。
	 * @var bool
	 */
	public $emulatePrepare = false;
	
	//
	private $_schema = '';
	
	//
	public $_transaction;

	
	/**
	 * 
	 * 
	 * 
	 */
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

	
	/**
	 * 调用此函数创建链接
	 * 
	 * 
	 */
	private function open(){
		
		if($this->pdo !== null){			
			return $this->pdo;			
		}
		
		//主从
		if(!empty($this->master)){
			
		}
		
        if (empty($this->dsn)) {
            throw new BaseErrorException('DB dsn is null.');
        }
        		
		try{
			//创建PDO对象
			$this->pdo = $this->createPdo();
			//配置相关设置
	        $this->initConnection();
		}catch( BaseErrorException $e ){
			echo $e->getMessage();
			exit;
		}
		
		
	}
	
	
	/**
	 * 
	 * 
	 * 
	 */
    public function getSlavePdo($fallbackToMaster = true)
    {
        $db = $this->getSlave(false);
        if ($db === null) {
            return $fallbackToMaster ? $this->getMasterPdo() : null;
        } else {
            return $db->pdo;
        }
    }

	/**
	 * 对外使用,封装后的open函数
	 * 
	 * */
    public function getMasterPdo()
    {
        $this->open();
        return $this->pdo;
    }
	
	/**
	 * 创建pdo对象 
	 * 
	 */
    protected function createPdo(){
        $pdoClass = $this->pdoClass;
        if ($pdoClass === null) {       	
            $pdoClass = 'PDO';
            if ($this->_driverName !== null) {
                $driver = $this->_driverName;
            } elseif (($pos = strpos($this->dsn, ':')) !== false) {
                $driver = strtolower(substr($this->dsn, 0, $pos));
            }
			$this->pdoClass = $pdoClass;
        }
        return new $pdoClass($this->dsn, $this->username, $this->password, $this->attributes);
    }

	/**
	 *初始化配置信息  
	 */	
    protected function initConnection()
    {
        //设置PDO异常模式
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //PDO模拟prepares
        if ($this->emulatePrepare !== null && constant('PDO::ATTR_EMULATE_PREPARES')) {
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, $this->emulatePrepare);
        }
        //mysql字符集处理
        if ($this->charset !== null && in_array($this->getDriverName(), ['pgsql', 'mysql', 'mysqli', 'cubrid'])) {
            $this->pdo->exec('SET NAMES ' . $this->pdo->quote($this->charset));
        }
    }   		
	
	/**
	 *创建命令对象 
	 * 
	 * 
	 */
    public function createCommand($sql = null, $params = [])
    {
        $command = new DbCommand([
            'db' => $this,
            'sql' => $sql,
        ]);

        return $command->bindValues($params);
    }
	
	
	/**
	 * 不同过PDO直接执行SQL
	 * 
	 */
	public function execSql($sql){
		
		return $this->getMasterPdo()->query($sql);
			
	}
	
	/**
	 *根据dsn名称获取数据库类型 如mysql，orale
	 */
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
	
	/**
	 *根据数据库类型取得数据库概要 
	 */
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
	
	/**
	 *处理SQL 
	 * 
	 * 
	 */
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

	/**
	 * 
	 */
    public function quoteColumnName($name)
    {
        return $this->getSchema()->quoteColumnName($name);
    }
	
	/**
	 * 
	 * 
	 * 
	 */
    public function getTransaction()
    {
        return $this->_transaction && $this->_transaction->getIsActive() ? $this->_transaction : null;
    }	
	
}



?>