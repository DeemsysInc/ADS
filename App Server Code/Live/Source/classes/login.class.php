<?php 
class cLogin{

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
			require_once(SRV_ROOT.'model/login.model.class.php');
			$this->objLoginQuery = new lModel();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'classes/common.class.php');
			$this->objCommon = new cCommon();
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getAccessWithLoginDetails(){
		try{
			global $config;
			$outResults = array();
			$uname = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$pwd = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
			$outResults = $this->login_user($uname, $pwd);
			echo json_encode($outResults);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function login_user($username, $password){
        global $config;
		$arrResult = array();
        $password = md5($password);
		$checkLogin = $this->objLoginQuery->checklogin($username,$password);
		if(!$checkLogin){		
			$arrResult['msg'] = 'Please enter correct username and password';		
		}else{
			$_SESSION['uname'] = isset($checkLogin[0]['u_uname']) ? $checkLogin[0]['u_uname'] : 'Guest';
			$_SESSION['fname'] = isset($checkLogin[0]['u_first_name']) ? $checkLogin[0]['u_first_name'] : "";
			$_SESSION['lname'] = isset($checkLogin[0]['u_last_name']) ? $checkLogin[0]['u_last_name'] : "";
			$_SESSION['user_id'] = isset($checkLogin[0]['u_id']) ? $checkLogin[0]['u_id'] : 0;
			$_SESSION['user_group'] = isset($checkLogin[0]['u_group_id']) ? $checkLogin[0]['u_group_id'] : 0;
			$_SESSION['last_login'] = isset($checkLogin[0]['last_login_date']) ? $checkLogin[0]['last_login_date'] : "";
			$_SESSION['clientIds'] = isset($checkLogin[0]['u_clientids']) ? $checkLogin[0]['u_clientids'] : "";
			$_SESSION['isValid'] = 'yes';
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = '';
			
			
			
			//Update last login date in sales_user table
			$pArray = array();
			$pArray['user_details_lastlogin'] = "NOW()";
			$tableName = "user_details";
			$con=array();
			$con['user_id']=$_SESSION['user_id'];
			$outUpdateResult = $this->objLoginQuery->updateRecordQuery($pArray,$tableName,$con);
		}
		return $arrResult; 
    }

	public function modSalesLogin($pUrl){
		try{
			global $config;
			$pAction = isset($pUrl[0]) ? $pUrl[0] : '';
			
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){	
				echo "sdfasdfadsf";
			} else if($pAction=='logout') {
				$this->logout();
				$this->redirectToURL($config['LIVE_URL']);
			}else{
				include SRV_ROOT.'views/login.tpl.php';
			}
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
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
    
 

	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>