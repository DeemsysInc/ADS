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
		
		require_once SRV_ROOT.'classes/analytics.class.php';
		$this->objAnalytics = new cAnalytics();
		
		require_once SRV_ROOT.'classes/user_analytics.class.php';
		$this->objUserAnalytics = new cUserAnalytics();
		
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
			//print_r( $pUrl);
			$outResults = array();
			$arrCondi = explode("?", $pUrl[1]);
			//print_r( $arrCondi);
			if (isset($arrCondi[0]) && ($arrCondi[0]=='json'))
			{
				if (isset($_REQUEST["sample"]) && ($_REQUEST["sample"]!=''))
				{
					 //android
					 $decoded = json_decode($_REQUEST["sample"],true);	
					 $this->objUserAnalytics->modTracking($decoded);
				 
			    }
			    else
			    {
			    	 //iphone and ipad
				     $decoded = array();
					 $handle = fopen('php://input','r');
					 $jsonInput = fgets($handle);
					 // Decoding JSON into an Array
					 $decoded = json_decode($jsonInput,true);	
					 //$this->objAnalytics->modTrackUserData($decoded);
					 $this->objUserAnalytics->modTracking($decoded);
			    }
				 
			
			}
			else if (isset($arrCondi[0]) && ($arrCondi[0]=='sample')){
				$arrsample=array();
				$arrsample['user_id']=isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
				$arrsample['session_id']=isset($_REQUEST['session_id']) ? $_REQUEST['session_id'] : '';
				$arrsample['screen_path']=isset($_REQUEST['screen_path']) ? $_REQUEST['screen_path'] : '';
				$arrsample['prod_ids']=isset($_REQUEST['prod_ids']) ? $_REQUEST['prod_ids'] : '';
				$arrsample['offer_ids']=isset($_REQUEST['offer_ids']) ? $_REQUEST['offer_ids'] : '';
				$arrsample['device_os']=isset($_REQUEST['device_os']) ? $_REQUEST['device_os'] : '';
				$arrsample['device_type']=isset($_REQUEST['device_type']) ? $_REQUEST['device_type'] : '';
				$arrsample['device_brand']=isset($_REQUEST['device_brand']) ? $_REQUEST['device_brand'] : '';
				$arrsample['device_os_version']=isset($_REQUEST['device_os_version']) ? $_REQUEST['device_os_version'] : '';
				$arrsample['user_address']=isset($_REQUEST['user_address']) ? $_REQUEST['user_address'] : '';
				$arrsample['user_country']=isset($_REQUEST['user_country']) ? $_REQUEST['user_country'] : '';
				$arrsample['user_state']=isset($_REQUEST['user_state']) ? $_REQUEST['user_state'] : '';
				$arrsample['user_city']=isset($_REQUEST['user_city']) ? $_REQUEST['user_city'] : '';
				$arrsample['time_on_screen']=isset($_REQUEST['time_on_screen']) ? $_REQUEST['time_on_screen'] : '';
				$arrsample['version']=isset($_REQUEST['version_name']) ? $_REQUEST['version_name'] : '';
				
				$outResults = $this->objUserAnalytics->modTracking($arrsample);
				//$outResults = "success";
				/*if($outResults == "success"){
					echo "mano";
				}*/
				//print_r($outResults);
				//echo json_encode($outResults);
			}
			else if (isset($arrCondi[0]) && ($arrCondi[0]=='crash')){
				    echo "crash code condition success";
				    $decoded = array();
					$handle = fopen('php://input','r');
					$jsonInput = fgets($handle);
					// Decoding JSON into an Array
					$decoded = json_decode($jsonInput,true);
					//print_r($decoded);	
					$this->objAnalytics->modtrackErrorCrashData($decoded);

			}

			//
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