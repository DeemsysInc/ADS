<?php

/**** Include SQLQuery class for Database connection and main function ****/

require_once SRV_ROOT.'model/SQLQuery.class.php'; 

class mPublic extends SQLQuery {

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

				//$result = $this->getInsertId();

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

	

	function getAllTriggers_new()

	{

		/*$query = "SELECT tr.id AS trigger_id, tr.url AS triggerUrl, tr.client_id, tr.title AS triggerTitle, 

			tr.height AS triggerHeight, tr.width AS triggerWidth, `vi`.`url` AS visualUrl, `pd`.`pd_image` AS pdImage, 

			pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, cl.background_color, cl.light_color, cl.dark_color, 

			cl.name AS clientName, vi.id AS visualId, vi.discriminator, pai.pd_additional_id AS tapForDetailsImgId, 

			pai.pd_id AS tapForDetailsImgPdId, of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, 

			of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, 

			of.offer_is_location_based, of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, 

			oi.offer_info_event_allday, oi.offer_info_event_has_alarm, oi.offer_info_reminder_days, 

			pai.pd_additional_images AS tapForDetailsImgs, `vi`.`x`, `vi`.`y`, `vi`.`rotation_x`,

			`vi`.`rotation_y`, `vi`.`rotation_z`, `vi`.`scale`, `vi`.`animate_on_recognition`, pd.pd_short_description, 

			cl.url AS clientUrl, of.offer_id, tr.instruction AS triggerInstruction, vi.product_id, 

			cl.logo AS clientLogo, cl.background_image, pd.pd_url AS productUrl 

			FROM `trigger` as `tr` 

			LEFT JOIN `visual` as `vi` ON `vi`.`trigger_id` = `tr`.id AND `vi`.`discriminator` !=''

			LEFT JOIN `products` as `pd` ON `vi`.`product_id` = `pd`.`pd_id` AND `pd`.`pd_status`=1 

			LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` AND `cl`.active=1

			LEFT JOIN `products_addtional_image` AS `pai` ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 

			LEFT JOIN `offers` AS of ON vi.offer_id=of.offer_id AND of.offer_status=1 

			LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 

			WHERE `tr`.`url`!='' AND `tr`.`active`=1";*/

		$query = "SELECT tr.id AS trigger_id, tr.url AS triggerUrl, tr.client_id, tr.title AS triggerTitle, tr.height AS triggerHeight, 

			tr.width AS triggerWidth, `vi`.`url` AS visualUrl, `pd`.`pd_image` AS pdImage,  

			pd.`pd_name` AS `prodName`, cl.background_color, cl.light_color, cl.dark_color, cl.name AS clientName,cl.client_vertical_id, vi.id AS visualId, 

			vi.discriminator, pai.pd_additional_id AS tapForDetailsImgId, pai.pd_id AS tapForDetailsImgPdId, 

			of.offer_type_id, of.offer_discount_type_id, odt.offer_discount_value,odt.offer_discount_type, of.offer_name, of.offer_image, of.offer_short_description, 

			of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, of.offer_is_calendar_based, 

			oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, oi.offer_info_event_has_alarm,  

			oi.offer_info_reminder_days, pai.pd_additional_images AS tapForDetailsImgs, 

			IFNULL(`vi`.`x`, 0) AS x, IFNULL(`vi`.`y`, 0) AS y, IFNULL(`vi`.`rotation_x`, 0) AS rotation_x, 

			IFNULL(`vi`.`rotation_y`, 0) AS rotation_y, IFNULL(`vi`.`rotation_z`, 0) AS rotation_z, 

			IFNULL(`vi`.`scale`, 0) AS scale, IFNULL(`vi`.`animate_on_recognition`, 0) AS `animate_on_recognition`,

			IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 

			CASE WHEN of.offer_id IS NULL or of.offer_id = '' THEN 'null' ELSE of.offer_id END AS offer_id,
			 
			CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, 
			
			CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 

			CASE WHEN tr.instruction IS NULL or tr.instruction = '' THEN 'null' ELSE tr.instruction END AS triggerInstruction, 

			CASE WHEN vi.product_id IS NULL or vi.product_id = '' THEN 'null' ELSE vi.product_id END AS product_id,  
			
			CASE WHEN vi.buy_button_name IS NULL or vi.buy_button_name = '' THEN 'null' ELSE vi.buy_button_name END AS buy_button_name,  
			CASE WHEN vi.buy_button_url IS NULL or vi.buy_button_url = '' THEN 'null' ELSE vi.buy_button_url END AS buy_button_url,

			CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

			CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 

			CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl,
			
			CASE WHEN pd.pd_button_name IS NULL or pd.pd_button_name = '' THEN 'null' ELSE pd.pd_button_name END AS pd_button_name,
			
			CASE WHEN pd.`pd_price` IS NULL or pd.`pd_price` = '' THEN 'null' ELSE pd.`pd_price` END AS pdPrice, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon 

			FROM `trigger` as `tr` 

			LEFT JOIN `visual` as `vi` ON `vi`.`trigger_id` = `tr`.id AND `vi`.`discriminator` !=''

			LEFT JOIN `products` as `pd` ON `vi`.`product_id` = `pd`.`pd_id` AND `pd`.`pd_status`=1 

			LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` AND `cl`.active=1

			LEFT JOIN `products_addtional_image` AS `pai` ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 

			LEFT JOIN `offers` AS of ON vi.offer_id=of.offer_id AND of.offer_status=1 

			LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 
			
			LEFT JOIN `offers_discount_type` AS odt ON odt.client_id=of.client_id AND odt.offer_discount_status =1 

			WHERE `tr`.`url`!='' AND `tr`.`active`=1";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function getAllTriggerModels_new($triggerId)

	{

		$query = "SELECT `tr`.`url` , `tr`.`id` AS `trigger_id` ,`tr`.`client_id` ,  `tr`.`title` ,  `cl`.`name` , `vi`.`id` , `vi`.`discriminator` ,IFNULL( `vi`.`product_id`, 0 ) AS product_id , IFNULL(`vi`.`x`, 0) AS x ,  IFNULL(`vi`.`y`, 0) AS y , IFNULL(`vi`.`rotation_x`, 0) AS rotation_x, IFNULL(`vi`.`rotation_y`, 0) AS rotation_y, IFNULL(`vi`.`rotation_z`, 0) AS rotation_z, IFNULL(`vi`.`scale`, 0) AS scale, IFNULL( `vi`.`animate_on_recognition`, 0 ) AS `animate_on_recognition`,`vi`.`url` AS visual, `tr`.`height`, `tr`.`width`, `pd`.`image`, `pd`.`price`, `pd`.`title` AS `prodName`,IFNULL( `pd`.`offer`, 0 ) AS offer, CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, cl.background_color, cl.light_color, cl.dark_color,cl.client_vertical_id, CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, apm.media AS tapForDetailsImg, apm.id AS tapForDetailsImgId, apm.product_id AS tapForDetailsImgPdId, IFNULL(pd.short_description, 'null' ) AS short_description, IFNULL(cl.url, 'null' ) AS clientUrl, CASE WHEN pd.url IS NULL or pd.url = '' THEN 'null' ELSE pd.url END AS productUrl, IFNULL(tr.instruction,'null') AS instruction, oi.event_start, oi.event_end, oi.event_allday, oi.event_has_alarm, oi.offer_info_reminder_days FROM `trigger` AS `tr` LEFT JOIN `visual` AS `vi` ON `vi`.`trigger_id` = `tr`.id LEFT JOIN `product` as `pd` ON `vi`.`product_id` = `pd`.`id` LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` LEFT JOIN additional_product_media AS apm ON apm.product_id=pd.id AND apm.active=1 LEFT JOIN `offers_info` AS oi ON oi.client_id=cl.id AND oi.product_id= `pd`.`id` WHERE `tr`.`url` != '' AND `tr`.`active` =1  AND `vi`.`discriminator` ='3DMODEL' AND `tr`.`id`=".$triggerId;

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	

	function getClientProducts($clientId, $productId)

	{

		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 

		pd.client_id, cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color, pdr.relatedto_id, pdr.relatedfrom_id, 

		pai.pd_additional_id AS tapForDetailsImgId, pai.pd_additional_images AS tapForDetailsImgs, pai.pd_id AS tapForDetailsImgPdId, 

		IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 

		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 

		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon

		FROM `products` AS pd 

		LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 

		LEFT JOIN product_related AS pdr ON pd.pd_id=pdr.relatedfrom_id 

		LEFT JOIN products_addtional_image AS pai ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 

		WHERE pd.client_id=".$clientId." AND pd.pd_status=1 ";

		/*if($productId!=""){

			$query .= " AND pd.pd_id!=".$productId; 

		}*/

		$query .= " GROUP BY pd.pd_id ORDER BY pd.pd_id DESC LIMIT 0,5";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function getClientWithRelatedProducts($clientId, $productId)

	{
		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 

		pd.client_id, cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color, pdr.relatedto_id, 

		pdr.relatedfrom_id, pai.pd_additional_id AS tapForDetailsImgId, pai.pd_additional_images AS tapForDetailsImgs, 

		pai.pd_id AS tapForDetailsImgPdId, IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, 

		IFNULL(cl.url, 'null' ) AS clientUrl, 

		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 

		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl, 
		
		CASE WHEN pd.pd_button_name IS NULL or pd.pd_button_name = '' THEN 'null' ELSE pd.pd_button_name END AS pd_button_name,
		IFNULL(pd.pd_istryon, '0' ) AS pd_istryon
		
		
		FROM `product_related` AS pdr 

		LEFT JOIN products AS pd ON pd.pd_id=pdr.relatedto_id AND pd.client_id=".$clientId." AND pd.pd_status=1 

		LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 		

		LEFT JOIN products_addtional_image AS pai ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 

		WHERE pdr.relatedfrom_id=".$productId." AND pd.pd_id!='' GROUP BY pd.pd_id  LIMIT 0,5";
		//echo $query;
		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function getTriggerModel($triggerId)

	{		

		$query = "SELECT *, (SELECT (COUNT(model)+COUNT(texture)+COUNT(material)) FROM model WHERE three_d_model_id=".$triggerId.") AS totalModelCount FROM `model` WHERE three_d_model_id=".$triggerId."";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	

	function getTapForDetails($loggedInUserId, $productId)

	{

		$query = "SELECT pai.pd_additional_id AS tapForDetailsImgId, pai.pd_id AS tapForDetailsImgPdId, pd.client_id, 

		pai.pd_additional_images AS tapForDetailsImgs

		FROM `products_addtional_image` AS pai 

		LEFT JOIN products AS pd ON pd.pd_id=pai.pd_id AND pd.pd_status=1 

		WHERE pai.pd_id=".$productId." AND pai.pd_additional_status=1";

		$result = $this->selectQueryForAssoc($query);

		return $result;		

	}

	function getClientWithProductDetails($clientId, $productId)

	{

		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 

		pd.client_id, cl.id AS clientId, cl.name AS clientName, cl.background_color, cl.light_color, cl.dark_color, 

		IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 

		IFNULL(pd.pd_description, 'null' ) AS pd_description, 

		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 

		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon

		FROM `products` AS pd 

		LEFT JOIN client AS cl ON cl.id = pd.client_id AND cl.active=1 

		WHERE pd.client_id=".$clientId." AND pd.pd_id=".$productId." AND pd.pd_status=1";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function getClientGroupTriggers()

	{

		$query = "SELECT *, image AS clientImage FROM `client_groups` WHERE active=1 ORDER BY id ASC";		

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function getClientAllTriggersForXML($clientId)

	{

		$query = "SELECT tr.id AS trigger_id, tr.url AS triggerUrl, tr.client_id, tr.title AS triggerTitle, tr.height AS triggerHeight, 

			tr.width AS triggerWidth, `vi`.`url` AS visualUrl, `pd`.`pd_image` AS pdImage,

			pd.`pd_name` AS `prodName`, cl.background_color, cl.light_color, cl.dark_color, cl.client_vertical_id, cl.name AS clientName, vi.id AS visualId, 

			vi.discriminator, pai.pd_additional_id AS tapForDetailsImgId, pai.pd_id AS tapForDetailsImgPdId, 

			of.offer_type_id, of.offer_discount_type_id, odt.offer_discount_value,odt.offer_discount_type, of.offer_name, of.offer_image, of.offer_short_description, 

			of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, of.offer_is_calendar_based, 

			oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, oi.offer_info_event_has_alarm,  

			oi.offer_info_reminder_days, pai.pd_additional_images AS tapForDetailsImgs, 

			IFNULL(`vi`.`x`, 0) AS x, IFNULL(`vi`.`y`, 0) AS y, IFNULL(`vi`.`rotation_x`, 0) AS rotation_x, 

			IFNULL(`vi`.`rotation_y`, 0) AS rotation_y, IFNULL(`vi`.`rotation_z`, 0) AS rotation_z, 

			IFNULL(`vi`.`scale`, 0) AS scale, IFNULL(`vi`.`animate_on_recognition`, 0) AS `animate_on_recognition`,

			IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 

			CASE WHEN of.offer_id IS NULL or of.offer_id = '' THEN 'null' ELSE of.offer_id END AS offer_id,
			
			CASE WHEN of.offer_purchase_url IS NULL or of.offer_purchase_url = '' THEN 'null' ELSE of.offer_purchase_url END AS offer_purchase_url, 
			
			CASE WHEN of.offer_button_name IS NULL or of.offer_button_name = '' THEN 'null' ELSE of.offer_button_name END AS offer_button_name, 
			
			CASE WHEN tr.instruction IS NULL or tr.instruction = '' THEN 'null' ELSE tr.instruction END AS triggerInstruction, 

			CASE WHEN vi.product_id IS NULL or vi.product_id = '' THEN 'null' ELSE vi.product_id END AS product_id, 
			
			CASE WHEN vi.buy_button_name IS NULL or vi.buy_button_name = '' THEN 'null' ELSE vi.buy_button_name END AS buy_button_name,  
			CASE WHEN vi.buy_button_url IS NULL or vi.buy_button_url = '' THEN 'null' ELSE vi.buy_button_url END AS buy_button_url,

			CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

			CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 

			CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl ,
			
			CASE WHEN pd.pd_button_name IS NULL or pd.pd_button_name = '' THEN 'null' ELSE pd.pd_button_name END AS pd_button_name,
			
			CASE WHEN `pwv`.pd_bg_color IS NULL or `pwv`.pd_bg_color = '' THEN 'null' ELSE `pwv`.pd_bg_color END AS pdBgColor,
			
			CASE WHEN `pwv`.pd_hide_bg_image IS NULL or `pwv`.pd_hide_bg_image = '' THEN 'null' ELSE `pwv`.pd_hide_bg_image END AS pdHideImage,
			CASE WHEN pd.`pd_price` IS NULL or pd.`pd_price` = '' THEN 'null' ELSE pd.`pd_price` END AS pdPrice,  		
			IFNULL(pd.pd_istryon, '0' ) AS pd_istryon
			
			FROM `trigger` as `tr` 

			LEFT JOIN `visual` as `vi` ON `vi`.`trigger_id` = `tr`.id AND `vi`.`discriminator` !=''

			LEFT JOIN `products` as `pd` ON `vi`.`product_id` = `pd`.`pd_id` AND `pd`.`pd_status`=1 

			LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` AND `cl`.active=1

			LEFT JOIN `products_addtional_image` AS `pai` ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 
			
			LEFT JOIN products_background_view AS `pwv` ON pwv.pd_id=vi.product_id AND pwv.pd_bg_status=1 

			LEFT JOIN `offers` AS of ON vi.offer_id=of.offer_id AND of.offer_status=1 

			LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 
			
			LEFT JOIN `offers_discount_type` AS odt ON odt.client_id=of.client_id AND odt.offer_discount_status =1 

			WHERE `tr`.`url`!='' AND `tr`.`active`=1 AND `tr`.`client_id` IN (".$clientId.") GROUP BY vi.id ORDER BY vi.id ASC";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}
	
	function getUserDetails($userId)
	{
		$query = "SELECT * FROM users AS u, user_details AS ud WHERE u.user_id = ud.user_id AND u.user_id =".$userId;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getClientStores($clientIds)
	{
		$query = "SELECT * FROM client_stores  AS cs LEFT JOIN client AS cl ON cl.id=cs.client_id AND cl.active=1 WHERE client_id IN (".$clientIds.")";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}

	function getClientWithTryonRelatedProducts($clientId, $productId)

	{
		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 

		pd.client_id, cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color, pdr.relatedto_id, 

		pdr.relatedfrom_id, pai.pd_additional_id AS tapForDetailsImgId, pai.pd_additional_images AS tapForDetailsImgs, 

		pai.pd_id AS tapForDetailsImgPdId, IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, 

		IFNULL(cl.url, 'null' ) AS clientUrl, 

		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 

		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 

		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl, 
		
		CASE WHEN pd.pd_button_name IS NULL or pd.pd_button_name = '' THEN 'null' ELSE pd.pd_button_name END AS pd_button_name,
		IFNULL(pd.pd_istryon, '0' ) AS pd_istryon
		
		
		FROM `product_related` AS pdr 

		LEFT JOIN products AS pd ON pd.pd_id=pdr.relatedto_id AND pd.client_id=".$clientId." AND pd.pd_status=1 

		LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 		

		LEFT JOIN products_addtional_image AS pai ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 

		WHERE pdr.relatedfrom_id=".$productId." AND pd.pd_id!='' AND pd.pd_istryon=1 GROUP BY pd.pd_id";
		//echo $query;
		$result = $this->selectQueryForAssoc($query);

		return $result;

	}


	function __destruct() {

		//pg_close($this->con); 

	}

}

?>