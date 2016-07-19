<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php';

class Model extends SQLQuery {
	protected $_model;
	
	function __construct() {
		try{
			global $config;
			$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
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
	public function getNewsList()
	{
		try 
		{
			$result = $this->selectQuery("SELECT  * FROM `news` ORDER BY `created_date` DESC");
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
	function __destruct() {
	}
}
?>