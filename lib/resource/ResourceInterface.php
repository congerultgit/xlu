<?php
namespace basexxl\lib\resource;

interface ResourceInterface{
	
	public function read($resName,$start,$end);
	
	public function write($resName,$content);
	
	public function baseInfo();
	
}



?>