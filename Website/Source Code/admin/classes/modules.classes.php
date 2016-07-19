<?php 


class cModules{

	/*** define public & private properties ***/
	private $objQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			
			/*require_once SRV_ROOT.'/classes/config.class.php';
			$objConfig = new cConfig();
			$getConfig = $objConfig->config();*/
			
			/**** Create Model Class and Object ****/
			//require_once('C:/wamp/www/admin-panel/model/model.class.php');
			
			$adminFlag = 1;
			require_once dirname(SRV_ROOT).'/smcfg.php';
			global $config;
			require_once($config['ABSOLUTEPATH'].'model/model.class.php');
			//require_once SRV_ROOT.'/model/model.class.php';
			$this->objQuery = new Model();

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	public function modLogin($pArray){
		try{
			$uArray = array();
			$uCon = array();
			$uArray['login_date'] = 'NOW()';
			$uArray['user_ip'] = $_SERVER['REMOTE_ADDR'];
			$uCon['user_name'] = $pArray['username'];
			
			//$this->objQuery->getUpdateAdminUsersTable($uArray, 'mvc_admin_users', $uCon);
			
			$arrLogin = $this->objQuery->getLogin($pArray);
			//print_r($arrLogin);
			//Checking whether user exists in database 
			if(count($arrLogin) > 0)
			{
				
				$userId = $arrLogin[0]['user_id'];		
				$_SESSION['admin_user_id']=$userId;
				$_SESSION['admin_user_name']=$arrLogin[0]['user_name'];
				$_SESSION['admin_group_id']=isset($arrLogin[0]['group_id']) ? $arrLogin[0]['group_id'] : '';
				//Update last login date in sales_user table
			    $pArray = array();
			    $pArray['last_login_date'] = "NOW()";
			    $tableName = "admin_users";
			    $con=array();
			    $con['user_id']=$_SESSION['admin_user_id'];
			    $outUpdateResult = $this->objQuery->updateRecordQuery($pArray,$tableName,$con);
				echo 1;
			}
			else
			{
				echo 0;
			}
			
			
			
			
			return $arrLogin;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modSavePressReleases(){
		try{
			$pArray = array();
			$pArray['title'] = isset($_REQUEST['press_title']) ? $_REQUEST['press_title'] : '';
			$pArray['created_date'] = isset($_REQUEST['press_date']) ? $_REQUEST['press_date'] : '';
			$pArray['url'] = isset($_REQUEST['press_url']) ? $_REQUEST['press_url'] : '';
			$pArray['publication'] = isset($_REQUEST['press_publication']) ? $_REQUEST['press_publication'] : '';
			$tableName="press";
			$arrSavePress=array();
			$arrSavePress = $this->objQuery->insertQuery($pArray,$tableName,false);
			return $arrSavePress;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modGetPressList(){
		try{
			global $config;
			$outArray = array();
			//$this->objQuery->getUpdateAdminUsersTable($uArray, 'mvc_admin_users', $uCon);
		        $outArray = $this->objQuery->getPressReleases();
			
			include SRV_ROOT.'views/press/press_list.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modEditPressReleaseForm($press_id){
		try{
			global $config;
			/*$pArray = array();
			$pArray['title'] = isset($_REQUEST['press_title']) ? $_REQUEST['press_title'] : '';
			$pArray['createddate'] = isset($_REQUEST['press_date']) ? $_REQUEST['press_date'] : '';
			$pArray['url'] = isset($_REQUEST['press_url']) ? $_REQUEST['press_url'] : '';
			$pArray['publication'] = isset($_REQUEST['press_publication']) ? $_REQUEST['press_publication'] : '';
			$tableName="press";
			$arrUpdatePress=array();
			$con = array();
			$con['id']= $press_id;
			$arrUpdatePress = $this->objQuery->updateRecordQuery($pArray,$tableName,$con);
			return $arrUpdatePress;*/
		        $outArray = array();
			$outArray = $this->objQuery->getPressReleasesById($press_id);
			
			include SRV_ROOT.'views/press/press_edit_form.tpl.php';
		
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUpdatePressReleases(){
		try{
			global $config;
			$pArray = array();
			$pArray['title'] = isset($_REQUEST['press_title']) ? $_REQUEST['press_title'] : '';
			$pArray['created_date'] = isset($_REQUEST['press_date']) ? $_REQUEST['press_date'] : '';
			$pArray['url'] = isset($_REQUEST['press_url']) ? $_REQUEST['press_url'] : '';
			$pArray['publication'] = isset($_REQUEST['press_publication']) ? $_REQUEST['press_publication'] : '';
			$tableName="press";
			$arrUpdatePress=array();
			$con = array();
			$con['id']= isset($_REQUEST['press_id']) ? $_REQUEST['press_id'] : '';
			$arrUpdatePress = $this->objQuery->updateRecordQuery($pArray,$tableName,$con);
			return $arrUpdatePress;
		      
		
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGetNewsList(){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objQuery->getNewsList();
			
			include SRV_ROOT.'views/news/news_list.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modSaveNews(){
		try{
			
		        $pArray = array();
			$pArray['title'] = isset($_REQUEST['news_title']) ? $_REQUEST['news_title'] : '';
			$pArray['subtitle'] = isset($_REQUEST['news_subtitle']) ? $_REQUEST['news_subtitle'] : '';
			$pArray['location'] = isset($_REQUEST['news_location']) ? $_REQUEST['news_location'] : '';
			$pArray['created_date'] = isset($_REQUEST['news_date']) ? $_REQUEST['news_date'] : '';
			$pArray['featured'] = isset($_REQUEST['news_featured']) ? $_REQUEST['news_featured'] : '';
			$pArray['include_about'] = isset($_REQUEST['news_include_about']) ? $_REQUEST['news_include_about'] : '';
			$pArray['excerpt'] = isset($_REQUEST['excerpt']) ? $_REQUEST['excerpt'] : '';
			$pArray['content'] = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
		
			$tableName="news";
			$arrSaveNews=array();
			$arrSaveNews = $this->objQuery->insertQuery($pArray,$tableName,false);
			return $arrSaveNews;
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modEditNewsForm($news_id){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objQuery->getNewsListById($news_id);
			
			include SRV_ROOT.'views/news/news_edit_form.tpl.php';
		
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUpdateNews(){
		try{
			global $config;
			$pArray = array();
			$pArray['title'] = isset($_REQUEST['news_title']) ? $_REQUEST['news_title'] : '';
			$pArray['subtitle'] = isset($_REQUEST['news_subtitle']) ? $_REQUEST['news_subtitle'] : '';
			$pArray['location'] = isset($_REQUEST['news_location']) ? $_REQUEST['news_location'] : '';
			$pArray['created_date'] = isset($_REQUEST['news_date']) ? $_REQUEST['news_date'] : '';
			$pArray['featured'] = isset($_REQUEST['news_featured']) ? $_REQUEST['news_featured'] : '';
			$pArray['include_about'] = isset($_REQUEST['news_include_about']) ? $_REQUEST['news_include_about'] : '';
			$pArray['excerpt'] = isset($_REQUEST['excerpt']) ? $_REQUEST['excerpt'] : '';
			$pArray['content'] = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
		
			$tableName="news";
			$arrUpdateNews=array();
			$con = array();
			$con['id']= isset($_REQUEST['news_id']) ? $_REQUEST['news_id'] : '';
			$arrUpdateNews = $this->objQuery->updateRecordQuery($pArray,$tableName,$con);
			return $arrUpdateNews;
		      
		
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modDeletePressRelease(){
		try{
			global $config;
		        $con = array();
			$con['id'] = isset($_REQUEST['press_id']) ? $_REQUEST['press_id'] : '';
		
			$tableName="press";
			$arrDeleteNews=array();
			$arrDeleteNews = $this->objQuery->deleteById($tableName, $con);
			
		        if ($arrDeleteNews){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_ADMIN_URL'].'press';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modDeleteNews(){
		try{
			global $config;
		        $con = array();
			$con['id'] = isset($_REQUEST['news_id']) ? $_REQUEST['news_id'] : '';
		
			$tableName="news";
			$arrDeleteNews=array();
			$arrDeleteNews = $this->objQuery->deleteById($tableName, $con);
			
		        if ($arrDeleteNews){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_ADMIN_URL'].'news';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		
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