<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mShopparVision extends SQLQuery {
	protected $_model;
	public $con;

	function __construct() {
		try{
			global $config;
			$this->con = $this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
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
		$query = "SELECT * FROM `client_dbs` WHERE `client_id` = $clientID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getAllClientDBs(){
		$query = "SELECT * FROM `client_dbs` WHERE `client_db_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getColorCategories(){
		$query = "SELECT * FROM `color_categories`";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getColorSubCategories(){
		$query = "SELECT * FROM `color_sub_categories`";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getProdIDsByColor($pColorSearch, $pProdType){
		$query = "SELECT * FROM `attrib_ref` AS `ar` LEFT JOIN `prod_tags` AS `pt` ON 
		`ar`.`prod_id` = `pt`.`prod_id` WHERE 
		MATCH(`ar`.`attrib_value`) AGAINST('$pColorSearch' IN BOOLEAN MODE) AND 
		MATCH(`pt`.`prod_tag_name`) AGAINST('$pProdType' IN BOOLEAN MODE)";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	

	function __destruct() {
		//pg_close($this->con); 
		$this->disconnect();
	}
}
?>