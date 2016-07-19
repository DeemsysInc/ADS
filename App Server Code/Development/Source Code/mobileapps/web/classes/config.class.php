<?php
//ini_set('display_errors', 1);
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$arrPath = pathinfo($thisFile);
$fileNamePath = $arrPath['basename'];
$webRoot  = str_replace(array($docRoot, $fileNamePath), '', $thisFile);
$srvRoot  = str_replace($fileNamePath, '', $docRoot);
if (!defined('SRV_ROOT')) {
	define('SRV_ROOT', $srvRoot.'/');
}

/**** Include interfaces link ****/
require_once(SRV_ROOT.'interfaces/interfaces.inc.php');

class cConfig implements iConfig{

	/*** define public & private properties ***/
	public $objQuery;
	public $config;

	/*** the constructor ***/
	public function __construct(){
		/**** Create Model Class and Object ****/
		/**** Create Model Class and Object ****/
		require_once(SRV_ROOT.'model/model.class.php');
		$this->objQuery = new Model();		
	}
	
	public function config(){
		require_once(SRV_ROOT.'smcfg_new.php');
		global $config;
		return $config;
	}
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>