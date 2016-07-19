<?php
/**** Include SQLQuery class for Database connection and main function ****/
require_once SRV_ROOT.'model/SQLQuery.class.php'; 
class mUsers extends SQLQuery {
	protected $_model;

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
	function getAllCMSUsers()
	{
	    $query = "SELECT * FROM `seemore_users` AS `su` , `seemore_user_groups` AS `sug` WHERE `su`.`u_group_id`=`sug`.`group_id` ORDER BY `su`.`created_date`";
		$result = $this->selectQuery($query);
		return $result;
	}
	public function getAllCmsUserGroups()
	{
		//$query = "SELECT * FROM user_table ORDER BY id DESC"; 
		$query = "SELECT * FROM seemore_user_groups"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}

	function getUsersProfile($pUid)
	{
		$query = "SELECT * FROM  `seemore_users` AS `su`,`seemore_user_groups` AS `sug` WHERE `su`.`u_group_id`=`sug`.`group_id` AND `su`.`u_id` = $pUid";
		$result = $this->selectQuery($query);
		return $result;
	}
	public function getAppUsersProfile($pUid)
	{
		//$query = "SELECT * FROM user_table ORDER BY id DESC"; 
		$query = "SELECT ut.id,ut.first_name,ut.last_name,ut.username,ut.password,gt.name,gt.id AS groupid FROM user_table AS ut, group_user AS gu, group_table AS gt WHERE gu.user_id = ut.id AND gu.group_id = gt.id AND ut.id=$pUid ORDER BY ut.id DESC"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	public function getAllGroups()
	{
		//$query = "SELECT * FROM user_table ORDER BY id DESC"; 
		$query = "SELECT * FROM group_table"; 
		$result = $this->selectQueryForAssoc($query);
		return $result;
	}
	
	function getUsersProfileByEmail($pEmail)
	{
		$query = "SELECT * FROM `seemore_users` WHERE `u_email`='$pEmail'";
		$result = $this->selectQuery($query);
		return $result;
	}
	function checkEmailAvailable($pUid, $pEmail)
	{
		$query = "SELECT * FROM `seemore_users` WHERE `u_email`='$pEmail' AND `u_id` != $pUid";
		$result = $this->selectQuery($query);
		return $result;
	}
	function getUsersTempPasswd($pActivation)
	{
		$query = "SELECT * FROM `seemore_users` WHERE `u_passwd_activation`='$pActivation'";
		$result = $this->selectQuery($query);
		return $result;
	}
	function getAppUsersWishlist($auid)
	{
		$query = "SELECT ut.id,ut.first_name,ut.last_name,ut.username,wl.name,wl.shared,wli.product_id,p.client_id,p.title,p.image,p.barcode,c.name AS client_name FROM user_table AS ut, wish_list AS wl, wish_list_item AS wli,product AS p,client AS c WHERE wl.user_id = ut.id AND wl.id = wli.wish_list_id AND wli.product_id=p.id AND p.client_id=c.id AND ut.id=$auid  ORDER BY ut.id DESC";
		$result = $this->selectQuery($query);
		return $result;
	}
	
	function __destruct() {
	}
}
?>