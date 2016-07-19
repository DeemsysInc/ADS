<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mShoppingCart extends SQLQuery {
	protected $_model;
	public $con;

	function __construct() {
		try{
			global $config;
			$this->con = $this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
			//echo $config['database']['host']." ".$config['database']['user']." ".$config['database']['name'];
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
	public function deleteById($pTableName, $con)
	{
		return $this->delete($pTableName, $con);
	}
	function connectToDB($dbName){
		$db_selected = mysql_select_db($dbName, $this->con);
		if (!$db_selected) {
		    die ('Can\'t use foo : ' . mysql_error());
		}
	}
	function getClientDB($clientID){
		$query = "SELECT * FROM `client_dbs` WHERE `client_ids` = $clientID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function getProdInfo($pID){
		$query = "SELECT * FROM `products` AS `p`, `client` AS `c`, `client_details` AS `cd` WHERE `p`.`client_id` = `c`.`id` AND `c`.`id` = `cd`.`client_id` AND `p`.`pd_status` = 1 AND `p`.`pd_id` = $pID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	

	function getProdAttribInfo($pID){
		$query = "SELECT *  FROM `attrib_ref` AS `ar`, `prod_attrib` AS `pa` WHERE `ar`.`attrib_id` = `pa`.`attrib_id` AND `ar`.`prod_id` = $pID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}

	function getShippingMethods($arrShippingMethods){
		$query = "SELECT *  FROM `shipping_methods`";
		if (count($arrShippingMethods)>0) {
			$query .= " WHERE `ship_method_id` IN (";
			for ($i=0; $i < count($arrShippingMethods); $i++) { 
				if ($arrShippingMethods[$i] > 0) {
					$query .= "$arrShippingMethods[$i]";
					if ($i < (count($arrShippingMethods)-1)) {
						$query .= ",";
					}
				}
			}
			$query .= ")";
		}
		// echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getPaymentMethods($arrPaymentMethods){
		$query = "SELECT *  FROM `payment_methods`";
		if (count($arrPaymentMethods)>0) {
			$query .= " WHERE `pay_method_id` IN (";
			for ($i=0; $i < count($arrPaymentMethods); $i++) { 
				if ($arrPaymentMethods[$i] > 0) {
					$query .= "$arrPaymentMethods[$i]";
					if ($i < (count($arrPaymentMethods)-1)) {
						$query .= ",";
					}
				}
			}
			$query .= ")";
		}
		// echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>