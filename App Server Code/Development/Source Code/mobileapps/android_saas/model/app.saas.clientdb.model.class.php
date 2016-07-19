<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mAppSassClientDbs extends SQLQuery {
	protected $_model;
	public $con;
	function __construct() {
		try{
			global $config;
			//print_r($config);
			$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
			
			$this->mainDbName = $config['database']['name'];
			$this->markersDbName = $config['database']['name_markers'];
			$this->usersDbName = $config['database']['name_users'];
			$this->userAnalyticsDbName = $config['database']['name_user_analytics'];
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function insertQuery($pArray, $pTableName, $pGetInsertIdCondition)
	{
		global $config;
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
		global $config;
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
		global $config;
		return $this->delete($pTableName, $con);
	}
	function getClientDbConnUsingQueryByClientId($clientId){
		global $config;
		$query = "SELECT * FROM ".$this->mainDbName.".`client_dbs` WHERE client_ids REGEXP '[[:<:]]".$clientId."[[:>:]]' AND client_db_status=1";		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getClientDbConnUsingQueryByClosetUserId($userId){
		global $config;
		$query = "SELECT * FROM ".$this->usersDbName.".`closet` AS c WHERE c.user_id=".$userId." AND c.closet_status=1";		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getClientDbConnUsingQueryByWishListUserId($userId){
		global $config;
		$query = "SELECT * FROM ".$this->usersDbName.".`wishlist` AS wl LEFT JOIN ".$this->usersDbName.".`wishlist_details` AS wld ON wl.wishlist_id=wld.wishlist_id AND wld.wishlist_details_status=1 WHERE wl.user_id=6 AND wl.wishlist_status=1";		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientDbConnUsingQueryByMyOfferUserId($userId){
		global $config;
		$query = "SELECT * FROM ".$this->usersDbName.".`my_offers` AS mo LEFT JOIN ".$this->usersDbName.".`my_offers_reference` AS mor ON mo.my_offers_id=mor.my_offers_id AND mor.my_offers_ref_status=1 WHERE mo.user_id=".$userId." AND mo.my_offers_status=1";		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>