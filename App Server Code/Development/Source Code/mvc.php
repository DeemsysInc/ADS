<?php 
global $config;
require_once 'smcfg.php';
global $getConfig;
require_once $srvRoot.'classes/config.class.php';
$objConfig = new cConfig();
$getConfig = $objConfig->config();
/*** include our class definitions ***/
require_once $getConfig['ABSOLUTEPATH'].'classes/pagebuilder.class.php';
$url = explode("/", $_SERVER["REQUEST_URI"]);
for($i=-1; $i<count($url); $i++){
	if(!empty($url[$i])){
	$arrayUrl[] = $url[$i];	
	}
}
print_r($arrayUrl);
$_SESSION['targetPage']=substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]));
/*** create a page builder object ***/
$objPageBuilder = new cPageBuilder();


$target = array('print', 'shorturl');

if(count(array_intersect($arrayUrl, $target))==0){
//if (!in_array("print", $arrayUrl) || !in_array("shorturl", $arrayUrl)) {	
    $objPageBuilder->pageHeader($arrayUrl);
}
	$objPageBuilder->pageContent($arrayUrl);
//$objPageBuilder->pageRight();
if(count(array_intersect($arrayUrl, $target))==0){
//if (!in_array("print", $arrayUrl) || !in_array("shorturl", $arrayUrl)) {	
	$objPageBuilder->pageFooter($arrayUrl);

}
?>