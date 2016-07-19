<?php 
/**** Include interfaces link ****/
//require_once(SRV_ROOT.'interfaces/interfaces.inc.php');
class cPageBuilder{
	/*** define public & private properties ***/
	 public $objLogin;
	 public $objConfig;
	 public $getConfig;
	 public $objCommon;
	 
	/*** the constructor ***/
	public function __construct(){
		
		require_once SRV_ROOT.'classes/public.class.php';
		$this->objPublic = new cPublic();
		
		require_once SRV_ROOT.'classes/config.class.php';
		$this->objConfig = new cConfig();
		$this->getConfig = $this->objConfig->config();
		
		require_once SRV_ROOT.'classes/app.login.class.php';
		$this->objAppLogin = new cAppLogin();
		
		require_once SRV_ROOT.'classes/app.product.wishlist.class.php';
		$this->objAppWishList = new cProductWishlist();
		
		require_once SRV_ROOT.'classes/app.my.offers.class.php';
		$this->objAppMyOffers = new cMyOffers();
		
		require_once SRV_ROOT.'classes/app.closet.class.php';
		$this->objAppCloset = new cCloset();
		
		require_once SRV_ROOT.'classes/app.saas.clientdb.class.php';
		$this->objAppSassClientDbs = new cAppSaasClientDbs();
		
		require_once(SRV_ROOT.'classes/Array2XML.php');
		
		//require_once SRV_ROOT.'classes/thread.class.php';
		$program_start_time = time();
		//$this->objThread = new Thread($_SERVER['SERVER_NAME'], 80);
	}
	/**** function to get all functions of Header and assign to header template ****/
	public function pageHeader(){
		try{
			global $config;
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){
				$outUserInfo = array();
				$outUserInfo['fname'] =  isset($_SESSION['fname']) ? $_SESSION['fname'] : "";
				$outUserInfo['lname'] =  isset($_SESSION['lname']) ? $_SESSION['lname'] : "";
				$outUserInfo['user_group'] =  isset($_SESSION['user_group']) ? $_SESSION['user_group'] : "";
				$getDateFromDB = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : "";
				$date = new DateTime($getDateFromDB);
				$outUserInfo['last_login'] = $date->format('m-d-Y H:i:s');
				/**** assign variables/array to header.tpl.php ****/
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get all functions of Content and assign to content template ****/
	public function pageContent($pUrl){
		try{
			global $config;
			//print_r($pUrl);
			$pAction = isset($pUrl[0]) ? $pUrl[0] : '';
			if ($pAction=='mobileapps'){
				if (isset($pUrl[1]) && ($pUrl[1]=='android_saas')){ 
					/*if (isset($pUrl[2]) && ($pUrl[2]=='triggers_xml')){
						$this->objPublic->modAllTriggersWithXML();
					}*/
					if (isset($pUrl[2]) && ($pUrl[2]=='triggers_model_xml')){
						$triggerId = isset($_REQUEST['triggerId']) ? $_REQUEST['triggerId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);		
						$clientId = isset($_REQUEST['clientId']) ? $_REQUEST['clientId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						$threeModelId = isset($_REQUEST['threeModelId']) ? $_REQUEST['threeModelId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
						$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($clientId);
						$this->objPublic->modAllTriggerModelsWithXML($triggerId, $clientId, $threeModelId);
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='client_xml')){
						$this->objPublic->modClientGroupTriggersWithXML($pUrl);
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='client_stores')){
						
						$clientIds = isset($_REQUEST['clientIds']) ? $_REQUEST['clientIds'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($clientIds);
						$this->objPublic->modClientStore($pUrl, $clientIds);
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='client_trigger_xml')){
						$clientIds = isset($_REQUEST['clientIds']) ? $_REQUEST['clientIds'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($clientIds);
						$this->objPublic->modClientTriggersWithXML($pUrl, $clientIds);
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='client_product')){
						if (isset($pUrl[6]) && ($pUrl[6]=='tryon')){
							$this->objPublic->modTryonRelatedProductsWithXml($pUrl);
						} else if (isset($pUrl[4]) && ($pUrl[4]=='related_products')){
							$this->objPublic->modRelatedProductsWithXml($pUrl);
						} else {
							$this->objPublic->modClientProductWithXml($pUrl);
						}
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='get_client_with_product_details')){
						$clientId = isset($_REQUEST['clientId']) ? $_REQUEST['clientId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$productId = isset($_REQUEST['productId']) ? $_REQUEST['productId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($clientId);
						$this->objPublic->modClientWithProductDetailsWithXml($clientId, $productId);
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='tap_for_details'))
					{
						$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$productId = isset($_REQUEST['getProductId']) ? $_REQUEST['getProductId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						$this->objPublic->modGetTapForDetailsWithXml($loggedInUserId, $productId);
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='my_offers')){
						if (isset($pUrl[3]) && ($pUrl[3]=='offer_info')){
							$clientId = isset($_REQUEST['clientId']) ? $_REQUEST['clientId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$checkFlag = isset($_REQUEST['checkFlag']) ? $_REQUEST['checkFlag'] : (isset($pUrl[6]) ? $pUrl[6] : "No");
							$offerId = isset($_REQUEST['offerIdsWithSymbol']) ? $_REQUEST['offerIdsWithSymbol'] : (isset($pUrl[7]) ? $pUrl[7] : 0);
							$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($clientId);
							$this->objAppMyOffers->modGetClientOffers($clientId, $checkFlag, $loggedInUserId, $offerId);
						} else if(isset($pUrl[3]) && ($pUrl[3]=='insert_offers')){
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$offerId = isset($_REQUEST['offerId']) ? $_REQUEST['offerId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$this->objAppMyOffers->modInsertMyOffersWithUserId($loggedInUserId, $offerId, "");
						} else if(isset($pUrl[3]) && ($pUrl[3]=='related_offers')){							
							$this->objAppMyOffers->modRelatedOffersWithXml($pUrl);
						}else if(isset($pUrl[3]) && ($pUrl[3]=='brand')){
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : (isset($pUrl[3]) ? $pUrl[3] : "");
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnMyOfferUserIds($loggedInUserId);
							$this->objAppMyOffers->modGetMyOffersList($loggedInUserId, 0, $flag, $newConfigArrResult);
						} else if(isset($pUrl[3]) && ($pUrl[3]=='expiration')){
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : (isset($pUrl[3]) ? $pUrl[3] : "");
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnMyOfferUserIds($loggedInUserId);
							$this->objAppMyOffers->modGetMyOffersList($loggedInUserId, 0, $flag, $newConfigArrResult);
						} else if(isset($pUrl[3]) && ($pUrl[3]=='value')){
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : (isset($pUrl[3]) ? $pUrl[3] : "");
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnMyOfferUserIds($loggedInUserId);
							$this->objAppMyOffers->modGetMyOffersList($loggedInUserId, 0, $flag, $newConfigArrResult);
						}else if(isset($pUrl[3]) && ($pUrl[3]=='recent')){
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : (isset($pUrl[3]) ? $pUrl[3] : "");
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnMyOfferUserIds($loggedInUserId);
							$this->objAppMyOffers->modGetMyOffersList($loggedInUserId, 0, $flag, $newConfigArrResult);
						} 
						else if(isset($pUrl[3]) && ($pUrl[3]=='delete')){
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : (isset($pUrl[3]) ? $pUrl[3] : "");
							$this->objAppMyOffers->modDeleteMyOffersWithUserId($loggedInUserId, $flag);
						} else {
							$loggedInUserId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
							$offerId = isset($_REQUEST['offerId']) ? $_REQUEST['offerId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnMyOfferUserIds($loggedInUserId);
							$this->objAppMyOffers->modGetMyOffersList($loggedInUserId, $offerId, "", $newConfigArrResult);
						}
					}
					/*}else if (isset($pUrl[2]) && ($pUrl[2]=='arapp')){*/
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='login')){
						$this->objAppLogin->getAccessWithAppLoginDetails($pUrl);
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='login_profiling')){
						$this->objAppLogin->getAccessWithAppLoginDetails($pUrl);
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='register')){
						$this->objAppLogin->getInsertRegistrationDetails($pUrl);
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='forgotpassword')){
						$this->objAppLogin->modForgotPassword($pUrl);
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='state_list')){
						$this->objAppLogin->getStateListValues();
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='user_details')){
						
						if($pUrl[3] == 'update'){
							$this->objPublic->modUpdateUsersDetails($pUrl);
						}else{
							$this->objPublic->modUserDetails($pUrl[3]);
						}
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='closet_wishlist')){
						$cValsArray['resultFormatType'] = isset($_REQUEST['resultFormatType']) ? $_REQUEST['resultFormatType'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						//$cValsArray['closetSelStatus'] = isset($_REQUEST['closetSelStatus']) ? $_REQUEST['closetSelStatus'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
						$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClosetUserIds($cValsArray['userId']);
						$this->objAppCloset->modGetClosetWishListProductValues($cValsArray, $newConfigArrResult);
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='closet')){
						$cValsArray = array();
						if(isset($pUrl[3]) && ($pUrl[3]=='insert')){
							$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$cValsArray['productId'] = isset($_REQUEST['productId']) ? $_REQUEST['productId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$cValsArray['closetSelStatus'] = isset($_REQUEST['closetSelStatus']) ? $_REQUEST['closetSelStatus'] : (isset($pUrl[6]) ? $pUrl[6] : 0);
							$cValsArray['clientId'] = isset($pUrl[7]) ? $pUrl[7] : 0;
							$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($cValsArray['clientId']);
							$this->objAppCloset->modInsertClosetWithUserProdIds($cValsArray);
						} else if(isset($pUrl[3]) && ($pUrl[3]=='update')){
							$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$cValsArray['productId'] = isset($_REQUEST['productId']) ? $_REQUEST['productId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$cValsArray['closetSelStatus'] = isset($_REQUEST['closetSelStatus']) ? $_REQUEST['closetSelStatus'] : (isset($pUrl[6]) ? $pUrl[6] : 1);
							$cValsArray['clientId'] = isset($pUrl[7]) ? $pUrl[7] : 0;
							$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($cValsArray['clientId']);
							$this->objAppCloset->modUpdateClosetWithUserProdIds($cValsArray);
						} else if(isset($pUrl[3]) && ($pUrl[3]=='delete')){
							$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$cValsArray['productId'] = isset($_REQUEST['productId']) ? $_REQUEST['productId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$cValsArray['closetSelStatus'] = isset($_REQUEST['closetSelStatus']) ? $_REQUEST['closetSelStatus'] : (isset($pUrl[6]) ? $pUrl[6] : 1);
							$cValsArray['clientId'] = isset($pUrl[7]) ? $pUrl[7] : 0;
							$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($cValsArray['clientId']);
							$this->objAppCloset->modDeleteClosetWithUserProdIds($cValsArray);
						}else if(isset($pUrl[3]) && ($pUrl[3]=='brands')){
							$cValsArray['resultFormatType'] = isset($_REQUEST['resultFormatType']) ? $_REQUEST['resultFormatType'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$cValsArray['closetSelStatus'] = isset($_REQUEST['closetSelStatus']) ? $_REQUEST['closetSelStatus'] : (isset($pUrl[6]) ? $pUrl[6] : 0);
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClosetUserIds($cValsArray['userId']);
							//print_r($newConfigArrResult);
							$this->objAppCloset->modGetClosetForBrands($cValsArray, $newConfigArrResult);
						}
						else if(isset($pUrl[3]) && ($pUrl[3]=='bybrands')){
							$cValsArray['resultFormatType'] = isset($_REQUEST['resultFormatType']) ? $_REQUEST['resultFormatType'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$cValsArray['productId'] = isset($_REQUEST['productId']) ? $_REQUEST['productId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[6]) ? $pUrl[6] : 0);
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClosetUserIds($cValsArray['userId']);
							$this->objAppCloset->modGetClosetProductByBrands($cValsArray, $newConfigArrResult);
						}
						 else {
							$cValsArray['resultFormatType'] = isset($_REQUEST['resultFormatType']) ? $_REQUEST['resultFormatType'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
							$cValsArray['userId'] = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$cValsArray['closetSelStatus'] = isset($_REQUEST['closetSelStatus']) ? $_REQUEST['closetSelStatus'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClosetUserIds($cValsArray['userId']);
							$this->objAppCloset->modGetClosetProductValues($cValsArray, $newConfigArrResult);
						}
						/*$resultFormatType = isset($_REQUEST['resultFormatType']) ? $_REQUEST['resultFormatType'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$arrProductId = isset($_REQUEST['arrProductId']) ? $_REQUEST['arrProductId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						$this->objPublic->modClosetProducts($arrProductId, $resultFormatType);*/
					}
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='wishlist')){							
						if (isset($pUrl[3]) && ($pUrl[3]=='select_wishlist')){
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$this->objAppWishList->modGetSelectedWishListNames($loggedInUserId);
						} else if (isset($pUrl[3]) && !empty($pUrl[3]) && ($pUrl[3]=='addtowishlist')) {
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$wishListName = isset($_REQUEST['addToWishListName']) ? $_REQUEST['addToWishListName'] : (isset($pUrl[5]) ? $pUrl[5] : "");
							$this->objAppWishList->modAddWishListName($loggedInUserId, $wishListName);
						} else if (isset($pUrl[3]) && ($pUrl[3]=='your_closet')){
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$this->objAppWishList->modGetYourClosetFromWishListProducts($loggedInUserId);
						} else if (isset($pUrl[3]) && ($pUrl[3]=='share')){
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$wishListName= isset($_REQUEST['wishListName']) ? $_REQUEST['wishListName'] : (isset($pUrl[5]) ? $pUrl[5] : "");
							$this->objAppWishList->modShareMultipleImagesByWishListName($loggedInUserId,$wishListName);
						} else if (isset($pUrl[3]) && ($pUrl[3]=='friends_wishlist')){
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$this->objAppWishList->modGetFriendsWishListNames($loggedInUserId);
						}else if (isset($pUrl[3]) && ($pUrl[3]=='friends_wishlist_product')){
							 $loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							 $wishListId = isset($_REQUEST['wishListId']) ? $_REQUEST['wishListId'] : (isset($pUrl[5]) ? $pUrl[5] : "");
							$this->objAppWishList->modGetFriendsWishListProducts($loggedInUserId,$wishListId);
						} else if (isset($pUrl[3]) && !empty($pUrl[3]) && ($pUrl[3]=='delete_selected_closet')){
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							$closetImgProdId = isset($_REQUEST['closetImgProdId']) ? $_REQUEST['closetImgProdId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
							$this->objAppWishList->modDelSelectedYourCloset($loggedInUserId, $closetImgProdId);
						} else if (isset($pUrl[3]) && !empty($pUrl[3]) && ($pUrl[3]=='additemtowishlist')) {						
							if (isset($pUrl[4]) && !empty($pUrl[4]) && ($pUrl[4]=='adding'))
							{
								//print_r($pUrl);
								$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[5]) ? $pUrl[5] : 0);
								$productIds = isset($_REQUEST['getProductIds']) ? $_REQUEST['getProductIds'] : (isset($pUrl[6]) ? $pUrl[6] : 0);							
								$wishListIds = isset($_REQUEST['wishListIds']) ? $_REQUEST['wishListIds'] : (isset($pUrl[7]) ? $pUrl[7] : 0);
								$productIdsUnChecked = isset($_REQUEST['getProductIdsUnChecked']) ? $_REQUEST['getProductIdsUnChecked'] : (isset($pUrl[8]) ? $pUrl[8] : 0);
								$wishListIdsUnChecked = "";						
								//$wishListIdsUnChecked = isset($_REQUEST['wishListIdsUnChecked']) ? $_REQUEST['wishListIdsUnChecked'] : (isset($pUrl[9]) ? $pUrl[9] : 0);
								$this->objAppWishList->modAddItemsToWishListInProducts($loggedInUserId, $productIds, $wishListIds, $productIdsUnChecked, $wishListIdsUnChecked);
							} else {
								$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
								$wishListName= isset($_REQUEST['wishListName']) ? $_REQUEST['wishListName'] : (isset($pUrl[5]) ? $pUrl[5] : "");
								$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnWishListUserIds($loggedInUserId);
								$this->objAppWishList->modGetAllWishListProducts($loggedInUserId,$wishListName,$newConfigArrResult);
							}
						}
						 else {
							//cAppSaasClientDbs::modGetClientDbConnectionsBasedOnWishListUserIds(6);
							$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
							$wishListName= isset($_REQUEST['wishListName']) ? $_REQUEST['wishListName'] : (isset($pUrl[4]) ? $pUrl[4] : "");
							
							/*$thread_b = new Thread($_SERVER['SERVER_NAME'],80);
							$thread_b->setFunc("cAppSaasClientDbs::modGetClientDbConnectionsBasedOnWishListUserIds(".$loggedInUserId.")", array(4));
							$thread_b->start();
							echo $thread_b->getreturn();
							echo "Main Program has run ".(time()-$program_start_time)." seconds<br />";*/
							//new cAppSaasClientDbs::modGetClientDbConnectionsBasedOnWishListUserIds($loggedInUserId);
							$newConfigArrResult = $this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnWishListUserIds($loggedInUserId);
							//print_r($newConfigArrResult);
							$this->objAppWishList->modGetWishListProducts($loggedInUserId,$wishListName, $newConfigArrResult);
						}
					}
					/*if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='addofferstowishlist'))
					{
						$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$productId = isset($_REQUEST['getProductId']) ? $_REQUEST['getProductId'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						//$loggedInUserId = isset($pUrl[3]) ? $pUrl[3] : 0;
						//$productId = isset($pUrl[4]) ? $pUrl[4] : 0;
						$this->objAppWishList->modAddWishListFromOffersProducts($loggedInUserId, $productId);
					}*/
					if (isset($pUrl[2]) && !empty($pUrl[2]) && ($pUrl[2]=='deletewishlist'))
					{
						$loggedInUserId = isset($_REQUEST['loggedInUserId']) ? $_REQUEST['loggedInUserId'] : (isset($pUrl[3]) ? $pUrl[3] : 0);
						$wishListName= isset($_REQUEST['wishListName']) ? $_REQUEST['wishListName'] : (isset($pUrl[4]) ? $pUrl[4] : "");
						
						$this->objAppWishList->modDelSelectedWishList($loggedInUserId,$wishListName, $newConfigArrResult);
					}
					//}
				}
			}		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	/**** function to get Page Footer ****/
	public function pageFooter(){
		try{
			global $config;
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){
				//include SRV_ROOT.'views/home/footer.tpl.php';
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get Page Left Modules ****/
	public function pageLeft(){
		try{
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get Page right modules ****/
	public function pageRight($pAction){
		try{
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>