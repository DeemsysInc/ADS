<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/clients.class.php');
$objClients= new cClients();
$action = $_REQUEST['action'];

function createArray($rubble) {
    $bricks = explode('&', $rubble);

    foreach($bricks as $key => $value) {
        $walls = preg_split('/=/', $value);
        $built[$walls[0]] = urldecode($walls[1]);
    }

    return $built;
}


if($action == "saveClientProduct"){
	$objClients->modAjaxSaveClientProduct();
}
if($action == "updateClientTrigger"){
	$objClients->modAjaxupdateClientTrigger();
}
if($action == "updateClientLogo"){
	$arrData=array();
	$arrData['logo']=isset($_REQUEST['c_logo']) ? $_REQUEST['c_logo'] : '';
    $c_id=isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : '';
	
	$objClients->modAjaxupdateClientLogo($arrData,$c_id);
}

if($action == "getEditClientForm"){
	$arrData=array();
	$arrData['logo']=isset($_REQUEST['c_logo']) ? $_REQUEST['c_logo'] : '';
    $c_id=isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : '';
	
	$objClients->modAjaxupdateClientLogo($arrData,$c_id);
}
if($action == "updateClientDetails"){
	$objClients->modAjaxupdateClientDetails();
}
if($action == "deleteClient"){
	$objClients->modAjaxDeleteClient();
}
if($action == "CreateClient"){
	
	$objClients->modAjaxCreateClientDetails();
}
if($action == "updateRelatedProduct"){
	$objClients->modAjaxSaveRelatedProducts();
}
if($action == "saveProductOffer"){
	$objClients->modAjaxSaveProductOffer();
}
if($action == "deleteProduct"){
	$objClients->modAjaxDeleteProduct();
}
if($action == "savebtnvisual"){
	
	$objClients->modAjaxSaveButtonVisual();
}
if($action == "deleteTriggerVisual"){
	
	$objClients->modAjaxDeleteTriggerVisual();
}
if($action == "deleteTrigger"){
	
	$objClients->modAjaxDeleteTrigger();
}

if($action == "save3dmodelvisual"){
	
	$objClients->modAjaxSave3DModelVisual();
}
if($action == "saveurlvisual"){
	
	$objClients->modAjaxSaveUrlVisual();
}
if($action == "saveVideoVisual"){
	$objClients->modAjaxSaveVideoVisual();
}
if($action == "uploadAdditionalMedia"){
	$objClients->modAjaxSaveAdditionalMedia();
}
if($action == "deleteAdditionalMedia"){
	$objClients->modAjaxDeleteAdditionalMedia();
}
if($action == "UpdateAdditionalMedia"){
	$objClients->modAjaxUpdateAdditionalMedia();
}
if($action == "CreateClientProduct"){
	$objClients->modAjaxAddClientProduct();
}
if($action == "addClientTrigger"){
	$objClients->modAjaxAddClientTrigger();
}
if($action == "update3Dmodel"){
	$objClients->modAjaxUpdate3Dmodel();
}
if($action == "add3Dmodel"){
	$objClients->modAjaxInsert3Dmodel();
}
if($action == "delete3DModel"){
	$objClients->modAjaxDelete3Dmodel();
}
?>