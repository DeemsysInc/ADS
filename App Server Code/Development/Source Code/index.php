<?php 
require_once 'smcfg.php';
global $config;

/*** include our class definitions ***/
require_once $config['ABSOLUTEPATH'].'classes/pagebuilder.class.php';

/*** Autoload class files ***/
/* function __autoload($class){
  require('classes/' . strtolower($class) . '.class.php');
} */

$arrayUrl = array();
$url = explode("/", $_SERVER["REQUEST_URI"]);
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
$objPageBuilder->pageFooter($arrayUrl);

?>