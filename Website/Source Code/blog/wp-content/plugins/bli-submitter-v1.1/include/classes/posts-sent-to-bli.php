<?php
class BliSubmitterPlugin_PostsSentToBli extends BliSubmitterPlugin_Generic_Model {

	public $id;
	public $url;
	public $sent_date_time;
	public $extra;

	private $table = 'wp_posts_sent_to_bli';

	public function __construct($data = array()) {

		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

	public function validate() {

		if(
			empty($this->url) ||
			empty($this->sent_date_time)
		) return false;

		return true;
	}

	public function addToDb() {

		$data = array(
			'url' => $this->url,
			'sent_date_time'	=> $this->sent_date_time,
			'extra' => $this->extra,
		);

		$id = self::insert($this->table,$data);

		if($id) {

			$this->id = $id;
			return true;
		}
		else {
			return false;
		}		
	}

	public function remove() {

		$where = array('id' => $this->id);
		return self::delete($this->table,$where);
	}

	public function save() {

		$data = array(
			'url' => $this->url,
			'sent_date_time'	=> $this->sent_date_time,
			'extra' => $this->extra,
		);

		return $ret = self::update($this->table,array('id'=>$this->id),$data);
	}
}
?>