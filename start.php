<?php 
header("Content-type: text/html; charset=utf-8");
$baseurl =  'http://www.juzimi.com/';        
$url = 'http://www.juzimi.com/ju/1043516';
$content  =  file_get_contents ($url);
function s($a){
	echo '<pre>';
	var_dump($a);
	echo '</pre>';
}
//echo $content;
//当前内容
preg_match_all ('/id="xqtitle">(.*)<\/h1>/i',$content,$out ,  PREG_PATTERN_ORDER );
var_dump($out);
//取得页面内容
$content_text = $out[1][0];

//上下页面
preg_match_all ('/<a class="goto-previous-node" title=".*?" href="(\/ju\/[0-9]+?)">/i',$content,$out ,  PREG_PATTERN_ORDER );
var_dump($out);
$forurl = 'http://www.juzimi.com';
$pageup = $out[1][0];
$pagedown = $out[1][1];
//目标url
$nexturl = '';
if($pageup > $pagedown){
	$nexturl = $pageup;
}else{
	$nexturl = $pagedown;
}
$nexturl = $forurl.$nexturl;

//原作者
preg_match_all ('/title="原作者：(.*?)"/i',$content,$out ,  PREG_PATTERN_ORDER );
var_dump($out);
$content_author = $out[1][0];

//出自
preg_match_all ('/<a title="出自(.*?)"/i',$content,$out ,  PREG_PATTERN_ORDER );
var_dump($out);
$content_orig = $out[1][0];
$content_orig = str_replace(array("《","》"),'',$content_orig);

echo 'info</br>';
s($content_text);
s($nexturl);
s($content_author);
s($content_orig);


?>