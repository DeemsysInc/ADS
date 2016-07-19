<?php
global $config;
//print_r($config);
/**** Include SQLQuery class for Database connection and main function ****/
//require_once 'C:/wamp/www/admin-panel/model/SQLQuery.class.php';
require_once $config['ABSOLUTEPATH'].'model/SQLQuery.class.php';


//require_once $getConfig['ABSOLUTEPATH'].'model/SQLQuery.class.php';

class Umodel extends SQLQuery {
	protected $_model;
	
	function __construct() {
		global $config;
		$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
	}

        function getUserList()
	{		
	        $query = "SELECT * FROM `admin_users` AS `au` , `user_groups` AS `ug` WHERE `au`.`group_id`=`ug`.`group_id`";
		$result = $this->selectQuery($query);
		return $result;
	}
	function getUserGroups()
	{		
	
		$result = $this->selectQuery("SELECT * FROM `user_groups`");
		return $result;
	}
	
        function getUserDetailsById($userId)
	{		
	        $query = "SELECT * FROM `admin_users` AS `au` , `user_groups` AS `ug` WHERE `au`.`group_id`=`ug`.`group_id` AND `user_id`='".$userId."'";
		$result = $this->selectQuery($query);
		return $result;
	}
	
	public function insertQuery($pArray, $pTableName)
	{
		try 
		{
			$result = $this->insert($pArray, $pTableName);
			
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
	function __destruct()
	{
	
	}
}
?>