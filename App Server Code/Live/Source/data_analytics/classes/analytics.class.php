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
			require_once(SRV_ROOT.'model/analytics.model.class.php');
			$this->objAnalyticsQuery = new mAnalytics();
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modtrackErrorCrashData($jsonArray){
		try{
			global $config;	
			//echo "hellp";
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="crash_log";
				
			
		    $pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['os_version'] = isset($jsonArray['os_version']) ? $jsonArray['os_version'] : '';
			$pArray['error_message'] = isset($jsonArray['error_message']) ? $jsonArray['error_message'] : '';
			$pArray['error_debug'] = isset($jsonArray['error_debug']) ? $jsonArray['error_debug'] : '';
			$pArray['crash_type'] = isset($jsonArray['crash_type']) ? $jsonArray['crash_type'] : '';
			$pArray['build_version'] = isset($jsonArray['build_version']) ? $jsonArray['build_version'] : '';
			$pArray['additional_info'] = isset($jsonArray['additional_info']) ? $jsonArray['additional_info'] : '';
			$pArray['crash_date'] = date('Y-m-d H:i:s');
			//print_r($pArray);
		
			$insertData=$this->objAnalyticsQuery->insertQuery($pArray, $tableName,null);
			if ($insertData){
				echo 'success';
				//$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/';
			}else{
				echo 'fail';
			}
		
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modTrackUserData($jsonArray){
		try{
			global $config;	
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$screenPath=isset($jsonArray['screen_path']) ? $jsonArray['screen_path'] : '';
			
			$productIds=isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : '';
			$offerIds=isset($jsonArray['offer_ids']) ? $jsonArray['offer_ids'] : '';
			$latLongs=isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			
			$tableName="data_analytics";
				
			$arrAllDataAnalyticsBySessionIdUserId=$this->objAnalyticsQuery->getDataAnalyticsInfoBySessionId($session_id,$userId);
			//print_r($arrAllDataAnalyticsBySessionIdUserId);
			
			if(count($arrAllDataAnalyticsBySessionIdUserId)>0)
			{
			  echo "session id same";
			  
			   
			  for($i=0;$i<count($arrAllDataAnalyticsBySessionIdUserId);$i++)
			  {
				  if($userId==$arrAllDataAnalyticsBySessionIdUserId[$i]['user_id'])
				  {
					//update
					$screenInfoFromDB=isset($arrAllDataAnalyticsBySessionIdUserId[$i]['screen_path']) ? $arrAllDataAnalyticsBySessionIdUserId[$i]['screen_path'] : '';
					$arrScrValues= json_decode($screenInfoFromDB,true);
				    //print_R($arrScrValues);
				    
				    $arrScreenInfo=array();
					for($j=0;$j<count($arrScrValues);$j++)
					{
					
						$arrScreenInfo[$j]['screen_name']=$arrScrValues[$j]['screen_name'];
						$arrScreenInfo[$j]['product_ids']=$arrScrValues[$j]['product_ids'];
						$arrScreenInfo[$j]['offer_ids']=$arrScrValues[$j]['offer_ids'];
						$arrScreenInfo[$j]['created_date']=date('Y-m-d H:i:s');
					
					
						$arrScreenInfo[$j+1]['screen_name']=$screenPath;
						$arrScreenInfo[$j+1]['product_ids']=$productIds;
						$arrScreenInfo[$j+1]['offer_ids']=$offerIds;
					    $arrScreenInfo[$j+1]['created_date']=date('Y-m-d H:i:s');
					
					}
					
					$uArray =array();	
					$uArray['screen_path'] = json_encode($arrScreenInfo);
					$uArray['modified_date'] = date('Y-m-d H:i:s');
					$con=array();
					$con['session_id']=$session_id;
					$con['user_id']=$userId;
					
					$UpdatedData=$this->objAnalyticsQuery->updateRecordQuery($uArray,$tableName,$con);
				
				
				  }
				  
			  }
			  
			
			}else{
			
				//insert 
				$arrScreenInfo=array();	
				$arrScreenInfo[0]['screen_name']=$screenPath;
				$arrScreenInfo[0]['product_ids']=$productIds;
				$arrScreenInfo[0]['offer_ids']=$offerIds;
				$arrScreenInfo[0]['created_date']=date('Y-m-d H:i:s');
			    
			    $pArray =array();	
				$pArray['user_id'] = $userId;
				$pArray['session_id'] = $session_id;
				//$pArray['screen_path'] = isset($jsonArray['screen_path']) ? $jsonArray['screen_path'] : '';
				$pArray['screen_path'] = json_encode($arrScreenInfo);
				$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
				$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
				$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
				$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
				$pArray['user_address'] = isset($jsonArray['user_address']) ? $jsonArray['user_address'] : '';
				$pArray['user_country'] = isset($jsonArray['user_country']) ? $jsonArray['user_country'] : '';
				$pArray['user_state'] = isset($jsonArray['user_state']) ? $jsonArray['user_state'] : '';
				$pArray['user_city'] = isset($jsonArray['user_city']) ? $jsonArray['user_city'] : '';
				$pArray['time_on_screen'] = isset($jsonArray['time_on_screen']) ? $jsonArray['time_on_screen'] : '';
				$pArray['created_date'] = date('Y-m-d H:i:s');
			
				print_r($pArray);
				$insertData=$this->objAnalyticsQuery->insertQuery($pArray, $tableName,null);
		
			
			}
			
			
			/*
			$arrAllDataAnalyticsBySessionId=$this->objAnalyticsQuery->getDataAnalyticsInfoBySessionId($session_id);
			$tableName="data_analytics";
			
			if(count($arrAllDataAnalyticsBySessionId)>0)
			{
			  
			    $screenInfo=isset($arrAllDataAnalyticsBySessionId[0]['screen_path'])?$arrAllDataAnalyticsBySessionId[0]['screen_path']: '';
			    $arrScrValues= json_decode($screenInfo,true);
			   // print_R($arrScrValues);
			   
			    for($i=0;$i<count($arrScrValues);$i++)
			    {
					if($screenPath==$arrScrValues[$i]['screen_name'])
					{
					    //echo "same".$arrScrValues[$i]['screen_name'];
						$screencount=1;
						
						$arrScreenInfo[$i]['screen_name']=$arrScrValues[$i]['screen_name'];
						$screencount += $arrScrValues[$i]['screen_count'];
						$arrScreenInfo[$i]['screen_count'] = $screencount;
						
					}
					else
					{
					   
						 $arrScreenInfo[$i]['screen_name']=$arrScrValues[$i]['screen_name'];
						 $arrScreenInfo[$i]['screen_count']=$arrScrValues[$i]['screen_count'];
					     $arrScreenInfo[$i+1]['screen_name']=$screenPath;
						 $arrScreenInfo[$i+1]['screen_count']=1;
					
					   
					}
			        
			    }    
			        //update
					$uArray =array();	
					$uArray['screen_path'] = json_encode($arrScreenInfo);
					$uArray['modified_date'] = date('Y-m-d H:i:s');
					$con=array();
					$con['session_id']=$session_id;
					$UpdatedData=$this->objAnalyticsQuery->updateRecordQuery($uArray,$tableName,$con);
					
			    
			  
			}
			else
			{
			    $arrScreenInfo=array();	
				$arrScreenInfo[]=isset($jsonArray['screen_path']) ? $jsonArray['screen_path'] : '';
				$arrScreenInfo['screen_name']=isset($jsonArray['screen_path']) ? $jsonArray['screen_path'] : '';
				$arrScreenInfo['screen_count']="1";
			
			
				$pArray =array();	
				$pArray['user_id'] = isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '';
				$pArray['session_id'] = isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
				//$pArray['screen_path'] = isset($jsonArray['screen_path']) ? $jsonArray['screen_path'] : '';
				$pArray['screen_path'] = json_encode($arrScreenInfo);
				$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
				$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
				$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
				$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
				$pArray['user_address'] = isset($jsonArray['user_address']) ? $jsonArray['user_address'] : '';
				$pArray['user_country'] = isset($jsonArray['user_country']) ? $jsonArray['user_country'] : '';
				$pArray['user_state'] = isset($jsonArray['user_state']) ? $jsonArray['user_state'] : '';
				$pArray['user_city'] = isset($jsonArray['user_city']) ? $jsonArray['user_city'] : '';
				$pArray['time_on_screen'] = isset($jsonArray['time_on_screen']) ? $jsonArray['time_on_screen'] : '';
				$pArray['created_date'] = date('Y-m-d H:i:s');
			
				//insert
				$insertData=$this->objAnalyticsQuery->insertQuery($pArray, $tableName,null);
			
			}
			*/
			
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