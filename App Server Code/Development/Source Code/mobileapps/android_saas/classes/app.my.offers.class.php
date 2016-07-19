<?php 
class cMyOffers{
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
			require_once(SRV_ROOT.'model/app.myoffers.model.class.php');
			$this->objMyOffers = new mMyOffers();
			
			require_once SRV_ROOT.'classes/public.class.php';
			$this->objPublic = new cPublic();
		
			require_once SRV_ROOT.'classes/app.saas.clientdb.class.php';
			$this->objAppSassClientDbs = new cAppSaasClientDbs();
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			//require_once(SRV_ROOT.'classes/Array2XML.php');
			
			$this->mainDbName = $config['database']['name'];
			$this->markersDbName = $config['database']['name_markers'];
			$this->usersDbName = $config['database']['name_users'];
			$this->userAnalyticsDbName = $config['database']['name_user_analytics'];
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetClientOffers($clientId, $checkFlag, $loggedInUserId, $offerId){
		try{
			global $config;	
			
			$outArrClientOffer = array();
			$outClientOffer= array();
			$outInsertResults = array();
			$outArrClientOffer = $this->objMyOffers->getClientWithOffersAndInfoDetails($clientId, $loggedInUserId, $offerId);
			//print_r($outArrAllTriggers);
			for($i=0;$i<count($outArrClientOffer);$i++)
			{
				$outArrClientOffer[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrClientOffer[$i]["client_id"]."/products/".$outArrClientOffer[$i]["offer_image"];
				if(isset($outArrUserOffers[$i]["offer_barcode_image"]) && $outArrUserOffers[$i]["offer_barcode_image"]!=""){
					$outArrUserOffers[$i]["offer_barcode_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrUserOffers[$i]["client_id"]."/products/".$outArrUserOffers[$i]["offer_barcode_image"];
				}
			}
			if($checkFlag=="Yes"){
				$expOfferIds = explode(",", $offerId);
				for($s=0; $s<count($expOfferIds); $s++){
					$outArrClientOffer[$s]['insertMsg'] = $this->modInsertMyOffersWithUserId($loggedInUserId, $expOfferIds[$s], $checkFlag);
				}
			}
			//print_r($outInsertResults);
			if(count($outArrClientOffer)>0){
				$outClientOffer['clientOffers'] = $outArrClientOffer;
			}
			//$outClientOffer['insertMsg'] = $outInsertResults;
			//print_r($outClientOffer);
			//echo json_encode($outClientProduct);
			$xml = Array2XML::createXML('rootOffers', $outClientOffer);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modInsertMyOffersWithUserId($loggedInUserId, $offerId, $checkFlag){
		try{
			global $config;	
			$outResults = array();
			$outArrUserOffers = array();
			$mTableName = "my_offers";
			$mArray = array();
			$mArray['my_offers_name'] = "My Offers";
			$mArray['user_id'] = $loggedInUserId;
			$mArray['my_offers_created_date'] = 'NOW()';
			$mArray['my_offers_created_by_id'] = $loggedInUserId;
			$mArray['my_offers_status'] = 1;
			
			$morTableName = "my_offers_reference";
			$morArray = array();
			$morArray['offer_id'] = $offerId;
			$morArray['coupon_id'] = 0;
			$morArray['my_offers_tracking_id'] = 1;
			$morArray['my_offers_ref_created_date'] = 'NOW()';
			$morArray['my_offers_ref_created_by_id'] = $loggedInUserId;
			$morArray['my_offers_ref_status'] = 1;
			
			$mortTableName = $this->usersDbName.".my_offers_tracking" ;
			$mortArray = array();
			$mortArray['my_offers_tracking_session_id'] = session_id();
			$mortArray['my_offers_tracking_created_date'] = 'NOW()';
			$mortArray['my_offers_tracking_created_by_id'] = $loggedInUserId;
			$mortArray['my_offers_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
			$mortArray['my_offers_tracking_updated_date'] = 'NOW()';
			$mortArray['my_offers_tracking_updated_by_id'] = $loggedInUserId;
			$mortArray['my_offers_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
			
 			$checkedRecord = $this->objMyOffers->checkingMyOffersName($mArray);
 
			if(count($checkedRecord) > 0){
				$morArray['my_offers_id'] = isset($checkedRecord[0]['my_offers_id']) ? $checkedRecord[0]['my_offers_id'] : 0;
 				$checkedRecordRef = $this->objMyOffers->checkingMyOffersReferences($morArray);
				if(count($checkedRecordRef)>0){
					if(isset($checkedRecordRef[0]['my_offers_ref_status']) && $checkedRecordRef[0]['my_offers_ref_status']==2){
						$conArr = array();
						$conArr['my_offers_id'] =isset($checkedRecordRef[0]['my_offers_id']) ? $checkedRecordRef[0]['my_offers_id'] : 0;
						$conArr['offer_id'] =isset($checkedRecordRef[0]['offer_id']) ? $checkedRecordRef[0]['offer_id'] : 0;
						$conArr['my_offers_ref_id'] =isset($checkedRecordRef[0]['my_offers_ref_id']) ? $checkedRecordRef[0]['my_offers_ref_id'] : 0;
						$updateArr = array();
						$updateArr['my_offers_ref_status'] = 1;
						$updateRecord = $this->objMyOffers->updateRecordQuery($updateArr, $morTableName, $conArr);
						
						$mortArray['my_offers_ref_id'] = $conArr['my_offers_ref_id'];
						$mortArray['my_offers_id'] = $conArr['my_offers_id'];
						$insertRecordMyOffersTracking = $this->objMyOffers->insertQuery($mortArray, $mortTableName, false);
					}
					$outResults['msg'] = 'already';
					
					$outArrUserOffers = $this->objMyOffers->getClientWithOfferDetailsWithUserIdOrOfferID($loggedInUserId,$offerId ,'');
					
					for($i=0;$i<count($outArrUserOffers);$i++)
					{
				 	$outArrUserOffers[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrUserOffers[$i]['client_id']."/products/".$outArrUserOffers[$i]["offer_image"];
					
					}
					//print_r($outArrUserOffers);
					$outResults['myOffers'] = $outArrUserOffers;
				} else {
					$insertRecordMyOfferRef = $this->objMyOffers->insertQuery($morArray, $morTableName, true);
					$mortArray['my_offers_ref_id'] = $insertRecordMyOfferRef;
					$mortArray['my_offers_id'] = $morArray['my_offers_id'];
					$insertRecordMyOffersTracking = $this->objMyOffers->insertQuery($mortArray, $mortTableName, false);
					if ($insertRecordMyOfferRef){
						$outResults['msg'] = 'success';
					} else {
						$outResults['msg'] = 'failed';
					}
				}
			} else if(count($checkedRecord)==0 && $mArray['my_offers_name']!=""){
				$myOffersInsertId = $this->objMyOffers->insertQuery($mArray, $mTableName, true);
				if($myOffersInsertId){
					$morArray['my_offers_id'] = $myOffersInsertId;
					$insertRecordMyOffersRef1 = $this->objMyOffers->insertQuery($morArray, $morTableName, true);
					$mortArray['my_offers_id'] = $myOffersInsertId;					
					$mortArray['my_offers_ref_id'] = $insertRecordMyOffersRef1;
					$insertRecordMyOffersTracking = $this->objMyOffers->insertQuery($mortArray, $mortTableName, false);
					if ($insertRecordMyOffersRef1){
						$outResults['msg'] = 'success';						
						
						$outArrUserOffers = $this->objMyOffers->getClientWithOfferDetailsWithUserIdOrOfferID($loggedInUserId,$morArray['my_offers_id'] ,'OfferViewMyOffers');
						
					for($i=0;$i<count($outArrUserOffers);$i++)
					{
				 	$outArrUserOffers[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrUserOffers[$i]['client_id']."/products/".$outArrUserOffers[$i]["offer_image"];
					}
						
					if(count($outArrUserOffers)>0){						
						$outResults['myOffers'] = $outArrUserOffers;
					}
					} else {
						$outResults['msg'] = 'failed';
					}
				} else {
					$outResults['msg'] = 'failed';
				}
			} else {
				$outResults['msg'] = 'failed';
			}			
			if($checkFlag=="Yes"){
				return $outResults['msg'];
			} else {
				//echo json_encode($outResults); 
				$xml = Array2XML::createXML('rootMyOffers', $outResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetMyOffersList($loggedInUserId, $offerId, $flag, $newConfigArrResult){
		try{
			global $config;	
			$outResults = array();
			$outArrUserOffers = array();
			
			if(count($newConfigArrResult['db_name'])>0){
				for($i=0; $i<count($newConfigArrResult['db_name']); $i++){
					if(isset($newConfigArrResult['db_name'][$i]) && $newConfigArrResult['db_name'][$i]!=""){
						//echo $newConfigArrResult['db_name'][$i]."<br>";
						$config['database']['name_clients'] = (isset($newConfigArrResult['db_name'][$i]) ? $newConfigArrResult['db_name'][$i] : '');
						$config['database']['host_clients'] = isset($newConfigArrResult['db_host'][$i]) ? $newConfigArrResult['db_host'][$i] : '';
						$config['database']['user_clients'] = isset($newConfigArrResult['db_username'][$i]) ? $newConfigArrResult['db_username'][$i] : '';
						$config['database']['password_clients'] = isset($newConfigArrResult['db_password'][$i]) ? $newConfigArrResult['db_password'][$i] : '';
						$outResults[$config['database']['name_clients']] = $this->objMyOffers->getClientWithOfferDetailsWithUserIdOrOfferID($loggedInUserId, $offerId, $flag);
					}
				}
			}
			foreach($outResults as $key => $value){
				foreach($value as $key1 => $value1){
					$value1["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$value1["client_id"]."/products/".$value1["offer_image"];
					$value1["offer_valid_from"] = date("m/d/Y", strtotime($value1["offer_valid_from"]));
					$value1["offer_valid_to"] = date("m/d/Y", strtotime($value1["offer_valid_to"]));
					if(isset($value1["offer_barcode_image"]) && $value1["offer_barcode_image"]!=""){
						$value1["offer_barcode_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$value1["client_id"]."/products/".$value1["offer_barcode_image"];
					}
					$finalOutResults[] = $value1;
				}
			}
			$arrOutResults['myOffers'] = $finalOutResults;
			
			/*$outArrUserOffers = $this->objMyOffers->getClientWithOfferDetailsWithUserIdOrOfferID($loggedInUserId, $offerId, $flag);
			for($i=0;$i<count($outArrUserOffers);$i++)
			{
				$outArrUserOffers[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrUserOffers[$i]["client_id"]."/products/".$outArrUserOffers[$i]["offer_image"];
				$outArrUserOffers[$i]["offer_valid_from"] = date("m/d/Y", strtotime($outArrUserOffers[$i]["offer_valid_from"]));
				$outArrUserOffers[$i]["offer_valid_to"] = date("m/d/Y", strtotime($outArrUserOffers[$i]["offer_valid_to"]));
				if(isset($outArrUserOffers[$i]["offer_barcode_image"]) && $outArrUserOffers[$i]["offer_barcode_image"]!=""){
					$outArrUserOffers[$i]["offer_barcode_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrUserOffers[$i]["client_id"]."/products/".$outArrUserOffers[$i]["offer_barcode_image"];
				}
			}
			if(count($outArrUserOffers)>0){	
				$outResults['myOffers'] = $outArrUserOffers;
			}*/
			if(count($arrOutResults['myOffers'])>0){
				$xml = Array2XML::createXML('rootMyOffers', $arrOutResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modDeleteMyOffersWithUserId($loggedInUserId, $checkFlag){
		try{
			global $config;	
			$outResults = array();
			
 			$arrGetMyOfferId = $this->objMyOffers->getMyOffersIdByUserId($loggedInUserId);
			$myOfferId = isset($arrGetMyOfferId[0]['my_offers_id']) ? $arrGetMyOfferId[0]['my_offers_id'] : 0;	
			$morTableName = "my_offers_reference";
			$morConArr = array();
			$morArray = array();
			$morConArr['my_offers_id'] = $myOfferId;
			$morArray['my_offers_ref_status'] = 2;
			$updateRecord = $this->objMyOffers->updateRecordQuery($morArray, $morTableName, $morConArr);
			if($updateRecord){
				$mortTableName = $this->usersDbName.".my_offers_tracking" ;
				$mortArray['my_offers_id'] = $myOfferId;
				$mortArray['my_offers_tracking_session_id'] = session_id();
				$mortArray['my_offers_tracking_updated_date'] = 'NOW()';
				$mortArray['my_offers_tracking_updated_by_id'] = $loggedInUserId;
				$mortArray['my_offers_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
				$insertRecordMyOffersTracking = $this->objMyOffers->insertQuery($mortArray, $mortTableName, false);
			}
		
			if ($updateRecord){
				$outResults['msg'] = 'success';
			} else {
				$outResults['msg'] = 'failed';
			}
			$xml = Array2XML::createXML('rootMyOffers', $outResults);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modRelatedOffersWithXml($pUrl){
		try{
			global $config;
			$clientId = isset($pUrl[4]) ? $pUrl[4] : 0;
			$offerId = isset($pUrl[5]) ? $pUrl[5] : 0;
			$layoutType = isset($pUrl[6]) ? $pUrl[6] : "";
			if($clientId != ""){
				$this->objAppSassClientDbs->modGetClientDbConnectionsBasedOnClientId($clientId);
				$outArrClientOffer = array();
				$outArrClientOffer = $this->objMyOffers->getClientWithRelatedOffers($clientId, $offerId, $layoutType);
				$outClientOffer=array();
				for($i=0;$i<count($outArrClientOffer);$i++)
				{
					if(isset($outArrClientOffer[$i]["pdImage"])) {
				 		$outArrClientOffer[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientOffer[$i]["pdImage"];
					}
					if(isset($outArrClientOffer[$i]["offer_image"])){
				 		$outArrClientOffer[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientOffer[$i]["offer_image"];
					} 					
					if(isset($outArrClientOffer[$i]["tapForDetailsImgs"])) {
						$arrForVideoPdImgs = json_decode($outArrClientOffer[$i]["tapForDetailsImgs"]);						
						for($f=0;$f<count($arrForVideoPdImgs);$f++){							
							if($arrForVideoPdImgs[$f]->video!=""){
				 				$outArrClientOffer[$i]["financialVideoPdImgs"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/additional/".$arrForVideoPdImgs[$f]->video;
							} else {
								$outArrClientOffer[$i]["financialVideoPdImgs"] = "";
							}
						}
					} else {
						$outArrClientOffer[$i]["financialVideoPdImgs"] = "";
					}
				}
				if(count($outArrClientOffer)>0){
					$outClientOffer['products'] = $outArrClientOffer;
				}
				//echo json_encode($outClientProduct);
				$xml = Array2XML::createXML('clientRelatedProducts', $outClientOffer);
				echo $xml->saveXML();
			
			}else{
				echo "No Data Available";
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