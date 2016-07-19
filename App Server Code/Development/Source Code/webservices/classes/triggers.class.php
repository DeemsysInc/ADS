<?php 
 class cTriggers{

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
			require_once(SRV_ROOT.'model/triggers.model.class.php');
			$this->objTriggersQuery = new mTriggers();
			
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetAllTriggersByClient()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] : 0;
			
			$outArrTriggersInfo = array();
			$outArrTriggersInfo = $this->objTriggersQuery->getAllTriggersByClientId($sendArray);
			
			echo json_encode($outArrTriggersInfo);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
   

	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
}  /*** end of class ****/
?>