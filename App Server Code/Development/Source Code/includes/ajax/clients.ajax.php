<?php require_once '../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/clients.class.php');
$objClients= new cClients();

require_once($config['ABSOLUTEPATH'].'classes/analytics.class.php');
$objAnalytics= new cAnalytics();

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
	$tempArray=array();
	$tempArray['response']=$_FILES;
	echo json_encode($tempArray);
	//$objClients->modAjaxCreateClientDetails();
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
if($action == "saveAudioVisual"){
	$objClients->modAjaxSaveAudioVisual();
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
if($action == "analyticsByDateRange"){
	$objAnalytics->modShowClientAnalyticsByProducts();
}

if($action == "UpdateClientStores"){
	$objClients->modAjaxUpdateClientStores();
}

if($action == "saveClientStores"){
	$objClients->modAjaxSaveClientStores();
}
if($action == "deleteStore"){
	$objClients->modAjaxDeleteClientStores();
}
if($action == "updateStoreRelatedOffers"){
    $objClients->modAjaxUpdateStoreRelatedOffers();
}
if($action == "getCountryByState"){
    $objClients->modAjaxGetCountryByState();
}
if($action == "getStatesByCountry"){
    $objClients->modAjaxGetStatesByCountry();
}

if($action == "getUserTrackingDashboard"){
    $objAnalytics->modShowUserTrackingDashboard();
}
//product flow
if($action == "clientProductUsersFlowLevel"){
    $objAnalytics->modShowProductFlowUserLevel();
}
if($action == "clientProductScannedFlowLevel"){
    $objAnalytics->modShowProductFlowScannedLevel();
}

if($action == "clientProductClosetFlowLevel"){
    $objAnalytics->modShowProductFlowClosetLevel();
}
if($action == "clientProductWishlistFlowLevel"){
    $objAnalytics->modShowProductFlowWishlistLevel();
}
if($action == "clientProductShareFlowLevel"){
    $objAnalytics->modShowProductFlowShareLevel();
}
if($action == "clientProductShareEmailFlowLevel"){
    $objAnalytics->modShowProductFlowShareEmailLevel();
}
if($action == "clientProductShareFBFlowLevel"){
    $objAnalytics->modShowProductFlowShareFBLevel();
}
if($action == "clientProductShareTwitterFlowLevel"){
    $objAnalytics->modShowProductFlowShareTwitterLevel();
}
if($action == "clientProductShareSmsFlowLevel"){
    $objAnalytics->modShowProductFlowShareSMSLevel();
}
if($action == "clientProductCartFlowLevel"){
    $objAnalytics->modShowProductFlowCartLevel();
}
if($action == "clientProductDetailsFlowLevel"){
    $objAnalytics->modShowProductFlowDetailsLevel();
}
//offers flow
if($action == "clientOfferUsersFlowLevel"){
    $objAnalytics->modShowOfferFlowUserLevel();
}
if($action == "clientOfferMyOffersFlowLevel"){
    $objAnalytics->modShowOfferFlowMyOffersLevel();
}
if($action == "clientOfferAddFlowLevel"){
    $objAnalytics->modShowOfferFlowAddLevel();
}
if($action == "clientOfferRemoveFlowLevel"){
    $objAnalytics->modShowOfferFlowRemoveLevel();
}
if($action == "clientOfferRedeemFlowLevel"){
    $objAnalytics->modShowOfferFlowRedeemLevel();
}
if($action == "clientOfferShareFlowLevel"){
    $objAnalytics->modShowOfferFlowShareLevel();
}
if($action == "clientOfferShareEmailFlowLevel"){
    $objAnalytics->modShowOfferFlowShareEmailLevel();
}
if($action == "clientOfferShareFBFlowLevel"){
    $objAnalytics->modShowOfferFlowShareFBLevel();
}
if($action == "clientOfferShareTwitterFlowLevel"){
    $objAnalytics->modShowOfferFlowShareTwitterLevel();
}
if($action == "clientOfferShareSmsFlowLevel"){
    $objAnalytics->modShowOfferFlowShareSMSLevel();
}
if($action == "clientOfferScannedFlowLevel"){
    $objAnalytics->modShowOfferFlowScannedLevel();
}
if($action == "clientCampaignInfo"){
    $objAnalytics->modShowGetClientCampaign();
}
if($action == "campaignInfo"){
    $objAnalytics->modShowGetCampaignInfo();
}
if($action == "getCampaignDates"){
    $objAnalytics->modShowGetClientCampaignDates();
}
if($action == "online_users"){
    $objAnalytics->modShowOnlineUsers();
}
if($action == "product_views"){
    $objAnalytics->modShowProductViews();
}
if($action == "offer_views"){
    $objAnalytics->modShowOfferViews();
}
if($action == "product_share_views"){
    $objAnalytics->modShowProductShareViews();
}
if($action == "offer_share_views"){
    $objAnalytics->modShowOfferShareViews();
}
if($action == "downloads"){
    $objAnalytics->modShowTotalDownloads();
}
if($action == "offer_graphs"){
    $objAnalytics->modShowOfferGraphViews();
}
if($action == "product_graphs"){
    $objAnalytics->modShowProductGraphViews();
}
if($action == "product_share_graphs"){
    $objAnalytics->modShowProductShareGraphViews();
}
if($action == "offer_share_graphs"){
    $objAnalytics->modShowOfferShareGraphViews();
}


?>