<?php
/**** Include interfaces link ****/
//require_once(SRV_ROOT.'interfaces/interfaces.inc.php');

class cCommon{

	/*** define public & private properties ***/
	public $objQuery;
	public $objCommon;
	public $_pageSlug;		
	public $objConfig;
	public $getConfig;
	
    public $error_message;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			/*require_once SRV_ROOT.'/classes/config.class.php';
			$objConfig = new cConfig();
			$getConfig = $objConfig->config();*/
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/model.class.php');
			$this->objQuery = new Model();
			
			/**** Create Gradings Model Class and Object ****/
			require_once(SRV_ROOT.'model/common.model.class.php');
			$this->objCommonQuery = new mCommon();		
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modLoadStates()
	{
		try{
			global $config;
			$arrResult = array();
			$arrResult = $this->objCommonQuery->getStateNames();
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modCallWebservices($baseUrl,$params)
	{
		try{
			global $config;
			//Base URL for the service
			
			// Create a cURL session
			$curl = curl_init() or die(curl_error());
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); 
			// Submit job
			$submitUrl = "$baseUrl/submit";
			curl_setopt($curl, CURLOPT_URL, $submitUrl);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			$jobId = curl_exec($curl);
			//echo $jobId;
			// Print job identifier
			$arrData=json_decode($jobId,true);
			// Close the cURL session
			//print_r($arrData);
			curl_close($curl);
			
			return $arrData;

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