<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mGames extends SQLQuery {
	protected $_model;
	public $con;

	function __construct() {
		try{
			global $config;
			$this->con = $this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function insertQuery($pArray, $pTableName, $pGetInsertIdCondition)
	{
		try 
		{
			$result = $this->insert($pArray, $pTableName);
			if($pGetInsertIdCondition==true){
				$result = $this->getInsertId();
			}
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function updateRecordQuery($pArray,$tableName,$con)
	{
		try 
		{
			$result = $this->update($pArray,$tableName,$con);
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function deleteById($pTableName, $con)
	{
		return $this->delete($pTableName, $con);
	}
	function connectToDB($dbName){
		$db_selected = mysql_select_db($dbName, $this->con);
		if (!$db_selected) {
		    die ('Can\'t use foo : ' . mysql_error());
		}
	}
	
	function getClientGameDetailsByCGID($sendArray){
		try{
			$query = "SELECT * FROM `client_games_details` as `cgd`,`client_games` as `cg` WHERE `cgd`.`client_game_id`=`cg`.`client_game_id` AND `cgd`.`client_game_id`='".$sendArray['client_game_id']."'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getGames(){
		try{
			$query = "SELECT * FROM `games` WHERE  `game_status` = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function checkGameItemsUsed($sendArray){
		try{
			
		    $query = "SELECT * FROM `client_games_users` WHERE `client_games_item_id`='".$sendArray['client_games_item_id']."' AND `user_id` = '".$sendArray['user_id']."'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getGameIdByCGId($sendArray){
		try{
			$query = "SELECT game_id,client_id FROM `client_games` WHERE `client_game_id`='".$sendArray['client_game_id']."'  AND  `client_game_status` = 1 ";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getDirectionTypeByCGId($sendArray){
		try{
			$query = "SELECT direction_type FROM `client_games_details` WHERE `client_game_id`='".$sendArray['client_game_id']."'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function getRandomClientGameId($sendArray){
		try{
			if(isset($sendArray['game_id']) && $sendArray['game_id'] ==1)
			{
				$query = "SELECT client_game_id FROM `client_games` WHERE `game_id`='".$sendArray['game_id']."' AND `client_id` = '".$sendArray['client_id']."' AND  `client_game_status` = 1 ORDER BY RAND() LIMIT 1";
			}
			else
			{
				$query = "SELECT client_game_id FROM `client_games` WHERE `game_id`='".$sendArray['game_id']."' AND `client_id` = '".$sendArray['client_id']."' AND `client_game_id` = '".$sendArray['client_game_id']."' AND  `client_game_status` = 1 ";
			}
			
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function getGamesInfo($sendArray){
	  try{
			$query = "SELECT * FROM `client_games` AS `cl`, `client_games_item` AS `cgi`,`client` AS `c` WHERE `cl`.`client_id`=`c`.`id` AND `cl`.`client_game_id` =`cgi`.`client_game_id` AND `cl`.`client_game_id` = '".$sendArray['client_game_id']."' AND `cl`.`client_id` = '".$sendArray['client_id']."' AND `cl`.`client_game_status` = 1";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getClientInfoByClientId($sendArray){
	  try{
			$query = "SELECT `c`.`id`,`c`.`name`,`c`.`prefix`, CONCAT('".$sendArray['client_logo_url']."',c.logo) AS logo,c.active,c.url,c.background_color,c.light_color,c.dark_color,c.background_image,c.is_demo,c.client_vertical_id,c.is_location_based,c.store_notify_msg,c.is_affiliate,c.created_date  FROM `client` AS `c` WHERE `c`.`id`  = '".$sendArray['client_id']."'";
			$result = $this->selectQueryForAssoc($query);
			return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	

	function getClientWithOfferDetailsWithUserIdOrOfferID($loggedInUserId, $offerId)
	{
		//$currentDate = date("Y-m-d h:m:s");
		$query = "SELECT cl.id, cl.name,cl.client_vertical_id,cl.url as client_url,cl.is_location_based, mof.my_offers_name,mof.my_offers_created_date, mof.user_id, mofr.my_offers_ref_id, of.offer_id, of.client_id, cl.background_color, cl.light_color, cl.dark_color, 
		of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, of.offer_barcode_image, 
		of.offer_barcode_number, of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, 
		of.offer_is_location_based, of.offer_is_calendar_based,of.offer_is_sharable, oi.offer_info_event_start, oi.offer_info_event_end, 
		oi.offer_info_event_allday, oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, ofdt.offer_discount_value, 
		ofdt.offer_discount_type,
		(SELECT group_concat(`relatedto_id` separator ',') as `related_id` FROM offers_related AS ofr WHERE ofr.relatedfrom_id= of.offer_id AND ofr.relatedto_id != 0)  AS related_offerid,		
		(SELECT group_concat(`relatedto_prodid` separator ',') as `related_id` FROM offers_related AS ofr WHERE ofr.relatedfrom_id= of.offer_id AND ofr.relatedto_prodid != 0)  AS related_prodid,
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, cld.client_details_country_code, cs.country_name, CASE WHEN cs.country_code_char2 IS NULL or cs.country_code_char2 = '' THEN 'US' ELSE cs.country_code_char2 END AS country_code_char2, CASE WHEN cs.country_languages IS NULL or cs.country_languages = '' THEN 'en' ELSE cs.country_languages END AS country_languages, cs.currency_code, 
		CASE WHEN of.offer_back_image IS NULL or of.offer_back_image = '' THEN 'null' ELSE of.offer_back_image END AS offer_back_image,of.offer_is_multi_redeem  			
		FROM my_offers AS mof 
		LEFT JOIN my_offers_reference AS mofr ON mofr.my_offers_id=mof.my_offers_id AND mofr.my_offers_ref_status=1 ";		
		if($offerId!=""){
			$query .= " AND mofr.offer_id=".$offerId." "; 
		}
		$query .= " LEFT JOIN offers AS of ON of.offer_id=mofr.offer_id AND of.offer_status=1 ";
		
		$query .= " LEFT JOIN offers_info AS oi ON oi.offer_id=of.offer_id AND oi.offer_info_status=1
		LEFT JOIN offers_discount_type AS ofdt ON ofdt.offer_discount_type_id=of.offer_discount_type_id AND ofdt.offer_discount_status=1 
		LEFT JOIN client AS cl ON cl.id=of.client_id AND cl.active=1
		LEFT JOIN `client_details` as `cld` ON `cl`.`id` = `cld`.`client_id` AND `cld`.client_details_status=1 
		LEFT JOIN `countries` as `cs` ON `cld`.`client_details_country_code` = `cs`.`country_code_char2` AND `cld`.`client_details_currency_code` = `cs`.`currency_code`
		WHERE mof.my_offers_status=1 AND of.offer_id<>'' ";
		
		
		//echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	
	}
	function getOfferInfoByOfferId($offerId)
	{
		$query = "SELECT * FROM `offers` WHERE `offer_id`=".$offerId;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithOfferDetailsWithOfferID($sendArray)
	{
		//$currentDate = date("Y-m-d h:m:s");
		$query = "SELECT cl.id, cl.name,cl.client_vertical_id,cl.url as client_url,cl.is_location_based,  of.offer_id, of.client_id, cl.background_color, cl.light_color, cl.dark_color, 
		of.offer_type_id, of.offer_discount_type_id, of.offer_name, CONCAT('".$sendArray['file_url']."',of.offer_image) AS offer_image, of.offer_barcode_image, 
		of.offer_barcode_number, of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, 
		of.offer_is_location_based, of.offer_is_calendar_based,of.offer_is_sharable, oi.offer_info_event_start, oi.offer_info_event_end, 
		oi.offer_info_event_allday, oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, ofdt.offer_discount_value, 
		ofdt.offer_discount_type,
		(SELECT group_concat(`relatedto_id` separator ',') as `related_id` FROM offers_related AS ofr WHERE ofr.relatedfrom_id= of.offer_id AND ofr.relatedto_id != 0)  AS related_offerid,		
		(SELECT group_concat(`relatedto_prodid` separator ',') as `related_id` FROM offers_related AS ofr WHERE ofr.relatedfrom_id= of.offer_id AND ofr.relatedto_prodid != 0)  AS related_prodid,
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, cld.client_details_country_code, cs.country_name, CASE WHEN cs.country_code_char2 IS NULL or cs.country_code_char2 = '' THEN 'US' ELSE cs.country_code_char2 END AS country_code_char2, CASE WHEN cs.country_languages IS NULL or cs.country_languages = '' THEN 'en' ELSE cs.country_languages END AS country_languages, cs.currency_code, 
		CASE WHEN of.offer_back_image IS NULL or of.offer_back_image = '' THEN 'null' ELSE of.offer_back_image END AS offer_back_image,of.offer_is_multi_redeem  			
		FROM offers AS of
		
		LEFT JOIN offers_info AS oi ON oi.offer_id=of.offer_id AND oi.offer_info_status=1
		LEFT JOIN offers_discount_type AS ofdt ON ofdt.offer_discount_type_id=of.offer_discount_type_id AND ofdt.offer_discount_status=1 
		LEFT JOIN client AS cl ON cl.id=of.client_id AND cl.active=1
		LEFT JOIN `client_details` as `cld` ON `cl`.`id` = `cld`.`client_id` AND `cld`.client_details_status=1 
		LEFT JOIN `countries` as `cs` ON `cld`.`client_details_country_code` = `cs`.`country_code_char2` AND `cld`.`client_details_currency_code` = `cs`.`currency_code`
		WHERE  of.offer_status=1 AND of.offer_id  IN (".$sendArray['offer_id'].")";
		
		
		//echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	
	}
	function __destruct() {
		//pg_close($this->con); 
		$this->disconnect();
	}
}
?>