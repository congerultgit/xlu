<?php
namespace xlu\lib\base;
use xlu;
class Baseobject{
	
	
		public function  __construct ($config =array()) {
						
			if (!empty($config)) {
            	xlu::configure($this, $config);
	        }
						
		}
		
	
}



?>