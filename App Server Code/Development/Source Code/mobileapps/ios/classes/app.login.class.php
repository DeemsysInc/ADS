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

			//print_r($pArray);

			$uname = isset($_REQUEST['username']) ? $_REQUEST['username'] : ""; //isset($pArray[4]) ? $pArray[4] : '';

			$pwd = isset($_REQUEST['password']) ? $_REQUEST['password'] : ""; //isset($pArray[5]) ? $pArray[5] : '';

			$outResults = $this->login_app_user($uname, $pwd);

			echo json_encode($outResults);

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}



	public function login_app_user($username, $password){

        global $config;

		$arrResult = array();

        //$password = md5($password);

		$checkLogin = $this->objLoginQuery->checkApplogin($username,$password);

		if(!$checkLogin){

			$arrResult['msg'] = 'fail';

		}else{

			$arrResult['msg'] = 'success';

		}

		$arrResult['id'] = isset($checkLogin[0]['id']) ? $checkLogin[0]['id'] : 0;

		$arrResult['username'] = isset($checkLogin[0]['username']) ? $checkLogin[0]['username'] : "";

		$arrResult['email_id'] = isset($checkLogin[0]['email_id']) ? $checkLogin[0]['email_id'] : "";

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

			$pArray = array();

			$pArray['first_name'] = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';

			$pArray['last_name'] = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';

			$pArray['username'] = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';

			$pArray['password'] = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

			$pArray['email_id'] = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';

			

			$pTableName = "user_table";

			if ($pArray['username']!="" && $pArray['password']!=""){

				$insertResults = $this->objLoginQuery->insertQuery($pArray, $pTableName, true);

				if ($insertResults && $pArray['username']!=""){

					if($insertResults){

						$gTableName = "group_user";

						$gArray = array();

						$gArray['group_id'] = 3324;

						$gArray['user_id'] = $insertResults;

						$insertGroupUsers = $this->objLoginQuery->insertQuery($gArray, $gTableName, false);

					}

					$outResults['msg'] = 'success';

				}else{

					$outResults['msg'] = 'fail';

				}

			}

			echo json_encode($outResults);

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

 



	public function __destruct(){

		/*** Destroy and unset the object ***/
		unset($objLoginQuery);
		unset($_pageSlug);
		unset($objConfig);
		unset($getConfig);
		unset($objCommon);
		unset($this->objLoginQuery);

	}

	

} /*** end of class ***/

?>