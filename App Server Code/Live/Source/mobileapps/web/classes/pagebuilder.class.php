<?php 

/**** Include interfaces link ****/

//require_once(SRV_ROOT.'interfaces/interfaces.inc.php');



class cPageBuilder{



	/*** define public & private properties ***/

	 public $objLogin;

	 public $objConfig;

	 public $getConfig;

	 public $objCommon;

	 

	/*** the constructor ***/

	public function __construct(){

		

		require_once SRV_ROOT.'classes/public.class.php';

		$this->objPublic = new cPublic();
		
		require_once SRV_ROOT.'classes/client.forms.class.php';

		$this->objForms = new cForms();
		

		require_once SRV_ROOT.'classes/config.class.php';

		$this->objConfig = new cConfig();

		$this->getConfig = $this->objConfig->config();

		

	}



	/**** function to get all functions of Header and assign to header template ****/

	public function pageHeader(){

		try{

			global $config;

			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){

				$outUserInfo = array();

				$outUserInfo['fname'] =  isset($_SESSION['fname']) ? $_SESSION['fname'] : "";

				$outUserInfo['lname'] =  isset($_SESSION['lname']) ? $_SESSION['lname'] : "";

				$outUserInfo['user_group'] =  isset($_SESSION['user_group']) ? $_SESSION['user_group'] : "";

				$getDateFromDB = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : "";

				$date = new DateTime($getDateFromDB);

				$outUserInfo['last_login'] = $date->format('m-d-Y H:i:s');

				/**** assign variables/array to header.tpl.php ****/

			}

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	/**** function to get all functions of Content and assign to content template ****/

	public function pageContent($pUrl){

		try{

			global $config;

			$pAction = isset($pUrl[0]) ? $pUrl[0] : '';
			
				if (isset($pUrl[1]) && ($pUrl[1]=='web')){
									
					if(isset($pUrl[2]) && ($pUrl[2]=='credit_app')){
						$this->objPublic->modClientCreditAppWithTheme($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='thanks')){
						include SRV_ROOT.'views/thanks.tpl.php';
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='contact_us')){
						$this->objPublic->modClientContactUsWithTheme($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='feedback')){
						$this->objPublic->modClientFeedbackForm($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='feedback_client_form')){
						$this->objForms->modClientFeedback($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='menu')){
						$this->objForms->modClientMenu($pUrl);						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='downloads')){
						$this->objPublic->modDownloadLinks($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='survey')){
						$this->objPublic->modClientSurveyForm($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='survey2')){
						$this->objPublic->modClientSurveyForm2($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='find')){
						$this->objPublic->modClientFindForm($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='game_rules')){
						$this->objPublic->modShowClientGameRules($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='seminars')){
						$this->objPublic->modClientSeminarsForm($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='stats')){
						$this->objPublic->modShowStatsPage($pUrl);
						
					}
					if(isset($pUrl[2]) && ($pUrl[2]=='triggers')){
						if(isset($pUrl[3]) && ($pUrl[3]=='id')){
							$this->objPublic->modShowTriggerImage($pUrl);
						}
						
					}


				}	
		}catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}



	/**** function to get Page Footer ****/

	public function pageFooter(){

		try{

			global $config;

			if(isset($_SESSION['uname']) && !empty($_SESSION['uname'])){

				//include SRV_ROOT.'views/home/footer.tpl.php';

			}

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	/**** function to get Page Left Modules ****/

	public function pageLeft(){

		try{



		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	/**** function to get Page right modules ****/

	public function pageRight($pAction){

		try{

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