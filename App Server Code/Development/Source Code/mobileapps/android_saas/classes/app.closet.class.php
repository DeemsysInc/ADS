<?php 
class cCloset{
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
			require_once(SRV_ROOT.'model/app.closet.model.class.php');
			$this->objCloset = new mCloset();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/app.product.wishlist.model.class.php');
			$this->objProductWishlist = new mProductWishlist();
		
			require_once SRV_ROOT.'classes/app.saas.clientdb.class.php';
			$this->objAppSassClientDbs = new cAppSaasClientDbs();
			
			$this->mainDbName = $config['database']['name'];
			$this->markersDbName = $config['database']['name_markers'];
			$this->usersDbName = $config['database']['name_users'];
			$this->userAnalyticsDbName = $config['database']['name_user_analytics'];
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetClosetProductValues($cValsArray, $newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$arrOutResults = array();	
			$outResultsForCloset = array();
			$outResultsForWishlist = array();	
			//echo count($newConfigArrResult);
			//print_r($newConfigArrResult);
			/*$outResultsForCloset = $this->objCloset->getUserWithProductsCloset($cValsArray);
			$outResultsForWishlist = $this->objCloset->getUserWithProductsWishList($cValsArray);*/
			//$outResults = $this->objCloset->getUserWithProductsWishListAndCloset($cValsArray);
			
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ? $newConfigArrResult['db_password'][$i] : '';
						$outResults[$config['database']['name_clients']] = $this->objCloset->getClosetProductsValues($cValsArray);
					}
				}
			}
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$clientId = isset($value1["client_id"]) ? $value1["client_id"] : 0;
					$image = isset($value1["pd_image"]) ? $value1["pd_image"] : "";
					$value1["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
					$finalOutResults[] = $value1;
				}
			}
			$arrOutResults['prodCloset'] = $finalOutResults;
			
			if(count($arrOutResults['prodCloset'])>0){  
				if($cValsArray['resultFormatType']=="json"){
					echo json_encode($arrOutResults);
				} else {
					$xml = Array2XML::createXML('rootCloset', $arrOutResults);
					echo $xml->saveXML();
				}
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function modGetClosetWishListProductValues($cValsArray, $newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$arrOutResults = array();	
			$outResultsForCloset = array();
			$outResultsForWishlist = array();		
			/*$outResultsForCloset = $this->objCloset->getUserWithProductsCloset($cValsArray);
			$outResultsForWishlist = $this->objCloset->getUserWithProductsWishList($cValsArray);*/
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ?$newConfigArrResult['db_password'][$i] : '';
						$outResults[$config['database']['name_clients']] = $this->objCloset->getUserWithProductsWishListAndCloset($cValsArray);
					}
				}
			}
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$clientId = isset($value1["client_id"]) ? $value1["client_id"] : 0;
					$image = isset($value1["pd_image"]) ? $value1["pd_image"] : "";
					$value1["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
					$finalOutResults[] = $value1;
				}
			}
			$arrOutResults['prodCloset'] = $finalOutResults;
			//$outResults = $this->objCloset->getUserWithProductsWishListAndCloset($cValsArray);
			
			
			/*for($i=0;$i<count($outResults);$i++)
			{
				$clientId = isset($outResults[$i]["client_id"]) ? $outResults[$i]["client_id"] : 0;
				$image = isset($outResults[$i]["pd_image"]) ? $outResults[$i]["pd_image"] : "";
				$outResults[$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
			}
			$arrOutResults['prodCloset'] = $outResults;
			*/
			if(count($arrOutResults['prodCloset'])>0){  
				if($cValsArray['resultFormatType']=="json"){
					echo json_encode($arrOutResults);
				} else {
					$xml = Array2XML::createXML('rootCloset', $arrOutResults);
					echo $xml->saveXML();
				}
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modInsertClosetWithUserProdIds($cValsArray){
		try{
			global $config;	
			$outResults = array();
			$cTableName = $this->usersDbName.".`closet`";
			$cArray = array();
			$cArray['user_id'] = $cValsArray['userId'];
			$cArray['client_id'] = $cValsArray['clientId'];
			$cArray['pd_id'] = $cValsArray['productId'];
			$cArray['closet_created_date'] = 'NOW()';
			$cArray['closet_updated_date'] = 'NOW()';
			$cArray['closet_selection_status'] = 1;
			$cArray['closet_status'] = 1;
			
			$ctTableName = $this->usersDbName.".`closet_tracking`" ;
			$ctArray = array();
			$ctArray['closet_tracking_session_id'] = session_id();
			$ctArray['closet_tracking_created_date'] = 'NOW()';
			$ctArray['closet_tracking_created_by_id'] = $cValsArray['userId'];
			$ctArray['closet_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
			$ctArray['closet_tracking_updated_date'] = 'NOW()';
			$ctArray['closet_tracking_updated_by_id'] = $cValsArray['userId'];
			$ctArray['closet_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
			
 			$checkedRecord = $this->objCloset->checkingClosetValues($cArray);
 
			if(count($checkedRecord)==0){
				$closetInsertId = $this->objCloset->insertQuery($cArray, $cTableName, true);
				if($closetInsertId){
					$ctArray['closet_id'] = $closetInsertId;					
					$insertRecordClosetTracking = $this->objCloset->insertQuery($ctArray, $ctTableName, false);
					if($insertRecordClosetTracking){
						$outResults['msg'] = 'success';
					} else {
						$outResults['msg'] = 'failed';
					}
				} else {
					$outResults['msg'] = 'failed';
				}
			} else {
				$outResults['msg'] = 'already';
			}	
			if(count($outResults)>0){  
				$xml = Array2XML::createXML('rootCloset', $outResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUpdateClosetWithUserProdIds($cValsArray){
		try{
			global $config;	
			$outResults = array();
			$con = array();
			$cTableName = $this->usersDbName.".`closet`";
			$cArray = array();
			$con['user_id'] = $cValsArray['userId'];
			$con['client_id'] = $cValsArray['clientId'];
			$con['pd_id'] = $cValsArray['productId'];
			$cArray['closet_selection_status'] = $cValsArray['closetSelStatus'];
			//if($cValsArray['closetSelStatus']==2)
			//$cArray['closet_created_date'] = 'NOW()';
			$cArray['closet_updated_date'] = 'NOW()';
			//$cArray['closet_status'] = 1;
			$updateRecord = $this->objCloset->updateRecordQuery($cArray, $cTableName, $con);
 			$checkedRecord = $this->objCloset->checkingClosetValues($con);
			if($updateRecord && count($checkedRecord)>0){
				$con1 = array();
				$con1['closet_id'] = isset($checkedRecord[0]['closet_id']) ? $checkedRecord[0]['closet_id'] : 0;
				$ctTableName = $this->usersDbName.".`closet_tracking`" ;
				$ctArray = array();
				$ctArray['closet_tracking_session_id'] = session_id();
				//$ctArray['closet_tracking_created_date'] = 'NOW()';
				//$ctArray['closet_tracking_created_by_id'] = $cValsArray['userId'];
				//$ctArray['closet_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
				$ctArray['closet_tracking_updated_date'] = 'NOW()';
				$ctArray['closet_tracking_updated_by_id'] = $cValsArray['userId'];
				$ctArray['closet_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
				$updateRecord1 = $this->objCloset->updateRecordQuery($ctArray, $ctTableName, $con1);
				$outResults['msg'] = 'success';
			} else {
				$outResults['msg'] = 'failed';
			}
			if(count($outResults)>0){  
				$xml = Array2XML::createXML('rootCloset', $outResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modDeleteClosetWithUserProdIds($cValsArray){
		try{
			global $config;	
			$outResults = array();
			$con = array();
			$cTableName = $this->usersDbName.".`closet`";
			$cArray = array();
			$con['user_id'] = $cValsArray['userId'];
			$con['client_id'] = $cValsArray['clientId'];
			$con['pd_id'] = $cValsArray['productId'];
			$cArray['closet_selection_status'] = $cValsArray['closetSelStatus'];
			$cArray['closet_updated_date'] = 'NOW()';
			$cArray['closet_status'] = 2;

			$updateRecord = $this->objCloset->updateRecordQuery($cArray, $cTableName, $con);
 			$checkedRecord = $this->objCloset->checkingClosetValues($con);
			if($updateRecord && count($checkedRecord)>0){
				$con1 = array();
				$con1['closet_id'] = isset($checkedRecord[0]['closet_id']) ? $checkedRecord[0]['closet_id'] : 0;
				$ctTableName = $this->usersDbName.".`closet_tracking`";
				$ctArray = array(); 
				$ctArray['closet_tracking_session_id'] = session_id();
				//$ctArray['closet_tracking_created_date'] = 'NOW()';
				//$ctArray['closet_tracking_created_by_id'] = $cValsArray['userId'];
				//$ctArray['closet_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
				$ctArray['closet_tracking_updated_date'] = 'NOW()';
				$ctArray['closet_tracking_updated_by_id'] = $cValsArray['userId'];
				$ctArray['closet_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
				$updateRecord1 = $this->objCloset->updateRecordQuery($ctArray, $ctTableName, $con1);
				$outResults['msg'] = 'success';
			} else {
				$outResults['msg'] = 'failed';
			}
			
			if(count($outResults)>0){  
				$xml = Array2XML::createXML('rootCloset', $outResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetClosetForBrands($cValsArray, $newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$arrOutResults = array();	
			$outResultsForCloset = array();
			$outResultsForWishlist = array();		
			/*$outResultsForCloset = $this->objCloset->getUserWithProductsCloset($cValsArray);
			$outResultsForWishlist = $this->objCloset->getUserWithProductsWishList($cValsArray);*/
			//$outResults = $this->objCloset->getUserWithProductsWishListAndCloset($cValsArray);
			
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ? $newConfigArrResult['db_password'][$i] : '';
						$outResults[$config['database']['name_clients']] = $this->objCloset->getClosetProductsForBrands($cValsArray);
					}
				}
			}
			//print_r($outResults);
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$clientId = isset($value1["client_id"]) ? $value1["client_id"] : 0;
					$image = isset($value1["pd_image"]) ? $value1["pd_image"] : "";
					$value1["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
					$finalOutResults[] = $value1;
				}
			}
			$arrOutResults['prodCloset'] = $finalOutResults;
			//print_r($arrOutResults);
			
			//$outResults = $this->objCloset->getClosetProductsForBrands($cValsArray);
			
			/*for($i=0;$i<count($outResults);$i++)
			{
				$clientId = isset($outResults[$i]["client_id"]) ? $outResults[$i]["client_id"] : 0;
				$image = isset($outResults[$i]["pd_image"]) ? $outResults[$i]["pd_image"] : "";
				$outResults[$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
			}
			$arrOutResults['prodCloset'] = $outResults;
			*/
			if(count($arrOutResults['prodCloset'])>0){  
				if($cValsArray['resultFormatType']=="json"){
					echo json_encode($arrOutResults);
				} else {
					$xml = Array2XML::createXML('rootCloset', $arrOutResults);
					echo $xml->saveXML();
				}
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetClosetProductByBrands($cValsArray, $newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$arrOutResults = array();
			
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ? $newConfigArrResult['db_password'][$i] : '';
						$outResults[$config['database']['name_clients']] = $this->objCloset->getClosetProductsByBrands($cValsArray);
					}
				}
			}
			//print_r($outResults);
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$clientId = isset($value1["client_id"]) ? $value1["client_id"] : 0;
					$image = isset($value1["pd_image"]) ? $value1["pd_image"] : "";
					$value1["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
					$finalOutResults[] = $value1;
				}
			}
			$arrOutResults['prodCloset'] = $finalOutResults;
			/*$outResults = $this->objCloset->getClosetProductsByBrands($cValsArray);
			for($i=0;$i<count($outResults);$i++)
			{
				$clientId = isset($outResults[$i]["client_id"]) ? $outResults[$i]["client_id"] : 0;
				$image = isset($outResults[$i]["pd_image"]) ? $outResults[$i]["pd_image"] : "";
				$outResults[$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
			}
			$arrOutResults['prodCloset'] = $outResults;*/
			if(count($arrOutResults['prodCloset'])>0){
				if($cValsArray['resultFormatType']=="json"){
					echo json_encode($arrOutResults);
				} else {
					$xml = Array2XML::createXML('rootCloset', $arrOutResults);
					echo $xml->saveXML();
				}
			}
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