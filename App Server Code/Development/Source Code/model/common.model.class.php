<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php';

class mCommon extends SQLQuery {
	protected $_model;
	
	function __construct() {
		global $config;
		$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
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
	
	function getStateNames()
	{
		$query = "SELECT `state_2_code`,`state_name` FROM `states_list` WHERE `state_publish` = 1 ORDER BY state_name";
		$result = $this->selectQuery($query);
		return $result;
	}
	
	
	function __destruct() {
	}
}
?>