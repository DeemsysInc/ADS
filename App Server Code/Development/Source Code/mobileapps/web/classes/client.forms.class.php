<?php 

class cForms{


	/*** the constructor ***/

	public function __construct(){

		try{

			/**** Create Model Class and Object ****/

			require_once(SRV_ROOT.'model/client.forms.model.class.php');

			$this->objForms = new mForms();

			

			/**** Create Model Class and Object ****/

			//require_once(SRV_ROOT.'classes/common.class.php');

			//$this->objCommon = new cCommon();

			require_once(SRV_ROOT.'classes/Array2XML.php');

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}
	
	public function modClientFeedback($pUrl){

		try{
			global $config;
			
			
			if(isset($pUrl[3])){
				$arrDetails= explode("?",$pUrl[3]);									
				$clientId = isset($arrDetails[0])?$arrDetails[0] : 0;
				$arrUserId =  explode("=",$arrDetails[1]);	
				$userId = isset($arrUserId[1])?	$arrUserId[1] : 0;
				if($userId ==0 ){
					$email = isset($_REQUEST['email'])?	$_REQUEST['email'] : "";					
					if($email !=""){
						$getUserDetails = $this->objForms->getUserDetailsByEmail($email);
						if(count($getUserDetails)>0){
							$userId = $getUserDetails[0]['user_id'];
						}
					}
				}
				//echo $userId;
				$id = $clientId;
				$action = isset($pUrl[5])?$pUrl[5]:'';			
				if($action !=''){		
					$userID = isset($pUrl[4])?	$pUrl[4] : 0;				
					$dataArray = array();
					$insertArray = array();
					for($i=1;$i<=6;$i++){
					$data = $_REQUEST['question_'.$i]."\n";				
					$dataArray['Q'.$i]['name'] = $_REQUEST['question_'.$i];
					if($_REQUEST['type_'.$i] == 'checkbox'){
						if(isset($_REQUEST['answer_'.$i]))
						{
							$check_list = array();
							foreach($_REQUEST['answer_'.$i] as $val)
							{
								$check_list[] =  $val;
							}
							$dataArray['Q'.$i]['answer'] =	$check_list;
							
						}
					}else{					
						$dataArray['Q'.$i]['answer'] = isset($_REQUEST['answer_'.$i])? $_REQUEST['answer_'.$i]: "";
					}
					}
					
					//$getUserDetails = $this->objForms->getUserDetails($userID);
					$insertArray['user_id'] = $userID;			
					$insertArray['ftf_feedback'] = json_encode($dataArray);
					$insertArray['ftf_created_date'] = date("Y-m-d h:i:s");
				 
					$inserted= $this->objForms->insertQuery($insertArray, "ftf_feedback", true);
					if( $inserted)
						include SRV_ROOT.'views/thanks.tpl.php';
				}else{				
					include SRV_ROOT.'views/clients/id/'.$clientId.'/feedback_form.tpl.php';
				}
			}
		}catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}
	
	public function  modClientMenu($pUrl){
		$clientId = isset($pUrl[3]) ? $pUrl[3] : 0;	
		include SRV_ROOT.'views/clients/id/'.$clientId.'/menu_trucks.tpl.php';
	}
} /*** end of class ***/

?>