<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mUsers extends SQLQuery {
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
	
	public function getAllAppUsersInfo($sendArray)
	{
		$query = "SELECT * FROM `users` AS `u`,`user_details` AS `ud` WHERE `u`.`user_id`= `ud`.`user_id` AND `u`.`user_status`=1 ORDER BY `u`.`user_id` DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>