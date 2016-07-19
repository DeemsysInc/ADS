<?php 
ob_start();
ini_set('display_errors', '1');
session_start();
// Place this code in your index.php file
//require($_SERVER['DOCUMENT_ROOT']."/plugins/php-error-log/index.php");

// Database
global $config, $_SESSION;

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$arrPath = pathinfo($thisFile);
$fileNamePath = $arrPath['basename'];
$webRoot  = str_replace(array($docRoot, $fileNamePath), '', $thisFile);
$srvRoot  = str_replace($fileNamePath, '', $thisFile);

date_default_timezone_set('America/Denver');

$server_type=3;//1=localhost ,2= beta ,3 =live,4=mac localhost

if($server_type==1)
{
$config['database']['prefix'] = ''; 
$config['database']['name'] = 'seemore';
$config['database']['host'] = 'localhost';
$config['database']['user'] = 'root';
$config['database']['password'] = '';
$config['LIVE_URL'] = 'http://'.$_SERVER['HTTP_HOST'].'/seemore_latest/';

}
else if($server_type==2)
{
$config['database']['prefix'] = ''; 
$config['database']['name'] = 'seemore_beta';
$config['database']['host'] = 'localhost';
$config['database']['user'] = 'smbetadbuser';
$config['database']['password'] = 'SmB@taDpU$er2013';

$config['LIVE_URL'] = 'http://'.$_SERVER['HTTP_HOST'].'/';

}
else if($server_type==3)
{
$config['database']['prefix'] = ''; 
$config['database']['name'] = 'seemore_site';
$config['database']['host'] = 'localhost';
$config['database']['user'] = 'seemore_dbuser';
$config['database']['password'] = 'SmDpU$er@2015';

$config['LIVE_URL'] = 'http://'.$_SERVER['HTTP_HOST'].'/';

}
else if($server_type==4)
{
$config['database']['prefix'] = ''; 
$config['database']['name'] = 'seemore';
$config['database']['host'] = 'localhost';
$config['database']['user'] = 'root';
$config['database']['password'] = 'root';
$config['LIVE_URL'] = 'http://'.$_SERVER['HTTP_HOST'].'/mvcseemore/';

}

$config['server_type']=$server_type;
if(isset($adminFlag)){
	$srvRoot = $srvRoot.'admin/';
	$config['LIVE_ADMIN_URL'] = $config['LIVE_URL'].'admin/';
	$config['ABSOLUTEPATH'] = $srvRoot;
} else {
	$config['ABSOLUTEPATH'] = $srvRoot;
}


$config['FROM_EMAIL'] = "web_contact@seemoreinteractive.com";
$config['FROM_EMAIL2'] = "info@seemoreinteractive.com";

//$config['FROM_EMAIL'] = "meher.e@digitalimperia.com";
//Default Values
if(!defined('SRV_ROOT')) {
 define("SRV_ROOT",$config['ABSOLUTEPATH']);
}



?>