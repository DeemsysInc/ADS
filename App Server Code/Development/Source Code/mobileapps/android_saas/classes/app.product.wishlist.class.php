<?php 
class cProductWishlist{
	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objCommon;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			$program_start_time = time();	
			global $config;
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/app.product.wishlist.model.class.php');
			$this->objProductWishlist = new mProductWishlist();		
			
			//require_once SRV_ROOT.'classes/thread.class.php';
		
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
	
	public function modGetWishListProducts($loggedInUserId,$wishListName, $newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$wishListName = str_replace("%20", " ", $wishListName);
			//print_r($newConfigArrResult);
			
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ? $newConfigArrResult['db_password'][$i] : '';
						$outResults[$config['database']['name_clients']] = $this->objProductWishlist->getUserWishListProducts($loggedInUserId, $wishListName);
						
						/*$thread = "thread_".$i;
						$$thread = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);
						$$thread->setFunc("mProductWishlist::getUserWishListProducts", array(6, "new"));
						$$thread->start();*/
					}
				}
			}
			$finalOutResults = array();
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//$thread = "thread_".$i;
						//$$thread = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);
						//$finalOutResults[] = $$thread->getreturn();
					}
				}
			}
			//print_r($finalOutResults);
			//echo "Main Program has run ".(time()-$program_start_time)." seconds<br />";
			
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$clientId = isset($value1["client_id"]) ? $value1["client_id"] : 0;
					$image = isset($value1["pd_image"]) ? $value1["pd_image"] : "";
					if($clientId!=0){
						$value1["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
						$finalOutResults[] = $value1;
					} /*else if($clientId==0 && $value1["flagForEmptyWishlist"]==0) {
						$finalOutResults = $value1;
					}*/
				}
			}
			$arrOutResults['prodWishList'] = $finalOutResults;
			//echo count($arrOutResults['prodWishList']);
			//print_r($arrOutResults);
			//print_r($arrOutResults['prodCloset']);
			/*$outResults['prodWishList'] = $this->objProductWishlist->getUserWishListProducts($loggedInUserId,$wishListName);
			//print_r($outResults);
			for($i=0;$i<count($outResults['prodWishList']);$i++)
			{
				$clientId = isset($outResults['prodWishList'][$i]["client_id"]) ? $outResults['prodWishList'][$i]["client_id"] : 0;
				$image = isset($outResults['prodWishList'][$i]["pd_image"]) ? $outResults['prodWishList'][$i]["pd_image"] : "";
				if($clientId!=0){
					$outResults['prodWishList'][$i]["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
				}
			}*/
			//echo json_encode($outResults);
			//echo count(prodWishList);
			if(count($arrOutResults['prodWishList'])){
				$xml = Array2XML::createXML('productsWishlist', $arrOutResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetAllWishListProducts($loggedInUserId, $wishListName,$newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$wishListName = str_replace("%20", " ", $wishListName);
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ? $newConfigArrResult['db_password'][$i] : '';
						
						$outResults[$config['database']['name_clients']] = $this->objProductWishlist->getUserAllWishListProducts($loggedInUserId, $wishListName);						
					}
				}
			}
			$finalOutResults = array();
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$clientId = isset($value1["client_id"]) ? $value1["client_id"] : 0;
					$image = isset($value1["pd_image"]) ? $value1["pd_image"] : "";
					if($clientId!=0){
						$value1["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
						$finalOutResults[] = $value1;
					} /*else if($clientId==0 && $value1["flagForEmptyWishlist"]==0) {
						$finalOutResults = $value1;
					}*/
				}
			}
			$arrOutResults['prodWishList'] = $finalOutResults;
			/*$outResults['prodWishList'] = $this->objProductWishlist->getUserAllWishListProducts($loggedInUserId,$wishListName);
			//print_r($outResults);
			for($i=0;$i<count($outResults['prodWishList']);$i++)
			{
				$clientId = isset($outResults['prodWishList'][$i]["client_id"]) ? $outResults['prodWishList'][$i]["client_id"] : 0;
				$image = isset($outResults['prodWishList'][$i]["pd_image"]) ? $outResults['prodWishList'][$i]["pd_image"] : "";
				if($clientId!=0){
					$outResults['prodWishList'][$i]["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
				}
			}*/
			//echo json_encode($outResults);
			
			if(count($arrOutResults['prodWishList'])>0){
				$xml = Array2XML::createXML('productsWishlist', $arrOutResults);
				echo $xml->saveXML();	
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetYourClosetFromWishListProducts($loggedInUserId){
		try{
			global $config;
			$outResults = array();
			$outResults['prodClosetFromWishList'] = $this->objProductWishlist->getYourClosetFromWishListProducts($loggedInUserId);
			//print_r($outResults);
			for($i=0;$i<count($outResults['prodClosetFromWishList']);$i++)
			{
				$clientId = isset($outResults['prodClosetFromWishList'][$i]["client_id"]) ? $outResults['prodClosetFromWishList'][$i]["client_id"] : 0;
				$image = isset($outResults['prodClosetFromWishList'][$i]["pd_image"]) ? $outResults['prodClosetFromWishList'][$i]["pd_image"] : "";
				$outResults['prodClosetFromWishList'][$i]["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
			}
			//echo json_encode($outResults);
		if(count($outResults)>0){
			$xml = Array2XML::createXML('productsWishlistRoot', $outResults);
			echo $xml->saveXML();
		}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAddWishListFromOffersProducts($loggedInUserId, $productId){
		try{
			global $config;
			$outResults = array();
			
			$wTableName = $this->usersDbName.".`wishlist`";
			$wArray = array();
			$wArray['name'] = "My Offers";
			$wArray['user_id'] = $loggedInUserId;
			$wArray['shared'] = 0;
 			$checkedRecord = $this->objProductWishlist->checkingWishListName($wArray);
			$wTableName1 = $this->usersDbName.".`wishlist_details`";
			if(count($checkedRecord) > 0){
				$uArray = array();
				$uArray['wishlist_id'] = isset($checkedRecord[0]['id']) ? $checkedRecord[0]['id'] : 0;
				$uArray['product_id'] = $productId;
				$insertRecord2 = $this->objProductWishlist->insertQuery($uArray, $wTableName1, false);
				if($insertRecord2)
				{
					$outResults['msg'] = 'success';
				} else {
					$outResults['msg'] = 'failed';
				}
			} else {
				$insertRecord = $this->objProductWishlist->insertQuery($wArray, $wTableName, true);
				if ($insertRecord && $productId!=""){				
					$wArray1 = array();
					$wArray1['wishlist_id'] = $insertRecord;
					$wArray1['product_id'] = $productId;
					$insertRecord1 = $this->objProductWishlist->insertQuery($wArray1, $wTableName1, false);
					if($insertRecord1)
					{
						$outResults['msg'] = 'success';
					} else {
						$outResults['msg'] = 'failed';
					}
				}
			}
			
			echo json_encode($outResults); 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAddWishListName($loggedInUserId, $wishListName){
		try{
			global $config;
			$outResults = array();
			
			$wTableName = $this->usersDbName.".`wishlist`";
			$wArray = array();
			$wArray['wishlist_name'] = str_replace("%20", " ", $wishListName);
			$wArray['user_id'] = $loggedInUserId;
			$wArray['wishlist_created_date'] = 'NOW()';
			$wArray['wishlist_created_by'] = $loggedInUserId;
			$wArray['wishlist_status'] = 1;
			
 			$checkedRecord = $this->objProductWishlist->checkingWishListName($wArray);
 
			if(count($checkedRecord) > 0){
				$outResults['msg'] = 'already';
			} else if($wishListName!=""){
				$insertRecord = $this->objProductWishlist->insertQuery($wArray, $wTableName, false);
				if ($insertRecord){
					$outResults['msg'] = 'success';
				} else {
					$outResults['msg'] = 'failed';
				}
			} else {
				$outResults['msg'] = 'failed';
			}			
			//echo json_encode($outResults); 
		if(count($outResults)>0){
				$xml = Array2XML::createXML('addToWishlist', $outResults);
				echo $xml->saveXML();
		}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetSelectedWishListNames($loggedInUserId){
		try{
			global $config;
			$outResults = array();
			
 			$outResults['wishLists'] = $this->objProductWishlist->getUserRelatedWishListNames($loggedInUserId);
			//echo json_encode($outResults);
			if(count($outResults)>0){
				$xml = Array2XML::createXML('userRelatedWishListNames', $outResults);
				echo $xml->saveXML();
			}
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAddItemsToWishListInProducts($loggedInUserId, $productIds, $wishListIds, $productIdsUnChecked, $wishListIdsUnChecked){
		try{
			global $config;
			$outResults = array();
			$outResults1 = array();
			$wArray = array();
			
			$wishTableName = $this->usersDbName.".`wishlist_tracking`" ;
			$wishArray = array();
			$wishArray['wishlist_tracking_session_id'] = session_id();
			$wishArray['wishlist_tracking_created_date'] = 'NOW()';
			$wishArray['wishlist_tracking_created_by_id'] = $loggedInUserId;
			$wishArray['wishlist_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
			$wishArray['wishlist_tracking_updated_date'] = 'NOW()';
			$wishArray['wishlist_tracking_updated_by_id'] = $loggedInUserId;
			$wishArray['wishlist_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
			
			$expProductIds = explode(",", $productIds);
			/*$expWishlistIds = explode(",", $wishListIds);
			print_r($expWishlistIds);
			$uniqueWishListId = 0;
			if(count($expWishlistIds)>0){
				for($k=0; $k<count($expWishlistIds); $k++){
					$uniqueWishListId = (isset($expWishlistIds[$k]) && $expWishlistIds[$k]!=0) ? $expWishlistIds[$k] : 0;
				}
			}*/
			//echo $productIdsUnChecked;
			$expProductIdsUnChecked = array();
			if($productIdsUnChecked!=0){
				$expProductIdsUnChecked = explode(",", $productIdsUnChecked);
			}
			/*$expWishlistIdsUnChecked = explode(",", $wishListIdsUnChecked);
			print_r($expWishlistIdsUnChecked);
			$uniqueWishListIdUnChecked = 0;
			if(count($expWishlistIdsUnChecked)>0){
				for($k=0; $k<count($expWishlistIdsUnChecked); $k++){
					$uniqueWishListIdUnChecked = (isset($expWishlistIdsUnChecked[$k]) && $expWishlistIdsUnChecked[$k]!=0) ? $expWishlistIdsUnChecked[$k] : 0;
				}
			}*/
			/*print_r($expProductIds);
			print_r($expProductIdsUnChecked);
			echo count($expProductIds)." ".count($expProductIdsUnChecked)."<br>";*/
			if(count($expProductIds)>0){
				$checkCountFlagTrue = 0;
				$checkCountFlagFalse = 0;
				$checkCountFlagTrueElse = 0;
				$checkCountFlagFalseElse = 0;
				for($p=0; $p<count($expProductIds); $p++){
					$wArray['wishlist_id'] = $wishListIds;
					$wArray['user_id'] = $loggedInUserId;
					$wArray['pd_id'] = isset($expProductIds[$p]) ? $expProductIds[$p] : 0;
					$checkedRecord = $this->objProductWishlist->checkingWishListWithProduct($wArray);
					//echo "count ".count($checkedRecord);
					if(count($checkedRecord)==0 && $wArray['pd_id']!="" && $wArray['wishlist_id']!=""){				
						$wTableName1 = $this->usersDbName.".`wishlist_details`";
						$uArray = array();
						$uArray['wishlist_id'] = $wArray['wishlist_id'];
						$uArray['pd_id'] = $wArray['pd_id'];
						$uArray['wishlist_details_status'] = 1;
						$insertRecordWithGetId = $this->objProductWishlist->insertQuery($uArray, $wTableName1, true);
						
						$wishArray['wishlist_details_id'] = $insertRecordWithGetId;
						$insertRecordWishListTracking = $this->objProductWishlist->insertQuery($wishArray, $wishTableName, false);
						if($insertRecordWithGetId)
						{
							$checkCountFlagTrue++;
							//$outResults['msg'] = 'success';
						} else {
							$checkCountFlagFalse++;
							//$outResults['msg'] = 'failed';
						}
					} else {
						//$outResults['msg'] = 'already';	
						$wArray = array();	
						$wArray['wishlist_details_status'] = 1;				
						$dArray = array();
						$dArray['wishlist_id'] = isset($checkedRecord[0]['wishlist_id']) ? $checkedRecord[0]['wishlist_id'] : 0;	
						$dArray['pd_id'] = isset($checkedRecord[0]['pd_id']) ? $checkedRecord[0]['pd_id'] : 0;
						$updateRecord = $this->objProductWishlist->updateRecordQuery($wArray, $this->usersDbName.".`wishlist_details`", $dArray);
						
						$wishTableName1 = $this->usersDbName.".wishlist_tracking" ;
						$wishArray1 = array();
						$wishArray1['wishlist_tracking_session_id'] = session_id();
						//$wishArray1['wishlist_tracking_created_date'] = 'NOW()';
						//$wishArray1['wishlist_tracking_created_by_id'] = $loggedInUserId;
						//$wishArray1['wishlist_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
						$wishArray1['wishlist_tracking_updated_date'] = 'NOW()';
						$wishArray1['wishlist_tracking_updated_by_id'] = $loggedInUserId;
						$wishArray1['wishlist_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];			
						//$wishArray1['wishlist_id'] = isset($checkedRecord[0]['wishlist_details_id']) ? $checkedRecord[0]['wishlist_id'] : 0;
						$wishArray1['wishlist_details_id'] = isset($checkedRecord[0]['wishlist_details_id']) ? $checkedRecord[0]['wishlist_details_id'] : 0;
						$insertRecordWishListTracking = $this->objProductWishlist->insertQuery($wishArray1, $wishTableName1, false);
						if($updateRecord)
						{
							$checkCountFlagTrueElse++;
							//$outResults['msg'] = 'success';
						} else {
							$checkCountFlagFalseElse++;
							//$outResults['msg'] = 'failed';
						}
					}
				}
				/*echo count($expProductIds)." ".$checkCountFlagTrue."<br>";
				echo count($expProductIds)." ".$checkCountFlagTrueElse."<br>";
				echo count($expProductIds)." ".($checkCountFlagTrue+$checkCountFlagTrueElse)."<br>";*/
				if(count($expProductIds)==$checkCountFlagTrue){
					$outResults['msg'] = 'success';
				}
				else if(count($expProductIds)==$checkCountFlagTrueElse){
					$outResults['msg'] = 'update';
				}
				else if(count($expProductIds)==($checkCountFlagTrue+$checkCountFlagTrueElse)){
					$outResults['msg'] = 'both';
				} else {
					$outResults['msg'] = 'failed';
				}
			}
			//echo "count un ".(count($expProductIdsUnChecked));
			if(count($expProductIdsUnChecked)>0){
				$unCheckCountFlagTrue = 0;
				$unCheckCountFlagFalse = 0;
				$unCheckCountFlagTrueElse = 0;
				$unCheckCountFlagFalseElse = 0;
				for($p=0; $p<count($expProductIdsUnChecked); $p++){
					$wArray['wishlist_id'] = $wishListIds;
					$wArray['user_id'] = $loggedInUserId;
					$wArray['pd_id'] = isset($expProductIdsUnChecked[$p]) ? $expProductIdsUnChecked[$p] : 0;
					$checkedRecord1 = $this->objProductWishlist->checkingWishListWithProduct($wArray);
					//echo "count ".count($checkedRecord1);
					/*if(count($checkedRecord)==0){				
						$wTableName1 = $this->usersDbName.".`wishlist_details`";
						$uArray = array();
						$uArray['wishlist_id'] = $wArray['wishlist_id'];
						$uArray['pd_id'] = $wArray['pd_id'];
						$uArray['wishlist_details_status'] = 1;
						$insertRecordWithGetId = $this->objProductWishlist->insertQuery($uArray, $wTableName1, true);
						
						$wishArray['wishlist_details_id'] = $insertRecordWithGetId;
						$insertRecordWishListTracking = $this->objProductWishlist->insertQuery($wishArray, $wishTableName, false);
						if($insertRecordWithGetId)
						{
							$outResults['msg'] = 'success';
						} else {
							$outResults['msg'] = 'failed';
						}
					} else {*/
					if(count($checkedRecord1)>0){
						//$outResults['msg'] = 'already';	
						$wArray = array();	
						$wArray['wishlist_details_status'] = 2;				
						$dArray = array();
						$dArray['wishlist_id'] = isset($checkedRecord1[0]['wishlist_id']) ? $checkedRecord1[0]['wishlist_id'] : 0;	
						$dArray['pd_id'] = isset($checkedRecord1[0]['pd_id']) ? $checkedRecord1[0]['pd_id'] : 0;
						$updateRecord = $this->objProductWishlist->updateRecordQuery($wArray, $this->usersDbName.".`wishlist_details`", $dArray);
						
						$wishTableName1 = $this->usersDbName.".wishlist_tracking" ;
						$wishArray1 = array();
						$wishArray1['wishlist_tracking_session_id'] = session_id();
						//$wishArray1['wishlist_tracking_created_date'] = 'NOW()';
						//$wishArray1['wishlist_tracking_created_by_id'] = $loggedInUserId;
						//$wishArray1['wishlist_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
						$wishArray1['wishlist_tracking_updated_date'] = 'NOW()';
						$wishArray1['wishlist_tracking_updated_by_id'] = $loggedInUserId;
						$wishArray1['wishlist_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];			
						//$wishArray1['wishlist_id'] = isset($checkedRecord[0]['wishlist_details_id']) ? $checkedRecord[0]['wishlist_id'] : 0;
						$wishArray1['wishlist_details_id'] = isset($checkedRecord1[0]['wishlist_details_id']) ? $checkedRecord1[0]['wishlist_details_id'] : 0;
						$insertRecordWishListTracking = $this->objProductWishlist->insertQuery($wishArray1, $wishTableName1, false);
						if($updateRecord)
						{
							$checkCountFlagTrueElse++;
						} else {
							$checkCountFlagFalseElse++;
						}
					}
				}
				/*echo count($expProductIds)." ".$unCheckCountFlagTrue."<br>";
				echo count($expProductIds)." ".$unCheckCountFlagTrueElse."<br>";
				echo count($expProductIds)." ".($unCheckCountFlagTrue+$checkCountFlagTrueElse)."<br>";*/
				if(count($expProductIds)==$unCheckCountFlagTrue){
					$outResults1['msg'] = 'success';
				}
				else if(count($expProductIds)==$unCheckCountFlagTrueElse){
					$outResults1['msg'] = 'update';
				}
				else if(count($expProductIds)==($unCheckCountFlagTrue+$checkCountFlagTrueElse)){
					$outResults1['msg'] = 'both';
				} else {
					$outResults1['msg'] = 'failed';
				}
			} 
			//echo count($expProductIds)."&&".count($expProductIdsUnChecked);
			if(count($expProductIds)==0 && count($expProductIdsUnChecked)==0) {
				$outResults['msg'] = 'failed';
			}
			if($outResults1['msg']=="failed" && $outResults['msg']=="failed"){
				$outResults['msg'] = "failed";
			} else if($outResults1['msg']=="success" && $outResults['msg']=="success"){
				$outResults['msg'] = "success";
			} else {
				$outResults['msg'] = "both";
			}
			//print_r($outResults);
			/*if(count($checkedRecord)==0){				
				$wTableName1 = $this->usersDbName.".`wishlist_details`";
				$uArray = array();
				$uArray['wishlist_id'] = $wArray['wishlist_id'];
				$uArray['pd_id'] = $productId;
				$uArray['wishlist_details_status'] = 1;
				$insertRecord2 = $this->objProductWishlist->insertQuery($uArray, $wTableName1, true);
				$insertRecordMyOfferRef = $this->objMyOffers->insertQuery($morArray, $morTableName, true);
				$mortArray['my_offers_ref_id'] = $insertRecordMyOfferRef;
				$mortArray['my_offers_id'] = $morArray['my_offers_id'];
				$insertRecordMyOffersTracking = $this->objMyOffers->insertQuery($mortArray, $mortTableName, false);
				if($insertRecord2)
				{
					$outResults['msg'] = 'success';
				} else {
					$outResults['msg'] = 'failed';
				}
			} else {
				//$outResults['msg'] = 'already';	
				$wArray = array();	
				$wArray['wishlist_details_status'] = 2;				
				$dArray = array();
				$dArray['wishlist_id'] = isset($checkedRecord[0]['wishlist_id']) ? $checkedRecord[0]['wishlist_id'] : 0;	
				$dArray['pd_id'] = isset($checkedRecord[0]['pd_id']) ? $checkedRecord[0]['pd_id'] : 0;
				$updateRecord = $this->objProductWishlist->updateRecordQuery($wArray, $this->usersDbName.".`wishlist_details`", $dArray);
				
			}*/
			//echo json_encode($outResults);
			
			if(count($outResults)>0){
				$xml = Array2XML::createXML('addedItemsToWishList', $outResults);
				echo $xml->saveXML();
			}
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 
	public function modDelSelectedWishList($loggedInUserId,$wishListName, $newConfigArrResult){
		try{
			global $config;
			$outResults = array();
			$updateRecord ="";
			//echo "wishlist".$wishListName;
			$wishListName = str_replace("%20"," ",$wishListName);
			$checkedRecord = $this->objProductWishlist->getUserWishListDelete($loggedInUserId, $wishListName);
			//print_r($checkedRecord);
			if(count($checkedRecord)>0){
				$j=0;		
				for($i=0;$i<count($checkedRecord);$i++){
					$con = array();
					$wdArray = array();	
					$con['wishlist_id'] = isset($checkedRecord[$i]['wishlist_id']) ? $checkedRecord[$i]['wishlist_id'] : 0;	
					$con['wishlist_details_id'] = isset($checkedRecord[$i]['wishlist_details_id']) ? $checkedRecord[$i]['wishlist_details_id'] : 0;
					if($con['wishlist_details_id'] !=0){
						$wdArray['wishlist_details_status'] = 2;
						$updateRecord = $this->objProductWishlist->updateRecordQuery($wdArray, $this->usersDbName.".`wishlist_details`", $con);
					}
					$j++;
				}
				if($j==count($checkedRecord)){
					$wlistTablName = $this->usersDbName.".`wishlist`";
					if($wishListName !=""){	
						$wArray = array();	
						$wArray['wishlist_status'] = 2;				
						$dArray = array();
						$dArray['wishlist_id'] = isset($checkedRecord[0]['wishlist_id']) ? $checkedRecord[0]['wishlist_id'] : 0;	
						$dArray['user_id'] = $loggedInUserId;
						$updateRecord = $this->objProductWishlist->updateRecordQuery($wArray, $wlistTablName, $dArray);
					}
				}
				if($updateRecord)
				{
					$outResults['msg'] = 'success';
				} else {
					$outResults['msg'] = 'failed';
				}
			} else {
				$outResults['msg'] = 'failed';
			}
			//echo json_encode($outResults);
			if(count($outResults)>0){
				$xml = Array2XML::createXML('deleteWishlistName', $outResults);
				echo $xml->saveXML();
			}
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShareWishList($loggedInUserId,$wishListName){
		try{
			global $config;
			$outResults = array();
			$pArray = array();
			$con = array();
			$updateRecord ="";
			
			$checkedRecord = $this->objProductWishlist->getUserWishListProducts($loggedInUserId,$wishListName);
			if(count($checkedRecord)>0){
				$shared = isset($checkedRecord[0]['shared']) ? $checkedRecord[0]['shared'] : 0;	
				if($shared == 0){
					$pArray['shared'] = 1;
				}else{
					$pArray['shared'] = 0;
				}
				$con['user_id']	= 	$loggedInUserId;
				$con['name']	= 	$wishListName;
				$updateRecord = $this->objProductWishlist->updateRecordQuery($pArray, $this->usersDbName.".`wishlist`",$con);
			}
			if($updateRecord)
			{				
				$outResults['msg'] = 'success';
				$outResults['share'] = $pArray['shared'];
			} else {
				$outResults['msg'] = 'failed';
			}
			echo json_encode($outResults);
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShareMultipleImagesByWishListName($loggedInUserId,$wishListName){
		try{
			global $config;
			$outResults = array();
			$pArray = array();
			$con = array();
			$updateRecord ="";
			$wishListName = str_replace("%20", " ", $wishListName);
			$outResults['prodWishList'] = $this->objProductWishlist->getUserAllWishListProducts($loggedInUserId,$wishListName);
			//print_r($outResults);
			for($i=0;$i<count($outResults['prodWishList']);$i++)
			{
				$clientId = isset($outResults['prodWishList'][$i]["client_id"]) ? $outResults['prodWishList'][$i]["client_id"] : 0;
				$image = isset($outResults['prodWishList'][$i]["pd_image"]) ? $outResults['prodWishList'][$i]["pd_image"] : "";
				if($clientId!=0){
					$outResults['prodWishList'][$i]["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
				}
			}
			//echo json_encode($outResults);
		if(count($outResults)>0){
			$xml = Array2XML::createXML('productsWishlist', $outResults);
			echo $xml->saveXML();
			}
			
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetFriendsWishListProducts($loggedInUserId,$wishListId){
		try{
			global $config;
			
			$outResults = array();			
			$outResults['prodWishList'] = $this->objProductWishlist->getFriendsWishListProducts($loggedInUserId,$wishListId);
			//print_r($outResults);
			for($i=0;$i<count($outResults['prodWishList']);$i++)
			{
				$clientId = isset($outResults['prodWishList'][$i]["client_id"]) ? $outResults['prodWishList'][$i]["client_id"] : 0;
				$image = isset($outResults['prodWishList'][$i]["image"]) ? $outResults['prodWishList'][$i]["image"] : "";
				$outResults['prodWishList'][$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
			}
			echo json_encode($outResults);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetFriendsWishListNames($loggedInUserId){
		try{
			global $config;
			$outResults = array();
			
 			$outResults['friendsRelatedWishListNames'] = $this->objProductWishlist->getFriendsRelatedWishListNames($loggedInUserId);
			echo json_encode($outResults);
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modDelSelectedYourCloset($loggedInUserId, $closetImgProdId){
		try{
			global $config;
			$outResults = array();
			$checkedRecord = $this->objProductWishlist->getYourClosetUserWishListProducts($loggedInUserId, $closetImgProdId);
			$uArray = array();
			$uArray['wishlist_id'] = isset($checkedRecord[0]['id']) ? $checkedRecord[0]['id'] : 0;	
			$uArray['id'] = isset($checkedRecord[$i]['item_id']) ? $checkedRecord[$i]['item_id'] : 0;
			if($uArray['id'] !=0){
				$delRecord = $this->objProductWishlist->delete($wTableName1, $uArray);
			}
			
 			//$outResults['friendsRelatedWishListNames'] = $this->objProductWishlist->getFriendsRelatedWishListNames($loggedInUserId);
			echo json_encode($outResults);
		} catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>