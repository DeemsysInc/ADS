<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php';
class Model extends SQLQuery {
	protected $_model;
	
	function __construct() {
		try{
			global $config;
			$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
			
			$this->mainDbName = $config['database']['name'];
			$this->markersDbName = $config['database']['name_markers'];
			$this->usersDbName = $config['database']['name_users'];
			$this->userAnalyticsDbName = $config['database']['name_user_analytics'];
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function __destruct() {
	}
}
?>