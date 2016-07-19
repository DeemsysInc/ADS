<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/common.class.php');
$objCommon = new cCommon();
$action = $_REQUEST['action'];

if($action == "loadStates"){
	 $objCommon->modLoadStates();
}


?>