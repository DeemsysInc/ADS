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
    public function modShowMobileUserTrackingData(){
		try{	
			global $config;
			
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : '2014-01-14 ';
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :'2014-01-15 ';
			
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
		    //print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			//print_r($outArrGetTrackingDataByDates);
			
			$arrOfferIdsTot=array();
			$arrTotalUsers=array();
			$arrOfferIds=array();
			$arrpId=array();
			$val=array();
			
			for($i=0;$i<count($outArrGetTrackingDataByDates);$i++)
			{
			   $val[$i]['data_analytics_id'] =$outArrGetTrackingDataByDates[$i]['data_analytics_id'];
			   
			   $val[$i]['session_id'] =$outArrGetTrackingDataByDates[$i]['session_id'];
			   $val[$i]['userid'] =$outArrGetTrackingDataByDates[$i]['user_id'];
			   $val[$i]['screenpath']=json_decode($outArrGetTrackingDataByDates[$i]['screen_path'],true);
			   
			   $screenPathJson=$outArrGetTrackingDataByDates[$i]['screen_path'];
			   $arrScreenInfo=json_decode($screenPathJson,true);
			   //print_r($arrScreenInfo);
			
			
			   
			   for($j=0;$j<count($arrScreenInfo);$j++)
			   {
			     $screenName=$arrScreenInfo[$j]['screen_name'];
			     
			     $arrscreenNames[]=$arrScreenInfo[$j]['screen_name'];
			     $expArrScrNames=explode('/',$screenName);
			     // print_r( $expArrScrNames);
			     $clientId=isset($expArrScrNames[3]) ? $expArrScrNames[3] :'';
			     if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
				 {
				     $arrOfferIds[]=$arrScreenInfo[$j]['offer_ids'];
				 }
			     if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
				 {
		  
					  if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			          {
			            if($clientId==$cid)
						  {
							  $arrpId[]=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
							  $arrScrNames[]= $arrscreenNames[$i];
						
						  }
						  
			          }
			          else
					  {
					      $arrpId[]=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
						  $arrScrNames[]= $arrscreenNames[$i];
					  }
				 } 
				     
			   }
			   if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			   {
				if($clientId==$cid)
				{
				   //echo "true";
				   $arrTotalUsers[]=$outArrGetTrackingDataByDates[$i]['user_id'];
				}
			   }
			   else
			   {
				   //echo "false";
				   $arrTotalUsers[]=$outArrGetTrackingDataByDates[$i]['user_id'];
			   } 
			  
			}
		    //print_r($val);
		    
		    
		    //chart for active users
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($start_date,$end_date);
			//print_r($arrBetweenDates);
			
			$arrDataForGraph = array();
			$outArrGetTrackingDataByDate = array();
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
			  
			  $outArrGetTrackingDataByDate = $this->objAnalyticsQuery->getUserTrackigDataByDate($arrBetweenDates[$i]);
			  for($j=0;$j<count($outArrGetTrackingDataByDate);$j++)
			  {
			   $arrrActiveUsers[]= $outArrGetTrackingDataByDate[$j]['user_id'];
			  }
			  
			  $arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			  $arrDataForGraph[$i]['active_users']=count(array_values(array_unique($arrrActiveUsers)));
			  
			}
			
		
	   
		    $arrpIdSort=array_values(array_unique($arrpId));
		   
		    //print_r($arrOfferIds);
		    $arrOfferIdsSort= array_slice(array_filter(array_values(array_unique($arrOfferIds))),0);
			
			//print_r($arrOfferIdsSort);
			for($k=0;$k<count($arrOfferIdsSort);$k++)
			{
			 
			    $arrExplOfferIds=explode('|',$arrOfferIdsSort[$k]); 
			    for($i=0;$i<count($arrExplOfferIds);$i++)
			    {
			      $arrOfferIdsTot[]=$arrExplOfferIds[$i];
			    }
			    
			}
			//print_r($arrOfferIdsTot);
			$arrOfferIdsfinalResult=array_values(array_unique($arrOfferIdsTot));
			
			
			$arrscreenNamesSort=array_values(array_unique($arrscreenNames));
			//print_r($arrscreenNamesSort);
			
			$arrData=array();
			for($i=0;$i<count($arrscreenNamesSort);$i++)
			{
			   $arrData[$i]['screen_name']= $arrscreenNamesSort[$i];
			   $arrData[$i]['screen_count']= count(array_keys($arrscreenNames, $arrscreenNamesSort[$i]));
			   
			}
			
		    $totalVisitors= count($arrTotalUsers);
            $totalProducts= count($arrpIdSort);
            if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
			{
             $totalOffers= 3;
            }
            else
            {
              $totalOffers= count($arrOfferIdsfinalResult);
             
            }
			//print_r($arrData);
			
		
		
			
			include SRV_ROOT.'views/clients/user_tracking_data.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    public function modShowMobileUseTrackDataByProducts(){
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
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			
			//print_r($outArrGetTrackingDataByDates);
			$arrscreenNames=array();
			$arrData=array();
			if(count($outArrGetTrackingDataByDates)>0)
			{
				for($i=0;$i<count($outArrGetTrackingDataByDates);$i++)
				{
				   $val[$i]['data_analytics_id'] =$outArrGetTrackingDataByDates[$i]['data_analytics_id'];
			   
				   $val[$i]['session_id'] =$outArrGetTrackingDataByDates[$i]['session_id'];
				   $val[$i]['userid'] =$outArrGetTrackingDataByDates[$i]['user_id'];
				   $val[$i]['screenpath']=json_decode($outArrGetTrackingDataByDates[$i]['screen_path'],true);
			   
				   $screenPathJson=$outArrGetTrackingDataByDates[$i]['screen_path'];
				   $arrScreenInfo=json_decode($screenPathJson,true);
				  // print_r($arrScreenInfo);
				   for($j=0;$j<count($arrScreenInfo);$j++)
				   {
					 $arrscreenNames[]=$arrScreenInfo[$j]['screen_name'];
					// $arrscreenUserIds[]=$outArrGetTrackingDataByDates[$i]['user_id'];//temp
					// $val[$i]['useridaaaa'][$j]=$outArrGetTrackingDataByDates[$i]['user_id'];//temp
				 
				   
				 
				   }
			   
				   
				}

				//print_r($val);
				//print_r($arrscreenUserIds);
				//$arrscreenNamesSort=array_values(array_unique($arrscreenNames));
				$arrscreenNamesSort=$arrscreenNames;
				
				for($i=0;$i<count($arrscreenNamesSort);$i++)
				{
		   
				   $expArrScrNames=explode('/',$arrscreenNamesSort[$i]);
		   
				   if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='product')
				   {
		  
					  $arrpId[]=isset($expArrScrNames[5]) ? $expArrScrNames[5] :'';
					  $arrScrNames[]= $arrscreenNamesSort[$i];
			  
				   } 
				      
		  
				  // $arrData[$i]['screen_name']=$prodName; 
				}
				
				$arrpIdSort=array_values(array_unique($arrpId));
				//print_r($arrpId);
				
				for($i=0;$i<count($arrpIdSort);$i++)
				{
				  $outArrGetProdInfo = $this->objAnalyticsQuery->getProductByPID($arrpIdSort[$i]);
				  
				     if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
					  {
						if(isset($outArrGetProdInfo[0]['client_id']) && $outArrGetProdInfo[0]['client_id']==$cid)
						{
						  $arrData[$i]['product_id']=isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';
						  $arrData[$i]['client_id']=isset($outArrGetProdInfo[0]['client_id']) ? $outArrGetProdInfo[0]['client_id'] : '';
						  $arrData[$i]['product_name']=isset($outArrGetProdInfo[0]['pd_name']) ? $outArrGetProdInfo[0]['pd_name'] : '';
						  $arrData[$i]['product_image']=isset($outArrGetProdInfo[0]['pd_image']) ? $outArrGetProdInfo[0]['pd_image'] : '';
						  $arrData[$i]['product_views']= count(array_keys($arrpId, $arrpIdSort[$i]));
				         }
		             }
		             else
		             {
		                  $arrData[$i]['product_id']=isset($outArrGetProdInfo[0]['pd_id']) ? $outArrGetProdInfo[0]['pd_id'] : '';
						  $arrData[$i]['client_id']=isset($outArrGetProdInfo[0]['client_id']) ? $outArrGetProdInfo[0]['client_id'] : '';
						  $arrData[$i]['product_name']=isset($outArrGetProdInfo[0]['pd_name']) ? $outArrGetProdInfo[0]['pd_name'] : '';
						  $arrData[$i]['product_image']=isset($outArrGetProdInfo[0]['pd_image']) ? $outArrGetProdInfo[0]['pd_image'] : '';
						  $arrData[$i]['product_views']= count(array_keys($arrpId, $arrpIdSort[$i]));
		             }
				}
		 }
			
			$arrData=array_slice($arrData,0);
			
			include SRV_ROOT.'views/clients/user_tracking_data_by_products.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    
    public function modShowMobileUseTrackDataByOffers(){
		try{	
			global $config;
			$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : '2014-01-05';
			$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :'2014-01-05';
			
			//clinet user info by userid
			$outArrClientUsers = array();
			$outArrClientUsers = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			//print_r($outArrClientUsers);
			//get client ids belongs to client user
			$cid=isset($outArrClientUsers[0]['client_ids']) ? $outArrClientUsers[0]['client_ids'] : '';
			
			$outArrGetTrackingDataByDates = array();
			$outArrGetTrackingDataByDates = $this->objAnalyticsQuery->getUserTrackigDataByDates($start_date,$end_date);
			
			//print_r($outArrGetTrackingDataByDates);
			
			for($i=0;$i<count($outArrGetTrackingDataByDates);$i++)
			{
			   $val[$i]['data_analytics_id'] =$outArrGetTrackingDataByDates[$i]['data_analytics_id'];
			   
			   $val[$i]['session_id'] =$outArrGetTrackingDataByDates[$i]['session_id'];
			   $val[$i]['userid'] =$outArrGetTrackingDataByDates[$i]['user_id'];
			   $val[$i]['screenpath']=json_decode($outArrGetTrackingDataByDates[$i]['screen_path'],true);
			   
			   $screenPathJson=$outArrGetTrackingDataByDates[$i]['screen_path'];
			   $arrScreenInfo=json_decode($screenPathJson,true);
			  // print_r($arrScreenInfo);
			 
			   for($j=0;$j<count($arrScreenInfo);$j++)
			   {
			     
			     $screenName=$arrScreenInfo[$j]['screen_name'];
			     $arrscreenNames[]=$arrScreenInfo[$j]['screen_name'];
			     //echo "<br>";
			     $expArrScrNames=explode('/',$screenName);
			    // print_r( $expArrScrNames);
			     if(isset($expArrScrNames[1]) && $expArrScrNames[1]=='myoffers')
				 {
			        // echo $arrScreenInfo[$j]['screen_name'].'======'.$arrScreenInfo[$j]['offer_ids'];
			         //echo "<br>";
			         $arrOfferIds[]=$arrScreenInfo[$j]['offer_ids'];
			         $arrUserIds[]=$val[$i]['userid'];
			        
					
			      }    
			    
			   }
			   
			 
			
			}
			//print_r($val);
			for($i=0;$i<count($arrOfferIds);$i++)
			{
			  $arr=explode('|',$arrOfferIds[$i]);
			  for($j=0;$j<count($arr);$j++)
			  {
			    $arrTotalOfferIds[]=$arr[$j];
			  }
			}
			//print_r($arrTotalOfferIds);
			
			 $arrOfferIdsSort=array_filter(array_values(array_unique($arrOfferIds)));
			 for($k=0;$k<count($arrOfferIdsSort);$k++)
			 {
			 
			    $arrExplOfferIds=explode('|',$arrOfferIdsSort[$k]);
			    for($i=0;$i<count($arrExplOfferIds);$i++)
		        {
		        	   
				  $outArrGetOfferInfo = $this->objAnalyticsQuery->getOfferByOffID($arrExplOfferIds[$i]);
				   if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='2')
					  {
						if(isset($outArrGetOfferInfo[0]['client_id']) && $outArrGetOfferInfo[0]['client_id']==$cid)
						{
						  $arrData[$i]['offer_id']=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
						  $arrData[$i]['client_id']=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] : '';
						  $arrData[$i]['offer_name']=isset($outArrGetOfferInfo[0]['offer_name']) ? $outArrGetOfferInfo[0]['offer_name'] : '';
						  $arrData[$i]['offer_image']=isset($outArrGetOfferInfo[0]['offer_image']) ? $outArrGetOfferInfo[0]['offer_image'] : '';
						  $arrData[$i]['offer_exp_date']=isset($outArrGetOfferInfo[0]['offer_valid_to']) ? $outArrGetOfferInfo[0]['offer_valid_to'] : '';
						  $arrData[$i]['offer_views']= count(array_keys($arrTotalOfferIds, $arrExplOfferIds[$i]));
				         }
				       }else
				       {
						  $arrData[$i]['offer_id']=isset($outArrGetOfferInfo[0]['offer_id']) ? $outArrGetOfferInfo[0]['offer_id'] : '';
						  $arrData[$i]['client_id']=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] : '';
						  $arrData[$i]['offer_name']=isset($outArrGetOfferInfo[0]['offer_name']) ? $outArrGetOfferInfo[0]['offer_name'] : '';
						  $arrData[$i]['offer_image']=isset($outArrGetOfferInfo[0]['offer_image']) ? $outArrGetOfferInfo[0]['offer_image'] : '';
						  $arrData[$i]['offer_exp_date']=isset($outArrGetOfferInfo[0]['offer_valid_to']) ? $outArrGetOfferInfo[0]['offer_valid_to'] : '';
						  $arrData[$i]['offer_views']= count(array_keys($arrTotalOfferIds, $arrExplOfferIds[$i]));
				       }	
				  
			    }
			 }
			
              $arrData=array_slice($arrData,0);
			//print_r($arrData);
			//print_r($arrOfferIds);
			
		include SRV_ROOT.'views/clients/user_tracking_data_by_offers.tpl.php';
			
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