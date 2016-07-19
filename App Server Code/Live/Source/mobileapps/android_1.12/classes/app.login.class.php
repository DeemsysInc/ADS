<?php 
class cAppLogin{
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
			require_once(SRV_ROOT.'model/app.login.model.class.php');
			$this->objLoginQuery = new lAppModel();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getAccessWithAppLoginDetails($pArray){
		try{
			global $config;
			$outResults = array();
			$vArray = array();
			//print_r($pArray);
			$vArray['checkUrlFlag'] = isset($pArray[2]) ? $pArray[2] : 'login';
			$vArray['uname'] = isset($_REQUEST['username']) ? $_REQUEST['username'] : (isset($pArray[3]) ? $pArray[3] : '');
			$vArray['pwd'] = isset($_REQUEST['password']) ? $_REQUEST['password'] : (isset($pArray[4]) ? $pArray[4] : '');
			$vArray['regThrough'] = isset($_REQUEST['register_through']) ? $_REQUEST['register_through'] : (isset($pArray[5]) ? $pArray[5] : '0');
			$vArray['email_id'] = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : (isset($pArray[6]) ? $pArray[6] : '');
			$vArray['user_details_fbid'] = isset($_REQUEST['user_details_fbid']) ? $_REQUEST['user_details_fbid'] : '';
			
			$outResults['resultXml'] = $this->login_app_user($vArray);
			//echo json_encode($outResults);
			if(count($outResults)>0){
				$xml = Array2XML::createXML('rootLogin', $outResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function login_app_user($vArray){
        global $config;
		$arrResult = array();
        //$password = md5($vArray['pwd']);
		if($vArray['checkUrlFlag']=="login_profiling"){
			$checkLogin = $this->objLoginQuery->checkApploginForProfiling($vArray);
		} else {
			$checkLogin = $this->objLoginQuery->checkApplogin($vArray);
		}
		$arrResult['id'] = 0;
		$arrResult['username'] = "";
		$arrResult['email_id'] = "";
		//echo "count".count($checkLogin);
		if(count($checkLogin) >0){
			$arrResult['msg'] = 'success';
			$arrResult['id'] = isset($checkLogin[0]['user_id']) ? $checkLogin[0]['user_id'] : 0;
			$arrResult['username'] = isset($checkLogin[0]['user_username']) ? $checkLogin[0]['user_username'] : "";
			$arrResult['email_id'] = isset($checkLogin[0]['user_email_id']) ? $checkLogin[0]['user_email_id'] : "";
			$arrResult['user_firstname'] = isset($checkLogin[0]['user_firstname']) ? $checkLogin[0]['user_firstname'] : "";
			$arrResult['user_lastname'] = isset($checkLogin[0]['user_lastname']) ? $checkLogin[0]['user_lastname'] : "";
			$arrResult['user_group_id'] = isset($checkLogin[0]['user_group_id']) ? $checkLogin[0]['user_group_id'] : 0;
			$con = array();
			$udArray = array();	
			$con['user_id'] = $arrResult['id'];	
			$udArray['user_details_lastlogin'] = 'NOW()';
			
			if($vArray['regThrough']!=0 && $vArray['uname'] == ""){	
				if($arrResult['username'] != $vArray['email_id']){
					$upArray = array();			
					$upArray['user_fb_username'] = $arrResult['username'];					
					$upArray['user_username'] = $vArray['email_id'];
					$udArray['user_details_fbid'] = isset($vArray['user_details_fbid']) ? $vArray['user_details_fbid'] : '';
					$updateRecord = $this->objLoginQuery->updateRecordQuery($upArray, "users", $con);
					
				}
			}
			$getUserDetails = $this->objLoginQuery->getUserDetailsById($arrResult['id']);
			$arrResult = array_merge($arrResult,$getUserDetails[0]);
			$updateRecord = $this->objLoginQuery->updateRecordQuery($udArray, "user_details", $con);
		}else{
			$arrTempUser = $this->objLoginQuery->validateUserLoginWithTempPwd($vArray['email_id'], $vArray['pwd']);
			if (count($arrTempUser) > 0){						
				//Remove temp password from DB
				$uid = isset($arrTempUser[0]['user_id']) ? $arrTempUser[0]['user_id'] : 0;
				if($uid!=0){
					$con = array();
					$con['user_id'] = $uid;	
					$con['user_status'] = 1;	
					if($con['user_id'] !=0){
						$wdUserArray['user_temp_password'] = "";
						$wdUserArray['user_password'] = md5($vArray['pwd']);
						$updateRecord = $this->objLoginQuery->updateRecordQuery($wdUserArray, "users", $con);
						if($updateRecord){
							$arrResult['msg'] = 'success';
							$arrResult['id'] = isset($checkLogin[0]['user_id']) ? $checkLogin[0]['user_id'] : 0;
							$arrResult['username'] = isset($checkLogin[0]['user_username']) ? $checkLogin[0]['user_username'] : "";
							$arrResult['email_id'] = isset($checkLogin[0]['user_email_id']) ? $checkLogin[0]['user_email_id'] : "";
							$arrResult['user_firstname'] = isset($checkLogin[0]['user_firstname']) ? $checkLogin[0]['user_firstname'] : "";
							$arrResult['user_lastname'] = isset($checkLogin[0]['user_lastname']) ? $checkLogin[0]['user_lastname'] : "";
							$arrResult['user_group_id'] = isset($checkLogin[0]['user_group_id']) ? $checkLogin[0]['user_group_id'] : 0;
							$con = array();
							$udArray = array();	
							$con['user_id'] = $arrResult['id'];	
							$udArray['user_details_lastlogin'] = 'NOW()';
							$getUserDetails = $this->objLoginQuery->getUserDetailsById($arrResult['id']);
							$arrResult = array_merge($arrResult,$getUserDetails[0]);
							$updateRecord = $this->objLoginQuery->updateRecordQuery($udArray, "user_details", $con);
						}else{
							$arrResult['msg']='fail';
						}
					}
				}else{
					$arrResult['msg']='fail';
				}
			}else{
				$arrResult['msg']='fail';
			}
		}
		//print_r($arrResult);
		return $arrResult; 
    }
	
    public function logout(){
		try{
			global $config;
			$arrResult = array();
			session_destroy();
			$arrResult['msg'] = 'success';
			$arrResult['redirect'] = "";
			echo json_encode($arrResult);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
	
    public function redirectToURL($url)
    {
        header("Location: ".$url);
       	exit;
    }
    
	public function getInsertRegistrationDetails($pArray){
		try{
			global $config;
			$outResults = array();
			//print_r($pArray);
			$dArray = array();
			$pArray = array();
			$pArray['user_group_id'] = 3;
			$pArray['user_firstname'] = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';
			$pArray['user_lastname'] = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';
			$pArray['user_username'] = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
			$user_password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
			$pArray['user_email_id'] = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';
			$pArray['user_status'] = 1;
			$pArray['user_created_date'] ='NOW()';
			$pArray['user_register_through'] = isset($_REQUEST['register_through']) ? $_REQUEST['register_through'] : 0;//0-App, 1-FB, 2-Twitter
			if($pArray['user_register_through'] ==0){
				$pArray['user_password'] = md5($user_password);			
			}else{
				$pArray['user_password'] ="";
			}
			
			$pTableName = "users";
			$checkUserReg = $this->objLoginQuery->checkAppUserRegistered($pArray);
			if(count($checkUserReg)==0 && $pArray['user_username']!=""){
				$insertedUsersResultId = $this->objLoginQuery->insertQuery($pArray, $pTableName, true);
				
				$pUserDetailsTableName = "user_details";
				$dArray['user_id'] = $insertedUsersResultId;
				$dArray['user_details_avatar'] = isset($_REQUEST['user_details_avatar']) ? $_REQUEST['user_details_avatar'] : '';
				$dArray['user_details_nickname'] = isset($_REQUEST['user_details_nickname']) ? $_REQUEST['user_details_nickname'] : $pArray['user_firstname'];
				$dArray['user_details_phone'] = isset($_REQUEST['user_details_phone']) ? $_REQUEST['user_details_phone'] : '';
				$dArray['user_details_address1'] = isset($_REQUEST['user_details_address1']) ? $_REQUEST['user_details_address1'] : '';
				$dArray['user_details_address2'] = isset($_REQUEST['user_details_address2']) ? $_REQUEST['user_details_address2'] : '';
				$dArray['user_details_city'] = isset($_REQUEST['user_details_city']) ? $_REQUEST['user_details_city'] : '';
				$dArray['user_details_state'] = isset($_REQUEST['user_details_state']) ? $_REQUEST['user_details_state'] : '';
				$dArray['user_details_country'] = isset($_REQUEST['user_details_country']) ? $_REQUEST['user_details_country'] : '';
				$dArray['user_details_zip'] = isset($_REQUEST['user_details_zip']) ? $_REQUEST['user_details_zip'] : '';
				$dArray['user_details_fbid'] = isset($_REQUEST['user_details_fbid']) ? $_REQUEST['user_details_fbid'] : '';
				$dArray['user_reg_device'] = 'ANDROID';
			
				$dArray['user_details_gender'] = isset($_REQUEST['user_details_gender']) ? $_REQUEST['user_details_gender'] : '';
				//$dArray['user_details_dob'] = isset($_REQUEST['user_details_dob']) ?  date("Y-m-d", strtotime($_REQUEST['user_details_dob'])) : '';
				$dArray['user_details_lastlogin'] = 'NOW()';
				
				$insertedUsersDetailsResultId = $this->objLoginQuery->insertQuery($dArray, $pUserDetailsTableName, true);
				if ($insertedUsersDetailsResultId && $pArray['user_username']!=""){
					
					$pUserTrackingTableName = "user_tracking";
			
					$utArray = array();
					$utArray['user_id'] = $insertedUsersResultId;
					$utArray['user_group_id'] = 0;
					$utArray['user_details_id'] = $insertedUsersDetailsResultId;
					$utArray['user_tracking_session_id'] = session_id();
					$utArray['user_tracking_created_date'] = 'NOW()';
					$utArray['user_tracking_created_by_id'] = $insertedUsersResultId;
					$utArray['user_tracking_created_ip_address'] = $_SERVER['REMOTE_ADDR'];
					$utArray['user_tracking_updated_date'] = 'NOW()';
					$utArray['user_tracking_updated_by_id'] = $insertedUsersResultId;
					$utArray['user_tracking_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
					$insertUserTracking = $this->objLoginQuery->insertQuery($utArray, $pUserTrackingTableName, false);
					if($pArray['user_register_through'] ==0){
					//define the receiver of the email
					$to = $pArray['user_email_id'];
					//define the subject of the email
					$subject = 'Thank you for registering.'; 
					$headers = "From: " . $config['FROM_EMAIL'] . "\r\n";
					//$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					
					$message = "Dear ".$pArray['user_firstname']." ".$pArray['user_lastname'].", ";
					$message .= "<p>Thank you for registering SeeMore App. </p>";
					$message .= "<p>Below is your login username. 
						<br>Username: ".$pArray['user_username'];
					$message .= "<p>Need Support? Contact us:</p>";
					$message .= "<p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p>";
					
					$message .= "<p>SeeMore Interactive.</p>";
				
					//send the email
					$mail_sent = @mail( $to, $subject, $message, $headers );
					//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
					$mail_sent ? "Mail sent" : "Mail failed";
					}
				
					if($insertUserTracking){
						$outResults['resultXml']['msg'] = 'success';
					} else {
						$outResults['resultXml']['msg'] = 'fail';
					}
				}else{
					$outResults['resultXml']['msg'] = 'fail';
				}
			} else {
				if($checkUserReg[0]['user_status'] == 0){
					$upArray = array();
					$con = array();					
					$con['user_id'] = $checkUserReg[0]['user_id'];					
					$upArray['user_status'] = 1;
					$updateRecord = $this->objLoginQuery->updateRecordQuery($upArray, "users", $con);
				}
				$outResults['resultXml']['msg'] = 'already';
			}
			//echo json_encode($outResults);
			if(count($outResults)>0){
				$xml = Array2XML::createXML('rootReg', $outResults);
				echo $xml->saveXML();
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
 public function getStateListValues(){
		try{
			global $config;
			$outResults = array();
			$vArray = array();
			//print_r($pArray);
			$outResults['resultXml'] = $this->objLoginQuery->getStateList();
			//echo json_encode($outResults);
			
			$xml = Array2XML::createXML('rootState', $outResults);
			echo $xml->saveXML();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	public function modForgotPassword($pArray){
		try{
			global $config;	
			$arrUser = array();
			$outUserForgotPassword = array();
			//$uname = isset($pUserInfo['uname']) ? $pUserInfo['uname'] : '';
			$uname = isset($_REQUEST['username']) ? $_REQUEST['username'] : (isset($pArray[3]) ? $pArray[3] : '');
			if ($uname!=""){
				$arrUser = $this->objLoginQuery->getUserDetailsByUname($uname);
				if (count($arrUser)>0){
					if ($arrUser[0]['user_status']==1){
						$userTempPassword = $this->generatePassword(8,3);
						$con = array();
						$wdArray = array();	
						$con['user_id'] = $arrUser[0]['user_id'];	
						if($con['user_id'] !=0){
							$wdArray['user_temp_password'] = $userTempPassword;
							$arrUser[0]['new_password'] = $userTempPassword;
							$updateRecord = $this->objLoginQuery->updateRecordQuery($wdArray, "users", $con);
							if ($updateRecord){
								if($this->sendForgotPassword($arrUser)){
									$outUserForgotPassword['resultXml']['msg']='success';
									$outUserForgotPassword['success']=true;
								}else{
									//$outUserForgotPassword['msg']='We could not send email now. Please try again later.';
									$outUserForgotPassword['resultXml']['msg']='fail';
								}
								
							}else{
								//$outUserForgotPassword['msg']='There was an error in the system. Please try again later.';								
								$outUserForgotPassword['resultXml']['msg']='fail';
							}
						}else{
							//$outUserForgotPassword['msg']="User doesn't exists. Please register.";
							$outUserForgotPassword['resultXml']['msg']='doesnot exists';
						}
					}else{
						//$outUserForgotPassword['msg']='User is not active. Please contact us.';
						$outUserForgotPassword['resultXml']['msg']='inactive';
					}
				}else{
					$outUserForgotPassword['msg']="fail.";
				}
			}else{
				$outUserForgotPassword['msg']='Invalid username';
			}
			//echo json_encode($outUserForgotPassword);
			
			if(count($outUserForgotPassword)>0){
				$xml = Array2XML::createXML('rootReg', $outUserForgotPassword);
				echo $xml->saveXML();
			}
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
	
	public function sendForgotPassword($arrUserProfile){
		global $config;
		
		$mailto = $arrUserProfile[0]['user_email_id'];
		$from_name = "SeeMore Interactive";
		$from_mail = $config['FROM_EMAIL'];
		$replyto = $config['FROM_EMAIL'];
		$pass = $arrUserProfile[0]['new_password'];
		$subject = "Password Retrieval for your SeeMore Interactive Login";
		$message = "<p>Dear ".$arrUserProfile[0]['user_firstname']." ".$arrUserProfile[0]['user_lastname'].",</p>"."<p>This is to confirm that we've received a Forgot Password request for your account <strong>'".$arrUserProfile[0]['user_username']."'</strong>. We've created a new password listed below. You can use this to login to your SeeMore Interactive App.</p><p>User Name: ".$arrUserProfile[0]['user_username']."<br />New Password: $pass</p><p><u>Support:</u></p><p>For any support with respect to your relationship with us, please do feel free to contact us:</p><p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p><p>SeeMore Interactive</p>";
		$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
		if ($mailStatus=="OK"){
			$arrResult['msg'] = 'success';
			return true;
		}else{
			$arrResult['msg'] = 'Error occured while sending an email. Please contact us';
			return false;
		}
	}
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>