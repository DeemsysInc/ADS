<?php 

class cPageBuilder{

	/*** define public & private properties ***/
	 private $objMenu;
	 private $_page;
	 private $objModules;
	 public $objConfig;
	 public $getConfig;
	 private $objUsers;
	/*** the constructor ***/
	public function __construct(){
		
		//$this->_page = $pPage;
		/**** Create Menus Class and Object ****/
		
		/*require_once SRV_ROOT.'classes/menus.class.php';
		$this->objMenu = new cMenus();*/

		/**** assign variables/array to content.tpl.php ****/
		 require_once SRV_ROOT.'classes/modules.classes.php';
		$this->objModules = new cModules();
		
		
		/*call class*/
		require_once SRV_ROOT.'classes/users.classes.php';
		$this->objUsers = new cUsers();
		
		/*require_once SRV_ROOT.'classes/config.class.php';
		$this->objConfig = new cConfig();
		$this->getConfig = $this->objConfig->config();
		//print_r($this->getConfig);
		*/
	}

	/**** function to get all functions of Header and assign to header template ****/
	public function pageHeader(){
		try{
			/**** assign variables/array to header.tpl.php ****/
			global $config;
			include SRV_ROOT.'views/home/header.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	/**** function to get all functions of Content and assign to content template ****/
	public function pageContent($pUrl){
		try{
		
			global $config;
			//echo "content";
			
			$pAction =  $pUrl;
			$outArrResult = array();
		
			if(!isset($_SESSION['admin_user_id']))
			{
			    include SRV_ROOT.'views/home/login.tpl.php';
			}
			elseif(isset($pAction[1]) && $pAction[1]=="press")
			{
				if(isset($pAction[2]) && $pAction[2]=="create")
				{
					
					include SRV_ROOT.'views/press/create_press.tpl.php';
				}
				elseif(isset($pAction[2]) && $pAction[2]=="edit")
				{
					if (isset($pAction[3]) && !empty($pAction[3])){
							$this->objModules->modEditPressReleaseForm($pAction[3]);
						}else{
							
							$this->objModules->modGetPressList();
						}
					
				}
				else
				{
					$this->objModules->modGetPressList();
					
					
				}
				
				
			}
			elseif(isset($pAction[1]) && $pAction[1]=="news")
			{
				if(isset($pAction[2]) && $pAction[2]=="create")
				{
					
					include SRV_ROOT.'views/news/create_news.tpl.php';
				}
				elseif(isset($pAction[2]) && $pAction[2]=="edit")
				{
					if (isset($pAction[3]) && !empty($pAction[3])){
							$this->objModules->modEditNewsForm($pAction[3]);
						}else{
							
							$this->objModules->modGetNewsList();
						}
					
				}
				else
				{
					$this->objModules->modGetNewsList();
					
					
				}
				
				
			}
			elseif(isset($pAction[1]) && $pAction[1]=="users")
			{
				if(isset($pAction[2]) && $pAction[2]=="edit")
				{
					//echo "user edit";
					if (isset($pAction[3]) && !empty($pAction[3])){
						$this->objUsers->modEditUserForm($pAction[3]);
					}
					
				}
				elseif(isset($pAction[2]) && $pAction[2]=="add")
				{
					//add new users
					$this->objUsers->modShowAddUserForm();
				}
				else
				{
					//users list
					$this->objUsers->modShowUserList(); 	
				}
				
				
			}
			else 
			{
			    // echo "content";
				include SRV_ROOT.'views/home/home.tpl.php';	
			}
			
			//$this->pageRight($pAction);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	/**** function to get Page Footer ****/
	public function pageFooter(){
		try{
				global $config;
				include SRV_ROOT.'views/home/footer.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get Page Left Modules ****/
	public function pageLeft(){
		try{
			echo 'left';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	/**** function to get Page right modules ****/
	public function pageRight($pAction){
		try{
			echo 'right';
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