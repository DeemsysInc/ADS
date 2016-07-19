<?php
$adminFlag = 1;
global $getConfig;
require_once('../smcfg.php');

$url = explode("/", $_SERVER["REQUEST_URI"]);
//print_r($url);
for($i=0; $i<count($url); $i++){
	if(!empty($url[$i])){
		$arrayUrl[] = $url[$i];
	}
}
//session_start();
//require_once SRV_ROOT.'classes/config.class.php';
//$objConfig = new cConfig();
//$getConfig = $objConfig->config();

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

//echo $_SERVER["REQUEST_URI"];
/*** create a page builder object ***/

$objPageBuilder = new cPageBuilder();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id']!=''){
	$objPageBuilder->pageHeader();
}

$objPageBuilder->pageContent($arrayUrl);
//$objPageBuilder->pageRight();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id']!=''){
	$objPageBuilder->pageFooter();
}


?>