<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mShortUrls extends SQLQuery {
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
	
	
	function checkUrlShortened($sendArray)
	{
		try 
		{
			$query = "SELECT long_url,code FROM short_urls WHERE long_url = '". mysql_real_escape_string($sendArray['long_url']) ."'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function getShortUrlsInfoByCode($sendArray)
	{
		try 
		{
			$query = 'SELECT long_url FROM short_urls WHERE code="' . mysql_real_escape_string($sendArray['short_code']) . '"';
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>