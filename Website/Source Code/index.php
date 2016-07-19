<?php
require_once 'smcfg.php';
global $config;
$uri = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);

/*** include our class definitions ***/
if($config['server_type']=="1" || $config['server_type']=="4")
{

   require_once $config['ABSOLUTEPATH'].'/classes/pagebuilder.class.php';	
}
else
{
   require_once $config['ABSOLUTEPATH'].'/classes/pagebuilder.class_live.php';	
}

/*** Autoload class files ***/
/* function __autoload($class){
  require('classes/' . strtolower($class) . '.class.php');
} */

$arrayUrl = array();
$url = explode("/", $uri);
for($i=0; $i<count($url); $i++){
	if(!empty($url[$i])){
		$arrayUrl[] = $url[$i];
	}
}
/*** create a page builder object ***/
$objPageBuilder = "";
$objPageBuilder = new cPageBuilder();

$objPageBuilder->pageHeader($arrayUrl);
$objPageBuilder->pageContent($arrayUrl);
//$objPageBuilder->pageRight();
$objPageBuilder->pageFooter();

?>