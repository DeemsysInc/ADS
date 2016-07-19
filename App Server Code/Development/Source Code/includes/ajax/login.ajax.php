<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/login.class.php');
$objLogin= new cLogin();
$action = $_REQUEST['action'];

if($action == "doLogin"){
	$objLogin->getAccessWithLoginDetails();
}
if($action == "doLogout"){
	$objLogin->logout();
}
?>