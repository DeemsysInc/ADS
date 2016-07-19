<?php

class cConfig{

	/*** define public & private properties ***/
	public $objQuery;
	public $config;

	/*** the constructor ***/
	public function __construct(){
		/**** Create Model Class and Object ****/
		/**** Create Model Class and Object ****/
		/*require_once(SRV_ROOT.'model/model.class.php');
		$this->objQuery = new Model();*/		
	}
	
	public function config(){
		require_once(SRV_ROOT.'smcfg.php');
		
		global $config;
		return $config;
	}
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>