<?php 
class cUsers{

	/*** define public & private properties ***/
	private $objUserQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			
			/*require_once SRV_ROOT.'/classes/config.class.php';
			$objConfig = new cConfig();
			$getConfig = $objConfig->config();*/
			
			/**** Create Model Class and Object ****/
			//require_once('C:/wamp/www/admin-panel/model/model.class.php');
			
			$adminFlag = 1;
			require_once dirname(SRV_ROOT).'/smcfg.php';
			global $config;
			require_once($config['ABSOLUTEPATH'].'model/users.model.class.php');
			//require_once SRV_ROOT.'/model/model.class.php';
			$this->objUserQuery = new Umodel();

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	public function modEditUserForm($userId){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUserQuery->getUserDetailsById($userId);
			$outArrGroups=array();
			$outArrGroups = $this->objUserQuery->getUserGroups();
			//print_r($outArray);
			include SRV_ROOT.'views/users/user_edit_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUpdateUserDetails(){
		try{
			global $config;
			$pArray = array();
			//$pArray['user'] = isset($_REQUEST['press_title']) ? $_REQUEST['press_title'] : '';
			$pass = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
			if (!empty($pass)){
				$pArray['password'] = md5($_REQUEST['password']);
			}
			$ua=$this->getBrowser();
                        $yourbrowser= $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'];
			$pArray['email_address'] = isset($_REQUEST['email_address']) ? $_REQUEST['email_address'] : '';
			$pArray['modified_date'] = "NOW()";
			$pArray['phone'] = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
			$pArray['user_ip'] = $_SERVER['REMOTE_ADDR'];
			$pArray['browser_type'] =isset($yourbrowser) ? $yourbrowser : "Unknown";
			$pArray['group_id'] = isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] : '';
			
			
			$tableName="admin_users";
			$arrUpdate=array();
			$con = array();
			$con['user_id']= isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
			$arrUpdate = $this->objUserQuery->updateRecordQuery($pArray,$tableName,$con);
			
		        if(count($arrUpdate) > 0)
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modShowUserList(){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUserQuery->getUserList();
			//print_r($outArray);
			include SRV_ROOT.'views/users/user_list.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modShowAddUserForm(){
		try{
			global $config;
			$outArrGroups=array();
			$outArrGroups = $this->objUserQuery->getUserGroups();
			include SRV_ROOT.'views/users/user_add_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAddNewUserDetails(){
		try{
			global $config;
			$pArray = array();
			$pArray['user_name'] = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '';
			$pArray['password'] = isset($_REQUEST['password']) ? md5($_REQUEST['password']) : '';
			$ua=$this->getBrowser();
                        $yourbrowser= $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'];
			$pArray['email_address'] = isset($_REQUEST['email_address']) ? $_REQUEST['email_address'] : '';
			$pArray['created_date'] = "NOW()";
			$pArray['phone'] = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
			$pArray['group_id'] = isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] : '';
			$pArray['user_ip'] = $_SERVER['REMOTE_ADDR'];
			$pArray['browser_type'] =isset($yourbrowser) ? $yourbrowser : "Unknown";
			
			
			$tableName="admin_users";
			$arrSave=array();
			$arrSave = $this->objUserQuery->insertQuery($pArray,$tableName);
			
		        if(count($arrSave) > 0)
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getBrowser() 
	{
		try{
	    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";
	
	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
		$platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
	    }
	    
	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
	    { 
		$bname = 'Internet Explorer'; 
		$ub = "MSIE"; 
	    } 
	    elseif(preg_match('/Firefox/i',$u_agent)) 
	    { 
		$bname = 'Mozilla Firefox'; 
		$ub = "Firefox"; 
	    } 
	    elseif(preg_match('/Chrome/i',$u_agent)) 
	    { 
		$bname = 'Google Chrome'; 
		$ub = "Chrome"; 
	    } 
	    elseif(preg_match('/Safari/i',$u_agent)) 
	    { 
		$bname = 'Apple Safari'; 
		$ub = "Safari"; 
	    } 
	    elseif(preg_match('/Opera/i',$u_agent)) 
	    { 
		$bname = 'Opera'; 
		$ub = "Opera"; 
	    } 
	    elseif(preg_match('/Netscape/i',$u_agent)) 
	    { 
		$bname = 'Netscape'; 
		$ub = "Netscape"; 
	    } 
	    
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	    }
	    
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
		    $version= $matches['version'][0];
		}
		else {
		    $version= $matches['version'][1];
		}
	    }
	    else {
		$version= $matches['version'][0];
	    }
	    
	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}
	    
	    return array(
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'    => $pattern
	    );
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