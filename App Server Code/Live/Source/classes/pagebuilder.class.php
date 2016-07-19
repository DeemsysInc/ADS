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
		
		require_once SRV_ROOT.'classes/login.class.php';
		$this->objLogin = new cLogin();
		
		require_once SRV_ROOT.'classes/users.class.php';
		$this->objUsers = new cUsers();
		
		require_once SRV_ROOT.'classes/dashboard.class.php';
		$this->objDashboard = new cDashboard();
		
		require_once SRV_ROOT.'classes/clients.class.php';
		$this->objClients = new cClients();
		
		require_once SRV_ROOT.'classes/creator.class.php';
		$this->objCreator = new cCreator();
		
		require_once SRV_ROOT.'classes/public.class.php';
		$this->objPublic = new cPublic();
		
		require_once SRV_ROOT.'classes/config.class.php';
		$this->objConfig = new cConfig();
		$this->getConfig = $this->objConfig->config();
		
		require_once SRV_ROOT.'classes/app.login.class.php';
		$this->objAppLogin = new cAppLogin();
		
		require_once SRV_ROOT.'classes/analytics.class.php';
		$this->objAnalytics = new cAnalytics();
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
				//session settting for start date and end date
					if(empty($_SESSION['start_date']) )
					{
						
						 $from =  isset($_POST['from']) ? $_POST['from'] : date('Y-m-d',strtotime('1 month ago'));
				         $to =  isset($_POST['to']) ? $_POST['to'] : date('Y-m-d');
				         $_SESSION['start_date']=$from;
					     $_SESSION['end_date']=$to;

					}
					else{
						$from =  isset($_POST['from']) ? $_POST['from'] : $_SESSION['start_date'];
				        $to =  isset($_POST['to']) ? $_POST['to'] : $_SESSION['end_date'];
				        $_SESSION['start_date']=$from;
					    $_SESSION['end_date']=$to;
					}
				if (($outUserInfo['user_group'] ==1) || ($outUserInfo['user_group'] ==2) || ($outUserInfo['user_group'] ==6)) {//super admin and admin and sales manager
					



					//session setting for client id
					if(empty($_SESSION['search_client_id']))
					{

						$byClient =  isset($_POST['byClient']) ? $_POST['byClient'] : '814';

					}
					else
					{
						$byClient =  isset($_POST['byClient']) ? $_POST['byClient'] : 0;
					}
					if ($byClient !=0) {
						$outUserInfo['user_clients'] = $byClient;
						$_SESSION['search_client_id'] = $outUserInfo['user_clients'];
						
					}
					else
					{
						//$outUserInfo['user_clients'] =  isset($_SESSION['default_clientid']) ? $_SESSION['default_clientid'] : 0;
						$outUserInfo['user_clients'] =  isset($_SESSION['search_client_id']) ? $_SESSION['search_client_id'] : 0;
					}

					
					
					

					
				}else{
					$outUserInfo['user_clients'] =  isset($_SESSION['search_client_id']) ? $_SESSION['search_client_id'] : "";
				}
				//print_r($_SESSION);
				/**** assign variables/array to header.tpl.php ****/
				include SRV_ROOT.'views/home/header.tpl.php';
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
			$pAction = isset($pUrl[0]) ? $pUrl[0] : '';
			//print_r($pUrl);
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname']))
			{
			 
				if ($pAction==''){
					// if(isset($_SESSION['user_group']) && $_SESSION['user_group']=='3')
					// {
					//      // $this->objClients->modShowClientDashboard();
					//      $this->objAnalytics->modShowMobileUserTrackingData();
					//      //$this->objAnalytics->modShowMobileUserTrackingData();
					// }
					// else
					// {
	    //                  //$this->objDashboard->modDashboard();
	    //                   $this->objAnalytics->modShowMobileUserTrackingData();
					// }
					$this->objDashboard->modDashboard();
				}else if ($pAction=='analytics'){
				
				    if (isset($pUrl[1]) && ($pUrl[1]=='products')){
					  $this->objAnalytics->modShowClientAnalyticsByProducts();
					} 
					elseif (isset($pUrl[1]) && ($pUrl[1]=='dashboard')){
					  $this->objAnalytics->modShowAnalyticDashboard();
					}
					elseif (isset($pUrl[1]) && ($pUrl[1]=='activeusers')){
					  $this->objAnalytics->modShowActiveUsersOverView();
					}
					elseif (isset($pUrl[1]) && ($pUrl[1]=='visitors_geo')){
					  $this->objAnalytics->modShowVisitorsGeoOverView();
					}
					elseif (isset($pUrl[1]) && ($pUrl[1]=='visitors_device_overview')){
					  $this->objAnalytics->modShowVisitorsDeviceOverView();
					}
					///mobile ios and android user tracking data
					elseif (isset($pUrl[1]) && ($pUrl[1]=='mobile_users'))
					{
					  
					  if(isset($pUrl[2]) && ($pUrl[2]=='client_products'))
					  {
					  	//$qString=isset($pUrl[3]) ?$pUrl[3] : '';
					  	//$arrExpVals=explode('?', $qString);
					  	//echo "clientid".$Client_id=explode('=', $arrExpVals[1]);
					  	//echo "QUERY_STRING".$_SERVER["QUERY_STRING"];

					  	//$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid']: '';
					  	$this->objAnalytics->modShowClientProducts();
					  }
					  else if(isset($pUrl[2]) && ($pUrl[2]=='client_offers'))
					  {
					  	$this->objAnalytics->modShowClinetOffers();

					  }
					  else if(isset($pUrl[2]) && ($pUrl[2]=='clients'))
					  {
					  	if(isset($pUrl[4]) && ($pUrl[4]=='products'))
				  		{
				  			//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
				  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
				  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			$requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
				  			$searchByValOrPercent=isset($_REQUEST['searchByValOrPercent']) ? $_REQUEST['searchByValOrPercent'] : 1;
				  			$start_date=isset($pUrl[5]) ? $pUrl[5] : $requestStartDate;
				    	    $end_date=isset($pUrl[6]) ? $pUrl[6] : $requestEndDate;
				  			$this->objAnalytics->modShowMobileUseTrackDataByProducts($pUrl[3],$start_date ,$end_date,$searchByValOrPercent);

					  	}
					  	else if(isset($pUrl[4]) && ($pUrl[4]=='offers'))
				  		{
				  			//echo "offers";
				  			//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
				  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
				  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			$requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
				  			$start_date=isset($pUrl[5]) ? $pUrl[5] : $requestStartDate;
				    	    $end_date=isset($pUrl[6]) ? $pUrl[6] : $requestEndDate;
				  			$this->objAnalytics->modShowMobileUseTrackDataByOffers($pUrl[3],$start_date ,$end_date);

					  	}
					  	
					  }
					  else if(isset($pUrl[2]) && ($pUrl[2]=='products'))
					  {
					    if(isset($pUrl[3]) && ($pUrl[3]=='flow'))
					    {
					    	if(isset($pUrl[4]) && ($pUrl[4]=='users'))
					        {
					        	//flow users
					        	//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
					  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
					  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			    $requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
					  			$start_date=isset($pUrl[6]) ? $pUrl[6] : $requestStartDate;
					    	    $end_date=isset($pUrl[7]) ? $pUrl[7] : $requestEndDate;


						    	$this->objAnalytics->modShowMobileUserProductsFlowUsers($pUrl[5],$start_date ,$end_date);


					    	}
					    	else
					    	{
					    		//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
					  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
					  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			    $requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
					  			$start_date=isset($pUrl[5]) ? $pUrl[5] : $requestStartDate;
					    	    $end_date=isset($pUrl[6]) ? $pUrl[6] : $requestEndDate;

						    	$this->objAnalytics->modShowMobileUserProductsFlow($pUrl[4],$start_date ,$end_date);

					    	}
					    	
					    	

					    }
					    else
					    {

					        $this->objAnalytics->modShowMobileUseTrackDataByProducts();					    	
					    }



					  }
					  elseif(isset($pUrl[2]) && ($pUrl[2]=='offers'))
					  {
					  	if(isset($pUrl[3]) && ($pUrl[3]=='flow'))
					    {
					    	if(isset($pUrl[3]) && ($pUrl[3]=='users'))
					        {
					        	//flow users
					        	//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
					  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
					  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			    $requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
					  			$start_date=isset($pUrl[5]) ? $pUrl[5] : $requestStartDate;
					    	    $end_date=isset($pUrl[6]) ? $pUrl[6] : $requestEndDate;


						    	$this->objAnalytics->modShowMobileUserOffersFlowUsers($pUrl[4],$start_date ,$end_date);


					    	}
					    	else
					    	{
					    		//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
					  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
					  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			    $requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
					  			$start_date=isset($pUrl[5]) ? $pUrl[5] : $requestStartDate;
					    	    $end_date=isset($pUrl[6]) ? $pUrl[6] : $requestEndDate;


						    	$this->objAnalytics->modShowMobileUserOffersFlow($pUrl[4],$start_date ,$end_date);
					    	}
					    }	

					    elseif(isset($pUrl[3]) && $pUrl[3]=='users')
					    {
					    	//$requestStartDate=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
				  			//$requestEndDate=isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
				  			$requestStartDate=isset($_SESSION['start_date']) ? $_SESSION['start_date'] :'';
				  			$requestEndDate=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :'';
				  			$start_date=isset($pUrl[5]) ? $pUrl[5] : $requestStartDate;
				    	    $end_date=isset($pUrl[6]) ? $pUrl[6] : $requestEndDate;


					    	$this->objAnalytics->modShowMobileOfferUsers($pUrl[4],$start_date ,$end_date);

					    }
					    else
					    {
					    	$this->objAnalytics->modShowMobileUseTrackDataByOffers();
					    }
					    
					  
					  }
					  elseif(isset($pUrl[2]) && ($pUrl[2]=='product_views'))
					  {
					    if(isset($pUrl[3]) && ($pUrl[3]=='clients'))
					    {
					        if(isset($pUrl[4]) && ($pUrl[4]=='date'))
					        {
					        	$this->objAnalytics->modShowMobileUserProdViewsDateDet($pUrl[5],$pUrl[6]);

					        }
					        else
					        {
					        	$this->objAnalytics->modShowMobileUserClientProdViews($pUrl[4]);
					        }
					    	
					    }
					    else
					    {
					      $this->objAnalytics->modShowMobileUserTrackingDataByProdViews();
					    }
					  }
					  elseif(isset($pUrl[2]) && ($pUrl[2]=='offer_views'))
					  {
					  	if(isset($pUrl[3]) && ($pUrl[3]=='clients'))
					    {
					        if(isset($pUrl[4]) && ($pUrl[4]=='date'))
					        {
					        	$this->objAnalytics->modShowMobileUserOfferViewsDateDet($pUrl[5],$pUrl[6]);

					        }
					        else
					        {
					        	$this->objAnalytics->modShowMobileUserClientOfferViews($pUrl[4]);
					        }
					    	
					    }
					    else
					    {
					      $this->objAnalytics->modShowMobileUserTrackingDataByOfferViews();
					    }
					  }
					  /*elseif(isset($pUrl[2]) && ($pUrl[2]=='offer_views'))
					  {
					  	if(isset($pUrl[3]) && ($pUrl[3]=='date'))
					    { 
                          $this->objAnalytics->modShowMobileUserOfferViewsDateDet($pUrl[4]);
					    }
					    else
					    {	
					      $this->objAnalytics->modShowMobileUserTrackingDataByOfferViews();
					    }
					  
					  }*/
					  else
					  {
					  	//main mobile analytic dashboard
					  	//$start_date=isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d',strtotime('1 month ago'));
			            //$end_date=isset($_REQUEST['to']) ? $_REQUEST['to'] :date('Y-m-d');
			            $this->objAnalytics->modShowMobileUserTrackingData();
			           

					  }
					}  
  
					
				
				}else if ($pAction=='appusers'){
					
					$this->objUsers->modManageAppUsers();
						
				}else if ($pAction=='cmsusers'){
					if (isset($pUrl[1]) && ($pUrl[1]=='add')){
						$this->objUsers->modAddCmsUsers();
					}elseif (isset($pUrl[1]) && ($pUrl[1]=='edit')){
						if (isset($pUrl[2]) && !empty($pUrl[2])){
							$this->objUsers->modEditUsers($pUrl[2]);
						}else{
							$this->objUsers->modManageCMSUsers();
						}
					}elseif (isset($pUrl[1]) && ($pUrl[1]=='profile')){
						if (isset($pUrl[2]) && !empty($pUrl[2])){
							if (isset($pUrl[2]) && ($pUrl[2]=='id')){
							    if (isset($pUrl[4]) && ($pUrl[4]=='view')){
									//echo "View";
									$this->objUsers->modCmsUserProfileView($pUrl[3]);
									
								}else if (isset($pUrl[4]) && ($pUrl[4]=='edit')){
									//echo "edit";
									$this->objUsers->modEditCmsUserProfile($pUrl[3]);
								}
							}else if (isset($pUrl[2]) && ($pUrl[2]=="edit")){
								$this->objUsers->modUserProfileEdit();
							}else if (isset($pUrl[2]) && ($pUrl[2]=="password")){
								$this->objUsers->modUserProfilePassword();
							}
						}else{
							$this->objUsers->modUserProfile();
						}
					}elseif (isset($pUrl[1]) && ($pUrl[1]=='cms')){
						if (isset($pUrl[2]) && ($pUrl[2]=='wishlist')){
						//echo "comming soon";
						}
					
					
					
					}else{
						$this->objUsers->modManageCMSUsers();
						
					}
				}else if ($pAction=='creator'){
					$this->objCreator->modShowCreatorList();
				
				}else if ($pAction=='clients'){
					if (isset($pUrl[1]) && ($pUrl[1]=='id')){
						if (isset($pUrl[3]) && ($pUrl[3]=='products')){
							if (isset($pUrl[4]) && ($pUrl[4]=='related')){
								//echo "Related";
								//related
							}elseif (isset($pUrl[4]) && ($pUrl[4]=='offers')){
								//echo "offers";
							
							}elseif (isset($pUrl[4]) && ($pUrl[4]=='add')){
								//echo "add";	
								$this->objClients->modShowAddClientProductForm($pUrl[2]);
							}else{
								if (isset($pUrl[5]) && ($pUrl[5]=='view')){
									//view
									$this->objClients->modClientProductView($pUrl[2],$pUrl[4]);
								}elseif (isset($pUrl[5]) && ($pUrl[5]=='edit')){
									//edit
									$this->objClients->modClientProductEdit($pUrl[2],$pUrl[4]);
								}elseif (isset($pUrl[5]) && ($pUrl[5]=='additional')){
								    //additional
								    $this->objClients->modClientProductAdditional($pUrl[2],$pUrl[4]);
								}else{
									$this->objClients->modClientProducts($pUrl[2]);
								}
							}
						}
						else if (isset($pUrl[3]) && ($pUrl[3]=='stores')){


							if (isset($pUrl[5]) && ($pUrl[5]=='edit')){
							    //edit
								$this->objClients->modClientStoresEditForm($pUrl[2],$pUrl[4]);
						    }elseif (isset($pUrl[4]) && ($pUrl[4]=='add')){
								//echo "add";
								$this->objClients->modClientStoresAddForm($pUrl[2]);

							}else
						    {
						    	$this->objClients->modClientStores($pUrl[2]);
						    }
							
						}
						else if (isset($pUrl[3]) && ($pUrl[3]=='triggers')){
							if (isset($pUrl[4]) && ($pUrl[4]=='related')){
								//echo "Related";
								//related
							}elseif (isset($pUrl[4]) && ($pUrl[4]=='offers')){
								//echo "offers";
							}elseif (isset($pUrl[4]) && ($pUrl[4]=='add')){
								//echo "add";
								$this->objClients->modShowClientTriggerAddForm($pUrl[2]);

							}else{
								if (isset($pUrl[5]) && ($pUrl[5]=='view')){
									//view
									$this->objClients->modClientTriggerView($pUrl[2],$pUrl[4]);
								}elseif (isset($pUrl[5]) && ($pUrl[5]=='edit')){
									//edit
									$this->objClients->modClientTriggerEdit($pUrl[2],$pUrl[4]);
								}else if (isset($pUrl[5]) && ($pUrl[5]=='visuals')){
									//visuals
									if (isset($pUrl[7]) && ($pUrl[7]=='edit')){
									//visuals edit
									  $this->objClients->modClientTriggerVisualsEditForm($pUrl[2],$pUrl[4],$pUrl[6]);
									}else if(isset($pUrl[7]) && ($pUrl[7]=='models')){
									   $this->objClients->modClientTriggerModels($pUrl[2],$pUrl[4],$pUrl[6]);
									}
									else
									{
									  $this->objClients->modClientTriggerVisuals($pUrl[2],$pUrl[4]);
									}
								}else{
									$this->objClients->modClientTriggers($pUrl[2]);
								}
							}
							
						}
						else if (isset($pUrl[3]) && ($pUrl[3]=='view')){
							//echo "view";
							
						}else if (isset($pUrl[3]) && ($pUrl[3]=='edit')){
							//echo "edit";
							$this->objClients->modClientEditDetails($pUrl[2]);
						}else{
							$this->objClients->modClientById($pUrl[2]);
						}
					}else if (isset($pUrl[1]) && ($pUrl[1]=='add')){
					    //echo "add";
						$this->objClients->modShowClientForm();
					}else{
						$this->objClients->modAllClients();
					}
				}else{
					echo "Access denied";
				}
			}else if ($pAction=='public'){
				if (isset($pUrl[1]) && ($pUrl[1]=='triggers')){					
					$this->objPublic->modAllTriggers();
				}
				if (isset($pUrl[1]) && ($pUrl[1]=='client')){
					$this->objPublic->modClientTriggers($pUrl);
				}
				if (isset($pUrl[1]) && ($pUrl[1]=='product')){
					$this->objPublic->modClientProduct($pUrl);
				}
				if (isset($pUrl[1]) && ($pUrl[1]=='client_product')){
					$this->objPublic->modClientProductNew($pUrl);
				}
			}else if ($pAction=='activation'){
				if (isset($pUrl[1]) && !empty($pUrl[1])){
					$this->objUsers->modPasswordActivation($pUrl[1]);
					$this->objLogin->modSalesLogin($pUrl);
				}
			}else if ($pAction=='arapp'){
				if (isset($pUrl[1]) && !empty($pUrl[1]) && ($pUrl[1]=='login')){
					$this->objAppLogin->getAccessWithAppLoginDetails($pUrl);
				}
				if (isset($pUrl[1]) && !empty($pUrl[1]) && ($pUrl[1]=='register')){
					$this->objAppLogin->getInsertRegistrationDetails($pUrl);
				}
			}else {
				$this->objLogin->modSalesLogin($pUrl);
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
				include SRV_ROOT.'views/home/footer.tpl.php';
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