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
	public function modDashboard(){
		try{
			global $config;

			//*********** End Seemore CMS code*************//
			$outArrClients = array();
			$outArrAppUsers = array();
			$outArrClients = $this->objDashboard->getAllClients();
			for($i=0;$i<count($outArrClients);$i++)
			{
				$outArrProductsTot[] = $this->objDashboard->getProductsByCID($outArrClients[$i]['id']);
				$outArrTriggersTot[] = $this->objDashboard->getTriggersByCID($outArrClients[$i]['id']);
			}
			$outArrAppUsers = $this->objDashboard->getAllAppUsers();
			//*********** End Seemore CMS code*************//

			//*********** Start mobile user analytics code*************//

			$clientIds = isset($_SESSION['clientIds']) ? $_SESSION['clientIds'] : 0;
			// $start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			// $end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			$start_date=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
			$end_date=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
            $clientSearch=isset($_SESSION['search_client_id']) ? $_SESSION['search_client_id'] :'';
			
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;
			
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/dashboard';
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['login_client_id']=$cid;
			$params['group_id']=$groupId;
			$params['client_search']=$clientSearch;
			$params['clientIds']=$clientIds;
			

			// Create a cURL session
			$curl = curl_init() or die(curl_error());
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); 
			 
			// Submit job
			$submitUrl = "$baseUrl/submit";
			curl_setopt($curl, CURLOPT_URL, $submitUrl);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			$jobId = curl_exec($curl);
			// Print job identifier
			//echo "$jobId\n";

			$arrData=json_decode($jobId,true);

			// print_r($arrData);
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

			$defaultClientId = isset($arrData[0]['total_clients'][0]['id']) ? $arrData[0]['total_clients'][0]['id'] : 0;
            

			//*********** End mobile user analytics code*************//


			
			include SRV_ROOT.'views/home/dashboard.tpl.php';
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