<?php 
class cDashboard{

	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objCommon;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/dashboard.model.class.php');
			$this->objDashboard = new mDashboard();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'classes/common.class.php');
			$this->objCommon = new cCommon();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetClientsInfo(){
		try{
			global $config;
			$sendArray=array();
			$requestWsUrl=$config['LIVE_URL'].'webservices/clients/list';
			$outArrData=array();
			$outArrData=$this->objCommon->modCallWebservices($requestWsUrl,$sendArray);
			return $outArrData;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    public function modGetProductsByClient($client_id){
		try{
			global $config;
			$requestWsUrl=$config['LIVE_URL'].'webservices/clients/products/list';
			$sendArray=array();
			$sendArray['client_id']=$client_id;
			$outArrData=array();
			$outArrData=$this->objCommon->modCallWebservices($requestWsUrl,$sendArray);
			return $outArrData;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    public function modGetTriggersByClient($client_id){
		try{
			global $config;
			$requestWsUrl=$config['LIVE_URL'].'webservices/clients/triggers/list';
			$sendArray=array();
			$sendArray['client_id']=$client_id;
			$outArrData=array();
			$outArrData=$this->objCommon->modCallWebservices($requestWsUrl,$sendArray);
			return $outArrData;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetOffersByClient($client_id){
		try{
			global $config;
			$requestWsUrl=$config['LIVE_URL'].'webservices/clients/offers/list';
			$sendArray=array();
			$sendArray['client_id']=$client_id;
			$outArrData=array();
			$outArrData=$this->objCommon->modCallWebservices($requestWsUrl,$sendArray);
			return $outArrData;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetAppUsers(){
		try{
			global $config;
			$requestWsUrl=$config['LIVE_URL'].'webservices/appusers/list';
			$sendArray=array();
			$outArrData=array();
			$outArrData=$this->objCommon->modCallWebservices($requestWsUrl,$sendArray);
			return $outArrData;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		
	public function modDashboard(){
		try{
			global $config;

			//*********** start Seemore CMS code*************//
			$outArrClients=array();
			$outArrClients=$this->modGetClientsInfo();
			for($i=0;$i<count($outArrClients);$i++)
			{
				$outArrProducts = $this->modGetProductsByClient($outArrClients[$i]['id']);
				$outArrClients[$i]['products_total']=count($outArrProducts);
				
				$outArrTriggers = $this->modGetTriggersByClient($outArrClients[$i]['id']);
				$outArrClients[$i]['triggers_total']=count($outArrTriggers);
				
				$outArrOffers = $this->modGetOffersByClient($outArrClients[$i]['id']);
				$outArrClients[$i]['offers_total']=count($outArrOffers);
			}
			
			$outArrAppUsers=array();
			$outArrAppUsers=$this->modGetAppUsers();
			
			
			//*********** End Seemore CMS code*************//

			//*********** End mobile user analytics code*************//


			
			include SRV_ROOT.'views/home/dashboard.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modQuantcastDashboard(){
		try{
			global $config;
			//*********** Start mobile Quantcast code*************//
			//setup authentication of the Quantcast API
			$segmentName="networks";
			$apikey = '2wwtaqtnamvu65sawu34aars'; 
			$secret = 'bd74me66gyM997KVQp6JtS4r'; 
			$timestamp = gmdate('U'); // 1200603038 
			$sig = md5($apikey . $secret . $timestamp); 

			//Requesting parameters
			$sendArray=array();
			$sendArray['api_key']='2wwtaqtnamvu65sawu34aars';	
			$sendArray['secret_key']='bd74me66gyM997KVQp6JtS4r';
			$sendArray['seg_name']='networks';
			$sendArray['sig_key']=$sig;
			$sendArray['p_code']='p-GMU4PxLWARY77';
			$sendArray['app_id']='04rfo14n7u8f1xiz-s7wk616kh7jy4vwh';	
			$sendArray['country_code']='US';	

			//*********** End mobile user analytics code*************//


			
			include SRV_ROOT.'views/quantcast/dashboard.tpl.php';
			
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