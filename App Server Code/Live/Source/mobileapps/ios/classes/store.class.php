<?php 
class cStore{

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
			require_once(SRV_ROOT.'model/store.model.class.php');
			$this->objStore = new mStore();
			
			require_once SRV_ROOT.'classes/public.class.php';
			$this->objPublic = new cPublic();

			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modProdCartInfo($pID){
		try{
			global $config;			
			$this->modProdAttribInfo($pID);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modProdAttribInfo($pID){
		try{
			global $config;			
			if($pID != ""){
				$outArrProductInfo = array();
				$arrProductInfo= array();
				$arrProductAttrib= array();
				$arrShippingMethods= array();
				$arrPaymentMethods = array();
				$arrClientPayMethods = array();
				$arrClientDB = array();

				$arrProductInfo = $this->objStore->getProdInfo($pID);
				// print_r($arrProductInfo);
				$clientPrefix = isset($arrProductInfo[0]['prefix']) ? $arrProductInfo[0]['prefix'] :'';
				$clientShipMethods = isset($arrProductInfo[0]['client_shipping_methods']) ? $arrProductInfo[0]['client_shipping_methods'] :'';
				$clientPaymentMethods = isset($arrProductInfo[0]['client_payment_methods']) ? $arrProductInfo[0]['client_payment_methods'] :'';
				$clientID = isset($arrProductInfo[0]['client_id']) ? $arrProductInfo[0]['client_id'] :0;

				$this->objStore->connectToDB($config['database']['masterdb']);
				$arrClientDB = $this->objStore->getClientDB($clientID);

				$dbName = isset($arrClientDB[0]['client_db_name']) ? $arrClientDB[0]['client_db_name'] :'';
				$this->objStore->connectToDB($dbName);
				$arrProductAttrib = $this->objStore->getProdAttribInfo($pID);
				// print_r($arrProductAttrib);
				for($i=0;$i<count($arrProductAttrib);$i++)
				{
				 $attribName = isset($arrProductAttrib[$i]['attrib_name']) ? $arrProductAttrib[$i]['attrib_name'] : "";
				 if ($attribName !="") {
				 	$attribValue = isset($arrProductAttrib[$i]['attrib_value']) ? $arrProductAttrib[$i]['attrib_value'] : "";
				 	// $arrAttribValue = json_decode($attribValue);
				 	$outArrProductInfo['attrib'][$attribName][] = $attribValue;
				 	$outArrProductInfo['attrib']['quantity'] = 10;
				 }
				}
				$arrClientShipMethods = explode(',', $clientShipMethods);
				$arrShippingMethods = $this->objStore->getShippingMethods($arrClientShipMethods);
				for($j=0;$j<count($arrShippingMethods);$j++){
					$outArrProductInfo['shippingMethods'][$j]['shipMethodName']= isset($arrShippingMethods[$j]['ship_method_name']) ? $arrShippingMethods[$j]['ship_method_name'] : "";
					$outArrProductInfo['shippingMethods'][$j]['shipMethodDetails']= isset($arrShippingMethods[$j]['ship_method_details']) ? $arrShippingMethods[$j]['ship_method_details'] : '{"A":0.00}';
					$shipMethodRateString= isset($arrShippingMethods[$j]['ship_method_rate']) ? $arrShippingMethods[$j]['ship_method_rate'] : "";
					$arrShipMethodRate = json_decode($shipMethodRateString);
					$outArrProductInfo['shippingMethods'][$j]['shipMethodRate']= $arrShipMethodRate;
				}
				// print_r($arrShippingMethods);

				$arrClientPayMethods = explode(',', $clientPaymentMethods);
				// print_r($arrClientPayMethods);
				$arrPaymentMethods = $this->objStore->getPaymentMethods($arrClientPayMethods);
				for($k=0;$k<count($arrPaymentMethods);$k++){
					$outArrProductInfo['paymentMethod'][$k]['paymentMethod']= isset($arrPaymentMethods[$k]['pay_method_name']) ? $arrPaymentMethods[$k]['pay_method_name'] : "";
					$outArrProductInfo['paymentMethod'][$k]['paymentMethodId']= isset($arrPaymentMethods[$k]['pay_method_id']) ? $arrPaymentMethods[$k]['pay_method_id'] : 0;
				}
				// print_r($arrPaymentMethods);
				$this->objStore->connectToDB($config['database']['name']);
				echo json_encode($outArrProductInfo);
				
			}else{
				echo "No data available";
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modPaymentMethods($pID){
		try{
			global $config;			
			$this->modProdAttribInfo($pID);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modStateTaxByState($pUserInfo){
		try{
			global $config;
			// echo 'modStateTaxByState';
			// print_r($pUserInfo);	
			$arrStateTax = array();		
		
			$outArrStateTax = array();
			$stateCode = isset($pUserInfo['state']) ? $pUserInfo['state'] : '';
			$countryCode = isset($pUserInfo['country']) ? $pUserInfo['country'] : '';
			$zipCode = isset($pUserInfo['zip']) ? $pUserInfo['zip'] : '';

			$arrStateTax = $this->objStore->getUSStateTax($stateCode);
			$outArrStateTax['statetax'] = 0.00;
			if (count($arrStateTax) > 0) {
				$outArrStateTax['statetax'] = isset($arrStateTax[0]['state_tax']) ? $arrStateTax[0]['state_tax'] : 0.00;
			}
			echo json_encode($outArrStateTax);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modGetClientStoresByZip($pUserInfo){
		try{
			global $config;
			// print_r($pUserInfo);	
			$outArrClientStores = array();
			$zipCode = isset($pUserInfo['zip']) ? $pUserInfo['zip'] : 0;

			$lat = 0;
			$lng = 0;
			if ($zipCode !=0) {
				$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zipCode."&sensor=false";
				$details=file_get_contents($url);
				$result = json_decode($details,true);

				$lat=$result['results'][0]['geometry']['location']['lat'];
				$lng=$result['results'][0]['geometry']['location']['lng'];
			}else{
				$lat = isset($pUserInfo['lat']) ? $pUserInfo['lat'] : 0;
				$lng = isset($pUserInfo['lng']) ? $pUserInfo['lng'] : 0;
			}
			

			if (($lat!=0) && ($lng !=0)) {
				$pUserInfo['requiredLat'] = $lat;
				$pUserInfo['requiredLng'] = $lng;
			}
			$outArrClientStores = $this->objStore->getClientStoresByLatLng($pUserInfo);

			// $outArrClientStores['stores'] = 'Testing '.$clientID.' '.$zipCode.' '.$lat.' '.$lng.'end';

			echo json_encode($outArrClientStores);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modMainCategories(){
		try{
			global $config;	
			$outArrMainCat = array();	
			$arrMainCat = array();	
			// echo 'modMainCategories';
			
			
			$arrMainCat = $this->objStore->getMainCategories();
			// print_r($arrMainCat);
			for ($i=0; $i < count($arrMainCat); $i++) { 
				$outArrMainCat[$i]['cat_main_id'] = isset($arrMainCat[$i]['cat_main_id']) ? $arrMainCat[$i]['cat_main_id'] : 0;
				$outArrMainCat[$i]['cat_main_name'] = isset($arrMainCat[$i]['cat_main_name']) ? $arrMainCat[$i]['cat_main_name'] : '';
				$clientIds = isset($arrMainCat[$i]['cat_main_clients']) ? $arrMainCat[$i]['cat_main_clients'] : '';
				$arrClientIds = array();
				$outArrMainCat[$i]['clients'] = array();
				if ($clientIds!='') {
					$arrClientIds = explode(',', $clientIds);
				}
				if (count($arrClientIds)>0) {
					$cID = 0;
					for ($j=0; $j < count($arrClientIds); $j++) { 
						$arrClientInfo = array();
						$clientID = isset($arrClientIds[$j]) ? $arrClientIds[$j] : 0;
						$arrClientInfo = $this->modClientCategories($clientID);
						$outArrMainCat[$i]['clients'][$cID] = $arrClientInfo;
						$cID++;
					}
				}
				
			}
			// print_r($outArrMainCat);
			echo json_encode($outArrMainCat);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientCategories($pClientID){
		try{
			global $config;	
			$outArrClientCat = array();	
			$arrClientInfo = array();
			$arrClientCat = array();
			
			$this->objStore->connectToDB($config['database']['name']);

			$arrClientInfo = $this->objStore->getClientInfo($pClientID);
			if (count($arrClientInfo) > 0){
				$outArrClientCat['clientId'] = isset($arrClientInfo[0]['id']) ? $arrClientInfo[0]['id'] : 0;
				$outArrClientCat['clientName'] = isset($arrClientInfo[0]['name']) ? $arrClientInfo[0]['name'] : '';
				$outArrClientCat['isAffiliate'] = isset($arrClientInfo[0]['is_affiliate']) ? $arrClientInfo[0]['is_affiliate'] : 0;
				$clientLogo = isset($arrClientInfo[0]['logo']) ? $arrClientInfo[0]['logo'] : "";
				$outArrClientCat["clientLogoURL"] = '';
				$outArrClientCat["clientTransLogoURL"] = '';
				if ($clientLogo !='') {
					$outArrClientCat["clientLogoURL"] = str_replace("{client_id}",$pClientID,$config['files']['logo']).$clientLogo;
					 $clientTransLogo = 'trans_'.$clientLogo;
					$outArrClientCat["clientTransLogoURL"] = str_replace("{client_id}",$pClientID,$config['files']['logo']).$clientTransLogo;
				}
				
				$dbName = $this->modClientDB($pClientID);
				if ($dbName !='') {
					$this->objStore->connectToDB($dbName);
					$arrClientCat = $this->modAllCategories(0);
					$outArrClientCat["clientCategories"] = $arrClientCat;

				}
				
			}
			// $outArrClientCat = $this->objStore->getClientCategories($pClientID);
			return $outArrClientCat;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modClientDB($pClientID){
		try{
			
			global $config;
			$arrClientDB = array();
			 // $indent++;
        	// $this->objStore->connectToDB('devarapp_client_lookbook');
	        $this->objStore->connectToDB($config['database']['masterdb']);
			$arrClientDB = $this->objStore->getClientDB($pClientID);
			$dbName = isset($arrClientDB[0]['client_db_name']) ? $arrClientDB[0]['client_db_name'] :'';
			// print_r($arrClientDB);
	        return $dbName;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modAllCategories($catID){
		try{
			
			global $config;	
			 // $indent++;
        	// $this->objStore->connectToDB('devarapp_client_lookbook');
	        $data = array();
	        
	        if($results = $this->objStore->getAllCategoriesByClient($catID))
	        {
	          for ($i=0; $i < count($results) ; $i++) { 
	                $data[$i]['catID'] = isset($results[$i]['cat_id']) ? $results[$i]['cat_id'] : 0;
	                $data[$i]['catName'] = isset($results[$i]['cat_name']) ? $results[$i]['cat_name'] : 0;
	                $catID = isset($results[$i]['cat_id']) ? $results[$i]['cat_id'] : 0;
	                // $data[$i]['subCatID'] = $catID;
	                if ($catID!=0) {
	                	$sub = $this->modAllCategories($catID);
	                	$arrProdCount = $this->objStore->getProdCountByCatID($catID);
	                	$prodCount = count($arrProdCount);
	                	$data[$i]['prodCount'] = $prodCount;
	                	$data[$i]['subCat'] = $sub;
	                }
	            }            
	        }

	        return $data;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modDropdownCategories($parent_id=''){
		try{
			// $this->objStore->connectToDB('devarapp_client_lookbook');
			global $config;	
			 // $indent++;
        
	        $data = array();
	        
	        if($results = $this->objStore->getDropdownCat($parent_id))
	        {
	          for ($i=0; $i < count($results) ; $i++) { 
	                $data['catID'] = isset($results[$i]['cat_id']) ? $results[$i]['cat_id'] : 0;
	                $data['catName'] = isset($results[$i]['cat_name']) ? $results[$i]['cat_name'] : 0;
	                $parentID = isset($results[$i]['cat_parent_id']) ? $results[$i]['cat_parent_id'] : 0;
	                $data['catParentID'] = $parentID;
	                if ($parentID!=0) {
	                	$sub = $this->modDropdownCategories($parentID);
	                	$data['subCat'] = $sub;
	                }
	            }            
	        }

	        return $data;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modClientProducts($clientId){
		try{
			
			global $config;	
			$arrClientProds = array();
			$outArrClientProds = array();
			 // $indent++;
        	// $this->objStore->connectToDB('devarapp_client_lookbook');

        	// echo 'modClientProducts'.$clientId;
        	$dbName = $this->modClientDB($clientId);
        	$this->objStore->connectToDB($dbName);
		    $arrClientProds = $this->objStore->getClientProducts();
		    $this->objStore->connectToDB($config['database']['name']);
	        // print_r($arrClientProds);
	        $c=0;
          	for ($i=0; $i < count($arrClientProds) ; $i++) { 
                $catID = isset($arrClientProds[$i]['cat_id']) ? $arrClientProds[$i]['cat_id'] : 0;
                $prodId = isset($arrClientProds[$i]['prod_id']) ? $arrClientProds[$i]['prod_id'] : 0;
                if ($prodId !=0) {
                	$arrProdDetails = array();
                	$arrProdDetails = $this->objPublic->getProductDetails($prodId);
                	$outArrClientProds[$c] = $arrProdDetails;
                	$outArrClientProds[$c]['catId'] = $catID;
                	$c++;
                }
            }            
            // print_r($outArrClientProds);
	           echo json_encode($outArrClientProds);
	        // return $data;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modOrderCreate($pUserInfo){
		try{
			// echo 'modOrderCreate';
			global $config;	
			// $arrOrderInfo = $this->getDummyClientOrder();
			// print_r($pUserInfo);
			$orderId = isset($pUserInfo['orderId']) ? $pUserInfo['orderId'] : 0;
			if ($orderId ==0) {
				$this->doOrderInsert($pUserInfo);
			}else {
				$this->doOrderUpdate($pUserInfo);
			}
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function doOrderInsert($pUserInfo){
		try{
			global $config;
			// print_r($arrOrderInfo);

			$arrOrderInfo = array();
			$outArrOrder = array();
			$clientOrderIDs = array();
			$arrUserShipAddress = array();

			$arrOrderInfo = $pUserInfo;
			// $arrOrderInfo = $this->getDummyClientOrder();
			// print_r($arrOrderInfo);
			$isOrderSuccess = 0;
		    $userShippingId = isset($arrOrderInfo['shippingAddress']['shippingId']) ? $arrOrderInfo['shippingAddress']['shippingId'] : 0;
			
		    if (count($arrOrderInfo) > 0) {
			    $orderId = isset($arrOrderInfo['orderId']) ? $arrOrderInfo['orderId'] : 0;
		    	for($c=0;$c<count($arrOrderInfo['clients']); $c++){

		    		//Connect to Client related database
		    		$clientId = isset($arrOrderInfo['clients'][$c]['clientId']) ? $arrOrderInfo['clients'][$c]['clientId'] : 0;
			    	$dbName = $this->modClientDB($clientId);
		        	$this->objStore->connectToDB($dbName);

		        	//Collect all informations
		        	$orderUserId = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;
					$clientOrderSubTotal = isset($arrOrderInfo['clients'][$c]['clientTotal']) ? $arrOrderInfo['clients'][$c]['clientTotal'] : 0.00;
					$orderSalesTaxValue = isset($arrOrderInfo['salesTaxValue']) ? $arrOrderInfo['salesTaxValue'] : 0.00;
					$orderSalesTax= isset($arrOrderInfo['salesTax']) ? $arrOrderInfo['salesTax'] : 0.00;
					$shippingCost = isset($arrOrderInfo['clients'][$c]['shippingCost']) ? $arrOrderInfo['clients'][$c]['shippingCost'] : 0.00;
					$cartTotal = isset($arrOrderInfo['clients'][$c]['cartTotal']) ? $arrOrderInfo['clients'][$c]['cartTotal'] : 0.00;
					$clientOrderSaleTax = ($clientOrderSubTotal * ($orderSalesTaxValue/100));
					$clientOrderTotal = ($clientOrderSubTotal + $clientOrderSaleTax);
					


					$orderShipAddrId = isset($arrOrderInfo['shippingAddress']['userShipId']) ? $arrOrderInfo['shippingAddress']['userShipId'] : '';
					$orderShipAddr1 = isset($arrOrderInfo['shippingAddress']['addr1']) ? $arrOrderInfo['shippingAddress']['addr1'] : '';
					$orderShipAddr2 = isset($arrOrderInfo['shippingAddress']['addr2']) ? $arrOrderInfo['shippingAddress']['addr2'] : '';
					$orderShipCity= isset($arrOrderInfo['shippingAddress']['city']) ? $arrOrderInfo['shippingAddress']['city'] : '';
					$orderShipState= isset($arrOrderInfo['shippingAddress']['state']) ? $arrOrderInfo['shippingAddress']['state'] : '';
					$orderShipZip = isset($arrOrderInfo['shippingAddress']['zip']) ? $arrOrderInfo['shippingAddress']['zip'] : '';
					$orderShipCountry = isset($arrOrderInfo['shippingAddress']['country']) ? $arrOrderInfo['shippingAddress']['country'] : '';
					$orderSessionId = isset($arrOrderInfo['analytics']['session_id']) ? $arrOrderInfo['analytics']['session_id'] : '';
					$orderShippingMethod = isset($arrOrderInfo['clients'][$c]['shippingMethod']) ? $arrOrderInfo['clients'][$c]['shippingMethod'] : '';

					$arrClientProds = array();
					$arrClientProds = isset($arrOrderInfo['clients'][$c]['products']) ? $arrOrderInfo['clients'][$c]['products'] : array();


		        	if ($orderId == 0) {
		        		// insert order - Start
				        $pArray = array();
						$pTableName = 'orders';
						
						$pArray['user_id'] = $orderUserId;
						$pArray['order_total'] = $clientOrderTotal;
						$pArray['order_created_date'] = 'NOW()';
						$pArray['order_updated_date'] = 'NOW()';
						$pArray['order_status'] = 0;
						$pArray['order_sales_tax'] = $clientOrderSaleTax;
						$pArray['order_cart_total'] = $cartTotal;
						$pArray['order_shipping_cost'] = $shippingCost;

						$pArray['order_shipping_addr1'] = $orderShipAddr1;
						$pArray['order_shipping_addr2'] = $orderShipAddr2;
						$pArray['order_shipping_city'] = $orderShipCity;
						$pArray['order_shipping_state'] = $orderShipState;
						$pArray['order_shipping_zip'] = $orderShipZip;
						$pArray['order_shipping_country'] = $orderShipCountry;
						$pArray['order_session_id'] = $orderSessionId;
						$pArray['order_shipping_method'] = $orderShippingMethod;
						$insertOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
						if ($insertOrder){

							
							for($i=0;$i< count($arrClientProds); $i++){
								$isOrderSuccess = 1;
								$pArray = array();
								$pTableName = 'order_details';
								$pArray['order_id'] = $insertOrder;
								$pArray['prod_id'] = isset($arrClientProds[$i]['prodId']) ? $arrClientProds[$i]['prodId'] : 0;
								$pArray['prod_name'] = isset($arrClientProds[$i]['prodName']) ? $arrClientProds[$i]['prodName'] : '';
								$pArray['prod_price'] = isset($arrClientProds[$i]['prodPrice']) ? $arrClientProds[$i]['prodPrice'] : 0.00;
								$pArray['prod_quantity'] = isset($arrClientProds[$i]['prodQuantity']) ? $arrClientProds[$i]['prodQuantity'] : 0;
								$prodAttribs = isset($arrClientProds[$i]['attribs']) ? $arrClientProds[$i]['attribs'] : array();
								if (count($prodAttribs) > 0) {
									$pArray['prod_attribs'] = json_encode($prodAttribs);
								}else{
									$pArray['prod_attribs'] = '';
								}
								
								$insertOrderDetails = $this->objStore->insertQuery($pArray, $pTableName, true);
								if (!$insertOrderDetails) {
									$isOrderSuccess = 0;
								}
							}
							
							$clientOrderIDs[$c]['clientId'] = $clientId;
							$clientOrderIDs[$c]['orderId'] = $insertOrder;
						// insert order - End
						}
						
					}
			    }
				
			}  
			$arrOrderInfo['clientOrderIDs'] = json_encode($clientOrderIDs);
			if ($isOrderSuccess==1) {
				$isOrderUpdateInMain = 0;
				$isOrderUpdateInMain = $this->doUserOrderInMain($arrOrderInfo);
				if ($isOrderUpdateInMain > 0) {
					$isOrderSuccess = 1;

				}
			}
			if ($isOrderSuccess !=1) {
			 	$outArrOrder['msg']='fail';
			 } else{
			 	$outArrOrder['msg']='success';
			 	$outArrOrder['orderID']=$isOrderUpdateInMain;
			 	$outArrOrder['paypalCustomMsg']='Payment Process';
			 	$outArrOrder['payment']['failMsg']='Opps! Your payment is not success. \n\nPlease contact us for more details.';
			 	$outArrOrder['payment']['successMsg']='Your payment is success. \nYou will receive payment receipt soon. \n\nThank you for your order.';
			 	$outArrOrder['payment']['saveOrderMsg']='Thank you for saving your order. \n\nPlease initiate your order from Settings -> My Orders.';
			 }
		 
		 	echo json_encode($outArrOrder);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function doOrderUpdate($pUserInfo){
		try{
			global $config;
			$arrClientIds = array();
			$arrMainOrderInfo = array();

			$arrOrderInfo = $pUserInfo;
			// $arrOrderInfo = $this->getDummyClientOrder();

			$this->objStore->connectToDB($config['database']['name']);

			$orderId = isset($arrOrderInfo['orderId']) ? $arrOrderInfo['orderId'] : 0;
			$userId = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;

			$arrMainOrderInfo = $this->objStore->getClientIdsFromMainOrder($orderId, $userId);
			$orderClientIds = $arrMainOrderInfo[0]['user_order_client_ids'];
			$arrClientIds = json_decode($orderClientIds,true);
			// print_r($arrClientIds);

			$isOrderSuccess =0;
			for($c=0;$c<count($arrOrderInfo['clients']); $c++){
				//Connect to Client related database
	    		$clientId = isset($arrOrderInfo['clients'][$c]['clientId']) ? $arrOrderInfo['clients'][$c]['clientId'] : 0;
		    	$dbName = $this->modClientDB($clientId);
	        	$this->objStore->connectToDB($dbName);

	        	//Collect all informations
	        	$orderUserId = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;
	        	$clientOrderSubTotal = isset($arrOrderInfo['clients'][$c]['clientTotal']) ? $arrOrderInfo['clients'][$c]['clientTotal'] : 0.00;
				$orderSalesTaxValue = isset($arrOrderInfo['salesTaxValue']) ? $arrOrderInfo['salesTaxValue'] : 0.00;
				$shippingCost = isset($arrOrderInfo['clients'][$c]['shippingCost']) ? $arrOrderInfo['clients'][$c]['shippingCost'] : 0.00;
				$cartTotal = isset($arrOrderInfo['clients'][$c]['cartTotal']) ? $arrOrderInfo['clients'][$c]['cartTotal'] : 0.00;
				$clientOrderSaleTax = ($clientOrderSubTotal * ($orderSalesTaxValue/100));
				$clientOrderTotal = ($clientOrderSubTotal + $clientOrderSaleTax);
				$orderSalesTax= isset($arrOrderInfo['salesTax']) ? $arrOrderInfo['salesTax'] : 0.00;
				
				$orderShipAddr1 = isset($arrOrderInfo['shippingAddress']['addr1']) ? $arrOrderInfo['shippingAddress']['addr1'] : '';
				$orderShipAddr2 = isset($arrOrderInfo['shippingAddress']['addr2']) ? $arrOrderInfo['shippingAddress']['addr2'] : '';
				$orderShipCity= isset($arrOrderInfo['shippingAddress']['city']) ? $arrOrderInfo['shippingAddress']['city'] : '';
				$orderShipState= isset($arrOrderInfo['shippingAddress']['state']) ? $arrOrderInfo['shippingAddress']['state'] : '';
				$orderShipZip = isset($arrOrderInfo['shippingAddress']['zip']) ? $arrOrderInfo['shippingAddress']['zip'] : '';
				$orderShipCountry = isset($arrOrderInfo['shippingAddress']['country']) ? $arrOrderInfo['shippingAddress']['country'] : '';
				$orderSessionId = isset($arrOrderInfo['analytics']['session_id']) ? $arrOrderInfo['analytics']['session_id'] : '';
				$orderShippingMethod = isset($arrOrderInfo['clients'][$c]['shippingMethod']) ? $arrOrderInfo['clients'][$c]['shippingMethod'] : '';

				$isClientExists = 0;
				$clientOrderId = 0;
				for ($x=0; $x < count($arrClientIds); $x++) { 
					$compareClientId = isset($arrClientIds[$x]['clientId']) ? $arrClientIds[$x]['clientId'] : 0;
					if ($clientId == $compareClientId) {
						$isClientExists = 1;
						$clientOrderId = isset($arrClientIds[$x]['orderId']) ? $arrClientIds[$x]['orderId'] : 0;
					}
				}

				$clientOrderIDs[$c]['clientId'] = $clientId;
				
				$arrClientProds = array();
				$arrClientProds = isset($arrOrderInfo['clients'][$c]['products']) ? $arrOrderInfo['clients'][$c]['products'] : array();

				// echo 'isClientExists: '.$isClientExists;
				if ($isClientExists == 1) {
					$clientOrderIDs[$c]['orderId'] = $clientOrderId;
					//Update order and order details database tables
					$con = array();
					$wdArray = array();	
					$con['order_id'] = $clientOrderId;
					if($con['order_id'] !=0){

						$wdArray['order_total'] = $clientOrderTotal;
						$wdArray['order_updated_date'] = 'NOW()';
						$wdArray['order_status'] = 0;
						$wdArray['order_sales_tax'] = $clientOrderSaleTax;
						$wdArray['order_cart_total'] = $cartTotal;
						$wdArray['order_shipping_cost'] = $shippingCost;

						$wdArray['order_shipping_addr1'] = $orderShipAddr1;
						$wdArray['order_shipping_addr2'] = $orderShipAddr2;
						$wdArray['order_shipping_city'] = $orderShipCity;
						$wdArray['order_shipping_state'] = $orderShipState;
						$wdArray['order_shipping_zip'] = $orderShipZip;
						$wdArray['order_shipping_country'] = $orderShipCountry;
						$wdArray['order_session_id'] = $orderSessionId;
						$wdArray['order_shipping_method'] = $orderShippingMethod;

						$updateClientOrder = $this->objStore->updateRecordQuery($wdArray, "orders", $con);
						if ($updateClientOrder) {
							for($i=0;$i< count($arrClientProds); $i++){
								$isOrderSuccess = 1;
								$prodId = isset($arrClientProds[$i]['prodId']) ? $arrClientProds[$i]['prodId'] : 0;
								$arrOrderDetails = $this->objStore->checkProdIdInOrderDetails($clientOrderId, $prodId);
								$orderDetailsId = $arrOrderDetails[0]['order_details_id'];
								if ($orderDetailsId > 0) {
									$con = array();
									$wdArray = array();
									$con['prod_id'] = $prodId;
									$con['order_details_id'] = $orderDetailsId;
									$wdArray['order_id'] = $clientOrderId;
									$wdArray['prod_name'] = isset($arrClientProds[$i]['prodName']) ? $arrClientProds[$i]['prodName'] : '';
									$wdArray['prod_price'] = isset($arrClientProds[$i]['prodPrice']) ? $arrClientProds[$i]['prodPrice'] : 0.00;
									$wdArray['prod_quantity'] = isset($arrClientProds[$i]['prodQuantity']) ? $arrClientProds[$i]['prodQuantity'] : 0;
									$prodAttribs = isset($arrClientProds[$i]['attribs']) ? $arrClientProds[$i]['attribs'] : array();
									if (count($prodAttribs) > 0) {
										$pArray['prod_attribs'] = json_encode($prodAttribs);
									}else{
										$pArray['prod_attribs'] = '';
									}
									$updateClientOrderDetails = $this->objStore->updateRecordQuery($wdArray, "order_details", $con);
									if (!$updateClientOrderDetails) {
										$isOrderSuccess = 0;
									}
								}else{
									$isOrderSuccess = 1;
									$pArray = array();
									$pTableName = 'order_details';
									$pArray['order_id'] = $clientOrderId;
									$pArray['prod_id'] = isset($arrClientProds[$i]['prodId']) ? $arrClientProds[$i]['prodId'] : 0;
									$pArray['prod_name'] = isset($arrClientProds[$i]['prodName']) ? $arrClientProds[$i]['prodName'] : '';
									$pArray['prod_price'] = isset($arrClientProds[$i]['prodPrice']) ? $arrClientProds[$i]['prodPrice'] : 0.00;
									$pArray['prod_quantity'] = isset($arrClientProds[$i]['prodQuantity']) ? $arrClientProds[$i]['prodQuantity'] : 0;
									$prodAttribs = isset($arrClientProds[$i]['attribs']) ? $arrClientProds[$i]['attribs'] : array();
									if (count($prodAttribs) > 0) {
										$pArray['prod_attribs'] = json_encode($prodAttribs);
									}else{
										$pArray['prod_attribs'] = '';
									}
									
									$insertOrderDetails = $this->objStore->insertQuery($pArray, $pTableName, true);
									if (!$insertOrderDetails) {
										$isOrderSuccess = 0;
									}
								}
								
							}
						}else{
							$isOrderSuccess = 0;
						}
					}
				}// update product if - end
				else{
	        		// insert order - Start
			        $pArray = array();
					$pTableName = 'orders';
					
					$pArray['user_id'] = $orderUserId;
					$pArray['order_total'] = $clientOrderTotal;
					$pArray['order_created_date'] = 'NOW()';
					$pArray['order_updated_date'] = 'NOW()';
					$pArray['order_status'] = 0;
					$pArray['order_sales_tax'] = $clientOrderSaleTax;
					$pArray['order_cart_total'] = $cartTotal;
					$pArray['order_shipping_cost'] = $shippingCost;

					$pArray['order_shipping_addr1'] = $orderShipAddr1;
					$pArray['order_shipping_addr2'] = $orderShipAddr2;
					$pArray['order_shipping_city'] = $orderShipCity;
					$pArray['order_shipping_state'] = $orderShipState;
					$pArray['order_shipping_zip'] = $orderShipZip;
					$pArray['order_shipping_country'] = $orderShipCountry;
					$pArray['order_session_id'] = $orderSessionId;
					$pArray['order_shipping_method'] = $orderShippingMethod;

					$insertOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
					if ($insertOrder){

						
						for($i=0;$i< count($arrClientProds); $i++){
							$isOrderSuccess = 1;
							$pArray = array();
							$pTableName = 'order_details';
							$pArray['order_id'] = $insertOrder;
							$pArray['prod_id'] = isset($arrClientProds[$i]['prodId']) ? $arrClientProds[$i]['prodId'] : 0;
							$pArray['prod_name'] = isset($arrClientProds[$i]['prodName']) ? $arrClientProds[$i]['prodName'] : '';
							$pArray['prod_price'] = isset($arrClientProds[$i]['prodPrice']) ? $arrClientProds[$i]['prodPrice'] : 0.00;
							$pArray['prod_quantity'] = isset($arrClientProds[$i]['prodQuantity']) ? $arrClientProds[$i]['prodQuantity'] : 0;
							$prodAttribs = isset($arrClientProds[$i]['attribs']) ? $arrClientProds[$i]['attribs'] : array();
							if (count($prodAttribs) > 0) {
								$pArray['prod_attribs'] = json_encode($prodAttribs);
							}else{
								$pArray['prod_attribs'] = '';
							}
							
							$insertOrderDetails = $this->objStore->insertQuery($pArray, $pTableName, true);
							if (!$insertOrderDetails) {
								$isOrderSuccess = 0;
							}
						}
						$clientOrderIDs[$c]['orderId'] = $insertOrder;
					// insert order - End
					}
				}
			}//for client loop - end
			$arrOrderInfo['clientOrderIDs'] = json_encode($clientOrderIDs);
			if ($isOrderSuccess==1) {
				$isOrderUpdateInMain = 0;
				$isOrderUpdateInMain = $this->doUserOrderInMain($arrOrderInfo);
				if ($isOrderUpdateInMain > 1) {
					$isOrderSuccess = 1;

				}
			}
			if ($isOrderSuccess !=1) {
			 	$outArrOrder['msg']='fail';
			 } else{
			 	$outArrOrder['msg']='success';
			 	$outArrOrder['orderID']=$orderId;
			 	$outArrOrder['paypalCustomMsg']='Payment Process';
			 	$outArrOrder['payment']['failMsg']='Opps! Your payment is not success. \n\nPlease contact us for more details.';
			 	$outArrOrder['payment']['successMsg']='Your payment is success. \nYou will receive payment receipt soon. \n\nThank you for your order.';
			 	$outArrOrder['payment']['saveOrderMsg']='Thank you for saving your order. \n\nPlease initiate your order from Settings -> My Orders.';
			 }
		 
		 	echo json_encode($outArrOrder);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function doUserOrderInMain($pUserInfo){
		try{
			global $config;	
			$this->objStore->connectToDB($config['database']['name']);

			$isOrderUpdateInMainInside = 0;
			$arrOrderInfo = $pUserInfo;
			
			$orderId = isset($arrOrderInfo['orderId']) ? $arrOrderInfo['orderId'] : 0;
			$userId = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;
			$orderTotal = isset($arrOrderInfo['orderTotal']) ? $arrOrderInfo['orderTotal'] : 0.00;
			$orderSalesTax = isset($arrOrderInfo['salesTax']) ? $arrOrderInfo['salesTax'] : 0.00;
			$orderSalesTaxValue = isset($arrOrderInfo['salesTaxValue']) ? $arrOrderInfo['salesTaxValue'] : 0.00;
			$clientOrderIds = isset($arrOrderInfo['clientOrderIDs']) ? $arrOrderInfo['clientOrderIDs'] : '';
			$paymentMethodId = isset($arrOrderInfo['paymentMethodId']) ? $arrOrderInfo['paymentMethodId'] : 0;
			if ($orderId == 0) {
				$pArray = array();
				$pTableName = 'user_orders';
				$pArray['user_id'] = $userId;
				$pArray['user_order_total'] = $orderTotal;
				$pArray['user_order_status'] = 1;
				$pArray['user_sales_tax'] = $orderSalesTax;
				$pArray['user_sales_tax_value'] = $orderSalesTaxValue;
				$pArray['user_order_created_date'] = 'NOW()';
				$pArray['user_order_updated_date'] = 'NOW()';
				$pArray['user_order_client_ids'] = $clientOrderIds;
				$pArray['user_pay_method_id'] = $paymentMethodId;
				
				$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
				$orderID = $insertUserOrder;
			}else{
				$con = array();
				$wdArray = array();
				$con['user_order_id'] = $orderId;
				$con['user_id'] = $userId;
				$wdArray['user_order_total'] = $orderTotal;
				$wdArray['user_order_status'] = 1;
				$wdArray['user_sales_tax'] = $orderSalesTax;
				$wdArray['user_sales_tax_value'] = $orderSalesTaxValue;
				$wdArray['user_order_updated_date'] = 'NOW()';
				$wdArray['user_order_client_ids'] = $clientOrderIds;
				$updateUserOrder = $this->objStore->updateRecordQuery($wdArray, "user_orders", $con);
				$orderID = $orderId;
			}
			if ($orderID > 0) {
				$pArray = array();
				$pTableName = 'user_ship_address';
				$userId = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;
				$userShipId = isset($arrOrderInfo['userShipId']) ? $arrOrderInfo['userShipId'] : 0;
				$pArray['user_id'] = $userId;
				$pArray['user_ship_addr1'] = isset($arrOrderInfo['shippingAddress']['addr1']) ? $arrOrderInfo['shippingAddress']['addr1'] : '';
				$pArray['user_ship_addr2'] = isset($arrOrderInfo['shippingAddress']['addr2']) ? $arrOrderInfo['shippingAddress']['addr2'] : '';
				$pArray['user_ship_city'] = isset($arrOrderInfo['shippingAddress']['city']) ? $arrOrderInfo['shippingAddress']['city'] : '';
				$pArray['user_ship_state'] = isset($arrOrderInfo['shippingAddress']['state']) ? $arrOrderInfo['shippingAddress']['state'] : '';
				$pArray['user_ship_zip'] = isset($arrOrderInfo['shippingAddress']['zip']) ? $arrOrderInfo['shippingAddress']['zip'] : '';
				$pArray['user_ship_country'] = isset($arrOrderInfo['shippingAddress']['country']) ? $arrOrderInfo['shippingAddress']['country'] : '';
				$pArray['user_order_id'] = $insertUserOrder;

				if ($userShipId > 0) {
					$checkUserShipExists = $this->objStore->CheckUserShipAddress($userId, $userShipId);
					if (count($checkUserShipExists) > 0) {
						$con = array();
						$con['user_ship_addr_id'] = $userShipId;
						$con['user_id'] = $userId;
						$updateUserOrder = $this->objStore->updateRecordQuery($pArray, $pTableName, $con);
					}else{
						$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
					}
				}else {
					$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
				}
				

				$pArray = array();
				$pTableName = 'user_order_analytics';
				$pArray['user_id'] = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;
				$pArray['session_id'] = isset($arrOrderInfo['analytics']['session_id']) ? $arrOrderInfo['analytics']['session_id'] : '';
				$pArray['lat_long'] = isset($arrOrderInfo['analytics']['lat_long']) ? $arrOrderInfo['analytics']['lat_long'] : '';
				$pArray['user_order_created_date'] = 'NOW()';
				$pArray['device_type'] = isset($arrOrderInfo['analytics']['device_type']) ? $arrOrderInfo['analytics']['device_type'] : '';
				$pArray['device_os_version'] = isset($arrOrderInfo['analytics']['device_os_version']) ? $arrOrderInfo['analytics']['device_os_version'] : '';
				$pArray['device_os'] = isset($arrOrderInfo['analytics']['device_os']) ? $arrOrderInfo['analytics']['device_os'] : '';
				$pArray['device_brand'] = isset($arrOrderInfo['analytics']['device_brand']) ? $arrOrderInfo['analytics']['device_brand'] : '';
				$pArray['build_number'] = isset($arrOrderInfo['analytics']['device_bundle_version']) ? $arrOrderInfo['analytics']['device_bundle_version'] : '';
				$pArray['user_order_id'] = $insertUserOrder;
				$pArray['datapoint_id'] = 42;
				$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
				$isOrderUpdateInMainInside = $orderID;
			}

			
			return $isOrderUpdateInMainInside;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getDummyClientOrder(){
		try{
			
			global $config;	
			// $pUserId = 9;
			// $pOrderId = 7;
			// $this->getInvoiceHTMLMsg($pOrderId,$pUserId);
		
			$arrClientProds = array();
			$arrOrderInfo['userId'] = 9;
			$arrOrderInfo['orderTotal'] = 989898.00;
			$arrOrderInfo['salesTax'] = 19.00;
			$arrOrderInfo['salesTaxValue'] = 6.5;
			$arrOrderInfo['orderId'] = 8;

			$arrOrderInfo['shippingAddress']['addr1'] = "Sufi Chambers";
			$arrOrderInfo['shippingAddress']['addr2'] = "Banjara Hills";
			$arrOrderInfo['shippingAddress']['city'] = "Hyderabad";
			$arrOrderInfo['shippingAddress']['state'] = "TS";
			$arrOrderInfo['shippingAddress']['zip'] = "500034";
			$arrOrderInfo['shippingAddress']['country'] = "India";

			$arrOrderInfo['analytics']['session_id'] = "kjklj998098jkkjlkj98";
			$arrOrderInfo['analytics']['device_type'] = "Apple";
			$arrOrderInfo['analytics']['device_os_version'] = "7.1";
			$arrOrderInfo['analytics']['device_os'] = "iOS";
			$arrOrderInfo['analytics']['device_brand'] = "iPad";
			$arrOrderInfo['analytics']['lat_long'] = "76.22121,87.989898";
			$arrOrderInfo['analytics']['device_bundle_version'] = "2.9";
			
			$arrOrderInfo['clients'][0]['clientName'] = "SeeMore";
			$arrOrderInfo['clients'][0]['clientId'] = 3397;
			$arrOrderInfo['clients'][0]['clientTotal'] = 2873.99;
			$arrOrderInfo['clients'][0]['cartTotal'] = 2873.99;
			$arrOrderInfo['clients'][0]['shippingMethod'] = "5-7 Business days";
			$arrOrderInfo['clients'][0]['shippingCost'] = 39.00;
			
			$arrOrderInfo['clients'][0]['products'][0]['prodId'] = 4321;
			$arrOrderInfo['clients'][0]['products'][0]['prodName'] = "Chair";
			$arrOrderInfo['clients'][0]['products'][0]['prodPrice'] = 877.99;
			$arrOrderInfo['clients'][0]['products'][0]['prodQuantity'] = 9;
			$arrOrderInfo['clients'][0]['products'][0]['attribs']['size']="XL";
			$arrOrderInfo['clients'][0]['products'][0]['attribs']['color']="#C5C5C5";
			$arrOrderInfo['clients'][0]['products'][1]['prodId'] = 5272;
			$arrOrderInfo['clients'][0]['products'][1]['prodName'] = "Sofa";
			$arrOrderInfo['clients'][0]['products'][1]['prodPrice'] = 221.99;
			$arrOrderInfo['clients'][0]['products'][1]['prodQuantity'] = 1;
			$arrOrderInfo['clients'][0]['products'][1]['attribs']['size']="L";
			$arrOrderInfo['clients'][0]['products'][1]['attribs']['color']="#000000";
			$arrOrderInfo['clients'][0]['products'][2]['prodId'] = 2121;
			$arrOrderInfo['clients'][0]['products'][2]['prodName'] = "Bed";
			$arrOrderInfo['clients'][0]['products'][2]['prodPrice'] = 3564.99;
			$arrOrderInfo['clients'][0]['products'][2]['prodQuantity'] = 5;

			$arrOrderInfo['clients'][1]['clientId'] = 1420;
			$arrOrderInfo['clients'][1]['clientName'] = "Lookbook";
			$arrOrderInfo['clients'][1]['clientTotal'] = 4334.99;
			$arrOrderInfo['clients'][1]['cartTotal'] = 4334.99;
			$arrOrderInfo['clients'][1]['shippingMethod'] = "3-5 Business days";
			$arrOrderInfo['clients'][1]['shippingCost'] = 12.00;
			
			$arrOrderInfo['clients'][1]['products'][0]['prodId'] = 2343;
			$arrOrderInfo['clients'][1]['products'][0]['prodName'] = "Jeans";
			$arrOrderInfo['clients'][1]['products'][0]['prodPrice'] = 23.99;
			$arrOrderInfo['clients'][1]['products'][0]['prodQuantity'] = 1;
			$arrOrderInfo['clients'][1]['products'][0]['attribs']['size']="Medium";
			$arrOrderInfo['clients'][1]['products'][0]['attribs']['color']="#F5F5F5";
			$arrOrderInfo['clients'][1]['products'][1]['prodId'] = 345;
			$arrOrderInfo['clients'][1]['products'][1]['prodName'] = "Necklace";
			$arrOrderInfo['clients'][1]['products'][1]['prodPrice'] = 3453.99;
			$arrOrderInfo['clients'][1]['products'][1]['prodQuantity'] = 2;
			$arrOrderInfo['clients'][1]['products'][1]['attribs']['color']="#FFFFFF";
			
			return $arrOrderInfo;
		    // print_r($arrClientProds);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modOrderUpdate($pUserInfo){ 
		try{
			
			global $config;	
			$outArrOrderProcess = array();
			$arrMainOrderInfo = array();
			$isUpdateRecord = 0;
			
			$arrOrderProcess = $pUserInfo;

			// $arrOrderProcess['orderId'] = 306;
			// $arrOrderProcess['userId'] = 9;
			// $arrOrderProcess['paymentMethod'] = 'Paypal';
			// $arrOrderProcess['paymentMethodId'] = 2;
			// $arrOrderProcess['session_id'] = 'znqcEnNYvFlKnYWotLmHbM0YA321UMamFsIWutLz';
			// print_r($arrOrderProcess);
			
			$this->objStore->connectToDB($config['database']['name']);

			$con = array();
			$wdArray = array();	
			$con['user_order_id'] = $arrOrderProcess['orderId'];
			$con['user_id'] = $arrOrderProcess['userId'];	
			if(($con['user_order_id'] !=0) && ($con['user_id'] !=0)){
				$wdArray['user_order_status'] = 1;
				$wdArray['user_pay_method_id'] = $arrOrderProcess['paymentMethodId'];
				$wdArray['user_order_updated_date'] = 'NOW()';
				$updateUserOrder = $this->objStore->updateRecordQuery($wdArray, "user_orders", $con);
				if ($updateUserOrder) {
					$pArray = array();
					$pTableName = 'user_order_analytics';
					$pArray['user_id'] = isset($arrOrderProcess['userId']) ? $arrOrderProcess['userId'] : 0;
					$pArray['session_id'] = isset($arrOrderProcess['analytics']['session_id']) ? $arrOrderProcess['analytics']['session_id'] : '';
					$pArray['lat_long'] = isset($arrOrderProcess['analytics']['lat_long']) ? $arrOrderProcess['analytics']['lat_long'] : '';
					$pArray['user_order_created_date'] = 'NOW()';
					$pArray['device_type'] = isset($arrOrderProcess['analytics']['device_type']) ? $arrOrderProcess['analytics']['device_type'] : '';
					$pArray['device_os_version'] = isset($arrOrderProcess['analytics']['device_os_version']) ? $arrOrderProcess['analytics']['device_os_version'] : '';
					$pArray['device_os'] = isset($arrOrderProcess['analytics']['device_os']) ? $arrOrderProcess['analytics']['device_os'] : '';
					$pArray['device_brand'] = isset($arrOrderProcess['analytics']['device_brand']) ? $arrOrderProcess['analytics']['device_brand'] : '';
					$pArray['build_number'] = isset($arrOrderProcess['analytics']['device_bundle_version']) ? $arrOrderProcess['analytics']['device_bundle_version'] : '';
					$pArray['user_order_id'] = $arrOrderProcess['orderId'];
					$pArray['datapoint_id'] = 43;
					$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
				}
			}

			$arrMainOrderInfo = $this->objStore->getClientIdsFromMainOrder($arrOrderProcess['orderId'], $arrOrderProcess['userId']);
			$orderClientIds = $arrMainOrderInfo[0]['user_order_client_ids'];
			$arrClientIds = json_decode($orderClientIds,true);
			// print_r($arrClientIds);
			for ($i=0; $i < count($arrClientIds); $i++) { 
				$clientId = isset($arrClientIds[$i]['clientId']) ? $arrClientIds[$i]['clientId'] : 0;
				$orderId = isset($arrClientIds[$i]['orderId']) ? $arrClientIds[$i]['orderId'] : 0;
		    	$dbName = $this->modClientDB($clientId);
	        	$this->objStore->connectToDB($dbName);

	        	$con = array();
				$wdArray = array();	
				$con['order_id'] = $orderId;	
				if($con['order_id'] !=0){
					$wdArray['user_pay_method_id'] = $arrOrderProcess['paymentMethodId'];
					$wdArray['order_status'] = 1;
					$wdArray['order_updated_date'] = 'NOW()';
					$updateClientOrder = $this->objStore->updateRecordQuery($wdArray, "orders", $con);
					if ($updateClientOrder) {
						$isUpdateRecord = 1;
					}else{
						$isUpdateRecord = 0;
					}
				}
			}

			if ($isUpdateRecord ==1) {
				$outArrOrderProcess['msg'] = 'success';
			}else{
				$outArrOrderProcess['msg'] = 'fail';
				
			}
			echo json_encode($outArrOrderProcess);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modOrderFinal($pUserInfo){
		try{
			global $config;	
			$outFinalPaymentProcess = array();
			$arrFinalOrderProcess = array();
			// $arrFinalOrderProcess['orderId'] = 1;
			// $arrFinalOrderProcess['userId'] = 9;
			// $arrFinalOrderProcess['paymentMethod'] = 'Paypal';
			// $arrFinalOrderProcess['paymentMethodId'] = 1;
			// $arrFinalOrderProcess['paymentStatus'] = 'success';
			// $arrFinalOrderProcess['paymentRespId'] = 'PAY-6RV70583SB702805EKEYSZ6Y';
			// $arrFinalOrderProcess['paymentAmount'] = 23424.22;
			// $arrFinalOrderProcess['paymentSubTotal'] = 424.22;
			// $arrFinalOrderProcess['paymentShippingCost'] = 2.29;
			// $arrFinalOrderProcess['paymentTax'] = 344.42;

			// $arrFinalOrderProcess['analytics']['device_type'] = "Apple";
			// $arrFinalOrderProcess['analytics']['device_os_version'] = "7.1";
			// $arrFinalOrderProcess['analytics']['device_os'] = "iOS";
			// $arrFinalOrderProcess['analytics']['device_brand'] = "iPad";
			// $arrFinalOrderProcess['analytics']['lat_long'] = "76.22121,87.989898";
			// $arrFinalOrderProcess['analytics']['device_bundle_version'] = "2.9";
			// $arrFinalOrderProcess['analytics']['session_id'] = "7879sdf79a8sdf79a8sd";

			$arrFinalOrderProcess = $pUserInfo;

			// print_r($arrFinalOrderProcess);
			if ($arrFinalOrderProcess['paymentStatus'] == 'success') {
				$this->doInvoiceByClient($arrFinalOrderProcess);
				$outFinalPaymentProcess['msg'] = 'success'; 
			}else{
				$outFinalPaymentProcess['msg'] = 'fail';
			}
			echo json_encode($outFinalPaymentProcess);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function doInvoiceByClient($arrFinalOrderProcess){
		try{
			
			global $config;	
		
			$this->objStore->connectToDB($config['database']['name']);

			$con = array();
			$wdArray = array();	
			$insertUserOrderPayment = 0;
			$con['user_order_id'] = $arrFinalOrderProcess['orderId'];
			$con['user_id'] = $arrFinalOrderProcess['userId'];	
			if(($con['user_order_id'] !=0) && ($con['user_id'] !=0)){
				$wdArray['user_order_status'] = 2;
				$wdArray['user_pay_method_id'] = $arrFinalOrderProcess['paymentMethodId'];
				$wdArray['user_order_updated_date'] = 'NOW()';
				$updateUserOrder = $this->objStore->updateRecordQuery($wdArray, "user_orders", $con);
				if ($updateUserOrder) {
					$pArray = array();
					$pTableName = 'user_order_payment';
					$pArray['user_pay_method_id'] = $arrFinalOrderProcess['paymentMethodId'];
					$pArray['user_pay_amount'] = $arrFinalOrderProcess['paymentAmount'];
					$pArray['user_pay_status'] = 1;
					$pArray['user_order_id'] = $arrFinalOrderProcess['orderId'];
					$pArray['user_id'] = $arrFinalOrderProcess['userId'];
					$pArray['user_pay_resp_id'] = $arrFinalOrderProcess['paymentRespId'];
					$pArray['user_pay_subtotal'] = $arrFinalOrderProcess['paymentSubTotal'];
					$pArray['user_pay_shipping'] = $arrFinalOrderProcess['paymentShippingCost'];
					$pArray['user_pay_tax'] = $arrFinalOrderProcess['paymentTax'];
					$pArray['user_pay_created_date'] = 'NOW()';
			
					$insertUserOrderPayment = $this->objStore->insertQuery($pArray, $pTableName, true);
					if ($insertUserOrderPayment) {
						$pArray = array();
						$pTableName = 'user_order_analytics';
						$pArray['user_id'] = isset($arrFinalOrderProcess['userId']) ? $arrFinalOrderProcess['userId'] : 0;
						$pArray['session_id'] = isset($arrFinalOrderProcess['analytics']['session_id']) ? $arrFinalOrderProcess['analytics']['session_id'] : '';
						$pArray['lat_long'] = isset($arrFinalOrderProcess['analytics']['lat_long']) ? $arrFinalOrderProcess['analytics']['lat_long'] : '';
						$pArray['user_order_created_date'] = 'NOW()';
						$pArray['device_type'] = isset($arrFinalOrderProcess['analytics']['device_type']) ? $arrFinalOrderProcess['analytics']['device_type'] : '';
						$pArray['device_os_version'] = isset($arrFinalOrderProcess['analytics']['device_os_version']) ? $arrFinalOrderProcess['analytics']['device_os_version'] : '';
						$pArray['device_os'] = isset($arrFinalOrderProcess['analytics']['device_os']) ? $arrFinalOrderProcess['analytics']['device_os'] : '';
						$pArray['device_brand'] = isset($arrFinalOrderProcess['analytics']['device_brand']) ? $arrFinalOrderProcess['analytics']['device_brand'] : '';
						$pArray['build_number'] = isset($arrFinalOrderProcess['analytics']['device_bundle_version']) ? $arrFinalOrderProcess['analytics']['device_bundle_version'] : '';
						$pArray['user_order_id'] = $arrFinalOrderProcess['orderId'];;
						$pArray['datapoint_id'] = 44;
						$insertUserOrderAnalytics = $this->objStore->insertQuery($pArray, $pTableName, true);
					}
				}
			}

			$arrMainOrderInfo = $this->objStore->getClientIdsFromMainOrder($arrFinalOrderProcess['orderId'], $arrFinalOrderProcess['userId']);
			$orderClientIds = $arrMainOrderInfo[0]['user_order_client_ids'];
			$arrClientIds = json_decode($orderClientIds,true);
			// print_r($arrClientIds);
			for ($i=0; $i < count($arrClientIds); $i++) { 
				$clientId = isset($arrClientIds[$i]['clientId']) ? $arrClientIds[$i]['clientId'] : 0;
				$orderId = isset($arrClientIds[$i]['orderId']) ? $arrClientIds[$i]['orderId'] : 0;
		    	$dbName = $this->modClientDB($clientId);
	        	$this->objStore->connectToDB($dbName);

	        	$con = array();
				$wdArray = array();	
				$con['order_id'] = $orderId;	
				if($con['order_id'] !=0){
					$wdArray['user_pay_method_id'] = $arrFinalOrderProcess['paymentMethodId'];
					$wdArray['order_status'] = 2;
					$wdArray['order_updated_date'] = 'NOW()';
					$updateClientOrder = $this->objStore->updateRecordQuery($wdArray, "orders", $con);
					if ($updateClientOrder) {
						$isUpdateRecord = 1;
					}else{
						$isUpdateRecord = 0;
					}
				}
			}

			if ($isUpdateRecord ==1) {
				$outArrOrderProcess['msg'] = 'success';
				$this->getInvoiceHTMLMsg($arrFinalOrderProcess['orderId'],$arrFinalOrderProcess['userId']);
			}else{
				$outArrOrderProcess['msg'] = 'fail';
			}
			echo json_encode($outArrOrderProcess);


			// $invoiceMsg = $this->getInvoiceHTMLMsg($pOrderId);
			// $this->sendInvoiceByMail($invoiceMsg);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function sendInvoiceByMail($pInvoiceMsg){
		try{
			global $config;	
			
			$mailto = "vikram.nerasu@digitalimperia.com,manohar.dumpati@digitalimperia.com";
			$from_name = "SeeMore Interactive";
			$from_mail = $config['FROM_EMAIL'];
			$replyto = $config['FROM_EMAIL'];
			$subject = "SeeMore Interactive - Order Confirmation";
			$message = $pInvoiceMsg;
			$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
			// if ($mailStatus=="OK"){
			// 	$outArrRegisterUser['msg'] = 'success';
			// }else{
			// 	if ($userFBId=='') {
			// 		$outArrRegisterUser['msg'] = 'Error occured while sending an email. Please contact us.';
			// 	}else{
			// 		$outArrRegisterUser['msg'] = 'success';
			// 	}
				
			// }
			//echo json_encode($outArrRegisterUser);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getInvoiceHTMLMsg($pOrderId, $pUserId){
		try{
			// echo 'sendInvoiceByMail';
			global $config;	
			$arrMainOrderInfo = array();
			$arrClientDetails = array();
			$arrUserInfo = array();

			$this->objStore->connectToDB($config['database']['name']);
			$arrMainOrderInfo = $this->objStore->getClientIdsFromMainOrder($pOrderId, $pUserId);
			$arrUserInfo = $this->objStore->getUserInfo($pUserId);

			$userFirstName = isset($arrUserInfo[0]['user_firstname']) ? $arrUserInfo[0]['user_firstname'] : '';
			$userLastName = isset($arrUserInfo[0]['user_lastname']) ? $arrUserInfo[0]['user_lastname'] : '';
			$userFullName = $userFirstName.' '.$userLastName;
			$userEmail = isset($arrUserInfo[0]['user_email_id']) ? $arrUserInfo[0]['user_email_id'] : '';
		
			$orderClientIds = $arrMainOrderInfo[0]['user_order_client_ids'];
			$arrClientIds = json_decode($orderClientIds,true);
			for ($i=0; $i < count($arrClientIds); $i++) { 
				$clientId = isset($arrClientIds[$i]['clientId']) ? $arrClientIds[$i]['clientId'] : 0;
				$arrClientInfo = $this->objStore->getClientInfo($clientId);
				$arrClientDetails[$clientId] = isset($arrClientInfo[0]) ? $arrClientInfo[0] : array();
			
				
	        }
	        // print_r($arrClientIds);
	        $invoiceMsg = '';
	        for ($i=0; $i < count($arrClientIds); $i++) { 

	        	$arrClientOrders = array();

	        	$clientId = isset($arrClientIds[$i]['clientId']) ? $arrClientIds[$i]['clientId'] : 0;
	        	$orderId = isset($arrClientIds[$i]['orderId']) ? $arrClientIds[$i]['orderId'] : 0;
		    	
	        	//Client Details
	        	$clientCompany = isset($arrClientDetails[$clientId]['client_details_company']) ? $arrClientDetails[$clientId]['client_details_company'] : '';
	        	$clientAddress = isset($arrClientDetails[$clientId]['client_details_address']) ? $arrClientDetails[$clientId]['client_details_address'] : '';
	        	$clientCity = isset($arrClientDetails[$clientId]['client_details_city']) ? $arrClientDetails[$clientId]['client_details_city'] : '';
	        	$clientState = isset($arrClientDetails[$clientId]['client_details_state']) ? $arrClientDetails[$clientId]['client_details_state'] : '';
	        	$clientZip = isset($arrClientDetails[$clientId]['client_details_zip']) ? $arrClientDetails[$clientId]['client_details_zip'] : '';
	        	$clientPhone = isset($arrClientDetails[$clientId]['client_details_phone']) ? $arrClientDetails[$clientId]['client_details_phone'] : '';
	        	$clientEmail = isset($arrClientDetails[$clientId]['client_details_email']) ? $arrClientDetails[$clientId]['client_details_email'] : '';
	        	$clientWebsite = isset($arrClientDetails[$clientId]['url']) ? $arrClientDetails[$clientId]['url'] : '';
	        	$clientCountryCode = isset($arrClientDetails[$clientId]['client_details_country_code']) ? $arrClientDetails[$clientId]['client_details_country_code'] : '';
	        	$clientCountryCurrencyCode = isset($arrClientDetails[$clientId]['client_details_currency_code']) ? $arrClientDetails[$clientId]['client_details_currency_code'] : '';
	        	$clientLogoImage = isset($arrClientDetails[$clientId]['logo']) ? $arrClientDetails[$clientId]['logo'] : '';
	        	$clientLogo = '';
				if ($clientLogoImage !='') {
					$clientLogo= str_replace("{client_id}",$clientId,$config['files']['logo']).$clientLogoImage;
				}
				
	        	//Client Address
	        	$clientAddressDisp = $clientCompany.'<br>'.$clientAddress.'<br>'.$clientCity.', '.$clientState.' '.$clientZip.'<br>'.$clientCountryCode;
	        	$clientAddressDisp .= '<br>'.$clientPhone.'<br>'.$clientEmail.'<br>'.$clientWebsite;
	        	
	        	//Client related order info

	        	//Connect to Client Database
	        	$dbName = $this->modClientDB($clientId);
	        	$this->objStore->connectToDB($dbName);

	        	//Get Client order info
	        	$arrClientOrders = $this->objStore->getClientOrders($orderId);
	        	// print_r($arrClientOrders);

	        	//Shipping Address
	        	$userShipAddress1 = isset($arrClientOrders[0]['order_shipping_addr1']) ? $arrClientOrders[0]['order_shipping_addr1'] : '';
	        	$userShipAddress2 = isset($arrClientOrders[0]['order_shipping_addr2']) ? $arrClientOrders[0]['order_shipping_addr2'] : '';
	        	$userShipAddress = $userShipAddress1.' '.$userShipAddress2;
	        	$userShipCity = isset($arrClientOrders[0]['order_shipping_city']) ? $arrClientOrders[0]['order_shipping_city'] : '';
	        	$userShipState = isset($arrClientOrders[0]['order_shipping_state']) ? $arrClientOrders[0]['order_shipping_state'] : '';
	        	$userShipZip = isset($arrClientOrders[0]['order_shipping_zip']) ? $arrClientOrders[0]['order_shipping_zip'] : '';
	        	$userShipCountryCode = isset($arrClientOrders[0]['order_shipping_country']) ? $arrClientOrders[0]['order_shipping_country'] : '';
	      
	      		//Make complete Shipping Address
	      		$userFullShippingAddr = $userFullName.'<br>'.$userShipAddress.'<br>'.$userShipCity.', '.$userShipState.' '.$userShipZip;
	      		$userFullShippingAddr .= '<br>'.$userEmail;

	      		//Order Date, Amount and Invoice number
	      		$orderCreatedDate = isset($arrClientOrders[0]['order_created_date']) ? $arrClientOrders[0]['order_created_date'] : '';
	    		$orderConvertedDate = date("jS F, Y", strtotime($orderCreatedDate));
				$orderYear = date("Y");
				// if ($insertPaymentInvoice) {
				// 	$invNumber = $invYear.'/'.$insertPaymentInvoice;
				// }
				$orderAmount = isset($arrClientOrders[0]['order_total']) ? $arrClientOrders[0]['order_total'] : 0.00;
				$orderAmountDisp = $clientCountryCurrencyCode.' '.$orderAmount;

				//Get order details for cart display
				$arrClientOrdersDetails = $this->objStore->getClientOrderDetails($orderId);
				// print_r($arrClientOrdersDetails);

				//Order Subtotal, Shipping cost, Tax and Grand total
				$orderCartTotal =  isset($arrClientOrders[0]['order_cart_total']) ? $arrClientOrders[0]['order_cart_total'] : 0.00;
				$orderShippingCost =  isset($arrClientOrders[0]['order_shipping_cost']) ? $arrClientOrders[0]['order_shipping_cost'] : 0.00;
				$orderSalesTax =  isset($arrClientOrders[0]['order_sales_tax']) ? $arrClientOrders[0]['order_sales_tax'] : 0.00;
				$orderGrandTotal =  ($orderCartTotal + $orderShippingCost + $orderSalesTax);
				$orderShippingMethod =  isset($arrClientOrders[0]['order_shipping_method']) ? $arrClientOrders[0]['order_shipping_method'] : '';
				$orderShippingMethodDisp = '';
				if ($orderShippingMethod !='') {
					$orderShippingMethodDisp =  '<span style="font-weight: normal;font-size: 0.8em;line-height: 10px;"><br>('.$orderShippingMethod.')</span>';
				}
				

	        	$invoiceMsg = '<div style="font-size;12px;">
				<h1 style="background-color:black;color:#ffffff;padding:5px;text-align: center;">Order Confirmation</h1>
				<div>
					<div style="float:left;">
						'.$clientAddressDisp.'
					</div>
				<span><img alt="" src="'.$clientLogo.'" style="float:right;width:30%;"></span>
				</div>
				<div style="clear:both;">
					<div style="clear:both;padding-top: 20px;">
					<div style="float:left;">
						'.$userFullShippingAddr.'
					</div>
					<table style="float:right;">
						<tr>
							<th style="text-align: right;">Order ID #:&nbsp;</th>
							<td>'.$orderId.'</td>
						</tr>
						<tr>
							<th style="text-align: right;">Order Date:&nbsp;</th>
							<td>'.$orderConvertedDate.'</td>
						</tr>
						<tr>
							<th style="text-align: right;">Order Total:&nbsp;</th>
							<td>'.$orderAmountDisp.'</td>
						</tr>
					</table>
					</div>
					
					<div style="clear:both;padding-top: 20px;">
						<table style="width:100%;" cellpadding="10">
							<thead>
								<tr style="background-color: #939393;">
									<th>Sl. No.</th>
									<th>Item</th>
									<th>Rate</th>
									<th>Quantity</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								';
									for ($j=0; $j < count($arrClientOrdersDetails); $j++) { 
										$color = '#FFFFFF';
										if (($colorCount/2) == 0) {
											$color = '#E5E5E5';
										}else{
											$color = '#C5C5C5';
										}
										
										//Product Cart details
										$prodId = isset($arrClientOrdersDetails[$j]['prod_id']) ? $arrClientOrdersDetails[$j]['prod_id'] : 0;
										$prodName = isset($arrClientOrdersDetails[$j]['prod_name']) ? $arrClientOrdersDetails[$j]['prod_name'] : 0;
										$prodPrice = isset($arrClientOrdersDetails[$j]['prod_price']) ? $arrClientOrdersDetails[$j]['prod_price'] : 0;
										$prodQuantity = isset($arrClientOrdersDetails[$j]['prod_quantity']) ? $arrClientOrdersDetails[$j]['prod_quantity'] : 0;
										$prodAttribs = isset($arrClientOrdersDetails[$j]['prod_attribs']) ? $arrClientOrdersDetails[$j]['prod_attribs'] : 0;
										$arrProdAttribsByClient = array();
										$arrProdAttribsByClient = json_decode($prodAttribs, true);
										$attribString = '';
										if (is_array($arrProdAttribsByClient)) {
											$attribString = '<br><span style="font-size:0.8em;font-weight:normal;">';
											foreach ($arrProdAttribsByClient AS $attribTitle => $attribValue) {
												$attribString .= $attribTitle.': '.$attribValue.'<br>';
											}
											$attribString .= '</span>';
										}
											
										$invoiceMsg .= '<tr style="text-align: center;background-color:'.$color.'">
											<td>'.($i+1).'</td>
											<td>'.$prodName.$attribString.'</td>
											<td>'.$clientCountryCurrencyCode.' '.$prodPrice.'</td>
											<td>'.$prodQuantity.'</td>
											<td>'.$clientCountryCurrencyCode.' '.($prodPrice * $prodQuantity).'</td>
										</tr>';
									}
									
					$invoiceMsg .= '
							</tbody>
						</table>
					</div>
					<div style="height:20px;">&nbsp;</div>
					<table style="float:right;line-height: 30px;width: 44%;" cellpadding="5">
						<tr style="background-color:#FAEBD7">
							<th style="width: 54%;text-align: right;">Sub Total</th>
							<td style="text-align:center;"><span data-prefix>'.$clientCountryCurrencyCode.'</span> <span>'.$orderCartTotal.'</span></td>
						</tr>
						<tr style="background-color:#FAEBD7">
							<th style="width: 54%;text-align: right;">Shipping Cost'.$orderShippingMethodDisp.'</th>
							<td style="text-align:center;"><span data-prefix>'.$clientCountryCurrencyCode.'</span> </span>'.$orderShippingCost.'</td>
						</tr>
						<tr style="background-color:#FAEBD7">
							<th style="width: 54%;text-align: right;">Sales Tax</th>
							<td style="text-align:center;"><span data-prefix>'.$clientCountryCurrencyCode.'</span> </span><span>'.$orderSalesTax.'</span></td>
						</tr>
						<tr style="background-color:#FAEBD7">
							<th style="width: 54%;text-align: right;">Total</th>
							<td style="text-align:center;"><span data-prefix>'.$clientCountryCurrencyCode.'</span> </span><span>'.$orderGrandTotal.'</span></td>
						</tr>
					</table>
				</div>
				<div style="height:20px;clear:both;">&nbsp;</div>
				<div>
					<h3 style="text-align: center;">Thank you for your order.</h3>
				</div>
				</div>';
				// echo $invoiceMsg;
				$this->sendInvoiceByMail($invoiceMsg);
	        }
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message){
		global $config;	
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
	public function modUserShipping($pUserInfo){
		try{
			global $config;	
			// echo "modUserShipping";
			$outArrUserShipAddr = array();
			$userId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;			

			$outArrUserShipAddr = $this->objStore->getuserShipAddress($userId);
			
			echo json_encode($outArrUserShipAddr);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserShippingUpdate($arrOrderInfo){
		try{
			// echo "modUserShippingUpdate";
			global $config;	
			$pArray = array();
			$outUserShippingAddr = array();
			$isUserShipUpdated = 0;
			$userShipAddressId = 0;

			$pTableName = 'user_ship_address';
			$userId = isset($arrOrderInfo['userId']) ? $arrOrderInfo['userId'] : 0;
			$userShipId = isset($arrOrderInfo['userShipId']) ? $arrOrderInfo['userShipId'] : 0;
			$pArray['user_id'] = $userId;
			$pArray['user_ship_addr1'] = isset($arrOrderInfo['shippingAddress']['addr1']) ? $arrOrderInfo['shippingAddress']['addr1'] : '';
			$pArray['user_ship_addr2'] = isset($arrOrderInfo['shippingAddress']['addr2']) ? $arrOrderInfo['shippingAddress']['addr2'] : '';
			$pArray['user_ship_city'] = isset($arrOrderInfo['shippingAddress']['city']) ? $arrOrderInfo['shippingAddress']['city'] : '';
			$pArray['user_ship_state'] = isset($arrOrderInfo['shippingAddress']['state']) ? $arrOrderInfo['shippingAddress']['state'] : '';
			$pArray['user_ship_zip'] = isset($arrOrderInfo['shippingAddress']['zip']) ? $arrOrderInfo['shippingAddress']['zip'] : '';
			$pArray['user_ship_country'] = isset($arrOrderInfo['shippingAddress']['country']) ? $arrOrderInfo['shippingAddress']['country'] : '';
			$pArray['user_order_id'] = isset($arrOrderInfo['userOrderID']) ? $arrOrderInfo['userOrderID'] : 0;

			if ($userId > 0) {
				if ($userShipId > 0) {
					$checkUserShipExists = $this->objStore->CheckUserShipAddress($userId, $userShipId);
					if (count($checkUserShipExists) > 0) {
						$con = array();
						$con['user_ship_addr_id'] = $userShipId;
						$con['user_id'] = $userId;
						$updateUserOrder = $this->objStore->updateRecordQuery($pArray, $pTableName, $con);
						if ($userShipId > 0) {
							$isUserShipUpdated = 1;
							$userShipAddressId = $userShipId;
						}
					}else{
						$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
						if ($insertUserOrder > 0) {
							$isUserShipUpdated = 1;
							$userShipAddressId = $insertUserOrder;
						}
					}
				}else {
					$insertUserOrder = $this->objStore->insertQuery($pArray, $pTableName, true);
					if ($insertUserOrder > 0) {
						$isUserShipUpdated = 1;
						$userShipAddressId = $insertUserOrder;
					}
				}
			}

			if ($isUserShipUpdated > 0) {
				$outUserShippingAddr['msg'] = 'success';
				$outUserShippingAddr['userShipId'] = $userShipAddressId;
			}else{
				$outUserShippingAddr['msg'] = 'fail';
			}
			echo json_encode($outUserShippingAddr);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserShippingDelete($pUserInfo){
		try{
			// echo "modUserShippingUpdate";
			global $config;	
			$pArray = array();
			$outUserShipStatus = array();
			$pTableName = 'user_ship_address';
			$userId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;
			$userShipId = isset($pUserInfo['userShipId']) ? $pUserInfo['userShipId'] : 0;

			$pArray['user_ship_status'] = 2;
			
			$con = array();
			$con['user_ship_addr_id'] = $userShipId;
			$con['user_id'] = $userId;
			if ($con['user_id'] !=0 && $con['user_ship_addr_id'] != 0) {
				$updateUserOrder = $this->objStore->updateRecordQuery($pArray, $pTableName, $con);
				$outUserShipStatus['msg'] = 'success';
			}else{
				$outUserShipStatus['msg'] = 'fail';
			}
			echo json_encode($outUserShipStatus);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function doGetProdAttribValues(){
		try{
			global $config;	
			$dbName = $this->modClientDB(3397);
        	$this->objStore->connectToDB($dbName);

			echo "doGetProdAttribValues.";
			$arrMainProdAttribList = array();
			$outArrMainProdAttribList = array();

			$prodId = 5892;

			$arrMainProdAttribList = $this->objStore->getProdAttribSet($prodId);
			for ($i=0; $i < count($arrMainProdAttribList); $i++) { 
				$attribRefSet = isset($arrMainProdAttribList[$i]['attrib_ref_set']) ? $arrMainProdAttribList[$i]['attrib_ref_set'] : '';
				if ($attribRefSet !='') {
					$arrAttribRefSet = json_decode($attribRefSet,true);
					// $outArrMainProdAttribList[]['attribRefSet'] = $attribRefSet;
					// print_r($arrAttribRefSet);
					$c = 1;
					for ($j=0; $j < count($arrAttribRefSet); $j++) { 
						echo 'attribRefId: '.$attribRefId = isset($arrAttribRefSet[$j]) ? $arrAttribRefSet[$j] : 0;
						$outArrMainProdAttribList[$attribRefId][] = 'hello';
						// if ($attribRefId > 0) {
						// 	$arrActualAttribs = array();
						// 	$arrActualAttribs = $this->objStore->getCompleteAttribInfo($attribRefId);
						// 	print_r($arrActualAttribs);
						// 	if ($c == 1) {
						// 		$attribKey = isset($arrActualAttribs[0]['attrib_name']) ? $arrActualAttribs[0]['attrib_name'] : '';
						// 		$attribValue = isset($arrActualAttribs[0]['attrib_value']) ? $arrActualAttribs[0]['attrib_value'] : '';
						// 		echo 'in c =1';
						// 		$outArrMainProdAttribList[$attribKey][$attribValue] = $attribValue;
						// 	}elseif ($c == 2) {
						// 		$outArrMainProdAttribList = array();
						// 		echo 'in c =2';
						// 		echo $attribKey2 = isset($arrActualAttribs[0]['attrib_name']) ? $arrActualAttribs[0]['attrib_name'] : '';
						// 		echo $attribValue2 = isset($arrActualAttribs[0]['attrib_value']) ? $arrActualAttribs[0]['attrib_value'] : '';
						// 		$outArrMainProdAttribList[$attribKey][$attribValue][$attribKey2][$attribValue2] = $attribValue2;
						// 	}
						// 	$c++;
						// }
						
					}
				}
			}


			print_r($outArrMainProdAttribList);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modUserSavedOrders($pUserInfo){
		try{
			global $config;	
			// echo "modUserShipping";
			$outArrUserSavedOrders = array();
			$userId = isset($pUserInfo['userId']) ? $pUserInfo['userId'] : 0;			

			$outArrUserSavedOrders = $this->objStore->getSavedOrdersById($userId);
			
			echo json_encode($outArrUserSavedOrders);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserSavedOrdersDelete($pUserInfo){
		try{
			global $config;	
			$pArray = array();
			$outUserSavedOrderUpdate = array();
			$pTableName = 'user_orders';
			$orderId = isset($pUserInfo['orderId']) ? $pUserInfo['orderId'] : 0;
		
			$pArray['user_order_status'] = 2;
			
			$con = array();
			$con['user_order_id'] = $orderId;
			if ($con['user_order_id'] !=0) {
				$updateUserOrder = $this->objStore->updateRecordQuery($pArray, $pTableName, $con);
				// $outUserSavedOrderUpdate['msg'] = 'success'.$pArray['user_order_status'].' '.$con['user_order_id'];
				if ($updateUserOrder) {
					$outUserSavedOrderUpdate['msg'] = 'success';
				}else{
					$outUserSavedOrderUpdate['msg'] = 'Failed. There was some error in updating the information';
				}
			}else{
				$outUserSavedOrderUpdate['msg'] = 'Failed. Please send valid order ID';
			}
			echo json_encode($outUserSavedOrderUpdate);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function __destruct(){
		/*** Destroy and unset the object ***/
		/*** Destroy and unset the object ***/
		unset($objLoginQuery);
		unset($_pageSlug);
		unset($objConfig);
		unset($getConfig);
		unset($objCommon);
		unset($this->objPublic);
		unset($this->objStore);
	}
	
} /*** end of class ***/
?>