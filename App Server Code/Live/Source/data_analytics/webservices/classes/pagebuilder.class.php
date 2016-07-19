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
			if (isset($pUrl[2]) && $pUrl[2]=='clientproducts'){
				$this->objPublic->modShowClientProducts();
            }
            elseif (isset($pUrl[2]) && $pUrl[2]=='campaigns'){
            	if (isset($pUrl[3]) && $pUrl[3]=='dates'){
            		$this->objPublic->modShowMobileUserClientCampaignDates();
            	}
            	
				
            }
            elseif (isset($pUrl[2]) && $pUrl[2]=='clientoffers'){
				$this->objPublic->modShowClientOffers();
            }
            elseif (isset($pUrl[2]) && $pUrl[2]=='product_views'){
            	if (isset($pUrl[3]) && $pUrl[3]=='clients'){
            		$this->objPublic->modShowMobileUserClientProdViews();
            	}
            	elseif (isset($pUrl[3]) && $pUrl[3]=='bydate'){
            		$this->objPublic->modShowMobileUserTrackDataByProductsViewsByDate();
            	}
            	else
            	{
            		$this->objPublic->modShowMobileUserTrackDataByProductsViews();
            	}
            	
			}
			elseif (isset($pUrl[2]) && $pUrl[2]=='offer_views'){
            	if (isset($pUrl[3]) && $pUrl[3]=='clients'){
            		$this->objPublic->modShowMobileUserClientOfferViews();
            	}
            	elseif (isset($pUrl[3]) && $pUrl[3]=='bydate'){
            		$this->objPublic->modShowMobileUserTrackDataByOffersViewsByDate();
            	}
            	else
            	{
            		$this->objPublic->modShowMobileUserTrackDataByOffersViews();
            	}
            	
			}
			elseif (isset($pUrl[2]) && $pUrl[2]=='byproducts'){
            	$this->objPublic->modShowMobileUseTrackDataByProducts();
			}
			elseif (isset($pUrl[2]) && $pUrl[2]=='byoffers'){
            	$this->objPublic->modShowMobileUseTrackDataByOffers();
			}
			elseif (isset($pUrl[2]) && $pUrl[2]=='flow')
			{
				if (isset($pUrl[3]) && $pUrl[3]=='products')
				{  
					if (isset($pUrl[4]) && $pUrl[4]=='users')
				    {
				    	$this->objPublic->modShowMobileUserProductsFlowUsers();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='scanned')
				    {
				    	$this->objPublic->modShowMobileUserProductsFlowScanned();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='closet')
				    {
				    	$this->objPublic->modShowMobileUserProductsFlowCloset();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='wishlist')
				    {
				    	$this->objPublic->modShowMobileUserProductsFlowWishlist();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='cart')
				    {
				    	$this->objPublic->modShowMobileUserProductsFlowCart();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='details')
				    {
				    	$this->objPublic->modShowMobileUserProductsFlowDetails();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='share')
				    {
				    	if (isset($pUrl[5]) && $pUrl[5]=='email')
				    	{
				    		$this->objPublic->modShowMobileUserProductsFlowShareEmail();

				    	}
				    	elseif (isset($pUrl[5]) && $pUrl[5]=='facebook')
				    	{
				    		$this->objPublic->modShowMobileUserProductsFlowShareFB();

				    	}
				    	elseif (isset($pUrl[5]) && $pUrl[5]=='twitter')
				    	{
				    		$this->objPublic->modShowMobileUserProductsFlowShareTwitter();

				    	}
				    	elseif (isset($pUrl[5]) && $pUrl[5]=='SMS')
				    	{
				    		$this->objPublic->modShowMobileUserProductsFlowShareSMS();

				    	}
				    	else
				    	{
				    		$this->objPublic->modShowMobileUserProductsFlowShare();

				    	}
				    
				    	
				    }
				    else
				    {
				    	$this->objPublic->modShowMobileUserProductsFlow();
				    }

					

				}
				elseif (isset($pUrl[3]) && $pUrl[3]=='offers')
				{
					if (isset($pUrl[4]) && $pUrl[4]=='users')
				    {
				    	$this->objPublic->modShowMobileUserOffersFlowUsers();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='myoffers')
				    {
				    	$this->objPublic->modShowMobileUserOffersFlowMyOffers();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='add')
				    {
				    	$this->objPublic->modShowMobileUserOffersFlowAdd();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='remove')
				    {
				    	$this->objPublic->modShowMobileUserOffersFlowRemove();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='redeem')
				    {
				    	$this->objPublic->modShowMobileUserOffersFlowRedeem();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='scanned')
				    {
				    	$this->objPublic->modShowMobileUserOffersFlowScanned();
				    }
				    elseif (isset($pUrl[4]) && $pUrl[4]=='share')
				    {
				    	if (isset($pUrl[5]) && $pUrl[5]=='email')
				    	{
				    		$this->objPublic->modShowMobileUserOffersFlowShareEmail();

				    	}
				    	elseif (isset($pUrl[5]) && $pUrl[5]=='facebook')
				    	{
				    		$this->objPublic->modShowMobileUserOffersFlowShareFB();

				    	}
				    	elseif (isset($pUrl[5]) && $pUrl[5]=='twitter')
				    	{
				    		$this->objPublic->modShowMobileUserOffersFlowShareTwitter();

				    	}
				    	elseif (isset($pUrl[5]) && $pUrl[5]=='sms')
				    	{
				    		$this->objPublic->modShowMobileUserOffersFlowShareSMS();

				    	}
				    	else
				    	{
				    		$this->objPublic->modShowMobileUserOffersFlowShare();

				    	}
				    
				    	
				    }
					else
					{
						$this->objPublic->modShowMobileUserOffersFlow();
					}
					

				}
            	
			}
			else
			{
				$this->objPublic->modShowAnalyticDashoard();
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