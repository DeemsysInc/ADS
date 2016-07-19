<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mStore extends SQLQuery {
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
		$query = "SELECT * FROM `client_dbs` WHERE `client_ids` = $clientID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function getProdInfo($pID){
		$query = "SELECT * FROM `products` AS `p`, `client` AS `c`, `client_details` AS `cd` WHERE `p`.`client_id` = `c`.`id` AND `c`.`id` = `cd`.`client_id` AND `p`.`pd_status` = 1 AND `p`.`pd_id` = $pID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	

	function getProdAttribInfo($pID){
		$query = "SELECT *  FROM `attrib_ref` AS `ar`, `prod_attrib` AS `pa` WHERE `ar`.`attrib_id` = `pa`.`attrib_id` AND `ar`.`prod_id` = $pID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}

	function getShippingMethods($arrShippingMethods){
		$query = "SELECT *  FROM `shipping_methods`";
		if (count($arrShippingMethods)>0) {
			$query .= " WHERE `ship_method_id` IN (";
			for ($i=0; $i < count($arrShippingMethods); $i++) { 
				if ($arrShippingMethods[$i] > 0) {
					$query .= "$arrShippingMethods[$i]";
					if ($i < (count($arrShippingMethods)-1)) {
						$query .= ",";
					}
				}
			}
			$query .= ")";
		}
		// echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getPaymentMethods($arrPaymentMethods){
		$query = "SELECT *  FROM `payment_methods`";
		if (count($arrPaymentMethods)>0) {
			$query .= " WHERE `pay_method_id` IN (";
			for ($i=0; $i < count($arrPaymentMethods); $i++) { 
				if ($arrPaymentMethods[$i] > 0) {
					$query .= "$arrPaymentMethods[$i]";
					if ($i < (count($arrPaymentMethods)-1)) {
						$query .= ",";
					}
				}
			}
			$query .= ")";
		}
		// echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getUSStateTax($stateCode){
		$query = "SELECT *  FROM `states_list` WHERE `state_code` = '$stateCode'";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}

	function getClientStoresByLatLng($pUserInfo){
		$clientID = isset($pUserInfo['clientid']) ? $pUserInfo['clientid'] : 0;
		$zipCode = isset($pUserInfo['zip']) ? $pUserInfo['zip'] : 0;
		$requiredLat = isset($pUserInfo['requiredLat']) ? $pUserInfo['requiredLat'] : 0;
		$requiredLng = isset($pUserInfo['requiredLng']) ? $pUserInfo['requiredLng'] : 0;

		$query = "SELECT *, ( 3959 * acos( cos( radians(
		".$requiredLat.") ) * cos( radians( 
		`cs`.`latitude` ) ) * cos( radians( 
		`cs`.`longitude` ) - radians(
		".$requiredLng.") ) + sin( radians(
		".$requiredLat.") ) * sin( radians( 
		`cs`.`latitude` ) ) ) ) AS distance 
		FROM `client_stores` AS `cs` WHERE `cs`.`client_id` = $clientID 
		ORDER BY distance";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}

	function getMainCategories(){
		try{
			$query = "SELECT * FROM `categories_main` ORDER BY `cat_main_id` DESC";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getClientInfo($pClientID){
		try{
			$query = "SELECT *  FROM `client` AS `c`, `client_details` AS `cd` WHERE `c`.`id` = `cd`.`client_id` AND `c`.`id` = $pClientID LIMIT 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getDropdownCat($catID){
		try{
			$query = "SELECT * FROM `prod_cat` AS `pc`, `cat_ref` AS `cr` WHERE `pc`.`cat_id` = `cr`.`cat_id` AND `pc`.`cat_id` = $catID";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getAllCategoriesByClient($parentID){
		try{
			$query = "SELECT * FROM `prod_cat` AS `pc`, `cat_ref` AS `cr` WHERE `pc`.`cat_id` = `cr`.`cat_id` AND `cr`.`cat_parent_id` = $parentID";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	function getClientProducts(){
		try{
			$query = "SELECT * FROM `prod_cat_ref` WHERE `prod_status` = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getProdCountByCatID($pCatId){
		try{
			$query = "SELECT * FROM `prod_cat_ref` WHERE `prod_status` = 1 AND `cat_id` = $pCatId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getClientIdsFromMainOrder($pOrderId, $pUserId){
		try{
			$query = "SELECT * FROM `user_orders` WHERE `user_order_id` = $pOrderId AND `user_id` = $pUserId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function checkProdIdInOrderDetails($pOrderId, $pProdId){
		try{
			$query = "SELECT * FROM `order_details` WHERE `order_id` = $pOrderId AND `prod_id` = $pProdId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getUserInfo($pUserId){
		try{
			$query = "SELECT * FROM `users` WHERE `user_id` = $pUserId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getClientOrders($pOrderId){
		try{
			$query = "SELECT * FROM `orders` WHERE `order_id` = $pOrderId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getClientOrderDetails($pOrderId){
		try{
			$query = "SELECT * FROM `order_details` WHERE `order_id` = $pOrderId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getuserShipAddress($pUserId){
		try{
			$query = "SELECT * FROM `user_ship_address` WHERE `user_id` = $pUserId AND `user_ship_status` = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function CheckUserShipAddress($pUserId, $puserShipId){
		try{
			$query = "SELECT * FROM `user_ship_address` WHERE `user_id` = $pUserId AND `user_ship_addr_id` = $puserShipId  AND `user_ship_status` = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getProdAttribSet($pProdId){
		try{
			$query = "SELECT * FROM `prod_attrib_ref` WHERE `prod_id` = $pProdId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getCompleteAttribInfo($pAttribRefId){
		try{
			$query = "SELECT * FROM `attrib_ref` AS `ar`, `prod_attrib` AS `pa` WHERE `ar`.`attrib_id` = `pa`.`attrib_id` AND `ar`.`attrib_ref_id` = $pAttribRefId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getSavedOrdersById($pUserId){
		try{
			$query = "SELECT *  FROM `user_orders` WHERE `user_id` = $pUserId AND `user_pay_method_id` = 1 AND `user_order_status` = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getMiniOrderInfoByOid($pOrderId){
		try{
			$query = "SELECT * FROM `user_orders` WHERE `user_order_id` = $pOrderId";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function __destruct() {
		//pg_close($this->con); 
		$this->disconnect();
	}
}
?>