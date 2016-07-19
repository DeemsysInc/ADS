<?php
class BliSubmitterPlugin_Settings {

	private $wpOptionkey;
	private $lastUpdatedAt;

	public $apiKey;

	public $isApiKeyValid;
	public $errorMessage;

	public $sendNew;
	public $sendRandom;
	public $randomNewerThan;
	public $randomCount;
	public $duplicate;
	public $duplicateOlderThan;

	public $modified;

	static $obj;

	public static function getInstance(){

		$key = "BliSubmitterPlugin_Settings";
		self::$obj = unserialize(get_option($key));
		if(!self::$obj){
			// This is running for the first time, so lets do the default setting
			self::$obj = new self;

			self::$obj->apiKey = '';

			self::$obj->isApiKeyValid = 0;
			self::$obj->errorMessage = 'Invalid Or Empty API Key.';

			self::$obj->sendNew = 0;
			self::$obj->sendRandom = 0;
			self::$obj->randomNewerThan = time();
			self::$obj->randomCount = 1;
			self::$obj->duplicate = 0;
			self::$obj->duplicateOlderThan = 0;
			
			self::$obj->modified = 0;

			self::$obj->wpOptionkey = $key;
			self::$obj->lastUpdatedAt = time();
			self::$obj->save();
		}
		return self::$obj;
	}

	public function validate() {

		$returnArray = array('error' => FALSE);
		if(
			($this->sendNew != 0 && $this->sendNew != 1) ||
			($this->sendRandom != 0 && $this->sendRandom != 1) ||
			!is_numeric($this->randomNewerThan) ||
			!is_numeric($this->randomCount) ||
			($this->duplicate != 0 && $this->duplicate != 1) ||
			!is_numeric($this->duplicateOlderThan)
		)
		{
			$returnArray = array('error' => TRUE);
			$temp = array();
			if(($this->sendNew != 0 && $this->sendNew != 1)) $temp[] = 'Field: "Send new post to backlinksindexer.com automatically" does not have appropriate value';
			if(($this->sendRandom != 0 && $this->sendRandom != 1)) $temp[] = 'Field: "Send random post to backlinksindexer.com" does not have appropriate value';
			if(!is_numeric($this->randomNewerThan)) $temp[] = 'Field: "Send random post newer than"  must be a date of format "yyyy/mm/dd"';
			if(!is_numeric($this->randomCount)) $temp[] = 'Field: "Count of random post to send"  must be an integer';
			if(($this->duplicate != 0 && $this->duplicate != 1)) $temp[] = 'Field: "Never submit the same post twice (Duplicates)" does not have appropriate value';
			if(!is_numeric($this->duplicateOlderThan)) $temp[] = 'Field: "Prevent the same post from being submitted to BLI unless _____ months have passed"  must have an integer value';

			if(!empty($temp)) $returnArray['errorMessage'] = $temp;

		}
		return $returnArray;
	}

	public function save(){

		$returnArray = array('error' => FALSE);
		$valRes = $this->validate();
		if($valRes['error']) {
			$returnArray['error'] = TRUE;
			$returnArray['message'] = implode('<br />', $valRes['errorMessage']);
		}
		else {
			$updateOptionRes = update_option($this->wpOptionkey, serialize($this));

			if(!$updateOptionRes) {
				$returnArray['error'] = TRUE;
				$returnArray['message'] = 'Settings seems to be same as previous one.';
			}
		}
		return $returnArray;
	}
}
?>