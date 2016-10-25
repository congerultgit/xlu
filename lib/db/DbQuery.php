<?php
namespace xlu\lib\db;
use xlu;
use xlu\lib\base\BaseComponent;
use xlu\lib\base\BaseErrorException;
use PDO;
/**
 * 
 * DB查询生成器，暂时不做处理
 * 
 * SELECT  *
 * FROM (
 * 		SELECT tableField 
 * 		FROM tableName
 * 		ORDER BY createTime Desc
 * 		LIMIT 10
 * )
 * LIMIT 1
 * 
 * 
 */
class DbQuery extends  BaseComponent{
	
	
	
	
	
}

?>