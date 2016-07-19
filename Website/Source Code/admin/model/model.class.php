<?php
global $config;
//print_r($config);
/**** Include SQLQuery class for Database connection and main function ****/
//require_once 'C:/wamp/www/admin-panel/model/SQLQuery.class.php';
require_once $config['ABSOLUTEPATH'].'model/SQLQuery.class.php';


//require_once $getConfig['ABSOLUTEPATH'].'model/SQLQuery.class.php';

class Model extends SQLQuery {
	protected $_model;
	
	function __construct() {
		global $config;
		$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
	}
	
	
	function getUpdateAdminUsersTable($updateValArray, $tableName, $con){
		$result =  $this->update($updateValArray, $tableName, $con);
		return $result;
	}
	
	function getLogin($pArray)
	{
	    $result = $this->selectQuery("SELECT  * FROM `admin_users` WHERE `user_name` = '".$pArray['username']."' and  password = '".md5($pArray['password'])."'");
		return $result;
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
	public function getPressReleases()
	{
		try 
		{
			$result = $this->selectQuery("SELECT  * FROM `press`");
		        return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getPressReleasesById($press_id)
	{
		try 
		{
			$result = $this->selectQuery("SELECT  * FROM `press` WHERE `id` = '".$press_id."'");
		        return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getNewsList()
	{
		try 
		{
			$result = $this->selectQuery("SELECT  * FROM `news`");
		        return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getNewsListById($news_id)
	{
		try 
		{
			$result = $this->selectQuery("SELECT  * FROM `news` WHERE `id` = '".$news_id."'");
		        return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function __destruct()
	{
	
	}
}
?>