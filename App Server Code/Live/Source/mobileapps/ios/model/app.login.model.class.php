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
	
	function checkApplogin($username,$password){
		try{
			$query = "SELECT * FROM user_table WHERE username = '$username' AND password = '$password' LIMIT 1";
			$result = $this->selectQuery($query);
			return $result;			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	function __destruct() {
		$this->disconnect();
	}
}
?>