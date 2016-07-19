<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mClients extends SQLQuery {
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
	
	public function getAllClients()
	{
		$query = "SELECT * FROM `client` AS `c`,`client_details` AS `cd` WHERE `c`.`id`= `cd`.`client_id` AND `c`.`active`!='2' ORDER BY `c`.`created_date` DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getAllClientGroups()
	{
		try{
			$query = "SELECT * FROM client_groups ORDER BY created_date DESC"; 
			$result = $this->selectQueryForAssoc($query);
			return $result;
			}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientById($cid)
	{
		$query = "SELECT * FROM client as `c`,client_details as `cd` WHERE  `c`.`id`=`cd`.`client_id` AND `c`.id=$cid"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getClientByName($cName)
	{
		$query = "SELECT * FROM client WHERE name LIKE '%".$cName."%'"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getClientUsersInfoByID($user_id)
	{
		$query = "SELECT * FROM users WHERE user_id=$user_id"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getProductsByCID($cid)
	{
		 $query = "SELECT * FROM products AS p, client AS c WHERE c.id = p.client_id AND p.client_id=$cid AND p.pd_status!=2 ORDER BY `pd_created_date` DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getProductByPID($pid)
	{
	    $query = "SELECT * FROM `products` AS `p`,`products_background_view` AS `pbv` WHERE `p`.`pd_status`!='2'AND `p`.`pd_id`=`pbv`.`pd_id` AND `p`.`pd_id`=$pid"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	public function getOfferedProductsByCID($cid)
	{
		$query = "SELECT * FROM products AS p  WHERE  p.client_id=$cid AND p.offer = '1' AND p.active!='2' ORDER BY p.id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getTriggersByCID($cid)
	{
		
		$query = "SELECT `t`.`id`,`t`.`url`,`t`.`client_id`,`t`.`title`,`t`.`height`,`t`.`width`,`t`.`instruction`,`t`.`active`,`c`.`name` FROM `trigger` AS `t`,`client` AS `c` WHERE `c`.`id`=`t`.`client_id` AND `t`.`client_id` =$cid AND `t`.`active`!=2 ORDER BY t.id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getTriggerByTID($tid)
	{
		$query = "SELECT * FROM `trigger`  WHERE `id`=$tid"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	public function getRelatedProducts($pid)
	{
		$query = "SELECT * FROM products AS p, product_related AS pr WHERE pr.relatedto_id = p.id AND pr.relatedfrom_id=$pid ORDER BY p.id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getRelatedProductId($pid)
	{
		 $query = "SELECT relatedto_id FROM product_related WHERE relatedfrom_id = $pid ORDER BY relatedfrom_id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function getOfferedProductId($pid)
	{
		$query = "SELECT * FROM product_offer WHERE offerfrom_id=$pid ORDER BY offerto_id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function checkOfferedProductId($fromId,$toId)
	{
		$query = "SELECT * FROM product_offer WHERE offerfrom_id=$fromId AND offerto_id=$toId ORDER BY offerto_id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function getTriggerVisualsByTID($tid)
	{
		$query = "SELECT * FROM `visual` AS `v` WHERE  `v`.`trigger_id` =$tid ORDER BY v.id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function getTriggerVisualsByVID($vid)
	{
		$query = "SELECT * FROM `visual` AS `v` WHERE  `v`.`id` =$vid ORDER BY v.id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function checkRelatedProducts($pid,$relID)
	
	{
		 $query = "SELECT * FROM product_related WHERE relatedfrom_id=$pid AND relatedto_id=$relID ORDER BY relatedfrom_id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function showClientTableStatus()
	{
		try 
		{   $query = "SHOW TABLE STATUS LIKE  'client'"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getAdditionalProductMedia($pid)
	{
		try 
		{  
		    $query = "SELECT * FROM additional_product_media WHERE active!=2 AND product_id=$pid  ORDER BY id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getAdditionalMediaByid($id)
	{
		try 
		{   $query = "SELECT * FROM `additional_product_media` WHERE id=$id  ORDER BY id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getTriggerVisualsByID($id)
	{
		 $query = "SELECT * FROM `visual` AS `v` WHERE  `v`.`id` =$id ORDER BY v.id DESC"; 
		$result = $this->selectQuery($query);
		return $result;
	}
	public function getCatagories()
	{
		try 
		{   $query = "SELECT * FROM `products_category` ORDER BY pd_category_id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getCatagoriesById($id)
	{
		try 
		{   $query = "SELECT * FROM `products_category` WHERE `pd_category_id`=$id ORDER BY pd_category_id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getStyles()
	{
		try 
		{   $query = "SELECT * FROM `style` ORDER BY id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getStylesById($id)
	{
		try 
		{   $query = "SELECT * FROM `style` WHERE `id`=$id ORDER BY id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientModelsByVid($vid)
	{
		try 
		{   $query = "SELECT * FROM `model` WHERE `three_d_model_id`=$vid AND `active` != '2' ORDER BY id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function getClientModelsByMid($mid)
	{
		try 
		{   $query = "SELECT * FROM `model` WHERE `id`=$mid  AND `active` != '2' ORDER BY id DESC"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function getVerticalClientsById($id)
	{
		try 
		{   $query = "SELECT * FROM `client_verticals` WHERE `client_vertical_id`=$id"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function getVerticalClients()
	{
		try 
		{   $query = "SELECT * FROM `client_verticals`"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function getClientStoresByCId($cid)
	{
		try 
		{   $query = "SELECT * FROM `client_stores` WHERE `client_id`='$cid'"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function getClientStoresByStoreId($storeId)
	{
		try 
		{   
			$query = "SELECT * FROM `client_stores` WHERE `store_id`='$storeId'"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getAllOffersByCID($cid)
	{
		try 
		{
		$query = "SELECT * FROM `offers` WHERE `client_id`='$cid'"; 
		$result = $this->selectQuery($query);
		return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getStoreRelatedOfferId($storeId)
	{
		try 
		{   
			$query = "SELECT * FROM `client_stores` WHERE `store_id`='$storeId'"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getStatesList()
	{
		try 
		{   
			$query = "SELECT * FROM `countries_states_subdivisions`"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getCountryList()
	{
		try 
		{   
			$query = "SELECT * FROM `countries`"; 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getCountryByStateCode($state_code)
	{
		try 
		{   
			$query = "SELECT *  FROM `countries_states_subdivisions` as `css`, `countries` as `c` WHERE `css`.`country_code_char2`= `c`.`country_code_char2` AND `css`.`state_subdivision_code`='".$state_code."'"; 
			$result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getStateByCountryCode($country_code)
	{
		try 
		{   
		    $query = "SELECT *  FROM `countries_states_subdivisions` as `css` WHERE  `css`.`country_code_char2`='".$country_code."' ORDER BY `css`.`state_subdivision_name`";
			$result = $this->selectQuery($query);
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