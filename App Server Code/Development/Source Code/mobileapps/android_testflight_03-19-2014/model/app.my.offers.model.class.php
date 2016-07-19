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

		$query = "SELECT of.offer_id, of.client_id,cl.name, of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image,  odt.offer_discount_value,odt.offer_discount_type,

		of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, 

		of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, 

		oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, of.offer_barcode_image, of.offer_barcode_number,
		
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url

		FROM offers AS of 

		LEFT JOIN client AS cl ON cl.id=of.client_id
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

		$query = "SELECT cl.id, cl.name,cl.client_vertical_id, mof.my_offers_name, mof.user_id, mofr.my_offers_ref_id, of.offer_id, of.client_id, 

		of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, of.offer_barcode_image, 

		of.offer_barcode_number, of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, 

		of.offer_is_location_based, of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, 

		oi.offer_info_event_allday, oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, ofdt.offer_discount_value, 

		ofdt.offer_discount_type,
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url 

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

		WHERE mof.my_offers_status=1 AND of.offer_id<>'' AND mof.user_id=".$loggedInUserId;

		if($flag=="value"){

			$query .= " ORDER BY ofdt.offer_discount_value DESC";

		}

		if($flag=="brand"){

			$query .= " ORDER BY cl.name ASC";

		}

		//echo $query;

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}


	function getClientWithRelatedOffers($clientId, $offerId)

	{


		$query = "SELECT cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color, `or `.relatedfrom_id, `or `.relatedto_id, of.offer_type_id, of.offer_discount_type_id, odt.offer_discount_value,odt.offer_discount_type, of.offer_name, of.offer_image, of.offer_short_description,	of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, oi.offer_info_event_has_alarm,oi.offer_info_reminder_days, 

		IFNULL(cl.url, 'null' ) AS clientUrl, 

		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 
		
		CASE WHEN of.offer_id IS NULL or of.offer_id = '' THEN 'null' ELSE of.offer_id END AS offer_id,
			 
		CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, 
			
		CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name

		
		FROM offers_related AS `or `

		LEFT JOIN offers AS of  ON  of.offer_id=`or `.relatedto_id AND of.client_id='".$clientId."' AND of.offer_status=1 AND of.offer_id!='".$offerId."'    	
		
		LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 

		LEFT JOIN client AS cl ON cl.id= of.client_id AND cl.active=1 	

		LEFT JOIN `offers_discount_type` AS odt ON odt.client_id=of.client_id AND odt.offer_discount_status =1 	AND odt.offer_discount_type_id =of. offer_discount_type_id	


		WHERE `or `.relatedfrom_id = '".$offerId."' AND of.offer_id!='' GROUP BY of.offer_id  LIMIT 0,5";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}
	

	function __destruct() {

		//pg_close($this->con); 

	}

}

?>