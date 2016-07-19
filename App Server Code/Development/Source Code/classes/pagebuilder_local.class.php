<?php 
/**** Include interfaces link ****/
//require_once(SRV_ROOT.'interfaces/interfaces.inc.php');

class cPageBuilder{

	/*** define public & private properties ***/
	 public $objLogin;
	 public $objConfig;
	 public $getConfig;
	 public $objCommon;
	 
	/*** the constructor ***/
	public function __construct(){
		
		require_once SRV_ROOT.'classes/login.class.php';
		$this->objLogin = new cLogin();
		
		require_once SRV_ROOT.'classes/users.class.php';
		$this->objUsers = new cUsers();
		
		require_once SRV_ROOT.'classes/dashboard.class.php';
		$this->objDashboard = new cDashboard();
		
		require_once SRV_ROOT.'classes/clients.class.php';
		$this->objClients = new cClients();
		
		require_once SRV_ROOT.'classes/config.class.php';
		$this->objConfig = new cConfig();
		$this->getConfig = $this->objConfig->config();
	}

	/**** function to get all functions of Header and assign to header template ****/
	public function pageHeader(){
		try{
			global $config;
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){
				$outUserInfo = array();
				$outUserInfo['fname'] =  isset($_SESSION['fname']) ? $_SESSION['fname'] : "";
				$outUserInfo['lname'] =  isset($_SESSION['lname']) ? $_SESSION['lname'] : "";
				$outUserInfo['user_group'] =  isset($_SESSION['user_group']) ? $_SESSION['user_group'] : "";
				$getDateFromDB = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : "";
				$date = new DateTime($getDateFromDB);
				$outUserInfo['last_login'] = $date->format('m-d-Y H:i:s');
				/**** assign variables/array to header.tpl.php ****/
				include SRV_ROOT.'views/home/header.tpl.php';
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	/**** function to get all functions of Content and assign to content template ****/
	public function pageContent($pUrl){
		try{
			global $config;
			$pAction = isset($pUrl[1]) ? $pUrl[1] : '';
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){
				if ($pAction==''){
					/**** assign variables/array to home.tpl.php ****/
					$this->objDashboard->modDashboard();
				}else if ($pAction=='users'){
					if (isset($pUrl[2]) && ($pUrl[2]=='add')){
						$this->objUsers->modAddUsers();
					}elseif (isset($pUrl[2]) && ($pUrl[2]=='edit')){
						if (isset($pUrl[3]) && !empty($pUrl[3])){
							$this->objUsers->modEditUsers($pUrl[3]);
						}else{
							$this->objUsers->modManageUsers();
						}
					}elseif (isset($pUrl[2]) && ($pUrl[2]=='profile')){
						if (isset($pUrl[3]) && !empty($pUrl[3])){
							if (isset($pUrl[3]) && ($pUrl[3]=="edit")){
								$this->objUsers->modUserProfileEdit();
							}else if (isset($pUrl[3]) && ($pUrl[3]=="password")){
								$this->objUsers->modUserProfilePassword();
							}
						}else{
							$this->objUsers->modUserProfile();
						}
					}else{
						echo "Access denied";
					}
				}else if ($pAction=='clients'){
					if (isset($pUrl[2]) && ($pUrl[2]=='id')){
						if (isset($pUrl[4]) && ($pUrl[4]=='products')){
							if (isset($pUrl[5]) && ($pUrl[5]=='related')){
								echo "Related";
								//related
							}elseif (isset($pUrl[5]) && ($pUrl[5]=='offers')){
								echo "offers";
							}elseif (isset($pUrl[5]) && ($pUrl[5]=='additional')){
								//additional
								echo "additional";
							}else{
								if (isset($pUrl[6]) && ($pUrl[6]=='view')){
									//view
									echo "view";
								}elseif (isset($pUrl[6]) && ($pUrl[6]=='edit')){
									//edit
									echo "edit";
								}else{
									$this->objClients->modClientProducts($pUrl[3]);
								}
							}
						}else{
							$this->objClients->modClientById($pUrl[3]);
						}
					}else{
						$this->objClients->modAllClients();
					}
				}else{
					echo "Access denied";
				}
			}else if ($pAction=='activation'){
				if (isset($pUrl[2]) && !empty($pUrl[2])){
					$this->objUsers->modPasswordActivation($pUrl[2]);
					$this->objLogin->modSalesLogin($pUrl);
				}
			}else {
				$this->objLogin->modSalesLogin($pUrl);
			}
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	/**** function to get Page Footer ****/
	public function pageFooter(){
		try{
			global $config;
			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){
				include SRV_ROOT.'views/home/footer.tpl.php';
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get Page Left Modules ****/
	public function pageLeft(){
		try{

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get Page right modules ****/
	public function pageRight($pAction){
		try{
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