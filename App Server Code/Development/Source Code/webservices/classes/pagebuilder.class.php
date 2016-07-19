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
		
		require_once SRV_ROOT.'classes/clients.class.php';
		$this->objClients = new cClients();
		
		require_once SRV_ROOT.'classes/products.class.php';
		$this->objProducts = new cProducts();
		
		require_once SRV_ROOT.'classes/triggers.class.php';
		$this->objTriggers = new cTriggers();
		
		require_once SRV_ROOT.'classes/offers.class.php';
		$this->objOffers = new cOffers();
		
		require_once SRV_ROOT.'classes/users.class.php';
		$this->objUsers = new cUsers();
		
		require_once SRV_ROOT.'classes/config.class.php';
		$this->objConfig = new cConfig();
		$this->getConfig = $this->objConfig->config();
		
	}

	/**** function to get all functions of Header and assign to header template ****/
	public function pageHeader(){
		try{
			global $config;
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get all functions of Content and assign to content template ****/
	public function pageContent($pUrl){
		try{
			global $config;
			//$pAction = isset($pUrl[1]) ? $pUrl[1] : '';
			if(isset($pUrl[1]) && $pUrl[1]=='clients'){
				if(isset($pUrl[2]) && $pUrl[2]=='list'){
					//get list of all clients
					$this->objClients->modGetAllClients();
				}
				else if(isset($pUrl[2]) && $pUrl[2]=='products')
				{
					//get all the list of products based on the client
					$this->objProducts->modGetAllProductsByClient();
				}
				else if(isset($pUrl[2]) && $pUrl[2]=='triggers')
				{
					//get all the list of triggers based on the client
					$this->objTriggers->modGetAllTriggersByClient();
				}
				else if(isset($pUrl[2]) && $pUrl[2]=='offers')
				{
					//get all the list of offers based on the client
					$this->objOffers->modGetAllOffersByClient();
				}
				
			}
			else if(isset($pUrl[1]) && $pUrl[1]=='client_verticals'){
				if(isset($pUrl[2]) && $pUrl[2]=='list'){
					//get list of all client verticals
					$this->objClients->modGetAllClientVerticals();
				}
			}
			else if(isset($pUrl[1]) && $pUrl[1]=='countries'){
				if(isset($pUrl[2]) && $pUrl[2]=='list'){
					//get list of all countries
					$this->objClients->modGetAllCountries();
				}
				else if(isset($pUrl[2]) && $pUrl[2]=='states'){
					//get list of all states by country code
					$this->objClients->modGetStatesByCountry($pUrl);
				}
			}
			else if(isset($pUrl[1]) && $pUrl[1]=='states'){
				if(isset($pUrl[2]) && $pUrl[2]=='list'){
					//get list of all states
					$this->objClients->modGetAllStates();
				}
			}
			else if(isset($pUrl[1]) && $pUrl[1]=='appusers'){
				if(isset($pUrl[2]) && $pUrl[2]=='list'){
					//get list of all app users
					$this->objUsers->modGetAllAppUsers();
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