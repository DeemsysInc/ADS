<?php 
class cModules{

	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objQuery;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/model.class.php');
			$this->objQuery = new Model();
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modAjaxSaveEnquiry(){
		try{
			global $config;
			$getSavedRecordId = array();
			$arrData=array();
			$arrData['firstname']=isset($_REQUEST['firstname']) ? $_REQUEST['firstname'] : '';
			$arrData['lastname']=isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : '';
			$arrData['company']=isset($_REQUEST['company']) ? $_REQUEST['company'] : '';
			$arrData['position']=isset($_REQUEST['position']) ? $_REQUEST['position'] : '';
			$arrData['email']=isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
			$arrData['phone']=isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
			$arrData['reason']=isset($_REQUEST['reason']) ? $_REQUEST['reason'] : '';
			$arrData['addinfo']=isset($_REQUEST['addinfo']) ? $_REQUEST['addinfo'] : '';
			$arrData['ip']=$_SERVER['REMOTE_ADDR'];;
			
			$arrData['browser']=$_SERVER['HTTP_USER_AGENT'];
			$pTableName="contact";
			$getSavedRecordId = $this->objQuery->insertQuery($arrData, $pTableName);
			//Checking whether user exists in database 
			if(count($getSavedRecordId) > 0)
			{
				//send mail to admin 
			        $to = $config['FROM_EMAIL'];
			        $to2= $config['FROM_EMAIL2'];
			        // $from = $config['FROM_EMAIL'];
			        $from = $arrData['email'];
				// subject
				$subject =  "New website contact request";			
				// message
				
				$fileName    =  SRV_ROOT."views/contact_email.tpl.php";				
				$file        = fopen($fileName, 'r');
				$emailContent = "";
				while (!feof($file)) {
				 $emailContent .= fgets($file, 4096);
				}
				fclose($file);
				//replace template data with values
				$emailContent = str_replace('%your_firstname%', $arrData['firstname'] ,$emailContent);
				$emailContent = str_replace('%your_lastname%', $arrData['lastname'] ,$emailContent);
				$emailContent = str_replace('%your_company%', $arrData['company'] ,$emailContent);
				$emailContent = str_replace('%your_position%', $arrData['position'] ,$emailContent);
				$emailContent = str_replace('%your_email%', $arrData['email'] ,$emailContent);
				$emailContent = str_replace('%your_phone%', $arrData['phone'] ,$emailContent);
				$emailContent = str_replace('%your_reason%', $arrData['reason'] ,$emailContent);
				$emailContent = str_replace('%your_addinfo%', $arrData['addinfo'] ,$emailContent);
				
				$message     =	$emailContent;
					
				//send mail to user
				$to_user = $arrData['email'];
			        // $from = $config['FROM_EMAIL'];
			        $from_user = $config['FROM_EMAIL'];
			        $donotreply="do-not-reply@seemoreinteractive.com";
				// subject
				$subject_user =  "SeeMore Interactive ";
				$message_user ="Thank you for your recent inquiry regarding SeeMore Interactive. Please let me know the best time to reach you an number to contact you at.";
				//$message_user = '<html><body>';
                               // $message_user .= '<p>Thank you for your recent inquiry regarding SeeMore Interactive. Please let me know the best time to reach you an number to contact you at.</p>';
                                //$message_user .= '<p>Thank you,<br />Seemore Interactive.<br /><a href="http://www.seemoreinteractive.com" target="_blank">http://www.seemoreinteractive.com</a></p>';
                                //$message_user .= '</body></html>'
				// Mail it
				 $this->sendMail($to_user,$donotreply,$subject_user,$message_user);
			        $ok = $this->sendMail($to,$from,$subject,$message);
			     $this->sendMail($to2,$from,$subject,$message);
				if($ok==1)
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				
			}
			else
			{
			    echo 0;
			}
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function sendMail($to,$from,$subject,$message)
	{
		try{
			// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						
				// Additional headers
				$headers .= 'To: '.$to . "\r\n";
				$headers .= 'From: '.$from . "\r\n";
				$headers .= 'Reply-To:'. $from . "\r\n";
				$headers .= 'Return-Path:' . $from . "\r\n";
				//$headers .= "X-Mailer: Drupal\n"; 
				// Mail it
			        $ok = mail($to, $subject, $message, $headers);
				if($ok)
				{
					return 1;
				}else
				{
					return 0;
				}
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