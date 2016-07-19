<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/quantcast.class.php');
$objQuantcast= new cQuantcast();

$action = $_REQUEST['action'];

if($action == "uniques"){
    $objQuantcast->modGetTotalUniques();
}

?>