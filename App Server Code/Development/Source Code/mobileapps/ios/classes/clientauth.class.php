<?php 
class cClientAuth{

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
			require_once(SRV_ROOT.'model/clientauth.model.class.php');
			$this->objClientAuth = new mClientAuth();
			
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
	
	

	public function modClientAuth($pUserInfo){
		try{
			global $config;
			$arrAuthKey = array();
			$arrSubscFeatures= array();
			$outArrAuthKey = array();
			$bundleId = isset($pUserInfo['bundleId']) ? $pUserInfo['bundleId'] : '';
			$clientAuthKey = isset($pUserInfo['clientAuthKey']) ? $pUserInfo['clientAuthKey'] : '';
	
			$arrAuthKey = $this->objClientAuth->validateClientSubscAuthKey($bundleId, $clientAuthKey);

			if (count($arrAuthKey) > 0) {
				$outArrAuthKey['metaioKey'] = isset($arrAuthKey[0]['client_metaio_key']) ? $arrAuthKey[0]['client_metaio_key'] : '';
				$outArrAuthKey['fbAppId'] = isset($arrAuthKey[0]['client_facebook_app_id']) ? $arrAuthKey[0]['client_facebook_app_id'] : '';
				$outArrAuthKey['clientIds'] = isset($arrAuthKey[0]['client_ids']) ? $arrAuthKey[0]['client_ids'] : '';
				$clientSubscId = isset($arrAuthKey[0]['client_app_subsc_id']) ? $arrAuthKey[0]['client_app_subsc_id'] : 0;
				if ($clientSubscId > 0) {
					$arrSubscFeatures = $this->objClientAuth->getClientSubscFeatures($clientSubscId);
					$outArrAuthKey['features'] = $arrSubscFeatures;
				}
				$outArrAuthKey['msg'] = 'success';
			}else{
				$outArrAuthKey['msg'] = 'fail';
			}
			echo json_encode($outArrAuthKey);
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
		unset($this->objClientAuth);
	}
	
} /*** end of class ***/
?>