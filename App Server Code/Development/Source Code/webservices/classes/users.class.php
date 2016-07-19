<?php 
 class cUsers{

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
			require_once(SRV_ROOT.'model/users.model.class.php');
			$this->objUsersQuery = new mUsers();
			
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetAllAppUsers()
	{
		try
		{
			global $config;
			
			$outArrUsersInfo = array();
			$outArrUsersInfo = $this->objUsersQuery->getAllAppUsersInfo($sendArray);
			
			echo json_encode($outArrUsersInfo);
			
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