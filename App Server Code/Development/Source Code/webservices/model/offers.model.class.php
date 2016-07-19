<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mOffers extends SQLQuery {
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
	
	public function getAllOffersByClientId($sendArray)
	{
		$query = "SELECT * FROM `offers` AS `o`,`offers_info` AS `oi` WHERE `o`.`offer_id`= `oi`.`offer_id` AND `o`.`client_id`=".$sendArray['client_id']." AND `o`.`offer_status`=1"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>