<?php
global $config;
//print_r($config);
/**** Include SQLQuery class for Database connection and main function ****/
//require_once 'C:/wamp/www/admin-panel/model/SQLQuery.class.php';
require_once $config['ABSOLUTEPATH'].'model/SQLQuery.class.php';


//require_once $getConfig['ABSOLUTEPATH'].'model/SQLQuery.class.php';

class Mmodel extends SQLQuery {
	protected $_model;
	
	function __construct() {
		global $config;
		$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
	}

    function checkMemberStatus($member_id)
	{		
	
		$result = $this->selectQuery("SELECT mu.user_name AS username, mu.user_status AS userstatus, mu.email_address AS emailaddress,mup.user_fname AS first_name,mup.user_lname AS last_name,mu.user_password AS user_password,mu.user_activationkey AS user_activationkey FROM `member_users` AS mu, `member_users_profile` AS mup WHERE `mu`.`user_id`=`mup`.`user_id` AND mup.user_id='".$member_id."'");
		return $result;
	}
	
	function updateMemberStatus($updateValArray, $tableName, $con){
		$result =  $this->update($updateValArray, $tableName, $con);
		return $result;
	}
	public function getResetPasswordForNotActive($checkUserValue){
		try{
			$sqlQuery = "SELECT `mu`.user_id,`mu`.user_name,`mup`.user_email AS email_address FROM `member_users` AS mu, `member_users_profile` AS `mup` WHERE (`mu`.`user_name`  = '".$checkUserValue."' OR `mup`.`user_email` ='".$checkUserValue."') AND `mu`.`user_status`= 0 AND mu.user_id=mup.user_id LIMIT 1";
			$result = $this->selectQuery($sqlQuery);
			$updateValArray = array();
			$con = array();
			$updateResult = 0;
			
			if(count($result) > 0){
				if($result[0]['email_address']==""){	
					$result = 0;
				} else if($result[0]['email_address']!=""){
					//Update Query Values
					$updateValArray['pwd_activation_code']  = substr(md5(rand().rand()), 0, 8);
					$updateValArray['offline_mail_status']  = 1;
					$con['user_id']         = $result[0]['user_id'];
					$updateResult = $this->update($updateValArray, 'member_users', $con);
					
					
					if($updateResult){
						$result = $result[0];
						$result['pwd_activation_code'] = $updateValArray['pwd_activation_code'];
					} else {
						$result = $updateResult;
					}
				}
			} else {
				$result = 2;
			}
			//print_r($result);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function __destruct()
	{
	
	}
}
?>