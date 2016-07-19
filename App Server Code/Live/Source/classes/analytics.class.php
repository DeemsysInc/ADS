<?php 
class cAnalytics{

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
			require_once(SRV_ROOT.'model/clients.model.class.php');
			$this->objClients = new mClients();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'classes/common.class.php');
			$this->objCommon = new cCommon();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/analytics.model.class.php');
			$this->objAnalyticsQuery = new mAnalytics();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowClientAnalyticsByProducts(){
		try{	
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : null;
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] : null;
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			//print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			//get clinet info from clinet ids
			$outArrGetClientInfo = array();
			$outArrGetClientInfo = $this->objClients->getClientById($cid);
			//print_r($outArrGetClientInfo);
			
			//all clients
			$outArrGetAllClients = array();
			$outArrGetAllClients = $this->objClients->getAllClients();
			
			//analytic results 
			define('ga_email','didi4team');
			define('ga_password','didi@123');
			define('ga_profile_id','77150719');
			//$filter='ga:screenName=~^/product/'.$cid.'/';
			$filter='ga:screenName=~^/product/';
			
			require SRV_ROOT.'plugins/gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
			$ga->requestReportData(ga_profile_id,array('ScreenName','date'),array('screenviews','uniqueScreenviews','screenviewsPerSession','timeOnScreen','avgScreenviewDuration'), $sort_metric=null, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$outArrGetProducts = array();	
			
			$results=$ga->getResults();
				$arrData=array();
		      foreach($ga->getResults() as $result):		
			   $screenName=$result->getScreenName();
			   $arrExplScrNames=explode('/',$screenName);
			   //print_r($arrExplScrNames);
			   $productType=isset($arrExplScrNames[2]) ? $arrExplScrNames[2]: '';
			   //echo $productType;
			   
			
				if (is_numeric($productType))
				{
				  $cId=isset($arrExplScrNames[2]) ? $arrExplScrNames[2]: '';
					 if($cId==$cid)
					 {
					   $arrData['report']['date'][]=$result->getDate();
					   $arrData['report']['screen_name'][]=$result->getscreenName();
					   $arrData['report']['screen_views'][]=$result->getScreenViews();
					   $arrData['report']['unique_screen_views'][]=$result->getuniqueScreenviews();
					   $arrData['report']['screen_views_per_session'][]=$result->getscreenviewsPerSession();
					   $arrData['report']['time_on_screen'][]=$result->gettimeOnScreen();
					   $arrData['report']['avg_screen_view_duration'][]=$result->getavgScreenviewDuration();
					 }
	   
				}
				else
				{
				
				   if($productType=="details")
				   {
					 $cId=isset($arrExplScrNames[3]) ? $arrExplScrNames[3]: '';
					 if($cId==$cid)
					 {
					   $arrData['report']['date'][]=$result->getDate();
					   $arrData['report']['screen_name'][]=$result->getscreenName();
					   $arrData['report']['screen_views'][]=$result->getScreenViews();
					   $arrData['report']['unique_screen_views'][]=$result->getuniqueScreenviews();;
					   $arrData['report']['screen_views_per_session'][]=$result->getscreenviewsPerSession();
					   $arrData['report']['time_on_screen'][]=$result->gettimeOnScreen();
					   $arrData['report']['avg_screen_view_duration'][]=$result->getavgScreenviewDuration();
					 }
			 
				   }
		
				}
            endforeach;
            
			//print_r($arrData);
			include SRV_ROOT.'views/clients/client_analytics_products.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowAnalyticDashboard(){
		try{	
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			//print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			//get clinet info from clinet ids
			$outArrGetClientInfo = array();
			$outArrGetClientInfo = $this->objClients->getClientById($cid);
			//print_r($outArrGetClientInfo);
			
			//all clients
			$outArrGetAllClients = array();
			$outArrGetAllClients = $this->objClients->getAllClients();
			
			//analytic results 
			define('ga_email','didi4team');
			define('ga_password','didi@123');
			define('ga_profile_id','77150719');
			if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			{
			  $filter=array('ga:screenName=~/product/details/'.$cid.'/,ga:screenName=~/product/'.$cid.'/');
			}
			else
			{
			  $filter=null;
			}
			
			require SRV_ROOT.'plugins/gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
			
			//get active users info
			
			$sort_metric=array('date');
			$ga->requestReportData(ga_profile_id,array('date'),array('visitors'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$activeUsersresults=$ga->getResults();
			
			
			//get Country /territary data
			$ga->requestReportData(ga_profile_id,array('country'),array('visits'), $sort_metric=null, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$Countryresults=$ga->getResults();
			
			//get Mobile Device Info
			$ga->requestReportData(ga_profile_id,array('mobileDeviceInfo'),array('visits'), $sort_metric=null, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$TopdeviceModelsresults=$ga->getResults();
			$totalSessions=0;
			 foreach($TopdeviceModelsresults as $result) {
			    $totalSessions += $result->getvisits();
				 
			 }
			
			//get Screens Info
			$sort_metric="-screenviews";
			$ga->requestReportData(ga_profile_id,array('screenName'),array('screenviews'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=5);
			$ScreensResults=$ga->getResults();
			
			//get user engagement Info
			$sort_metric=array('date');
			$ga->requestReportData(ga_profile_id,array('date'),array('avgTimeOnSite','screenviewspersession'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$userEngagementResults=$ga->getResults();
			
			//print_r($userEngagementResults);
		    
			include SRV_ROOT.'views/clients/client_analytics_dashboard.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowActiveUsersOverView(){
		try{	
			global $config;
			
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			//print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			//get clinet info from clinet ids
			$outArrGetClientInfo = array();
			$outArrGetClientInfo = $this->objClients->getClientById($cid);
			//print_r($outArrGetClientInfo);
			
			//all clients
			$outArrGetAllClients = array();
			$outArrGetAllClients = $this->objClients->getAllClients();
			
			//analytic results 
			define('ga_email','didi4team');
			define('ga_password','didi@123');
			define('ga_profile_id','77150719');
			if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			{
			  $filter=array('ga:screenName=~/product/details/'.$cid.'/,ga:screenName=~/product/'.$cid.'/');
			}
			else
			{
			  $filter=null;
			}
			
			require SRV_ROOT.'plugins/gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
			
			//get active users info
			
			$sort_metric=array('date');
			$ga->requestReportData(ga_profile_id,array('date'),array('visitors','visits','screenViews','screenviewspersession','avgTimeOnSite'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$activeUsersresults=$ga->getResults();
			
			//get data of new visitors sessions percentage for image line chart
			$sort_metric=array('date');
			//$filter=array('ga:visitorType=~New Visitor');
			$ga->requestReportData(ga_profile_id,array('date'),array('visits'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$percentNewUsersSessionforChart=$ga->getResults();
			
			//get active users info by month
			$sort_metric=array('month');
			$ga->requestReportData(ga_profile_id,array('month'),array('visitors'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$activeUsersresultsByMonth=$ga->getResults();
			$totalActiveUsers=0;
			 foreach($activeUsersresultsByMonth as $result) {
			    $totalActiveUsers += $result->getvisitors();
				 
			 }
			// print_r($totalActiveUsers);
			// echo "<br>";
			
			//get active users info by month
			$sort_metric=array('visitorType');
			$ga->requestReportData(ga_profile_id,array('visitorType'),array('visits'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$activeUsersSessionsByMonth=$ga->getResults();
			$totalActiveUsersSessions=0;
			$newVisitorsSessions=0;
			 foreach($activeUsersSessionsByMonth as $result) {
			    $totalActiveUsersSessions += $result->getvisits();
				 if($result->getvisitorType()=="New Visitor")
				 {
				   $newVisitorsSessions=$result->getvisits();
				 }else if($result->getvisitorType()=="Returning Visitor")
				 {
				   $retVisitorsSessions=$result->getvisits();
				 }
				 
			 }
			 if(!empty($totalActiveUsersSessions))
			 {
			  $percentNewVisitorsSessions=round(($newVisitorsSessions/$totalActiveUsersSessions)*100,2).'%';
			 }
			//print_r($percentNewVisitorsSessions);
			
			
			// echo "<br>";
			
			//get screenViews  users info by month
			$sort_metric=array('month');
			$ga->requestReportData(ga_profile_id,array('month'),array('screenViews'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$screenViewsByMonth=$ga->getResults();
			$totalScreenViews=0;
			 foreach($screenViewsByMonth as $result) {
			    $totalScreenViews += $result->getscreenViews();
				 
			 }
			//print_r($totalScreenViews);
			//echo "<br>";
			
			//get Screens / Session  users info by month
			$sort_metric=array('month');
			$ga->requestReportData(ga_profile_id,array('month'),array('screenviewspersession'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$screenviewspersessionByMonth=$ga->getResults();
			$totalscreenViewsperSession=0;
			 foreach($screenviewspersessionByMonth as $result) {
			    $totalscreenViewsperSession += $result->getscreenviewspersession();
				 
			 }
			 $avgscreenviewsperSession=0;
			 $avgscreenviewsperSession=round($totalscreenViewsperSession/count($screenviewspersessionByMonth),2);
			 
			//print_r($avgscreenviewsperSession);
			//echo "<br>";
			
			//get Avg. Session Duration  users info by month
			$sort_metric=array('month');
			$ga->requestReportData(ga_profile_id,array('month'),array('avgTimeOnSite'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$avgTimeOnSiteByMonth=$ga->getResults();
			$totalavgTimeOnSite=0;
			 foreach($avgTimeOnSiteByMonth as $result) {
			    $totalavgTimeOnSite += $result->getavgTimeOnSite();
				 
			 }
			 $avgTimeOnSite=0;
			 $avgTimeOnSite=round($totalavgTimeOnSite/count($avgTimeOnSiteByMonth),2);
			 
			//print_r($avgTimeOnSite	);
			//echo "<br>";
			
			
			include SRV_ROOT.'views/clients/analytics_acitve_users.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowVisitorsGeoOverView(){
		try{	
			global $config;
			
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			//print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			//get clinet info from clinet ids
			$outArrGetClientInfo = array();
			$outArrGetClientInfo = $this->objClients->getClientById($cid);
			//print_r($outArrGetClientInfo);
			
			//all clients
			$outArrGetAllClients = array();
			$outArrGetAllClients = $this->objClients->getAllClients();
			
			//analytic results 
			define('ga_email','didi4team');
			define('ga_password','didi@123');
			define('ga_profile_id','77150719');
			if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			{
			  $filter=array('ga:screenName=~/product/details/'.$cid.'/,ga:screenName=~/product/'.$cid.'/');
			}
			else
			{
			  $filter=null;
			}
			
			require SRV_ROOT.'plugins/gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
			
			//get Country /territary data
			$ga->requestReportData(ga_profile_id,array('country'),array('visits','screenViews','screenviewspersession','avgTimeOnSite'), $sort_metric=null, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$Countryresults=$ga->getResults();
			
			include SRV_ROOT.'views/clients/analytics_visitors_geo.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowVisitorsDeviceOverView(){
		try{	
			global $config;
			
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			//print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			//get clinet info from clinet ids
			$outArrGetClientInfo = array();
			$outArrGetClientInfo = $this->objClients->getClientById($cid);
			//print_r($outArrGetClientInfo);
			
			//all clients
			$outArrGetAllClients = array();
			$outArrGetAllClients = $this->objClients->getAllClients();
			
			//analytic results 
			define('ga_email','didi4team');
			define('ga_password','didi@123');
			define('ga_profile_id','77150719');
			if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			{
			  $filter=array('ga:screenName=~/product/details/'.$cid.'/,ga:screenName=~/product/'.$cid.'/');
			}
			else
			{
			  $filter=null;
			}
			
			require SRV_ROOT.'plugins/gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
			
			//get mobile device and network data
			$sort_metric=array('operatingSystemVersion');
			$ga->requestReportData(ga_profile_id,array('operatingSystem','operatingSystemVersion'),array('visits'), $sort_metric, $filter, $start_date, $end_date, $start_index=1, $max_results=300);
			$deviceInfoArray=$ga->getResults();
			
			include SRV_ROOT.'views/clients/analytics_visitors_device.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function seoUrl($string)
	{
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
    }
    function createDateRangeArray($strDateFrom,$strDateTo)
	{
	   try{
		// takes two dates formatted as YYYY-MM-DD and creates an
		// inclusive array of the dates between the from and to dates.

		// could test validity of dates here but I'm already doing
		// that in the main script

		$aryRange=array();

		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
		$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

		if ($iDateTo>=$iDateFrom)
		{
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo)
			{
				$iDateFrom+=86400; // add 24 hours
				array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
		return $aryRange;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetOfferIds($offerId,$cId){
		try{	
			global $config;
			$outArrGetOfferInfo=array();
		    $outArrGetOfferInfo = $this->objAnalyticsQuery->getOfferByOffID($offerId);
			if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
		    {
		    	//client login
		    	if(isset($outArrGetOfferInfo[0]['client_id']) && $outArrGetOfferInfo[0]['client_id']==$cId)
				{
				
				    //$arrDataScannedOffers[$k]=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
				    $arrOfferId=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
				    
					}
			}
			else
			{
			    //$arrDataScannedOffers[$k]=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
				$arrOfferId=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
			}
			return $arrOfferId;


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetProductIds($productId,$cId){
		try{	
			global $config;
			$outArrGetProdInfo=array();
		    $outArrGetProdInfo = $this->objAnalyticsQuery->getProductByPID($productId);
			//print_r($outArrGetProdInfo);
			if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
		    {
		    
				if(isset($outArrGetProdInfo[0]['client_id']) && $outArrGetProdInfo[0]['client_id']==$cId)
				{
				
				    $arrProductId=isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';
				}
			}
			else
			{
			    $arrProductId=isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';
				 
			}
			return $arrProductId;


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowUserCharts(){
		try{	
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			
			

			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);

			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$arrDataForGraph = array();
			$outArrGetTrackingDataByDate = array();
			$arrOfferIds=array();
			$arrDataMyOffers=array();
			$arrTotalOfferIds=array();
			$arrrActiveUsers=array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrDataTotalOffids=array();
			$arrTotalClosetPidsForViews=array();
			$allMyOfferIds=array();
			
			$arrDataRedeemOffers=array();
            $arrDataTotalRedeemOfferids=array();
			$arrDataTotalApplyOfferids=array();
			$arrDataTotalShareOfferids=array();
			$arrDataTotalShareEmailOfferids=array();
			$arrDataTotalShareFacebookOfferids=array();
			$arrDataTotalShareTwitterOfferids=array();
			$arrDataTotalFinancialOfferids=array();
			$arrDataScannedOffers=array();
			$arrDataTotalMyOffids=array();
			$arrDataTotalScannedOfferids=array();
			

			$arrDataTotalPIdsWishlists=array();
			$arrDataTotalSharedPids=array();
			$arrDataTotalSharedEmailPids=array();
			$arrDataTotalSharedFacebookPids=array();
			$arrDataTotalSharedTwitterPids=array();
			$arrDataTotalCartPids=array();
            $arrDataTotalScannedPids=array();
			
            $screenName='';

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    //$arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
				$screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='offers')
					{
						$scannedOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    $arrDataTotalScannedOfferids[]=$this->modGetOfferIds($scannedOfferId,$cid);
						
					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						//financial related
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='offers')
						{
							//offers
							$financialOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialOfferids[]=$this->modGetOfferIds($financialOfferId,$cid);

						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
							
						}


					}
					//my offers
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
					{
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='redeem')
					    {
					    	//redeem
					    	$redeemOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalRedeemOfferids[]=$this->modGetOfferIds($redeemOfferId,$cid);
						}
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='apply')
					    {
					    	//apply
					    	$applyOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    	$arrDataTotalApplyOfferids[]=$this->modGetOfferIds($applyOfferId,$cid);
                        }
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareoffer')
					    {
					    	if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$shareOfferEmailId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareEmailOfferids[]=$this->modGetOfferIds($shareOfferEmailId,$cid);


							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	$shareOfferFacebookId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareFacebookOfferids[]=$this->modGetOfferIds($shareOfferFacebookId,$cid);

							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	$shareOfferTwitterId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareTwitterOfferids[]=$this->modGetOfferIds($shareOfferTwitterId,$cid);

							}
					        else
					        {
					        	//shareoffers
					            $shareOfferId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					            $arrDataTotalShareOfferids[]=$this->modGetOfferIds($shareOfferId,$cid);

							}

					    }

					    if(!empty($arrScreenInfo[$k]['offer_ids']))
					    {
					       
							$arrtemp=explode('|',$arrScreenInfo[$k]['offer_ids']);
							//print_r($arrtemp);
						    for($l=0;$l<count($arrtemp);$l++)
							{
							 $arrDataTotalMyOffids[]=$this->modGetOfferIds($arrtemp[$l],$cid);
				 
							}
							
						}
					
					 }
					
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
					 {

					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='details')
					    {
					    	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='cart')
					        {
					        	$pId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalCartPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalPids[]=$this->modGetProductIds($pId,$cid);

					        }
						    
						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareproduct')
					    {
						    if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedEmailPids[]=$this->modGetProductIds($pId,$cid);
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedFacebookPids[]=$this->modGetProductIds($pId,$cid);

								
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
					        	$arrDataTotalSharedTwitterPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalSharedPids[]=$this->modGetProductIds($pId,$cid);
								
					        }


						    
						}
						else
						{
							$scannedPId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalScannedPids[]=$this->modGetProductIds($scannedPId,$cid);
							
						}	
						
					 }
					 
					 if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='mycloset')
					 {
					 	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='wishlists')
					    {
					    	//wishlists
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPIdsWishlists[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }
					    }
					    else
					    {
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPdsCloset[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }

					    }
					 
					    
					 }
					 
					 
					 
				   } 
		      
			  }
			  
			  
			 
			   $arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			   
			  
			   //products category code
			   $productDetailsIdsArray=array_slice($arrDataProducts,0);
			   //$arrDataProducts=array();
			   $productScannedIdsArray=array_slice($arrDataTotalScannedPids,0);
			   $pIdsClosetArray=array_slice($arrDataTotalPdsCloset,0);
			   $pIdsClosetWishlistArray=array_slice($arrDataTotalPIdsWishlists,0);
			   $pIdsSharedArray=array_slice($arrDataTotalSharedPids,0);
			   $pIdsSharedEmailArray=array_slice($arrDataTotalSharedEmailPids,0);
			   $pIdsSharedFacebookArray=array_slice($arrDataTotalSharedFacebookPids,0);
			   $pIdsSharedTwitterArray=array_slice($arrDataTotalSharedTwitterPids,0);
			   $pIdsCartArray=array_slice($arrDataTotalCartPids,0);
			   
			   //$arrDataPdsCloset=array();
			   $arrSumOfProducts=array_merge($productDetailsIdsArray, $pIdsClosetArray,$productScannedIdsArray,$pIdsClosetWishlistArray,$pIdsSharedArray,$pIdsSharedEmailArray,$pIdsSharedFacebookArray,$pIdsSharedTwitterArray,$pIdsCartArray);
			   $arrDataForGraph[$i]['productids']=count(array_slice($arrSumOfProducts,0));
			   
			   //offers category code
			   $redeemOfferIdsArray=array_slice($arrDataTotalRedeemOfferids,0);
			   $applyOfferIdsArray=array_slice($arrDataTotalApplyOfferids,0);
			   $shareOfferIdsArray=array_slice($arrDataTotalShareOfferids,0);
			   $shareEmailOfferIdsArray=array_slice($arrDataTotalShareEmailOfferids,0);
			   $shareFacebookOfferIdsArray=array_slice($arrDataTotalShareFacebookOfferids,0);
			   $shareTwitterOfferIdsArray=array_slice($arrDataTotalShareTwitterOfferids,0);
			   $financialOfferIdsArray=array_slice($arrDataTotalFinancialOfferids,0);
			   $scannedOfferIdsArray=array_slice($arrDataTotalScannedOfferids,0);

			   
			   
			   //$arrDataTotalRedeemOfferids=array();
               $myOffersArray=array_slice($arrDataTotalMyOffids,0);
			   //$arrDataTotalMyOffids=array();
			   $arrSumOfOffers=array_merge($redeemOfferIdsArray, $myOffersArray,$applyOfferIdsArray,$shareOfferIdsArray,$shareEmailOfferIdsArray,$shareFacebookOfferIdsArray,$shareTwitterOfferIdsArray,$financialOfferIdsArray,$scannedOfferIdsArray);
               $arrDataForGraph[$i]['offerids']=count(array_slice($arrSumOfOffers,0));
			   
			   

		    
			} 
			
		   return $arrDataForGraph;
			
			

			}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 public function modShowUserTrackingDashboard(){
		try{	
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			
			

			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);

			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$arrDataForGraph = array();
			$outArrGetTrackingDataByDate = array();
			$arrOfferIds=array();
			$arrDataMyOffers=array();
			$arrTotalOfferIds=array();
			$arrrActiveUsers=array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrDataTotalOffids=array();
			$arrTotalClosetPidsForViews=array();
			$allMyOfferIds=array();
			
			$arrDataRedeemOffers=array();
            $arrDataTotalRedeemOfferids=array();
			$arrDataTotalApplyOfferids=array();
			$arrDataTotalShareOfferids=array();
			$arrDataTotalShareEmailOfferids=array();
			$arrDataTotalShareFacebookOfferids=array();
			$arrDataTotalShareTwitterOfferids=array();
			$arrDataTotalFinancialOfferids=array();
			$arrDataScannedOffers=array();
			$arrDataTotalMyOffids=array();
			$arrDataTotalScannedOfferids=array();
			

			$arrDataTotalPIdsWishlists=array();
			$arrDataTotalSharedPids=array();
			$arrDataTotalSharedEmailPids=array();
			$arrDataTotalSharedFacebookPids=array();
			$arrDataTotalSharedTwitterPids=array();
			$arrDataTotalCartPids=array();
            $arrDataTotalScannedPids=array();
			
            $screenName='';

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    //$arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
				$screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='offers')
					{
						$scannedOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    $arrDataTotalScannedOfferids[]=$this->modGetOfferIds($scannedOfferId,$cid);
						
					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						//financial related
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='offers')
						{
							//offers
							$financialOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialOfferids[]=$this->modGetOfferIds($financialOfferId,$cid);

						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
							
						}


					}
					//my offers
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
					{
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='redeem')
					    {
					    	//redeem
					    	$redeemOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalRedeemOfferids[]=$this->modGetOfferIds($redeemOfferId,$cid);
						}
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='apply')
					    {
					    	//apply
					    	$applyOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    	$arrDataTotalApplyOfferids[]=$this->modGetOfferIds($applyOfferId,$cid);
                        }
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareoffer')
					    {
					    	if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$shareOfferEmailId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareEmailOfferids[]=$this->modGetOfferIds($shareOfferEmailId,$cid);


							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	$shareOfferFacebookId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareFacebookOfferids[]=$this->modGetOfferIds($shareOfferFacebookId,$cid);

							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	$shareOfferTwitterId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareTwitterOfferids[]=$this->modGetOfferIds($shareOfferTwitterId,$cid);

							}
					        else
					        {
					        	//shareoffers
					            $shareOfferId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					            $arrDataTotalShareOfferids[]=$this->modGetOfferIds($shareOfferId,$cid);

							}

					    }

					    if(!empty($arrScreenInfo[$k]['offer_ids']))
					    {
					       
							$arrtemp=explode('|',$arrScreenInfo[$k]['offer_ids']);
							//print_r($arrtemp);
						    for($l=0;$l<count($arrtemp);$l++)
							{
							 $arrDataTotalMyOffids[]=$this->modGetOfferIds($arrtemp[$l],$cid);
				 
							}
							
						}
					
					 }
					
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
					 {

					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='details')
					    {
					    	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='cart')
					        {
					        	$pId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalCartPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalPids[]=$this->modGetProductIds($pId,$cid);

					        }
						    
						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareproduct')
					    {
						    if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedEmailPids[]=$this->modGetProductIds($pId,$cid);
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedFacebookPids[]=$this->modGetProductIds($pId,$cid);

								
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
					        	$arrDataTotalSharedTwitterPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalSharedPids[]=$this->modGetProductIds($pId,$cid);
								
					        }


						    
						}
						else
						{
							$scannedPId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalScannedPids[]=$this->modGetProductIds($scannedPId,$cid);
							
						}	
						
					 }
					 
					 if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='mycloset')
					 {
					 	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='wishlists')
					    {
					    	//wishlists
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPIdsWishlists[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }
					    }
					    else
					    {
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPdsCloset[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }

					    }
					 
					    
					 }
					 
					 
					 
				   } 
		      
			  }
			  
			  
			 
			   $arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			   
			  
			   //products category code
			   $productDetailsIdsArray=array_slice($arrDataProducts,0);
			   //$arrDataProducts=array();
			   $productScannedIdsArray=array_slice($arrDataTotalScannedPids,0);
			   $pIdsClosetArray=array_slice($arrDataTotalPdsCloset,0);
			   $pIdsClosetWishlistArray=array_slice($arrDataTotalPIdsWishlists,0);
			   $pIdsSharedArray=array_slice($arrDataTotalSharedPids,0);
			   $pIdsSharedEmailArray=array_slice($arrDataTotalSharedEmailPids,0);
			   $pIdsSharedFacebookArray=array_slice($arrDataTotalSharedFacebookPids,0);
			   $pIdsSharedTwitterArray=array_slice($arrDataTotalSharedTwitterPids,0);
			   $pIdsCartArray=array_slice($arrDataTotalCartPids,0);
			   
			   //$arrDataPdsCloset=array();
			   $arrSumOfProducts=array_merge($productDetailsIdsArray, $pIdsClosetArray,$productScannedIdsArray,$pIdsClosetWishlistArray,$pIdsSharedArray,$pIdsSharedEmailArray,$pIdsSharedFacebookArray,$pIdsSharedTwitterArray,$pIdsCartArray);
			   $arrDataForGraph[$i]['productids']=count(array_slice($arrSumOfProducts,0));
			   
			   //offers category code
			   $redeemOfferIdsArray=array_slice($arrDataTotalRedeemOfferids,0);
			   $applyOfferIdsArray=array_slice($arrDataTotalApplyOfferids,0);
			   $shareOfferIdsArray=array_slice($arrDataTotalShareOfferids,0);
			   $shareEmailOfferIdsArray=array_slice($arrDataTotalShareEmailOfferids,0);
			   $shareFacebookOfferIdsArray=array_slice($arrDataTotalShareFacebookOfferids,0);
			   $shareTwitterOfferIdsArray=array_slice($arrDataTotalShareTwitterOfferids,0);
			   $financialOfferIdsArray=array_slice($arrDataTotalFinancialOfferids,0);
			   $scannedOfferIdsArray=array_slice($arrDataTotalScannedOfferids,0);

			   
			   
			   //$arrDataTotalRedeemOfferids=array();
               $myOffersArray=array_slice($arrDataTotalMyOffids,0);
			   //$arrDataTotalMyOffids=array();
			   $arrSumOfOffers=array_merge($redeemOfferIdsArray, $myOffersArray,$applyOfferIdsArray,$shareOfferIdsArray,$shareEmailOfferIdsArray,$shareFacebookOfferIdsArray,$shareTwitterOfferIdsArray,$financialOfferIdsArray,$scannedOfferIdsArray);
               //$arrDataForGraph[$i]['offerids']=count(array_slice($arrSumOfOffers,0));
			   
			   

		    
			} 
			
		   echo json_encode($arrDataForGraph);
			
			

			}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

    public function modShowMobileUserTrackingData(){
		try{	
			global $config;
			$clientIds = isset($_SESSION['clientIds']) ? $_SESSION['clientIds'] : 0;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

			$defaultClientId = isset($arrData[0]['total_clients'][0]['id']) ? $arrData[0]['total_clients'][0]['id'] : 0;
			//$_SESSION['default_clientid'] = $defaultClientId;



            include SRV_ROOT.'views/analytics/user_tracking_data_dashboard.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    public function modShowMobileUseTrackDataByProducts($client_id,$start_date,$end_date,$searchByValOrPercent){
		try{	
			global $config;

			$cid = isset($_SESSION['clientIds']) ? $_SESSION['clientIds'] : 0;
			// $start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			// $end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			 $groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/byproducts';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['group_id']=$groupId;
			$params['client_id']=$client_id;
			$params['searchByValOrPercent']=$searchByValOrPercent;
			

			
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

			include SRV_ROOT.'views/analytics/user_tracking_data_by_products.tpl.php';


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    function mergeArrays($filenames, $titles, $descriptions) {
    $result = array();

    foreach ( $filenames as $key=>$name ) {
        $result[] = array( 'filename' => $name, 'title' => $titles[$key], 'descriptions' => $descriptions[ $key ] );
    }

    return $result;
}
    public function modShowMobileUseTrackDataByOffers($client_id,$start_date,$end_date){
		try{	
			global $config;

			$cid = isset($_SESSION['clientIds']) ? $_SESSION['clientIds'] : 0;
			// $start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			// $end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/byoffers';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['group_id']=$groupId;
			$params['client_id']=$client_id;
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);
			
		    /* $start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
		    //print_r($outArrClientUsers);
			
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$outArrGetTrackingDataByDate = array();
			$arrDataTotalOffids=array();
			$allMyOfferIds=array();
			$arrDataRedeemOffers=array();
            $arrDataTotalRedeemOfferids=array();
			$arrDataTotalApplyOfferids=array();
			$arrDataTotalShareOfferids=array();
			$arrDataTotalShareEmailOfferids=array();
			$arrDataTotalShareFacebookOfferids=array();
			$arrDataTotalShareTwitterOfferids=array();
			$arrDataTotalFinancialOfferids=array();
			$arrDataScannedOffers=array();
            $arrDataTotalScannedOfferids=array();
			

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    //$arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
				$screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='offers')
					{
						$scannedOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    $arrDataTotalScannedOfferids[]=$this->modGetOfferIds($scannedOfferId,$cid);
						
					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						//financial related
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='offers')
						{
							//offers
							$financialOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialOfferids[]=$this->modGetOfferIds($financialOfferId,$cid);

						}


					}
					//my offers
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
					{
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='redeem')
					    {
					    	//redeem
					    	$redeemOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalRedeemOfferids[]=$this->modGetOfferIds($redeemOfferId,$cid);
						}
					    else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='apply')
					    {
					    	//apply
					    	$applyOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    	$arrDataTotalApplyOfferids[]=$this->modGetOfferIds($applyOfferId,$cid);
                        }
					    else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareoffer')
					    {
					    	if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$shareOfferEmailId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareEmailOfferids[]=$this->modGetOfferIds($shareOfferEmailId,$cid);


							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	$shareOfferFacebookId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareFacebookOfferids[]=$this->modGetOfferIds($shareOfferFacebookId,$cid);

							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	$shareOfferTwitterId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareTwitterOfferids[]=$this->modGetOfferIds($shareOfferTwitterId,$cid);

							}
					        else
					        {
					        	//shareoffers
					            $shareOfferId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					            $arrDataTotalShareOfferids[]=$this->modGetOfferIds($shareOfferId,$cid);

							}

					    }
					    else 
					    {
					       if(!empty($arrScreenInfo[$k]['offer_ids']))
					       {
						       	$arrtemp=explode('|',$arrScreenInfo[$k]['offer_ids']);
								//print_r($arrtemp);
							    for($l=0;$l<count($arrtemp);$l++)
								{
								 $arrDataTotalMyOffids[]=$this->modGetOfferIds($arrtemp[$l],$cid);
					 
								}
					       }
						}
					
					 }
					
				   } 
		      
			  }
			  
			  
			 
			   $arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			   
			  
			   
			   
			   //offers category code
			   $redeemOfferIdsArray=array_slice($arrDataTotalRedeemOfferids,0);
			   $applyOfferIdsArray=array_slice($arrDataTotalApplyOfferids,0);
			   $shareOfferIdsArray=array_slice($arrDataTotalShareOfferids,0);
			   $shareEmailOfferIdsArray=array_slice($arrDataTotalShareEmailOfferids,0);
			   $shareFacebookOfferIdsArray=array_slice($arrDataTotalShareFacebookOfferids,0);
			   $shareTwitterOfferIdsArray=array_slice($arrDataTotalShareTwitterOfferids,0);
			   $financialOfferIdsArray=array_slice($arrDataTotalFinancialOfferids,0);
			   $scannedOfferIdsArray=array_slice($arrDataTotalScannedOfferids,0);

			   
			   
			   //$arrDataTotalRedeemOfferids=array();
               $myOffersArray=array_slice($arrDataTotalMyOffids,0);
			   //$arrDataTotalMyOffids=array();
			   $arrSumOfOffers=array_merge($redeemOfferIdsArray, $myOffersArray,$applyOfferIdsArray,$shareOfferIdsArray,$shareEmailOfferIdsArray,$shareFacebookOfferIdsArray,$shareTwitterOfferIdsArray,$financialOfferIdsArray,$scannedOfferIdsArray);
               $arrDataForGraph[$i]['offerids']=count(array_slice($arrSumOfOffers,0));
			   
			   

		    
			} 
			
		   //print_r($arrDataForGraph);
			$totalOffers = array_merge($arrDataTotalMyOffids,$arrDataTotalRedeemOfferids,$arrDataTotalApplyOfferids,$arrDataTotalShareOfferids,$arrDataTotalShareEmailOfferids,$arrDataTotalShareFacebookOfferids,$arrDataTotalShareTwitterOfferids,$arrDataTotalFinancialOfferids,$arrDataTotalScannedOfferids);
			$totalOffersWithSort = array_slice(array_filter(array_unique(array_merge($arrDataTotalMyOffids,$arrDataTotalRedeemOfferids,$arrDataTotalApplyOfferids,$arrDataTotalShareOfferids,$arrDataTotalShareEmailOfferids,$arrDataTotalShareFacebookOfferids,$arrDataTotalShareTwitterOfferids,$arrDataTotalFinancialOfferids,$arrDataTotalScannedOfferids))),0);

			for($i=0;$i<count($totalOffersWithSort);$i++)
	        {
	           $outArrGetOfferInfo = $this->objAnalyticsQuery->getOfferByOffID($totalOffersWithSort[$i]);
			   if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
				  {
					if(isset($outArrGetOfferInfo[0]['client_id']) && $outArrGetOfferInfo[0]['client_id']==$cid)
					{
					  $arrData[$i]['offer_id']=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
					  $arrData[$i]['client_id']=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] : '';
					  $arrData[$i]['offer_name']=isset($outArrGetOfferInfo[0]['offer_name']) ? $outArrGetOfferInfo[0]['offer_name'] : '';
					  $arrData[$i]['offer_image']=isset($outArrGetOfferInfo[0]['offer_image']) ? $outArrGetOfferInfo[0]['offer_image'] : '';
					  $arrData[$i]['offer_exp_date']=isset($outArrGetOfferInfo[0]['offer_valid_to']) ? $outArrGetOfferInfo[0]['offer_valid_to'] : '';
					  $arrData[$i]['offer_views']= count(array_keys($totalOffers, $totalOffersWithSort[$i]));
					  $arrData[$i]['myoffers']= count(array_keys($arrDataTotalMyOffids, $totalOffersWithSort[$i]));
			         }
			       }else
			       {
			       	if(isset($outArrGetOfferInfo[0]['client_id']) && $outArrGetOfferInfo[0]['client_id']==$clientId)
					{
					  $arrData[$i]['offer_id']=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
					  $arrData[$i]['client_id']=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] : '';
					  $arrData[$i]['offer_name']=isset($outArrGetOfferInfo[0]['offer_name']) ? $outArrGetOfferInfo[0]['offer_name'] : '';
					  $arrData[$i]['offer_image']=isset($outArrGetOfferInfo[0]['offer_image']) ? $outArrGetOfferInfo[0]['offer_image'] : '';
					  $arrData[$i]['offer_exp_date']=isset($outArrGetOfferInfo[0]['offer_valid_to']) ? $outArrGetOfferInfo[0]['offer_valid_to'] : '';
					  $arrData[$i]['offer_views']= count(array_keys($totalOffers, $totalOffersWithSort[$i]));
					  $arrData[$i]['myoffers']= count(array_keys($arrDataTotalMyOffids, $totalOffersWithSort[$i]));
					}
			       }	
			  
		    }


			
              //$arrData=array_slice($arrData,0);
              
              $arrData = array_values(array_map('unserialize', array_unique(array_map('serialize', $arrData))));
			//print_r($arrData);
		
		   include SRV_ROOT.'views/clients/user_tracking_data_by_offers.tpl.php';
		   */
			 include SRV_ROOT.'views/analytics/user_tracking_data_by_offers.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    public function modShowMobileUserTrackingDataByProdViews(){
		try{	
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/product_views';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['client_id']=$client_id;
			$params['searchByValOrPercent']=$searchByValOrPercent;
			

			
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

			/*$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
		    //print_r($outArrClientUsers);
			
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$outArrGetTrackingDataByDate = array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrTotalClosetPidsForViews=array();
			
			$arrDataTotalScannedPids=array();
			$arrDataTotalPIdsWishlists=array();
			$arrDataTotalSharedPids=array();
			$arrDataTotalSharedEmailPids=array();
			$arrDataTotalSharedFacebookPids=array();
			$arrDataTotalSharedTwitterPids=array();
			$arrDataTotalCartPids=array();
            
            $arrDataTotalScannedOfferids=array();
			$arrDataTotalFinancialPids=array();

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    $screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
						    $financialPId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialPids[]=$this->modGetProductIds($financialPId,$cid);
						}

					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
					{

					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='details')
					    {
					    	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='cart')
					        {
					        	$pId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalCartPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalPids[]=$this->modGetProductIds($pId,$cid);

					        }
						    
						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareproduct')
					    {
						    if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedEmailPids[]=$this->modGetProductIds($pId,$cid);
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedFacebookPids[]=$this->modGetProductIds($pId,$cid);

								
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
					        	$arrDataTotalSharedTwitterPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalSharedPids[]=$this->modGetProductIds($pId,$cid);
								
					        }


						    
						}
						else
						{
							$scannedPId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalScannedPids[]=$this->modGetProductIds($scannedPId,$cid);
							
						}	
						
					 }
					 
					 if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='mycloset')
					 {
					 	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='wishlists')
					    {
					    	//wishlists
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPIdsWishlists[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }
					    }
					    else
					    {
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPdsCloset[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }

					    }
					 
					    
					 }
					
					 
				 } 
		      
			  }
			  
			  


               $arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			   
			  
			   //products category code
			   $productDetailsIdsArray=array_slice($arrDataProducts,0);
			   //$arrDataProducts=array();
			   $productScannedIdsArray=array_slice($arrDataTotalScannedPids,0);
			   $pIdsClosetArray=array_slice($arrDataTotalPdsCloset,0);
			   $pIdsClosetWishlistArray=array_slice($arrDataTotalPIdsWishlists,0);
			   $pIdsSharedArray=array_slice($arrDataTotalSharedPids,0);
			   $pIdsSharedEmailArray=array_slice($arrDataTotalSharedEmailPids,0);
			   $pIdsSharedFacebookArray=array_slice($arrDataTotalSharedFacebookPids,0);
			   $pIdsSharedTwitterArray=array_slice($arrDataTotalSharedTwitterPids,0);
			   $pIdsCartArray=array_slice($arrDataTotalCartPids,0);
			   
			   //$arrDataPdsCloset=array();
			   $arrSumOfProducts=array_merge($productDetailsIdsArray, $pIdsClosetArray,$productScannedIdsArray,$pIdsClosetWishlistArray,$pIdsSharedArray,$pIdsSharedEmailArray,$pIdsSharedFacebookArray,$pIdsSharedTwitterArray,$pIdsCartArray);
			   $arrDataForGraph[$i]['product_views']=count(array_slice($arrSumOfProducts,0));
			  
	
			} 





			//print_r($arrDataForGraph);
			
			*/
			include SRV_ROOT.'views/analytics/user_tracking_by_products_views.tpl.php';
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowMobileUserTrackingDataByOfferViews(){
		try{	
			global $config;

			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/offer_views';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['client_id']=$client_id;
			

			
			 
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

			 
			// Close the cURL session
			curl_close($curl);




			/*$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
		    //print_r($outArrClientUsers);
			
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$arrDataForGraph = array();
			$outArrGetTrackingDataByDate = array();
			$arrOfferIds=array();
			$arrDataMyOffers=array();
			$arrTotalOfferIds=array();
			$arrrActiveUsers=array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrDataTotalOffids=array();
			$arrTotalClosetPidsForViews=array();
			$allMyOfferIds=array();
			
			$arrDataRedeemOffers=array();
            $arrDataTotalRedeemOfferids=array();
			$arrDataTotalApplyOfferids=array();
			$arrDataTotalShareOfferids=array();
			$arrDataTotalShareEmailOfferids=array();
			$arrDataTotalShareFacebookOfferids=array();
			$arrDataTotalShareTwitterOfferids=array();
			$arrDataTotalFinancialOfferids=array();
			$arrDataScannedOffers=array();
			$arrDataTotalMyOffids=array();
			$arrDataTotalScannedOfferids=array();
			
            

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    //$arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
				$screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='offers')
					{
						$scannedOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    $arrDataTotalScannedOfferids[]=$this->modGetOfferIds($scannedOfferId,$cid);
						
					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						//financial related
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='offers')
						{
							//offers
							$financialOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialOfferids[]=$this->modGetOfferIds($financialOfferId,$cid);

						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
							
						}


					}
					//my offers
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
					{
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='redeem')
					    {
					    	//redeem
					    	$redeemOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalRedeemOfferids[]=$this->modGetOfferIds($redeemOfferId,$cid);
						}
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='apply')
					    {
					    	//apply
					    	$applyOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    	$arrDataTotalApplyOfferids[]=$this->modGetOfferIds($applyOfferId,$cid);
                        }
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareoffer')
					    {
					    	if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$shareOfferEmailId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareEmailOfferids[]=$this->modGetOfferIds($shareOfferEmailId,$cid);


							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	$shareOfferFacebookId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareFacebookOfferids[]=$this->modGetOfferIds($shareOfferFacebookId,$cid);

							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	$shareOfferTwitterId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareTwitterOfferids[]=$this->modGetOfferIds($shareOfferTwitterId,$cid);

							}
					        else
					        {
					        	//shareoffers
					            $shareOfferId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					            $arrDataTotalShareOfferids[]=$this->modGetOfferIds($shareOfferId,$cid);

							}

					    }

					    if(!empty($arrScreenInfo[$k]['offer_ids']))
					    {
					       
							$arrtemp=explode('|',$arrScreenInfo[$k]['offer_ids']);
							//print_r($arrtemp);
						    for($l=0;$l<count($arrtemp);$l++)
							{
							 $arrDataTotalMyOffids[]=$this->modGetOfferIds($arrtemp[$l],$cid);
				 
							}
							
						}
					
					 }
					
					
					 
				   } 
		      
			  }
			  
			  
			 
			   $arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			   
			  
			   
			   //offers category code
			   $redeemOfferIdsArray=array_slice($arrDataTotalRedeemOfferids,0);
			   $applyOfferIdsArray=array_slice($arrDataTotalApplyOfferids,0);
			   $shareOfferIdsArray=array_slice($arrDataTotalShareOfferids,0);
			   $shareEmailOfferIdsArray=array_slice($arrDataTotalShareEmailOfferids,0);
			   $shareFacebookOfferIdsArray=array_slice($arrDataTotalShareFacebookOfferids,0);
			   $shareTwitterOfferIdsArray=array_slice($arrDataTotalShareTwitterOfferids,0);
			   $financialOfferIdsArray=array_slice($arrDataTotalFinancialOfferids,0);
			   $scannedOfferIdsArray=array_slice($arrDataTotalScannedOfferids,0);

			   
			   
			   //$arrDataTotalRedeemOfferids=array();
               $myOffersArray=array_slice($arrDataTotalMyOffids,0);
			   //$arrDataTotalMyOffids=array();
			   $arrSumOfOffers=array_merge($redeemOfferIdsArray, $myOffersArray,$applyOfferIdsArray,$shareOfferIdsArray,$shareEmailOfferIdsArray,$shareFacebookOfferIdsArray,$shareTwitterOfferIdsArray,$financialOfferIdsArray,$scannedOfferIdsArray);
               $arrDataForGraph[$i]['offer_views']=count(array_slice($arrSumOfOffers,0));
			   
			   

		    
			} 
			
			*/
			//print_r($arrDataForGraph);
			include SRV_ROOT.'views/analytics/user_tracking_by_offers_views.tpl.php';
			
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 public function modShowMobileUserProdViewsDateDet($client_id,$start_date){
		try{	
			global $config;


			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/product_views/bydate';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['client_id']=$client_id;
			
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

            
			/*//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
		    //print_r($outArrClientUsers);
			
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
		
			$outArrGetTrackingDataByDate = array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrTotalClosetPidsForViews=array();
			
			$arrDataTotalScannedPids=array();
			$arrDataTotalPIdsWishlists=array();
			$arrDataTotalSharedPids=array();
			$arrDataTotalSharedEmailPids=array();
			$arrDataTotalSharedFacebookPids=array();
			$arrDataTotalSharedTwitterPids=array();
			$arrDataTotalCartPids=array();
            
            $arrDataTotalScannedOfferids=array();
			$arrDataTotalFinancialPids=array();

			
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($start_date);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    $screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
						    $financialPId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialPids[]=$this->modGetProductIds($financialPId,$cid);
						}

					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
					{

					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='details')
					    {
					    	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='cart')
					        {
					        	$pId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalCartPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalPids[]=$this->modGetProductIds($pId,$cid);

					        }
						    
						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareproduct')
					    {
						    if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedEmailPids[]=$this->modGetProductIds($pId,$cid);
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								$arrDataTotalSharedFacebookPids[]=$this->modGetProductIds($pId,$cid);

								
					        }
					        if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//email
					        	$pId=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
					        	$arrDataTotalSharedTwitterPids[]=$this->modGetProductIds($pId,$cid);
								
					        }
					        else
					        {
					        	$pId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	$arrDataTotalSharedPids[]=$this->modGetProductIds($pId,$cid);
								
					        }


						    
						}
						else
						{
							$scannedPId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalScannedPids[]=$this->modGetProductIds($scannedPId,$cid);
							
						}	
						
					 }
					 
					 if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='mycloset')
					 {
					 	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='wishlists')
					    {
					    	//wishlists
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPIdsWishlists[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }
					    }
					    else
					    {
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								 $arrDataTotalPdsCloset[]=$this->modGetProductIds($arrtempPid[$l],$cid);
								}
								
						    }

					    }
					 
					    
					 }
					
					 
				 } 
		      
			  }
			  
			  

			   
			  
			   
	
            $totalProducts = array_unique(array_merge($arrDataTotalPids,$arrDataTotalPdsCloset,$arrDataTotalScannedPids,$arrDataTotalPIdsWishlists,$arrDataTotalSharedPids,$arrDataTotalSharedEmailPids,$arrDataTotalSharedFacebookPids,$arrDataTotalSharedTwitterPids,$arrDataTotalCartPids));
			$totalProductsWithSort = array_slice(array_filter(array_unique(array_merge($arrDataTotalPids,$arrDataTotalPdsCloset,$arrDataTotalScannedPids,$arrDataTotalPIdsWishlists,$arrDataTotalSharedPids,$arrDataTotalSharedEmailPids,$arrDataTotalSharedFacebookPids,$arrDataTotalSharedTwitterPids,$arrDataTotalCartPids))),0);
			
             for($l=0;$l<count($totalProductsWithSort);$l++)
			 {
			 	$outArrGetProdInfo = $this->objAnalyticsQuery->getProductByPID($totalProductsWithSort[$l]);
				if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
				{
				    if(isset($outArrGetProdInfo[0]['client_id']) && $outArrGetProdInfo[0]['client_id']==$cid)
					{
						
						$arrData[$l]['product_id']=isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';
						$arrData[$l]['client_id']=isset($outArrGetProdInfo[0]['client_id']) ? $outArrGetProdInfo[0]['client_id'] : '';
						$arrData[$l]['product_name']=isset($outArrGetProdInfo[0]['pd_name']) ? $outArrGetProdInfo[0]['pd_name'] : '';
						$arrData[$l]['product_image']=isset($outArrGetProdInfo[0]['pd_image']) ? $outArrGetProdInfo[0]['pd_image'] : '';
						$arrData[$l]['product_views']= count(array_keys($totalProducts, $totalProductsWithSort[$l]));	 
					}
				}
				else
				{
			        $arrData[$l]['product_id']=isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';
					$arrData[$l]['client_id']=isset($outArrGetProdInfo[0]['client_id']) ? $outArrGetProdInfo[0]['client_id'] : '';
					$arrData[$l]['product_name']=isset($outArrGetProdInfo[0]['pd_name']) ? $outArrGetProdInfo[0]['pd_name'] : '';
					$arrData[$l]['product_image']=isset($outArrGetProdInfo[0]['pd_image']) ? $outArrGetProdInfo[0]['pd_image'] : '';
					$arrData[$l]['product_views']= count(array_keys($totalProducts, $totalProductsWithSort[$l]));	
			        
				}	 

			 }	
             
		     //$arrData = array_values(array_map('unserialize', array_unique(array_map('serialize', $arrData))));
			 //print_r($arrData);

*/
			
			 include SRV_ROOT.'views/analytics/user_tracking_by_products_views_date.tpl.php';
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	public function modShowMobileUserOfferViewsDateDet($client_id,$start_date){
		try{	
			global $config;
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/offer_views/bydate';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['client_id']=$client_id;
			
			 
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

			 
			// Close the cURL session
			curl_close($curl);


            /*$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : '2014-03-03';
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :'2014-03-03';
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
		    //print_r($outArrClientUsers);
			
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$arrDataForGraph = array();
			$outArrGetTrackingDataByDate = array();
			$arrOfferIds=array();
			$arrDataMyOffers=array();
			$arrTotalOfferIds=array();
			$arrrActiveUsers=array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrDataTotalOffids=array();
			$arrTotalClosetPidsForViews=array();
			$allMyOfferIds=array();
			
			$arrDataRedeemOffers=array();
            $arrDataTotalRedeemOfferids=array();
			$arrDataTotalApplyOfferids=array();
			$arrDataTotalShareOfferids=array();
			$arrDataTotalShareEmailOfferids=array();
			$arrDataTotalShareFacebookOfferids=array();
			$arrDataTotalShareTwitterOfferids=array();
			$arrDataTotalFinancialOfferids=array();
			$arrDataScannedOffers=array();
			$arrDataTotalMyOffids=array();
			$arrDataTotalScannedOfferids=array();
			
            

			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($start_date);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    //$arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
				$screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='offers')
					{
						$scannedOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    $arrDataTotalScannedOfferids[]=$this->modGetOfferIds($scannedOfferId,$cid);
						
					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						//financial related
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='offers')
						{
							//offers
							$financialOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalFinancialOfferids[]=$this->modGetOfferIds($financialOfferId,$cid);

						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
							
						}


					}
					//my offers
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
					{
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='redeem')
					    {
					    	//redeem
					    	$redeemOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							$arrDataTotalRedeemOfferids[]=$this->modGetOfferIds($redeemOfferId,$cid);
						}
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='apply')
					    {
					    	//apply
					    	$applyOfferId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    	$arrDataTotalApplyOfferids[]=$this->modGetOfferIds($applyOfferId,$cid);
                        }
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareoffer')
					    {
					    	if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	$shareOfferEmailId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareEmailOfferids[]=$this->modGetOfferIds($shareOfferEmailId,$cid);


							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	$shareOfferFacebookId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareFacebookOfferids[]=$this->modGetOfferIds($shareOfferFacebookId,$cid);

							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	$shareOfferTwitterId=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	$arrDataTotalShareTwitterOfferids[]=$this->modGetOfferIds($shareOfferTwitterId,$cid);

							}
					        else
					        {
					        	//shareoffers
					            $shareOfferId=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					            $arrDataTotalShareOfferids[]=$this->modGetOfferIds($shareOfferId,$cid);

							}

					    }

					    if(!empty($arrScreenInfo[$k]['offer_ids']))
					    {
					       
							$arrtemp=explode('|',$arrScreenInfo[$k]['offer_ids']);
							//print_r($arrtemp);
						    for($l=0;$l<count($arrtemp);$l++)
							{
							 $arrDataTotalMyOffids[]=$this->modGetOfferIds($arrtemp[$l],$cid);
				 
							}
							
						}
					
					 }
					
					
					 
				   } 
		      
			  }
			  
			$totalOffers = array_merge($arrDataTotalMyOffids,$arrDataTotalRedeemOfferids,$arrDataTotalApplyOfferids,$arrDataTotalShareOfferids,$arrDataTotalShareEmailOfferids,$arrDataTotalShareFacebookOfferids,$arrDataTotalShareTwitterOfferids,$arrDataTotalFinancialOfferids,$arrDataTotalScannedOfferids);
			$totalOffersWithSort = array_slice(array_filter(array_unique(array_merge($arrDataTotalMyOffids,$arrDataTotalRedeemOfferids,$arrDataTotalApplyOfferids,$arrDataTotalShareOfferids,$arrDataTotalShareEmailOfferids,$arrDataTotalShareFacebookOfferids,$arrDataTotalShareTwitterOfferids,$arrDataTotalFinancialOfferids,$arrDataTotalScannedOfferids))),0);

			for($i=0;$i<count($totalOffersWithSort);$i++)
	        {
	           $outArrGetOfferInfo = $this->objAnalyticsQuery->getOfferByOffID($totalOffersWithSort[$i]);
			   if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
				  {
					if(isset($outArrGetOfferInfo[0]['client_id']) && $outArrGetOfferInfo[0]['client_id']==$cid)
					{
					  $arrData[$i]['offer_id']=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
					  $arrData[$i]['client_id']=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] : '';
					  $arrData[$i]['offer_name']=isset($outArrGetOfferInfo[0]['offer_name']) ? $outArrGetOfferInfo[0]['offer_name'] : '';
					  $arrData[$i]['offer_image']=isset($outArrGetOfferInfo[0]['offer_image']) ? $outArrGetOfferInfo[0]['offer_image'] : '';
					  $arrData[$i]['offer_exp_date']=isset($outArrGetOfferInfo[0]['offer_valid_to']) ? $outArrGetOfferInfo[0]['offer_valid_to'] : '';
					  $arrData[$i]['offer_views']= count(array_keys($totalOffers, $totalOffersWithSort[$i]));
					  
			         }
			       }else
			       {
					  $arrData[$i]['offer_id']=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
					  $arrData[$i]['client_id']=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] : '';
					  $arrData[$i]['offer_name']=isset($outArrGetOfferInfo[0]['offer_name']) ? $outArrGetOfferInfo[0]['offer_name'] : '';
					  $arrData[$i]['offer_image']=isset($outArrGetOfferInfo[0]['offer_image']) ? $outArrGetOfferInfo[0]['offer_image'] : '';
					  $arrData[$i]['offer_exp_date']=isset($outArrGetOfferInfo[0]['offer_valid_to']) ? $outArrGetOfferInfo[0]['offer_valid_to'] : '';
					  $arrData[$i]['offer_views']= count(array_keys($totalOffers, $totalOffersWithSort[$i]));
					  
			       }	
			  
		    }

			//$arrData = array_values(array_map('unserialize', array_unique(array_map('serialize', $arrData))));
			 //print_r($arrData);

			 */
			 include SRV_ROOT.'views/analytics/user_tracking_by_offer_views_date.tpl.php';
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	
	function modShowMobileUserProductsFlow($prdId,$start_date,$end_date)
	{
		try
		{
			global $config;
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;


			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['product_id']=$prdId;
			$params['group_id']=$groupId;
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);


			/*
			$outArrGetProdInfo=array();
			$outArrGetProdInfo = $this->objAnalyticsQuery->getProductByPID($prdId);
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$outArrGetTrackingDataByDate = array();
			$arrDataProducts=array();
			$arrDataPdsCloset=array();
			$arrDataTotalPdsCloset=array();
			$arrDataTotalPids=array();
			$arrTotalClosetPidsForViews=array();
			
			$arrDataTotalScannedPids=array();
			$arrDataTotalPIdsWishlists=array();
			$arrDataTotalSharedPids=array();
			$arrDataTotalSharedEmailPids=array();
			$arrDataTotalSharedFacebookPids=array();
			$arrDataTotalSharedTwitterPids=array();
			$arrDataTotalCartPids=array();
            
            $arrDataTotalScannedOfferids=array();
			$arrDataTotalFinancialPids=array();

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    $screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='product')
						{
							//products
							if($prdId==$expArrScrNames[3])
						    {
						    	$arrDataTotalFinancialPids[]=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
						    }
							
						}

					}
					
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
					 {

					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='details')
					    {
					    	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='cart')
					        {
					        	if($prdId==$expArrScrNames[4])
						        {
						        	$arrDataTotalCartPids[]=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	}
					        	
								
					        }
					        else
					        {
					        	if($prdId==$expArrScrNames[5])
						        {
						        	$arrDataTotalPids[]=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
						        }

					        	
					        	
					        }
						    
						}
						else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareproduct')
					    {
						    if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	if($prdId==$expArrScrNames[6])
						        {
					        	    $arrDataTotalSharedEmailPids[]=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
								}
					        }
					        elseif(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	if($prdId==$expArrScrNames[6])
						        {
						        	$arrDataTotalSharedFacebookPids[]=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
									
							    }

								
					        }
					        elseif(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	if($prdId==$expArrScrNames[6])
						        {
						        	$arrDataTotalSharedTwitterPids[]=isset($expArrScrNames[6]) ? $expArrScrNames[6] :'';
						        	
					            } 
								
					        }
					        else
					        {
								if($prdId==$expArrScrNames[5])
						        {
						        	$arrDataTotalSharedPids[]=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					        	    
						        }
					        	
					        }


						    
						}
						else
						{
							if($prdId==$expArrScrNames[3])
						    {
						    	$arrDataTotalScannedPids[]=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							}
						}	
						
					 }
					 
					 if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='mycloset')
					 {
					 	if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='wishlists')
					    {
					    	//wishlists
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								  if($prdId==$arrtempPid[$l])
					              {
					              	$arrDataTotalPIdsWishlists[]=$this->modGetProductIds($arrtempPid[$l],$cid);
					              }
								   
								}
								
						    }
					    }
					    else
					    {
					    	if(!empty($arrScreenInfo[$k]['product_ids']))
						    {
						    
						        $arrtempPid=explode('|',$arrScreenInfo[$k]['product_ids']);
								
								for($l=0;$l<count($arrtempPid);$l++)
								{
								  if($prdId==$arrtempPid[$l])
					              {
					              	$arrDataTotalPdsCloset[]=$this->modGetProductIds($arrtempPid[$l],$cid);
					              }
								 
								}
								
						    }

					    }
					 
					    
					 }
					 
					 
					 
				   } 
		      
			  }
		 
			} 


			$totalShared = array_merge($arrDataTotalSharedPids,$arrDataTotalSharedEmailPids,$arrDataTotalSharedTwitterPids,$arrDataTotalSharedFacebookPids);
			*/
			//print_r($arrDataTotalPids);
			//print_r($arrData);

        include SRV_ROOT.'views/analytics/user_tracking_by_products_flow.tpl.php';


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowMobileUserOffersFlow($offerId,$start_date,$end_date)
	{
		try
		{
			global $config;
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['offer_id']=$offerId;
			$params['group_id']=$groupId;
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

			/*$outArrGetOfferInfo=array();
			$outArrGetOfferInfo = $this->objAnalyticsQuery->getOfferByOffID($offerId);


			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : '2014-03-03';
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :'2014-03-03';
			
			
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
		
			$outArrGetTrackingDataByDate = array();
			$arrDataTotalOffids=array();
			$allMyOfferIds=array();
			$arrDataRedeemOffers=array();
            $arrDataTotalRedeemOfferids=array();
			$arrDataTotalApplyOfferids=array();
			$arrDataTotalShareOfferids=array();
			$arrDataTotalShareEmailOfferids=array();
			$arrDataTotalShareFacebookOfferids=array();
			$arrDataTotalShareTwitterOfferids=array();
			$arrDataTotalFinancialOfferids=array();
			$arrDataScannedOffers=array();
            $arrDataTotalScannedOfferids=array();
			

			for($i=0;$i<count($arrBetweenDates);$i++)
			{
		 
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  //print_r($outArrGetTrackingDataByDate);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			    //$arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
				$screenPathJson=$outArrGetTrackingDataByDate[$j]['screen_path'];
				$arrScreenInfo=json_decode($screenPathJson,true);
				//print_r($arrScreenInfo);
				for($k=0;$k<count($arrScreenInfo);$k++)
				{
				    $screenName=isset($arrScreenInfo[$k]['screen_name']) ? $arrScreenInfo[$k]['screen_name'] : '';
				    $arrscreenNames[]=$arrScreenInfo[$k]['screen_name'];
				    $expArrScrNames=explode('/',$screenName);
					//print_r( $expArrScrNames);
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='offers')
					{
						if($offerId==$expArrScrNames[3])
					    {
					    	$arrDataTotalScannedOfferids[]=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    }
						
						
					}
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='video')
					{
						//financial related
						if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='offers')
						{
							//offers
							if($offerId==$expArrScrNames[3])
							{
								$arrDataTotalFinancialOfferids[]=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							}
					    

						}


					}
					//my offers
					if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
					{
					    if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='redeem')
					    {
					    	//redeem
					    	if($offerId==$expArrScrNames[3])
							{
								$arrDataTotalRedeemOfferids[]=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
							    
							}
					    	
						}
					    else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='apply')
					    {
					    	//apply
					    	if($offerId==$expArrScrNames[3])
							{
								$arrDataTotalApplyOfferids[]=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
					    	
							}

					    	
                        }
					    else if(isset($expArrScrNames[2]) && $expArrScrNames[2]=='shareoffer')
					    {
					    	if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='email')
					        {
					        	//email
					        	if($offerId==$expArrScrNames[4])
							    {
							    	$arrDataTotalShareEmailOfferids[]=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	
							    }
					        	


							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='facebook')
					        {
					        	//facebook
					        	if($offerId==$expArrScrNames[4])
							    {
							    	$arrDataTotalShareFacebookOfferids[]=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	}

							}
							else if(isset($expArrScrNames[3]) && $expArrScrNames[3]=='twitter')
					        {
					        	//twitter
					        	if($offerId==$expArrScrNames[4])
							    {
							    	$arrDataTotalShareTwitterOfferids[]=isset($expArrScrNames[4]) ? $expArrScrNames[4] :'';
					        	
					        	}

							}
					        else
					        {
					        	//shareoffers
					        	if($offerId==$expArrScrNames[5])
							    {
							    	$arrDataTotalShareOfferids=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					            
					            }

							}

					    }
					    else 
					    {
					       if(!empty($arrScreenInfo[$k]['offer_ids']))
					       {
						       	$arrtemp=explode('|',$arrScreenInfo[$k]['offer_ids']);
								//print_r($arrtemp);
							    for($l=0;$l<count($arrtemp);$l++)
								{
									if($offerId==$arrtemp[$l])
							        {
							        	$arrDataTotalMyOffids[]=$this->modGetOfferIds($arrtemp[$l],$cid);
							        }
								 
					 
								}
					       }
						}
					
					 }
					
				   } 
		      
			  }
			  
			  
			 
			  

		    
			} 
			
		   //print_r($arrDataForGraph);
			$totalOffers = array_merge($arrDataTotalMyOffids,$arrDataTotalRedeemOfferids,$arrDataTotalApplyOfferids,$arrDataTotalShareOfferids,$arrDataTotalShareEmailOfferids,$arrDataTotalShareFacebookOfferids,$arrDataTotalShareTwitterOfferids,$arrDataTotalFinancialOfferids,$arrDataTotalScannedOfferids);
			$totalOffersWithSort = array_slice(array_filter(array_unique(array_merge($arrDataTotalMyOffids,$arrDataTotalRedeemOfferids,$arrDataTotalApplyOfferids,$arrDataTotalShareOfferids,$arrDataTotalShareEmailOfferids,$arrDataTotalShareFacebookOfferids,$arrDataTotalShareTwitterOfferids,$arrDataTotalFinancialOfferids,$arrDataTotalScannedOfferids))),0);

			



        include SRV_ROOT.'views/clients/user_tracking_by_offers_flow.tpl.php';
        */
         include SRV_ROOT.'views/analytics/user_tracking_by_offers_flow.tpl.php';


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowClientProducts()
	{
		try
		{
			global $config;
			
			$cid = isset($_SESSION['clientIds']) ? $_SESSION['clientIds'] : 0;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;
	
$outArrUrlVals='';
$outArrUrlVals=explode('?', $_SERVER["REQUEST_URI"]);
$outCidVals=isset($outArrUrlVals[1]) ? $outArrUrlVals[1] :'';
$outArrCidValue=explode("=", $outCidVals);
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/clientproducts';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['login_client_id']=$cid;
			$params['group_id']=$groupId;
			$params['search_client_id']=isset($outArrCidValue[1]) ? $outArrCidValue[1] : 0;
			 
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
            //print_r($arrData);

			include SRV_ROOT.'views/analytics/user_tracking_data_by_clients_products.tpl.php';

			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowClinetOffers()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			$cid = isset($_SESSION['clientIds']) ? $_SESSION['clientIds'] : 0;
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;


$outArrUrlVals='';
$outArrUrlVals=explode('?', $_SERVER["REQUEST_URI"]);
$outCidVals=isset($outArrUrlVals[1]) ? $outArrUrlVals[1] :'';
$outArrCidValue=explode("=", $outCidVals);



			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/clientoffers';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['login_client_id']=$cid;
			$params['group_id']=$groupId;
			$params['search_client_id']=isset($outArrCidValue[1]) ? $outArrCidValue[1] : 0;
			 
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
            //print_r($arrData);


			include SRV_ROOT.'views/analytics/user_tracking_data_by_clients_offers.tpl.php';

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowMobileOfferUsers($offerId,$start_date,$end_date)
	{
		try
		{
			global $config;
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/users';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			$params['offer_id']=$offerId;
			 
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

			 
			// Close the cURL session
			curl_close($curl);

			//print_r($arrData);

			
         //include SRV_ROOT.'views/analytics/user_tracking_by_offers_users.tpl.php';


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowMobileUserClientProdViews($start_date)
	{
		try
		{
			global $config;
			

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/product_views/clients';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			 
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
            //print_r($arrData);

			include SRV_ROOT.'views/analytics/user_tracking_data_by_clients_products_views.tpl.php';

			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowMobileUserClientOfferViews($start_date)
	{
		try
		{
			global $config;
			

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/offer_views/clients';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['start_date']=$start_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_clients_offers_views.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowMobileUserProductsFlowUsers($product_id,$start_date,$end_date)
	{
		try
		{
			global $config;
			

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/users';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$start_date;
			 
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
            //print_r($arrData);
            //include SRV_ROOT.'views/analytics/user_tracking_data_by_clients_offers_views.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowUserLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/users';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_users.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowScannedLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/scanned';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_scanned.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowClosetLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/closet';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_closet.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowWishlistLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/wishlist';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_wishlist.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowShareLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/share';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_share.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}		
	function modShowProductFlowShareEmailLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/share/email';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_share_email.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}	
	function modShowProductFlowShareFBLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/share/facebook';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_share_fb.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}	
	function modShowProductFlowShareTwitterLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/share/twitter';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_share_twitter.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowShareSMSLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/share/SMS';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_share_sms.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}		
	function modShowProductFlowCartLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/cart';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_cart.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowProductFlowDetailsLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$product_id=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/products/details';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['product_id']=$product_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_product_flow_details.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}



	function modShowOfferFlowUserLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/users';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_users.tpl.php';


			// Close the cURL session
			curl_close($curl);


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}	
	function modShowOfferFlowMyOffersLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/myoffers';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_myoffers.tpl.php';


			// Close the cURL session
			curl_close($curl);


		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}	
	function modShowOfferFlowAddLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/add';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_add.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowRemoveLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/remove';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_remove.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowRedeemLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/redeem';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_redeem.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowShareLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/share';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_share.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowShareEmailLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/share/email';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_share_email.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowShareFBLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/share/facebook';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_share_fb.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowShareTwitterLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/share/twitter';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_share_twitter.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}	
	function modShowOfferFlowShareSMSLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/share/sms';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_share_sms.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowOfferFlowScannedLevel()
	{
		try
		{
			global $config;
			$start_date=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
			$end_date=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
			$offer_id=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] : '';

			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/flow/offers/scanned';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['offer_id']=$offer_id;
			$params['start_date']=$start_date;
			$params['end_date']=$end_date;
			 
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
            //print_r($arrData);
            include SRV_ROOT.'views/analytics/user_tracking_data_by_offers_flow_scanned.tpl.php';


			// Close the cURL session
			curl_close($curl);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	function modShowGetClientCampaignDates()
	{
		try
		{
			global $config;
			$campaign_id=isset($_REQUEST['campaignId']) ? $_REQUEST['campaignId'] : '';
			$groupId = isset($_SESSION['user_group']) ? $_SESSION['user_group'] : 0;
			
			// Base URL for the service
			$baseUrl = $config['LIVE_URL'].'data_analytics/webservices/campaigns/dates';
			// Tool name
			//$toolName = 'iprscan';
			 
			// Parameters for call
			$params = array();
			$params['campaign_id']=$campaign_id;
			$params['group_id']=$groupId;
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
			
			$arr=array();
			$arr['campaign_id']=isset($arrData[0]['campaign_id']) ? $arrData[0]['campaign_id'] : 0;
			$arr['campaign_start_date']=isset($arrData[0]['campaign_start_date']) ? $arrData[0]['campaign_start_date'] : 0;
			$arr['campaign_end_date']=isset($arrData[0]['campaign_end_date']) ? $arrData[0]['campaign_end_date'] : 0;
			echo json_encode($arr);
			//print_r($arrData);
            
            //print_r($arrData);
            // Close the cURL session
			curl_close($curl);

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