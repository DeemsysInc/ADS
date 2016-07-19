<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/users.class.php');
$objUsers= new cUsers();
$action = $_REQUEST['action'];

function createArray($rubble) {
    $bricks = explode('&', $rubble);

    foreach($bricks as $key => $value) {
        $walls = preg_split('/=/', $value);
        $built[$walls[0]] = urldecode($walls[1]);
    }

    return $built;
}

if($action == "addCmsUser"){
	$arrData = createArray($_REQUEST['dataVals']);
	$objUsers->addCmsUser($arrData);
	
}
if($action == "deleteUsers"){
	$arrData = createArray($_REQUEST['dataVals']);
	$objUsers->modDeleteUsers($arrData);
}
if($action == "deleteUser"){
	$arrData['check']=isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
	$objUsers->modDeleteUsers($arrData);
}
if($action == "editSaveUser"){
	$objUsers->modifyUser();
}

if($action == "ajaxEditSalesUsers"){
	$objUsers->modAjaxEditSalesUsers();
}
if($action == "saveRequisition"){
	$arrData = createArray($_REQUEST['dataVals']);
	$objUsers->modAjaxSaveRequisition($arrData);
}
if($action == "saveUserProfile"){
	$arrData = createArray($_REQUEST['dataVals']);
	$objUsers->modAjaxSaveUserProfile($arrData);
}
if($action == "saveUserPassword"){
	$objUsers->modAjaxSaveUserPassword();
}
if($action == "forgotPassword"){
	$objUsers->modAjaxForgotPassword();
}
if($action == "checkEmail"){
	$objUsers->modCheckEmail();
}
if($action == "saveAppUserProfile"){
	$arrData = createArray($_REQUEST['dataVals']);
	$objUsers->modAjaxSaveAppUserProfile($arrData);
}
if($action == "updateCmsUserProfile"){
	$arrData = createArray($_REQUEST['dataVals']);
	$objUsers->modAjaxUpdateCmsUserProfile($arrData);
}

if($action == "deleteCmsUser"){
	
	$objUsers->modDeleteCmsUser();
}

?>