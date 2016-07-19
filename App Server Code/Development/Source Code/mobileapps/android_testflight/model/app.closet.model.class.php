<?php

/**** Include SQLQuery class for Database connection and main function ****/

require_once SRV_ROOT.'model/SQLQuery.class.php'; 

class mCloset extends SQLQuery {

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

	

	public function checkingClosetValues($cArray)

	{

		$query = "SELECT * FROM closet WHERE user_id='".$cArray['user_id']."' AND pd_id=".$cArray['pd_id']." AND closet_status = 1  LIMIT 0, 1";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function getClosetProductsValues($cValsArray)

	{

		$query = "SELECT c.*,pd.*,cl.name AS client_name, cl.background_color, cl.light_color, cl.dark_color, cl.logo AS client_logo FROM closet AS c 

		LEFT JOIN products AS pd ON pd.pd_id = c.pd_id LEFT JOIN client AS cl ON pd.client_id = cl.id WHERE c.closet_status=1 AND c.user_id=".$cValsArray['userId']." AND pd.pd_id!=''";

		if($cValsArray['closetSelStatus']!=0){

			$query .= " AND c.closet_selection_status=".$cValsArray['closetSelStatus'];

		}

		$query .= "  ORDER BY closet_created_date DESC";

		//echo $query;

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	public function getUserWithProductsWishListAndCloset($cValsArray)

	{

		$query = "SELECT pd_id, pd_name, client_id, pd_image, closet_selection_status, wishlist_id, client_name, closet_created_date FROM ((SELECT pd.pd_id, pd.pd_name, pd.client_id, pd.pd_image, IF('closet_selection_status' IS NULL, '', '0') AS closet_selection_status, wl.wishlist_id, cl.name AS client_name, IF('closet_created_date' IS NULL, '', NOW()) AS closet_created_date FROM `wishlist` AS wl LEFT JOIN wishlist_details AS wld ON wld.wishlist_id=wl.wishlist_id AND wld.wishlist_details_status=1 LEFT JOIN products AS pd ON pd.pd_id=wld.pd_id AND pd.pd_status=1 LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 WHERE wl.user_id=".$cValsArray['userId']." AND wl.wishlist_status=1) UNION ALL (SELECT pd.pd_id, pd.pd_name, pd.client_id, pd.pd_image, c.closet_selection_status, IF('wishlist_id' IS NULL, '', '0') AS wishlist_id, cl.name AS client_name, c.closet_created_date FROM closet AS c LEFT JOIN products AS pd ON pd.pd_id=c.pd_id AND pd.pd_status=1 LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 WHERE c.closet_status=1 AND c.user_id=".$cValsArray['userId']; 

		/*if($cValsArray['closetSelStatus']!=0){

			$query .= " AND c.closet_selection_status=".$cValsArray['closetSelStatus'];

		}*/

		$query .= " )) products WHERE pd_id!='' ";

		/*if($cValsArray['closetSelStatus']!=0){

			$query .= " AND closet_selection_status=".$cValsArray['closetSelStatus'];

		}

		 */

		$query .= " GROUP BY pd_id ORDER BY closet_created_date DESC";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	public function getUserWithProductsWishList($cValsArray)

	{

		$query = "SELECT pd.pd_id, pd.pd_name, pd.pd_image, pd.client_id, cl.name AS client_name, wl.wishlist_id, IF('closet_selection_status' IS NULL, '', '0') AS closet_selection_status, IF('closet_created_date' IS NULL, '', '0') AS closet_created_date, cl.logo AS client_logo, cl.background_color, cl.light_color, cl.dark_color FROM `wishlist` AS wl LEFT JOIN wishlist_details AS wld ON wld.wishlist_id=wl.wishlist_id AND wld.wishlist_details_status=1 LEFT JOIN products AS pd ON pd.pd_id=wld.pd_id AND pd.pd_status=1 LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 WHERE wl.user_id=5 AND wl.wishlist_status=1 AND pd.pd_id!='' GROUP BY pd.pd_id ORDER BY wishlist_details_id DESC";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	public function getUserWithProductsCloset($cValsArray)

	{

		$query = "SELECT pd.pd_id, pd.pd_name, pd.pd_image, pd.client_id, cl.name AS client_name, IF('wishlist_id' IS NULL, '', '0') AS wishlist_id, c.closet_selection_status, c.closet_created_date, cl.logo AS client_logo, cl.background_color, cl.light_color, cl.dark_color FROM closet AS c LEFT JOIN products AS pd ON pd.pd_id=c.pd_id AND pd.pd_status=1 LEFT JOIN client AS cl ON cl.id=pd.client_id AND cl.active=1 WHERE c.closet_status=1 AND c.user_id=5 ORDER BY c.closet_created_date DESC";

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	

	function getClosetProductsByBrands($cValsArray)

	{

		

		//$query = "SELECT * FROM  `product` WHERE id IN (".$arrproductId.")";

		$query = "SELECT pd . * , cl.name AS client_name, cl.id AS client_id, cl.logo AS client_logo, cl.background_color, cl.light_color, cl.dark_color FROM  products AS pd LEFT JOIN  `client` AS cl ON cl.id = pd.client_id WHERE pd.pd_id IN (".$cValsArray['productId'].")";

		//echo $query;

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	

	function getClosetProductsForBrands($cValsArray)

	{

		$query = "SELECT c.*,pd.*,cl.name AS client_name, cl.logo AS client_logo, cl.background_color, cl.light_color, cl.dark_color FROM closet AS c 
		LEFT JOIN products AS pd ON pd.pd_id = c.pd_id LEFT JOIN client AS cl ON pd.client_id = cl.id WHERE c.closet_status=1 AND c.user_id=".$cValsArray['userId']." AND pd.pd_id!=''  ORDER BY  `cl`.`name` ASC ";

		//echo $query;

		$result = $this->selectQueryForAssoc($query);

		return $result;

	}

	function __destruct() {

		//pg_close($this->con); 

	}

}

?>