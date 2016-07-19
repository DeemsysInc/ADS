<?php

/**** Include SQLQuery class for Database connection and main function ****/

require_once SRV_ROOT.'model/SQLQuery.class.php'; 

class mForms extends SQLQuery {

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
			return $result;

		}catch ( Exception $e ) {
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


	function getUserDetails($userId)
	{
		try{
			 $query = "SELECT * FROM  `users` WHERE 	user_id=".$userId." AND user_status = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	
	function getUserDetailsByEmail($email)
	{
		try{
			$query = "SELECT * FROM  `users` WHERE user_email_id='".$email."' AND user_status = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}

	}
	

	function __destruct() {

		//pg_close($this->con); 

	}

}

?>