<?php
/*
 *不要做更多
 * 
 * */
require(__DIR__ . '/base.php');
class xlu extends xlu\base {
	
	
}
xlu::setNickname('xlu',XLUROOT);
spl_autoload_register('xlu::autoload',true, true);
xlu::$container = new xlu\lib\di\DiContainer();
?>