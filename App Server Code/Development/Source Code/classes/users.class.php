<?php 
class cUsers{

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
			require_once(SRV_ROOT.'model/users.model.class.php');
			$this->objUsers = new mUsers();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/dashboard.model.class.php');
			$this->objDashboard = new mDashboard();
			
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'classes/common.class.php');
			$this->objCommon = new cCommon();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUsersDashboard(){
		try{
			global $config;

			include SRV_ROOT.'views/users/users_dashboard.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modManageCMSUsers(){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUsers->getAllCMSUsers();
			
			include SRV_ROOT.'views/users/cms_users.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modManageAppUsers(){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objDashboard->getAllAppUsers();
			include SRV_ROOT.'views/users/app_users.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modDeleteUsers($arrData){
		try{
			global $config;
			$outArray = array();
			$con = array();
			$pTableName = "seemore_users";
			foreach($arrData as $key=>$value){
				$con['u_id']=$value;
				$retDeleteStatus = $this->objUsers->deleteById($pTableName, $con);
			}
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = $config['LIVE_URL'].'users/';
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAddUsers(){
		try{
			global $config;
			$outArray = array();
			//$outArray = $this->objUsers->getAllUsers();
			include SRV_ROOT.'views/users/add_users.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAddAppUsers(){
		try{
			global $config;
			$outArray = array();
			//$outArray = $this->objUsers->getAllUsers();
			include SRV_ROOT.'views/users/app_users_add.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAddCmsUsers(){
		try{
			global $config;
			$outArrCmsUserGropus = array();
			$outArrCmsUserGropus = $this->objUsers->getAllCmsUserGroups();
			include SRV_ROOT.'views/users/cms_users_add.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function addCmsUser($arrData){
		try{
			global $config;
			
			
			$arrCmsUser = array();
			$arrCmsUser['u_group_id'] = isset($arrData['cms_u_group']) ? $arrData['cms_u_group'] : '';
			$arrCmsUser['u_first_name'] = isset($arrData['cms_u_fname']) ? $arrData['cms_u_fname'] : '';
			$arrCmsUser['u_last_name'] = isset($arrData['cms_u_lname']) ? $arrData['cms_u_lname'] : '';
			$arrCmsUser['u_uname'] = isset($arrData['cms_u_email']) ? $arrData['cms_u_email'] : '';
			$arrCmsUser['u_password'] = isset($arrData['cms_u_password']) ? md5($arrData['cms_u_password']) : '';
			$arrCmsUser['u_email'] = isset($arrData['cms_u_email']) ? $arrData['cms_u_email'] : '';
			$arrCmsUser['phone'] = isset($arrData['cms_u_phone']) ? $arrData['cms_u_phone'] : '';
			$arrCmsUser['created_date'] = "NOW()";
			
			$pTableName = "seemore_users";

			$getSavedRecordId = $this->objUsers->insertQuery($arrCmsUser, $pTableName, true);
			if ($getSavedRecordId){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'cmsusers/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modEditUsers($pUid){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUsers->getUsersProfile($pUid);
			include SRV_ROOT.'views/users/edit_users.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modEditCmsUserProfile($pUid){
		try{
			global $config;
			$outArrCmsUserGropus = array();
			$outArrCmsUserGropus = $this->objUsers->getAllCmsUserGroups();
			$outArray = array();
			$outArray = $this->objUsers->getUsersProfile($pUid);
			include SRV_ROOT.'views/users/cms_users_profile_edit.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modifyUser(){
		try{
			global $config;
			$arrUser['u_group_id'] = 3;
			$arrUser['u_first_name'] = isset($_REQUEST['fname']) ? $_REQUEST['fname'] : '';
			$arrUser['u_last_name'] = isset($_REQUEST['lname']) ? $_REQUEST['lname'] : '';
			$pass = isset($_REQUEST['passw']) ? md5($_REQUEST['passw']) : '';
			if (!empty($pass) || ($pass!="")){
				$arrUser['u_password'] = isset($_REQUEST['passw']) ? md5($_REQUEST['passw']) : '';
			}
			$arrUser['u_email'] = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
			$arrUser['phone'] = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
			$arrUser['updated_date'] = "NOW()";
			
			$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
			$pTableName = "seemore_users";
			$con['u_id'] = $uid;
			
			$getModifyStatus = $this->objUsers->updateRecordQuery($arrUser,$pTableName,$con);
			if ($getModifyStatus){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'users/';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAjaxEditSalesUsers(){
		try{
			global $config;
			$outArray = array();
			$pArray=array();
			$con=array();
			$tableName = "seemore_users";
			$fieldName = isset($_POST['id']) ? $_POST['id'] : '';
			
			if ($fieldName==""){
			}else{
				$fieldValues = isset($_POST['row_id']) ? $_POST['row_id'] : '';
				if ($fieldValues!=""){
					$fieldVal = explode("_",$fieldValues);
					if (isset($fieldVal[1]) && !empty($fieldVal[1])){
						$con['u_id']=$fieldVal[1];
						$pArray[$fieldName]=$_POST['value'];
						
						$this->objUsers->updateRecordQuery($pArray,$tableName,$con);
						echo $_POST['value'];
					}
				}
				
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAjaxSaveUserProfile($arrData){
		try{
			global $config;
			$arrUserProfile = array();
			$uid = isset($arrData['u_id']) ? $arrData['u_id'] : 0;
			$pEmail = isset($arrData['u_email']) ? $arrData['u_email'] : 0;
			$pTableName = "seemore_users";
			$arrUserProfile = $this->objUsers->getUsersProfile($uid);
			//print_r($arrUserProfile);
			$countUserProfile = count($arrUserProfile);
			if ($countUserProfile==1){
				$getProfileEmailStatus = $this->objUsers->getUsersProfileByEmail($pEmail);
				$con['u_id'] = $uid;
				$getModifyStatus = $this->objUsers->updateRecordQuery($arrData,$pTableName,$con);
				if ($getModifyStatus){
					$arrResult['msg'] = 'success';
					$arrResult['redirect'] = $config['LIVE_URL'].'users/profile/';
				}else{
					$arrResult['msg'] = 'fail';
				}
			}else{
				$getSavedRecordId = $this->objUsers->insertQuery($arrData, $pTableName, true);
				if ($getSavedRecordId){
					$arrResult['msg'] = 'success';
					$arrResult['redirect'] = $config['LIVE_URL'].'users/profile/';
				}else{
					$arrResult['msg'] = 'fail';
				}
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveUserPassword(){
		try{
			global $config;
			$arrUserProfile = array();
			$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
			$uPasswd = isset($_REQUEST['passwd']) ? $_REQUEST['passwd'] : '';
			$arrUserProfile['u_password'] = md5($uPasswd);
			$pTableName = "seemore_users";
			
			$con['u_id'] = $uid;
			$getModifyStatus = $this->objUsers->updateRecordQuery($arrUserProfile,$pTableName,$con);
			if ($getModifyStatus){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'users/profile/';
			}else{
				$arrResult['msg'] = 'fail';
			}

			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxForgotPassword(){
		try{
			global $config;
			$arrUserProfile = array();
			$arrUpdatePassw = array();
			$pEmail = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
			$pTableName = "seemore_users";
			$arrUserProfile = $this->objUsers->getUsersProfileByEmail($pEmail);
			//print_r($arrUserProfile);
			if (count($arrUserProfile)==1){
				$uid = isset($arrUserProfile[0]['u_id']) ? $arrUserProfile[0]['u_id'] : 0;
				$pass = $this->generatePassword(8,1);
				$passActivation = $this->generatePassword(6,1);
				$arrUpdatePassw['u_temp_password'] = $pass;
				$arrUpdatePassw['u_passwd_activation'] = $passActivation;
				$con['u_id'] = $uid;
				$getModifyStatus = $this->objUsers->updateRecordQuery($arrUpdatePassw,$pTableName,$con);
				if ($getModifyStatus){
					$mailto = $arrUserProfile[0]['u_email'];
					$from_name = "Vziom";
					$from_mail = $config['FROM_EMAIL'];
					$replyto = $config['FROM_EMAIL'];
					$subject = "Password Retrieval for your Vziom Client Login";
					$message = "<p>Dear ".$arrUserProfile[0]['u_first_name']." ".$arrUserProfile[0]['u_last_name'].",</p>"."<p>This is to confirm that we've received a Forgot Password request for your account ".$arrUserProfile[0]['u_uname'].". We've created a temporary password listed below which will work for the next 3 days. You can use this to login to your interface.</p><p>Click on below link to activate your new password.<br /><a href='http://client.vziom.com/activation/$passActivation/'>http://client.vziom.com/activation/$passActivation/</a></p><p>User Name: ".$arrUserProfile[0]['u_uname']."<br />Temporary Password: $pass</p><p>You must login to the VIZIOM Client Control Panel within these 3 days and change your primary password from <strong>Settings > Change Password </strong> Section.</p><p><u>Support:</u></p><p>For any support with respect to your relationship with us, please do feel free to contact us directly using the following information:</p><p>Email: <a href='mailto:response@vziom.com'>response@vziom.com</a> Phone: (614) 594-8001</p><p><u>Sales & Billing Related Support:</u></p><p>Email: <a href='mailto:sales@vziom.com'>sales@vziom.com</a> Phone: (614) 594-8001</p><p>Regards,<br />VZIOM - A Digital Marketing & Web Development venture by L&P Web Services, LLC</p>";
					$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
					if ($mailStatus=="OK"){
						$arrResult['msg'] = 'success';
					}else{
						$arrResult['msg'] = 'Error occured while sending an email. Please contact us';
					}
					//$arrResult['msg'] = 'success';
				}else{
					$arrResult['msg'] = 'Error occured while updating database. Please contact us';
				}

				
			}else{
				$arrResult['msg'] = 'There is no record';
			}
			
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserProfile(){
		try{
			global $config;
			$outArray = array();
			$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
			$outArray = $this->objUsers->getUsersProfile($uid);
			//print_r($outArray);
			include SRV_ROOT.'views/users/users_profile.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserProfileView($uid){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUsers->getUsersProfile($uid);
			//print_r($outArray);
			include SRV_ROOT.'views/users/users_profile.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAppUserProfileView($uid){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUsers->getAppUsersProfile($uid);
			include SRV_ROOT.'views/users/app_users_profile.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAppUserProfileEdit($auid){
		try{
			global $config;
			$outArray = array();
			$outArrayGroups=$this->objUsers->getAllGroups();
			$outArray = $this->objUsers->getAppUsersProfile($auid);
			include SRV_ROOT.'views/users/app_users_profile_edit.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveAppUserProfile($arrData){
		try{
			global $config;
			$arrAppUserProfile = array();
			$auid = isset($arrData['au_id']) ? $arrData['au_id'] : 0;
			$pEmail = isset($arrData['u_email']) ? $arrData['u_email'] : 0;
			$pTableName1 = "user_table";
			$pTableName2 = "group_user";
			$arrDataAppUserTable=array();
			$arrDataAppUserTable['first_name']=$arrData['au_first_name'];
			$arrDataAppUserTable['last_name']=$arrData['au_last_name'];
			$arrDataAppUserTable['password']=$arrData['au_password'];
			$arrDataAppUserGrp=array();
			$arrDataAppUserGrp['group_id']=$arrData['au_grp_id'];
			$conAppUserGrp=array();
			$conAppUserGrp['user_id']=$auid;
			$conAppUserTable=array();
			$conAppUserTable['id']=$auid;
			//$arrAppUserProfile = $this->objUsers->getAppUsersProfile($auid);
			
				
				$getModifyStatus = $this->objUsers->updateRecordQuery($arrDataAppUserTable,$pTableName1,$conAppUserTable);
				$getModifyStatusGrp = $this->objUsers->updateRecordQuery($arrDataAppUserGrp,$pTableName2,$conAppUserGrp);
				
				$arrResult=array();
				if ($getModifyStatus){
					$arrResult['msg'] = 'success';
					$arrResult['redirect'] = $config['LIVE_URL'].'appusers/profile/id/'.$auid.'/view';
				}else{
					$arrResult['msg'] = 'fail';
				}
			
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modCheckEmail(){
		try{
			global $config;
			$outArray = array();
			$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
			$pEmail = isset($_REQUEST['email']) ? $_REQUEST['email'] : 0;
			$outArray = $this->objUsers->checkEmailAvailable($uid, $pEmail);
			if (count($outArray) == 0){
				$arrResult['msg'] = 'valid';
			}else{
				$arrResult['msg'] = 'Email address already exists.';
			}
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserProfileEdit(){
		try{
			global $config;
			$outArray = array();
			$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
			$outArray = $this->objUsers->getUsersProfile($uid);
			//print_r($outArray);
			include SRV_ROOT.'views/users/users_profile_edit.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modUserProfilePassword(){
		try{
			global $config;
			$outArray = array();
			$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
			$outArray = $this->objUsers->getUsersProfile($uid);
			//print_r($outArray);
			include SRV_ROOT.'views/users/users_profile_password.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modPasswordActivation($pActivation){
		try{
			global $config;
			$arrTempPass = array();
			$arrTempPass = $this->objUsers->getUsersTempPasswd($pActivation);
			if (count($arrTempPass)==1){
				$pTableName = "seemore_users";
				$uid = isset($arrTempPass[0]['u_id']) ? $arrTempPass[0]['u_id'] : 0;
				$uTempPass = isset($arrTempPass[0]['u_temp_password']) ? $arrTempPass[0]['u_temp_password'] : 0;
				
				$arrUpdatePassw['u_password'] = md5($uTempPass);
				$arrUpdatePassw['u_passwd_activation'] = '';
				$arrUpdatePassw['u_temp_password'] = '';
				$con['u_id'] = $uid;
				$getModifyStatus = $this->objUsers->updateRecordQuery($arrUpdatePassw,$pTableName,$con);
				if ($getModifyStatus){
					echo '<ul class="message success grid_12"><li>You activation is success. Please login with new password.</li><li class="close-bt"></li></ul>';
				}else{
					echo '<ul class="message error grid_12"><li>Error Occurred: Please contact us.</li><li class="close-bt"></li></ul>';
				}
				
			}else{
				echo '<ul class="message error grid_12"><li>Your activation is not success. Please verify and activate again.</li><li class="close-bt"></li></ul>';
			}
			
			//print_r($outArray);
			//include SRV_ROOT.'views/users/users_profile_password.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
	public function mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message){

		//create a boundary string. It must be unique 
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "From: ".$from_name." <".$from_mail.">\r\n";
		$headers .= "Reply-To: ".$replyto."\r\n";
		//add boundary string and mime type specification
		$headers .= "Content-type:text/html; charset=iso-8859-1\r\n";


		//send the email
		$mail_sent = @mail( $mailto, $subject, $message, $headers );

		if ($mail_sent){
		 return "OK"; // or use booleans here
		} else {
			return "ERROR";
		}

	}
	public function modAppUserWishlist($auid){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUsers->getAppUsersWishlist($auid);
			//print_r($outArray);
			include SRV_ROOT.'views/users/app_users_wishlist.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modAjaxUpdateCmsUserProfile($arrData){
		try{
			global $config;
			
			$arrCmsUser=array();
			$arrCmsUser['u_group_id'] = isset($arrData['cms_u_group']) ? $arrData['cms_u_group'] : '';
			$arrCmsUser['u_first_name'] = isset($arrData['cms_u_fname']) ? $arrData['cms_u_fname'] : '';
			$arrCmsUser['u_last_name'] = isset($arrData['cms_u_lname']) ? $arrData['cms_u_lname'] : '';
			$pass = isset($arrData['cms_u_password']) ? md5($arrData['cms_u_password']) : '';
			if (!empty($pass) || ($pass!="")){
				$arrUser['u_password'] = isset($arrData['cms_u_password']) ? md5($arrData['cms_u_password']) : '';
			}
			$arrCmsUser['u_email'] = isset($arrData['cms_u_email']) ? $arrData['cms_u_email'] : '';
			$arrCmsUser['phone'] = isset($arrData['cms_u_phone']) ? $arrData['cms_u_phone'] : '';
			$arrCmsUser['u_address_1'] = isset($arrData['cms_u_address_1']) ? $arrData['cms_u_address_1'] : '';
			$arrCmsUser['u_address_2'] = isset($arrData['cms_u_address_2']) ? $arrData['cms_u_address_2'] : '';
			$arrCmsUser['u_city'] = isset($arrData['cms_u_city']) ? $arrData['cms_u_city'] : '';
			$arrCmsUser['u_state'] = isset($arrData['cms_u_state']) ? $arrData['cms_u_state'] : '';
			$arrCmsUser['u_country'] = isset($arrData['cms_u_country']) ? $arrData['cms_u_country'] : '';
			$arrCmsUser['u_zip'] = isset($arrData['cms_u_zip']) ? $arrData['cms_u_zip'] : '';
			
			$arrCmsUser['updated_date'] = "NOW()";
			$pTableName = "seemore_users";
			$con['u_id'] =  isset($arrData['cms_u_id']) ? $arrData['cms_u_id'] : '';
			$getModifyStatus = $this->objUsers->updateRecordQuery($arrCmsUser,$pTableName,$con);
			if ($getModifyStatus){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'cmsusers/profile/id/'.$con['u_id'].'/edit';
			}else{
				$arrResult['msg'] = 'fail';
			}
			echo json_encode($arrResult);
			
			
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modCmsUserProfileView($uid){
		try{
			global $config;
			$outArray = array();
			$outArray = $this->objUsers->getUsersProfile($uid);
			//print_r($outArray);
			include SRV_ROOT.'views/users/cms_users_profile.tpl.php';
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modDeleteCmsUser(){
		try{
			global $config;
			$outArray = array();
			$con = array();
			$pTableName = "seemore_users";
			$con['u_id']=isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
			$retDeleteStatus = $this->objUsers->deleteById($pTableName, $con);
			if($retDeleteStatus){
				$arrResult['msg'] = 'success';
				$arrResult['redirect'] = $config['LIVE_URL'].'cmsusers/';
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