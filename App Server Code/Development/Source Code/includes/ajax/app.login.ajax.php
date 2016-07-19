<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/app.login.class.php');
$objLogin= new cAppLogin();
$action = $_REQUEST['action'];

if($action == "doLogin"){
	$objLogin->getAccessWithAppLoginDetails();
}
if($action == "doLogout"){
	$objLogin->logout();
}
?>