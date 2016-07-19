<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/creator.class.php');
$objCreator= new cCreator();
$action = $_REQUEST['action'];

function createArray($rubble) {
    $bricks = explode('&', $rubble);

    foreach($bricks as $key => $value) {
        $walls = preg_split('/=/', $value);
        $built[$walls[0]] = urldecode($walls[1]);
    }

    return $built;
}

if($action == "addButtonCreator"){
	$objCreator->modAjaxAddButton();
	
}
if($action == "add3DmodelCreator"){
	$objCreator->modAjaxAdd3DModel();
	
}
if($action == "addURLCreator"){
	$objCreator->modAjaxAddURL();
	
}


?>