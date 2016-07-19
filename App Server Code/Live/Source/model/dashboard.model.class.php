<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mDashboard extends SQLQuery {
	protected $_model;
	public $con;

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
	public function deleteById($pTableName, $con)
	{
		return $this->delete($pTableName, $con);
	}
	
	public function getAllClients()
	{
		$query = "SELECT * FROM client WHERE `active`!='2' ORDER BY created_date DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getAllAppUsers()
	{
	    $query = "SELECT * FROM `users` AS `u` , `user_details` AS `ud` WHERE `u`.`user_id`=`ud`.`user_id` AND `u`.`user_group_id`='3'AND `u`.`user_status`!='2' ORDER BY `u`.`user_id` DESC"; 
		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getProductsByCID($cid)
	{
		$query = "SELECT * FROM `products` WHERE `pd_status`!='2' AND `client_id`=".$cid; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getTriggersByCID($cid)
	{
		
		$query = "SELECT * FROM `trigger` WHERE `active`!='2' AND`client_id` =".$cid; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>