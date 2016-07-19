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
	public function modClientWithProductDetailsWithXml($clientId, $productId,$fileType){
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
				$outClientProduct['products'] = $outArrClientProduct;
				if($fileType=="json")
			    {
			       echo json_encode($outClientProduct);
			    }
			    else if($fileType=="xml")
			    {
			       $xml = Array2XML::createXML('clientWithProductsDetails', $outClientProduct);
			       echo $xml->saveXML();
			    }
			    else
			    {
			       print_r($outClientProduct);
			    }
				
				
				//echo json_encode($outClientProduct);
				/*$xml = Array2XML::createXML('clientWithProductsDetails', $outClientProduct);
				echo $xml->saveXML();*/
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
			$clientId = isset($pUrl[4])?$pUrl[4]:"";
			$fileType = isset($pUrl[5])?$pUrl[5]:"";
			if($clientId != ""){
				$outArrClientProduct = array();
				$outArrClientProduct = $this->objPublic->getClientProducts($clientId);
				$outClientProduct=array();
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
				 	$outArrClientProduct[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["pdImage"];
				}
				$outClientProduct['products'] = $outArrClientProduct;
				
				if($fileType=="json")
			    {
			      echo json_encode($outClientProduct);
			    }
			      else if($fileType=="xml")
			    {
			      $xml = Array2XML::createXML('clientProducts', $outClientProduct);
			      echo $xml->saveXML();
			    }
			    else
			    {
			       print_r($outClientProduct);
			    }
				//echo json_encode($outClientProduct);
				/*$xml = Array2XML::createXML('clientProducts', $outClientProduct);
				echo $xml->saveXML();*/
			
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
			$clientId = isset($pUrl[4]) ? $pUrl[4] : 0;
			$productId = isset($pUrl[6]) ? $pUrl[6] : 0;
			$fileType=isset($pUrl[7]) ? $pUrl[7] : 0;
			if($clientId != ""){
				$outArrClientProduct = array();
				$outArrClientProduct = $this->objPublic->getClientWithRelatedProducts($clientId, $productId);
				$outClientProduct=array();
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
				 	$outArrClientProduct[$i]["pdImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$outArrClientProduct[$i]["pdImage"];
				}
				$outClientProduct['products'] = $outArrClientProduct;
				//echo json_encode($outClientProduct);
				if($fileType=="json")
			    {
			      echo json_encode($outClientProduct);
			    }
			    else if($fileType=="xml")
			    {
			      $xml = Array2XML::createXML('clientRelatedProducts', $outClientProduct);
			      echo $xml->saveXML();
			    }
			    else
			    {
			      print_r($outClientProduct);
			    }
				
				/*$xml = Array2XML::createXML('clientRelatedProducts', $outClientProduct);
				echo $xml->saveXML();*/
			
			}else{
				echo "No Data Available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetTapForDetailsWithXml($loggedInUserId, $productId,$fileType){
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
			$outResults['products'] = $outGetTapAllResults;  
			if($fileType=="json")
			{
			   echo json_encode($outResults);
			}
			else if($fileType=="xml")
			{
			   $xml = Array2XML::createXML('tapOnProductForDetails', $outResults);
			   echo $xml->saveXML();
			}
			else
			{
			    print_r($outResults);
			}
			
			
			//print_r($outResults);
			//echo json_encode($outResults);
			// $xml = Array2XML::createXML('tapOnProductForDetails', $outResults);
             // echo $xml->saveXML();
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modGetClientProductOffers($clientId, $productId){
		try{
			global $config;	
			if($productId != ""){
				$outArrClientProduct = array();
				$outClientProduct= array();
				$outArrClientProduct = $this->objPublic->getClientWithProductOfferDetails($clientId, $productId);
				//print_r($outArrAllTriggers);
				for($i=0;$i<count($outArrClientProduct);$i++)
				{
				 $outArrClientProduct[$i]["offer_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrClientProduct[$i]["client_id"]."/products/".$outArrClientProduct[$i]["offer_image"];
				}
			$outClientProduct['product'] = $outArrClientProduct;
				echo json_encode($outClientProduct);
			}else{
				echo "No Data Available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modClosetProducts($arrproductId,$fileType){
		try{
			$outResults = array();
			$arroutResults = array();
			$outResults = $this->objPublic->getClosetProductsValues($arrproductId);
			for($i=0;$i<count($outResults);$i++)
			{
				$clientId = isset($outResults[$i]["client_id"]) ? $outResults[$i]["client_id"] : 0;
				$image = isset($outResults[$i]["pd_image"]) ? $outResults[$i]["pd_image"] : "";
				$outResults[$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;
			}
			$arroutResults['prodCloset'] =$outResults;
			if($fileType=="json")
			{
			   echo json_encode($arroutResults);
			}
			else if($fileType=="xml")
			{
			   //$xml = Array2XML::createXML('triggerAll', $arroutResults);
			   //echo $xml->saveXML();
			}
			else
			{
			    print_r($arroutResults);
			}
			
			//echo json_encode($arroutResults);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		
	public function modAllTriggersWithXML($clientID){
		try{
			//echo "modAllTriggersWithXML".$clientID;
			$fileType = '';
			global $config;			
			$outArrAllTriggers = array();		
			$outAllTriggers=array();
			$outArrAllModel = array();	
			$arrAllTriggers = array();
			$arrClients = array();
			$outArrAllTriggers = array();
			$arrVisualsByTriggerID = array();
			$arrClientDetailsById = array();
			array_push($arrClients,$clientID);
			$filePath = "http://".$_SERVER['HTTP_HOST']."/files/clients/";
			
			$arrAllTriggers = $this->objPublic->getClientTriggers($arrClients);
			$arrClientDetailsById = $this->objPublic->getClientById($clientID);
			
			for($i=0;$i<count($arrAllTriggers);$i++){

				$outArrAllTriggers[$i]['triggerId'] = isset($arrAllTriggers[$i]['id']) ? $arrAllTriggers[$i]['id'] : 0;
				$outArrAllTriggers[$i]['triggerURL'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['triggers']).$arrAllTriggers[$i]['url'];
				$outArrAllTriggers[$i]['client_id'] = isset($arrAllTriggers[$i]['client_id']) ? $arrAllTriggers[$i]['client_id'] : 0;
				$outArrAllTriggers[$i]['clientName'] = isset($arrClientDetailsById[0]['name']) ? $arrClientDetailsById[0]['name'] : '';
				$outArrAllTriggers[$i]['triggerTitle'] = isset($arrAllTriggers[$i]['title']) ? $arrAllTriggers[$i]['title'] : '';
				$outArrAllTriggers[$i]['triggerHeight'] = isset($arrAllTriggers[$i]['height']) ? $arrAllTriggers[$i]['height'] : 0;
				$outArrAllTriggers[$i]['triggerWidth'] = isset($arrAllTriggers[$i]['width']) ? $arrAllTriggers[$i]['width'] : 0;
				$outArrAllTriggers[$i]['instruction'] = isset($arrAllTriggers[$i]['instruction']) ? $arrAllTriggers[$i]['instruction'] : '';
				$outArrAllTriggers[$i]['triggerByVertical'] = isset($arrAllTriggers[$i]['trigger_by_vertical']) ? $arrAllTriggers[$i]['trigger_by_vertical'] : 0;
				$outArrAllTriggers[$i]['active'] = $arrAllTriggers[$i]['active'];
				$arrVisualsByTriggerID = $this->objPublic->getVisualsByTriggerID($arrAllTriggers[$i]['id']);
				for($j=0;$j<count($arrVisualsByTriggerID);$j++){
					$outArrAllTriggers[$i]['visual'][$j]['visualID'] = isset($arrVisualsByTriggerID[$j]['id']) ? $arrVisualsByTriggerID[$j]['id'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['discriminator'] = isset($arrVisualsByTriggerID[$j]['discriminator']) ? $arrVisualsByTriggerID[$j]['discriminator'] : '';
					$outArrAllTriggers[$i]['visual'][$j]['x'] = isset($arrVisualsByTriggerID[$j]['x']) ? $arrVisualsByTriggerID[$j]['x'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['y'] = isset($arrVisualsByTriggerID[$j]['y']) ? $arrVisualsByTriggerID[$j]['y'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['product_id'] = isset($arrVisualsByTriggerID[$j]['product_id']) ? $arrVisualsByTriggerID[$j]['product_id'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['offer_id'] = isset($arrVisualsByTriggerID[$j]['offer_id']) ? $arrVisualsByTriggerID[$j]['offer_id'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['VisualURL'] = isset($arrVisualsByTriggerID[$j]['url']) ? $arrVisualsByTriggerID[$j]['url'] : '';
					if($arrVisualsByTriggerID[$j]['discriminator'] == "VIDEO"){
						$outArrAllTriggers[$i]['visual'][$j]['VisualURL'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['videos']).$arrVisualsByTriggerID[$j]['url'];
						$outArrAllTriggers[$i]['visual'][$j]['videostreaming'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['videostreaming']).$arrVisualsByTriggerID[$j]['url'];
						
					}
					$outArrAllTriggers[$i]['visual'][$j]['rotation_x'] = isset($arrVisualsByTriggerID[$j]['rotation_x']) ? $arrVisualsByTriggerID[$j]['rotation_x'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['rotation_y'] = isset($arrVisualsByTriggerID[$j]['rotation_y']) ? $arrVisualsByTriggerID[$j]['rotation_y'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['rotation_z'] = isset($arrVisualsByTriggerID[$j]['rotation_z']) ? $arrVisualsByTriggerID[$j]['rotation_z'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['scale'] = isset($arrVisualsByTriggerID[$j]['scale']) ? $arrVisualsByTriggerID[$j]['scale'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['animate_on_recognition'] = isset($arrVisualsByTriggerID[$j]['animate_on_recognition']) ? $arrVisualsByTriggerID[$j]['animate_on_recognition'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['video_in_metaio'] = isset($arrVisualsByTriggerID[$j]['video_in_metaio']) ? $arrVisualsByTriggerID[$j]['video_in_metaio'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['ignore_tracking'] = isset($arrVisualsByTriggerID[$j]['ignore_tracking']) ? $arrVisualsByTriggerID[$j]['ignore_tracking'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['hasTryOn'] = isset($arrVisualsByTriggerID[$j]['hasTryOn']) ? $arrVisualsByTriggerID[$j]['hasTryOn'] : 0;
					$outArrAllTriggers[$i]['visual'][$j]['buyButtonName'] = isset($arrVisualsByTriggerID[$j]['buy_button_name']) ? $arrVisualsByTriggerID[$j]['buy_button_name'] : '';
					$outArrAllTriggers[$i]['visual'][$j]['buyButtonUrl'] = isset($arrVisualsByTriggerID[$j]['buy_button_url']) ? $arrVisualsByTriggerID[$j]['buy_button_url'] : '';
					
					$arrModelsByVisualID = $this->objPublic->getModelsByVisualID($arrVisualsByTriggerID[$j]['id']);
					for($k=0;$k<count($arrModelsByVisualID);$k++){
						$outArrAllTriggers[$i]['visual'][$j]['models'][$k]['modelID'] = isset($arrModelsByVisualID[$k]['id']) ? $arrModelsByVisualID[$k]['id'] : 0;
						$outArrAllTriggers[$i]['visual'][$j]['models'][$k]['three_d_model_id'] = isset($arrModelsByVisualID[$k]['three_d_model_id']) ? $arrModelsByVisualID[$k]['three_d_model_id'] : 0;
						$outArrAllTriggers[$i]['visual'][$j]['models'][$k]['model'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['models']).$arrModelsByVisualID[$k]['model'];
						$outArrAllTriggers[$i]['visual'][$j]['models'][$k]['texture'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['models']).$arrModelsByVisualID[$k]['texture'];
						$outArrAllTriggers[$i]['visual'][$j]['models'][$k]['material'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['models']).$arrModelsByVisualID[$k]['material'];
						$outArrAllTriggers[$i]['visual'][$j]['models'][$k]['active'] = $arrModelsByVisualID[$k]['active'];
						
					}
						
				}
			}
			if($fileType=="json")
			{
			   echo json_encode($outArrAllTriggers);
			}
			else
			{
			   echo json_encode($outArrAllTriggers);
			}
			//echo json_encode($outAllTriggers);
			/*$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
			echo $xml->saveXML();*/
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modClientGroupsTriggers($fileType){
		try{
			global $config;			
			$outArrAllTriggers = array();		
			$outAllTriggers=array();
			$outArrAllModel = array();	
			$arrAllTriggers = array();
			$arrClients = array("");
			$outArrAllTriggers = array();
			$arrVisualsByTriggerID = array();
			
			$filePath = "http://".$_SERVER['HTTP_HOST']."/files/all_clients_markers/";
			
			$arrAllTriggers = $this->objPublic->getClientGroupTriggers();
			
			
			for($i=0;$i<count($arrAllTriggers);$i++){
				$arrClientDetailsById = array();
				$outArrAllTriggers[$i]['triggerId'] = isset($arrAllTriggers[$i]['id']) ? $arrAllTriggers[$i]['id'] : 0;
				$outArrAllTriggers[$i]['triggerURL'] = isset($arrAllTriggers[$i]['image']) ? $filePath.$arrAllTriggers[$i]['image'] : '';
				$outArrAllTriggers[$i]['client_id'] = isset($arrAllTriggers[$i]['client_id']) ? $arrAllTriggers[$i]['client_id'] : 0;
				$arrClientDetailsById = $this->objPublic->getClientById($outArrAllTriggers[$i]['client_id']);
				$outArrAllTriggers[$i]['clientName'] = isset($arrClientDetailsById[0]['name']) ? $arrClientDetailsById[0]['name'] : '';
				$outArrAllTriggers[$i]['triggerTitle'] = isset($arrAllTriggers[$i]['group_name']) ? $arrAllTriggers[$i]['group_name'] : '';
				$outArrAllTriggers[$i]['triggerHeight'] = isset($arrAllTriggers[$i]['height']) ? $arrAllTriggers[$i]['height'] : 0;
				$outArrAllTriggers[$i]['triggerWidth'] = isset($arrAllTriggers[$i]['width']) ? $arrAllTriggers[$i]['width'] : 0;
				$outArrAllTriggers[$i]['instruction'] = isset($arrAllTriggers[$i]['instruction']) ? $arrAllTriggers[$i]['instruction'] : '';
				$outArrAllTriggers[$i]['active'] = $arrAllTriggers[$i]['active'];

			}
			if($fileType=="json")
			{
			   echo json_encode($outArrAllTriggers);
			}
			else
			{
			   echo json_encode($outArrAllTriggers);
			}
			//echo json_encode($outAllTriggers);
			/*$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
			echo $xml->saveXML();*/
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modProductOffers($fileType){
		try{
			global $config;			
			$arrAllProductOffers = array();
			$outArrAllProductOffers = array();
			
			//$filePath = "http://".$_SERVER['HTTP_HOST']."/files/all_clients_markers/";
			
			 $arrAllProductOffers = $this->objPublic->getAllProductOffers();
			 print_r($arrAllProductOffers);
			
			for($i=0;$i<count($arrAllProductOffers);$i++){
				$outArrAllProductOffers[$i]['clientId'] = isset($arrAllProductOffers[$i]['offer_name']) ? $arrAllProductOffers[$i]['offer_name'] : 0;
				$outArrAllProductOffers[$i]['productId'] = isset($arrAllProductOffers[$i]['offer_name']) ? $arrAllProductOffers[$i]['offer_name'] : 0;

			}
			if($fileType=="json")
			{
			   echo json_encode($outArrAllProductOffers);
			}
			else
			{
			   echo json_encode($outArrAllProductOffers);
			}
			//echo json_encode($outAllTriggers);
			/*$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
			echo $xml->saveXML();*/
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

// public function modClientById($pCliendId){
// 		try{
// 			global $config;			
// 			$arrAllClient = array();
// 			$outArrAllClient = array();
// 			$arrClientForPublicClients = array();
// 			$arrRelatedProducts = array();
// 			$arrAllClientProducts = array();
// 			
// 			$clientID = isset($pCliendId) ? $pCliendId : 0;
// 			//$filePath = "http://".$_SERVER['HTTP_HOST']."/files/all_clients_markers/";
// 			
// 			 $arrAllClient = $this->objPublic->getClientById($clientID);
// 			 //print_r($arrAllClient);
// 			
// 			for($i=0;$i<count($arrAllClient);$i++){
// 				$outArrAllClient[$i]['name'] = isset($arrAllClient[$i]['name']) ? $arrAllClient[$i]['name'] : "";
// 				$outArrAllClient[$i]['id'] = isset($arrAllClient[$i]['id']) ? (int)$arrAllClient[$i]['id'] : 0;
// 				$outArrAllClient[$i]['prefix'] = isset($arrAllClient[$i]['prefix']) ? $arrAllClient[$i]['prefix'] : "";
// 				$isClientActive = isset($arrAllClient[$i]['active']) ? $arrAllClient[$i]['active'] : 0;
// 				$isClientActive =  $isClientActive==1 ? true : false;
// 				$outArrAllClient[$i]['active'] = $isClientActive;
// 				
// 				$outArrAllClient[$i]['url'] = isset($arrAllClient[$i]['url']) ? $arrAllClient[$i]['url'] : "";
// 				$outArrAllClient[$i]['logo'] = isset($arrAllClient[$i]['logo']) ? $arrAllClient[$i]['logo'] : "";
// 				$outArrAllClient[$i]['backgroundColor'] = isset($arrAllClient[$i]['background_color']) ? $arrAllClient[$i]['background_color'] : "";
// 				$outArrAllClient[$i]['lightColor'] = isset($arrAllClient[$i]['light_color']) ? $arrAllClient[$i]['light_color'] : "";
// 				$outArrAllClient[$i]['darkColor'] = isset($arrAllClient[$i]['dark_color']) ? $arrAllClient[$i]['dark_color'] : "";
// 				$outArrAllClient[$i]['backgroundImage'] = isset($arrAllClient[$i]['background_image']) ? $arrAllClient[$i]['background_image'] : "";
// 				
// 				$outArrAllClient[$i]['catalogs'] = array();
// 				$outArrAllClient[$i]['products'] = array();
// 				$arrClientForPublicClients = $outArrAllClient[$i];
// 				//array_push($arrClientForPublicClients, array("products"=>""));
// 				$arrAllClientProducts = $this->objPublic->getClientProductsById($clientID);
// 				//print_r($arrAllClientProducts);
// 				for($j=0;$j<count($arrAllClientProducts);$j++){
// 					$outArrAllClient[$i]['products'][$j]["id"] = isset($arrAllClientProducts[$j]['pd_id']) ? $arrAllClientProducts[$j]['pd_id'] : "";
// 					$isClientActive = isset($arrAllClientProducts[$j]['active']) ? $arrAllClientProducts[$j]['active'] : 0;
// 					$isClientActive =  $isClientActive==1 ? true : false;
// 					$outArrAllClient[$i]['products'][$j]["active"] = $isClientActive;
// 					$outArrAllClient[$i]['products'][$j]["description"] = isset($arrAllClientProducts[$j]['pd_description']) ? $arrAllClientProducts[$j]['pd_description'] : "";
// 					$outArrAllClient[$i]['products'][$j]["category"] = isset($arrAllClientProducts[$j]['pd_category_name']) ? $arrAllClientProducts[$j]['pd_category_name'] : "";
// 					$outArrAllClient[$i]['products'][$j]["shortDescription"] = isset($arrAllClientProducts[$j]['pd_short_description']) ? $arrAllClientProducts[$j]['pd_short_description'] : "";
// 					$outArrAllClient[$i]['products'][$j]["url"] = isset($arrAllClientProducts[$j]['pd_url']) ? $arrAllClientProducts[$j]['pd_url'] : "";
// 					$outArrAllClient[$i]['products'][$j]["additionalProductMedia"][0]['id'] =0;
// 					$outArrAllClient[$i]['products'][$j]["additionalProductMedia"][0]['media'] ="";
// 					$outArrAllClient[$i]['products'][$j]["additionalProductMedia"][0]['video'] ="";
// 					$outArrAllClient[$i]['products'][$j]["red"] = 0;
// 					$outArrAllClient[$i]['products'][$j]["green"] = 0;
// 					$outArrAllClient[$i]['products'][$j]["blue"] = 0;
// 					$outArrAllClient[$i]['products'][$j]["title"] = isset($arrAllClientProducts[$j]['pd_name']) ? $arrAllClientProducts[$j]['pd_name'] : "";
// 					$prodImage = isset($arrAllClientProducts[$j]['pd_image']) ? $arrAllClientProducts[$j]['pd_image'] : "";
// 					$outArrAllClient[$i]['products'][$j]["image"] = str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['products']).$prodImage;
// 					$outArrAllClient[$i]['products'][$j]["barcode"] = "";
// 					$outArrAllClient[$i]['products'][$j]["hideBackground"] = 0;
// 					$outArrAllClient[$i]['products'][$j]["price"] = isset($arrAllClientProducts[$j]['pd_price']) ? $arrAllClientProducts[$j]['pd_price'] : "";
// 					$outArrAllClient[$i]['products'][$j]["html"] = "";
// 					$outArrAllClient[$i]['products'][$j]["style"] = null;
// 					$outArrAllClient[$i]['products'][$j]["offer"] = false;
// 					$outArrAllClient[$i]['products'][$j]["color"] = "";
// 					
// 					
// 					
// 					$outArrAllClient[$i]['products'][$j]["publicClient"] = $arrClientForPublicClients;
// 					
// 					
// 					
// 					$arrRelatedProducts = $this->objPublic->getRelatedProdsById($outArrAllClient[$i]['products'][$j]["id"]);
// 					//print_r($arrRelatedProducts);
// 					$prodRelated = isset($arrRelatedProducts[0]['prodRelated']) ? $arrRelatedProducts[0]['prodRelated'] : '';
// 					$arrProdRelated = array();
// 					$arrProdRelated = explode(",", $prodRelated);
// 					$outArrAllClient[$i]['products'][$j]["related"] = $arrProdRelated;
// 
// 				}
// 				$outArrAllClient[$i]['color'] = isset($arrAllClient[$i]['prefix']) ? $arrAllClient[$i]['prefix'] : "000000";
// 				
// 				
// 			}
// // 			if(isset($fileType) && $fileType=="json")
// // 			{
// // 			   echo json_encode($outArrAllClient);
// // 			}
// // 			else
// // 			{
// // 			   echo json_encode($outArrAllClient);
// // 			}
// 		 	$jsonString =  json_encode($outArrAllClient);
// // 			$jsonString = trim($jsonString, "[");
// // 			$jsonString = trim($jsonString, "]");
// // 			$jsonString = str_replace('\\','',$jsonString);
// 			echo  json_encode($outArrAllClient);
// 			//echo json_encode($outAllTriggers);
// 			/*$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
// 			echo $xml->saveXML();*/
// 		}
// 		catch ( Exception $e ) {
// 			echo 'Message: ' .$e->getMessage();
// 		}
// 	}
		public function modClientById($pCliendId){
		try{
			global $config;			
			$arrAllClient = array();
			$outArrAllClient = array();
			$arrClientForPublicClients = array();
			$arrRelatedProducts = array();
			$arrAllClientProducts = array();
			$arrAdditionalImages = array();
			$arrProdBackgroundInfo = array();
			$prodAdditionalImages = array();
			
			$clientID = isset($pCliendId) ? $pCliendId : 0;
			//$filePath = "http://".$_SERVER['HTTP_HOST']."/files/all_clients_markers/";
			
			 $arrAllClient = $this->objPublic->getClientById($clientID);
			 //print_r($arrAllClient);
			
			for($i=0;$i<count($arrAllClient);$i++){
				$outArrAllClient['name'] = isset($arrAllClient[$i]['name']) ? $arrAllClient[$i]['name'] : "";
				$outArrAllClient['id'] = isset($arrAllClient[$i]['id']) ? (int)$arrAllClient[$i]['id'] : 0;
				$outArrAllClient['prefix'] = isset($arrAllClient[$i]['prefix']) ? $arrAllClient[$i]['prefix'] : "";
				$isClientActive = isset($arrAllClient[$i]['active']) ? $arrAllClient[$i]['active'] : 0;
				$isClientActive =  $isClientActive==1 ? true : false;
				$outArrAllClient['active'] = $isClientActive;
				$outArrAllClient["currencyCode"] = 'USD';
					$outArrAllClient["searchType"] ='';
				$arrClientDetails = $this->objPublic->getCurrencyCodeByClientId($outArrAllClient['id']);
				if (count($arrClientDetails)>0) {
					$outArrAllClient["currencyCode"] = isset($arrClientDetails[0]['client_details_currency_code']) ? $arrClientDetails[0]['client_details_currency_code'] : 'USD';
					$outArrAllClient["searchType"] = isset($arrClientDetails[0]['client_search_type']) ? $arrClientDetails[0]['client_search_type'] : '';
				}

				$outArrAllClient['url'] = isset($arrAllClient[$i]['url']) ? $arrAllClient[$i]['url'] : "";
				$clientLogo = isset($arrAllClient[$i]['logo']) ? $arrAllClient[$i]['logo'] : "";
				$outArrAllClient['logo'] = str_replace("{client_id}",$arrAllClient[$i]['id'],$config['files']['logo']).$clientLogo;
				$outArrAllClient['storeNotifyMsg'] = isset($arrAllClient[$i]['store_notify_msg']) ? $arrAllClient[$i]['store_notify_msg'] : "";
					
				$outArrAllClient['backgroundColor'] = isset($arrAllClient[$i]['background_color']) ? $arrAllClient[$i]['background_color'] : "";
				$outArrAllClient['lightColor'] = isset($arrAllClient[$i]['light_color']) ? $arrAllClient[$i]['light_color'] : "";
				$outArrAllClient['darkColor'] = isset($arrAllClient[$i]['dark_color']) ? $arrAllClient[$i]['dark_color'] : "";
				$outArrAllClient['backgroundImage'] = isset($arrAllClient[$i]['background_image']) ? $arrAllClient[$i]['background_image'] : "";
				$outArrAllClient['isLocationBased'] = isset($arrAllClient[$i]['is_location_based']) ? $arrAllClient[$i]['is_location_based'] : 0;
				
				$outArrAllClient['catalogs'] = array();
				$outArrAllClient['products'] = array();
				$arrClientForPublicClients = $outArrAllClient;
				//array_push($arrClientForPublicClients, array("products"=>""));
				$arrAllClientProducts = $this->objPublic->getClientProductsById($clientID);
				//print_r($arrAllClientProducts);
				for($j=0;$j<count($arrAllClientProducts);$j++){
					$outArrAllClient['products'][$j]["id"] = isset($arrAllClientProducts[$j]['pd_id']) ? $arrAllClientProducts[$j]['pd_id'] : "";
					$isClientActive = isset($arrAllClientProducts[$j]['active']) ? $arrAllClientProducts[$j]['active'] : 0;
					$isClientActive =  $isClientActive==1 ? true : false;
					$outArrAllClient['products'][$j]["active"] = $isClientActive;
					$outArrAllClient['products'][$j]["description"] = isset($arrAllClientProducts[$j]['pd_description']) ? $arrAllClientProducts[$j]['pd_description'] : "";
					$outArrAllClient['products'][$j]["category"] = isset($arrAllClientProducts[$j]['pd_category_name']) ? $arrAllClientProducts[$j]['pd_category_name'] : "";
					$outArrAllClient['products'][$j]["shortDescription"] = isset($arrAllClientProducts[$j]['pd_short_description']) ? $arrAllClientProducts[$j]['pd_short_description'] : "";
					$outArrAllClient['products'][$j]["url"] = isset($arrAllClientProducts[$j]['pd_url']) ? $arrAllClientProducts[$j]['pd_url'] : "";
					$outArrAllClient['products'][$j]["red"] = 0;
					$outArrAllClient['products'][$j]["green"] = 0;
					$outArrAllClient['products'][$j]["blue"] = 0;
					$outArrAllClient['products'][$j]["title"] = isset($arrAllClientProducts[$j]['pd_name']) ? $arrAllClientProducts[$j]['pd_name'] : "";
					$prodImage = isset($arrAllClientProducts[$j]['pd_image']) ? $arrAllClientProducts[$j]['pd_image'] : "";
					$outArrAllClient['products'][$j]["image"] = str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['products']).$prodImage;
					$outArrAllClient['products'][$j]["barcode"] = "";
					$outArrAllClient['products'][$j]["price"] = isset($arrAllClientProducts[$j]['pd_price']) ? $arrAllClientProducts[$j]['pd_price'] : "";
					$outArrAllClient['products'][$j]["html"] = "";
					$outArrAllClient['products'][$j]["style"] = null;
					$outArrAllClient['products'][$j]["offer"] = false;
					$outArrAllClient['products'][$j]["isTryOn"] = isset($arrAllClientProducts[$j]['pd_istryon']) ? $arrAllClientProducts[$j]['pd_istryon'] : 0;
					$outArrAllClient['products'][$j]["buyButtonName"] = isset($arrAllClientProducts[$j]['pd_button_name']) ? $arrAllClientProducts[$j]['pd_button_name'] : '';
					$outArrAllClient['products'][$j]["clientVerticalId"] = isset($arrAllClientProducts[$j]['client_vertical_id']) ? $arrAllClientProducts[$j]['client_vertical_id'] : 0;
					$outArrAllClient['products'][$j]["isDemo"] = isset($arrAllClientProducts[$j]['is_demo']) ? $arrAllClientProducts[$j]['is_demo'] : 0;
			
					$arrProdBackgroundInfo = $this->objPublic->getProductBackgroundById($outArrAllClient['products'][$j]["id"]);
					$outArrAllClient['products'][$j]["hideBackground"] = 0;
					$outArrAllClient['products'][$j]["color"] = "";
					if (count($arrProdBackgroundInfo)>0){
						$outArrAllClient['products'][$j]["hideBackground"] = isset($arrProdBackgroundInfo[0]['pd_hide_bg_image']) ? $arrProdBackgroundInfo[0]['pd_hide_bg_image'] : 0;
						$outArrAllClient['products'][$j]["color"] = isset($arrProdBackgroundInfo[0]['pd_bg_color']) ? $arrProdBackgroundInfo[0]['pd_bg_color'] : 0;
					}
					$outArrAllClient['products'][$j]["publicClient"] = $arrClientForPublicClients;
					
					$prodAdditionalImages = $this->objPublic->getAdditionalImagesByPdId($outArrAllClient['products'][$j]["id"]);
					if (count($prodAdditionalImages)>0){
						$arrAdditionalImages = (array) json_decode($prodAdditionalImages[0]['pd_additional_images'],true);
						// print_r($arrAdditionalImages);
						for ($a=0; $a <count($arrAdditionalImages); $a++){
							$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['id'] =$a;
							$pdAddImage = isset($arrAdditionalImages[$a]['image']) ? $arrAdditionalImages[$a]['image'] : '';
							if($pdAddImage!=""){
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['media'] = str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['additional']).$pdAddImage;
							}else{
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['media'] = "";
							}
							$pdAddVideo = isset($arrAdditionalImages[$a]['video']) ? $arrAdditionalImages[$a]['video'] : '';
							if($pdAddVideo!=""){
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['video'] =str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['additional']).$pdAddVideo;
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['videostreaming'] = str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['videostreaming']).$pdAddVideo;
				
							}else{
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['video'] ="";
							}
							$pdAddAudio = isset($arrAdditionalImages[$a]['audio']) ? $arrAdditionalImages[$a]['audio'] : '';
							if($pdAddAudio!=""){
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['audio'] =str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['additional']).$pdAddAudio;
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['audiostreaming'] = str_replace("{client_id}",$arrAllClientProducts[$i]['client_id'],$config['files']['videostreaming']).$pdAddAudio;
							}else{
								$outArrAllClient['products'][$j]["additionalProductMedia"][$a]['audio'] ="";
							}
						}
					}
					$arrRelatedProducts = $this->objPublic->getRelatedProdsById($outArrAllClient['products'][$j]["id"]);
					//print_r($arrRelatedProducts);
					$prodRelated = isset($arrRelatedProducts[0]['prodRelated']) ? $arrRelatedProducts[0]['prodRelated'] : '';
					$arrProdRelated = array();
					$arrProdRelated = explode(",", $prodRelated);
					$outArrAllClient['products'][$j]["related"] = $arrProdRelated;
					
					
					$prodOfferRelated = isset($arrRelatedProducts[0]['offerRelated']) ? $arrRelatedProducts[0]['offerRelated'] : '';
					$arrProdOfferRelated = array();
					$arrProdOfferRelated = explode(",", $prodOfferRelated);
					$outArrAllClient['products'][$j]["relatedOffer"]  = $arrProdOfferRelated;

				}
				$outArrAllClient['color'] = isset($arrAllClient[$i]['prefix']) ? $arrAllClient[$i]['prefix'] : "000000";
				
				
			}

			echo  json_encode($outArrAllClient);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modProductDetailsSet($pUserInfo){
		try{
			global $config;			
			$outArrProdDetails = array();
			$arrProdIds = isset($pUserInfo['prodIds']) ? $pUserInfo['prodIds'] : '';
			// $arrProdIds = "2798|104";
			// print_r($arrProdIds);
			$arrProdIds = explode("|",$arrProdIds);
			$j=0;
			for ($i=0;$i<count($arrProdIds); $i++){
				$pId = isset($arrProdIds[$i]) ? $arrProdIds[$i] : 0;
				if ($pId > 0) {
					$outArrProdDetails[$j] = $this->getProductDetails($pId);	
					$j++;
				}
				
			}
			// print_r($outArrProdDetails);
			// $outArrProdDetails = $this->getProductDetails($pId);
			echo json_encode($outArrProdDetails); 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modProductDetails($pId){
		try{
			global $config;			
			$outArrProdDetails = array();
			$outArrProdDetails = $this->getProductDetails($pId);
			echo json_encode($outArrProdDetails); 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getProductDetails($pId){
		try{
			global $config;			
			$arrProdDetails = array();
			$outArrProdDetails = array();
			$arrClientDetails = array();
			$outArrClientDetails = array();
			$arrClientForPublicClients = array();
			$prodAdditionalImages = array();
			$arrClientFullDetails = array();
			
			$arrProdDetails = $this->objPublic->getProductsById($pId);
			//print_r($arrProdDetails);
			$outArrProdDetails["active"] = isset($arrProdDetails[0]['pd_status']) ? $arrProdDetails[0]['pd_status'] : "";
			$outArrProdDetails["id"] = isset($arrProdDetails[0]['pd_id']) ? $arrProdDetails[0]['pd_id'] : "";
			$outArrProdDetails["title"] = isset($arrProdDetails[0]['pd_name']) ? $arrProdDetails[0]['pd_name'] : "";
			$outArrProdDetails["barcode"] = "";
			$outArrProdDetails["color"] = "";
			$outArrProdDetails["category"] = isset($arrProdDetails[0]['pd_category_name']) ? $arrProdDetails[0]['pd_category_name'] : "";
			$outArrProdDetails["description"] = isset($arrProdDetails[0]['pd_description']) ? $arrProdDetails[0]['pd_description'] : "";
			$outArrProdDetails["hideBackground"] = isset($arrProdDetails[0]['pd_hide_bg_image']) ? $arrProdDetails[0]['pd_hide_bg_image'] : 0;
			$outArrProdDetails["isTryOn"] = isset($arrProdDetails[0]['pd_istryon']) ? $arrProdDetails[0]['pd_istryon'] : 0;
			$outArrProdDetails["html"] = "";
			$prodImage = isset($arrProdDetails[0]['pd_image']) ? $arrProdDetails[0]['pd_image'] : "";
			$clientId = isset($arrProdDetails[0]['client_id']) ? $arrProdDetails[0]['client_id'] : 0;
			$prodCompleteImage = str_replace("{client_id}",$clientId,$config['files']['products']).$prodImage;
			$outArrProdDetails["image"] = $prodCompleteImage;
			$outArrProdDetails["price"] = isset($arrProdDetails[0]['pd_price']) ? $arrProdDetails[0]['pd_price'] : "";
			$outArrProdDetails["shortDescription"] = isset($arrProdDetails[0]['pd_short_description']) ? $arrProdDetails[0]['pd_short_description'] : "";
			$outArrProdDetails["url"] = isset($arrProdDetails[0]['pd_url']) ? $arrProdDetails[0]['pd_url'] : "";
			$outArrProdDetails["buyButtonName"] = isset($arrProdDetails[0]['pd_button_name']) ? $arrProdDetails[0]['pd_button_name'] : "";
			
			$clientID = isset($arrProdDetails[0]['client_id']) ? $arrProdDetails[0]['client_id'] : 0;
			$arrClientDetails = $this->objPublic->getClientById($clientID);
			
			$arrClientFullDetails = $this->objPublic->getCurrencyCodeByClientId($clientID);
			if (count($arrClientFullDetails)>0) {
				$outArrClientDetails["currencyCode"] = isset($arrClientFullDetails[0]['client_details_currency_code']) ? $arrClientFullDetails[0]['client_details_currency_code'] : 'USD';
			}else{
				$outArrClientDetails["currencyCode"] = 'USD';
			}

			$outArrClientDetails['id'] = isset($arrClientDetails[0]['id']) ? $arrClientDetails[0]['id'] : 0;
			$outArrClientDetails['name'] = isset($arrClientDetails[0]['name']) ? $arrClientDetails[0]['name'] : "";
			$outArrClientDetails['prefix'] = isset($arrClientDetails[0]['prefix']) ? $arrClientDetails[0]['prefix'] : "";
			$clientLogo = isset($arrClientDetails[0]['logo']) ? $arrClientDetails[0]['logo'] : "";
			$outArrClientDetails['logo'] = str_replace("{client_id}",$clientId,$config['files']['logo']).$clientLogo;
			$outArrClientDetails['url'] = isset($arrClientDetails[0]['url']) ? $arrClientDetails[0]['url'] : "";
			$outArrClientDetails['backgroundColor'] = isset($arrClientDetails[0]['background_color']) ? $arrClientDetails[0]['background_color'] : "";
			$outArrClientDetails['lightColor'] = isset($arrClientDetails[0]['light_color']) ? $arrClientDetails[0]['light_color'] : "";
			$outArrClientDetails['darkColor'] = isset($arrClientDetails[0]['dark_color']) ? $arrClientDetails[0]['dark_color'] : "";
			$outArrClientDetails['color'] = isset($arrClientDetails[0]['prefix']) ? $arrClientDetails[0]['prefix'] : "000000";
			$outArrClientDetails['backgroundImage'] = isset($arrClientDetails[0]['background_image']) ? $arrClientDetails[0]['background_image'] : "";
			$outArrClientDetails['clientVerticalId'] = isset($arrClientDetails[0]['client_vertical_id']) ? $arrClientDetails[0]['client_vertical_id'] : 0;
			$outArrClientDetails['isDemo'] = isset($arrClientDetails[0]['is_demo']) ? $arrClientDetails[0]['is_demo'] : 0;
			
			$outArrClientDetails['catalogs'] = array();
			$outArrClientDetails['products'] = array();
			$arrClientForPublicClients = $outArrClientDetails;
			//array_push($arrClientForPublicClients, array("products"=>""));
				
			$outArrProdDetails["publicClient"] = $arrClientForPublicClients;
			
			$prodAdditionalImages = $this->objPublic->getAdditionalImagesByPdId($outArrProdDetails["id"]);
			if (count($prodAdditionalImages)>0){
				$arrAdditionalImages = (array) json_decode($prodAdditionalImages[0]['pd_additional_images'],true);
// 						print_r($arrAdditionalImages);
				for ($a=0; $a <count($arrAdditionalImages); $a++){
					$outArrProdDetails["additionalProductMedia"][$a]['id'] =$a;
					$pdAddImage = isset($arrAdditionalImages[$a]['image']) ? $arrAdditionalImages[$a]['image'] : '';
					if($pdAddImage!=""){
						$outArrProdDetails["additionalProductMedia"][$a]['media'] = str_replace("{client_id}",$clientID,$config['files']['additional']).$pdAddImage;
					}else{
						$outArrProdDetails["additionalProductMedia"][$a]['media'] = "";
					}
					$pdAddVideo = isset($arrAdditionalImages[$a]['video']) ? $arrAdditionalImages[$a]['video'] : '';
					if($pdAddVideo!=""){
						$outArrProdDetails["additionalProductMedia"][$a]['video'] =str_replace("{client_id}",$clientID,$config['files']['additional']).$pdAddVideo;
						$outArrProdDetails["additionalProductMedia"][$a]['videostreaming'] = str_replace("{client_id}",$clientID,$config['files']['videostreaming']).$pdAddVideo;
						
					}else{
						$outArrProdDetails["additionalProductMedia"][$a]['video'] ="";
					}
					$pdAddAudio = isset($arrAdditionalImages[$a]['audio']) ? $arrAdditionalImages[$a]['audio'] : '';
					if($pdAddAudio!=""){
						$outArrProdDetails["additionalProductMedia"][$a]['audio'] =str_replace("{client_id}",$clientID,$config['files']['additional']).$pdAddAudio;
						$outArrProdDetails["additionalProductMedia"][$a]['audiostreaming'] = str_replace("{client_id}",$clientID,$config['files']['videostreaming']).$pdAddAudio;
					
					}else{
						$outArrProdDetails["additionalProductMedia"][$a]['audio'] ="";
					}
				}
			}
			
			
			$arrRelatedProducts = $this->objPublic->getRelatedProdsById($outArrProdDetails["id"]);
			
			$prodRelated = isset($arrRelatedProducts[0]['prodRelated']) ? $arrRelatedProducts[0]['prodRelated'] : '';
			
			$arrProdRelated = array();
			$arrProdRelated = explode(",", $prodRelated);
			$outArrProdDetails["related"]  = $arrProdRelated;
			
			$prodOfferRelated = isset($arrRelatedProducts[0]['offerRelated']) ? $arrRelatedProducts[0]['offerRelated'] : '';
			
			$arrProdOfferRelated = array();
			$arrProdOfferRelated = explode(",", $prodOfferRelated);
			$outArrProdDetails["relatedOffer"]  = $arrProdOfferRelated;
					
			return $outArrProdDetails;
			
			//echo '{"id":104,"active":true,"description":"Palazzo","category":null,"shortDescription":"","url":null,"additionalProductMedia":[{"id":151,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/FashionShowMap.jpg","video":false},{"id":152,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PropertyMap.jpg","video":false},{"id":150,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/77969624.png","video":false}],"red":0,"green":0,"blue":0,"title":"Palazzo","image":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PalazzoAd_main_image.jpg","barcode":"","hideBackground":false,"price":0.0,"html":"","style":null,"offer":false,"offeredProductId":0,"related":[118,100,117,101,112],"publicClient":{"name":"GGP","id":94,"prefix":"GGP","active":true,"url":"http://www.ggp.com","products":[],"catalogs":[],"logo":null,"backgroundColor":"f68e2e","lightColor":"f26522","darkColor":"f26522","backgroundImage":null}}';

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modDoUserLogin($pUserInfo){
		try{
			global $config;			
			$outArrUserDetails = array();
			$outArrUserDetails = $this->modUserLogin($pUserInfo);
			echo json_encode($outArrUserDetails); 
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserLogin($pUserInfo){
		try{
			global $config;	
			$arrUser = array();
			$arrTempUser = array();
			$outArrUser = array();
			$uname = isset($pUserInfo['username']) ? $pUserInfo['username'] : '';
			$pass = isset($pUserInfo['password']) ? $pUserInfo['password'] : '';
			
			if ($uname!="" && $pass!=""){
				$arrUser = $this->objPublic->validateUserLogin($uname, $pass);
				// echo "validateUserLogin";
				// print_r($arrUser);
				if (count($arrUser) > 0){
					$pUserInfo = array("uname" => $arrUser[0]['user_username']);
					$outArrUser = $this->modUserLoggedInDetails($pUserInfo);
						
				}else{
					$arrTempUser = $this->objPublic->validateUserLoginWithTempPwd($uname, $pass);
					if (count($arrTempUser) > 0){
						
						//Remove temp password from DB
						$uid = isset($arrTempUser[0]['user_id']) ? $arrTempUser[0]['user_id'] : 0;
						if($uid!=0){
							$con = array();
							$con['user_id'] = $uid;	
							$con['user_status'] = 1;	
							if($con['user_id'] !=0){
								$wdUserArray['user_temp_password'] = "";
								$wdUserArray['user_password'] = md5($pass);
								$updateRecord = $this->objPublic->updateRecordQuery($wdUserArray, "users", $con);
								if ($updateRecord){
									$pUserInfo = array("uname" => $arrTempUser[0]['user_username']);
									$outArrUser = $this->modUserLoggedInDetails($pUserInfo);
									$outArrUser['msg']='success';
								}else{
									$outArrUser['msg']='fail';
								}
							}
						}else{
							$outArrUser['msg']='fail';
						}
						
					}else{
						$outArrUser['success'] = false;
					}
				}
			}else{
				$outArrUser['success'] = false;
			}
			
			return $outArrUser;
			//echo json_encode($outArrUser);
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserLoggedInDetails($pUserInfo){
		try{
			global $config;	
			$arrUser = array();
			$outArrUser = array();
			// print_r($pUserInfo);
			$uname = isset($pUserInfo['uname']) ? $pUserInfo['uname'] : '';
			$fbid = isset($pUserInfo['fbid']) ? $pUserInfo['fbid'] : '';
			$email = isset($pUserInfo['email']) ? $pUserInfo['email'] : '';
			$fname = isset($pUserInfo['fname']) ? $pUserInfo['fname'] : '';
			$lname = isset($pUserInfo['lname']) ? $pUserInfo['lname'] : '';
			
			if (($uname!="") || ($fbid!="") || ($email!="")){
				if (($fbid!="") && ($uname =="")){
					// echo 'modUserLoggedInDetails';
					// $arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingFB($pUserInfo);
					// $username = $userEmail;
					// if (count($arrUser) == 0){
					// 	if (count($arrUser) == 0){
					// 		$arrUser = $this->objPublic->checkIfUsernameExistsByFBID($pUserInfo);
					// 	}
						
					// 	if (count($arrUser) == 0){
					// 		$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingType($pUserInfo);
					// 	}
					// }


					if (($email != "") && ($fbid != "")) {
						$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingFB($pUserInfo);
					}
					
					if (count($arrUser) == 0){
						if ($email != "") {
							$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingType($pUserInfo);
						}
					}
					if (count($arrUser) == 0){
						if (($fname != "") && ($lname != "") && ($fbid != "")) {
							$arrUser = $this->objPublic->checkIfUsernameExistsByFBIDAndNames($pUserInfo);
						}
					}
					if (count($arrUser) == 0){
						$tempUserName = $email;
						if ($tempUserName !="") {
							$arrUser = $this->objPublic->checkIfUsernameExistsByUname($tempUserName);
						}
					}


				}else{
					// $arrUser = $this->objPublic->checkIfUsernameExistsByUname($username);
					// $arrUser = $this->objPublic->getUserDetailsByUname($uname);
					if ($uname !="") {
						//$arrUser = $this->objPublic->checkIfUsernameExistsByUname($uname);
						$arrUser = $this->objPublic->getUserDetailsByUname($uname);
					}
					if (count($arrUser) == 0){
						if ($email !="") {
							$arrUser = $this->objPublic->getUserDetailsByEmail($email);
						}
					}
				}
				// print_r($arrUser);
				if (count($arrUser) > 0){
					$outArrUser['success'] = true;
					$outArrUser['user']['id']=$arrUser[0]['user_id'];
					$outArrUser['user']['username']=$arrUser[0]['user_username'];
					$outArrUser['user']['firstName']=$arrUser[0]['user_firstname'];
					$outArrUser['user']['lastName']=$arrUser[0]['user_lastname'];
					$outArrUser['user']['groupName']=$arrUser[0]['user_group_name'];
					$outArrUser['user']['groupId']=$arrUser[0]['user_group_id'];
					$outArrUser['user']['email']=$arrUser[0]['user_email_id'];
					$outArrUser['user']['status']=$arrUser[0]['user_status'];
					$outArrUser['user']['clients']=$arrUser[0]['client_ids'];
					
					$outArrUser['user']['avatar']=$arrUser[0]['user_details_avatar'];
					$outArrUser['user']['nickname']=$arrUser[0]['user_details_nickname'];
					$outArrUser['user']['phone']=$arrUser[0]['user_details_phone'];
					$outArrUser['user']['address1']=$arrUser[0]['user_details_address1'];
					$outArrUser['user']['address2']=$arrUser[0]['user_details_address2'];
					$outArrUser['user']['city']=$arrUser[0]['user_details_city'];
					$outArrUser['user']['state']=$arrUser[0]['user_details_state'];
					$outArrUser['user']['country']=$arrUser[0]['user_details_country'];
					$outArrUser['user']['zip']=$arrUser[0]['user_details_zip'];
					$outArrUser['user']['gender']=$arrUser[0]['user_details_gender'];
					$outArrUser['user']['dob']=$arrUser[0]['user_details_dob'];
					$outArrUser['user']['lastlogin']=$arrUser[0]['user_details_lastlogin'];
					$outArrUser['user']['facebookid']=$arrUser[0]['user_details_fbid'];
					
					
				}else{
					$outArrUser['success'] = false;
				}
			}else{
				$outArrUser['success'] = false;
			}
			
			return $outArrUser;
			//echo json_encode($outArrUser);
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserLoggedInDetailsByUID($pUid){
		try{
			// echo "modUserLoggedInDetailsByUID ".$pUid;
			global $config;	
			$arrUser = array();
			$outArrUser = array();
			// print_r($pUserInfo);
			// $uname = isset($pUserInfo['uname']) ? $pUserInfo['uname'] : '';
			// $fbid = isset($pUserInfo['fbid']) ? $pUserInfo['fbid'] : '';
			
			if ($pUid !='') {

				$arrUser = $this->objPublic->getUserDetailsByUid($pUid);

				// print_r($arrUser);
				if (count($arrUser) > 0){
					$outArrUser['success'] = true;
					$outArrUser['user']['id']=$arrUser[0]['user_id'];
					$outArrUser['user']['username']=$arrUser[0]['user_username'];
					$outArrUser['user']['firstName']=$arrUser[0]['user_firstname'];
					$outArrUser['user']['lastName']=$arrUser[0]['user_lastname'];
					$outArrUser['user']['groupName']=$arrUser[0]['user_group_name'];
					$outArrUser['user']['groupId']=$arrUser[0]['user_group_id'];
					$outArrUser['user']['email']=$arrUser[0]['user_email_id'];
					$outArrUser['user']['status']=$arrUser[0]['user_status'];
					$outArrUser['user']['clients']=$arrUser[0]['client_ids'];
					
					$outArrUser['user']['avatar']=$arrUser[0]['user_details_avatar'];
					$outArrUser['user']['nickname']=$arrUser[0]['user_details_nickname'];
					$outArrUser['user']['phone']=$arrUser[0]['user_details_phone'];
					$outArrUser['user']['address1']=$arrUser[0]['user_details_address1'];
					$outArrUser['user']['address2']=$arrUser[0]['user_details_address2'];
					$outArrUser['user']['city']=$arrUser[0]['user_details_city'];
					$outArrUser['user']['state']=$arrUser[0]['user_details_state'];
					$outArrUser['user']['country']=$arrUser[0]['user_details_country'];
					$outArrUser['user']['zip']=$arrUser[0]['user_details_zip'];
					$outArrUser['user']['gender']=$arrUser[0]['user_details_gender'];
					$outArrUser['user']['dob']=$arrUser[0]['user_details_dob'];
					$outArrUser['user']['lastlogin']=$arrUser[0]['user_details_lastlogin'];
					$outArrUser['user']['facebookid']=$arrUser[0]['user_details_fbid'];
					
					
				}else{
					$outArrUser['success'] = false;
				}
			}else{
				$outArrUser['success'] = false;
			}
			
			return $outArrUser;
			//echo json_encode($outArrUser);
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetUserDetails($pUserInfo){
		try{
			global $config;	
			$arrUser = array();
			$outArrUser = array();
			$uid = isset($pUserInfo['userid']) ? $pUserInfo['userid'] : '';
			if ($uid!=""){
				$arrUser = $this->objPublic->getUserDetailsByUid($uid);
				if (count($arrUser) > 0){
					$outArrUser['success'] = true;
					$outArrUser['user']['id']=$arrUser[0]['user_id'];
					$outArrUser['user']['username']=$arrUser[0]['user_username'];
					$outArrUser['user']['firstName']=$arrUser[0]['user_firstname'];
					$outArrUser['user']['lastName']=$arrUser[0]['user_lastname'];
					$outArrUser['user']['groupName']=$arrUser[0]['user_group_name'];
					$outArrUser['user']['email']=$arrUser[0]['user_email_id'];
					$outArrUser['user']['status']=$arrUser[0]['user_status'];
					$outArrUser['user']['clients']=$arrUser[0]['client_ids'];
					
					$outArrUser['user']['avatar']=$arrUser[0]['user_details_avatar'];
					$outArrUser['user']['nickname']=$arrUser[0]['user_details_nickname'];
					$outArrUser['user']['phone']=$arrUser[0]['user_details_phone'];
					$outArrUser['user']['address1']=$arrUser[0]['user_details_address1'];
					$outArrUser['user']['address2']=$arrUser[0]['user_details_address2'];
					$outArrUser['user']['city']=$arrUser[0]['user_details_city'];
					$outArrUser['user']['state']=$arrUser[0]['user_details_state'];
					$outArrUser['user']['country']=$arrUser[0]['user_details_country'];
					$outArrUser['user']['zip']=$arrUser[0]['user_details_zip'];
					$outArrUser['user']['gender']=$arrUser[0]['user_details_gender'];
					$outArrUser['user']['dob']=$arrUser[0]['user_details_dob'];
					$outArrUser['user']['lastlogin']=$arrUser[0]['user_details_lastlogin'];
					
				}else{
					$outArrUser['success'] = false;
				}
			}else{
				$outArrUser['success'] = false;
			}
			
			echo json_encode($outArrUser);
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUpdateUserDetails($pUserInfo){
		try{
			global $config;	
			$arrUser = array();
			$outArrUser = array();
			$wdUserArray = array();
			$wdUserDetailsArray = array();	
			
			$uid = isset($pUserInfo['userid']) ? $pUserInfo['userid'] : 0;
			if (isset($pUserInfo['firstName'])){
				$wdUserArray['user_firstname']=$pUserInfo['firstName'];
			}
			if (isset($pUserInfo['lastName'])){
				$wdUserArray['user_lastname']=$pUserInfo['lastName'];
			}
			if (isset($pUserInfo['password'])){
				$wdUserArray['user_password']=md5($pUserInfo['password']);
			}
			if (isset($pUserInfo['email'])){
				$wdUserArray['user_email_id']=$pUserInfo['email'];
			}
			if (isset($pUserInfo['status'])){
				$wdUserArray['user_status']=$pUserInfo['status'];
			}
			
			if (isset($pUserInfo['nickname'])){
				$wdUserDetailsArray['user_details_nickname']=$pUserInfo['nickname'];
			}
			if (isset($pUserInfo['phone'])){
				$wdUserDetailsArray['user_details_phone']=$pUserInfo['phone'];
			}
			if (isset($pUserInfo['address1'])){
				$wdUserDetailsArray['user_details_address1']=$pUserInfo['address1'];
			}
			if (isset($pUserInfo['address2'])){
				$wdUserDetailsArray['user_details_address2']=$pUserInfo['address2'];
			}
			if (isset($pUserInfo['city'])){
				$wdUserDetailsArray['user_details_city']=$pUserInfo['city'];
			}
			if (isset($pUserInfo['state'])){
				$wdUserDetailsArray['user_details_state']=$pUserInfo['state'];
			}
			if (isset($pUserInfo['country'])){
				$wdUserDetailsArray['user_details_country']=$pUserInfo['country'];
			}
			if (isset($pUserInfo['zip'])){
				$wdUserDetailsArray['user_details_zip']=$pUserInfo['zip'];
			}
			if (isset($pUserInfo['gender'])){
				$wdUserDetailsArray['user_details_gender']=$pUserInfo['gender'];
			}
			if (isset($pUserInfo['dob'])){
				$wdUserDetailsArray['user_details_dob']=$pUserInfo['dob'];
			}
			// if (isset($pUserInfo['facebookid'])){
			// 	$wdUserDetailsArray['user_details_fbid']=$pUserInfo['facebookid'];
			// }
			
			if ($uid > 0){
				$arrUser = $this->objPublic->getUserDetailsByUid($uid);
				if (count($arrUser) > 0){
					if (count($wdUserArray)>0){
						$con = array();
						$con['user_id'] = $uid;	
						$con['user_status'] = 1;	
						if($con['user_id'] !=0){
							$updateRecord = $this->objPublic->updateRecordQuery($wdUserArray, "users", $con);
							if ($updateRecord){
								$outArrUser['msg']='success';
							}else{
								$outArrUser['msg']='fail';
							}
						}
					}
					if (count($wdUserDetailsArray)>0){
							$con = array();
							$con['user_id'] = $uid;		
							if($con['user_id'] !=0){
								$updateRecord = $this->objPublic->updateRecordQuery($wdUserDetailsArray, "user_details", $con);
								if ($updateRecord){
									$outArrUser['msg']='success';
								}else{
									$outArrUser['msg']='fail';
								}
							}
						}
					
				}else{
					$outArrUser['msg']='fail';
				}
				
				
				 if($outArrUser['msg']=='success'){
					if (count($arrUser) > 0){
						$pUserInfo = array("uname" => $arrUser[0]['user_username']);
						$outArrUser = $this->modUserLoggedInDetails($pUserInfo);
						
					}else{
						$outArrUser['success'] = false;
					}
				}else{
					$outArrUser['success'] = false;
				}
				
			}else{
				$outArrUser['success'] = false;
			}

			echo json_encode($outArrUser);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserLogInAfterRegister($pUserInfo){
		try{
			global $config;			
			$outArrUserDetails = array();
			$outArrUserDetails = $this->modUserLoggedInDetails($pUserInfo);
			echo json_encode($outArrUserDetails); 
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserRegister($pUserInfo){
		try{
			//print_r($pUserInfo);
			global $config;	
			$arrUser = array();
			
			// $pUserInfo['email'] = '';
			// $pUserInfo['uname'] = '';
			// $pUserInfo['fbid'] = '';

			$outArrRegisterUser = array();
			$username = isset($pUserInfo['uname']) ? $pUserInfo['uname'] : '';
			$userFirstName = isset($pUserInfo['fname']) ? $pUserInfo['fname'] : '';
			$userLastName = isset($pUserInfo['lname']) ? $pUserInfo['lname'] : '';
			$userEmail = isset($pUserInfo['email']) ? $pUserInfo['email'] : '';
			$userAvatar = '';
			$userNickName = isset($pUserInfo['fullname']) ? $pUserInfo['fullname'] : '';
			$userGender = isset($pUserInfo['gender']) ? $pUserInfo['gender'] : '';
			$userDOB = isset($pUserInfo['dob']) ? $pUserInfo['dob'] : '';
			$userFBId = isset($pUserInfo['fbid']) ? $pUserInfo['fbid'] : '';
			$userPassword = isset($pUserInfo['password']) ? $pUserInfo['password'] : '';
			
			if (($username == "") && ($userEmail == "") && ($userFBId == "")) {
				$outArrRegisterUser['msg']="Failed. We couldn't register now. Please try again later.";
				$outArrRegisterUser['success']=false;
				echo json_encode($outArrRegisterUser);
				return false;
			}
			if ($userFBId!=""){
				if (($userEmail != "") && ($userFBId != "")) {
					$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingFB($pUserInfo);
				}
				
				if (count($arrUser) == 0){
					if ($userEmail != "") {
						$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingType($pUserInfo);
					}
				}
				if (count($arrUser) == 0){
					if (($userFirstName != "") && ($userLastName != "") && ($userFBId != "")) {
						$arrUser = $this->objPublic->checkIfUsernameExistsByFBIDAndNames($pUserInfo);
					}
				}
				if (count($arrUser) == 0){
					$tempUserName = $userEmail;
					if ($tempUserName !="") {
						$arrUser = $this->objPublic->checkIfUsernameExistsByUname($tempUserName);
					}
				}
			}else{
				if ($username !="") {
					$arrUser = $this->objPublic->checkIfUsernameExistsByUname($username);
				}
				if (count($arrUser) == 0){
					if ($userEmail !="") {
						$arrUser = $this->objPublic->checkIfUsernameExistsByEmail($userEmail);
					}
				}
			}
			
			// if (($userFBId!="") && ($username =="")){
			// 	echo 'modUserRegister';
			// 	$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingFB($pUserInfo);
			// 	echo 'checkIfUsernameExistsByUnameUsingFB';
			// 	print_r($arrUser);
			// 	echo '<br>';
			// 	$username = $userEmail;
			// 	if (count($arrUser) == 0){
			// 		if (count($arrUser) == 0){
			// 			$arrUser = $this->objPublic->checkIfUsernameExistsByFBID($pUserInfo);
			// 			echo 'checkIfUsernameExistsByFBID';
			// 			print_r($arrUser);
			// 			echo '<br>';
			// 		}
					
			// 		if (count($arrUser) == 0){
			// 			$arrUser = $this->objPublic->checkIfUsernameExistsByUnameUsingType($pUserInfo);
			// 			echo 'checkIfUsernameExistsByUnameUsingType';
			// 			print_r($arrUser);
			// 			echo '<br>';
			// 		}
			// 	}
			// }else{
			// 	$arrUser = $this->objPublic->checkIfUsernameExistsByUname($username);
			// 	echo 'checkIfUsernameExistsByUname';
			// 	print_r($arrUser);
			// 	echo '<br>';
			// }
			// print_r($arrUser);
			

			if (count($arrUser) > 0){
				$uid = isset($arrUser[0]['user_id']) ? $arrUser[0]['user_id'] : 0;
				if (($arrUser[0]['user_status']!=1) || ($userFBId !="")){
					$con = array();
					$wdArray = array();	
					$con['user_id'] = $uid;	
					if($con['user_id'] !=0){
						$wdArray['user_status'] = 1;
						$wdArray['user_username'] = $username;
						if ($arrUser[0]['user_email_id']=="") {
							$wdArray['user_email_id'] = $userEmail;
						}
						if ($userFBId!="") {
							$wdArray['user_fb_username'] = $arrUser[0]['user_username'];
							$wdArray['user_register_through'] = 1;
						}
						
						$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "users", $con);
						if ($updateRecord){
							$outArrRegisterUser['msg']='success';
							$outArrRegisterUser['success']=true;
							$wdArrayDetails = array();
							$conDetails['user_id'] = $uid;	
							if($conDetails['user_id'] !=0){
								$wdArrayDetails['user_reg_device'] = "IOS";
								$wdArrayDetails['user_details_fbid'] = $userFBId;
								$updateDetailsRecord = $this->objPublic->updateRecordQuery($wdArrayDetails, "user_details", $conDetails);
								if ($updateDetailsRecord){
									$outArrRegisterUser['msg']='success';
								}else{
									$outArrRegisterUser['msg']='fail';
								}
							}else{
								$outArrRegisterUser['msg']='fail';
							}
						}else{
							$outArrRegisterUser['msg']='fail';
						}
					}
				}else{
					$outArrRegisterUser['msg']='Username/Email already exist';
					if($userFBId!=""){
						$outArrRegisterUser = $this->modUserLoggedInDetailsByUID($uid);
					}
					echo json_encode($outArrRegisterUser);
					return false;
				}
			}else{
				if ($userPassword==""){
					$userPassword = $this->generatePassword(8,3);
				}
				$pArray = array();
				$pTableName = 'users';
				$pArray['user_group_id'] = 3;
				$pArray['user_firstname'] = $userFirstName;
				$pArray['user_lastname'] = $userLastName;
				$pArray['user_username'] = $username;
				$pArray['user_password'] = md5($userPassword);
				$pArray['user_email_id'] = $userEmail;
				if ($userFBId!=""){
					$pArray['user_register_through'] = 1;
				}else{
					$pArray['user_register_through'] = 0;
				}
				$pArray['user_status'] = 1;
				$pArray['user_created_date'] = 'NOW()';
				$insertRegisterUser = $this->objPublic->insertQuery($pArray, $pTableName, true);
				if ($insertRegisterUser > 0){
					$registeredUserId = $insertRegisterUser;
					$uid = $registeredUserId;
					$pArrayDetails = array();
					$pTableName = 'user_details';
					$pArrayDetails['user_id'] = $registeredUserId;
					$pArrayDetails['user_details_avatar'] = $userAvatar;
					$pArrayDetails['user_details_nickname'] = $userNickName;
					$pArrayDetails['user_details_gender'] = $userGender;
					$pArrayDetails['user_details_dob'] = $userDOB;
					$pArrayDetails['user_details_fbid'] = $userFBId;
					$pArrayDetails['user_reg_device'] = "IOS";

					$insertRegisterUserDetails = $this->objPublic->insertQuery($pArrayDetails, $pTableName, true);
					if ($insertRegisterUserDetails > 0){
						//$registeredUserDetailsId = $insertRegisterUserDetails;
						
						$mailto = $userEmail;
						$from_name = "SeeMore Interactive";
						$from_mail = $config['FROM_EMAIL'];
						$replyto = $config['FROM_EMAIL'];
						$subject = "SeeMore Interactive App Login Details";
						// $message = "<p>Dear ".$userFirstName." ".$userLastName.",</p>"."<p>Thank you for registering SeeMore App </p><p>Below are your login details</p><p>Username: ".$username."<BR>Password: ".$userPassword."</p><p>For any support with respect to your relationship with us, please do feel free to contact us:</p><p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p><p>SeeMore Interactive</p>";
						$message = "<p>Dear ".$userFirstName." ".$userLastName.",</p>"."<p>Thank you for registering SeeMore App </p><p>Below is your login username</p><p>Username: ".$username."</p><p>Need Support? Contact us:</p><p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p><p>SeeMore Interactive</p>";
						$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
						if ($mailStatus=="OK"){
							$outArrRegisterUser['msg'] = 'success';
						}else{
							if ($userFBId=='') {
								$outArrRegisterUser['msg'] = 'Error occured while sending an email. Please contact us.';
							}else{
								$outArrRegisterUser['msg'] = 'success';
							}
							
						}
						
						//Create a default User assets after registration
						$this->modUserDefaultAssets($registeredUserId);
					}else{
						$outArrRegisterUser['msg']='fail';
					}
				}else{
					$outArrRegisterUser['msg']='fail';
				}
			}
			// print_r($outArrRegisterUser);
			// echo 'uid: '.$uid;
			if($outArrRegisterUser['msg']=='success'){
				$outArrRegisterUser = $this->modUserLoggedInDetailsByUID($uid);
			}
			$arrUser = array();
			echo json_encode($outArrRegisterUser);
			//echo '{"user":{"id":9064,"password":"nerasu","username":"Vikram","firstName":"Vikram","lastName":"Nerasu","group":"1986"},"success":true}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modForgotPassword($pUserInfo){
		try{
			global $config;	
			$arrUser = array();
			$outUserForgotPassword = array();
			$uname = isset($pUserInfo['uname']) ? $pUserInfo['uname'] : '';
			if ($uname!=""){
				$arrUser = $this->objPublic->getUserDetailsByUname($uname);
				if (count($arrUser)>0){
					if ($arrUser[0]['user_status']==1){
						$userTempPassword = $this->generatePassword(8,3);
						$con = array();
						$wdArray = array();	
						$con['user_id'] = $arrUser[0]['user_id'];	
						if($con['user_id'] !=0){
							$wdArray['user_temp_password'] = $userTempPassword;
							$arrUser[0]['new_password'] = $userTempPassword;
							$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "users", $con);
							if ($updateRecord){
								if($this->sendForgotPassword($arrUser)){
									$outUserForgotPassword['msg']='success';
									$outUserForgotPassword['success']=true;
								}else{
									$outUserForgotPassword['msg']='We could not send email now. Please try again later.';
								}
								
							}else{
								$outUserForgotPassword['msg']='There was an error in the system. Please try again later.';
							}
						}else{
							$outUserForgotPassword['msg']="User doesn't exists. Please register.";
						}
					}else{
						$outUserForgotPassword['msg']='User is not active. Please contact us.';
					}
				}else{
					$outUserForgotPassword['msg']="User doesn't exists. Please register.";
				}
			}else{
				$outUserForgotPassword['msg']='Invalid username';
			}
			echo json_encode($outUserForgotPassword);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function sendForgotPassword($arrUserProfile){
		global $config;
		
		$mailto = $arrUserProfile[0]['user_email_id'];
		$from_name = "SeeMore Interactive";
		$from_mail = $config['FROM_EMAIL'];
		$replyto = $config['FROM_EMAIL'];
		$pass = $arrUserProfile[0]['new_password'];
		$subject = "Password Retrieval for your SeeMore Interactive Login";
		$message = "<p>Dear ".$arrUserProfile[0]['user_firstname']." ".$arrUserProfile[0]['user_lastname'].",</p>"."<p>This is to confirm that we've received a Forgot Password request for your account <strong>'".$arrUserProfile[0]['user_username']."'</strong>. We've created a new password listed below. You can use this to login to your SeeMore Interactive App.</p><p>User Name: ".$arrUserProfile[0]['user_username']."<br />New Password: $pass</p><p><u>Support:</u></p><p>For any support with respect to your relationship with us, please do feel free to contact us:</p><p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p><p>SeeMore Interactive</p>";
		$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
		if ($mailStatus=="OK"){
			$arrResult['msg'] = 'success';
			return true;
		}else{
			$arrResult['msg'] = 'Error occured while sending an email. Please contact us';
			return false;
		}
	}
	public function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
	
	public function mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message){

		//create a boundary string. It must be unique 
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "From: ".$from_name." <".$from_mail.">\r\n";
		$headers .= "Reply-To: ".$replyto."\r\n";
		//add boundary string and mime type specification
		$headers .= "Content-type:text/html; charset=iso-8859-1\r\n";


		//send the email
		$mail_sent = @mail( $mailto, $subject, $message, $headers );

		if ($mail_sent){
		 return "OK"; // or use booleans here
		} else {
			return "ERROR";
		}

	}

	public function modUserDefaultAssets($pUid){
		try{
			global $config;	
			$arrClosetByUID = array();
			$arrClosetByUID = $this->objPublic->getMyClosetByUid($pUid);
			if (count($arrClosetByUID) == 0) {
				$this->modDefaultCloset($pUid);
				$this->modDefaultMyOffers($pUid);
				$this->modDefaultWishlist($pUid);
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modDefaultCloset($pUid){
		try{
			global $config;	
			// echo 'modDefaultCloset';
			$pUserInfo = array();
			$pUserInfo['userId'] = $pUid;
			$pUserInfo['prodIds'] = "816|820|876|1423|1432|1436|6066|6071|6075|5891|5894|6108|6111";
			// $this->modUserAddMyCloset($userInfoArr);


			$outArrClosetInfo = array();
			$arrMyClosetProdIds = array();
			$uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrProdIds = isset($pUserInfo['prodIds']) ? $pUserInfo['prodIds'] : '';
			$arrProdIds = explode("|",$arrProdIds);
			for ($i=0;$i<count($arrProdIds); $i++){
				//echo "product Ids".$arrProdIds[$i];
				$pdId = isset($arrProdIds[$i]) ? $arrProdIds[$i] : 0;
				if ($uid!=0 && $pdId!=0){
					$pArray = array();
					$pTableName = 'closet';
					$pArray['user_id'] = $uid;
					$pArray['closet_created_date'] = 'NOW()';
					$pArray['pd_id'] = $pdId;
					$pArray['closet_selection_status'] = 1;
					$pArray['closet_status'] = 1;
					$insertCloset = $this->objPublic->insertQuery($pArray, $pTableName, true);
				}
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modDefaultMyOffers($pUid){
		try{
			global $config;	
			// echo 'modDefaultMyOffers';
			$pUserInfo = array();
			$pUserInfo['userId'] = $pUid;
			//Adore Product IDs - 12 products
			$pUserInfo['offerIds'] ="47|15";		
			
			$arrCheckForMyOffer = array();
			$arrCheckForMyOfferRef = array();
			$outResponse = array();
			
			$pUserId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrOfferIds = explode("|",$pUserInfo['offerIds']);
			//$arrOfferIds = array('13');
			// print_r($arrOfferIds);
			$pArray = array();
			$pTableName = 'my_offers';
			$pArray['my_offers_name'] = "My Offers";
			$pArray['user_id'] = $pUserId;
			$pArray['my_offers_created_date'] = 'NOW()';
			$pArray['my_offers_created_by_id'] = $pUserId;
			$pArray['my_offers_status'] = '1';

			$insertMyOffers = $this->objPublic->insertQuery($pArray, $pTableName, true);
			for ($i=0;$i<count($arrOfferIds);$i++){
				$pOfferID = $arrOfferIds[$i];
				// echo "offer ids : ".$pOfferID;
				if ($pUserId > 0){
					// echo "done on myoffers ".$insertMyOffers;
					if ($insertMyOffers>0){
						$pTableName = 'my_offers_reference';
						$pArrayRef['my_offers_id'] = $insertMyOffers;
						$pArrayRef['offer_id'] = $pOfferID; //Dynamic
						$pArrayRef['coupon_id'] = '0'; //Do it later
						$pArrayRef['my_offers_ref_created_date'] = 'NOW()';
						$pArrayRef['my_offers_tracking_id'] = '1'; //Do it later
						$pArrayRef['my_offers_ref_created_by_id'] = $pUserId; //user_id
						$pArrayRef['my_offers_ref_status'] = '1'; //Active
	
						$insertOfferRef = $this->objPublic->insertQuery($pArrayRef, $pTableName, true);
						
					}

				}

			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modDefaultWishlist($pUid){
		try{
			// echo "modDefaultWishlist";
			 global $config;
			 $outWishlistInfo = array();
			 $arrUserWishlistsByName = array();
			 $arrWishlistProds = array();
			 $arrWishlistProdsInGroup = array();
			 $arrWishlistUser = array();
			 
			 $wishlistName = "Sample";
			 // $arrWishlistProds = $pUserInfo['productIds'];
			
			$pArray = array();
			$pTableName = 'wishlist';
			$pArray['wishlist_name'] = $wishlistName;
			$pArray['user_id'] = $pUid;
			$pArray['wishlist_created_date'] = 'NOW()';
			$pArray['wishlist_created_by'] = $pUid;
			$pArray['wishlist_status'] = '1';
			$pArray['wishlist_tracking_id'] = '0';

			$insertWishlist = $this->objPublic->insertQuery($pArray, $pTableName, true);
			if ($insertWishlist > 0){
				$wishlistid = $insertWishlist;
			}

			$arrWishlistProds = array('816','6066','6111');
			//echo "count of ".count($arrWishlistProds);
			if ($wishlistid > 0) {
				for($i=0;$i<count($arrWishlistProds);$i++){
					$checkWishlistItem = array();
					$pArrayDetails = array();
					$pTableName = 'wishlist_details';
					$pArrayDetails['wishlist_id'] = $wishlistid;
					$pArrayDetails['pd_id'] = $arrWishlistProds[$i];
					$pArrayDetails['wishlist_details_status'] = '1';

					$insertWishlistDetails = $this->objPublic->insertQuery($pArrayDetails, $pTableName, true);
				}
			}
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	// public function modUserAddMyClosetDefault($pUserInfo){
	// 	try{
	// 		$outArrClosetInfo = array();
	// 		$arrMyClosetProdIds = array();
	// 		$uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
	// 		$arrProdIds = isset($pUserInfo['prodIds']) ? $pUserInfo['prodIds'] : '';
	// 		//$arrProdIds = "815|816|819|820|821|822|870|871|872|874|875|876";
	// 		$arrProdIds = explode("|",$arrProdIds);
	// 		for ($i=0;$i<count($arrProdIds); $i++){
	// 			//echo "product Ids".$arrProdIds[$i];
	// 			$pdId = isset($arrProdIds[$i]) ? $arrProdIds[$i] : 0;
	// 			$checkClosetExist = $this->objPublic->checkIfClosetExistsByUidDefault($uid,$pdId);
	// 			if ($uid!=0 && $pdId!=0){
	// 			if (count($checkClosetExist)>0){
	// 				$con = array();
	// 				$wdArray = array();	
	// 				$con['pd_id'] = $pdId;	
	// 				$con['user_id'] = $uid;	
	// 				if($con['pd_id'] !=0){
	// 					$wdArray['closet_status'] = 1;
	// 					$wdArray['closet_updated_date'] = 'NOW()';
	// 					$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "closet_testing", $con);
	// 					if ($updateRecord){
	// 						$outArrClosetInfo['msg']='success';
	// 					}else{
	// 						$outArrClosetInfo['msg']='fail';
	// 					}
	// 				}
	// 			}else{
	// 				$pArray = array();
	// 				$pTableName = 'closet_testing';
	// 				$pArray['user_id'] = $uid;
	// 				$pArray['closet_created_date'] = 'NOW()';
	// 				$pArray['pd_id'] = $pdId;
	// 				$pArray['closet_selection_status'] = 1;
	// 				$pArray['closet_status'] = 1;
	
	// 				$insertCloset = $this->objPublic->insertQuery($pArray, $pTableName, true);
	// 				if ($insertCloset){
	// 					$outArrClosetInfo['msg']='success';
	// 				}else{
	// 					$outArrClosetInfo['msg']='fail';
	// 				}
	// 				}
	// 			}else{
	// 				$outArrClosetInfo['msg']='fail';
	// 			}
	// 		}
	// 		echo json_encode($outArrClosetInfo);
	// 	}
	// 	catch ( Exception $e ) {
	// 		echo 'Message: ' .$e->getMessage();
	// 	}
	// }

	public function modUserWishlists($pUserInfo){
		try{
			//print_r($pUserInfo);
			 global $config;
			 $outArrUserWishlists = array();
			 $arrUserWishlists = array();
			 $arrWishlistProds = array();
			 $arrWishlistProdDetails = array();
			 
			 $uid = isset($pUserInfo['userid']) ? $pUserInfo['userid'] : 0;
			 $arrUserWishlists = $this->objPublic->getUserWishlistsByUid($uid);
			//print_r($arrUserWishlists);
			//echo "count of arrUserWishlists : ".count($arrUserWishlists);
			for($i=0;$i<count($arrUserWishlists);$i++){
				$outArrUserWishlists[$i]['name']= isset($arrUserWishlists[$i]['wishlist_name']) ? $arrUserWishlists[$i]['wishlist_name'] : '';
				$outArrUserWishlists[$i]['id']=isset($arrUserWishlists[$i]['wishlist_id']) ? $arrUserWishlists[$i]['wishlist_id'] : 0;
				$pWishlistIds = isset($arrUserWishlists[$i]['wishlist_id']) ? $arrUserWishlists[$i]['wishlist_id'] : 0;
				
				$arrWishlistProds = $this->objPublic->getWishlistsProdIDs($pWishlistIds);
				for($j=0;$j<count($arrWishlistProds);$j++){
					//echo "<BR>arrWishlistProds: ".$arrWishlistProds[$j]['pd_id'];
					$arrWishlistProdDetails[$j] = $this->getProductDetails($arrWishlistProds[$j]['pd_id']);
				}
				$outArrUserWishlists[$i]['products'] =  $arrWishlistProdDetails;
			}
			echo json_encode($outArrUserWishlists);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserWishlistsUpdate($pUserInfo){
		try{
			//print_r($pUserInfo);
			 global $config;
			 $outWishlistInfo = array();
			 $arrUserWishlistsByName = array();
			 $arrWishlistProds = array();
			 $arrWishlistProdsInGroup = array();
			 $arrWishlistUser = array();
			 
			 $wishlistName = isset($pUserInfo['name']) ? $pUserInfo['name'] : "";
			 $wishlistid = isset($pUserInfo['wishListId']) ? $pUserInfo['wishListId'] : 0;
			 $uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			 $arrWishlistProds = $pUserInfo['productIds'];
			//$arrWishlistProds = array('122','1563');
			//echo "count of ".count($arrWishlistProds);
			$arrUserWishlistsByName = $this->objPublic->getWishlistByName($wishlistName);
			if (count($arrUserWishlistsByName)==0){
				$pArray = array();
				$pTableName = 'wishlist';
				$pArray['wishlist_name'] = $wishlistName;
				$pArray['user_id'] = $uid;
				$pArray['wishlist_created_date'] = 'NOW()';
				$pArray['wishlist_created_by'] = $uid;
				$pArray['wishlist_status'] = '1';
				$pArray['wishlist_tracking_id'] = '0';
	
				$insertWishlist = $this->objPublic->insertQuery($pArray, $pTableName, true);
				if ($insertWishlist > 0){
					$wishlistid = $insertWishlist;
				}

			 }else{
			 	$wishlistStatus = isset($arrUserWishlistsByName[0]['wishlist_status']) ? ($arrUserWishlistsByName[0]['wishlist_status']) : 0;
			 	if ($wishlistStatus == 2){
			 		$con = array();
					$wdArray = array();	
					$con['wishlist_name'] = $wishlistName;	
					if($con['wishlist_name'] !=""){
						$wdArray['wishlist_status'] = 1;
						$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "wishlist", $con);
						if ($updateRecord){
							$outWishlistInfo['msg']='success';
						}else{
							$outWishlistInfo['msg']='fail';
						}
					}
			 	}

				$wishlistid = isset($arrUserWishlistsByName[0]['wishlist_id']) ? ($arrUserWishlistsByName[0]['wishlist_id']) : 0;
				for($i=0;$i<count($arrWishlistProds);$i++){
					$checkWishlistItem = array();
					$checkWishlistItem = $this->objPublic->checkIfWishlistProdExists($wishlistid, $arrWishlistProds[$i]);
					if (count($checkWishlistItem)==0){
						$pArrayDetails = array();
						$pTableName = 'wishlist_details';
						$pArrayDetails['wishlist_id'] = $wishlistid;
						$pArrayDetails['pd_id'] = $arrWishlistProds[$i];
						$pArrayDetails['wishlist_details_status'] = '1';

						$insertMyOffers = $this->objPublic->insertQuery($pArrayDetails, $pTableName, true);
					}else{
						$conDetails = array();
						$wdArrayDetails = array();	
						$conDetails['wishlist_id'] = $wishlistid;
						$conDetails['pd_id'] = $arrWishlistProds[$i];	
						if($conDetails['wishlist_id'] !=""){
							$wdArrayDetails['wishlist_details_status'] = 1;
							$updateRecord = $this->objPublic->updateRecordQuery($wdArrayDetails, "wishlist_details", $conDetails);
							if ($updateRecord){
								$outWishlistInfo['msg']='success';
							}else{
								$outWishlistInfo['msg']='fail';
							}
						}
					}
				}
			 	
			 }
			$arrUserWishlistsByName = $this->objPublic->getWishlistById($wishlistid);
			$outWishlistInfo['name'] = isset($arrUserWishlistsByName[0]['wishlist_name']) ? $arrUserWishlistsByName[0]['wishlist_name'] : '';
			$outWishlistInfo['id'] = isset($arrUserWishlistsByName[0]['wishlist_id']) ? $arrUserWishlistsByName[0]['wishlist_id'] : 0;
			
			$arrWishlistProdsInGroup = $this->objPublic->getWishlistProds($wishlistid);
			$arrProdGroup = array();
			$arrProdGroup = explode(",", $arrWishlistProdsInGroup[0]['productIds']);
			$outWishlistInfo['products'] = $arrProdGroup;
			$outWishlistInfo['shared'] = false;
			
			$arrWishlistUser = $this->modUserDetails($uid);
			//print_r($arrWishlistUser);
			$outWishlistInfo['publicUser'] = $arrWishlistUser;
			
			 echo json_encode($outWishlistInfo);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserWishlistsRemove($pUserInfo){
		try{
			//print_r($pUserInfo);
			 global $config;
			 $outWishlistInfo = array();

			 $wishlistId = isset($pUserInfo['wishlistId']) ? $pUserInfo['wishlistId'] : 0;
			 $uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
		
			$con = array();
			$wdArray = array();	
			$con['wishlist_id'] = $wishlistId;
			$con['user_id'] = $uid;	
			if($con['wishlist_id'] !=""){
				$wdArray['wishlist_status'] = 2;
				$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "wishlist", $con);
				if ($updateRecord){
					$conDetails = array();
					$wdArrayDetails = array();	
					$conDetails['wishlist_id'] = $wishlistId;	
					if($conDetails['wishlist_id'] !=""){
						$wdArrayDetails['wishlist_details_status'] = 2;
						$updateRecord = $this->objPublic->updateRecordQuery($wdArrayDetails, "wishlist_details", $conDetails);
						if ($updateRecord){
							$outWishlistInfo['msg']='success';
						}else{
							$outWishlistInfo['msg']='fail';
						}
					}
					
				}else{
					$outWishlistInfo['msg']='fail';
				}
			}
			 echo json_encode($outWishlistInfo);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserWishlistItemRemove($pUserInfo){
		try{
			//print_r($pUserInfo);
			 global $config;
			 $outWishlistInfo = array();

			 $wishlistId = isset($pUserInfo['wishlistId']) ? $pUserInfo['wishlistId'] : 56;
			 $uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 2;
			 $wishlistProductId = isset($pUserInfo['wishlistProductId']) ? $pUserInfo['wishlistProductId'] : 5892;

			$con = array();
			$wdArray = array();	
			$con['wishlist_id'] = $wishlistId;
			// $con['user_id'] = $uid;	
			$con['pd_id'] = $wishlistProductId;	
			// print_r($con);
			if($con['wishlist_id'] !=""){
				$wdArray['wishlist_details_status'] = 2;
				$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "wishlist_details", $con);
				if ($updateRecord){
					$outWishlistInfo['msg']='success';
				}else{
					$outWishlistInfo['msg']='fail';
				}
			}else{
				$outWishlistInfo['msg']='fail';
			}
			echo json_encode($outWishlistInfo);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserDetails($pUid){
		try{
			global $config;
			$outArrUserDetails = array();
			$arrUserDetails = array();
			
			$arrUserDetails = $this->objPublic->getUserDetails($pUid);
			$outArrUserDetails['id'] = isset($arrUserDetails[0]['user_id']) ? $arrUserDetails[0]['user_id'] : 0;
			$outArrUserDetails['username'] = isset($arrUserDetails[0]['user_username']) ? $arrUserDetails[0]['user_username'] : '';
			$outArrUserDetails['firstName'] = isset($arrUserDetails[0]['user_firstname']) ? $arrUserDetails[0]['user_firstname'] : '';
			$outArrUserDetails['lastName'] = isset($arrUserDetails[0]['user_lastname']) ? $arrUserDetails[0]['user_lastname'] : '';
			
			return $outArrUserDetails;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserMyOffers($pUserInfo){
		try{
			//print_r($pUserInfo);
			 global $config;
			 $outArrMyOffers = array();
			 $arrMyOfferIds = array();
			 $arrMyOfferDetails = array();
			 $arrWishlistProdDetails = array();
			 
			 $uid = isset($pUserInfo['userid']) ? $pUserInfo['userid'] : 0;
			 $arrMyOfferIds = $this->objPublic->getMyOfferIdByUid($uid);
			for($i=0;$i<count($arrMyOfferIds);$i++){
				$outArrMyOffers['name']= isset($arrMyOfferIds[$i]['my_offers_name']) ? $arrMyOfferIds[$i]['my_offers_name'] : '';
				$myOfferId = isset($arrMyOfferIds[$i]['my_offers_id']) ? $arrMyOfferIds[$i]['my_offers_id'] : 0;
				$outArrMyOffers['id'] = $myOfferId;
				$arrMyOfferDetails = $this->getMyOfferDetails($myOfferId);
			 	$outArrMyOffers['products'] = $arrMyOfferDetails;
			 }
			echo json_encode($outArrMyOffers);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modOffersById($pUserInfo){
		try{
			global $config;
			$outArrOffers = array();
			$arrOfferIds = array();
			$arrOfferDetails = array();
			//print_r($pUserInfo);
			//echo "userinfo of ".$pUserInfo['offerIds'];
			$arrOfferIds = explode("|",$pUserInfo['offerIds']);

			for ($i=0;$i<count($arrOfferIds);$i++){
				//echo "print offer ids ".$arrOfferIds[$i];
				$arrOfferDetails = $this->getOfferDetails($arrOfferIds[$i]);
				$outArrOffers[$i] = $arrOfferDetails;
			}
			echo json_encode($outArrOffers);
			//echo '{"active":"1","id":"46","title":"Electronics","barcode":"","color":"","category":"","description":"Electronics: Brand Name Cell Phones, Computers, TVs & More","hideBackground":0,"html":"","image":"http:\/\/arapps.vziom.com\/files\/clients\/2101\/products\/aug31.png","shortDescription":"","url":"","offerValidTo":"2014-09-20 00:00:00","offerValidFrom":"2013-09-20 11:46:24","offerReminderDays":"1,2","offerIsAllDayEvent":0,"price":0,"clientID":"2101","clientName":"Home Shopping Network","related":[]},{"active":"1","id":"45","title":"Concierge Collection","barcode":"","color":"","category":"","description":"Concierge Collection - Luxury Hotel Inspired Bedding","hideBackground":0,"html":"","image":"http:\/\/arapps.vziom.com\/files\/clients\/2101\/products\/aug30.png","shortDescription":"","url":"","offerValidTo":"2014-09-20 00:00:00","offerValidFrom":"2013-09-20 11:46:24","offerReminderDays":"1,2","offerIsAllDayEvent":0,"price":0,"clientID":"2101","clientName":"Home Shopping Network","related":[]}';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserAddMyOffers($pUserInfo){
		try{
			//print_r($pUserInfo);
			global $config;
			$arrCheckForMyOffer = array();
			$arrCheckForMyOfferRef = array();
			$outResponse = array();
			
			$pUserId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrOfferIds = explode("|",$pUserInfo['offerIds']);
			//$arrOfferIds = array('13');
			for ($i=0;$i<count($arrOfferIds);$i++){
				$pOfferID = $arrOfferIds[$i];
				//echo "offer ids : ".$pOfferID;
				if ($pUserId > 0){
					$arrCheckForMyOffer = $this->objPublic->checkIfMyOfferExists($pUserId);
					//print_r($arrCheckForMyOffer);
					if (count($arrCheckForMyOffer)>0){
						$myOfferId = isset($arrCheckForMyOffer[0]['my_offers_id']) ? $arrCheckForMyOffer[0]['my_offers_id'] : 0;
						$arrCheckForMyOfferRef = $this->objPublic->checkIfMyOfferRefExists($myOfferId, $pOfferID);
						if (count($arrCheckForMyOfferRef)>0){
							//echo " <BR> needs to update<BR>";
							$con = array();
							$wdArray = array();	
							$con['my_offers_id'] = isset($arrCheckForMyOfferRef[0]['my_offers_id']) ? $arrCheckForMyOfferRef[0]['my_offers_id'] : 0;	
							$con['offer_id'] = isset($arrCheckForMyOfferRef[0]['offer_id']) ? $arrCheckForMyOfferRef[0]['offer_id'] : 0;
							$con['my_offers_ref_status!'] = 3;
							if($con['my_offers_id'] !=0){
								$wdArray['my_offers_ref_status'] = 1;
								$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "my_offers_reference", $con);
								if ($updateRecord){
									$outResponse['status']="success";
								}else{
									$outResponse['status']="fail";
								}
							}
						}else{
							if ($myOfferId > 0){
								//echo "<BR>needs to insert new myoffer ref<BR>";
								$pArray = array();
								$pTableName = 'my_offers_reference';
								$pArray['my_offers_id'] = $myOfferId;
								$pArray['offer_id'] = $pOfferID; //Dynamic
								$pArray['coupon_id'] = '0'; //Do it later
								$pArray['my_offers_ref_created_date'] = 'NOW()';
								$pArray['my_offers_tracking_id'] = '1'; //Do it later
								$pArray['my_offers_ref_created_by_id'] = isset($arrCheckForMyOffer[0]['user_id']) ? $arrCheckForMyOffer[0]['user_id'] : 0; //user_id
								$pArray['my_offers_ref_status'] = '1'; //Active
			
								$insertResults = $this->objPublic->insertQuery($pArray, $pTableName, true);
								if ($insertResults > 0){
									$outResponse['status']="success";
								}else{
									$outResponse['status']="fail";
								}
								//echo "<BR>one adding new reference<BR> ".$insertResults;
							}
						}
						//print_r($arrCheckForMyOfferRef);
					}else{
						$pArray = array();
						$pTableName = 'my_offers';
						$pArray['my_offers_name'] = "My Offers";
						$pArray['user_id'] = $pUserId;
						$pArray['my_offers_created_date'] = 'NOW()';
						$pArray['my_offers_created_by_id'] = $pUserId;
						$pArray['my_offers_status'] = '1';
			
						$insertMyOffers = $this->objPublic->insertQuery($pArray, $pTableName, true);
						//echo "done on myoffers ".$insertMyOffers;
						if ($insertMyOffers>0){
							$pTableName = 'my_offers_reference';
							$pArrayRef['my_offers_id'] = $insertMyOffers;
							$pArrayRef['offer_id'] = $pOfferID; //Dynamic
							$pArrayRef['coupon_id'] = '0'; //Do it later
							$pArrayRef['my_offers_ref_created_date'] = 'NOW()';
							$pArrayRef['my_offers_tracking_id'] = '1'; //Do it later
							$pArrayRef['my_offers_ref_created_by_id'] = $pUserId; //user_id
							$pArrayRef['my_offers_ref_status'] = '1'; //Active
		
							$insertOfferRef = $this->objPublic->insertQuery($pArrayRef, $pTableName, true);
							if ($insertOfferRef>0){
								$outResponse['status']="success";
								//echo "success in inserting complete my offers record";
							}else{
								$outResponse['status']="fail";
								//echo "failure to insert new record";
							}
						}
					}
				}else{
					$outResponse['status']="fail";
				}
			}
			echo json_encode($outResponse);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserRemoveMyOffers($pUserInfo){
		try{
			//print_r($pUserInfo);
			global $config;
			$arrCheckForMyOffer = array();
			$arrCheckForMyOfferRef = array();
			$outResponse = array();
			
			$pUserId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrOfferIds = explode("|",$pUserInfo['offerIds']);
			//$arrOfferIds = array('45');
			for ($i=0;$i<count($arrOfferIds);$i++){
				$pOfferID = $arrOfferIds[$i];
				//echo "offer ids : ".$pOfferID;
				if ($pUserId > 0){
					$arrCheckForMyOffer = $this->objPublic->checkIfMyOfferExists($pUserId);
					//print_r($arrCheckForMyOffer);
					if (count($arrCheckForMyOffer)>0){
						$myOfferId = isset($arrCheckForMyOffer[0]['my_offers_id']) ? $arrCheckForMyOffer[0]['my_offers_id'] : 0;
						$arrCheckForMyOfferRef = $this->objPublic->checkIfMyOfferRefExists($myOfferId, $pOfferID);
						if (count($arrCheckForMyOfferRef)>0){
							//echo " <BR> needs to update<BR>";
							$con = array();
							$wdArray = array();	
							$con['my_offers_id'] = isset($arrCheckForMyOfferRef[$i]['my_offers_id']) ? $arrCheckForMyOfferRef[$i]['my_offers_id'] : 0;	
							$con['offer_id'] = isset($arrCheckForMyOfferRef[$i]['offer_id']) ? $arrCheckForMyOfferRef[$i]['offer_id'] : 0;
							if($con['my_offers_id'] !=0){
								$wdArray['my_offers_ref_status'] = 2;
								$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "my_offers_reference", $con);
								if ($updateRecord){
									$outResponse['status']="success";
								}else{
									$outResponse['status']="fail";
								}
							}
						}
					}
				}else{
					$outResponse['status']="fail";
				}
			}
			echo json_encode($outResponse);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserRedeemMyOffers($pUserInfo){
		try{
			//print_r($pUserInfo);
			global $config;
			$arrCheckForMyOffer = array();
			$arrCheckForMyOfferRef = array();
			$outResponse = array();
			
			$pUserId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrOfferIds = explode("|",$pUserInfo['offerIds']);
			//$arrOfferIds = array('45');
			for ($i=0;$i<count($arrOfferIds);$i++){
				$pOfferID = $arrOfferIds[$i];
				//echo "offer ids : ".$pOfferID;
				if ($pUserId > 0){
					$arrCheckForMyOffer = $this->objPublic->checkIfMyOfferExists($pUserId);
					//print_r($arrCheckForMyOffer);
					if (count($arrCheckForMyOffer)>0){
						$myOfferId = isset($arrCheckForMyOffer[0]['my_offers_id']) ? $arrCheckForMyOffer[0]['my_offers_id'] : 0;
						$arrCheckForMyOfferRef = $this->objPublic->checkIfMyOfferRefExists($myOfferId, $pOfferID);
						if (count($arrCheckForMyOfferRef)>0){
							//echo " <BR> needs to update<BR>";
							$con = array();
							$wdArray = array();	
							$con['my_offers_id'] = isset($arrCheckForMyOfferRef[$i]['my_offers_id']) ? $arrCheckForMyOfferRef[$i]['my_offers_id'] : 0;	
							$con['offer_id'] = isset($arrCheckForMyOfferRef[$i]['offer_id']) ? $arrCheckForMyOfferRef[$i]['offer_id'] : 0;
							if($con['my_offers_id'] !=0){
								$wdArray['my_offers_ref_status'] = 3;
								$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "my_offers_reference", $con);
								if ($updateRecord){
									$outResponse['status']="success";
								}else{
									$outResponse['status']="fail";
								}
							}
						}
					}
				}else{
					$outResponse['status']="fail";
				}
			}
			echo json_encode($outResponse);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserClientStores($pClientInfo){
		try{
// 			print_r($pClientInfo);
			$pClientId = isset($pClientInfo['clientid']) ? $pClientInfo['clientid'] : 0;
 			//echo $pClientId = 30;
			global $config;
			$arrClientStores = array();
			$outArrClientStores = array();
			$arrClientStores = $this->objPublic->getClientStores($pClientId);
			for($j=0;$j<count($arrClientStores);$j++){
				$outArrClientStores[$j]['storeId'] = isset($arrClientStores[$j]['store_id']) ? $arrClientStores[$j]['store_id'] : 0;
				$outArrClientStores[$j]['storeCode'] = isset($arrClientStores[$j]['store_code']) ? $arrClientStores[$j]['store_code'] : "";
				$outArrClientStores[$j]['storeName'] = isset($arrClientStores[$j]['store_name']) ? $arrClientStores[$j]['store_name'] : "";
				$outArrClientStores[$j]['storeSearchType'] = isset($arrClientStores[$j]['store_search_type']) ? $arrClientStores[$j]['store_search_type'] : "";
				$outArrClientStores[$j]['clientId'] = isset($arrClientStores[$j]['client_id']) ? $arrClientStores[$j]['client_id'] : 0;
				$outArrClientStores[$j]['latitude'] = isset($arrClientStores[$j]['latitude']) ? $arrClientStores[$j]['latitude'] : 0;
				$outArrClientStores[$j]['longitude'] = isset($arrClientStores[$j]['longitude']) ? $arrClientStores[$j]['longitude'] : 0;
				$outArrClientStores[$j]['address1'] = isset($arrClientStores[$j]['address_1']) ? $arrClientStores[$j]['address_1'] : "";
				$outArrClientStores[$j]['address2'] = isset($arrClientStores[$j]['address_2']) ? $arrClientStores[$j]['address_2'] : "";
				$outArrClientStores[$j]['phone'] = isset($arrClientStores[$j]['phone']) ? $arrClientStores[$j]['phone'] : "";
				$outArrClientStores[$j]['city'] = isset($arrClientStores[$j]['city']) ? $arrClientStores[$j]['city'] : "";
				$outArrClientStores[$j]['state'] = isset($arrClientStores[$j]['state']) ? $arrClientStores[$j]['state'] : "";
				$outArrClientStores[$j]['zip'] = isset($arrClientStores[$j]['zip']) ? $arrClientStores[$j]['zip'] : "";
				$outArrClientStores[$j]['email'] = isset($arrClientStores[$j]['email']) ? $arrClientStores[$j]['email'] : "";
				$outArrClientStores[$j]['storeTriggerThreshold'] = isset($arrClientStores[$j]['store_trigger_threshold']) ? $arrClientStores[$j]['store_trigger_threshold'] : 0;
				$outArrClientStores[$j]['storeAvailableOfferIds'] = isset($arrClientStores[$j]['store_available_offerids']) ? $arrClientStores[$j]['store_available_offerids'] : "";
				$outArrClientStores[$j]['storeUpdateThreshold'] = isset($arrClientStores[$j]['store_update_threshold']) ? $arrClientStores[$j]['store_update_threshold'] : 0;
				$outArrClientStores[$j]['storeNotifyMsg'] = isset($arrClientStores[$j]['store_notify_msg']) ? $arrClientStores[$j]['store_notify_msg'] : 0;
				$outArrClientStores[$j]['clientName'] = isset($arrClientStores[$j]['name']) ? $arrClientStores[$j]['name'] : "";
		
			}
			echo json_encode($outArrClientStores);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getMyOfferDetails($pOfferID){
		try{
			global $config;			
			$arrProdDetails = array();
			$outArrProdDetails = array();
			$arrClientDetails = array();
			$outArrMyOfferDetails = array();
			$arrClientForPublicClients = array();
			$arrMyOfferDetails = array();
			$arrMyOfferInfo = array();
			$arrRelatedOffers = array();
			$arrClientDetails = array();
			
			$arrProdDetails = $this->objPublic->getMyOfferDetailsByOffID($pOfferID);
			//$outArrMyOfferDetails = $arrProdDetails;
			//print_r($arrProdDetails);
			for($i=0;$i<count($arrProdDetails);$i++){
				$outArrMyOfferDetails[$i]["active"] = isset($arrProdDetails[$i]['offer_status']) ? $arrProdDetails[$i]['offer_status'] : "";
				$outArrMyOfferDetails[$i]["id"] = isset($arrProdDetails[$i]['offer_id']) ? $arrProdDetails[$i]['offer_id'] : "";
				$outArrMyOfferDetails[$i]["title"] = isset($arrProdDetails[$i]['offer_name']) ? $arrProdDetails[$i]['offer_name'] : "";
				$outArrMyOfferDetails[$i]["barcode"] = "";
				$outArrMyOfferDetails[$i]["color"] = "";
				$outArrMyOfferDetails[$i]["category"] = "";
				$outArrMyOfferDetails[$i]["description"] = isset($arrProdDetails[$i]['offer_description']) ? $arrProdDetails[$i]['offer_description'] : "";
				$outArrMyOfferDetails[$i]["hideBackground"] = isset($arrProdDetails[$i]['pd_hide_bg_image']) ? $arrProdDetails[$i]['pd_hide_bg_image'] : 0;
				$outArrMyOfferDetails[$i]["html"] = "";
				$prodImage = isset($arrProdDetails[$i]['offer_image']) ? $arrProdDetails[$i]['offer_image'] : "";
				$outArrMyOfferDetails[$i]["image"] = str_replace("{client_id}",$arrProdDetails[$i]['client_id'],$config['files']['products']).$prodImage;
				
				$prodBarcodeImage = isset($arrProdDetails[$i]['offer_barcode_image']) ? $arrProdDetails[$i]['offer_barcode_image'] : "";
				if ($prodBarcodeImage !=""){
					$outArrMyOfferDetails[$i]["barcodeImage"] = str_replace("{client_id}",$arrProdDetails[$i]['client_id'],$config['files']['products']).$prodBarcodeImage;
				}else{
					$outArrMyOfferDetails[$i]["barcodeImage"] ="";
				}
				$outArrMyOfferDetails[$i]["barcodeNumber"] = isset($arrProdDetails[$i]['offer_barcode_number']) ? $arrProdDetails[$i]['offer_barcode_number'] : 0;
				$outArrMyOfferDetails[$i]["isOfferSharable"] = isset($arrProdDetails[$i]['offer_is_sharable']) ? $arrProdDetails[$i]['offer_is_sharable'] : 0;
				
				$outArrMyOfferDetails[$i]["shortDescription"] = isset($arrProdDetails[$i]['offer_short_description']) ? $arrProdDetails[$i]['offer_short_description'] : "";
				$outArrMyOfferDetails[$i]["offerPurchaseUrl"] = isset($arrProdDetails[$i]['offer_purchase_url']) ? $arrProdDetails[$i]['offer_purchase_url'] : "";
				$outArrMyOfferDetails[$i]["offerButtonName"] = isset($arrProdDetails[$i]['offer_button_name']) ? $arrProdDetails[$i]['offer_button_name'] : "";
			
				$outArrMyOfferDetails[$i]["url"] = "";
				$outArrMyOfferDetails[$i]["offerValidTo"] = isset($arrProdDetails[$i]['offer_valid_to']) ? $arrProdDetails[$i]['offer_valid_to'] : "";
				$outArrMyOfferDetails[$i]["offerValidFrom"] = isset($arrProdDetails[$i]['offer_valid_from']) ? $arrProdDetails[$i]['offer_valid_from'] : "";
				
				$checkIfCalendarEvent = isset($arrProdDetails[$i]['offer_is_calendar_based']) ? $arrProdDetails[$i]['offer_is_calendar_based'] : 0;
				$outArrMyOfferDetails[$i]["offerReminderDays"]="";
				$outArrMyOfferDetails[$i]["offerIsAllDayEvent"]="0";
				$arrMyOfferInfo = $this->objPublic->getOfferInfoByOfferId($outArrMyOfferDetails[$i]["id"]);
				if ($checkIfCalendarEvent==1){
					$outArrMyOfferDetails[$i]["offerReminderDays"] = isset($arrMyOfferInfo[0]['offer_info_reminder_days']) ? $arrMyOfferInfo[0]['offer_info_reminder_days'] : 0;
					$outArrMyOfferDetails[$i]["offerIsAllDayEvent"] = isset($arrMyOfferInfo[0]['offer_info_event_allday']) ? $arrMyOfferInfo[0]['offer_info_event_allday'] : 0;
				}
				// print_r($arrMyOfferInfo);
				// $offerDiscType = isset($arrProdDetails[$i]['offer_discount_type']) ? $arrProdDetails[$i]['offer_discount_type'] : "";
				// if ($offerDiscType=="A"){
				// 	$outArrMyOfferDetails[$i]["price"] = isset($arrProdDetails[$i]['offer_discount_value']) ? $arrProdDetails[$i]['offer_discount_value'] : 0;
				// }else{
				// 	$outArrMyOfferDetails[$i]["price"] = 0;
				// }
				// $outArrMyOfferDetails[$i]["price"] = intVal($outArrMyOfferDetails[$i]["price"]);
				$outArrMyOfferDetails[$i]["price"] = isset($arrMyOfferInfo[0]['offer_discount_value']) ? $arrMyOfferInfo[0]['offer_discount_value'] : '';
				$outArrMyOfferDetails[$i]["offerDiscType"] = isset($arrMyOfferInfo[0]['offer_discount_type']) ? $arrMyOfferInfo[0]['offer_discount_type'] : '';
				$arrClientForPublicClients = $outArrMyOfferDetails;
				$clientID = isset($arrProdDetails[$i]['client_id']) ? $arrProdDetails[$i]['client_id'] : 0;
				$outArrMyOfferDetails[$i]["clientID"] = $clientID;
				$outArrMyOfferDetails[$i]["clientName"] = isset($arrProdDetails[$i]['name']) ? $arrProdDetails[$i]['name'] : "";
				$arrClientDetails = $this->objPublic->getCurrencyCodeByClientId($clientID);
				if (count($arrClientDetails)>0) {
					$outArrMyOfferDetails[$i]["offerCurrencyCode"] = isset($arrClientDetails[0]['client_details_currency_code']) ? $arrClientDetails[0]['client_details_currency_code'] : 'USD';
				}else{
					$outArrMyOfferDetails[$i]["offerCurrencyCode"] = 'USD';
				}
				
				// $arrRelatedOffers = $this->objPublic->getRelatedOffersById($pOfferID);
				// $outArrMyOfferDetails[$i]["related"][0] = isset($arrRelatedOffers[0]['offerRelated']) ? $arrRelatedOffers[0]['offerRelated'] : "";

				$arrRelatedOffers = $this->objPublic->getRelatedOffersById($outArrMyOfferDetails[$i]["id"]);
				$offerRelated = isset($arrRelatedOffers[0]['offerRelated']) ? $arrRelatedOffers[0]['offerRelated'] : "";
				
				$arrOfferRelated = explode(",", $offerRelated);
				$outArrMyOfferDetails[$i]["related"]  = $arrOfferRelated;
				$offerProdRelated = isset($arrRelatedOffers[0]['prodRelated']) ? $arrRelatedOffers[0]['prodRelated'] : "";
				$arrOfferProdRelated = explode(",", $offerProdRelated);
				$outArrMyOfferDetails[$i]["relatedProducts"]  = $arrOfferProdRelated;
				
 			}
 			return $outArrMyOfferDetails;
			
			//echo '{"id":104,"active":true,"description":"Palazzo","category":null,"shortDescription":"","url":null,"additionalProductMedia":[{"id":151,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/FashionShowMap.jpg","video":false},{"id":152,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PropertyMap.jpg","video":false},{"id":150,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/77969624.png","video":false}],"red":0,"green":0,"blue":0,"title":"Palazzo","image":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PalazzoAd_main_image.jpg","barcode":"","hideBackground":false,"price":0.0,"html":"","style":null,"offer":false,"offeredProductId":0,"related":[118,100,117,101,112],"publicClient":{"name":"GGP","id":94,"prefix":"GGP","active":true,"url":"http://www.ggp.com","products":[],"catalogs":[],"logo":null,"backgroundColor":"f68e2e","lightColor":"f26522","darkColor":"f26522","backgroundImage":null}}';

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getOfferDetails($pOfferID){
		try{
			global $config;			
			$arrProdDetails = array();
			$outArrProdDetails = array();
			$arrClientDetails = array();
			$outArrMyOfferDetails = array();
			$arrClientForPublicClients = array();
			$arrMyOfferDetails = array();
			$arrMyOfferInfo = array();
			$arrRelatedOffers = array();
			$offerAdditionalImages = array();
			$arrClientDetails = array();
			
// 			$pOfferID = 69;
			$arrProdDetails = $this->objPublic->getOfferByOfferId($pOfferID);
			//$outArrMyOfferDetails = $arrProdDetails;
			//print_r($arrProdDetails);
			for($i=0;$i<count($arrProdDetails);$i++){
				$outArrMyOfferDetails["active"] = isset($arrProdDetails[$i]['offer_status']) ? $arrProdDetails[$i]['offer_status'] : "";
				$outArrMyOfferDetails["id"] = isset($arrProdDetails[$i]['offer_id']) ? $arrProdDetails[$i]['offer_id'] : "";
				$outArrMyOfferDetails["title"] = isset($arrProdDetails[$i]['offer_name']) ? $arrProdDetails[$i]['offer_name'] : "";
				$outArrMyOfferDetails["barcode"] = "";
				$outArrMyOfferDetails["color"] = "";
				$outArrMyOfferDetails["category"] = "";
				$outArrMyOfferDetails["description"] = isset($arrProdDetails[$i]['offer_description']) ? $arrProdDetails[$i]['offer_description'] : "";
				$outArrMyOfferDetails["hideBackground"] = isset($arrProdDetails[$i]['pd_hide_bg_image']) ? $arrProdDetails[$i]['pd_hide_bg_image'] : 0;
				$outArrMyOfferDetails["html"] = "";
				$prodImage = isset($arrProdDetails[$i]['offer_image']) ? $arrProdDetails[$i]['offer_image'] : "";
				$outArrMyOfferDetails["image"] = str_replace("{client_id}",$arrProdDetails[$i]['client_id'],$config['files']['products']).$prodImage;
				
				$outArrMyOfferDetails["shortDescription"] = isset($arrProdDetails[$i]['offer_short_description']) ? $arrProdDetails[$i]['offer_short_description'] : "";
				$outArrMyOfferDetails["offerPurchaseUrl"] = isset($arrProdDetails[$i]['offer_purchase_url']) ? $arrProdDetails[$i]['offer_purchase_url'] : "";
				$outArrMyOfferDetails["offerButtonName"] = isset($arrProdDetails[$i]['offer_button_name']) ? $arrProdDetails[$i]['offer_button_name'] : "";
				$outArrMyOfferDetails["url"] = "";
				$outArrMyOfferDetails["offerValidTo"] = isset($arrProdDetails[$i]['offer_valid_to']) ? $arrProdDetails[$i]['offer_valid_to'] : "";
				$outArrMyOfferDetails["offerValidFrom"] = isset($arrProdDetails[$i]['offer_valid_from']) ? $arrProdDetails[$i]['offer_valid_from'] : "";
				
				$checkIfCalendarEvent = isset($arrProdDetails[$i]['offer_is_calendar_based']) ? $arrProdDetails[$i]['offer_is_calendar_based'] : 0;
				$outArrMyOfferDetails["offerReminderDays"]="";
				$outArrMyOfferDetails["offerIsAllDayEvent"]="0";
				$arrMyOfferInfo = $this->objPublic->getOfferInfoByOfferId($pOfferID);
				// echo'check here first';
				// print_r($arrMyOfferInfo);
				if ($checkIfCalendarEvent==1){
					
					$outArrMyOfferDetails["offerReminderDays"] = isset($arrMyOfferInfo[0]['offer_info_reminder_days']) ? $arrMyOfferInfo[0]['offer_info_reminder_days'] : 0;
					$outArrMyOfferDetails["offerIsAllDayEvent"] = isset($arrMyOfferInfo[0]['offer_info_event_allday']) ? $arrMyOfferInfo[0]['offer_info_event_allday'] : 0;
				}
				// $offerDiscType = isset($arrProdDetails[$i]['offer_discount_type']) ? $arrProdDetails[$i]['offer_discount_type'] : "";
				// if ($offerDiscType=="A"){
				// 	$outArrMyOfferDetails["price"] = isset($arrProdDetails[$i]['offer_discount_value']) ? $arrProdDetails[$i]['offer_discount_value'] : 0;
				// }else if ($offerDiscType=="P"){
				// 	$outArrMyOfferDetails["price"] = 0.00;
				// }else if ($offerDiscType=="R"){
				// 	$outArrMyOfferDetails["price"] = 0.00;
				// }else{
				// 	$outArrMyOfferDetails["price"] = 0.00;
				// }
				$outArrMyOfferDetails["price"] = isset($arrMyOfferInfo[$i]['offer_discount_value']) ? $arrMyOfferInfo[$i]['offer_discount_value'] : '';
				$outArrMyOfferDetails["offerDiscType"] = isset($arrMyOfferInfo[$i]['offer_discount_type']) ? $arrMyOfferInfo[$i]['offer_discount_type'] : '';
				
				// print_r($arrMyOfferInfo);
				$arrClientForPublicClients = $outArrMyOfferDetails;
				$clientID = isset($arrProdDetails[$i]['client_id']) ? $arrProdDetails[$i]['client_id'] : 0;
				$outArrMyOfferDetails["clientID"] = $clientID;
				$outArrMyOfferDetails["clientName"] = isset($arrMyOfferInfo[$i]['name']) ? $arrMyOfferInfo[$i]['name'] : "";
				
				$arrClientDetails = $this->objPublic->getCurrencyCodeByClientId($clientID);
				if (count($arrClientDetails)>0) {
					$outArrMyOfferDetails["offerCurrencyCode"] = isset($arrClientDetails[0]['client_details_currency_code']) ? $arrClientDetails[0]['client_details_currency_code'] : 'USD';
				}else{
					$outArrMyOfferDetails["offerCurrencyCode"] = 'USD';
				}

				$clientLogo = isset($arrMyOfferInfo[$i]['logo']) ? $arrMyOfferInfo[$i]['logo'] : "";
				$outArrMyOfferDetails["clientLogoURL"] = str_replace("{client_id}",$clientID,$config['files']['logo']).$clientLogo;
				$outArrMyOfferDetails['backgroundColor'] = isset($arrMyOfferInfo[$i]['background_color']) ? $arrMyOfferInfo[$i]['background_color'] : "";
				$outArrMyOfferDetails['lightColor'] = isset($arrMyOfferInfo[$i]['light_color']) ? $arrMyOfferInfo[$i]['light_color'] : "";
				$outArrMyOfferDetails['darkColor'] = isset($arrMyOfferInfo[$i]['dark_color']) ? $arrMyOfferInfo[$i]['dark_color'] : "";
				$outArrMyOfferDetails['backgroundImage'] = isset($arrMyOfferInfo[$i]['background_image']) ? $arrMyOfferInfo[$i]['background_image'] : "";
				
				$arrRelatedOffers = $this->objPublic->getRelatedOffersById($pOfferID);
				$offerRelated = isset($arrRelatedOffers[0]['offerRelated']) ? $arrRelatedOffers[0]['offerRelated'] : "";
				
				$arrOfferRelated = explode(",", $offerRelated);
				$outArrMyOfferDetails["related"]  = $arrOfferRelated;
				$offerProdRelated = isset($arrRelatedOffers[0]['prodRelated']) ? $arrRelatedOffers[0]['prodRelated'] : "";
				$arrOfferProdRelated = explode(",", $offerProdRelated);
				$outArrMyOfferDetails["relatedProducts"]  = $arrOfferProdRelated;
				
				$offerAdditionalImages = $this->objPublic->getAdditionalImagesByOfferId($pOfferID);
				if (count($offerAdditionalImages)>0){
					$arrAdditionalImages = (array) json_decode($offerAdditionalImages[0]['offer_additional_media'],true);
	// 						print_r($arrAdditionalImages);
					for ($a=0; $a <count($arrAdditionalImages); $a++){
						$outArrMyOfferDetails["additionalOfferMedia"][$a]['id'] =$a;
						$pdAddImage = isset($arrAdditionalImages[$a]['image']) ? $arrAdditionalImages[$a]['image'] : '';
						if($pdAddImage!=""){
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['media'] = str_replace("{client_id}",$clientID,$config['files']['additional']).$pdAddImage;
						}else{
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['media'] = "";
						}
						$pdAddVideo = isset($arrAdditionalImages[$a]['video']) ? $arrAdditionalImages[$a]['video'] : '';
						if($pdAddVideo!=""){
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['video'] =str_replace("{client_id}",$clientID,$config['files']['additional']).$pdAddVideo;
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['videostreaming'] = str_replace("{client_id}",$clientID,$config['files']['videostreaming']).$pdAddVideo;
						
						}else{
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['video'] ="";
						}
						$pdAddAudio = isset($arrAdditionalImages[$a]['audio']) ? $arrAdditionalImages[$a]['audio'] : '';
						if($pdAddAudio!=""){
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['audio'] =str_replace("{client_id}",$clientID,$config['files']['additional']).$pdAddAudio;
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['audiostreaming'] = str_replace("{client_id}",$clientID,$config['files']['videostreaming']).$pdAddAudio;
						
						}else{
							$outArrMyOfferDetails["additionalOfferMedia"][$a]['audio'] ="";
						}
					}
				}else{
					$outArrMyOfferDetails["additionalOfferMedia"]=array();
				}
			

// 			$outArrMyOfferDetails["additionalOfferMedia"]=array();
 			}
 			return $outArrMyOfferDetails;
			
			//echo '{"id":104,"active":true,"description":"Palazzo","category":null,"shortDescription":"","url":null,"additionalProductMedia":[{"id":151,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/FashionShowMap.jpg","video":false},{"id":152,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PropertyMap.jpg","video":false},{"id":150,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/77969624.png","video":false}],"red":0,"green":0,"blue":0,"title":"Palazzo","image":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PalazzoAd_main_image.jpg","barcode":"","hideBackground":false,"price":0.0,"html":"","style":null,"offer":false,"offeredProductId":0,"related":[118,100,117,101,112],"publicClient":{"name":"GGP","id":94,"prefix":"GGP","active":true,"url":"http://www.ggp.com","products":[],"catalogs":[],"logo":null,"backgroundColor":"f68e2e","lightColor":"f26522","darkColor":"f26522","backgroundImage":null}}';

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserMyCloset($pUserInfo){
		try{
		
		$outArrCloset = array();
		$arrMyClosetProdIds = array();
		$uid = isset($pUserInfo['userid']) ? $pUserInfo['userid'] : 0;
		$arrMyClosetProdIds = $this->objPublic->getMyClosetByUid($uid);
		//print_r($arrMyClosetProdIds);
		
		// $outArrCloset = $this->getProductDetails($prodId);
		for($i=0;$i<count($arrMyClosetProdIds);$i++){
			$prodId = isset($arrMyClosetProdIds[$i]['pd_id']) ? $arrMyClosetProdIds[$i]['pd_id'] : 0;
			$outArrCloset[$i] = $this->getProductDetails($prodId);
			$outArrCloset[$i]['closetStatus'] = isset($arrMyClosetProdIds[$i]['closet_selection_status']) ? $arrMyClosetProdIds[$i]['closet_selection_status'] : 0;
		 }
 		echo json_encode($outArrCloset);
	
			
		//echo '[{"id":104,"active":true,"description":"Palazzo","category":null,"shortDescription":"","url":null,"additionalProductMedia":[{"id":151,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/FashionShowMap.jpg","video":false},{"id":152,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PropertyMap.jpg","video":false},{"id":150,"media":"http://s3.amazonaws.com/seemore-cms/cms/GGP/77969624.png","video":false}],"red":0,"green":0,"blue":0,"title":"Palazzo","image":"http://s3.amazonaws.com/seemore-cms/cms/GGP/PalazzoAd_main_image.jpg","barcode":"","hideBackground":false,"price":0.0,"html":"","style":null,"offer":false,"offeredProductId":0,"related":[118,100,117,101,112],"publicClient":{"name":"GGP","id":94,"prefix":"GGP","active":true,"url":"http://www.ggp.com","products":[],"catalogs":[],"logo":null,"backgroundColor":"f68e2e","lightColor":"f26522","darkColor":"f26522","backgroundImage":null}}]';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserRemoveMyCloset($pUserInfo){
		try{
			$outArrClosetInfo = array();
			$arrMyClosetProdIds = array();
			$uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrProdIds = isset($pUserInfo['prodIds']) ? $pUserInfo['prodIds'] : '';
			//$arrProdIds = "2798|104";
			$arrProdIds = explode("|",$arrProdIds);
			for ($i=0;$i<count($arrProdIds); $i++){
				//echo "product Ids".$arrProdIds[$i];
				$con = array();
				$wdArray = array();	
				$con['pd_id'] = isset($arrProdIds[$i]) ? $arrProdIds[$i] : 0;	
				$con['user_id'] = $uid;	
				if($con['pd_id'] !=0){
					$wdArray['closet_status'] = 2;
					$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "closet", $con);
					if ($updateRecord){
						$outArrClosetInfo['msg']='success';
					}else{
						$outArrClosetInfo['msg']='fail';
					}
				}
			}
			echo json_encode($outArrClosetInfo);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modUserAddMyCloset($pUserInfo){
		try{
			$outArrClosetInfo = array();
			$arrMyClosetProdIds = array();
			$uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$arrProdIds = isset($pUserInfo['prodIds']) ? $pUserInfo['prodIds'] : '';
			//$arrProdIds = "2798|104";
			$arrProdIds = explode("|",$arrProdIds);
			for ($i=0;$i<count($arrProdIds); $i++){
				//echo "product Ids".$arrProdIds[$i];
				$pdId = isset($arrProdIds[$i]) ? $arrProdIds[$i] : 0;
				$checkClosetExist = $this->objPublic->checkIfClosetExistsByUid($uid,$pdId);
				if ($uid!=0 && $pdId!=0){
				if (count($checkClosetExist)>0){
					$con = array();
					$wdArray = array();	
					$con['pd_id'] = $pdId;	
					$con['user_id'] = $uid;	
					if($con['pd_id'] !=0){
						$wdArray['closet_status'] = 1;
						$wdArray['closet_updated_date'] = 'NOW()';
						$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "closet", $con);
						if ($updateRecord){
							$outArrClosetInfo['msg']='success';
						}else{
							$outArrClosetInfo['msg']='fail';
						}
					}
				}else{
					$pArray = array();
					$pTableName = 'closet';
					$pArray['user_id'] = $uid;
					$pArray['closet_created_date'] = 'NOW()';
					$pArray['pd_id'] = $pdId;
					$pArray['closet_selection_status'] = 1;
					$pArray['closet_status'] = 1;
	
					$insertCloset = $this->objPublic->insertQuery($pArray, $pTableName, true);
					if ($insertCloset){
						$outArrClosetInfo['msg']='success';
					}else{
						$outArrClosetInfo['msg']='fail';
					}
					}
				}else{
					$outArrClosetInfo['msg']='fail';
				}
			}
			echo json_encode($outArrClosetInfo);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modUserUpdateMyClosetOwnIt($pUserInfo){
		try{
			$outArrClosetInfo = array();
			$arrMyClosetProdIds = array();
			$uid = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$ownit = isset($pUserInfo['ownit']) ? $pUserInfo['ownit'] : 0;
			$arrProdIds = isset($pUserInfo['prodIds']) ? $pUserInfo['prodIds'] : '';
			//$arrProdIds = "2798|104";
			$arrProdIds = explode("|",$arrProdIds);
			for ($i=0;$i<count($arrProdIds); $i++){
				//echo "product Ids".$arrProdIds[$i]." own it ".$ownit;
				$con = array();
				$wdArray = array();	
				$con['pd_id'] = isset($arrProdIds[$i]) ? $arrProdIds[$i] : 0;	
				$con['user_id'] = $uid;	
				if($con['pd_id'] !=0){
					$wdArray['closet_selection_status'] = $ownit;
					$updateRecord = $this->objPublic->updateRecordQuery($wdArray, "closet", $con);
					if ($updateRecord){
						$outArrClosetInfo['msg']='success';
					}else{
						$outArrClosetInfo['msg']='fail';
					}
				}
			}
			echo json_encode($outArrClosetInfo);
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
			$outArrModelForXml['Model']=$outArrAllModel;
			echo json_encode($outArrAllModel);
			/*$xml = Array2XML::createXML('triggerAll', $outArrModelForXml);
			echo $xml->saveXML();*/
			
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
				 $outArrAllTriggers[$i]["clientImage"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$outArrAllTriggers[$i]["client_id"]."/triggers/".$outArrAllTriggers[$i]["clientImage"];
				 
					$outAllTriggers['trigger'] = $outArrAllTriggers;  
			}
			/*$xml = Array2XML::createXML('clientTriggers', $outAllTriggers);
			echo $xml->saveXML();*/
		    echo json_encode($outAllTriggers);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggersWithXML($pUrl){
		try{
			global $config;			
			$outArrAllTriggers = array();		
			$outAllTriggers=array();
			$outArrAllModel = array();	
			$outArrAllTriggers = $this->objPublic->getClientAllTriggersForXML($pUrl[4]);
			
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
						}
					}
					
					$outArrAllTriggers[$i]['Model']=$outArrAllModel;
			}
			$outAllTriggers['trigger'] = $outArrAllTriggers;  
			echo json_encode($outArrAllTriggers);
			/*$xml = Array2XML::createXML('triggerAll', $outAllTriggers);
			echo $xml->saveXML();*/
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}


	public function modVisualByTriggerID($pTriggerID){
		try{
			global $config;		
			$arrVisualsByTriggerID = array();
			$outArrAllTriggers = array();	
			$arrModelsByVisualID = array();
			// $pTriggerID = 2705;

			$arrVisualsByTriggerID = $this->objPublic->getVisualsByTriggerID($pTriggerID);
			// print_r($arrVisualsByTriggerID);
			for($j=0;$j<count($arrVisualsByTriggerID);$j++){
				$outArrAllTriggers[$j]['visualID'] = isset($arrVisualsByTriggerID[$j]['id']) ? $arrVisualsByTriggerID[$j]['id'] : 0;
				$outArrAllTriggers[$j]['discriminator'] = isset($arrVisualsByTriggerID[$j]['discriminator']) ? $arrVisualsByTriggerID[$j]['discriminator'] : '';
				$outArrAllTriggers[$j]['x'] = isset($arrVisualsByTriggerID[$j]['x']) ? $arrVisualsByTriggerID[$j]['x'] : 0;
				$outArrAllTriggers[$j]['y'] = isset($arrVisualsByTriggerID[$j]['y']) ? $arrVisualsByTriggerID[$j]['y'] : 0;
				$outArrAllTriggers[$j]['product_id'] = isset($arrVisualsByTriggerID[$j]['product_id']) ? $arrVisualsByTriggerID[$j]['product_id'] : 0;
				$outArrAllTriggers[$j]['offer_id'] = isset($arrVisualsByTriggerID[$j]['offer_id']) ? $arrVisualsByTriggerID[$j]['offer_id'] : 0;
				$outArrAllTriggers[$j]['VisualURL'] = isset($arrVisualsByTriggerID[$j]['url']) ? $arrVisualsByTriggerID[$j]['url'] : '';
				if($arrVisualsByTriggerID[$j]['discriminator'] == "VIDEO"){
					$outArrAllTriggers[$j]['VisualURL'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['videos']).$arrVisualsByTriggerID[$j]['url'];
					$outArrAllTriggers[$j]['videostreaming'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['videostreaming']).$arrVisualsByTriggerID[$j]['url'];
					
				}
				$outArrAllTriggers[$j]['rotation_x'] = isset($arrVisualsByTriggerID[$j]['rotation_x']) ? $arrVisualsByTriggerID[$j]['rotation_x'] : 0;
				$outArrAllTriggers[$j]['rotation_y'] = isset($arrVisualsByTriggerID[$j]['rotation_y']) ? $arrVisualsByTriggerID[$j]['rotation_y'] : 0;
				$outArrAllTriggers[$j]['rotation_z'] = isset($arrVisualsByTriggerID[$j]['rotation_z']) ? $arrVisualsByTriggerID[$j]['rotation_z'] : 0;
				$outArrAllTriggers[$j]['scale'] = isset($arrVisualsByTriggerID[$j]['scale']) ? $arrVisualsByTriggerID[$j]['scale'] : 0;
				$outArrAllTriggers[$j]['animate_on_recognition'] = isset($arrVisualsByTriggerID[$j]['animate_on_recognition']) ? $arrVisualsByTriggerID[$j]['animate_on_recognition'] : 0;
				$outArrAllTriggers[$j]['video_in_metaio'] = isset($arrVisualsByTriggerID[$j]['video_in_metaio']) ? $arrVisualsByTriggerID[$j]['video_in_metaio'] : 0;
				$outArrAllTriggers[$j]['ignore_tracking'] = isset($arrVisualsByTriggerID[$j]['ignore_tracking']) ? $arrVisualsByTriggerID[$j]['ignore_tracking'] : 0;
				$outArrAllTriggers[$j]['hasTryOn'] = isset($arrVisualsByTriggerID[$j]['hasTryOn']) ? $arrVisualsByTriggerID[$j]['hasTryOn'] : 0;
				$outArrAllTriggers[$j]['buyButtonName'] = isset($arrVisualsByTriggerID[$j]['buy_button_name']) ? $arrVisualsByTriggerID[$j]['buy_button_name'] : '';
				$outArrAllTriggers[$j]['buyButtonUrl'] = isset($arrVisualsByTriggerID[$j]['buy_button_url']) ? $arrVisualsByTriggerID[$j]['buy_button_url'] : '';
				
				$arrModelsByVisualID = $this->objPublic->getModelsByVisualID($arrVisualsByTriggerID[$j]['id']);
				for($k=0;$k<count($arrModelsByVisualID);$k++){
					$outArrAllTriggers[$j]['models'][$k]['modelID'] = isset($arrModelsByVisualID[$k]['id']) ? $arrModelsByVisualID[$k]['id'] : 0;
					$outArrAllTriggers[$j]['models'][$k]['three_d_model_id'] = isset($arrModelsByVisualID[$k]['three_d_model_id']) ? $arrModelsByVisualID[$k]['three_d_model_id'] : 0;
					$outArrAllTriggers[$j]['models'][$k]['model'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['models']).$arrModelsByVisualID[$k]['model'];
					$outArrAllTriggers[$j]['models'][$k]['texture'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['models']).$arrModelsByVisualID[$k]['texture'];
					$outArrAllTriggers[$j]['models'][$k]['material'] = str_replace("{client_id}",$arrAllTriggers[$i]['client_id'],$config['files']['models']).$arrModelsByVisualID[$k]['material'];
					$outArrAllTriggers[$j]['models'][$k]['active'] = $arrModelsByVisualID[$k]['active'];
					
				}
					
			}
			echo json_encode($outArrAllTriggers);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}			
	public function modCheckClientGroups($pUserInfo){
		try{
			global $config;			
			$outResponse= array();
			$arrClientGroups= array();
			$arrRequiredChanges = array();
			$arrChangeLog = array();
			date_default_timezone_set('America/Detroit');

			$updatedDate = isset($pUserInfo['uDate']) ? $pUserInfo['uDate'] : date("Y-m-d H:i:s");
			$date = new DateTime($updatedDate);
			$pUpdatedDate = $date->format('Y-m-d'); 
			$arrClientGroups = $this->objPublic->getClientGroupTriggers();
			$clientGroupCount = isset($arrClientGroups) ? count($arrClientGroups) : 0;
			$outResponse['count'] = $clientGroupCount;
			
			$arrChangeLog = $this->objPublic->getChangeLog($pUpdatedDate);
			// print_r($arrChangeLog);
			$c=0;
			for ($i=0; $i < count($arrChangeLog); $i++) { 
				$arrRequiredChanges[$c]['changeID'] = isset($arrChangeLog[$i]['change_log_id']) ? $arrChangeLog[$i]['change_log_id'] : 0;
				$arrRequiredChanges[$c]['clientsIDs'] = isset($arrChangeLog[$i]['client_id']) ? $arrChangeLog[$i]['client_id'] : '';
				$arrRequiredChanges[$c]['triggerIDs'] = isset($arrChangeLog[$i]['trigger_id']) ? $arrChangeLog[$i]['trigger_id'] : '';
				$arrRequiredChanges[$c]['visualIDs'] = isset($arrChangeLog[$i]['visual_id']) ? $arrChangeLog[$i]['visual_id'] : ''; 
				$arrRequiredChanges[$c]['triggerVisualIDs'] = isset($arrChangeLog[$i]['trigger_visual_id']) ? $arrChangeLog[$i]['trigger_visual_id'] : ''; 
				$arrRequiredChanges[$c]['productIDs'] = isset($arrChangeLog[$i]['product_id']) ? $arrChangeLog[$i]['product_id'] : ''; 
				$arrRequiredChanges[$c]['offerIDs'] = isset($arrChangeLog[$i]['offer_id']) ? $arrChangeLog[$i]['offer_id'] : ''; 
				$arrRequiredChanges[$c]['createdDate'] = isset($arrChangeLog[$i]['created_date']) ? $arrChangeLog[$i]['created_date'] : ''; 
				$c++;
			}
			$c=0;
			$outResponse['recentChanges'] = $arrRequiredChanges;
			echo json_encode($outResponse);
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