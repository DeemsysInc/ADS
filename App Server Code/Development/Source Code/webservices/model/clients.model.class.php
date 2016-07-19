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
	
	public function getAllClientsInfo()
	{
		$query = "SELECT * FROM `client` AS `c`,`client_details` AS `cd` WHERE `c`.`id`= `cd`.`client_id` AND `c`.`active`!='2' ORDER BY `c`.`created_date` DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	public function getAllClientVerticals()
	{
		$query = "SELECT * FROM `client_verticals` AS `cv`"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getAllCountries()
	{
		$query = "SELECT country_id,country_code_char2,country_name,currency_code FROM `countries` AS `c`"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getAllStatesByCountry($countryCode)
	{
		$query = "SELECT *  FROM `countries_states_subdivisions` WHERE `country_code_char2` LIKE '$countryCode'"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>