<?php 

class cPublic{



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

			require_once(SRV_ROOT.'model/public.model.class.php');

			$this->objPublic = new mPublic();

			

			/**** Create Model Class and Object ****/

			//require_once(SRV_ROOT.'classes/common.class.php');

			//$this->objCommon = new cCommon();

			require_once(SRV_ROOT.'classes/Array2XML.php');

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	public function modClientContactUsWithTheme($pUrl){

		try{
			global $config;
			
			$clientId = isset($pUrl[3])?$pUrl[3]:0;
			$action = isset($pUrl[4])?$pUrl[4]:'';
			//echo $config['LIVE_URL'];
			$arrClientDetails = array();
			$arrClientDetails = $this->objPublic->getClientDetails($clientId);	
			$first_name = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : 'Enter your first name.';
			$last_name = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : 'Enter your last name.';
			$date_of_birth = isset($_REQUEST['date_of_birth']) ? $_REQUEST['date_of_birth'] : 'Enter your birth date.';
			$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : 'Enter your email address.';
			$client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : '';
			if($first_name == 'Enter your first name.')
				 $fclassname = 'hint';
			else
				$fclassname ='boxes';
				
			if($last_name == 'Enter your last name.')
				$lclassname = 'hint';
			else
				$lclassname ='boxes';
			
			if($date_of_birth == 'Enter your birth date.')
				$bclassname = 'hint';
			else
				$bclassname ='boxes';
			if($email == 'Enter your email address.')
				$eclassname = 'hint';
			else
				$eclassname ='boxes';
				
				
			if($action !=''){	
			
					$arrUserProfile = array();
					$arrUserProfile['client_name'] = $client_name;
					$arrUserProfile['first_name'] = $first_name;
					$arrUserProfile['last_name']= $last_name;
					$arrUserProfile['email'] = $email;
					$arrUserProfile['date_of_birth'] = date('d/m/y',strtotime($date_of_birth));
					if($this->sendMailToClient($arrUserProfile)){
						$msg='success';
					}else{
						$msg='fail';
					}
					
					if($this->sendMailToUser($arrUserProfile)){
						$msg='success';
					}else{
						$msg='fail';
					}
					
			
					include SRV_ROOT.'views/thanks.tpl.php';
					
					
					
			}else{				
				include SRV_ROOT.'views/contact_us.tpl.php';
			}
			
		}catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}
	
	public function modClientCreditAppWithTheme($pUrl){

		try{
			global $config;
			
			$clientId = isset($pUrl[3])?$pUrl[3]:0;
			$action = isset($pUrl[4])?$pUrl[4]:'';
			//echo $config['LIVE_URL'];
			if(isset($pUrl[3])){
					$arrDetails= explode("?",$pUrl[3]);									
					$clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
					$arrUserId =  explode("=",$arrDetails[1]);	
					$userId = isset($arrUserId[1])?	$arrUserId[1] : 0;
					$arrClientDetails = array();
					$arrClientDetails = $this->objPublic->getClientDetails($clientId);	
					$arrUserDetails = $this->objPublic->getUserDetails($userId);	
					$first_name = isset($arrUserDetails[0]['user_firstname']) ? $arrUserDetails[0]['user_firstname'] : 'Enter your first name.';
					$last_name = isset($arrUserDetails[0]['user_lastname']) ? $arrUserDetails[0]['user_lastname'] : 'Enter your last name.';
					$date_of_birth = isset($arrUserDetails[0]['user_details_dob']) ? date('Y-m-d',strtotime($arrUserDetails[0]['user_details_dob'])) : '';
					$email = isset($arrUserDetails[0]['user_email_id']) ? $arrUserDetails[0]['user_email_id'] : 'Enter your email address.';
					$client_name = isset($arrClientDetails[0]['name']) ? $arrClientDetails[0]['name'] : '';
					$client_url  = isset($arrClientDetails[0]['url']) ? $arrClientDetails[0]['url'] : '';
					if(strpos($client_url, "http://") !== false){
						if(strpos($client_url, "www.") !== false){
							$result = str_replace('http://www.','',$client_url);
							$client_url = str_replace('/','',$result);
						}else{
							$result = str_replace('http://','',$client_url);
							$client_url = str_replace('/','',$result);
						}
					}else{
						$result = str_replace('www.','',$client_url);
						$client_url = str_replace('/','',$result);
					}
					/* if(strpos($client_url, "http://") !== false){
						$client_url = explode("http://", $client_url);	
						if(strpos($client_url[0], "www") !== false){
							$client_url = explode("www.", $client_url[0]);
							$client_url = explode("/", $client_url[1]);	
							}
					}else{						
						$client_url = explode("www.", $client_url);
						}	 */
									
						
					$client_email = "do-not-reply@".$client_url;
					
					
					if($first_name == 'Enter your first name.')
						 $fclassname = 'hint';
					else
						$fclassname ='boxes';
						
					if($last_name == 'Enter your last name.')
						$lclassname = 'hint';
					else
						$lclassname ='boxes';
					
					if($date_of_birth == '')
						$bclassname = 'hint';
					else
						$bclassname ='boxes';
					if($email == 'Enter your email address.')
						$eclassname = 'hint';
					else
						$eclassname ='boxes';
						
						
					if($action !=''){
							
							$first_name = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';
							$last_name = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';
							$date_of_birth = isset($_REQUEST['date_of_birth']) ? date('Y-m-d',strtotime($_REQUEST['date_of_birth'])) : '';
							$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
				
							$arrUserProfile = array();
							$arrUserProfile['client_name'] = $client_name;
							$arrUserProfile['client_email'] = $client_email;
							$arrUserProfile['first_name'] = $first_name;
							$arrUserProfile['last_name']= $last_name;
							$arrUserProfile['email'] = $email;
							$arrUserProfile['date_of_birth'] = date('Y-m-d',strtotime($date_of_birth));
							if($this->sendMailToClient($arrUserProfile)){
								$msg='success';
							}else{
								$msg='fail';
							}
							
							if($this->sendMailToUser($arrUserProfile)){
								$msg='success';
							}else{
								$msg='fail';
							}
							
					
							include SRV_ROOT.'views/thanks.tpl.php';
							
							
							
					}else{				
						include SRV_ROOT.'views/credit-app.tpl.php';
					}
			}
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
		

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
	
	
	public function sendMailToUser($arrUserProfile){
		global $config;
		
		
		/* 
		$mailto = $arrUserProfile['email'];
		$from_name = "SeeMore Interactive";
		$from_mail = $config['FROM_EMAIL'];
		$replyto = $config['FROM_EMAIL'];
		$subject = "Form Submission Confirmation";
		$message = "<p>Dear ".$arrUserProfile['first_name']." ".$arrUserProfile['last_name'].",</p>"."<p>This is to confirm that your form is submitted to ".$arrUserProfile['client_name']." . </p><p><u>Support:</u></p><p>For any support with respect to your relationship with us, please do feel free to contact us:</p><p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p><p>SeeMore Interactive</p>"; */
		
		$mailto = $arrUserProfile['email'];
		$from_name = $arrUserProfile['client_name'];
		$from_mail = $arrUserProfile['client_email'];
		$replyto =  $arrUserProfile['client_email'];
		$subject = "Form Submission Confirmation";
		$message = "<p>Dear ".$arrUserProfile['first_name']." ".$arrUserProfile['last_name'].",</p>"."<p>This is to confirm that your form was submitted to ".$arrUserProfile['client_name'].". </p>
		<p> Thank You - ".$arrUserProfile['client_name']." </p>";
		
		$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
		if ($mailStatus=="OK"){
			$arrResult['msg'] = 'success';
			return true;
		}else{
			$arrResult['msg'] = 'Error occured while sending an email. Please contact us';
			return false;
		}
	}


public function sendMailToClient($arrUserProfile){
		global $config;
		
		$mailto = 'vikram.nerasu@digitalimperia.com';
		$from_name = "SeeMore Interactive";
		$from_mail = $config['FROM_EMAIL'];
		$replyto = $config['FROM_EMAIL'];
		$subject = "Customer Request Form ";
		$message = "<p>Dear ".$arrUserProfile['client_name'].",</p>"."<p>This is to confirm that you have received a request from customer.</p><p>Customer details are listed below.</p>
		<p>First Name: ".$arrUserProfile['first_name']."</p><p>Last Name: ".$arrUserProfile['last_name']."</p><p>Email: ".$arrUserProfile['email']."</p><p>Date of Birth: ".$arrUserProfile['date_of_birth']."</p>
		<p><u>Support:</u></p><p>For any support with respect to your relationship with us, please do feel free to contact us:</p><p><a href='http://www.seemoreinteractive.com/contact' alt='Seemore Contact Us' />http://www.seemoreinteractive.com/contact</a></p><p>SeeMore Interactive</p>";
		$mailStatus = $this->mail_html($mailto, $from_mail, $from_name, $replyto, $subject, $message);
		if ($mailStatus=="OK"){
			$arrResult['msg'] = 'success';
			return true;
		}else{
			$arrResult['msg'] = 'Error occured while sending an email. Please contact us';
			return false;
		}
	}
	
	
	
	
	
	
	
	public function modClientFeedbackForm($pUrl){

		try{
			global $config,$clientId;
			//$clientId = isset($pUrl[3])?$pUrl[3]:0;
			if(isset($pUrl[3])){
				$arrDetails= explode("?",$pUrl[3]);									
				$clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
				$arrUserId =  explode("=",$arrDetails[1]);	
				$userId = isset($arrUserId[1])?	$arrUserId[1] : 0;		
				if($userId ==0 ){
					$email = isset($_REQUEST['email'])?	$_REQUEST['email'] : "";					
					if($email !=""){
						$getUserDetails = $this->objPublic->getUserByEmail($email);
						if(count($getUserDetails)>0){
							$userId = $getUserDetails[0]['user_id'];
						}
					}
				}else{
					$getUserDetails = $this->objPublic->getUserDetails($userId);
						if(count($getUserDetails)>0){
							$userId = $getUserDetails[0]['user_id'];
						}
				}
					
				$arrClientDetails = array();
				$arrClientDetails = $this->objPublic->getClientDetails($clientId);	
				
				
				$action = isset($pUrl[5])?$pUrl[5]:'';			
				if($action !=''){			
					$userID = isset($pUrl[4])?	$pUrl[4] : 0;				
					$dataArray = array();
					$insertArray = array();
					for($i=1;$i<20;$i++){
					$data = $_REQUEST['question_'.$i]."\n";				
					///$dataArray['Q'.$i]['name'] = $_REQUEST['question_'.$i];
					$dataArray['Q'.$i]['name'] = $i;									
					$dataArray['Q'.$i]['answer'] = isset($_REQUEST['answer_'.$i])? $_REQUEST['answer_'.$i]: "";
					
					}
						
					//$getUserDetails = $this->objForms->getUserDetails($userID);
					$insertArray['user_id'] = $userID;			
					$insertArray['user_name'] = $_REQUEST['name'];
					$insertArray['user_email'] = $_REQUEST['email'];
					$insertArray['age'] = $_REQUEST['age'];
					$insertArray['spouse_age'] = $_REQUEST['spouse_age'];
					$insertArray['address'] = $_REQUEST['address'];
					$insertArray['city'] = $_REQUEST['city'];
					$insertArray['state'] = $_REQUEST['state'];
					$insertArray['zip'] = $_REQUEST['zip_code'];
					$insertArray['phone'] = $_REQUEST['number'];				
					$insertArray['precoa_feedback'] = json_encode($dataArray);
					$insertArray['precoa_created_date'] = date("Y-m-d h:i:s");
				 
					$inserted= $this->objPublic->insertQuery($insertArray, "precoa_feedback", true);
					if( $inserted)
						include SRV_ROOT.'views/thanks.tpl.php';			
				}else{			
					include SRV_ROOT.'views/clients/id/'.$clientId.'/feedback_form.tpl.php';
				}
			/*if($action !=''){	
			 $File = SRV_ROOT."views/clients/id/".$clientId."/feedback_".date("Y-m-d h:i:s").".txt";		 
 			 $Handle = fopen($File, 'w');			 
			 if (file_exists($File)) {
				$dataArray = array();

			for($i=1;$i<20;$i++){
				$data = $_REQUEST['question_'.$i]."\n";
				$dataArray[$i]['question'] = $_REQUEST['question_'.$i];
				$dataArray[$i]['answer'] = $_REQUEST['answer_'.$i];
				
			 }
			 $dataArray['details']['name'] = $_REQUEST['name'];
			 $dataArray['details']['age'] = $_REQUEST['age'];
			 $dataArray['details']['spouse_age'] = $_REQUEST['spouse_age'];
			 $dataArray['details']['address'] = $_REQUEST['address'];
			 $dataArray['details']['city'] = $_REQUEST['city'];
			 $dataArray['details']['state'] = $_REQUEST['state'];
			 $dataArray['details']['zip_code'] = $_REQUEST['zip_code'];
			 $dataArray['details']['number'] = $_REQUEST['number'];
			 $dataArray['details']['email'] = $_REQUEST['email'];			 
			 
			 
			 fwrite($Handle, print_r($dataArray, TRUE)); 
			 fclose($Handle); 
			 }*/
			
			}
		
		}catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}
public function modDownloadLinks($pUrl){
	try{
		// print_r($pUrl);
		$platform = isset($pUrl['3']) ? $pUrl['3'] : 0;
		$appID = isset($pUrl['4']) ? $pUrl['4'] : 0;
		if ($platform == 'itunes') {
			// $itunesLink = 'https://itunes.apple.com/us/app/seemore-interactive/id591304180?mt=8&ign-mpt=uo%3D4';
			// echo 'download itunes link: '.$itunesLink;
			include SRV_ROOT.'views/downloads/download.tpl.php';
		}
	}catch ( Exception $e ) {
		echo 'Message: ' .$e->getMessage();
	}
}
    public function modClientSurveyForm($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[3]);
			//print_r($arrDetails);
            $clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
			include SRV_ROOT.'views/clients/id/'.$clientId.'/survey.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	public function modClientSurveyForm2($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[3]);
			//print_r($arrDetails);
            $clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
			include SRV_ROOT.'views/clients/id/'.$clientId.'/survey2.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	 public function modClientFindForm($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[3]);
			//print_r($arrDetails);
            $clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
			include SRV_ROOT.'views/clients/id/'.$clientId.'/find_doctor.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	
	public function modShowClientGameRules($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[3]);
			//print_r($arrDetails);
            $game_id = isset($arrDetails[0])?$arrDetails[0] : 1;
			
			include SRV_ROOT.'views/games/id/'.$game_id.'/game_rules.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	public function modClientSeminarsForm($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[3]);
			//print_r($arrDetails);
            $clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
			include SRV_ROOT.'views/clients/id/'.$clientId.'/seminars.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	public function modShowStatsPage($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[3]);
			//print_r($arrDetails);
            $clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
			include SRV_ROOT.'views/clients/id/'.$clientId.'/stats.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	public function modShowTriggerImage($pUrl){

		try{
			global $config;
			
			$arrDetails= explode("?",$pUrl[4]);
			//print_r($arrDetails);
            $triggerId = isset($arrDetails[0])?$arrDetails[0] : 0;
			$arrTriggerDetails=array();
			$arrTriggerDetails = $this->objPublic->getTriggerImageByTId($triggerId);
			$triggerImageUrl=isset($arrTriggerDetails[0]['url']) ? $arrTriggerDetails[0]['url'] : 0;
			$clientId=isset($arrTriggerDetails[0]['client_id']) ? $arrTriggerDetails[0]['client_id'] : 0;
			
			$finalImageUrl=$config['LIVE_URL']."files/clients/".$clientId."/triggers/".$triggerImageUrl;
			echo "<img src='".$finalImageUrl."'>";
			//include SRV_ROOT.'views/clients/id/'.$clientId.'/stats.tpl.php';
					
			}catch ( Exception $e ) {

				echo 'Message: ' .$e->getMessage();

		}
	}
	
	public function __destruct(){

		/*** Destroy and unset the object ***/

	}

	

} /*** end of class ***/

?>