<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mProducts extends SQLQuery {
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
	
	public function getAllProductsByClientId($sendArray)
	{
		$query = "SELECT * FROM `products` AS `p` WHERE `p`.`client_id`=".$sendArray['client_id']." AND `p`.`pd_status`=1"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>