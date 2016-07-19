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

		require_once SRV_ROOT.'classes/store.class.php';
		$this->objStore = new cStore();

		require_once SRV_ROOT.'classes/clientauth.class.php';
		$this->objClientAuth = new cClientAuth();

		require_once SRV_ROOT.'classes/shopparvision.class.php';
		$this->objShoppar = new cShopparVision();
		
		require_once SRV_ROOT.'classes/games.class.php';
		$this->objGames = new cGames();
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
				if (isset($pUrl[1]) && ($pUrl[1]=='ios')){ 
					if (isset($pUrl[2]) && ($pUrl[2]=='public')){
						if (isset($pUrl[3]) && ($pUrl[3]=='triggers')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							//print_r($decoded);
						    $fileType = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						    $clientID = isset($decoded['clientId']) ? $decoded['clientId'] : 0;
						    $this->objPublic->modAllTriggersWithXML($clientID);
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='trigger')){
							if (isset($pUrl[4]) && ($pUrl[4]!=0)){
								 $this->objPublic->modTriggerByTId($pUrl[4]);
							}
						   
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='clientgroups')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							$this->objPublic->modCheckClientGroups($decoded);
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='clientgroupscount')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							$this->objPublic->modCheckClientGroupsPipe($decoded);
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='clienttriggers')){
						    $fileType = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						   $this->objPublic->modClientGroupsTriggers($fileType);
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='visualdetails')){
							if (isset($pUrl[4]) && ($pUrl[4]!=0)){
						    	 $this->objPublic->modVisualByTriggerID($pUrl[4]);
						 	}
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='productdetails')){
							// echo "testing product details";
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							// print_r($decoded);
						     $this->objPublic->modProductDetailsSet($decoded);
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='product')){
							if (isset($pUrl[4]) && ($pUrl[4]=='offers')){
								$fileType = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							   	$this->objPublic->modProductOffers($fileType);
						   }else if (isset($pUrl[4]) && ($pUrl[4]!=0)){
						   		if (isset($pUrl[5]) && ($pUrl[5]=='client')){
									$fileType = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
									$this->objPublic->modProductDetails($pUrl[4]);
							   	}
						   }
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='user')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							//print_r($decoded);
							$arrLoginDetails = explode("?", $pUrl[4]);
							if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='login')){
								$this->objPublic->modDoUserLogin($decoded);
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='registerlogin')){
								$this->objPublic->modUserLogInAfterRegister($decoded);
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='register')){
								$this->objPublic->modUserRegister($decoded);
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='wishlists')){
						    	if (isset($pUrl[5]) && ($pUrl[5]!="")){
									$arrMyOffers = explode("?", $pUrl[5]);
									if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='update')){
										$this->objPublic->modUserWishlistsUpdate($decoded);
									}else if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='remove')){
										$this->objPublic->modUserWishlistsRemove($decoded);
									}else if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='item')){
										$arrProdID = explode("?", $pUrl[6]);
										// print_r($arrProdID);
										if (isset($arrProdID[0]) && ($arrProdID[0]=='remove')){
											// echo 'remove function';
											$this->objPublic->modUserWishlistItemRemove($decoded);
										}
									}
						    	}else{
						    		$this->objPublic->modUserWishlists($decoded);
						    	}
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='myoffers')){
						    	if (isset($pUrl[5]) && ($pUrl[5]!="")){
									$arrMyOffers = explode("?", $pUrl[5]);
									if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='add')){
										$this->objPublic->modUserAddMyOffers($decoded);
									}else if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='remove')){
										$this->objPublic->modUserRemoveMyOffers($decoded);
									}else if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='redeem')){
										$this->objPublic->modUserRedeemMyOffers($decoded);
									}
						    	}else{
						    		$this->objPublic->modUserMyOffers($decoded);
						    	}
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='closet')){
						    	if (isset($pUrl[5]) && ($pUrl[5]!="")){
									$arrMyOffers = explode("?", $pUrl[5]);
									if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='add')){
										$this->objPublic->modUserAddMyCloset($decoded);
									}else if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='remove')){
										$this->objPublic->modUserRemoveMyCloset($decoded);
									}else if (isset($arrMyOffers[0]) && ($arrMyOffers[0]=='ownit')){
										$this->objPublic->modUserUpdateMyClosetOwnIt($decoded);
									}
						    	}else{
						    		$this->objPublic->modUserMyCloset($decoded);
						    	}
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='detail')){
						    	$this->objPublic->modGetUserDetails($decoded);
						    }else if (isset($arrLoginDetails[0]) && ($arrLoginDetails[0]=='update')){
						    	$this->objPublic->modUpdateUserDetails($decoded);
						    }
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='client')){
							if (isset($pUrl[4]) && ($pUrl[4]!='')){
								//$fileType = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
							   	$this->objPublic->modClientById($pUrl[4]);
						   }
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='offers')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							//print_r($decoded);
							$this->objPublic->modOffersById($decoded);
						   
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='forgotpassword')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							//print_r($decoded);
							$this->objPublic->modForgotPassword($decoded);
						   
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='clientstores')){
							$handle = fopen('php://input','r');
							$jsonInput = fgets($handle);
							// Decoding JSON into an Array
							$decoded = json_decode($jsonInput,true);
							$this->objPublic->modUserClientStores($decoded);
						   
						}
						//Shopping cart functionalities
						if (isset($pUrl[3]) && ($pUrl[3]=='stores')){
							$this->modStores($pUrl);
						}
						//Shoppar Vision functionalities
						if (isset($pUrl[3]) && ($pUrl[3]=='shoppar')){
							$this->modShopparVision($pUrl);
						}
						//Client Authentication functionalities
						if (isset($pUrl[3]) && ($pUrl[3]=='subscription')){
							$this->modClientAuth($pUrl);
						}
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='test')){

						// $this->objPublic->modDefaultCloset('89');
						// $this->objPublic->modDefaultMyOffers('96');
						// $this->objPublic->modDefaultWishlist('89');
					}
					if (isset($pUrl[3]) && ($pUrl[3]=='games')){
						
							$this->modGames($pUrl);
					}
				}
			}	
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	/**** function to get Page Footer ****/
	public function modStores($pUrl){
		try{
			global $config;
			// echo "modStores";
			// print_r($pUrl);
			if (isset($pUrl[4]) && ($pUrl[4]=='products')){
				if (isset($pUrl[5]) && ($pUrl[5]!='')){
					$this->objStore->modProdCartInfo($pUrl[5]);
				}
			}else if (isset($pUrl[4]) && ($pUrl[4]=='statetax')){
				// echo 'ship address will come soon.';
				$handle = fopen('php://input','r');
				$jsonInput = fgets($handle);
				// Decoding JSON into an Array
				$decoded = json_decode($jsonInput,true);
				if (isset($_REQUEST['state']) && ($_REQUEST['state'] !='')) {
					$decoded = array();
					$decoded = json_decode($_REQUEST['json'],true);
				}
				$this->objStore->modStateTaxByState($decoded);
			}else if (isset($pUrl[4]) && ($pUrl[4]=='shipaddress')){
				// echo 'ship address will come soon.';
				$handle = fopen('php://input','r');
				$jsonInput = fgets($handle);
				// Decoding JSON into an Array
				$decoded = json_decode($jsonInput,true);
				if (isset($_REQUEST['state']) && ($_REQUEST['state'] !='')) {
					$decoded = array();
					$decoded = json_decode($_REQUEST['json'],true);
				}
				$this->objStore->modStateTaxByState($decoded);
			}else if (isset($pUrl[4]) && ($pUrl[4]=='clientstores')){
				// echo 'lat lng'.json_encode($_REQUEST['lat']);
				// echo 'ship address will come soon.';
				$handle = fopen('php://input','r');
				$jsonInput = fgets($handle);
				// Decoding JSON into an Array
				$decoded = json_decode($jsonInput,true);
				if (isset($_REQUEST['clientid']) && ($_REQUEST['clientid'] !='')) {
					$decoded = array();
					// $decoded['clientid'] = isset($_REQUEST['clientid']) ? $_REQUEST['clientid'] : 0;
					$decoded = json_decode($_REQUEST['json'],true);
				}
				$this->objStore->modGetClientStoresByZip($decoded);
			}else if (isset($pUrl[4]) && ($pUrl[4]=='maincategories')){
				$this->objStore->modMainCategories();
			}else if (isset($pUrl[4]) && ($pUrl[4]=='clientproducts')){
				if (isset($pUrl[5]) && ($pUrl[5]!='')){
					$this->objStore->modClientProducts($pUrl[5]);
				}
			}else if (isset($pUrl[4]) && ($pUrl[4]=='order')){
				// print_r($_POST);
				$handle = fopen('php://input','r');
				$jsonInput = fgets($handle);
				// Decoding JSON into an Array
				$decoded = json_decode($jsonInput,true);
				if (isset($_REQUEST['userid']) && ($_REQUEST['userid'] !='')) {
					$decoded = array();
					$decoded = json_decode($_REQUEST['json'],true);
				}
				if (count($decoded) == 0) {
					$input = file_get_contents("php://input");
					$decoded = json_decode($input,true);
				}
				if (isset($pUrl[5]) && ($pUrl[5]=='testarray')){
					$this->objStore->doGetProdAttribValues();
					// $dummyTestArray = $this->objStore->getDummyClientOrder();
					// echo json_encode($dummyTestArray);
				}
				if (isset($pUrl[5]) && ($pUrl[5]=='create')){				 
					$this->objStore->modOrderCreate($decoded);
				}else if (isset($pUrl[5]) && ($pUrl[5]=='update')){
					$this->objStore->modOrderUpdate($decoded);
				}else if (isset($pUrl[5]) && ($pUrl[5]=='final')){
					$this->objStore->modOrderFinal($decoded);
				}else if (isset($pUrl[5]) && ($pUrl[5]=='saved')){
					if (isset($pUrl[6]) && ($pUrl[6]=='delete')){
						$this->objStore->modUserSavedOrdersDelete($decoded);
					}else{
						$this->objStore->modUserSavedOrders($decoded);
					}
				}
			}else if (isset($pUrl[4]) && ($pUrl[4]=='usershipping')){
				// echo "usershipping";
				// print_r($_POST);
				$handle = fopen('php://input','r');
				$jsonInput = fgets($handle);
				// Decoding JSON into an Array
				$decoded = json_decode($jsonInput,true);
				if (isset($_REQUEST['userid']) && ($_REQUEST['userid'] !='')) {
					$decoded = array();
					$decoded = json_decode($_REQUEST['json'],true);
				}
				if (isset($pUrl[5]) && ($pUrl[5]=='update')){				 
					$this->objStore->modUserShippingUpdate($decoded);
				}if (isset($pUrl[5]) && ($pUrl[5]=='delete')){				 
					$this->objStore->modUserShippingDelete($decoded);
				}else{
					$this->objStore->modUserShipping($decoded);
				}
				
			}

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modShopparVision($pUrl){
		try{
			global $config;
			// echo "modShopparVision";
			// print_r($pUrl);
			if (isset($pUrl[4]) && ($pUrl[4]=='match')){
				$handle = fopen('php://input','r');
				$jsonInput = fgets($handle);
				// Decoding JSON into an Array
				$decoded = json_decode($jsonInput,true);
				if (isset($_REQUEST['json']) && ($_REQUEST['json'] !='')) {
					$decoded = array();
					$decoded = json_decode($_REQUEST['json'],true);
				}

				$this->objShoppar->modMatching($decoded);
			}

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientAuth($pUrl){
		try{
			global $config;
			// echo "modClientAuth";
			// print_r($pUrl);
			$handle = fopen('php://input','r');
			$jsonInput = fgets($handle);
			// Decoding JSON into an Array
			$decoded = json_decode($jsonInput,true);
			if (isset($_REQUEST['json']) && ($_REQUEST['json'] !='')) {
				$decoded = array();
				$decoded = json_decode($_REQUEST['json'],true);
			}
			if (isset($pUrl[4]) && ($pUrl[4]=='auth')){
				$this->objClientAuth->modClientAuth($decoded);
			}

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGames($pUrl){
		try{
			global $config;
			// echo "modClientAuth";
			// print_r($pUrl);
			$handle = fopen('php://input','r');
			$jsonInput = fgets($handle);
			// Decoding JSON into an Array
			$decoded = json_decode($jsonInput,true);
			if (isset($_REQUEST['json']) && ($_REQUEST['json'] !='')) {
				$decoded = array();
				$decoded = json_decode($_REQUEST['json'],true);
			}
			$this->objGames->modClientGames($decoded);
			
			
			/* if (isset($pUrl[4]) && ($pUrl[4]=='list')){			
				$this->objGames->modGames($decoded);
			}
			if (isset($pUrl[4]) && ($pUrl[4]=='scratch')){	
		
				$this->objGames->modClientScratchGames($decoded);
			}
			if (isset($pUrl[4]) && ($pUrl[4]=='wod')){			
				$this->objGames->modClientWheelGames($decoded);
			} */
			
			
			
			

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
		unset($objLogin);
		unset($objConfig);
		unset($getConfig);
		unset($objCommon);
		unset($this->objPublic);
		unset($this->getConfig);
		unset($this->objAppLogin);
		unset($this->objAppWishList);
		unset($this->objStore);
		unset($this->objShoppar);
		unset($this->objClientAuth);
		unset($this->objGames);
		}
	
} /*** end of class ***/
?>