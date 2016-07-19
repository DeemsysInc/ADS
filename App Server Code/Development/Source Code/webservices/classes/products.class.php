<?php 
 class cProducts{

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
			require_once(SRV_ROOT.'model/products.model.class.php');
			$this->objProductsQuery = new mProducts();
			
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetAllProductsByClient()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] : 0;
			
			$outArrProductsInfo = array();
			$outArrProductsInfo = $this->objProductsQuery->getAllProductsByClientId($sendArray);
			
			echo json_encode($outArrProductsInfo);
			
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