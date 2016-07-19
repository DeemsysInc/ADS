<?php 
class cAppLogin{

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
			require_once(SRV_ROOT.'model/app.login.model.class.php');
			$this->objLoginQuery = new lAppModel();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'classes/common.class.php');
			$this->objCommon = new cCommon();
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getAccessWithAppLoginDetails($pArray){
		try{
			global $config;
			$outResults = array();
			//print_r($pArray);
			$uname = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$pwd = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
			$outResults = $this->login_app_user($uname, $pwd);
			echo json_encode($outResults);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function login_app_user($username, $password){
        global $config;
		$arrResult = array();
        //$password = md5($password);
		$checkLogin = $this->objLoginQuery->checkApplogin($username,$password);
		if(!$checkLogin){		
			$arrResult['msg'] = 'fail';		
		}else{
			/*$_SESSION['uname'] = isset($checkLogin[0]['username']) ? $checkLogin[0]['username'] : 'guest';
			$_SESSION['fname'] = isset($checkLogin[0]['first_name']) ? $checkLogin[0]['first_name'] : "";
			$_SESSION['lname'] = isset($checkLogin[0]['last_name']) ? $checkLogin[0]['last_name'] : "";
			$_SESSION['user_id'] = isset($checkLogin[0]['id']) ? $checkLogin[0]['id'] : "";
			$_SESSION['isValid'] = 'yes';*/
			$arrResult['msg'] = 'success';
			//$arrResult['redirect'] = '';
			
			
			
			//Update last login date in sales_user table
			/*$pArray = array();
			$pArray['last_login_date'] = "NOW()";
			$tableName = "seemore_users";
			$con=array();
			$con['u_uname']=$_SESSION['uname'];
			$outUpdateResult = $this->objLoginQuery->updateRecordQuery($pArray,$tableName,$con);*/
		}
		return $arrResult; 
    }
	
    public function logout(){
		try{
			global $config;
			$arrResult = array();
			session_destroy();
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = "";
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
	
    public function redirectToURL($url)
    {
        header("Location: ".$url);
       	exit;
    }
    
	
	
	
	public function getInsertRegistrationDetails($pArray){
		try{
			global $config;
			$outResults = array();
			//print_r($pArray);
			$pArray = array();
			$pArray['first_name'] = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';
			$pArray['last_name'] = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';
			$pArray['username'] = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$pArray['password'] = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
			$pArray['email_id'] = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';
			
			$pTableName = "user_table";
			if ($pArray['username']!="" && $pArray['password']!=""){
				$insertResults = $this->objLoginQuery->insertQuery($pArray, $pTableName, false);
				if ($insertResults && $pArray['username']!=""){
					$outResults['msg'] = 'success';
				}else{
					$outResults['msg'] = 'fail';
				}
			}
			echo json_encode($outResults);
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