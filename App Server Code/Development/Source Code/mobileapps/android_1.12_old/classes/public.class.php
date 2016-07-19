<?php 
class cPublic{
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
			require_once(SRV_ROOT.'model/public.model.class.php');
			$this->objPublic = new mPublic();
			
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientWithProductDetailsWithXml($clientId, $productId){
		try{
			global $config;			
			if($productId != ""){
				$outArrClientProduct = array();
				$outClientProduct= array();
				$outArrClientProduct = $this->objPublic->getClientWithProductDetails($clientId, $productId);
				//print_r($outArrAllTriggers);
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
				 $outArrClientProduct[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrClientProduct[$i]["client_id"]."/products/".$outArrClientProduct[$i]["pdImage"];
				}
				if(count($outArrClientProduct)>0){	
					$outClientProduct['products'] = $outArrClientProduct;
				}
				//echo json_encode($outClientProduct);
				$xml = Array2XML::createXML('clientWithProductsDetails', $outClientProduct);
				echo $xml->saveXML();
			}else{
				echo "No Data Available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientAllProducts($pUrl){
		try{
			global $config;			
			$clientId = isset($pUrl[4])?$pUrl[4]:"";
			if($clientId != ""){
				$outArrClientAllProduct = array();
				$outClientProduct= array();
				$outArrClientAllProduct['product'] = $this->objPublic->getClientProducts($clientId);
				for($i=0;$i<count($outArrClientAllProduct);$i++)
				{
				 $outArrClientAllProduct[$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrClientAllProduct[$i]["client_id"]."/products/".$outArrClientAllProduct[$i]["pd_image"];
				}
			$outClientProduct['product'] = $outArrClientAllProduct;
				echo json_encode($outClientProduct);
				
			}else{
				echo "No Data Available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modClientProductWithXml($pUrl){
		try{
			global $config;			
			$clientId = isset($pUrl[3])?$pUrl[3]:"";
			$productId = isset($pUrl[4])?$pUrl[4]:"";
			if($clientId != ""){
				$outArrClientProduct = array();
				$outArrClientProduct = $this->objPublic->getClientProducts($clientId, $productId);
				$outClientProduct=array();
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
				 	$outArrClientProduct[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["pdImage"];
				}
				if(count($outArrClientProduct)>0){	
				$outClientProduct['products'] = $outArrClientProduct;
				}
				//echo json_encode($outClientProduct);
				$xml = Array2XML::createXML('clientProducts', $outClientProduct);
				echo $xml->saveXML();
			
			}else{
				echo "No Data Available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modRelatedProductsWithXml($pUrl){
		try{
			global $config;
			$clientId = isset($pUrl[3]) ? $pUrl[3] : 0;
			$productId = isset($pUrl[5]) ? $pUrl[5] : 0;
			$layoutType = isset($pUrl[6]) ? $pUrl[6] : "";
			if($clientId != ""){
				$outArrClientProduct = array();
				$outArrClientProduct = $this->objPublic->getClientWithRelatedProducts($clientId, $productId, $layoutType);
				//print_r($outArrClientProduct);
				$outClientProduct=array();
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
					if(isset($outArrClientProduct[$i]["offer_image"])){
				 		$outArrClientProduct[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["offer_image"];
					} 
					if(isset($outArrClientProduct[$i]["pdImage"])) {
				 		$outArrClientProduct[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["pdImage"];
					}
					if(isset($outArrClientProduct[$i]["clientId"]) && $outArrClientProduct[$i]["clientId"]=="") {
				 		$outArrClientProduct[$i]["clientId"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["offerClientId"];
					}					
					if(isset($outArrClientProduct[$i]["tapForDetailsImgs"])) {
						$arrForVideoPdImgs = json_decode($outArrClientProduct[$i]["tapForDetailsImgs"]);						
						for($f=0;$f<count($arrForVideoPdImgs);$f++){							
							if($arrForVideoPdImgs[$f]->video!=""){
				 				$outArrClientProduct[$i]["financialVideoPdImgs"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/additional/".$arrForVideoPdImgs[$f]->video;
							} else {
								$outArrClientProduct[$i]["financialVideoPdImgs"] = "";
							}
						}
					} else {
						$outArrClientProduct[$i]["financialVideoPdImgs"] = "";
					}
				}
				if(count($outArrClientProduct)>0){	
					$outClientProduct['products'] = $outArrClientProduct;
				}
				//echo json_encode($outClientProduct);
				$xml = Array2XML::createXML('clientRelatedProducts', $outClientProduct);
				echo $xml->saveXML();
			
			}else{
				echo "No Data Available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetTapForDetailsWithXml($loggedInUserId, $productId){
		try{		
			$outResults = array();		
			$outGetTapAllResults=array();
			$outGetTapResultsForImages = array();	
			
			$outGetTapAllResults = $this->objPublic->getTapForDetails($loggedInUserId, $productId);
			
			for($i=0;$i<count($outGetTapAllResults);$i++)
			{
				if(isset($outGetTapAllResults[$i]['tapForDetailsImgs'])){
					$imagesJson = (array) json_decode($outGetTapAllResults[$i]['tapForDetailsImgs']);
					if(count($imagesJson)>0){
						for($s=0; $s<count($imagesJson['additional_images']); $s++){
							$outGetTapResultsForImages[$s]['images'] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outGetTapAllResults[$i]["client_id"]."/additional/".$imagesJson['additional_images'][$s]->image;
						}
					}
				}
				$outGetTapAllResults[$i]['tapForDetailsImgs'] = $outGetTapResultsForImages;
			}
			if(count($outGetTapAllResults)>0){	
				$outResults['products'] = $outGetTapAllResults;  
			}
			//print_r($outResults);
			//echo json_encode($outResults);
			$xml = Array2XML::createXML('tapOnProductForDetails', $outResults);
			echo $xml->saveXML();
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAllTriggersWithXML(){
		try{
			global $config;			
			$outArrAllTriggers = array();		
			$outAllTriggers=array();
			$outArrAllModel = array();	
			$outArrAllVisuals = array();	
			$outArrAllTriggers = $this->objPublic->getAllTriggers_new();
			
			for($i=0;$i<count($outArrAllTriggers);$i++)
			{
				 $outArrAllTriggers[$i]["triggerUrl"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/triggers/".$outArrAllTriggers[$i]["triggerUrl"];
				if($outArrAllTriggers[$i]["pdImage"]==""){
					$outArrAllTriggers[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/products/".$outArrAllTriggers[$i]["offer_image"];
				}
				if($outArrAllTriggers[$i]["offer_image"]==""){
					$outArrAllTriggers[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/products/".$outArrAllTriggers[$i]["pdImage"];
				}
				if($outArrAllTriggers[$i]["discriminator"] == "VIDEO"){
				 	$outArrAllTriggers[$i]["visualUrl"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/videos/".$outArrAllTriggers[$i]["visualUrl"];
				}
				
				//$outArrAllVisuals = $this->objPublic->getTriggerVisuals($outArrAllTriggers[$i]["trigger_id"]);
				  
				$outArrAllModel = $this->objPublic->getTriggerModel($outArrAllTriggers[$i]["visualId"]);
				for($j=0;$j<count($outArrAllModel);$j++)
				{
					if($outArrAllModel[$j]['model'] != ""){
						$outArrAllModel[$j]['model']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/models/".$outArrAllModel[$j]['model'];
					}
					if(	$outArrAllModel[$j]['texture'] != ""){
						$outArrAllModel[$j]['texture']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/models/".$outArrAllModel[$j]['texture'];
					}
					if(	$outArrAllModel[$j]['material'] != ""){
						$outArrAllModel[$j]['material']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/models/".$outArrAllModel[$j]['material'];
					}
				}
				
				//$outArrAllTriggers[$i]['Visuals'] = $outArrAllVisuals;
				$outArrAllTriggers[$i]['Model'] = $outArrAllModel;
			}
			if(count($outArrAllTriggers)>0){	
				$outAllTriggers['trigger'] = $outArrAllTriggers;  
			}
			//print_r($outAllTriggers);
			$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
			echo $xml->saveXML();
			//echo json_encode($outAllTriggers);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		
	public function modAllTriggerModelsWithXML($triggerId, $clientId, $threeModelId){
		try{
			global $config;			
			$outArrAllModel = array();	
			$outArrModelForXml = array();
			
			$outArrAllModel = $this->objPublic->getTriggerModel($threeModelId);
			for($j=0;$j<count($outArrAllModel);$j++)
			{
				if(	isset($outArrAllModel[$j]['model']) && $outArrAllModel[$j]['model'] != ""){
					$outArrAllModel[$j]['model']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/models/".$outArrAllModel[$j]['model'];
				}
				if(	isset($outArrAllModel[$j]['texture']) && $outArrAllModel[$j]['texture'] != ""){
					$outArrAllModel[$j]['texture']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/models/".$outArrAllModel[$j]['texture'];
				}
				if(	isset($outArrAllModel[$j]['material']) && $outArrAllModel[$j]['material'] != ""){
					$outArrAllModel[$j]['material']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/models/".$outArrAllModel[$j]['material'];
				}			
			}
			//print_r($outArrAllModel);
			if(count($outArrAllModel)>0){	
				$outArrModelForXml['Model']=$outArrAllModel;
			}
			$xml = Array2XML::createXML('triggerAll', $outArrModelForXml);
			echo $xml->saveXML();
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientGroupTriggersWithXML(){
		try{
			global $config;			
			$outArrAllTriggers = array();		
			$outAllTriggers=array();
			$outArrAllModel = array();	
			$outArrAllTriggers = $this->objPublic->getClientGroupTriggers();
			
			for($i=0;$i<count($outArrAllTriggers);$i++)
			{
				 $outArrAllTriggers[$i]["clientImage"] = "http://".$_SERVER['HTTP_HOST']."/files/all_clients_markers/".$outArrAllTriggers[$i]["clientImage"];
				 
					$outAllTriggers['trigger'] = $outArrAllTriggers;  
			}
			$xml = Array2XML::createXML('clientTriggers', $outAllTriggers);
			echo $xml->saveXML();
		   // echo json_encode($outAllTriggers);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggersWithXML($pUrl, $clientIds){
		try{
			global $config;			
			$outArrAllTriggers = array();		
			$outAllTriggers=array();
			$outArrAllModel = array();	
			$outArrAllTriggers = $this->objPublic->getClientAllTriggersForXML($clientIds);
			
			for($i=0;$i<count($outArrAllTriggers);$i++)
			{
				 $outArrAllTriggers[$i]["triggerUrl"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/triggers/".$outArrAllTriggers[$i]["triggerUrl"];
				if($outArrAllTriggers[$i]["pdImage"]==""){
					$outArrAllTriggers[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/products/".$outArrAllTriggers[$i]["offer_image"];
				}
				if($outArrAllTriggers[$i]["offer_image"]==""){
					$outArrAllTriggers[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/products/".$outArrAllTriggers[$i]["pdImage"];
				}
				  if($outArrAllTriggers[$i]["discriminator"] == "VIDEO"){
					  $outArrAllTriggers[$i]["visualUrl"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/videos/".$outArrAllTriggers[$i]["visualUrl"];
				  }
				  
				  	$outArrAllModel = $this->objPublic->getTriggerModel($outArrAllTriggers[$i]["visualId"]);
					for($j=0;$j<count($outArrAllModel);$j++)
					{
						if(	$outArrAllModel[$j]['model'] != ""){
							$outArrAllModel[$j]['model']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/models/".$outArrAllModel[$j]['model'];
						}
						if(	$outArrAllModel[$j]['texture'] != ""){
							$outArrAllModel[$j]['texture']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/models/".$outArrAllModel[$j]['texture'];
						}
						if(	$outArrAllModel[$j]['material'] != ""){
							$outArrAllModel[$j]['material']= "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/models/".$outArrAllModel[$j]['material'];
						}else{
							$outArrAllModel[$j]['material'] = "null";
						}
					}
					
					$outArrAllTriggers[$i]['Model']=$outArrAllModel;
			}
			if(count($outArrAllTriggers)>0){	
			$outAllTriggers['trigger'] = $outArrAllTriggers;  
			}
			$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	 public function modUserDetails($userId){
		try{
			global $config;
			$outResults = array();
			$vArray = array();
			//print_r($pArray);
			$outResults['resultXml'] = $this->objPublic->getUserDetails($userId);
			//echo json_encode($outResults);
			
			$xml = Array2XML::createXML('rootUser', $outResults);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
public function modUpdateUsersDetails($pUrl){
		try{
			global $config;
			$outResults = array();
			$dArray = array();
			$pArray = array();			
			$userId =  isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
			
			
			if (isset($_REQUEST['first_name'])){
				$pArray['user_firstname'] =  $_REQUEST['first_name'];
			}
			if (isset($_REQUEST['last_name'])){
				$pArray['user_lastname'] =  $_REQUEST['last_name'];
			}
			if (isset($_REQUEST['email'])){
				$pArray['user_email_id'] = $_REQUEST['email'];
			}
			if (isset($_REQUEST['password'])){
				$pArray['user_password'] =  md5($_REQUEST['password']);
			}
			if (isset($_REQUEST['user_details_avatar'])){
				$dArray['user_details_avatar'] = $_REQUEST['user_details_avatar'];
			}
	
			if (isset($_REQUEST['user_details_nickname'])){
				$dArray['user_details_nickname'] =  $_REQUEST['user_details_nickname'];
			}
			if (isset($_REQUEST['user_details_phone'])){
				$dArray['user_details_phone'] =  $_REQUEST['user_details_phone'];
			}
			if (isset($_REQUEST['user_details_address1'])){
				$dArray['user_details_address1'] =  $_REQUEST['user_details_address1'];
			}
			if (isset($_REQUEST['user_details_address2'])){
				$dArray['user_details_address2'] = $_REQUEST['user_details_address2'];
			}
			if (isset($_REQUEST['user_details_city'])){
				$dArray['user_details_city'] = $_REQUEST['user_details_city'];
			}
			if (isset($_REQUEST['user_details_state'])){
				$dArray['user_details_state'] = $_REQUEST['user_details_state'];
			}
			if (isset($_REQUEST['user_details_country'])){
				$dArray['user_details_country'] = $_REQUEST['user_details_country'];
			}
			if (isset($_REQUEST['user_details_zip'])){
				$dArray['user_details_zip'] = $_REQUEST['user_details_zip'];
			}
			if (isset($_REQUEST['user_details_gender'])){
				$dArray['user_details_gender'] = $_REQUEST['user_details_gender'];
			}
			if (isset($_REQUEST['user_details_dob'])){
				$dArray['user_details_dob'] = $_REQUEST['user_details_dob'];
				if($dArray['user_details_dob'] !=''){
					if (strpos($_REQUEST['user_details_dob'],'/') !== false)    
						$date = str_replace('/', '-', $_REQUEST['user_details_dob']);
					else if(strpos($_REQUEST['user_details_dob'],'.') !== false)
						$date = str_replace('.', '-', $_REQUEST['user_details_dob']);				
					else	
						$date = $_REQUEST['user_details_dob'];
						
						$dArray['user_details_dob'] = date("Y-m-d", strtotime($date)); 
				}
				
			}
			$dArray['user_details_lastlogin'] = 'NOW()';
			
				
				if ($userId > 0){
				$arrUser = $this->objPublic->getUserDetails($userId);
				if (count($arrUser) > 0){
					if (count($pArray)>0){
						$con = array();
						$con['user_id'] = $userId;	
						$con['user_status'] = 1;	
						if($con['user_id'] !=0){
							$updateRecord = $this->objPublic->updateRecordQuery($pArray, "users", $con);
							if ($updateRecord){
								$outArrUser['msg']='success';
							}else{
								$outArrUser['msg']='fail';
							}
						}
					}
					if (count($dArray)>0){
							$con = array();
							$con['user_id'] = $userId;		
							if($con['user_id'] !=0){
								$updateRecord = $this->objPublic->updateRecordQuery($dArray, "user_details", $con);
								if ($updateRecord){
									$outResults['resultXml']['msg'] = 'success';
										$utArray = array();
										$utArray['user_details_id'] = $arrUser[0]['user_details_id'];
										$utArray['user_tracking_session_id'] = session_id();
										$utArray['user_tracking_created_date'] = 'NOW()';
										$utArray['user_tracking_created_by_id'] = $con['user_id'];
										$utArray['user_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
										$utArray['user_tracking_updated_date'] = 'NOW()';
										$utArray['user_tracking_updated_by_id'] = $con['user_id'];
										$utArray['user_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
										$pUserTrackingTableName = "user_tracking";	
										$insertUserTracking = $this->objPublic->insertQuery($utArray, $pUserTrackingTableName, true);			
								}else{
									$outResults['resultXml']['msg'] = 'fail';
								}
							}
						}
					
				}else{
					$outResults['resultXml']['msg'] = 'fail';
				}
				
				
				}else{
					$outResults['resultXml']['msg'] = 'fail';
				}
			
			/* $insertedUsersDetailsResultId  = isset($_REQUEST['user_details_id']) ? $_REQUEST['user_details_id'] : '';
				$pTableName = "users";
				$updateUsersResults = $this->objPublic->updateRecordQuery($pArray, $pTableName, $con);
				if($updateUsersResults){
				$pUserDetailsTableName = "user_details";
				 $updateUserDetails= $this->objPublic->updateRecordQuery($dArray, $pUserDetailsTableName, $con);
				}
				
					if($updateUserDetails){
						$outResults['resultXml']['msg'] = 'success';
					} else {
						$outResults['resultXml']['msg'] = 'fail';
					}
*/
			$xml = Array2XML::createXML('rootReg', $outResults);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	 public function modClientStore($pUrl,$clientIds){
		try{
			global $config;
			$outResults = array();
			$vArray = array();
			//print_r($pArray);
			$outResults['resultXml'] = $this->objPublic->getClientStores($clientIds);
			//echo json_encode($outResults);
			
			$xml = Array2XML::createXML('rootUser', $outResults);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modTryonRelatedProductsWithXml($pUrl){
		try{
			global $config;
			$clientId = isset($pUrl[3]) ? $pUrl[3] : 0;
			$productId = isset($pUrl[5]) ? $pUrl[5] : 0;
			if($clientId != ""){
				$outArrClientProduct = array();
				$outArrClientProduct = $this->objPublic->getClientWithTryonRelatedProducts($clientId, $productId);
				$outClientProduct=array();
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
				 	$outArrClientProduct[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["pdImage"];
				}
			if(count($outArrClientProduct)>0){	
				$outClientProduct['products'] = $outArrClientProduct;
			}
				//echo json_encode($outClientProduct);
				$xml = Array2XML::createXML('clientRelatedProducts', $outClientProduct);
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