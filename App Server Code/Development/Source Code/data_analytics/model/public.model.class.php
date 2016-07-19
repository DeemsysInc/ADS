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
	
	function getAllTriggers_new()
	{
		$query = "SELECT tr.id AS trigger_id, tr.url AS triggerUrl, tr.client_id, tr.title AS triggerTitle, tr.height AS triggerHeight, 
			tr.width AS triggerWidth, `vi`.`url` AS visualUrl, `pd`.`pd_image` AS pdImage, pd.`pd_price` AS pdPrice, 
			pd.`pd_name` AS `prodName`, cl.background_color, cl.light_color, cl.dark_color, cl.name AS clientName, vi.id AS visualId, 
			vi.discriminator, pai.pd_additional_id AS tapForDetailsImgId, pai.pd_id AS tapForDetailsImgPdId, 
			of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, of.offer_short_description, 
			of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, of.offer_is_calendar_based, 
			oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, oi.offer_info_event_has_alarm,  
			oi.offer_info_reminder_days, pai.pd_additional_images AS tapForDetailsImgs, 
			IFNULL(`vi`.`x`, 0) AS x, IFNULL(`vi`.`y`, 0) AS y, IFNULL(`vi`.`rotation_x`, 0) AS rotation_x, 
			IFNULL(`vi`.`rotation_y`, 0) AS rotation_y, IFNULL(`vi`.`rotation_z`, 0) AS rotation_z, 
			IFNULL(`vi`.`scale`, 0) AS scale, IFNULL(`vi`.`animate_on_recognition`, 0) AS `animate_on_recognition`,
			IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 
			CASE WHEN of.offer_id IS NULL or of.offer_id = '' THEN 'null' ELSE of.offer_id END AS offer_id, 
			CASE WHEN tr.instruction IS NULL or tr.instruction = '' THEN 'null' ELSE tr.instruction END AS triggerInstruction, 
			CASE WHEN vi.product_id IS NULL or vi.product_id = '' THEN 'null' ELSE vi.product_id END AS product_id,  
			CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 
			CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 
			CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl 
			FROM `trigger` as `tr` 
			LEFT JOIN `visual` as `vi` ON `vi`.`trigger_id` = `tr`.id AND `vi`.`discriminator` !=''
			LEFT JOIN `products` as `pd` ON `vi`.`product_id` = `pd`.`pd_id` AND `pd`.`pd_status`=1 
			LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` AND `cl`.active=1
			LEFT JOIN `products_addtional_image` AS `pai` ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 
			LEFT JOIN `offers` AS of ON vi.offer_id=of.offer_id AND of.offer_status=1 
			LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 
			WHERE `tr`.`url`!='' AND `tr`.`active`=1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getAllTriggerModels_new($triggerId)
	{
		$query = "SELECT `tr`.`url` , `tr`.`id` AS `trigger_id` ,`tr`.`client_id` ,  `tr`.`title` ,  `cl`.`name` , `vi`.`id` , `vi`.`discriminator` ,IFNULL( `vi`.`product_id`, 0 ) AS product_id , IFNULL(`vi`.`x`, 0) AS x ,  IFNULL(`vi`.`y`, 0) AS y , IFNULL(`vi`.`rotation_x`, 0) AS rotation_x, IFNULL(`vi`.`rotation_y`, 0) AS rotation_y, IFNULL(`vi`.`rotation_z`, 0) AS rotation_z, IFNULL(`vi`.`scale`, 0) AS scale, IFNULL( `vi`.`animate_on_recognition`, 0 ) AS `animate_on_recognition`,`vi`.`url` AS visual, `tr`.`height`, `tr`.`width`, `pd`.`image`, `pd`.`price`, `pd`.`title` AS `prodName`,IFNULL( `pd`.`offer`, 0 ) AS offer, CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, cl.background_color, cl.light_color, cl.dark_color, CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, apm.media AS tapForDetailsImg, apm.id AS tapForDetailsImgId, apm.product_id AS tapForDetailsImgPdId, IFNULL(pd.short_description, 'null' ) AS short_description, IFNULL(cl.url, 'null' ) AS clientUrl, CASE WHEN pd.url IS NULL or pd.url = '' THEN 'null' ELSE pd.url END AS productUrl, IFNULL(tr.instruction,'null') AS instruction, oi.event_start, oi.event_end, oi.event_allday, oi.event_has_alarm, oi.offer_info_reminder_days FROM `trigger` AS `tr` LEFT JOIN `visual` AS `vi` ON `vi`.`trigger_id` = `tr`.id LEFT JOIN `product` as `pd` ON `vi`.`product_id` = `pd`.`id` LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` LEFT JOIN additional_product_media AS apm ON apm.product_id=pd.id AND apm.active=1 LEFT JOIN `offers_info` AS oi ON oi.client_id=cl.id AND oi.product_id= `pd`.`id` WHERE `tr`.`url` != '' AND `tr`.`active` =1  AND `vi`.`discriminator` ='3DMODEL' AND `tr`.`id`=".$triggerId;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getClientProducts($clientId)
	{
		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 
		pd.client_id, cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color, pdr.relatedto_id, pdr.relatedfrom_id, 
		pai.pd_additional_id AS tapForDetailsImgId, pai.pd_additional_images AS tapForDetailsImgs, pai.pd_id AS tapForDetailsImgPdId, 
		IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 
		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 
		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 
		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl
		FROM `products` AS pd 
		LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 
		LEFT JOIN product_related AS pdr ON pd.pd_id=pdr.relatedfrom_id 
		LEFT JOIN products_addtional_image AS pai ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 
		WHERE pd.client_id=".$clientId." AND pd.pd_status=1 GROUP BY pd.pd_id ORDER BY pd.pd_id DESC LIMIT 0,5";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithRelatedProducts($clientId, $productId)
	{
		$query = "SELECT pd.pd_id AS prodId, pd.pd_image AS pdImage, pd.`pd_price` AS pdPrice, pd.`pd_name` AS `prodName`, 
		pd.client_id, cl.id AS clientId, cl.name, cl.background_color, cl.light_color, cl.dark_color, pdr.relatedto_id, pdr.relatedfrom_id, 
		pai.pd_additional_id AS tapForDetailsImgId, pai.pd_additional_images AS tapForDetailsImgs, pai.pd_id AS tapForDetailsImgPdId, 
		IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 
		CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 
		CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 
		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl
		FROM `products` AS pd 
		LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 
		LEFT JOIN product_related AS pdr ON pdr.relatedfrom_id=".$productId." 
		LEFT JOIN products_addtional_image AS pai ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 
		WHERE pd.client_id=".$clientId." AND pd.pd_status=1 AND pd.pd_id!=".$productId." GROUP BY pd.pd_id ORDER BY pd.pd_id DESC LIMIT 0,5";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getTriggerModel($triggerId)
	{		
		$query = "SELECT * FROM  `model` WHERE three_d_model_id=".$triggerId."";
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
		CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl
		FROM `products` AS pd 
		LEFT JOIN client AS cl ON cl.id = pd.client_id AND cl.active=1 
		WHERE pd.client_id=".$clientId." AND pd.pd_id=".$productId." AND pd.pd_status=1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClientWithProductOfferDetails($clientId, $productId)
	{
		$query = "SELECT of.offer_id, of.client_id, of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, 
		of.offer_short_description, of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, 
		of.offer_is_calendar_based, oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, 
		oi.offer_info_event_has_alarm, oi.offer_info_reminder_days
		FROM offers AS of 
		LEFT JOIN offers_info AS oi ON oi.offer_id=of.offer_id 
		WHERE of.client_id=".$clientId." AND of.offer_status=1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function getClosetProductsValues($arrproductId)
	{
		//$query = "SELECT * FROM  `product` WHERE id IN (".$arrproductId.")";
		$query = "SELECT pd.* , cl.name AS client_name, cl.id AS client_id FROM `products` AS pd 
		LEFT JOIN `client` AS cl ON cl.id = pd.client_id WHERE pd.pd_id IN (".$arrproductId.")";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	// function getClientGroupTriggers()
// 	{
// 		$query = "SELECT *, image AS clientImage FROM  `client_groups`";		
// 		$result = $this->selectQueryForAssoc($query);
// 		return $result;
// 	}
	function getClientAllTriggersForXML($clientId)
	{
		$query = "SELECT tr.id AS trigger_id, tr.url AS triggerUrl, tr.client_id, tr.title AS triggerTitle, tr.height AS triggerHeight, 
			tr.width AS triggerWidth, `vi`.`url` AS visualUrl, `pd`.`pd_image` AS pdImage, pd.`pd_price` AS pdPrice, 
			pd.`pd_name` AS `prodName`, cl.background_color, cl.light_color, cl.dark_color, cl.name AS clientName, vi.id AS visualId, 
			vi.discriminator, pai.pd_additional_id AS tapForDetailsImgId, pai.pd_id AS tapForDetailsImgPdId, 
			of.offer_type_id, of.offer_discount_type_id, of.offer_name, of.offer_image, of.offer_short_description, 
			of.offer_description, of.offer_valid_from, of.offer_valid_to, of.offer_is_location_based, of.offer_is_calendar_based, 
			oi.offer_info_event_start, oi.offer_info_event_end, oi.offer_info_event_allday, oi.offer_info_event_has_alarm,  
			oi.offer_info_reminder_days, pai.pd_additional_images AS tapForDetailsImgs, 
			IFNULL(`vi`.`x`, 0) AS x, IFNULL(`vi`.`y`, 0) AS y, IFNULL(`vi`.`rotation_x`, 0) AS rotation_x, 
			IFNULL(`vi`.`rotation_y`, 0) AS rotation_y, IFNULL(`vi`.`rotation_z`, 0) AS rotation_z, 
			IFNULL(`vi`.`scale`, 0) AS scale, IFNULL(`vi`.`animate_on_recognition`, 0) AS `animate_on_recognition`,
			IFNULL(pd.pd_short_description, 'null' ) AS pd_short_description, IFNULL(cl.url, 'null' ) AS clientUrl, 
			CASE WHEN of.offer_id IS NULL or of.offer_id = '' THEN 'null' ELSE of.offer_id END AS offer_id, 
			CASE WHEN tr.instruction IS NULL or tr.instruction = '' THEN 'null' ELSE tr.instruction END AS triggerInstruction, 
			CASE WHEN vi.product_id IS NULL or vi.product_id = '' THEN 'null' ELSE vi.product_id END AS product_id,  
			CASE WHEN cl.logo IS NULL or cl.logo = '' THEN 'null' ELSE cl.logo END AS clientLogo, 
			CASE WHEN cl.background_image IS NULL or cl.background_image = '' THEN 'null' ELSE cl.background_image END AS background_image, 
			CASE WHEN pd.pd_url IS NULL or pd.pd_url = '' THEN 'null' ELSE pd.pd_url END AS productUrl 
			FROM `trigger` as `tr` 
			LEFT JOIN `visual` as `vi` ON `vi`.`trigger_id` = `tr`.id AND `vi`.`discriminator` !=''
			LEFT JOIN `products` as `pd` ON `vi`.`product_id` = `pd`.`pd_id` AND `pd`.`pd_status`=1 
			LEFT JOIN `client` as `cl` ON `tr`.`client_id` = `cl`.`id` AND `cl`.active=1
			LEFT JOIN `products_addtional_image` AS `pai` ON pai.pd_id=pd.pd_id AND pai.pd_additional_status=1 
			LEFT JOIN `offers` AS of ON vi.offer_id=of.offer_id AND of.offer_status=1 
			LEFT JOIN `offers_info` AS oi ON oi.client_id=of.client_id AND oi.offer_id=of.offer_id 
			WHERE `tr`.`url`!='' AND `tr`.`active`=1 AND `tr`.`client_id` IN (".$clientId.") GROUP BY vi.id ORDER BY vi.id ASC";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	/* New functionalities */
	function getClientTriggers($arrClient){
		$query = "SELECT * FROM `trigger` ";
		if (count($arrClient)>0){
			$query .= " WHERE `client_id` IN (";
			for ($i=0;$i<count($arrClient);$i++){
				$query .= $arrClient[$i];
				if ($i<(count($arrClient)-1)){
					$query .= ",";
				}
			}
			$query .= " ) AND `active` = 1";
		}
		//echo "Query: ".$query;
		$result = $this->selectQueryForAssoc($query);
		return $result;
		
	}
	function getVisualsByTriggerID($triggerID){
		$query = "SELECT * FROM `visual` WHERE `trigger_id`=$triggerID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
		
	}
	function getModelsByVisualID($visualID){
		$query = "SELECT * FROM `model` WHERE `three_d_model_id`=$visualID";
		$result = $this->selectQueryForAssoc($query);
		return $result;
		
	}
	function getClientGroupTriggers(){
		$query = "SELECT * FROM `client_groups` WHERE `active` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;
		
	}
	
	function getAllProductOffers(){
		$query = "SELECT * FROM `offers`";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getClientById($clientID){
		$query = "SELECT * FROM `client` WHERE `id`IN ('$clientID') AND `active` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getClientProductsById($clientID){
		$query = "SELECT * FROM `products` AS `p`, 
				`products_category` AS `pc`  
					WHERE  
						`p`.`pd_category_id` = `pc`.`pd_category_id` AND 
						`p`.`client_id`=$clientID";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getProductBackgroundById($pId){
		$query = "SELECT * FROM `products_background_view` WHERE `pd_id` = $pId AND `pd_bg_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getRelatedProdsById($pId){
		//$query = "SELECT GROUP_CONCAT(relatedto_id SEPARATOR ',') AS prodRelated FROM `product_related` WHERE `relatedfrom_id`=$pId";
		$query = "SELECT GROUP_CONCAT(DISTINCT relatedto_id ORDER BY relatedto_id ASC SEPARATOR ',') AS prodRelated FROM product_related WHERE `relatedfrom_id`=$pId";
		//$data = mysql_query($query);
		//print_r($data);
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getProductsById($pId){
		$query = "SELECT * FROM `products` AS `p`, 
				`products_category` AS `pc`  
					WHERE  
						`p`.`pd_category_id` = `pc`.`pd_category_id` AND 
						`p`.`pd_id`=$pId";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getAdditionalImagesByPdId($pdId){
		$query = "SELECT pd_additional_images FROM `products_addtional_image` WHERE `pd_id`IN ($pdId) AND `pd_additional_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function validateUserLogin($pUsername, $pPassword){
		$query = "SELECT * FROM `users` AS `u`, `user_group` AS `ug` WHERE  
				 `u`.`user_group_id` = `ug`.`user_group_id` 
			AND `u`.`user_username` = '$pUsername' 
			AND `u`.`user_password` = MD5('$pPassword') 
			AND `u`.`user_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getUserDetailsByUid($pUid){
		$query = "SELECT * FROM `users` AS `u`, `user_details` AS `ud`, `user_group` AS `ug` WHERE  
				`u`.`user_id` = `ud`.`user_id` 
			AND `u`.`user_group_id` = `ug`.`user_group_id` 
			AND `u`.`user_id` = '$pUid' 
			AND `u`.`user_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getUserDetailsByUname($pUname){
		$query = "SELECT * FROM `users` AS `u`, `user_details` AS `ud`, `user_group` AS `ug` WHERE  
				`u`.`user_id` = `ud`.`user_id` 
			AND `u`.`user_group_id` = `ug`.`user_group_id` 
			AND `u`.`user_username` = '$pUname' 
			AND `u`.`user_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getUserWishlistsByUid($pUid){
		$query = "SELECT * FROM `wishlist` WHERE `user_id` = $pUid AND `wishlist_status` = 1 ORDER BY `wishlist_created_date` DESC";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getWishlistsProdIDs($pWishlistId){
		$query = "SELECT * FROM `wishlist_details` WHERE `wishlist_id` = $pWishlistId AND `wishlist_details_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getMyOfferIdByUid($pUid){
		$query = "SELECT * FROM `my_offers` WHERE `user_id` = $pUid AND `my_offers_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getMyOfferDetailsByOffID($pOfferID){
		$query = "SELECT * FROM `my_offers_reference` AS `mr`, `offers` AS `o`, `client` AS `c`, `offers_discount_type` AS `odt` WHERE  
				`mr`.`offer_id` = `o`.`offer_id`  
			AND `mr`.`my_offers_id` = '$pOfferID' 
			AND `o`.`client_id` = `c`.`id` 
			AND `odt`.`offer_discount_type_id` = `o`.`offer_discount_type_id` 
			AND `mr`.`my_offers_ref_status` = 1 
			AND `o`.`offer_status` = 1";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getMyOfferOnlyByID($pOfferID){
		$query = "SELECT * FROM `my_offers_reference` AS `mr`, `offers` AS `o` WHERE  
				`mr`.`offer_id` = `o`.`offer_id`  
			AND `mr`.`my_offers_id` = '$pOfferID'";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function checkIfMyOfferExists($pUserId){
		$query = "SELECT * FROM `my_offers` WHERE `user_id` = $pUserId";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function checkIfMyOfferRefExists($pMyOfferID, $pOfferID){
		$query = "SELECT * FROM `my_offers_reference` WHERE `my_offers_id` = $pMyOfferID AND `offer_id` = $pOfferID";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getOfferInfoByOfferId($pOfferID){
		 $query = "SELECT * FROM `offers_info` AS `oi`, `client` AS `c` WHERE 
		`oi`.`client_id` = `c`.`id` AND 
		`offer_id` = $pOfferID";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getOfferByOfferId($pOfferID){
		$query = "SELECT * FROM `offers` WHERE `offer_id` = $pOfferID";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getWishlistByName($pWishlistName){
		$query = "SELECT * FROM `wishlist` WHERE `wishlist_name` = '$pWishlistName'";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getWishlistById($pWishlistId){
		$query = "SELECT * FROM `wishlist` WHERE `wishlist_id` = $pWishlistId";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getUserDetails($pUid){
		$query = "SELECT * FROM `users` AS `u`, `user_details` AS `ud` WHERE 
			`u`.`user_id` = `ud`.`user_id` AND 
			`u`.`user_id` = $pUid";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	
	function getWishlistProds($pWishlistId){
		$query = "SELECT GROUP_CONCAT(DISTINCT pd_id ORDER BY pd_id ASC SEPARATOR ',') AS productIds FROM wishlist_details WHERE `wishlist_id`=$pWishlistId";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function checkIfWishlistProdExists($pWishlistId, $pdId){
		$query = "SELECT * FROM `wishlist_details` WHERE `wishlist_id` = $pWishlistId AND `pd_id` = $pdId ORDER BY `wishlist_details_id` DESC";
		$result = $this->selectQueryForAssoc($query);
		return $result;	
	}
	function getMyClosetByUid($pUid){
		$query = "SELECT * FROM `closet` WHERE `user_id` = $pUid AND `closet_status`=1 ORDER BY `closet_created_date` DESC";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function checkIfClosetExistsByUid($pUid,$pdId){
		$query = "SELECT * FROM `closet` WHERE `user_id` = $pUid AND `pd_id`=$pdId";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function checkIfUsernameExistsByUname($pUname){
		$query = "SELECT * FROM `users` WHERE `user_username` = '$pUname'";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>