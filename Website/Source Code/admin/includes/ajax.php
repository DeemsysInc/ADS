<?php $adminFlag = 1;
require_once '../../smcfg.php';
global $config;

//define('SRV_ROOT', $srvRoot);
//session_start();
//require_once $config['ABSOLUTEPATH'].'classes/config.class.php';
ini_set("display_errors", 1);
/*$objConfig = new cConfig();
$getConfig = $objConfig->config();*/

/**** Create Model Class and Object ****/
/*require_once($getConfig['ABSOLUTEPATH'].'model/model.class.php');
$objQuery = new Model();*/


require_once($config['ABSOLUTEPATH'].'classes/modules.classes.php');
 $objModules = new cModules();
// require_once($config['ABSOLUTEPATH'].'classes/member_list.class.php');
// $objMembers = new cMembers();




//Requesting the type of action
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	
//Action for login	
	if($action == "login")
	{
		//Retrieving all input data
			 $username = $_REQUEST['username'];
			 $password = $_REQUEST['password'];
	
		//Creating an array of values
			$pArray = array();
			$pArray['username'] = $username;
			$pArray['password'] = $password;
		
		
		//cheking condtion for remeber me
		//$remember = $_REQUEST['remember'];
		/*if ( isset( $remember ) && $remember == 'remember' ) {
			setcookie( session_name(), session_id(), time() + 86400*30 );
		}*/
		
		//Retrieving results from class
		
		 $outArray = $objModules->modLogin($pArray);
		
		
	}

         //Action for logout
	if($action == "logout")
	{
		session_destroy();
		echo "<script>window.location.href='".$config['LIVE_ADMIN_URL']."index.php';</script>";
	}
	
	
	if($action == "savepressrelease")
	{
				
		$outArray = $objModules->modSavePressReleases();
		
		
		
		//Checking whether user exists in database 
			if(count($outArray) > 0)
			{
				
				echo 1;
			}
			else
			{
				echo 0;
			}
	}
	if($action == "updatepressrelease")
	{
				
		$outArray = $objModules->modUpdatePressReleases();
		
		
		
		//Checking whether user exists in database 
			if(count($outArray) > 0)
			{
				
				echo 1;
			}
			else
			{
				echo 0;
			}
	}
	if($action == "savenews")
	{
				
		$outArray = $objModules->modSaveNews();
		
		
		
		//Checking whether user exists in database 
			if(count($outArray) > 0)
			{
				
				echo 1;
			}
			else
			{
				echo 0;
			}
	}
	if($action == "updatenews")
	{
				
		$outArray = $objModules->modUpdateNews();
		
		
		
		//Checking whether user exists in database 
			if(count($outArray) > 0)
			{
				
				echo 1;
			}
			else
			{
				echo 0;
			}
	}
	if($action == "deletePressRelease")
	{
		$objModules->modDeletePressRelease();
		
	}
	if($action == "deleteNews")
	{
		$objModules->modDeleteNews();
		
	}
	
?>