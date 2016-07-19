<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mClientAuth extends SQLQuery {
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
	function validateClientSubscAuthKey($pBundleId, $pClientAuthKey){
		$query = "SELECT * FROM `client_app_subscribe` WHERE 
			`client_bundle_id` = '$pBundleId' AND 
			`client_subsc_key` = '$pClientAuthKey' AND 
			`client_subsc_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientSubscFeatures($pClientSubscId){
		$query = "SELECT * FROM `client_subsc_features_ref` AS `csfr`, `client_subsc_features` AS `csf` WHERE 
			`csfr`.`client_feature_id` = `csf`.`client_feature_id` AND 
			`csf`.`client_feature_status` = 1 AND 
			`csfr`.`client_app_subsc_id` = $pClientSubscId";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function __destruct() {
		//pg_close($this->con); 
		$this->disconnect();
	}
}
?>