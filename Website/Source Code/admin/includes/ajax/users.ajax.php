<?php $adminFlag = 1;

require_once '../../../smcfg.php';
global $config;
require_once($config['ABSOLUTEPATH'].'classes/users.classes.php');
 $objUsers = new cUsers();
// require_once($config['ABSOLUTEPATH'].'classes/member_list.class.php');
// $objMembers = new cMembers();




//Requesting the type of action
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	

if($action == "updateUserDetails")
{
	$objUsers->modUpdateUserDetails();
}
if($action == "addNewUser")
{
	$objUsers->modAddNewUserDetails();
}

        
?>