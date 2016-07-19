<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class lModel extends SQLQuery {
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
				//$result = $this->getInsertId();
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
	
	function checklogin($username,$password){
		try{
			$query = "SELECT * FROM seemore_users WHERE u_uname = '$username' AND u_password = '$password' LIMIT 1";
			// $query = "SELECT * FROM `users` AS `u` , `user_details` AS `ud` WHERE `u`.`user_id`=`ud`.`user_id`  AND  `u`.`user_username` = '$username'  AND  `u`.`user_password` = '$password' LIMIT 1";
			
			$result = $this->selectQuery($query);
			return $result;			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	function __destruct() {
	}
}
?>