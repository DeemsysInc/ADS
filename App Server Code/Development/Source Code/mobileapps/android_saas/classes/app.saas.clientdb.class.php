<?php 
class cAppSaasClientDbs{
	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objCommon;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			global $config;
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/app.saas.clientdb.model.class.php');
			$this->objAppSassClientDbs = new mAppSassClientDbs();
			
			//include_once(SRV_ROOT.'classes/thread.class.php');
			
			
			$this->mainDbName = $config['database']['name'];
			$this->markersDbName = $config['database']['name_markers'];
			$this->usersDbName = $config['database']['name_users'];
			$this->userAnalyticsDbName = $config['database']['name_user_analytics'];
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetClientDbConnectionsBasedOnClientId($clientId){
		try{
			global $config;
			$outArrForClients = array();
			$outArrForClients = $this->objAppSassClientDbs->getClientDbConnUsingQueryByClientId($clientId);
			print_r($outArrForClients);
			//echo count($outArrForClients);
			if(count($outArrForClients)>0){
				//client db connections-----------------------
				//$config['database']['prefix_clients'] = 'devarapp_'; 
				$config['database']['name_clients'] = (isset($outArrForClients[0]['client_db_name']) ? $outArrForClients[0]['client_db_name'] : '');
				$config['database']['host_clients'] = isset($outArrForClients[0]['client_db_host']) ? $outArrForClients[0]['client_db_host'] : '';
				$config['database']['user_clients'] = isset($outArrForClients[0]['client_db_username']) ? $outArrForClients[0]['client_db_username'] : '';
				$config['database']['password_clients'] = isset($outArrForClients[0]['client_db_password']) ? $outArrForClients[0]['client_db_password'] : '';
			}
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetClientDbConnectionsBasedOnClosetUserIds($userId){
		try{
			global $config;
			//$program_start_time = time();
			//$thread_a = new Thread("localhost", 80);
			$outArrForClientsForConfig = array();
			$outArrForClients = array();
			$outArrForClients1 = array();
			$outArrForClients1 = $this->objAppSassClientDbs->getClientDbConnUsingQueryByClosetUserId($userId);
			//print_r($outArrForClients1);
			if(count($outArrForClients1)>0){
				for($i=0; $i<count($outArrForClients1); $i++){
					$clientId = isset($outArrForClients1[$i]['client_id']) ? $outArrForClients1[$i]['client_id'] : 0;
					if($clientId!=0){
						$outArrForClients[] = $this->objAppSassClientDbs->getClientDbConnUsingQueryByClientId($clientId);
					}
				}
			}
			//echo count($outArrForClients);
			if(count($outArrForClients)>0){
				//print_r($outArrForClients);
				for($i=0; $i<count($outArrForClients); $i++){
					if(isset($outArrForClients[$i][0]['client_db_name']) && $outArrForClients[$i][0]['client_db_name']!=""){
						$outArrForClientsForConfig['db_name'][] = (isset($outArrForClients[$i][0]['client_db_name']) ? $outArrForClients[$i][0]['client_db_name'] : '');
						$outArrForClientsForConfig['db_host'][] = isset($outArrForClients[$i][0]['client_db_host']) ? $outArrForClients[$i][0]['client_db_host'] : '';
						$outArrForClientsForConfig['db_username'][] = isset($outArrForClients[$i][0]['client_db_username']) ? $outArrForClients[$i][0]['client_db_username'] : '';
						$outArrForClientsForConfig['db_password'][] = isset($outArrForClients[$i][0]['client_db_password']) ? $outArrForClients[$i][0]['client_db_password'] : '';
					}
				}
			}
			//print_r($config);
			//print_r($outArrForClientsForConfig);
			return $outArrForClientsForConfig;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetClientDbConnectionsBasedOnWishListUserIds($userId){
		try{
			global $config;
			//$program_start_time = time();
			//$thread_a = new Thread("localhost", 80);
			$outArrForClientsForConfig = array();
			$outArrForClients = array();
			$outArrForClients1 = array();
			$outArrForClients1 = $this->objAppSassClientDbs->getClientDbConnUsingQueryByWishListUserId($userId);
			//print_r($outArrForClients1); 
			if(count($outArrForClients1)>0){
				for($i=0; $i<count($outArrForClients1); $i++){
					$clientId = isset($outArrForClients1[$i]['client_id']) ? $outArrForClients1[$i]['client_id'] : 0;
					if($clientId!=0){
						$outArrForClients[] = $this->objAppSassClientDbs->getClientDbConnUsingQueryByClientId($clientId);
					}
				}
			}
			//echo count($outArrForClients);
			if(count($outArrForClients)>0){
				//print_r($outArrForClients);
				for($i=0; $i<count($outArrForClients); $i++){
					if(isset($outArrForClients[$i][0]['client_db_name']) && $outArrForClients[$i][0]['client_db_name']!=""){
						$outArrForClientsForConfig['db_name'][] = (isset($outArrForClients[$i][0]['client_db_name']) ? $outArrForClients[$i][0]['client_db_name'] : '');
						$outArrForClientsForConfig['db_host'][] = isset($outArrForClients[$i][0]['client_db_host']) ? $outArrForClients[$i][0]['client_db_host'] : '';
						$outArrForClientsForConfig['db_username'][] = isset($outArrForClients[$i][0]['client_db_username']) ? $outArrForClients[$i][0]['client_db_username'] : '';
						$outArrForClientsForConfig['db_password'][] = isset($outArrForClients[$i][0]['client_db_password']) ? $outArrForClients[$i][0]['client_db_password'] : '';
					}
				}
			}
			//print_r($config);
			//print_r($outArrForClientsForConfig);
			return $outArrForClientsForConfig;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetClientDbConnectionsBasedOnMyOfferUserIds($userId){
		try{
			global $config;
			//$program_start_time = time();
			//$thread_a = new Thread("localhost", 80);
			$outArrForClientsForConfig = array();
			$outArrForClients = array();
			$outArrForClients1 = array();
			$outArrForClients1 = $this->objAppSassClientDbs->getClientDbConnUsingQueryByMyOfferUserId($userId);
			//print_r($outArrForClients1);
			if(count($outArrForClients1)>0){
				for($i=0; $i<count($outArrForClients1); $i++){
					$clientId = isset($outArrForClients1[$i]['client_id']) ? $outArrForClients1[$i]['client_id'] : 0;
					if($clientId!=0){
						$outArrForClients[] = $this->objAppSassClientDbs->getClientDbConnUsingQueryByClientId($clientId);
					}
				}
			}
			//echo count($outArrForClients);
			if(count($outArrForClients)>0){
				//print_r($outArrForClients);
				for($i=0; $i<count($outArrForClients); $i++){
					if(isset($outArrForClients[$i][0]['client_db_name']) && $outArrForClients[$i][0]['client_db_name']!=""){
						$outArrForClientsForConfig['db_name'][] = (isset($outArrForClients[$i][0]['client_db_name']) ? $outArrForClients[$i][0]['client_db_name'] : '');
						$outArrForClientsForConfig['db_host'][] = isset($outArrForClients[$i][0]['client_db_host']) ? $outArrForClients[$i][0]['client_db_host'] : '';
						$outArrForClientsForConfig['db_username'][] = isset($outArrForClients[$i][0]['client_db_username']) ? $outArrForClients[$i][0]['client_db_username'] : '';
						$outArrForClientsForConfig['db_password'][] = isset($outArrForClients[$i][0]['client_db_password']) ? $outArrForClients[$i][0]['client_db_password'] : '';
					}
				}
			}
			//print_r($config);
			//print_r($outArrForClientsForConfig);
			return $outArrForClientsForConfig;
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