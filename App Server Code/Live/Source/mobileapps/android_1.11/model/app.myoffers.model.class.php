<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mMyOffers extends SQLQuery {
	protected $_model;
	public $con;
	function __construct() {
		try{
			global $config;
			$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
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
	
	public function checkingMyOffersName($mArray)
	{
		$query = "SELECT * FROM my_offers WHERE my_offers_name='".$mArray['my_offers_name']."' AND user_id=".$mArray['user_id']." LIMIT 0, 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}	
	public function getMyOffersIdByUserId($loggedInUserId)
	{
		$query = "SELECT * FROM my_offers WHERE user_id=".$loggedInUserId." LIMIT 0, 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	/*public function getMyOffersReferencesByUserIdAndMyOfferId($myOfferId, $loggedInUserId)
	{
		$query = "SELECT * FROM my_offers AS m LEFT JOIN my_offers_reference AS mor ON mor.my_offers_id=m.my_offers_id WHERE user_id=".$loggedInUserId." LIMIT 0, 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}*/
	public function checkingMyOffersReferences($mArray)
	{
		$query = "SELECT * FROM my_offers_reference WHERE my_offers_id='".$mArray['my_offers_id']."' AND offer_id=".$mArray['offer_id']." LIMIT 0, 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithOffersAndInfoDetails($clientId, $loggedInUserId, $offerId)
	{
		$query = "SELECT of.offer_id, of.client_id,cl.name, of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image,  odt.offer_discount_value,odt.offer_discount_type,cl.client_vertical_id,of.client_id,
		of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, 
		of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, 
		oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, of.offer_barcode_image, of.offer_barcode_number,
		
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, cl.background_color, cl.light_color, cl.dark_color, cld.client_details_country_code, cs.country_name, CASE WHEN cs.country_code_char2 IS NULL or cs.country_code_char2 = '' THEN 'US' ELSE cs.country_code_char2 END AS country_code_char2, CASE WHEN cs.country_languages IS NULL or cs.country_languages = '' THEN 'en' ELSE cs.country_languages END AS country_languages, cs.currency_code
		FROM offers AS of 
		LEFT JOIN client AS cl ON cl.id=of.client_id
		LEFT JOIN `client_details` as `cld` ON `cl`.`id` = `cld`.`client_id` AND `cld`.client_details_status=1 
		LEFT JOIN `countries` as `cs` ON `cld`.`client_details_country_code` = `cs`.`country_code_char2` AND `cld`.`client_details_currency_code` = `cs`.`currency_code`
		LEFT JOIN offers_info AS oi ON oi.offer_id=of.offer_id
		
		LEFT JOIN `offers_discount_type` AS odt ON odt.client_id=of.client_id AND odt.offer_discount_status =1 	AND odt.offer_discount_type_id =of. offer_discount_type_id	
 
		WHERE ";
		if($clientId!=""){
			$query .= " of.client_id=".$clientId." AND ";
		}
		if($offerId!=""){
			$query .= " of.offer_id IN (".$offerId.") AND "; 
		}
		$query .= " of.offer_status=1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithOfferDetailsWithUserIdOld($loggedInUserId, $offerId, $flag)
	{
		$query = "SELECT mof.my_offers_name, mof.user_id, mofr.my_offers_ref_id, of.offer_id, of.client_id, 
		of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, 
		of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, 
		of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, 
		oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, of.offer_barcode_image, of.offer_barcode_number,
		
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url
		FROM my_offers AS mof 
		LEFT JOIN my_offers_reference AS mofr ON mofr.my_offers_id=mof.my_offers_id AND mofr.my_offers_ref_status=1 ";		
		if($offerId!=""){
			$query .= " AND mofr.offer_id=".$offerId." "; 
		}
		$query .= " LEFT JOIN offers AS of ON of.offer_id=mofr.offer_id AND of.offer_status=1 
		LEFT JOIN offers_info AS oi ON oi.offer_id=of.offer_id AND oi.offer_info_status=1 
		WHERE mof.my_offers_status=1 AND mof.user_id=".$loggedInUserId;
		
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithOfferDetailsWithUserIdOrOfferID($loggedInUserId, $offerId, $flag)
	{
		//$currentDate = date("Y-m-d h:m:s");
		$query = "SELECT cl.id, cl.name,cl.client_vertical_id, cl.url AS client_url, mof.my_offers_name,mof.my_offers_created_date, mof.user_id, mofr.my_offers_ref_id, of.offer_id, of.client_id, cl.background_color, cl.light_color, cl.dark_color, 
		of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, of.offer_barcode_image, 
		of.offer_barcode_number, of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, 
		of.offer_is_location_based, of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, 
		oi.offer_info_event_allday, oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, ofdt.offer_discount_value, 
		ofdt.offer_discount_type,
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, cld.client_details_country_code, cs.country_name, CASE WHEN cs.country_code_char2 IS NULL or cs.country_code_char2 = '' THEN 'US' ELSE cs.country_code_char2 END AS country_code_char2, CASE WHEN cs.country_languages IS NULL or cs.country_languages = '' THEN 'en' ELSE cs.country_languages END AS country_languages, cs.currency_code 
		FROM my_offers AS mof 
		LEFT JOIN my_offers_reference AS mofr ON mofr.my_offers_id=mof.my_offers_id AND mofr.my_offers_ref_status=1 ";		
		if($offerId!=""){
			$query .= " AND mofr.offer_id=".$offerId." "; 
		}
		$query .= " LEFT JOIN offers AS of ON of.offer_id=mofr.offer_id AND of.offer_status=1 ";
		if($flag=="expiration"){
			$query .= " AND of.offer_valid_to > NOW()";
		}
		$query .= " LEFT JOIN offers_info AS oi ON oi.offer_id=of.offer_id AND oi.offer_info_status=1
		LEFT JOIN offers_discount_type AS ofdt ON ofdt.offer_discount_type_id=of.offer_discount_type_id AND ofdt.offer_discount_status=1 
		LEFT JOIN client AS cl ON cl.id=of.client_id AND cl.active=1
		LEFT JOIN `client_details` as `cld` ON `cl`.`id` = `cld`.`client_id` AND `cld`.client_details_status=1 
		LEFT JOIN `countries` as `cs` ON `cld`.`client_details_country_code` = `cs`.`country_code_char2` AND `cld`.`client_details_currency_code` = `cs`.`currency_code`
		WHERE mof.my_offers_status=1 AND of.offer_id<>'' AND mof.user_id=".$loggedInUserId;
		if($flag=="value"){
			$query .= " ORDER BY ofdt.offer_discount_value DESC";
		}
		if($flag=="brand"){
			$query .= " ORDER BY cl.name ASC";
		}
		if($flag=="recent"){
			$query .= " ORDER BY  of.offer_id  DESC";
		}
		//echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithRelatedOffers($clientId, $offerId, $layoutType)
	{
		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 
		pd.client_id, cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color,cl.client_vertical_id, `ofr`.relatedto_id, 
		`ofr`.relatedfrom_id, `ofr`.relatedto_prodid, IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, 
		IFNULL(cl.url, 'null' ) AS clientUrl, cld.client_details_country_code, cs.country_name, CASE WHEN cs.country_code_char2 IS NULL or cs.country_code_char2 = '' THEN 'US' ELSE cs.country_code_char2 END AS country_code_char2, CASE WHEN cs.country_languages IS NULL or cs.country_languages = '' THEN 'en' ELSE cs.country_languages END AS country_languages, cs.currency_code, 
		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 
		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 
		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl,(SELECT group_concat(`relatedto_id` separator ',') as `related_id` FROM offers_related AS ofr WHERE ofr.relatedfrom_id= of.offer_id AND ofr.relatedto_id != 0) AS related_offerid,
		(SELECT group_concat(`relatedto_prodid` separator ',') as `related_id` FROM offers_related AS ofr WHERE ofr.relatedfrom_id= of.offer_id AND ofr.relatedto_prodid != 0) AS related_id,
		
		CASE WHEN pd.pd_button_name IS NULL or pd.pd_button_name = '' THEN 'null' ELSE pd.pd_button_name END AS pd_button_name,
		IFNULL(pd.pd_istryon, '0' ) AS pd_istryon, of.offer_id, of.offer_name, of.offer_image, of.offer_type_id, 
		of.offer_discount_type_id, of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, 
		of.offer_is_location_based, of.offer_is_calendar_based, odt.offer_discount_value,odt.offer_discount_type,
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, 			
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, of.client_id AS offerClientId ";
		/*if($layoutType=="financial"){
			$query .= ", of.offer_id, of.offer_name, of.offer_image ";
		}*/
		
		$query .= "
		FROM offers_related AS `ofr`
		LEFT JOIN offers AS of  ON  of.offer_id=`ofr`.relatedto_id AND of.client_id='".$clientId."' AND of.offer_status=1 AND of.offer_id!='".$offerId."'    	
		
		LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 
		LEFT JOIN `offers_discount_type` AS odt ON odt.client_id=of.client_id AND odt.offer_discount_status =1 	AND odt.offer_discount_type_id =of. offer_discount_type_id	
		LEFT JOIN products AS pd ON pd.pd_id=`ofr`.relatedto_prodid AND pd.client_id=".$clientId." AND pd.pd_status=1 
		LEFT JOIN client AS cl ON (cl.id=of.client_id OR cl.id=pd.client_id ) AND cl.active=1  
		LEFT JOIN `client_details` as `cld` ON `cl`.`id` = `cld`.`client_id` AND `cld`.client_details_status=1  
		LEFT JOIN `countries` as `cs` ON `cld`.`client_details_country_code` = `cs`.`country_code_char2` ";
		
		/*if($layoutType=="financial"){
			$query .= "LEFT JOIN offers AS of ON of.offer_id=pdr.relatedto_offerid AND of.offer_status=1 ";
		}
*/
		$query .= "WHERE `ofr`.relatedfrom_id=".$offerId;
		if($layoutType!="financial"){
			$query .= " AND of.offer_id!=''";
		}
		$query .= " GROUP BY of.offer_id DESC LIMIT 0,5";
		
	//echo $query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>