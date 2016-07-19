<?php
require_once ABSPATH."wp-includes/http.php";

class BliSubmitter {

	public function __construct() {

		add_action('admin_menu', array($this, 'admin_menu'));
		
		add_action('bli_submitter_daily_event', array($this, 'action_bli_submitter_cron'));
		add_action('new_to_publish', array($this, 'action_send_new_post_to_bli'));
		add_action('draft_to_publish', array($this, 'action_send_new_post_to_bli'));
		add_action('pending_to_publish', array($this, 'action_send_new_post_to_bli'));
	}

    public function admin_menu() {

		$page_title = 'BLI Submitter Settings';
		$menu_title = 'BLI Submitter';
		$capability = 'manage_options';
		$menu_slug = 'bli-submitter-settings';
		$function = array($this,'action_settings');
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);

		$sub_menu_title = 'Settings';
		add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

		/*
		| For testing only
		| Remove on production
		*/
		// $submenu_page_title = 'BLI Submitter Tester';
		// $submenu_title = 'Tester';
		// $submenu_slug = 'bli-submitter-tester';
		// $submenu_function = array($this,'action_tester');
		// add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
		/*
		| For testing only
		| Remove on production
		*/		

    }

    public function action_settings() {

    	$settingsObj = BliSubmitterPlugin_Settings::getInstance();

		$successMessage = '';
		$infoMessage = '';
		$warningMessage = '';
		$dangerMessage = '';

		if(isset($_REQUEST['settings'])) {

			$inputSettings = $_REQUEST['settings'];

			try {
				if(empty($inputSettings['api_key'])) {

					throw new Exception("Invalid data", 1);
				}

				$apiKey = $inputSettings['api_key'];

				if(!$this->validateBliApiKey($apiKey)) {
					$settingsObj->isApiKeyValid = 0;
					$settingsObj->errorMessage = 'Invalid API Key';
				}
				else {
					$settingsObj->isApiKeyValid = 1;
					$settingsObj->errorMessage = '';
				}

				$settingsObj->apiKey = $inputSettings['api_key'];
				$settingsObj->sendNew = $inputSettings['auto'] == 'on' ? 1 : 0;
				$settingsObj->sendRandom = $inputSettings['random'] == 'on' ? 1 : 0;
				$settingsObj->randomNewerThan = strtotime($inputSettings['random_newer_than']);
				$settingsObj->randomCount = $inputSettings['random_count'];
				$settingsObj->duplicate = $inputSettings['duplicate'] == 'on' ? 0 : 1;
				$settingsObj->duplicateOlderThan = $inputSettings['duplicate_older_than'];

				$settingsObj->save();

				if($res['error']){

					throw new Exception("Failed to update Settings. ".$res['message'], 1);
				}else{

					if($settingsObj->modified == 0) {
						wp_schedule_event( time(), 'daily', 'bli_submitter_daily_event');
					}

					$settingsObj->modified = 1;
					$settingsObj->save();

					$successMessage = "General Settings Updated successfully";
				}
				
			}
			catch(Exception $e) {
				$dangerMessage = $e->getMessage();
			}
		}

		$data = array(
			'successMessage' => $successMessage,
			'infoMessage' => $infoMessage,
			'warningMessage' => $warningMessage,
			'dangerMessage' => $dangerMessage,
			'settingsObj' => $settingsObj,
		);

		$this->render("settings",$data);
    }

    public function validateBliApiKey($key) {

    	if(empty($key)) return false;

    	$url = BACKLINK_API_URL."?key=".$key."&option=test";
    	$res = wp_remote_get($url);
    	$res = $res['body'];
    	
    	if($res == 'success') return true;
    	return false;
    }

    public function sendToBli($url) {

		$data = array(
			'url' => $url,
			'sent_date_time' => BliSubmitterPlugin_Utils::mysql_datetime(),
		);

		$postsSentToBliObj = new BliSubmitterPlugin_PostsSentToBli($data);


		if($postsSentToBliObj->validate()) {

    		$postsSentToBliObj->addToDb();

	    	$settingsObj = BliSubmitterPlugin_Settings::getInstance();

			$apiKey = $settingsObj->apiKey;
			$allowDup = $settingsObj->duplicate ? 'yes' : 'no';

			$postdata = array(
					        'key' => $apiKey,
					        'urls' => $url,
					        'allow_duplicate' => $allowDup,
					    );
			

			// $opts = array('http' =>
			//     array(
			//         'method'  => 'POST',
			//         'header'  => 'Content-type: application/x-www-form-urlencoded',
			//         'content' => $postdata
			//     )
			// );

			//$context = stream_context_create($opts);
			//$result = file_get_contents(BACKLINK_API_URL, false, $context);

			$response = wp_remote_post( BACKLINK_API_URL, array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array("Content-type"=>"application/x-www-form-urlencoded"),
				'body' => $postdata,
				'cookies' => array()
			    )
			);
			
			return true;
		}
		else {
			return false;
		}
    }

    public function action_send_new_post_to_bli($postObj) {

    	if(empty($postObj) || empty($postObj->ID)) return false;

    	$permalink = get_permalink($postObj->ID);
    	
    	$settingsObj = BliSubmitterPlugin_Settings::getInstance();
    	
    	if(!$settingsObj->sendNew)  return;
    	
    	$this->sendToBli($permalink);
    }

    public function action_bli_submitter_cron() {

    	$settingsObj = BliSubmitterPlugin_Settings::getInstance();

    	if(!$settingsObj->sendRandom) return false;

		$args = array(
			//'nopaging'		   => true,
			'posts_per_page'   => $settingsObj->randomCount,
			'offset'           => 0,
			'category'         => '',
			'orderby'          => 'rand',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);

		$postsArr = get_posts( $args );

		if(!is_array($postsArr)) return false;

		foreach ($postsArr as $singlePost) {

			if(empty($singlePost->ID)) continue;

			$permalink = get_permalink($singlePost->ID);

			$postObj = new BliSubmitterPlugin_Post($permalink,$singlePost->post_date);

			if($postObj->isSendingAllowed())
	    		$this->sendToBli($permalink);
		}
    }

    public function install() {
    	bli_submitter_db_install();
    }

    public function uninstall() {
    	bli_submitter_db_uninstall();
    	wp_clear_scheduled_hook('bli_submitter_daily_event');
    }

    protected function renderJson($data) {
    	header('Content-Type: application/json');
    	echo json_encode($data);
    	die;
    }

    protected function render($file, $data = array(), $return = false) {
    	if($return) {
    		ob_start();
    	}

    	if(is_array($this->error) && count($this->error) > 0) {
    		foreach($this->error as $error_msg) {
    			echo $error_msg."\n";
    		}
    	}

    	$final_file = BLI_SUBMITTER_PLUGIN_VIEW_DIR.'/'.$file.".php";
    	if(file_exists($final_file)) {
    		include($final_file);
    	}
    	else {
    		echo 'Failed loading view: '.$final_file;
    	}
    	
    	if($return) {
    		$output = ob_get_contents();
    		ob_end_clean();
    		return $output;
    	}
    }
}
?>
