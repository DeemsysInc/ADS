<?php 
class cShoppingCart{

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
			require_once(SRV_ROOT.'model/shoppingcart.model.class.php');
			$this->objShoppingCart = new mShoppingCart();
			
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

				$arrProductInfo = $this->objShoppingCart->getProdInfo($pID);
				// print_r($arrProductInfo);
				$clientPrefix = isset($arrProductInfo[0]['prefix']) ? $arrProductInfo[0]['prefix'] :'';
				// $outArrProductInfo['proddId'] = isset($arrProductInfo[0]['pd_id']) ? $arrProductInfo[0]['pd_id'] :0;
				// $outArrProductInfo['clientId'] = isset($arrProductInfo[0]['client_id']) ? $arrProductInfo[0]['client_id'] :0;
				// $outArrProductInfo['clientName'] = isset($arrProductInfo[0]['name']) ? $arrProductInfo[0]['name'] :'';
				// $outArrProductInfo['prodName'] = isset($arrProductInfo[0]['pd_name']) ? $arrProductInfo[0]['pd_name'] :'';
				// $outArrProductInfo['prodPrice'] = isset($arrProductInfo[0]['pd_price']) ? $arrProductInfo[0]['pd_price'] :0;
				// $outArrProductInfo['prodImage'] = isset($arrProductInfo[0]['pd_image']) ? $arrProductInfo[0]['pd_image'] :'';
				// $outArrProductInfo['prodShortDesc'] = isset($arrProductInfo[0]['pd_short_description']) ? $arrProductInfo[0]['pd_short_description'] :'';
				// $outArrProductInfo['prodDesc'] = isset($arrProductInfo[0]['pd_description']) ? $arrProductInfo[0]['pd_description'] :'';
				// $outArrProductInfo['prodCreatedDate'] = isset($arrProductInfo[0]['pd_created_date']) ? $arrProductInfo[0]['pd_created_date'] :0;
				// $outArrProductInfo['prodUrl'] = isset($arrProductInfo[0]['pd_url']) ? $arrProductInfo[0]['pd_url'] :'';
				// $outArrProductInfo['clientBackColor'] = isset($arrProductInfo[0]['background_color']) ? $arrProductInfo[0]['background_color'] :'';
				// $outArrProductInfo['clientCurrencyCode'] = isset($arrProductInfo[0]['client_details_currency_code']) ? $arrProductInfo[0]['client_details_currency_code'] :'USD';
				$clientShipMethods = isset($arrProductInfo[0]['client_shipping_methods']) ? $arrProductInfo[0]['client_shipping_methods'] :'';
				$clientPaymentMethods = isset($arrProductInfo[0]['client_payment_methods']) ? $arrProductInfo[0]['client_payment_methods'] :'';
				$clientID = isset($arrProductInfo[0]['client_id']) ? $arrProductInfo[0]['client_id'] :0;

				$this->objShoppingCart->connectToDB("devarapp_masters");
				$arrClientDB = $this->objShoppingCart->getClientDB($clientID);

				$dbName = isset($arrClientDB[0]['client_db_name']) ? $arrClientDB[0]['client_db_name'] :'';
				$this->objShoppingCart->connectToDB($dbName);
				$arrProductAttrib = $this->objShoppingCart->getProdAttribInfo($pID);
				// print_r($arrProductAttrib);
				for($i=0;$i<count($arrProductAttrib);$i++)
				{
				 $attribName = isset($arrProductAttrib[$i]['attrib_name']) ? $arrProductAttrib[$i]['attrib_name'] : "";
				 if ($attribName !="") {
				 	$attribValue = isset($arrProductAttrib[$i]['attrib_value']) ? $arrProductAttrib[$i]['attrib_value'] : "";
				 	$arrAttribValue = json_decode($attribValue);
				 	$outArrProductInfo['attrib'][$attribName] = $arrAttribValue;
				 }
				}
				$arrClientShipMethods = explode(',', $clientShipMethods);
				$arrShippingMethods = $this->objShoppingCart->getShippingMethods($arrClientShipMethods);
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
				$arrPaymentMethods = $this->objShoppingCart->getPaymentMethods($arrClientPayMethods);
				for($k=0;$k<count($arrPaymentMethods);$k++){
					$outArrProductInfo['paymentMethod'][$k]['paymentMethod']= isset($arrPaymentMethods[$k]['pay_method_name']) ? $arrPaymentMethods[$k]['pay_method_name'] : "";
				}
				// print_r($arrPaymentMethods);
				$this->objShoppingCart->connectToDB($config['database']['name']);
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
			echo 'modStateTaxByState';
			print_r($pUserInfo);			
			// $this->modProdAttribInfo($pID);
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