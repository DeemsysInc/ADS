<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mProductWishlist extends SQLQuery {
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
	
	public function getUserWishListProducts($userId,$wishListName)
	{
		$query = "SELECT wl.wishlist_id, wl.wishlist_name, wl.user_id, wld.wishlist_details_id, wld.pd_id AS prodId, 
		pd.client_id, pd.pd_name, pd.pd_image, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon FROM `wishlist` AS wl 
		LEFT JOIN wishlist_details AS wld ON wld.wishlist_id=wl.wishlist_id AND wld.wishlist_details_status=1 
		LEFT JOIN products AS pd ON pd.pd_id=wld.pd_id AND pd.pd_status=1 
		WHERE wl.user_id=".$userId." AND wl.wishlist_status=1 ";
		if($wishListName!=""){
			$query .= " AND wl.wishlist_name='".$wishListName."' ";
		}
		else if($wishListName==""){
			$query .= " AND wl.wishlist_name=(SELECT wishlist_name FROM wishlist WHERE user_id=".$userId." AND wishlist_status=1 ORDER BY wishlist_id ASC LIMIT 0, 1) ";
		}		
		$query .= " GROUP BY wld.pd_id ORDER BY wl.wishlist_id ASC";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getUserAllWishListProducts($userId,$wishListName)
	{
		$query = "SELECT wl.wishlist_id, wl.wishlist_name, wl.user_id, wld.wishlist_details_id, wld.pd_id AS prodId, 
		pd.client_id, pd.pd_name, pd.pd_image, pd.pd_url, pd.pd_description, pd.pd_price, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon FROM `wishlist` AS wl 
		LEFT JOIN wishlist_details AS wld ON wld.wishlist_id=wl.wishlist_id AND wld.wishlist_details_status=1 
		LEFT JOIN products AS pd ON pd.pd_id=wld.pd_id AND pd.pd_status=1 
		WHERE wl.user_id=".$userId." AND wld.pd_id!='' AND wl.wishlist_status=1 ";
		if($wishListName!=""){
			$query .= " AND wl.wishlist_name='".$wishListName."' ";
		}
		else if($wishListName==""){
			$query .= " AND wl.wishlist_name=(SELECT wishlist_name FROM wishlist WHERE user_id=".$userId." AND wishlist_status=1 ORDER BY wishlist_id ASC LIMIT 0, 1) ";
		}		
		$query .= " GROUP BY wld.pd_id ORDER BY wl.wishlist_id ASC";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getYourClosetFromWishListProducts($userId)
	{
		$query = "SELECT wl.wishlist_id, wl.wishlist_name AS wishListName, pd.client_id, pd.pd_name, pd.pd_image, pd.pd_id AS prodId, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon FROM wishlist AS wl LEFT JOIN wishlist_details AS wld ON wld.wishlist_id=wl.wishlist_id AND wld.wishlist_details_status=1 LEFT JOIN products AS pd ON pd.pd_id=wld.pd_id AND pd.pd_status=1 WHERE wl.user_id=".$userId." AND wl.wishlist_status=1 AND wld.pd_id!=0 GROUP BY wl.wishlist_id";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	public function checkingWishListName($wArray)
	{
		$query = "SELECT * FROM `wishlist` WHERE wishlist_name='".$wArray['wishlist_name']."' AND user_id=".$wArray['user_id']."";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	public function checkingWishListWithProduct($wArray)
	{
		$query = "SELECT * FROM `wishlist_details` WHERE wishlist_id=".$wArray['wishlist_id']." AND pd_id=".$wArray['pd_id']."";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	public function getUserRelatedWishListNames($loggedInUserId)
	{
		$query = "SELECT * FROM `wishlist` WHERE user_id=".$loggedInUserId." AND wishlist_status=1 ORDER BY wishlist_id ASC";
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function delete($pTableName, $con)
	{
		return $this->query_delete($pTableName, $con);
	}
	
	public function getFriendsWishListProducts($userId,$wishListId)
	{
		 $query = "SELECT cl.name AS  `client_name` , wl.id,wl.shared,wli.id AS `item_id`, wl.name AS wishListName, pd.client_id,pd.price, pd.title, pd.image, pd.id AS prodId, IFNULL(pd.pd_istryon, '0' ) AS pd_istryon FROM  `wish_list` AS wl LEFT JOIN wish_list_item AS wli ON wli.wish_list_id=wl.id LEFT JOIN product AS pd ON pd.id=wli.product_id AND pd.active=1 LEFT JOIN  `client` AS cl ON cl.id = pd.client_id WHERE wl.user_id !='".$userId."'  AND wl.id='".$wishListId."' AND  wl.shared = 1 GROUP BY wli.product_id ORDER BY wli.product_id DESC";
		
		/*$query ="SELECT cl.name AS  `client_name` , wl.id, wl.shared, wli.id AS  `item_id` , wl.name AS wishListName, pd.client_id, pd.title, pd.image, pd.id AS prodId
FROM  `wish_list` AS wl LEFT JOIN wish_list_item AS wli ON wli.wish_list_id = wl.id LEFT JOIN product AS pd ON pd.id = wli.product_id AND pd.active =1
LEFT JOIN  `client` AS cl ON cl.id = pd.client_id AND pd.active =1 WHERE wl.user_id !='".$userId."' AND wl.id ='".$wishListId."'  AND wl.shared =1 GROUP BY wli.product_id ORDER BY wli.product_id DESC ";
*/
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getFriendsRelatedWishListNames($loggedInUserId)
	{
		//$query = "SELECT * FROM  `wish_list` WHERE user_id != '".$loggedInUserId."' AND  wl.shared = 1 ORDER BY id ASC";
		/*$query = "SELECT * , (SELECT COUNT( * ) FROM wish_list_item AS wli WHERE wli.wish_list_id = wl.id ) AS count FROM  `wish_list` AS  `wl`  WHERE wl.user_id !='".$loggedInUserId."' AND wl.shared =1 ORDER BY wl.id ASC ";*/
		
		$query = "SELECT wl.* ,`u`.first_name,`u`.last_name,`u`.username,`u`.username,`u`.email_id,(SELECT COUNT( * ) FROM wish_list_item AS wli WHERE wli.wish_list_id = wl.id) AS count FROM  `wish_list` AS  `wl` LEFT JOIN  `user_table` AS  `u` ON  `u`.id = wl.user_id WHERE wl.user_id !='".$loggedInUserId."' AND wl.shared =1 ORDER BY wl.id ASC";
				$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getYourClosetUserWishListProducts($loggedInUserId, $closetImgProdId)
	{
		$query = "SELECT * FROM wish_list AS wl LEFT JOIN wish_list_item AS wli ON wli.wish_list_id=wl.id AND wli.product_id=".$closetImgProdId." WHERE wl.user_id=".$loggedInUserId;
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	function __destruct() {
		//pg_close($this->con); 
	}
}
?>