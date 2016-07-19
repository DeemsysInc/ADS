<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mPublic extends SQLQuery {
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
	function getClientTrigger($clientId)
	{
		/*$query = "SELECT  `tr`.`url` ,  `tr`.`client_id` ,  `cl`.`name` FROM  `client` AS cl,  `trigger` AS  `tr` WHERE  `cl`.`id` ='".$clientId."'
AND  `cl`.id =  `tr`.`client_id`";
*/
$query = "SELECT  `tr`.`url` ,  `tr`.`client_id` ,  `tr`.`title` , `tr`.`instruction` ,  `cl`.`name` ,  `vi`.`discriminator` , `vi`.`product_id` ,  `vi`.`x` ,  `vi`.`y` ,  `vi`.`url` AS visual ,  `tr`.`height` ,  `tr`.`width`  FROM  `client` AS  `cl` ,  `trigger` AS  `tr` LEFT JOIN  `visual` AS  `vi` ON  `tr`.`id` =  `vi`.`trigger_id` WHERE  `cl`.`id` =  `tr`.`client_id` AND  `tr`.`active` =1 AND `tr`.`active` !=2 AND`cl`.`id` ='".$clientId."' AND  `tr`.`url` != '' ";

		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getAllTriggers()
	{
		/*$query = "SELECT  `tr`.`url` ,  `tr`.`client_id` ,  `tr`.`title` ,  `cl`.`name` ,  `vi`.`discriminator` , `vi`.`product_id` , `vi`.`x` ,  `vi`.`y` , `vi`.`url` AS visual ,  `tr`.`height` ,  `tr`.`width`  FROM  `client` AS  `cl` ,  `trigger` AS  `tr` LEFT JOIN  `visual` AS  `vi` ON  `tr`.`id` =  `vi`.`trigger_id` WHERE  `cl`.`id` =  `tr`.`client_id` AND  `tr`.`active` =1 AND  `tr`.`url` !=  ''";*/
		$query = "SELECT `tr`.`url` , `tr`.`client_id` ,  `tr`.`title` , `tr`.`instruction` ,  `cl`.`name` ,  `vi`.`discriminator` , `vi`.`product_id` , `vi`.`x` ,  `vi`.`y` , `vi`.`url` AS visual ,  `tr`.`height` ,`tr`.`width`,`pd`.`image`FROM `trigger` as `tr` LEFT JOIN `visual` as `vi` ON `vi`.`trigger_id` = `tr`.id LEFT JOIN `product` as `pd` ON `vi`.`product_id` = `pd`.`id` LEFT JOIN `client` as `cl` ON `pd`.`client_id` = `cl`.`id` WHERE `tr`.`url` != '' AND `tr`.`active` =1 AND `tr`.`active` !=2";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getProductDetails($productId)
	{
		$query = "SELECT * FROM  `product` WHERE id  ='".$productId."'";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientProducts($clientId)
	{
		$query = "SELECT `image`,`title` FROM  `product` WHERE client_id  ='".$clientId."' AND offer = 0";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>