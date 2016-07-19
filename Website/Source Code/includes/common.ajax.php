<?php require_once '../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/modules.class.php');
$objModules= new cModules();
$action = $_REQUEST['action'];

function createArray($rubble) {
    $bricks = explode('&', $rubble);

    foreach($bricks as $key => $value) {
        $walls = preg_split('/=/', $value);
        $built[$walls[0]] = urldecode($walls[1]);
    }

    return $built;
}


if($action == "saveenquiry"){
	$objModules->modAjaxSaveEnquiry();
	
	
}

?>