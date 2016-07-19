<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class lAppModel extends SQLQuery {
	protected $_model;
	function __construct() {
		try{
			global $config;
			$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function insertQuery($pArray, $pTableName, $pGetInsertIdCondition)
	{
		try 
		{
			$result = $this->insert($pArray, $pTableName);
			if($pGetInsertIdCondition==true){
				$result = $this->getInsertId();
			}
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function updateRecordQuery($pArray,$tableName,$con)
	{
		try 
		{
			$result = $this->update($pArray,$tableName,$con);
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 
	function checkApplogin($vArray){
		try{ 
			$query = "SELECT * FROM users AS u ";
			if($vArray['regThrough']!=0 && $vArray['email_id']=="" && $vArray['uname']==""){
				$query .= ", user_details AS ud ";
			}
			$query .= "WHERE ";
			if($vArray['regThrough']!=0){
				if($vArray['uname'] !=""){
					$query .= " u.user_username='".$vArray['uname']."' AND u.user_register_through=".$vArray['regThrough']." ";
				} else if($vArray['email_id']=="" && $vArray['uname']==""){
					$query .= "ud.user_details_fbid='".$vArray['user_details_fbid']."' AND u.user_register_through=1  AND u.user_id = ud.user_id";
				} else {
					$query .= " (u.user_username='".$vArray['email_id']."' OR u.user_email_id='".$vArray['email_id']."') AND u.user_register_through=".$vArray['regThrough']." ";
				}
			} else {
				$query .= " u.user_username='".$vArray['uname']."' AND u.user_password='".md5($vArray['pwd'])."'";
			}
			$query .= " AND u.user_status =1  LIMIT 1";
			//echo $query;
			$result = $this->selectQuery($query);
			return $result;			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function checkApploginForProfiling($vArray){
		try{
			$query = "SELECT * FROM users WHERE ";
			if($vArray['regThrough']!=0){
				$query .= " user_username='".$vArray['uname']."' AND user_register_through=".$vArray['regThrough']." ";
			} else {
				$query .= " user_username='".$vArray['email_id']."' OR user_email_id='".$vArray['email_id']."' ";
			}
			$query .= " LIMIT 1";
		//echo $query;
			$result = $this->selectQuery($query);
			return $result;			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function checkAppUserRegistered($pArray,$dArray){
		try{
			//print_r($pArray);
			$query = "SELECT * FROM users AS u ";
			if($pArray['user_register_through']!=0 && $pArray['user_email_id']=="" && $pArray['user_username']==""){
				$query .= ", user_details AS ud";
			}
			$query .= " WHERE ";
			 if($pArray['user_username']=="" && $pArray['user_email_id']==""){
				$query .= " ud.user_details_fbid='".$dArray['user_details_fbid']."' LIMIT 1";
			}else{
				$query .= " u.user_username='".$pArray['user_username']."' OR u.user_email_id='".$pArray['user_email_id']."' LIMIT 1";
			}
			// echo $query;
			 
			$result = $this->selectQuery($query);
			return $result;			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getStateList(){
		try{
			$query = "SELECT * FROM states_list WHERE state_publish =1";
			$result = $this->selectQuery($query);
			return $result;			
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function getUserDetailsByUname($pUname){
		$query = "SELECT * FROM `users` AS `u`, `user_details` AS `ud`, `user_group` AS `ug` WHERE  
				`u`.`user_id` = `ud`.`user_id` 
			AND `u`.`user_group_id` = `ug`.`user_group_id` 
			AND `u`.`user_username` = '$pUname' 
			AND `u`.`user_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function validateUserLoginWithTempPwd($pUsername, $pPassword){
		$query = "SELECT * FROM `users` AS `u`, `user_group` AS `ug` WHERE  
				 `u`.`user_group_id` = `ug`.`user_group_id` 
			AND `u`.`user_username` = '$pUsername' 
			AND `u`.`user_temp_password` = '$pPassword' 
			AND `u`.`user_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function __destruct() {
	}
}
?>