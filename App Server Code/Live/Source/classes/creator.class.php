<?php 
class cCreator{

	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objCommon;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/clients.model.class.php');
			$this->objClients = new mClients();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'classes/common.class.php');
			$this->objCommon = new cCommon();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowCreatorList(){
		try{
			global $config;
			//echo "comming soon..........";
			/*$outArrAllClients = array();
			
			$outArrAllClients = $this->objClients->getAllClients();*/
			include SRV_ROOT.'views/clients/creator_list.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxAddButton(){
		try{
			global $config;
			
			//echo "comming soon..........";\	
			echo '<button onclick="return false;" id="btn">text button</button>';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxAdd3DModel(){
		try{
			global $config;
			
			//echo "comming soon..........";\	 $config['LIVE_URL']
			echo '<img src='.$config['LIVE_URL'].'views/images/image_3dcross.png id="3dmodel" alt="3d model" width="50%" />';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxAddURL(){
		try{
			global $config;
			
			//echo "comming soon..........";
		    echo '<textarea id="ta_url">Add URL</textarea>';
			//echo '<img src='.$config['LIVE_URL'].'views/images/image_3dcross.png id="3dmodel" alt="3d model" width="50%" />';
			
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