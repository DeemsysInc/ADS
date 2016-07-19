<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mAnalytics extends SQLQuery {
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
	public function getUserTrackigData()
	{
		$query = "SELECT * FROM `data_analytics` "; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getUserTrackigDataByDates($from_date,$to_date)
	{
	    $query = "SELECT * FROM `data_analytics`  WHERE  DATE_FORMAT(created_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'"; 
		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getUserTrackigDataByDate($created_date)
	{
	    $query = "SELECT * FROM `data_analytics`  WHERE  DATE_FORMAT(created_date, '%Y-%m-%d') = '$created_date'"; 
		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getProductByPID($pid)
	{
		$query = "SELECT * FROM `products` as `p`,`client` as `c` WHERE `p`.`pd_status`!='2' AND `p`.`client_id`=`c`.`id` AND `p`.`pd_id`=$pid"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getOfferByOffID($offid)
	{
		$query = "SELECT * FROM `offers` as `o`, `client` as `c` WHERE `o`.`offer_status`!='2' AND `o`.`client_id`=`c`.`id` AND `o`.`offer_id`=$offid"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	///////////////////////////////////////////////
	public function getAllClients()
	{
		$query = "SELECT * FROM client ORDER BY id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getClientById($cid)
	{
		$query = "SELECT * FROM client WHERE id=$cid"; 
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
		 $query = "SELECT * FROM products AS p, client AS c WHERE c.id = p.client_id AND p.client_id=$cid AND p.pd_status!=2 ORDER BY p.pd_id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	public function getOfferedProductsByCID($cid)
	{
		//$query = "SELECT * FROM product AS p, client AS c WHERE c.id = p.client_id AND p.client_id=$cid AND p.offer = '1' ORDER BY p.id DESC";
		$query = "SELECT * FROM product AS p  WHERE  p.client_id=$cid AND p.offer = '1' AND p.active!='2' ORDER BY p.id DESC"; 
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
		$query = "SELECT * FROM product AS p, product_related AS pr WHERE pr.relatedto_id = p.id AND pr.relatedfrom_id=$pid ORDER BY p.id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getRelatedProductId($pid)
	{
		$query = "SELECT relatedto_id FROM product_related WHERE relatedfrom_id=$pid ORDER BY relatedfrom_id DESC"; 
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
		{   $query = "SELECT * FROM `category` ORDER BY id DESC"; 
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
		{   $query = "SELECT * FROM `category` WHERE `id`=$id ORDER BY id DESC"; 
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


	///////////////////
	///////////////////
	public function getClientProducts($sendArray)
	{
		try 
		{   echo $query = "SELECT * FROM `products_analytics` WHERE   DATE_FORMAT(pd_created_date, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'"; 
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