<?php 
class cClients{

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
	public function modShowAllClients(){
		try{
			global $config;
			//$outArrAllClients = array();
			//$outArrAllClients = $this->objClients->getAllClients();
            $baseUrl = $config['LIVE_URL'].'webservices/clients/list';
			$params=array();
			$outArrAllClients=$this->objCommon->modCallWebservices($baseUrl,$params);
            include SRV_ROOT.'views/clients/clients_list.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowAllClientGroups(){
		try{
			global $config;
			$outArrAllClientGroups = array();
			$outArrAllClientGroups = $this->objClients->getAllClientGroups();
			include SRV_ROOT.'views/clients/client_groups.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modClientProducts($cid){
		try{
			global $config;
			$outArrClientProducts = array();
			$outArrClientProducts = $this->objClients->getProductsByCID($cid);
			//print_r($outArrClientProducts );
			include SRV_ROOT.'views/clients/client_products.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientById($cid){
		try{
			global $config;
			$outArrClient = array();
			$outArrClientProducts = array();
			$outArrClientTriggers = array();
			
			$outArrClient = $this->objClients->getClientById($cid);
		    $verticalClientId=isset($outArrClient[0]['client_vertical_id']) ? $outArrClient[0]['client_vertical_id'] :'';
			$outArrVerticalClients = array();
			$outArrVerticalClients =$this->objClients->getVerticalClientsById($verticalClientId);
			$outArrClientProducts = $this->objClients->getProductsByCID($cid);
			$outArrClientTriggers = $this->objClients->getTriggersByCID($cid);
			$outArrClientStores = array();
			$outArrClientStores = $this->objClients->getClientStoresByCId($cid);
			include SRV_ROOT.'views/clients/client_dashboard.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowClientDashboard(){
		try{
			global $config;
			
			$outArrClientUserInfo = array();
			$outArrClientUserInfo = $this->objClients->getClientUsersInfoByID($_SESSION['user_id']);
			
			$cid=isset($outArrClientUserInfo[0]['client_ids']) ? $outArrClientUserInfo[0]['client_ids'] : '';
			
			$outArrClient = array();
			$outArrClient = $this->objClients->getClientById($cid);
			$outArrClientProducts = array();
			$outArrClientProducts = $this->objClients->getProductsByCID($cid);
			
			$outArrClientTriggers = array();
			$outArrClientTriggers = $this->objClients->getTriggersByCID($cid);
			include SRV_ROOT.'views/clients/client_dashboard.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modClientProductEdit($cid,$pid){
		try{
			global $config;
			$outArrClientProductEdit = array();
			$outArrClientProductEdit = $this->objClients->getProductByPID($pid);
		    //print_r($outArrClientProductEdit);
			$getCatagories=array();
			$getCatagories=$this->objClients->getCatagories();
			//print_r($getCatagories);
			$getStyles=array();
			$getStyles=$this->objClients->getStyles();
			
			$arrgetStylesIds=array();
			for($i=0;$i<count($getStyles);$i++){
			 $arrgetStylesIds[]=$getStyles[$i]['id'];
			}
			
			include SRV_ROOT.'views/clients/client_product_edit.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveClientProduct(){
		try{
			global $config;
			$arrData=array();
			
			$arrData['pd_name']=isset($_REQUEST['p_title']) ? $_REQUEST['p_title'] : '';
			$arrData['pd_barcode']=isset($_REQUEST['p_barcode']) ? $_REQUEST['p_barcode'] : '';
			$arrData['pd_description']=isset($_REQUEST['p_description']) ? $_REQUEST['p_description'] : '';
			$arrData['pd_price']=isset($_REQUEST['p_price']) ? $_REQUEST['p_price'] : '';
			$arrData['pd_short_description']=isset($_REQUEST['p_shortdescription']) ? $_REQUEST['p_shortdescription'] : '';
			$arrData['pd_status']=isset($_REQUEST['p_status']) ? $_REQUEST['p_status'] : '';
			$arrData['pd_url']=isset($_REQUEST['p_website']) ? $_REQUEST['p_website'] : '';
			// $arrData['hide_background']=isset($_REQUEST['p_background']) ? $_REQUEST['p_background'] : '';
			$arrData['pd_category_id']=isset($_REQUEST['p_catagory']) ? $_REQUEST['p_catagory'] : '';
			$arrData['pd_button_name']=isset($_REQUEST['p_button_name']) ? $_REQUEST['p_button_name'] : '';
			$arrData['pd_istryon']=isset($_REQUEST['p_is_tryon']) ? $_REQUEST['p_is_tryon'] : '';
			$arrData['pd_created_date']='NOW()';
			$pid=isset($_REQUEST['p_id']) ? $_REQUEST['p_id'] : '';
			$cid=isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : '';
			$outArrGetClientProducts = $this->objClients->getProductByPID($pid);
			if(isset($_FILES['p_image']['name']) && $_FILES['p_image']['name']!='')
			{
				if(isset($outArrGetClientProducts[0]['pd_image']) && $outArrGetClientProducts[0]['pd_image']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['products']).$outArrGetClientProducts[0]['pd_image'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['products']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}
						chmod($srcfile, 0777);				
					//copy
					if (copy($srcfile,$dstfile.$outArrGetClientProducts[0]['pd_image'])) {
						//deleting previous image
						unlink($srcfile);
					}
				}
				 $arrData['pd_image']=basename($_FILES['p_image']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['products']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['p_image']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['p_image']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			$tableName="products";
			$con=array();
			$con['pd_id']=$pid;
			$outArrUpdateClientProduct = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			$uArray=array();
			$uArray['pd_bg_color']=isset($_REQUEST['bcid']) ? $_REQUEST['bcid'] : '';
		    $uArray['pd_hide_bg_image']=isset($_REQUEST['p_background']) ? $_REQUEST['p_background'] : '';
		    $getSavedRecordBackgroundView = $this->objClients->updateRecordQuery($uArray,"products_background_view",$con);
			
			if ($outArrUpdateClientProduct){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products/'.$pid.'/edit/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggers($cid){
		try{
			global $config;
			$outArrClientTriggers = array();
			
			$outArrClientTriggers = $this->objClients->getTriggersByCID($cid);
			
			include SRV_ROOT.'views/clients/client_triggers.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggerEdit($cid,$tid){
		try{
			global $config;
			$outArrAllVerticalClients = array();
			$outArrAllVerticalClients =$this->objClients->getVerticalClients();

			$outArrClientTriggerEdit = array();
			$outArrClientTriggerEdit = $this->objClients->getTriggerByTID($tid);
			
			include SRV_ROOT.'views/clients/client_trigger_edit.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
		public function modAjaxupdateClientTrigger(){
		try{
			global $config;
			$arrData=array();
			$arrData['title']=isset($_REQUEST['t_title']) ? $_REQUEST['t_title'] : '';
			$arrData['height']=isset($_REQUEST['t_height']) ? $_REQUEST['t_height'] : '';
			$arrData['width']=isset($_REQUEST['t_width']) ? $_REQUEST['t_width'] : '';
			$arrData['instruction']=isset($_REQUEST['t_instruction']) ? $_REQUEST['t_instruction'] : '';
			$arrData['active']=isset($_REQUEST['t_status']) ? $_REQUEST['t_status'] : 1;
			$arrData['trigger_by_vertical']=isset($_REQUEST['trigger_by_vertical']) ? $_REQUEST['trigger_by_vertical'] : 1;
			$t_id=isset($_REQUEST['t_id']) ? $_REQUEST['t_id'] : '';
			$cid=isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : '';
			$outArrGetClientTriggers= $this->objClients->getTriggerByTID($t_id);
			if(isset($_FILES['t_image']['name']) && $_FILES['t_image']['name']!='')
			{
				if(isset($outArrGetClientTriggers[0]['image']) && $outArrGetClientTriggers[0]['image']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['triggers']).$outArrGetClientTriggers[0]['image'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['triggers']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}
						chmod($srcfile, 0777);				
					//copy
					if (copy($srcfile,$dstfile.$outArrGetClientTriggers[0]['image'])) {
						//deleting previous image
						unlink($srcfile);
					}

				}
				
				 $arrData['url']=basename($_FILES['t_image']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['triggers']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				 $target =  $dir.basename($_FILES['t_image']['name']) ;
	             if(move_uploaded_file($_FILES['t_image']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
	
			$tableName="trigger";
			$con=array();
			$con['id']=$t_id;
			$outArrUpdateClientTrigger= $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			if ($outArrUpdateClientTrigger){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxupdateClientLogo($arrData,$cid){
		try{
			global $config;
			$tableName="client";
			$con=array();
			$con['id']=$cid;
			$outArrUpdateClientLogo= $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			if ($outArrUpdateClientLogo){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid;
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowClientEditForm($pUrl){
		try{
			global $config;
			$cId=isset($pUrl[2]) ? $pUrl[2] : 0;
			$outArrClientEditDetails = array();
			$outArrClientEditDetails = $this->objClients->getClientById($cid);

			$outArrAllVerticalClients = array();
			$outArrAllVerticalClients =$this->objClients->getVerticalClients();
			
			$outArrStatesList = array();
			$outArrStatesList =$this->objClients->getStatesList();
			
			$outArrCountryList = array();
			$outArrCountryList =$this->objClients->getCountryList();
			
			include SRV_ROOT.'views/clients/client_add_edit_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxupdateClientDetails(){
		try{
			global $config;
			$arrData=array();
			$arrData['name']=isset($_REQUEST['c_name']) ? $_REQUEST['c_name'] : '';
			$arrData['prefix']=isset($_REQUEST['c_prefix']) ? $_REQUEST['c_prefix'] : '';
			$arrData['active']=isset($_REQUEST['c_status']) ? $_REQUEST['c_status'] : '';
			$arrData['url']=isset($_REQUEST['c_website']) ? $_REQUEST['c_website'] : '';
			$arrData['background_color']=isset($_REQUEST['hdn_bcid']) ? $_REQUEST['hdn_bcid'] : '';
			$arrData['light_color']=isset($_REQUEST['hdn_lcid']) ? $_REQUEST['hdn_lcid'] : '';
			$arrData['dark_color']=isset($_REQUEST['hdn_dcid']) ? $_REQUEST['hdn_dcid'] : '';
			$arrData['is_demo']=isset($_REQUEST['c_is_demo']) ? $_REQUEST['c_is_demo'] : '';
			$arrData['client_vertical_id']=isset($_REQUEST['c_client_vertical']) ? $_REQUEST['c_client_vertical'] : '0';
			$arrData['is_location_based']=isset($_REQUEST['c_is_location_based']) ? $_REQUEST['c_is_location_based'] : '0';
			$arrData['is_affiliate']=isset($_REQUEST['c_is_affiliate']) ? $_REQUEST['c_is_affiliate'] : '0';
			$arrData['store_notify_msg']=isset($_REQUEST['store_notify_msg']) ? $_REQUEST['store_notify_msg'] : '0';
			
			$cid=isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : '';
			$outArrClientDetails = $this->objClients->getClientById($cid);
			if(isset($_FILES['c_logo']['name']) && $_FILES['c_logo']['name']!='')
			{
					 
				$srcfile=str_replace("{client_id}",$cid,$config['root_files']['logo']).$outArrClientDetails[0]['logo'];
				$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['logo']);
				if ( !file_exists($dstfile) ) {
				  @mkdir ($dstfile, 0777, true);
				  chmod($dstfile, 0777);
				}
					chmod($srcfile, 0777);				
				//copy
				if (copy($srcfile,$dstfile.$outArrClientDetails[0]['logo'])) {
					//deleting previous image
					unlink($srcfile);
				}
								
				$arrData['logo']=basename($_FILES['c_logo']['name']);
				$dir=str_replace("{client_id}",$cid,$config['root_files']['logo']);
				if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				}
				$target =  $dir.basename($_FILES['c_logo']['name']) ;
				if(move_uploaded_file($_FILES['c_logo']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			if(isset($_FILES['c_bgimage']['name']) && $_FILES['c_bgimage']['name']!='')
			{
				
				$srcfile=str_replace("{client_id}",$cid,$config['root_files']['background']).$outArrClientDetails[0]['background_image'];
				$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['logo']);
				if ( !file_exists($dstfile) ) {
				  @mkdir ($dstfile, 0777, true);
				  chmod($dstfile, 0777);
				}
					chmod($srcfile, 0777);				
				if (copy($srcfile,$dstfile.$outArrClientDetails[0]['background_image'])) {
					unlink($srcfile);
				}
				$arrData['background_image']=basename($_FILES['c_bgimage']['name']);
				$dir=str_replace("{client_id}",$cid,$config['root_files']['background']);
				if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				}
				$target =  $dir.basename($_FILES['c_bgimage']['name']) ;
				if(move_uploaded_file($_FILES['c_bgimage']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			
			$tableName="client";
			$con=array();
			$con['id']=$cid;
			$outArrUpdateClientLogo= $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			if ($outArrUpdateClientLogo){
				//insert data in the client details table
				$uArray=array();
				$uArray['client_id']=$nextIncrementedClientId;
				$uArray['client_details_company']=isset($_REQUEST['c_company']) ? $_REQUEST['c_company'] : '';
				$uArray['client_details_address']=isset($_REQUEST['c_address']) ? $_REQUEST['c_address'] : '';
				$uArray['client_details_city']=isset($_REQUEST['c_city']) ? $_REQUEST['c_city'] : '';
				$uArray['client_details_state']=isset($_REQUEST['c_state']) ? $_REQUEST['c_state'] : '';
				$uArray['client_details_country_code']=isset($_REQUEST['c_country']) ? $_REQUEST['c_country'] : '';
			    $uArray['client_details_zip']=isset($_REQUEST['c_zip']) ? $_REQUEST['c_zip'] : '';
				$uArray['client_details_phone']=isset($_REQUEST['c_phone']) ? $_REQUEST['c_phone'] : '';
				$uArray['client_details_email']=isset($_REQUEST['c_email']) ? $_REQUEST['c_email'] : '';
				$uArray['client_details_currency_code']=isset($_REQUEST['c_currency_code']) ? $_REQUEST['c_currency_code'] : '';
				$uArray['client_details_created_date']=date('Y-m-d H:i:s');
				$uArray['client_details_status']=isset($_REQUEST['c_status']) ? $_REQUEST['c_status'] : '';
				
				$uArray['client_shipping_methods']=isset($_REQUEST['c_shipping_methods']) ? $_REQUEST['c_shipping_methods'] : '';
				$uArray['client_payment_methods']=isset($_REQUEST['c_payment_methods']) ? $_REQUEST['c_payment_methods'] : '';
				
				$tableName='client_details';
				$updateClientDetails=array();
			    $updateClientDetails = $this->objClients->updateRecordQuery($uArray, $tableName, $con);
				
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/edit/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientProductView($cid,$pid){
		try{
			global $config;
			$outArrClientProductView = array();
			$outArrClientProductView = $this->objClients->getProductByPID($pid);
			
			$category_id=isset($outArrClientProductView[0]['pd_category_id']) ? $outArrClientProductView[0]['pd_category_id']: 0;
			$style_id=isset($outArrClientProductView[0]['style_id']) ? $outArrClientProductView[0]['style_id']: 0;
			
			$getCatagoriesById=array();
			$getCatagoriesById = $this->objClients->getCatagoriesById($category_id);
			
			$getStylesById=array();
			$getStylesById = $this->objClients->getStylesById($style_id);
			
			include SRV_ROOT.'views/clients/client_products_view.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggerView($cid,$tid){
		try{
			global $config;
			$outArrClientTriggerView = array();
			
			$outArrClientTriggerView = $this->objClients->getTriggerByTID($tid);
			$outArrAllVerticalClients = array();
			$outArrAllVerticalClients =$this->objClients->getVerticalClients();
			include SRV_ROOT.'views/clients/client_triggers_view.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggerVisuals($cid,$tid){
		try{
			global $config;
			$outArrClientTriggerVisuals = array();
			
			$outArrClientTriggerVisuals = $this->objClients->getTriggerVisualsByTID($tid);
			
			include SRV_ROOT.'views/clients/client_trigger_visuals.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxDeleteClient(){
		try{
			global $config;
			$tableName='client';
			$con=array();
			$con['id']=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			
			$arrData=array();
			$arrData['active']=2;//1=deleted,0=enabled,2=trash
			$outArrUpdateClientDeleteStatus = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowClientAddForm(){
		try{
			global $config;
			$outArrAllVerticalClients = array();
			$outArrAllVerticalClients =$this->objClients->getVerticalClients();
			
			$outArrStatesList = array();
			$outArrStatesList =$this->objClients->getStatesList();
			
			$outArrCountryList = array();
			$outArrCountryList =$this->objClients->getCountryList();
			
			include SRV_ROOT.'views/clients/client_add_edit_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxGetCountryByState(){
		try{
			global $config;
			
			$state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : '';
			$outArrCountryList = array();
			$outArrCountryList =$this->objClients->getCountryByStateCode($state_code);
			print_r(json_encode($outArrCountryList));
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxGetStatesByCountry(){
		try{
			global $config;
			
			$country_code = isset($_REQUEST['country_code']) ? $_REQUEST['country_code'] : '';
			$outArrState = array();
			$outArrState =$this->objClients->getStateByCountryCode($country_code);
			print_r(json_encode($outArrState));
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAjaxCreateClientDetails(){
		try{
			global $config;
			$arrData=array();
			$arrData['name']=isset($_REQUEST['c_name']) ? $_REQUEST['c_name'] : '';
			$arrData['prefix']=isset($_REQUEST['c_prefix']) ? $_REQUEST['c_prefix'] : '';
			$arrData['active']=isset($_REQUEST['c_status']) ? $_REQUEST['c_status'] : '';
			$arrData['url']=isset($_REQUEST['c_website']) ? $_REQUEST['c_website'] : '';
			$arrData['background_color']=isset($_REQUEST['bcid']) ? $_REQUEST['bcid'] : '';
			$arrData['light_color']=isset($_REQUEST['lcid']) ? $_REQUEST['lcid'] : '';
			$arrData['dark_color']=isset($_REQUEST['dcid']) ? $_REQUEST['dcid'] : '';
			$arrData['is_demo']=isset($_REQUEST['c_is_demo']) ? $_REQUEST['c_is_demo'] : '';
			$arrData['client_vertical_id']=isset($_REQUEST['c_client_vertical']) ? $_REQUEST['c_client_vertical'] : '0';
			$arrData['is_location_based']=isset($_REQUEST['c_is_location_based']) ? $_REQUEST['c_is_location_based'] : '0';
			$arrData['is_affiliate']=isset($_REQUEST['c_is_affiliate']) ? $_REQUEST['c_is_affiliate'] : '0';
			$arrData['store_notify_msg']=isset($_REQUEST['store_notify_msg']) ? $_REQUEST['store_notify_msg'] : '0';
			
			$getClientTableStatus = $this->objClients->showClientTableStatus();
			$nextIncrementedClientId=isset($getClientTableStatus[0]['Auto_increment']) ? $getClientTableStatus[0]['Auto_increment'] : '';
			if(isset($_FILES['c_bgimage']['name']) && $_FILES['c_bgimage']['name']!='')
			{
				$arrData['background_image']=basename($_FILES['c_bgimage']['name']);
				$dir=SRV_ROOT."files/clients/".$nextIncrementedClientId."/background";
				 if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				 }
				$target =  SRV_ROOT."files/clients/".$nextIncrementedClientId."/background/".basename($_FILES['c_bgimage']['name']) ;
				if(move_uploaded_file($_FILES['c_bgimage']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			
			if(isset($_FILES['c_logo']['name']) && $_FILES['c_logo']['name']!='')
			{
				$arrData['logo']=basename($_FILES['c_logo']['name']);
				
				$dir=SRV_ROOT."files/clients/".$nextIncrementedClientId."/logo";
				 if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				 }
				
				$target =  SRV_ROOT."files/clients/".$nextIncrementedClientId."/logo/".basename($_FILES['c_logo']['name']) ;
				if(move_uploaded_file($_FILES['c_logo']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			$arrData['created_date']=date('Y-m-d H:i:s');
				
			$tableName="client";
			$con=array();
			$getSavedRecordId = $this->objClients->insertQuery($arrData, $tableName, true);
			if ($getSavedRecordId){
				
				//insert data in the client details table
				$iArray=array();
				$iArray['client_id']=$nextIncrementedClientId;
				$iArray['client_details_company']=isset($_REQUEST['c_company']) ? $_REQUEST['c_company'] : '';
				$iArray['client_details_address']=isset($_REQUEST['c_address']) ? $_REQUEST['c_address'] : '';
				$iArray['client_details_city']=isset($_REQUEST['c_city']) ? $_REQUEST['c_city'] : '';
				$iArray['client_details_state']=isset($_REQUEST['c_state']) ? $_REQUEST['c_state'] : '';
				$iArray['client_details_country_code']=isset($_REQUEST['c_country']) ? $_REQUEST['c_country'] : '';
			    $iArray['client_details_zip']=isset($_REQUEST['c_zip']) ? $_REQUEST['c_zip'] : '';
				$iArray['client_details_phone']=isset($_REQUEST['c_phone']) ? $_REQUEST['c_phone'] : '';
				$iArray['client_details_email']=isset($_REQUEST['c_email']) ? $_REQUEST['c_email'] : '';
				$iArray['client_details_currency_code']=isset($_REQUEST['c_currency_code']) ? $_REQUEST['c_currency_code'] : '';
				$iArray['client_details_created_date']=date('Y-m-d H:i:s');
				$iArray['client_details_status']=isset($_REQUEST['c_status']) ? $_REQUEST['c_status'] : '';
				
				$iArray['client_shipping_methods']=isset($_REQUEST['c_shipping_methods']) ? $_REQUEST['c_shipping_methods'] : '';
				$iArray['client_payment_methods']=isset($_REQUEST['c_payment_methods']) ? $_REQUEST['c_payment_methods'] : '';
				
				$tableName='client_details';
				$con=array();
				$insertClientDetails=array();
			    $insertClientDetails = $this->objClients->insertQuery($iArray, $tableName, true);
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveRelatedProducts(){
		try{
			global $config;
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			//checked values
			$arrRelatedProducts=isset($_REQUEST['related_prods']) ? $_REQUEST['related_prods'] : '';
			$arrRelatedProducts=explode("," ,$arrRelatedProducts);//checked prodyucts
			$arrData=array();
			$arrData['relatedfrom_id']=isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';//main product id
			
			//get all related products from main product
		    $outArrRelatedProductIds = $this->objClients->getRelatedProductId($arrData['relatedfrom_id']);
			$pTableName="product_related";
			
			$arrrelId=array();
			for($i=0;$i<count($outArrRelatedProductIds);$i++)
			{
				$arrrelId[]=$outArrRelatedProductIds[$i]['relatedto_id'];
					
			}
			if(isset($arrRelatedProducts[0]) && $arrRelatedProducts[0]=='')
			{
				//delete existing related products
				if(count($outArrRelatedProductIds)>0)
				{
					for($i=0;$i<count($outArrRelatedProductIds);$i++)
					{
						//echo "delete EXISTINNG RELATED PROD";
						$con=array();
					    $con['relatedfrom_id']=$arrData['relatedfrom_id'];
					    $retDeleteStatus = $this->objClients->deleteById($pTableName, $con);
					}
				}
			}
			else
			{
				//compare checked products with existing related products
				for($i=0;$i<count($arrRelatedProducts);$i++)
				{
					if (in_array($arrRelatedProducts[$i], $arrrelId, true)) {
						//update					
					   //echo "update";
					}
					else
					{
						 //insert
						 //echo "insert";
						 $arrData['relatedto_id']=isset($arrRelatedProducts[$i]) ? $arrRelatedProducts[$i] : 0;
						 $getSavedRecordId = $this->objClients->insertQuery($arrData, $pTableName, true);
							
					}
					
				}
				for($i=0;$i<count($arrrelId);$i++)
				 {
					if (in_array($arrrelId[$i], $arrRelatedProducts, true)) {
						 //update					
					    //echo "updateinner";
					}
					else
					{
						    //echo "delete".$arrrelId[$i];
						    $con=array();
							$con['relatedfrom_id']=$arrData['relatedfrom_id'];
							$con['relatedto_id']=$arrrelId[$i];
							$retDeleteStatus = $this->objClients->deleteById($pTableName, $con);
						 
//								
							  
					}
				}
			}
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products';
			
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveProductOffer(){
		try{
			global $config;
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$pid=isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
		    $rd_product=isset($_REQUEST['rd_products']) ? $_REQUEST['rd_products'] : 0;
			$arrData=array();
			$outArrOfferProductIds=array();
			$outArrOfferProductIds = $this->objClients->getOfferedProductId($pid);
			$pTableName="product_offer";
			
			if($rd_product!=0)
			{
				//
				if (count($outArrOfferProductIds)>0) {
					//update
					$arrData['offerto_id']=$rd_product;
					$con=array();
				    $con['offerfrom_id']=$pid;
					$outArrUpdateStatus = $this->objClients->updateRecordQuery($arrData,$pTableName,$con);
				}
				else
				{
					//insert
					$arrData['offerfrom_id']=$pid;
					$arrData['offerto_id']=$rd_product;
					$getSavedRecordId = $this->objClients->insertQuery($arrData, $pTableName, true);
					
					
				}
			
			}
			else
			{
				// "delete";
				$con=array();
				$con['offerfrom_id']=$pid;
				$retDeleteStatus = $this->objClients->deleteById($pTableName, $con);
			}
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products';
			
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxDeleteProduct(){
		try{
			global $config;
			$tableName='products';
			$con=array();
			$con['pd_id']=isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$outArrGetProductByPID =array();
			$outArrGetProductByPID = $this->objClients->getProductByPID($con['pd_id']);
			/*$outArrGetProductAdditionalByPID=array();
			$outArrGetProductAdditionalByPID = $this->objClients->getAdditionalProductMedia($con['pd_id']);
			for($i=0;$i<count($outArrGetProductAdditionalByPID);$i++)
			{
				if(isset($outArrGetProductAdditionalByPID[$i]['media']) && $outArrGetProductAdditionalByPID[$i]['media']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['additional']).$outArrGetProductAdditionalByPID[$i]['media'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['additional']);
					if(file_exists($srcfile)){
						if ( !file_exists($dstfile) ) {
						  @mkdir ($dstfile, 0777, true);
						  chmod($dstfile, 0777);
						}
							chmod($srcfile, 0777);				
						if (copy($srcfile,$dstfile.$outArrGetProductAdditionalByPID[$i]['media'])) {
							unlink($srcfile);
						}
					}
				}
				$aTableName='additional_product_media';
				$arraData=array();
			    $arraData['active']=2;//1=deleted,0=enabled,2=tra
				$aCon=array();
				$aCon['id']=$outArrGetProductAdditionalByPID[$i]['id'];
			    $outArrUpdateProductDeleteStatus = $this->objClients->updateRecordQuery($arraData,$aTableName,$aCon);
		   }
		   */
			if(isset($outArrGetProductByPID[0]['pd_image']) && $outArrGetProductByPID[0]['pd_image']!='')
			{
				$srcfile=str_replace("{client_id}",$cid,$config['root_files']['products']).$outArrGetProductByPID[0]['pd_image'];
				$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['products']);
				
				if(file_exists($srcfile)){
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					   chmod($dstfile, 0777);
					}
					chmod($srcfile, 0777);
					if (copy($srcfile,$dstfile.$outArrGetProductByPID[0]['pd_image'])) {
					unlink($srcfile);
				    }
				}
			}
			
			$arrData=array();
			$arrData['pd_status']=2;//1=deleted,0=enabled,2=trash
			$outArrUpdateProductDeleteStatus=array();
			$outArrUpdateProductDeleteStatus = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveButtonVisual(){
		try{
			global $config;
			$pArray=array();
			$pArray['trigger_id']=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
			$pArray['x']=isset($_REQUEST['x']) ? $_REQUEST['x'] : 0;
			$pArray['y']=isset($_REQUEST['y']) ? $_REQUEST['y'] : 0;
		    $pArray['product_id']=isset($_REQUEST['add_product']) ? $_REQUEST['add_product'] : '';
			$pArray['discriminator']="BUTTON";
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
		    $visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : 0;
			$tableName="visual";
			
			if($visual_id=='')
			{
				$getSavedTriggerButton = $this->objClients->insertQuery($pArray, $tableName, true);
			}
			else
			{
				$arrData=array();
				$arrData['x']=isset($_REQUEST['x']) ? $_REQUEST['x'] : 0;
			    $arrData['y']=isset($_REQUEST['y']) ? $_REQUEST['y'] : 0;
				$arrData['product_id']=isset($_REQUEST['add_product']) ? $_REQUEST['add_product'] : '';
				$con=array();
				$con['id']=$visual_id;
				$getSavedTriggerButton = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			
			}
			if ($getSavedTriggerButton){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$pArray['trigger_id'].'/visuals/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxDeleteTriggerVisual(){
		try{
			global $config;
			$con = array();
			$pTableName = "visual";
			$con['id']=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$tid=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : '';
			
			
			
            $outArrGetVisualProductIds = $this->objClients->getTriggerVisualsByID($_REQUEST['visual_id']);
			
			if(isset($outArrGetVisualProductIds[0]['url']) && pathinfo($outArrGetVisualProductIds[0]['url'], PATHINFO_EXTENSION)=='mp4')
			{
				$dir=SRV_ROOT."files/clients/".$cid."/videos";
			    $target =  SRV_ROOT."files/clients/".$cid."/videos/".$outArrGetVisualProductIds[0]['url'] ;
				if ( file_exists($dir) ) {
				  unlink($target);
				 }
			}
			
			$retDeleteVisual = $this->objClients->deleteById($pTableName, $con);
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$tid.'/visuals/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSave3DModelVisual(){
		try{
			global $config;
			$pArray=array();
			$pArray['trigger_id']=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
			$pArray['x']=isset($_REQUEST['x']) ? $_REQUEST['x'] : 0;
			$pArray['y']=isset($_REQUEST['y']) ? $_REQUEST['y'] : 0;
			$pArray['rotation_x']= deg2rad(isset($_REQUEST['x_rot']) ? $_REQUEST['x_rot'] : 0);
			$pArray['rotation_y']=deg2rad(isset($_REQUEST['y_rot']) ? $_REQUEST['y_rot'] : 0);
			$pArray['rotation_z']=deg2rad(isset($_REQUEST['z_rot']) ? $_REQUEST['z_rot'] : 0);
			$pArray['scale']=isset($_REQUEST['scale']) ? $_REQUEST['scale'] : '';
			$pArray['animate_on_recognition']=isset($_REQUEST['animateRecog']) ? $_REQUEST['animateRecog'] : '';
			$pArray['discriminator']="3DMODEL";
			$pArray['product_id']=isset($_REQUEST['rd_products']) ? $_REQUEST['rd_products'] : '';
			
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			$tableName="visual";
			if($visual_id=='')
			{
				$getSavedTrigger3DModel = $this->objClients->insertQuery($pArray, $tableName, true);
			}
			else
			{
				$arrData=array();
				$arrData['x']=isset($_REQUEST['x']) ? $_REQUEST['x'] : 0;
			    $arrData['y']=isset($_REQUEST['y']) ? $_REQUEST['y'] : 0;
				$arrData['rotation_x']= deg2rad(isset($_REQUEST['x_rot']) ? $_REQUEST['x_rot'] : 0);
			    $arrData['rotation_y']=deg2rad(isset($_REQUEST['y_rot']) ? $_REQUEST['y_rot'] : 0);
			    $arrData['rotation_z']=deg2rad(isset($_REQUEST['z_rot']) ? $_REQUEST['z_rot'] : 0);
				$arrData['scale']=isset($_REQUEST['scale']) ? $_REQUEST['scale'] : '';
			    $arrData['animate_on_recognition']=isset($_REQUEST['animateRecog']) ? $_REQUEST['animateRecog'] : '';
				$arrData['product_id']=isset($_REQUEST['rd_products']) ? $_REQUEST['rd_products'] : '';
				$con=array();
				$con['id']=$visual_id;
				$getSavedTrigger3DModel = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
				
			}
			if ($getSavedTrigger3DModel){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$pArray['trigger_id'].'/visuals/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveUrlVisual(){
		try{
			global $config;
			$pArray=array();
			$pArray['trigger_id']=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
			$pArray['url']=isset($_REQUEST['url']) ? $_REQUEST['url'] : 0;
			$pArray['discriminator']="URL";
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			$tableName="visual";
			if(empty($visual_id))
			{
				$getSavedTriggerURL = $this->objClients->insertQuery($pArray, $tableName, true);
			}
			else
			{
				$arrData=array();
				$arrData['url']=isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
			    $con=array();
				$con['id']=$visual_id;
				$getSavedTriggerURL = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
				
			}
			
			if ($getSavedTriggerURL){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$pArray['trigger_id'].'/visuals/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveVideoVisual(){
		try{
			global $config;
			$pArray=array();
			$pArray['trigger_id']=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
			$pArray['discriminator']="VIDEO";
			$pArray['x']=isset($_REQUEST['x']) ? $_REQUEST['x'] : 0;
			$pArray['y']=isset($_REQUEST['y']) ? $_REQUEST['y'] : 0;
			$pArray['scale']=isset($_REQUEST['scale']) ? $_REQUEST['scale'] : 0;
			$pArray['video_in_metaio']=isset($_REQUEST['play_in_3d']) ? $_REQUEST['play_in_3d'] : 0;
			$pArray['ignore_tracking']=isset($_REQUEST['ignore_tracking']) ? $_REQUEST['ignore_tracking'] : 0;
			
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : 0;
			if(isset($_FILES['videofile']['name']) && $_FILES['videofile']['name']!='')
			{
			    $pArray['url']=basename($_FILES['videofile']['name']);
				$arrData['url']=basename($_FILES['videofile']['name']);
				$dir=SRV_ROOT."files/clients/".$cid."/videos";
				 if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				 }
				$target =  SRV_ROOT."files/clients/".$cid."/videos/".basename($_FILES['videofile']['name']) ;
				if(move_uploaded_file($_FILES['videofile']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			$tableName="visual";
			if($visual_id=='')
			{
				$getSavedTriggerVideo = $this->objClients->insertQuery($pArray, $tableName, true);
			}
			else
			{
				$arrData=array();
				$arrData['x']=isset($_REQUEST['x']) ? $_REQUEST['x'] : 0;
			    $arrData['y']=isset($_REQUEST['y']) ? $_REQUEST['y'] : 0;
			    $arrData['video_in_metaio']=isset($_REQUEST['play_in_3d']) ? $_REQUEST['play_in_3d'] : 0;
			    $arrData['ignore_tracking']=isset($_REQUEST['ignore_tracking']) ? $_REQUEST['ignore_tracking'] : 0;
				$arrData['scale']=isset($_REQUEST['scale']) ? $_REQUEST['scale'] : 0;
				$con=array();
				$con['id']=$visual_id;
				$outArrGetVisualProductIds = $this->objClients->getTriggerVisualsByID($visual_id);
			
				
				if(isset($_FILES['videofile']['name']) && $_FILES['videofile']['name']!='')
			      {
			     	if(isset($outArrGetVisualProductIds[0]['url']) && $outArrGetVisualProductIds[0]['url']!='')
				     {
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['triggers']).$outArrGetVisualProductIds[0]['url'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['triggers']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}			
					//copy
					if (copy($srcfile,$dstfile.$outArrGetVisualProductIds[0]['url'])) {
						//deleting previous image
						unlink($srcfile);
					}

				  }
				 $arrData['url']=basename($_FILES['videofile']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['triggers']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				 $target =  $dir.basename($_FILES['videofile']['name']) ;
	             if(move_uploaded_file($_FILES['videofile']['tmp_name'],$target)) ;//$chmod o+rw galleries
			       }
			    	
			$getSavedTriggerVideo = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
				
			}
			if ($getSavedTriggerVideo){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$pArray['trigger_id'].'/visuals/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	public function modAjaxSaveAudioVisual(){
		try{
			global $config;
			$pArray=array();
			$pArray['trigger_id']=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : 0;
			$pArray['discriminator']="AUDIO";
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : 0;
			if(isset($_FILES['audiofile']['name']) && $_FILES['audiofile']['name']!='')
			{
			    $pArray['url']=basename($_FILES['audiofile']['name']);
				$arrData['url']=basename($_FILES['audiofile']['name']);
				$dir=SRV_ROOT."files/clients/".$cid."/additional";
				 if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				 }
				$target =  SRV_ROOT."files/clients/".$cid."/additional/".basename($_FILES['audiofile']['name']) ;
				if(move_uploaded_file($_FILES['audiofile']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			$tableName="visual";
			if($visual_id=='')
			{
				$getSavedTriggerVideo = $this->objClients->insertQuery($pArray, $tableName, true);
			}
			else
			{
				$arrData=array();
				$con=array();
				$con['id']=$visual_id;
				$outArrGetVisualProductIds = $this->objClients->getTriggerVisualsByID($visual_id);
				if(isset($_FILES['audiofile']['name']) && $_FILES['audiofile']['name']!='')
			      {
			     	if(isset($outArrGetVisualProductIds[0]['url']) && $outArrGetVisualProductIds[0]['url']!='')
				     {
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['triggers']).$outArrGetVisualProductIds[0]['url'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['triggers']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}			
					//copy
					if (copy($srcfile,$dstfile.$outArrGetVisualProductIds[0]['url'])) {
						//deleting previous image
						unlink($srcfile);
					}

				  }
				 $arrData['url']=basename($_FILES['audiofile']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['triggers']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				 $target =  $dir.basename($_FILES['audiofile']['name']) ;
	             if(move_uploaded_file($_FILES['audiofile']['tmp_name'],$target)) ;//$chmod o+rw galleries
			       }
			    	
			$getSavedTriggerAudio = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
				
			}
			if ($getSavedTriggerAudio){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$pArray['trigger_id'].'/visuals/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	
	public function modAjaxDeleteTrigger(){
		try{
			global $config;
			$tableName='trigger';
			$con=array();
			$con['id']=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			//$retDeleteStatus = $this->objUsers->deleteById($pTableName, $con);
			$arrData=array();
			$arrData['active']=2;//1=deleted,0=enabled,2=trash
			$outArrUpdateTriggerDeleteStatus = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientProductAdditional($cid,$pid){
		try{
			global $config;
			$outArrClientProductAdditional = array();
			$outArrClientProductAdditional = $this->objClients->getAdditionalProductMedia($pid);
			
			include SRV_ROOT.'views/clients/client_products_additionalmedia.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveAdditionalMedia(){
		try{
			global $config;
			$arrData=array();
			$arrData['product_id']=isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			if(isset($_FILES['uploaded_mediafile']['name']) && $_FILES['uploaded_mediafile']['name']!='')
			{
				$arrData['media']=basename($_FILES['uploaded_mediafile']['name']);
				$dir=str_replace("{client_id}",$cid,$config['root_files']['additional']);
				if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				}
				$target =  $dir.basename($_FILES['uploaded_mediafile']['name']) ;
				if(move_uploaded_file($_FILES['uploaded_mediafile']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			$tableName="additional_product_media";
			$getSavedRecord = $this->objClients->insertQuery($arrData, $tableName, true);
			if ($getSavedRecord){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products/'.$arrData['product_id'].'/additional/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxDeleteAdditionalMedia(){
		try{
			global $config;
			$pid=isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
			$tableName='additional_product_media';
			$con=array();
			$con['id']=isset($_REQUEST['additional_id']) ? $_REQUEST['additional_id'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			/* $outArrGetAdditional = $this->objClients->getAdditionalMediaByid($_REQUEST['additional_id']);
			    $srcfile=str_replace("{client_id}",$cid,$config['root_files']['additional']).$outArrGetAdditional[0]['media'];
				$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['additional']);
				if ( !file_exists($dstfile) ) {
				  @mkdir ($dstfile, 0777, true);
				  chmod($dstfile, 0777);
				}
					chmod($srcfile, 0777);				
				if (copy($srcfile,$dstfile.$outArrGetAdditional[0]['media'])) {
					unlink($srcfile);
				} */
			$arrData['active']=2;//1=deleted,0=enabled,2=trash
			$outArrUpdateDeleteStatus = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products/'.$_REQUEST['pid'].'/additional/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxUpdateAdditionalMedia(){
		try{
			global $config;
			$tableName='additional_product_media';
			$con=array();
			$con['id']=isset($_REQUEST['additional_id']) ? $_REQUEST['additional_id'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$arrData=array();
			$arrData['product_id']=isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
			$outArrGetAdditional = $this->objClients->getAdditionalMediaByid($_REQUEST['additional_id']);
			if(isset($_FILES['uploaded_mediafile']['name']) && $_FILES['uploaded_mediafile']['name']!='')
			{
				if(isset($outArrGetAdditional[0]['media']) && $outArrGetAdditional[0]['media']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['additional']).$outArrGetAdditional[0]['media'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['additional']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}
						chmod($srcfile, 0777);				
					if (copy($srcfile,$dstfile.$outArrGetAdditional[0]['media'])) {
						unlink($srcfile);
					}
				}
				$arrData['media']=basename($_FILES['uploaded_mediafile']['name']);
				$dir=str_replace("{client_id}",$cid,$config['root_files']['additional']);
				if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				}
				$target =  $dir.basename($_FILES['uploaded_mediafile']['name']) ;
				if(move_uploaded_file($_FILES['uploaded_mediafile']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			$outArrUpdateTriggerDeleteStatus = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/products/'.$_REQUEST['pid'].'/additional/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowAddClientProductForm($cid){
		try{
			global $config;
			$getClientDetails=array();
			$getClientDetails=$this->objClients->getClientById($cid);
			//print_r($getClientDetails);
			$getCatagories=array();
			$getCatagories=$this->objClients->getCatagories();
			//print_r($getCatagories);
			$getStyles=array();
			$getStyles=$this->objClients->getStyles();
			include SRV_ROOT.'views/clients/client_product_add_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxAddClientProduct(){
		try{
			global $config;
			$arrData=array();
			$arrData['client_id']=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$arrData['pd_name']=isset($_REQUEST['p_title']) ? $_REQUEST['p_title'] : '';
			$arrData['pd_barcode']=isset($_REQUEST['p_barcode']) ? $_REQUEST['p_barcode'] : '';
			$arrData['pd_description']=isset($_REQUEST['p_description']) ? $_REQUEST['p_description'] : '';
			$arrData['pd_price']=isset($_REQUEST['p_price']) ? $_REQUEST['p_price'] : '';
			$arrData['pd_short_description']=isset($_REQUEST['p_shortdescription']) ? $_REQUEST['p_shortdescription'] : '';
			$arrData['pd_status']=isset($_REQUEST['p_status']) ? $_REQUEST['p_status'] : '';
			$arrData['pd_url']=isset($_REQUEST['p_website']) ? $_REQUEST['p_website'] : '';
			$arrData['pd_category_id']=isset($_REQUEST['p_catagory']) ? $_REQUEST['p_catagory'] : '';
			$arrData['pd_button_name']=isset($_REQUEST['p_button_name']) ? $_REQUEST['p_button_name'] : '';
			$arrData['pd_istryon']=isset($_REQUEST['p_is_tryon']) ? $_REQUEST['p_is_tryon'] : '';
			$arrData['pd_issmcart']=isset($_REQUEST['p_is_smcart']) ? $_REQUEST['p_is_smcart'] : '';
			$arrData['pd_created_by_id']=isset($_SESSION['user_group']) ? $_SESSION['user_group'] : '';
			$arrData['pd_created_date']='NOW()';
			

			//$arrData['style_id']=isset($_REQUEST['p_style']) ? $_REQUEST['p_style'] : '';
			
			//$arrData['html']=isset($_REQUEST['p_html']) ? $_REQUEST['p_html'] : '';
			/*
			$arrData['red']=isset($_REQUEST['p_red']) ? $_REQUEST['p_red'] : 0;
			$arrData['green']=isset($_REQUEST['p_green']) ? $_REQUEST['p_green'] : 0;
			$arrData['blue']=isset($_REQUEST['p_blue']) ? $_REQUEST['p_blue'] : 0;
			$arrData['offer']=isset($_REQUEST['p_offer']) ? $_REQUEST['p_offer'] : 0;
			*/
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$tableName="products";
			if(isset($_FILES['p_image']['name']) && $_FILES['p_image']['name']!='')
			{

				 $arrData['pd_image']=basename($_FILES['p_image']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['products']);
				 if ( !file_exists($dir) ) {
				  mkdir ($dir, 0777, true);
				 }
				
				$target =  $dir.basename($_FILES['p_image']['name']) ;
				if(move_uploaded_file($_FILES['p_image']['tmp_name'],$target)) ;//$chmod o+rw galleries
				
			}
			//print_r($arrData);
			$getSavedRecord = $this->objClients->insertQuery($arrData, $tableName, true);
			$pArray=array();
			$lastInsertedId=0;
			$lastInsertedId=mysql_insert_id();
			    
			if($getSavedRecord)
			{
				$pArray['pd_id']=$lastInsertedId;
			    $pArray['pd_bg_color']=isset($_REQUEST['p_bg_color']) ? $_REQUEST['p_bg_color'] : '';
			    $pArray['pd_hide_bg_image']=isset($_REQUEST['p_background']) ? $_REQUEST['p_background'] : '';
			    $pArray['pd_bg_status']=1;
			    $getSavedRecordBackgroundView = $this->objClients->insertQuery($pArray, "products_background_view", true);
				
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$_REQUEST['cid'].'/products/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShowClientTriggerAddForm($cid){
		try{
			global $config;
			$getClientDetails=$this->objClients->getClientById($cid);
			$outArrAllVerticalClients = array();
			$outArrAllVerticalClients =$this->objClients->getVerticalClients();
			include SRV_ROOT.'views/clients/client_trigger_add_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxAddClientTrigger(){
		try{
			global $config;
			$arrData=array();
			$arrData['title']=isset($_REQUEST['t_title']) ? $_REQUEST['t_title'] : '';
			$arrData['height']=isset($_REQUEST['t_height']) ? $_REQUEST['t_height'] : '';
			$arrData['width']=isset($_REQUEST['t_width']) ? $_REQUEST['t_width'] : '';
			$arrData['instruction']=isset($_REQUEST['t_instruction']) ? $_REQUEST['t_instruction'] : '';
			$arrData['client_id']=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$arrData['active']=isset($_REQUEST['t_status']) ? $_REQUEST['t_status'] : 1;
			$arrData['trigger_by_vertical']=isset($_REQUEST['t_client_vertical']) ? $_REQUEST['t_client_vertical'] : 1;
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$tableName="trigger";
			if(isset($_FILES['t_image']['name']) && $_FILES['t_image']['name']!='')
			{
			    $arrData['url']=basename($_FILES['t_image']['name']);
				$dir=str_replace("{client_id}",$cid,$config['root_files']['triggers']);
				if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				}
				$target =  $dir.basename($_FILES['t_image']['name']) ;
				if(move_uploaded_file($_FILES['t_image']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			$getSavedRecord = $this->objClients->insertQuery($arrData, $tableName, true);
			if ($getSavedRecord){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggerVisualsEditForm($cid,$tid,$visual_id){
		try{
			global $config;
			
			include SRV_ROOT.'views/clients/client_trigger_visual_edit_form.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientTriggerModels($cid,$tid,$visual_id){
		try{
			global $config;
			$getClientModelsList=$this->objClients->getClientModelsByVid($visual_id);
			include SRV_ROOT.'views/clients/client_trigger_models.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxUpdate3Dmodel(){
		try{
			global $config;
			$arrData=array();
			
			
			//$arrData['product']=isset($_REQUEST['rd_products']) ? $_REQUEST['rd_products'] : '';
			$tid=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$model_id=isset($_REQUEST['model_id']) ? $_REQUEST['model_id'] : '';
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			
			$outArrGetModelProductIds = $this->objClients->getClientModelsByMid($model_id);
			if(isset($_FILES['model']['name']) && $_FILES['model']['name']!='')
			{
				if(isset($outArrGetModelProductIds[0]['model']) && $outArrGetModelProductIds[0]['model']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['models']).$outArrGetModelProductIds[0]['model'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['models']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}
						chmod($srcfile, 0777);				
					//copy
					if (copy($srcfile,$dstfile.$outArrGetModelProductIds[0]['model'])) {
						//deleting previous image
						unlink($srcfile);
					}
				}
				 $arrData['model']=basename($_FILES['model']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['models']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['model']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['model']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			if(isset($_FILES['texture']['name']) && $_FILES['texture']['name']!='')
			{
				if(isset($outArrGetModelProductIds[0]['texture']) && $outArrGetModelProductIds[0]['texture']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['models']).$outArrGetModelProductIds[0]['texture'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['models']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}
						chmod($srcfile, 0777);				
					//copy
					if (copy($srcfile,$dstfile.$outArrGetModelProductIds[0]['texture'])) {
						//deleting previous image
						unlink($srcfile);
					}
				}
				 $arrData['texture']=basename($_FILES['texture']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['models']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['texture']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['texture']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			if(isset($_FILES['material']['name']) && $_FILES['material']['name']!='')
			{
				if(isset($outArrGetModelProductIds[0]['material']) && $outArrGetModelProductIds[0]['material']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['models']).$outArrGetModelProductIds[0]['material'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['models']);
					if ( !file_exists($dstfile) ) {
					  @mkdir ($dstfile, 0777, true);
					  chmod($dstfile, 0777);
					}
						chmod($srcfile, 0777);				
					//copy
					if (copy($srcfile,$dstfile.$outArrGetModelProductIds[0]['material'])) {
						//deleting previous image
						unlink($srcfile);
					}
				}
				 $arrData['material']=basename($_FILES['material']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['models']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['material']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['material']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			
			
			$tableName="model";
			$con=array();
			$con['id']=$model_id;
			$outArrUpdateClientModel = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			if ($outArrUpdateClientModel){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$tid.'/visuals/'.$visual_id.'/models/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxInsert3Dmodel(){
		try{
			global $config;
			$arrData=array();
			
			
			//$arrData['product']=isset($_REQUEST['rd_products']) ? $_REQUEST['rd_products'] : '';
			$arrData['three_d_model_id']=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			$tid=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			
			
			if(isset($_FILES['model']['name']) && $_FILES['model']['name']!='')
			{
				
				 $arrData['model']=basename($_FILES['model']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['models']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['model']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['model']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			if(isset($_FILES['texture']['name']) && $_FILES['texture']['name']!='')
			{
				
				 $arrData['texture']=basename($_FILES['texture']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['models']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['texture']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['texture']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			if(isset($_FILES['material']['name']) && $_FILES['material']['name']!='')
			{
				
				 $arrData['material']=basename($_FILES['material']['name']);
				 $dir=str_replace("{client_id}",$cid,$config['root_files']['models']);
				 
				 if ( !file_exists($dir) ) {
				  @mkdir ($dir, 0777, true);
				 }
				$target =  $dir.basename($_FILES['material']['name']) ;
				//chmod($target, 0777);
				if(move_uploaded_file($_FILES['material']['tmp_name'],$target)) ;//$chmod o+rw galleries
			}
			
			
			$tableName="model";
			$getSavedRecordId = $this->objClients->insertQuery($arrData, $tableName, true);
			if ($getSavedRecordId){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$tid.'/visuals/'.$visual_id.'/models/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxDelete3Dmodel(){
		try{
			global $config;
			$tid=isset($_REQUEST['tid']) ? $_REQUEST['tid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$visual_id=isset($_REQUEST['visual_id']) ? $_REQUEST['visual_id'] : '';
			$model_id=isset($_REQUEST['model_id']) ? $_REQUEST['model_id'] : '';
			
			/*$outArrGetModels = $this->objClients->getClientModelsByVid($model_id);
			
				if(isset($outArrGetModels[0]['model']) && $outArrGetModels[0]['model']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['models']).$outArrGetModels[$i]['model'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['models']);
					if(file_exists($srcfile)){
						if ( !file_exists($dstfile) ) {
						  @mkdir ($dstfile, 0777, true);
						  chmod($dstfile, 0777);
						}
							chmod($srcfile, 0777);				
						if (copy($srcfile,$dstfile.$outArrGetModels[$i]['model'])) {
							unlink($srcfile);
						}
					}
				}
				if(isset($outArrGetModels[0]['texture']) && $outArrGetModels[0]['texture']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['models']).$outArrGetModels[$i]['texture'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['models']);
					if(file_exists($srcfile)){
						if ( !file_exists($dstfile) ) {
						  @mkdir ($dstfile, 0777, true);
						  chmod($dstfile, 0777);
						}
							chmod($srcfile, 0777);				
						if (copy($srcfile,$dstfile.$outArrGetModels[$i]['texture'])) {
							unlink($srcfile);
						}
					}
				}
				if(isset($outArrGetModels[0]['material']) && $outArrGetModels[0]['material']!='')
				{
					$srcfile=str_replace("{client_id}",$cid,$config['root_files']['models']).$outArrGetModels[$i]['material'];
					$dstfile=str_replace("{client_id}",$cid,$config['trash_files']['models']);
					if(file_exists($srcfile)){
						if ( !file_exists($dstfile) ) {
						  @mkdir ($dstfile, 0777, true);
						  chmod($dstfile, 0777);
						}
							chmod($srcfile, 0777);				
						if (copy($srcfile,$dstfile.$outArrGetModels[$i]['material'])) {
							unlink($srcfile);
						}
					}
				}*/
			
			
			$tableName='model';
			$con=array();
			$con['id']=isset($_REQUEST['model_id']) ? $_REQUEST['model_id'] : '';
			
			$arrData=array();
			$arrData['active']=2;//1=deleted,0=enabled,2=trash
			$outArrUpdate3DmodelDeleteStatus = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/triggers/'.$tid.'/visuals/'.$visual_id.'/models/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientStores($cid){
		try{
			global $config;
			$outArrClientStores = array();
			$outArrClientStores = $this->objClients->getClientStoresByCId($cid);
			include SRV_ROOT.'views/clients/client_stores.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientStoresEditForm($cid,$storeId){
		try{
			global $config;
			$outArrClientStoreEditDetails = array();
			$outArrClientStoreEditDetails = $this->objClients->getClientStoresByStoreId($storeId);
			include SRV_ROOT.'views/clients/client_store_edit.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxUpdateClientStores(){
		try{
			global $config;
			$arrData=array();
			
			$arrData['store_name']=isset($_REQUEST['s_name']) ? $_REQUEST['s_name'] : '';
			$arrData['store_search_type']=isset($_REQUEST['s_search_type']) ? $_REQUEST['s_search_type'] : '';
			$arrData['latitude']=isset($_REQUEST['s_latitude']) ? $_REQUEST['s_latitude'] : '';
			$arrData['longitude']=isset($_REQUEST['s_longitude']) ? $_REQUEST['s_longitude'] : '';
			$arrData['address_1']=isset($_REQUEST['s_address1']) ? $_REQUEST['s_address1'] : '';
			$arrData['address_2']=isset($_REQUEST['s_address2']) ? $_REQUEST['s_address2'] : '';
			$arrData['phone']=isset($_REQUEST['s_phone']) ? $_REQUEST['s_phone'] : '';
			$arrData['city']=isset($_REQUEST['s_city']) ? $_REQUEST['s_city'] : '';
			$arrData['state']=isset($_REQUEST['s_state']) ? $_REQUEST['s_state'] : '';
			$arrData['zip']=isset($_REQUEST['s_zip']) ? $_REQUEST['s_zip'] : '';
			$arrData['email']=isset($_REQUEST['s_email']) ? $_REQUEST['s_email'] : '';
			$arrData['store_update_threshold']=isset($_REQUEST['s_update_threshold']) ? $_REQUEST['s_update_threshold'] : 0;
			$arrData['store_trigger_threshold']=isset($_REQUEST['s_trigger_threshold']) ? $_REQUEST['s_trigger_threshold'] : 0;
			
			$storeId=isset($_REQUEST['s_id']) ? $_REQUEST['s_id'] : '';
			$cid=isset($_REQUEST['c_id']) ? $_REQUEST['c_id'] : '';

			$tableName="client_stores";
			$con=array();
			$con['store_id']=$storeId;
			$outArrUpdateClientStore = $this->objClients->updateRecordQuery($arrData,$tableName,$con);
			if ($outArrUpdateClientStore){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/stores/'.$storeId.'/edit/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClientStoresAddForm($cid){
		try{
			global $config;
			$getClientDetails=$this->objClients->getClientById($cid);
			include SRV_ROOT.'views/clients/client_store_add_form.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveClientStores(){
		try{
			global $config;
			
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$arrData=array();
			$arrData['store_name']=isset($_REQUEST['s_name']) ? $_REQUEST['s_name'] : '';
			$arrData['store_search_type']=isset($_REQUEST['s_search_type']) ? $_REQUEST['s_search_type'] : '';
			$arrData['latitude']=isset($_REQUEST['s_latitude']) ? $_REQUEST['s_latitude'] : '';
			$arrData['longitude']=isset($_REQUEST['s_longitude']) ? $_REQUEST['s_longitude'] : '';
			$arrData['address_1']=isset($_REQUEST['s_address1']) ? $_REQUEST['s_address1'] : '';
			$arrData['address_2']=isset($_REQUEST['s_address2']) ? $_REQUEST['s_address2'] : '';
			$arrData['phone']=isset($_REQUEST['s_phone']) ? $_REQUEST['s_phone'] : '';
			$arrData['city']=isset($_REQUEST['s_city']) ? $_REQUEST['s_city'] : '';
			$arrData['state']=isset($_REQUEST['s_state']) ? $_REQUEST['s_state'] : '';
			$arrData['zip']=isset($_REQUEST['s_zip']) ? $_REQUEST['s_zip'] : '';
			$arrData['email']=isset($_REQUEST['s_email']) ? $_REQUEST['s_email'] : '';
			$arrData['store_update_threshold']=isset($_REQUEST['s_update_threshold']) ? $_REQUEST['s_update_threshold'] : 0;
			$arrData['store_trigger_threshold']=isset($_REQUEST['s_trigger_threshold']) ? $_REQUEST['s_trigger_threshold'] : 0;
			$arrData['client_id']=$cid;
			
			$tableName="client_stores";
			
			$outArrInsertClientStore = $this->objClients->insertQuery($arrData,$tableName,true);
			if ($outArrInsertClientStore){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/stores/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxDeleteClientStores(){
		try{
			global $config;
			$con = array();
			$pTableName = "client_stores";
			$con['store_id']=isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$storeId=isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
			
			$retDeleteStore = $this->objClients->deleteById($pTableName, $con);
			
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/stores/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function modAjaxUpdateStoreRelatedOffers(){
		try{
			global $config;
			$cid=isset($_REQUEST['cid']) ? $_REQUEST['cid'] : '';
			$storeId=isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : '';
			
			$uArray=array();
			$tableName="client_stores";

			$arrRelatedOffers=isset($_REQUEST['related_offers']) ? $_REQUEST['related_offers'] : '';//from check box filed
			$arrRelatedOffersIds=explode("," ,$arrRelatedOffers);
			
			$outArrRelatedOfferIdsByStoreId=array();
            $outArrRelatedOfferIdsByStoreId = $this->objClients->getStoreRelatedOfferId($storeId);
            
            $availableOffIds=isset($outArrRelatedOfferIdsByStoreId[0]['store_available_offerids']) ? $outArrRelatedOfferIdsByStoreId[0]['store_available_offerids'] : '';
            $arrAvailOffersIds=explode("," ,$availableOffIds);

          
            $uArray['store_available_offerids']=$arrRelatedOffers;
			$con=array();
			$con['store_id']=$storeId;
			$outArrUpdateClientStore = $this->objClients->updateRecordQuery($uArray,$tableName,$con);
			if ($outArrUpdateClientStore){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'clients/id/'.$cid.'/stores/';
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