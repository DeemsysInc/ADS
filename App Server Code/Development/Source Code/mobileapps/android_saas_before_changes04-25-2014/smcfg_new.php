<?php ini_set('display_errors', '1');
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

//cpanel db connections-----------------
$config['database']['prefix'] = 'devarapp_'; 
$config['database']['name'] = $config['database']['prefix'].'masters';
$config['database']['host'] = 'localhost';
$config['database']['user'] = 'devarapp';
$config['database']['password'] = 'D@vArapp@2014';

//markers db connection--------------------
$config['database']['prefix_markers'] = 'devarapp_'; 
$config['database']['name_markers'] = $config['database']['prefix'].'markers';
$config['database']['host_markers'] = 'localhost';
$config['database']['user_markers'] = 'devarapp';
$config['database']['password_markers'] = 'D@vArapp@2014';

//users db connection--------------------
$config['database']['prefix_users'] = 'devarapp_'; 
$config['database']['name_users'] = $config['database']['prefix'].'users';
$config['database']['host_users'] = 'localhost';
$config['database']['user_users'] = 'devarapp';
$config['database']['password_users'] = 'D@vArapp@2014';

//user_analytics db connections-----------------------
$config['database']['prefix_user_analytics'] = 'devarapp_'; 
$config['database']['name_user_analytics'] = $config['database']['prefix'].'user_analytics';
$config['database']['host_user_analytics'] = 'localhost';
$config['database']['user_user_analytics'] = 'devarapp';
$config['database']['password_user_analytics'] = 'D@vArapp@2014';

//client db connections-----------------------
//$config['database']['prefix_clients'] = 'devarapp_'; 
$config['database']['name_clients'] = '';
$config['database']['host_clients'] = 'localhost';
$config['database']['user_clients'] = 'devarapp';
$config['database']['password_clients'] = 'D@vArapp@2014';

$config['LIVE_URL'] = 'http://'.$_SERVER['HTTP_HOST'].'/';
$config['ABSOLUTEPATH'] = $srvRoot;
$config['FROM_EMAIL'] = "do-not-reply@seemoreinteractive.com";
//Default Values
if(!defined('SRV_ROOT')) {
 define("SRV_ROOT",$config['ABSOLUTEPATH']);
}
$config['pg_database']['prefix'] = ''; 
$config['pg_database']['name'] = 'local-seemore-dev';
$config['pg_database']['host'] = 'localhost';
$config['pg_database']['user'] = 'postgres';
$config['pg_database']['password'] = 'postgres';
$config['pg_database']['port'] = '5432';
$config['pg_database']['sslmode'] = '';
$config['files']['logo'] = $config['LIVE_URL'].'files/clients/{client_id}/logo/';
$config['files']['background'] = $config['LIVE_URL'].'files/clients/{client_id}/background/';
$config['files']['triggers'] = $config['LIVE_URL'].'files/clients/{client_id}/triggers/';
$config['files']['products'] = $config['LIVE_URL'].'files/clients/{client_id}/products/';
$config['files']['additional']=$config['LIVE_URL'].'files/clients/{client_id}/additional/';
$config['files']['videos']=$config['LIVE_URL'].'files/clients/{client_id}/videos/';
?>