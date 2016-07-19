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

$config['database']['prefix'] = ''; 
$config['database']['name'] = 'devarapp_cms';
$config['database']['host'] = 'localhost';
$config['database']['user'] = 'devarapp_dbuser';
$config['database']['password'] = 'D@vArDpU$er@2015';

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
$config['files']['models']=$config['LIVE_URL'].'files/clients/{client_id}/models/';



//absolute folders

$config['root_files']['logo'] = SRV_ROOT.'files/clients/{client_id}/logo/';
$config['root_files']['background'] = SRV_ROOT.'files/clients/{client_id}/background/';
$config['root_files']['triggers'] = SRV_ROOT.'files/clients/{client_id}/triggers/';
$config['root_files']['products'] = SRV_ROOT.'files/clients/{client_id}/products/';
$config['root_files']['additional']=SRV_ROOT.'files/clients/{client_id}/additional/';
$config['root_files']['videos']=SRV_ROOT.'files/clients/{client_id}/videos/';
$config['root_files']['models']=SRV_ROOT.'files/clients/{client_id}/models/';


//trash folders
$config['trash_files']['logo'] = SRV_ROOT.'files/trash/{client_id}/logo/';
$config['trash_files']['background'] = SRV_ROOT.'files/trash/{client_id}/background/';
$config['trash_files']['triggers'] = SRV_ROOT.'files/trash/{client_id}/triggers/';
$config['trash_files']['products'] = SRV_ROOT.'files/trash/{client_id}/products/';
$config['trash_files']['additional']=SRV_ROOT.'files/trash/{client_id}/additional/';
$config['trash_files']['videos']=SRV_ROOT.'files/trash/{client_id}/videos/';
$config['trash_files']['models']=SRV_ROOT.'files/trash/{client_id}/models/';




?>