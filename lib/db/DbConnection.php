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
	
	public $attributes = '';
	
	//保存数据库连接实例
	private $_pdo = null;
	
	public $master = null;
		
	//POD类名
	public $pdoClass = '';
	
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
		
		$this->_pdo = $this->createPdoInstance();
		//配置 参数
        $this->initConnection();
		
		
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
                $pdoClass = 'yii\db\mssql\PDO';
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
        $this->trigger(self::EVENT_AFTER_OPEN);
    }		
	
}



?>