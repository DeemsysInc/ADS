<?php 
/**** Include interfaces link ****/
//require_once(SRV_ROOT.'interfaces/interfaces.inc.php');

class cPageBuilder{

	/*** define public & private properties ***/
	
	 public $objConfig;
	 public $getConfig;
	 public $objQuery;
	 private $objModules;
	 
	/*** the constructor ***/
	public function __construct(){
		 require_once SRV_ROOT.'classes/modules.class.php';
		$this->objModules = new cModules();

		require_once(SRV_ROOT.'model/model.class.php');
			$this->objQuery = new Model();
	}

	/**** function to get all functions of Header and assign to header template ****/
	public function pageHeader(){
		try{
			global $config;
			/**** assign variables/array to header.tpl.php ****/
			//echo "header";
			
			  include SRV_ROOT.'views/header.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	/**** function to get all functions of Content and assign to content template ****/
	public function pageContent($pUrl){
		try{
			global $config;
			
			//$pAction =  isset($pUrl[0]) ? $pUrl[0] :'';//for live
			$pAction =  isset($pUrl[1]) ? $pUrl[1] :'';//for localhost
			/*** Declare local variable for this function ***/
			if ($pAction=='whyseemore'){
				include SRV_ROOT.'views/whyseemore.tpl.php';
			}
			elseif ($pAction=='pressrelease'){
				$pressReleasesArray=$this->objQuery->getPressReleases();
			        include SRV_ROOT.'views/press_release_archive.tpl.php';
			}
			elseif ($pAction=='home'){
			   //if(isset($pUrl[1]) && $pUrl[1]=='privacy'){//for live
			   if(isset($pUrl[2]) && $pUrl[2]=='privacy'){//for localhost
			   include SRV_ROOT.'views/home/privacy.tpl.php';
			   }
			   else if(isset($pUrl[2]) && $pUrl[2]=='terms')
			   {
			   	include SRV_ROOT.'views/home/terms_and_conditions.tpl.php';

			   }

			   else
			   {
			   include SRV_ROOT.'views/home.tpl.php';
			   }
			   
			}elseif ($pAction=='contact'){
			include SRV_ROOT.'views/contact.tpl.php';
			}
			
			else
			{
			include SRV_ROOT.'views/home.tpl.php';
			}
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	/**** function to get Page Footer ****/
	public function pageFooter(){
		try{
			global $config;
			include SRV_ROOT.'views/footer.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>