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
						    $clientID = isset($decoded['clientId']) ? $decoded['clientId'] : "some";
						    $this->objPublic->modAllTriggersWithXML($clientID);
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='clientgroups')){
							$this->objPublic->modCheckClientGroups();
						}
						if (isset($pUrl[3]) && ($pUrl[3]=='clienttriggers')){
						    $fileType = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : (isset($pUrl[4]) ? $pUrl[4] : 0);
						   $this->objPublic->modClientGroupsTriggers($fileType);
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
					}
					if (isset($pUrl[2]) && ($pUrl[2]=='test')){
						// $this->objPublic->modDefaultCloset('3');
						// $this->objPublic->modDefaultMyOffers('3');
						
					}
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