<?php 
global $config;

require_once 'smcfg.php';

global $getConfig;

require_once $srvRoot.'classes/config.class.php';
$objConfig = new cConfig();
$getConfig = $objConfig->config();

/*** include our class definitions ***/
//require_once $getConfig['ABSOLUTEPATH'].'classes/pagebuilder.class.php';
if($config['server_type']=="1" || $config['server_type']=="4")
{

   require_once $config['ABSOLUTEPATH'].'/classes/pagebuilder.class.php';	
}
else
{
   require_once $config['ABSOLUTEPATH'].'/classes/pagebuilder.class_live.php';	
}

$url = explode("/", $_SERVER["REQUEST_URI"]);

for($i=0; $i<count($url); $i++){
	if(!empty($url[$i])){
		$arrayUrl[] = $url[$i];
	}
}
$_SESSION['targetPage']=substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]));;

/*** create a page builder object ***/
$objPageBuilder = new cPageBuilder();
if (!in_array("print", $arrayUrl)) {
	$objPageBuilder->pageHeader($arrayUrl);
}
$objPageBuilder->pageContent($arrayUrl);
//$objPageBuilder->pageRight();
if (!in_array("print", $arrayUrl)) {
	$objPageBuilder->pageFooter($arrayUrl);
}



?>