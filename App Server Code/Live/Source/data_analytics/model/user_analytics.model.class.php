<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mUserAnalytics extends SQLQuery {
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
	function checkSessionId($session_id)
	{
		try
		{
			$query = "SELECT * FROM `downloads_analytics` WHERE `session_id`='$session_id'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}

		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getProductInfo($productId)
	{
	    try 
		{
			$query = "SELECT * FROM `products` WHERE `pd_id`='$productId'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getOfferInfo($offerId)
	{
	    try 
		{
			$query = "SELECT * FROM `offers` WHERE `offer_id`='$offerId'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}
		catch ( Exception $e ) {
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
	public function deleteById($pTableName, $con)
	{
		return $this->delete($pTableName, $con);
	}
	
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>