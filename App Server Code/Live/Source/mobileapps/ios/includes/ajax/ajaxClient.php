<?php
global $config;

require_once '../../smcfg_new.php';
require_once $config['ABSOLUTEPATH'].'classes/config.class.php';
$objConfig = new cConfig();
$getConfig = $objConfig->config();

require_once($getConfig['ABSOLUTEPATH'].'classes/public.class.php');
$objPublic = new cPublic();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

if($action == "saveToMyOffers"){
	$pUserInfo = array();
	$pUserInfo['userId'] = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : 9;
	$pUserInfo['offerIds'] = isset($_REQUEST['offerid']) ? $_REQUEST['offerid'] : 0;
	// print_r($pUserInfo);
	$objPublic->modUserAddMyOffers($pUserInfo);
	// echo 'saveToMyOffers in ajax';
}

?>