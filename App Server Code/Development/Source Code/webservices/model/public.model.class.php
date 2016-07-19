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
	
	//start
	public function getproductanalytics($sendArray)
	{
	    try
		{
		   $query = "SELECT count(pa.`pd_id`) AS productviews FROM  `products_analytics` AS pa WHERE pa.`client_id` IN('".$sendArray['search_client_id']."') AND pa.pd_datapoint_id!=4 AND pa.pd_datapoint_id!=3 AND  DATE_FORMAT(pa.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";
			$result = $this->selectQuery($query);
		    return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	//end
	
	 public function getAllOnlineUsers()
	{
	    try
		{
		   // $query = "SELECT *,NOW(),COUNT(DISTINCT user_id) FROM `home_analytics` WHERE `home_created_date` > NOW() - INTERVAL 5 MINUTE  ORDER BY `home_created_date` DESC ";
		  $query = "SELECT COUNT(DISTINCT user_id) AS onlineUsers FROM `home_analytics` WHERE `home_created_date` > NOW() - INTERVAL 5 MINUTE  ORDER BY `home_created_date` DESC "; 
			$result = $this->selectQuery($query);
			$result = $this->selectQuery($query);
		    return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	} 
	
	public function getAllDownloads($sendArray)
	{
	    try
		{
		 $query = "SELECT count(`da`.`downloads_analytics_id`) AS `totalDownloads` FROM  `downloads_analytics` AS `da` WHERE DATE_FORMAT(`da`.`created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `da`.`device_type` NOT LIKE '%Simulator%'";
			$result = $this->selectQuery($query);
		    return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getAllDownloadsByDate($sendArray)
	{
	    try
		{
		    $query = "SELECT * FROM  `downloads_analytics` AS `da` WHERE DATE_FORMAT(`da`.`created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `da`.`device_type` NOT LIKE '%Simulator%'";
			$result = $this->selectQuery($query);
		    return $result;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	public function getClientCampaignsAllProductsForDashboardGraph($sendArray)
	{
		try 
		{
		  
		  $query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` AS `pa` WHERE `pa`.`pd_datapoint_id`!=4 AND `pa`.`pd_datapoint_id`!=3 AND `pa`.`user_id`!=0 AND  `pa`.`pd_id` IN(".$sendArray['campaign_product_ids'].") AND   DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";
			$result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	} 
  public function getClientCampaignsProductsForDashboardGraph($sendArray)
	{
		try 
		{
		  
    $query = "SELECT count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics` AS `pa` WHERE `pa`.`pd_datapoint_id`!=4 AND `pa`.`pd_datapoint_id`!=3 AND `pa`.`user_id`!=0 AND `pa`.`pd_id`!='' AND  `pa`.`pd_id` IN('".$sendArray['campaign_product_ids']."') AND   DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";
			$result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}  
	
	public function getClientCampaignsAllClosetProductDetailsForDashboard($sendArray)
	{
		try 
		{  
		//print_r($sendArray);
		//if(!empty($sendArray['search_campaign_id'])&& !empty($sendArray['search_client_id']))
			//{
    $query = "SELECT count(`ca`.`product_id`) AS 'closetViews'  FROM `closet_analytics` as `ca`  WHERE `ca`.`user_id`!=0  AND `ca`.`product_id` IN( '".$sendArray['campaign_product_ids']."') AND `ca`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`ca`.`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";  
		    //}
		  $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignsAllWishlistProductDetailsForDashboard($sendArray)
	{
		try 
		{  
	 $query = "SELECT count(`wa`.`product_id`) AS 'wishlistViews'  FROM `wishlist_analytics` as `wa`  WHERE `wa`.`user_id`!=0  AND `wa`.`product_id` IN('".$sendArray['campaign_product_ids']."') AND `wa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`wa`.`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	public function getClientCampaignsAllOffersForDashboard($sendArray)
	{
		try 
		{   
		
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` as `moa`  WHERE `moa`.`datapoint_id`!=10  AND `moa`.`user_id`!=0  AND `moa`.`offer_id`!='' AND `moa`.`offer_id` LIKE '".$sendArray['campaign_offer_ids']."' AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE `datapoint_id`!=10 AND `user_id`!=0   AND `offer_id`!='' AND `offer_id` LIKE '".$sendArray['campaign_offer_ids']."' AND DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    
			}
			//echo $query;
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignsOffersByDpIdOfferId($sendArray)
	{
		try 
		{   
     $query = "SELECT count(`myoffers_ids`) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `myoffers_ids` LIKE '%|".$sendArray['campaign_offer_ids']."|%' AND `user_id`!=0 AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignsShareProductsForDashboardGraph($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` AS `pa` WHERE `pa`.`user_id`!=0 AND  `pa`.`pd_id` IN('".$sendArray['campaign_product_ids']."') AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` AS `pa` WHERE `pa`.`user_id`!=0  AND  `pa`.`pd_id` IN('".$sendArray['campaign_product_ids']."') AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			//echo $query;
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignsShareOffersForDashboardGraph($sendArray)
	{
	
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` as `moa` WHERE `moa`.`user_id`!=0   AND `moa`.`offer_id` IN('".$sendArray['campaign_offer_ids']."') AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				  $query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!=0 AND `offer_id` IN('".$sendArray['campaign_offer_ids']."') AND   DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			//echo $query;
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaigns($sendArray)
	{
		try 
		{	
		
		$query = "SELECT * FROM  `client_campaigns` AS `cc`,`client` AS `c` WHERE   `cc`.`client_id` = `c`.`id` AND cc.campagin_status=1";  
			$result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignsInfoByIds($sendArray)
	{
		try 
		{
		
		    if($sendArray['group_id']==3)
			{
			 if(!empty($sendArray['search_campaign_id'])){
		    	$query = "SELECT * FROM `client_campaigns` WHERE `client_id` IN (".$sendArray['search_client_id'].") AND `campaign_id` IN (".$sendArray['search_campaign_id'].")";    
		       }
			    else{
				$query = "SELECT * FROM `client_campaigns` WHERE `client_id` IN (".$sendArray['search_client_id'].") ";   
				}
		    
		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))//admin and super admin
		    {
			  if(!empty($sendArray['search_campaign_id'])){
		    	$query = "SELECT * FROM `client_campaigns` WHERE `client_id` IN (".$sendArray['search_client_id'].") AND `campaign_id` IN (".$sendArray['search_campaign_id'].")";    
		       }
			   else{
			   $query = "SELECT * FROM `client_campaigns` WHERE `client_id` IN (".$sendArray['search_client_id'].") "; 
			   }
		    }
			//echo $query;
			
            $result = $this->selectQuery($query);
		    return $result;
		}
		
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignsDates($sendArray)
	{
		try 
		{
            $query = "SELECT * FROM  `client_campaigns` AS `cc` WHERE   `cc`.`campaign_id`='".$sendArray['campaign_id']."'";
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignDetails($sendArray)
	{
		try 
		{
            $query = "SELECT * FROM  `client_campaigns` AS `cc`,`client` AS `c` WHERE  `cc`.`client_id` = `c`.`id` AND  `cc`.`client_id`='".$sendArray['client_id']."'";
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignDetailsByCID($sendArray)
	{
		try 
		{
            $query = "SELECT * FROM  `client_campaigns` AS `cc`,`client` AS `c` WHERE  `cc`.`client_id` = `c`.`id` AND  `cc`.`campaign_id`='".$sendArray['campaign_id']."'";
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getAllDownloadsTrackingByVersion($sendArray)
	{
		try 
		{
			$query = "SELECT count(*) as `totalDownloads` FROM  `downloads_analytics`  WHERE   `build_number` LIKE  '".$sendArray['build_number']."'";
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientActualProducts($sendArray)
	{
		try 
		{
			if($sendArray['group_id']==3)
			{
				$query = "SELECT `p`.`pd_id` FROM  `products` AS `p`,`client` AS `c` WHERE   `p`.`client_id` = `c`.`id`  AND `p`.`client_id`=".$sendArray['login_client_id'];
		    				
		    
		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
		    {
		    	$query = "SELECT `p`.`pd_id` FROM  `products` AS `p`,`client` AS `c` WHERE   `p`.`client_id` = `c`.`id`  AND `p`.`client_id`=".$sendArray['search_client_id'];
		    	
		    }
			$result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientActualOffers($sendArray)
	{
		try 
		{
			if($sendArray['group_id']==3)
			{
				$query = "SELECT `offer_id` FROM  `offers` AS `o`,`client` AS `c` WHERE   `o`.`client_id` = `c`.`id`  AND `o`.`client_id`=".$sendArray['login_client_id'];
		    				
		    
		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
		    {
		    	$query = "SELECT `offer_id` FROM  `offers` AS `o`,`client` AS `c` WHERE   `o`.`client_id` = `c`.`id`  AND `o`.`client_id`=".$sendArray['client_search_id'];
		    	
		    }
			$result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientInfoByCID($sendArray)
	{
		try 
		{
		    if($sendArray['group_id']==3)//client
			{
				$query = "SELECT `id`,`name`,logo FROM `client` WHERE `id` IN ('".$sendArray['search_client_id']."')";    
		    
		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))//2=admin ,1=super admin and sales manager=6
		    {
		    	$query = "SELECT `id`,`name`,logo FROM `client` WHERE `id` IN ('".$sendArray['search_client_id']."')";    
		    
		    }
            $result = $this->selectQuery($query);
		    return $result;
		}
		
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
    public function getTotalClients()
	{
		try 
		{
			$query = "SELECT `id`,`name` FROM `client` order by `name` ASC ";  
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientProducts($sendArray)
	{
		try 
		{
			if($sendArray['group_id']==3)
			{
				
				//$query = "SELECT `c`.`name` as `client_name`,`pa`.`client_id` as `client_id`, count(`pa`.`pd_id`) as `no_of_products` FROM `products_analytics` AS `pa`, `client` AS `c` WHERE DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` != 0 AND `pa`.`client_id` = `c`.`id` AND `pa`.`client_id` IN (".$sendArray['login_client_id'].") GROUP BY `pa`.`client_id`";
				$query = "SELECT  `pa`.`pd_id`,`pa`.`user_id` FROM `products_analytics`  AS `pa` WHERE `pa`.`user_id`!=0 AND `pa`.`pd_datapoint_id`!=4 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` != 0 AND `pa`.`client_id` IN (".$sendArray['login_client_id'].") ";  
  

		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
		    {

		        //$query = "SELECT `c`.`name` as `client_name`,`pa`.`client_id` as `client_id`, count(`pa`.`pd_id`) as `no_of_products` FROM `products_analytics` AS `pa`, `client` AS `c` WHERE DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` != 0 AND `pa`.`client_id` = `c`.`id` GROUP BY `pa`.`client_id`";
		        $query = "SELECT  `pa`.`pd_id`,`pa`.`user_id` FROM `products_analytics` AS `pa` WHERE `pa`.`user_id`!=0 AND `pa`.`pd_datapoint_id`!=4 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` != 0 AND `pa`.`client_id` IN (".$sendArray['search_client_id'].")  ";  
  
		    }
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsUsersByCID($sendArray)
	{
		try 
		{   
			$query = "SELECT user_id FROM `products_analytics`  WHERE DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `client_id` = '".$sendArray['client_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersUsersByCID($sendArray)
	{
		try 
		{   
			$query = "SELECT user_id FROM `my_offers_analytics`  WHERE DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `client_id` = '".$sendArray['client_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsByCID($sendArray)
	{
		try 
		{   
			$query = "SELECT pd_id FROM `products_analytics`  WHERE DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `client_id` = '".$sendArray['client_id']."' GROUP BY `pd_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsViewsByCID($sendArray)
	{
		try 
		{   
			$query = "SELECT pd_id FROM `products_analytics`  WHERE DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `client_id` = '".$sendArray['client_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersViewsByCID($sendArray)
	{
		try 
		{   
			$query = "SELECT offer_id FROM `my_offers_analytics`  WHERE DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `client_id` = '".$sendArray['client_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsViewsByPID($sendArray)
	{
		try 
		{   
			$query = "SELECT pd_id FROM `products_analytics`  WHERE DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `pd_id` = '".$sendArray['product_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersViewsByOfferID($sendArray)
	{
		try 
		{   
			$query = "SELECT offer_id FROM `my_offers_analytics`  WHERE DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `offer_id` = '".$sendArray['offer_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getClientProductsDetails($sendArray)
	{
		try 
		{   
		    $query = "SELECT `pa`.`pd_id` AS 'productId', count(`pa`.`pd_id`) AS `productViews`, `p`.`pd_name` AS `productName`, `p`.`pd_image`, `p`.`client_id`, `pa`.`pd_datapoint_id` FROM `products_analytics` AS `pa`, `products` AS `p` WHERE `pa`.`client_id` = ".$sendArray['client_id']." AND `pa`.`pd_id`!=0 AND `pa`.`pd_id` = `p`.`pd_id`  AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' GROUP BY `pa`.`pd_id`";  
		    //$query = "SELECT `p`.`pd_id`, `p`.`client_id`, `p`.`pd_image`,`p`.`pd_name` FROM `products` AS `p` WHERE `p`.`client_id` = '".$sendArray['client_id']."'" AND `p`.`pd_status` !=0;  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersDetails($sendArray)
	{
		try 
		{   

		    // $query = "SELECT `pa`.`pd_id` AS 'productId', count(`pa`.`pd_id`) AS `productViews`, `p`.`pd_name` AS `productName`, `p`.`pd_image`, `p`.`client_id`, `pa`.`pd_datapoint_id` FROM `products_analytics` AS `pa`, `products` AS `p` WHERE `pa`.`client_id` = ".$sendArray['client_id']." AND `pa`.`pd_id`!=0 AND `pa`.`pd_id` = `p`.`pd_id`  AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' GROUP BY `pa`.`pd_id`";  
		    $query = "SELECT `o`.`offer_id`, `o`.`client_id`, `o`.`offer_image`,`o`.`offer_name` FROM `offers` AS `o` WHERE `o`.`client_id` = '".$sendArray['client_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getProductInfoByProdId($sendArray)
	{
		try 
		{   

		    $query = "SELECT `p`.`pd_id`, `p`.`client_id`, `p`.`pd_image`,`p`.`pd_name`,`c`.`name` as `client_name`,`c`.`logo` FROM `products` AS `p`,`client` as `c` WHERE `p`.`pd_id` = '".$sendArray['product_id']."'  AND `p`.`client_id` = `c`.`id` AND `p`.`pd_status` !=0" ;  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getProductInfoByCampaignProdId($sendArray)
	{
		try 
		{   

		    $query = "SELECT `p`.`pd_id`, `cc`.`campaign_id`, `cc`.`client_id`,`p`.`pd_image`,`p`.`pd_name`,`cc`.`campaign_name`  FROM `products` AS `p`,`client_campaigns` as `cc` WHERE `p`.`pd_id` = '".$sendArray['campaign_product_ids']."'  AND `p`.`client_id` = `cc`.`client_id` AND `p`.`pd_status` !=0" ;  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientProductsDetailsByPId($sendArray)
	{
		try 
		{   

		    $query = "SELECT `pa`.`pd_id` AS 'productId', count(`pa`.`pd_id`) AS `productViews`, `p`.`pd_name` AS `productName`, `p`.`pd_image`, `p`.`client_id`, `pa`.`pd_datapoint_id` FROM `products_analytics` AS `pa`, `products` AS `p` WHERE `pa`.`pd_id` = ".$sendArray['product_id']." AND `pa`.`pd_id`!=0 AND `pa`.`pd_id` = `p`.`pd_id`  AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' GROUP BY `pa`.`pd_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientProductsForDashboard($sendArray)
	{
		try 
		{   if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` as `pa` WHERE  `pa`.`pd_id`!=0 AND `pa`.`client_id` IN (".$sendArray['login_client_id'].") AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_datapoint_id` IN ('4','6','7','8','18')";  
		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
		    {
		    	$query = "SELECT count(pd_id) AS 'productViews'  FROM `products_analytics` WHERE  `pd_id`!=0 AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pd_datapoint_id` IN ('4','6','7','8','18')";  
		    
		    }
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersForDashboard($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` as `moa` WHERE  `moa`.`user_id`!=0 AND`moa`.`offer_id`!=0 AND `moa`.`client_id` IN (".$sendArray['login_client_id'].") AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!=0 AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsForDashboardGraph($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` AS `pa` WHERE `pa`.`user_id`!=0 AND `pa`.`pd_id`!=0 AND `pa`.`client_id` IN ('".$sendArray['search_client_id']."') AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` AS `pa` WHERE `pa`.`user_id`!=0 AND `pa`.`pd_id`!=0 AND  `pa`.`client_id` IN ('".$sendArray['search_client_id']."') AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersForDashboardGraph($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` as `moa` WHERE `moa`.`user_id`!=0 AND `moa`.`offer_id`!=0 AND `moa`.`client_id` IN (".$sendArray['search_client_id'].") AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!=0 AND `client_id` IN (".$sendArray['search_client_id'].") AND   DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    
			}
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllProductsForDashboard($sendArray)
	{
		try 
		{  
		    if($sendArray['group_id']==3)
		    {
		    	$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` as `pa`  WHERE  `pa`.`pd_id`!=0 AND `pa`.`pd_datapoint_id` IN (2,3,4,5,6,7,8,18) AND `pa`.`client_id` IN (".$sendArray['login_client_id'].") AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    
		    }
		    else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
		    {
		    	$query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` as `pa` WHERE  `pa`.`pd_id`!=0 AND `pa`.`pd_datapoint_id`IN (2,3,4,5,6,7,8,18) AND  DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    

		    } 
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllMyOffers($sendArray)
	{
		try 
		{  
		    $query = "SELECT `moa`.`myoffers_ids`,`moa`.`user_id`,count(`moa`.`myoffers_ids`) AS 'offerViews' FROM `my_offers_analytics` as `moa`  WHERE `moa`.`user_id`!=0 AND `moa`.`myoffers_ids`!='' AND `moa`.`myoffers_ids` LIKE '%|".$sendArray['offer_id']."|%' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllMyOffersUsers($sendArray)
	{
		try 
		{  
		    $query = "SELECT `moa`.`user_id` FROM `my_offers_analytics` as `moa`  WHERE `moa`.`user_id`!=0 AND `moa`.`myoffers_ids`!='' AND `moa`.`myoffers_ids` LIKE '%|".$sendArray['offer_id']."|%' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' GROUP BY `moa`.`user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllClosetProducts($sendArray)
	{
		try 
		{  
		    $query = "SELECT `ca`.`prod_ids`,`ca`.`user_id` FROM `closet_analytics` as `ca`  WHERE  `ca`.`user_id`!=0 AND `ca`.`prod_ids`!='' AND `ca`.`prod_ids` LIKE '%|".$sendArray['product_id']."|%' AND `ca`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`ca`.`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllWishlistProducts($sendArray)
	{
		try 
		{  
		    $query = "SELECT `wa`.`prod_ids`,`wa`.`user_id` FROM `wishlist_analytics` as `wa`  WHERE  `wa`.`user_id`!=0 AND  `wa`.`prod_ids`!='' AND `wa`.`prod_ids` LIKE '%|".$sendArray['product_id']."|%' AND `wa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`wa`.`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllClosetProductsForDashboard($sendArray)
	{
		try 
		{  
		    $query = "SELECT count(`ca`.`prod_ids`) AS 'closetViews'  FROM `closet_analytics` as `ca`  WHERE `ca`.`user_id`!=0 AND `ca`.`prod_ids`!='' AND `ca`.`prod_ids` LIKE '%|".$sendArray['product_id']."|%' AND `ca`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`ca`.`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllClosetProductDetailsForDashboard($sendArray)
	{
		try 
		{  
	        $query = "SELECT count(`ca`.`product_id`) AS 'closetViews'  FROM `closet_analytics` as `ca`  WHERE `ca`.`user_id`!=0  AND `ca`.`product_id` LIKE '".$sendArray['product_id']."' AND `ca`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`ca`.`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllWishlistProductsForDashboard($sendArray)
	{
		try 
		{  
		    $query = "SELECT count(`wa`.`prod_ids`) AS 'wishlistViews'  FROM `wishlist_analytics` as `wa`  WHERE `wa`.`user_id`!=0 AND `wa`.`prod_ids`!='0' AND `wa`.`prod_ids` LIKE '%|".$sendArray['product_id']."|%' AND `wa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`wa`.`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllWishlistProductDetailsForDashboard($sendArray)
	{
		try 
		{  
		    $query = "SELECT count(`wa`.`product_id`) AS 'wishlistViews'  FROM `wishlist_analytics` as `wa`  WHERE `wa`.`user_id`!=0  AND `wa`.`product_id` LIKE '".$sendArray['product_id']."' AND `wa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`wa`.`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllClosetRemovedProductsForDashboard($sendArray)
	{
		try 
		{  
		    $query = "SELECT count(`ca`.`product_id`) AS 'removedViews'  FROM `closet_analytics` as `ca`  WHERE  `ca`.`product_id`!=0 AND `ca`.`product_id` = '".$sendArray['product_id']."' AND `ca`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`ca`.`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllProductsForDashboardGraph($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)//client login
			{
				 $query = "SELECT count(`pa`.`pd_id`) AS 'productViews'  FROM `products_analytics` AS `pa` WHERE `pa`.`pd_datapoint_id`!=4 AND `pa`.`pd_datapoint_id`!=3 AND `pa`.`user_id`!=0 AND `pa`.`pd_id`!=0 AND `pa`.`client_id` IN(".$sendArray['search_client_id'].") AND   DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";  
			}else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))//admin and super admin and sales manager
			{
			     $query = "SELECT count(pd_id) AS 'productViews'  FROM `products_analytics` WHERE `pd_datapoint_id`!=4 AND `pd_datapoint_id`!=3 AND `user_id`!=0 AND `pd_id`!=0 AND `client_id` IN (".$sendArray['search_client_id'].") AND DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";  
		    	
			}
			//echo $query;
             $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllOffersForDashboardGraph($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` AS `moa`  WHERE  `moa`.`offer_id`!=0 AND `moa`.`client_id` IN(".$sendArray['search_client_id'].") AND   DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` AS `moa` WHERE  `moa`.`offer_id`!=0 AND   DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."'";  
		    

			}
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getClientAllOffersForDashboard($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			{
				$query = "SELECT count(`moa`.`offer_id`) AS 'offerViews'  FROM `my_offers_analytics` as `moa`  WHERE `moa`.`datapoint_id`!=10  AND `moa`.`user_id`!=0 AND `moa`.`offer_id`!=0  AND `moa`.`client_id` IN(".$sendArray['search_client_id'].") AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    
			}
			else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))
			{
				$query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE `datapoint_id`!=10 AND `user_id`!=0 AND `offer_id`!=0 AND `client_id` IN(".$sendArray['search_client_id'].") AND DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";  
		    
			}
            $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientProductUsersByDpIds($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `products_analytics` WHERE `pd_datapoint_id`!=4 AND  `pd_id`!=0 AND `user_id`!=0 AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'  AND  `pd_id` = '".$sendArray['product_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientClosetUsersByDpIds($sendArray)
	{
		try 
		{   
            //$query = "SELECT user_id  FROM `closet_analytics` WHERE  `user_id`!=0 AND DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`IN (".$sendArray['datapoint_id'].") AND prod_ids LIKE '%|".$sendArray['product_id']."|%' GROUP BY `user_id`";  
		    $query = "SELECT user_id  FROM `closet_analytics` WHERE  `user_id`!=0 AND DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['product_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	

	public function getClientClosetByDpId($sendArray)
	{
		try 
		{   
            //$query = "SELECT  count(prod_ids) AS 'closetViews'  FROM `closet_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND prod_ids LIKE '%|".$sendArray['product_id']."|%'";  
		    $query = "SELECT  count(product_id) AS 'closetViews'  FROM `closet_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['product_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientWishlistByDpId($sendArray)
	{
		try 
		{   
            //$query = "SELECT  count(prod_ids) AS 'wishlistViews'  FROM `wishlist_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND prod_ids LIKE '%|".$sendArray['product_id']."|%'";  
		    $query = "SELECT  count(product_id) AS 'wishlistViews'  FROM `wishlist_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['product_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	//offers flow
	public function getClientOfferUsersByDpIds($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `my_offers_analytics` WHERE `datapoint_id`!=10 AND `datapoint_id`!=19 AND `datapoint_id`!=31 AND `offer_id`!=0 AND `user_id`!=0 AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'   AND `offer_id` = '".$sendArray['offer_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientMyOffersUsersByDpIds($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND myoffers_ids LIKE '%|".$sendArray['offer_id']."|%' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    public function getClientOffersByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE `user_id`!=0 AND  `offer_id`!=0  AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND `user_id`='".$sendArray['user_id']."' AND `offer_id` = '".$sendArray['offer_id']."'";  
		   
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientMyOffersByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT  count(myoffers_ids) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND `user_id`='".$sendArray['user_id']."' AND myoffers_ids LIKE '%|".$sendArray['offer_id']."|%'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersUsersByDpIdPdId($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `my_offers_analytics` WHERE  `offer_id`!=0 AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND `offer_id` = '".$sendArray['offer_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	///////




	public function getClientWishlistUsersByDpIds($sendArray)
	{
		try 
		{   

            //$query = "SELECT user_id  FROM `wishlist_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND prod_ids LIKE '%|".$sendArray['product_id']."|%' GROUP BY `user_id`";  
		    $query = "SELECT user_id  FROM `wishlist_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['product_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOfferUsersByDpId($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!='' AND `datapoint_id`!=10  AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `offer_id`='".$sendArray['offer_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientClosetProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

		    $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`closet_analytics` as `ca` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."'  AND '".$sendArray['end_date']."' AND `pa`.`pd_id` = '".$sendArray['product_id']."' AND `pa`.`session_id`=`ca`.`session_id` AND `pa`.`pd_id`!=0  AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientWishlistProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

		    $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`wishlist_analytics` as `wa` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` = '".$sendArray['product_id']."' AND `pa`.`session_id`=`wa`.`session_id` AND `pa`.`pd_id`!=0  AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllWishlistProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

            $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`wishlist_analytics` as `wa` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` = '".$sendArray['product_id']."' AND `pa`.`session_id`=`wa`.`session_id` AND `pa`.`pd_id`!=0  AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientAllClosetProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  
    
            $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`closet_analytics` as `ca` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."'  AND '".$sendArray['end_date']."' AND `pa`.`pd_id` = '".$sendArray['product_id']."' AND `pa`.`session_id`=`ca`.`session_id` AND `pa`.`pd_id`!=0  AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsByDpIdPid($sendArray)
	{
		try 
		{  

		    $query = "SELECT `pd_id` ,count(pd_id) AS 'productViews'  FROM `products_analytics` WHERE `user_id`!=0 AND `pd_id` = ".$sendArray['product_id']." AND `pd_id`!=0 AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pd_datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	public function getClientCampaignProductsByDpIdPid($sendArray)
	{
		try 
		{  

		    $query = "SELECT `pd_id` ,count(pd_id) AS 'productViews'  FROM `products_analytics` WHERE `user_id`!=0 AND `pd_id` = ".$sendArray['campaign_product_ids']." AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND `pd_datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignClosetByDpId($sendArray)
	{
		try 
		{   
		    $query = "SELECT  count(product_id) AS 'closetViews'  FROM `closet_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['campaign_product_ids']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignWishlistByDpId($sendArray)
	{
		try 
		{   
		    $query = "SELECT  count(product_id) AS 'wishlistViews'  FROM `wishlist_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['campaign_product_ids']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignClosetProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

            $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`closet_analytics` as `ca` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."'  AND '".$sendArray['campaign_end_date']."' AND `pa`.`pd_id` = '".$sendArray['campaign_product_ids']."' AND `pa`.`session_id`=`ca`.`session_id`  AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignAllClosetProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

            $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`closet_analytics` as `ca` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."'  AND '".$sendArray['start_date']."' AND `pa`.`pd_id` IN('".$sendArray['campaign_product_ids']."') AND `pa`.`session_id`=`ca`.`session_id`  AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignWishlistProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

		    $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`wishlist_analytics` as `wa` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND `pa`.`pd_id` = '".$sendArray['campaign_product_ids']."' AND `pa`.`session_id`=`wa`.`session_id`   AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignAllWishlistProdutDetailsFromPAnalytics($sendArray)
	{
		try 
		{  

		    $query = "SELECT `pa`.`pd_id` ,count(`pa`.`pd_id`) AS 'productViews' FROM `products_analytics`as `pa`,`wishlist_analytics` as `wa` WHERE `pa`.`user_id`!=0 AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['start_date']."' AND `pa`.`pd_id` IN('".$sendArray['campaign_product_ids']."') AND `pa`.`session_id`=`wa`.`session_id`   AND `pa`.`pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pa`.`device_type` NOT LIKE '%Simulator%'";  
		    $result = $this->selectQuery($query);
		    return $result;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignProductUsersByDpIds($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `products_analytics` WHERE `pd_datapoint_id`!=4 AND  `pd_id`!=0 AND `user_id`!=0 AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."'  AND  `pd_id` = '".$sendArray['campaign_product_ids']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignClosetUsersByDpIds($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `closet_analytics` WHERE  `user_id`!=0 AND DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['campaign_product_ids']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignWishlistUsersByDpIds($sendArray)
	{
		try 
		{   

            $query = "SELECT user_id  FROM `wishlist_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].") AND product_id LIKE '".$sendArray['campaign_product_ids']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffers($sendArray)
	{
		try 
		{   
			 if($sendArray['group_id']==3)
			 {
               $query = "SELECT  `moa`.`offer_id`, `moa`.`user_id`  FROM `my_offers_analytics` AS `moa` WHERE `moa`.`user_id`!=0 AND `moa`.`datapoint_id`!=10 AND `moa`.`datapoint_id`!=19 AND `moa`.`datapoint_id`!=31 AND DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `moa`.`offer_id` != 0  AND `moa`.`client_id` IN (".$sendArray['login_client_id'].") GROUP BY `moa`.`offer_id`"; 

			 }
			 else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))//admin and super admin
			 {
			 	//$query = "SELECT `c`.`name` as `client_name`,`pa`.`client_id` as `client_id`, count(`pa`.`pd_id`) as `no_of_products` FROM `products_analytics` AS `pa`, `client` AS `c` WHERE DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pa`.`pd_id` != 0 AND `pa`.`client_id` = `c`.`id` GROUP BY `pa`.`client_id`";
			 	$query = "SELECT  `moa`.`offer_id`, `moa`.`user_id`  FROM `my_offers_analytics` AS `moa` WHERE `moa`.`user_id`!=0 AND `moa`.`datapoint_id`!=10 AND `moa`.`datapoint_id`!=19 AND `moa`.`datapoint_id`!=31 AND DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `moa`.`offer_id` != 0  AND `moa`.`client_id` IN (".$sendArray['search_client_id'].")  GROUP BY `moa`.`offer_id`"; 
  
			 }
			 
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}


	
	public function getClientOffersByDpId($sendArray)
	{
		try 
		{   
            $query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!=0 AND DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND  `datapoint_id` IN (".$sendArray['datapoint_id'].") AND `offer_id`='".$sendArray['offer_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientOffersDetailsByOfferId($sendArray)
	{
		try 
		{   

		    $query = "SELECT `moa`.`offer_id` AS 'OfferId', count(`moa`.`offer_id`) AS `offerViews`, `o`.`offer_name` AS `offerName`, `o`.`offer_image`, `o`.`client_id`, `moa`.`datapoint_id` FROM `my_offers_analytics` AS `moa`, `offers` AS `o` WHERE `moa`.`offer_id` = ".$sendArray['offer_id']." AND `moa`.`offer_id`!=0 AND `moa`.`offer_id` = `o`.`offer_id`  AND DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' GROUP BY `moa`.`offer_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientOffersByDpIdOfferId($sendArray)
	{
		try 
		{   
            $query = "SELECT count(`myoffers_ids`) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `myoffers_ids` LIKE  '%|".$sendArray['offer_id']."|%' AND `myoffers_ids`!='' AND `user_id`!=0 AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id` IN (".$sendArray['datapoint_id'].")";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getClientCampaignOffersByDpId($sendArray)
	{
		try 
		{   
            $query = "SELECT count(offer_id) AS 'offerViews'  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!='' AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND  `datapoint_id` IN (".$sendArray['datapoint_id'].") AND `offer_id`='".$sendArray['campaign_offer_ids']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignAllMyOffers($sendArray)
	{
		try 
		{  
		    $query = "SELECT `moa`.`myoffers_ids`,`moa`.`user_id`,count(`moa`.`myoffers_ids`) AS 'offerViews' FROM `my_offers_analytics` as `moa`  WHERE `moa`.`user_id`!=0 AND `moa`.`myoffers_ids`!='' AND `moa`.`myoffers_ids` LIKE '%|".$sendArray['campaign_offer_ids']."|%' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientCampaignOfferUsersByDpId($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `my_offers_analytics` WHERE  `user_id`!=0 AND `offer_id`!='' AND `datapoint_id`!=10  AND  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' AND  `offer_id`='".$sendArray['campaign_offer_ids']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientCampaignAllMyOffersUsers($sendArray)
	 {
		try 
		{  
		    $query = "SELECT `moa`.`user_id` FROM `my_offers_analytics` as `moa`  WHERE `moa`.`user_id`!=0 AND `moa`.`myoffers_ids`!='' AND `moa`.`myoffers_ids` LIKE '%|".$sendArray['campaign_offer_ids']."|%' AND `moa`.`datapoint_id` IN (".$sendArray['datapoint_id'].")  AND  DATE_FORMAT(`moa`.`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['campaign_start_date']."' AND '".$sendArray['campaign_end_date']."' GROUP BY `moa`.`user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
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
	
	
	public function getClosetByDateRange($sendArray)
	{
		try 
		{   

		    $query = "SELECT `pa`.`pd_id` AS 'productId', count(`pa`.`pd_id`) AS `productViews`, `p`.`pd_name` AS `productName`, `p`.`pd_image`, `p`.`client_id`, `pa`.`pd_datapoint_id` FROM `closet_analytics` AS `pa`, `products` AS `p` WHERE `pa`.`client_id` = ".$sendArray['client_id']." AND `pa`.`pd_id`!=0 AND `pa`.`pd_id` = `p`.`pd_id`  AND DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' GROUP BY `pa`.`pd_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getOfferInfoByOfferId($sendArray)
	{
		try 
		{   

		    $query = "SELECT `o`.`offer_id`, `o`.`client_id`, `o`.`offer_image`,`o`.`offer_name`,`c`.`name` as `client_name`,`c`.`logo` FROM `offers` AS `o`,`client` as `c` WHERE `o`.`offer_id` = '".$sendArray['offer_id']."'  AND `o`.`client_id` = `c`.`id` " ;  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	public function getOfferInfoByCampaignsOfferId($sendArray)
	{
		try 
		{   

		    $query = "SELECT `o`.`offer_id`, `o`.`client_id`, `o`.`offer_image`,`o`.`offer_name`,`cc`.`campaign_name`  FROM `offers` AS `o`,`client_campaigns` as `cc` WHERE `o`.`offer_id` = '".$sendArray['campaign_offer_ids']."'  AND `o`.`client_id` = `cc`.`client_id` " ;  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientProductsByDate($start_date)
	{
		try 
		{   //echo $query = "SELECT * FROM `products_analytics` WHERE   DATE_FORMAT(pd_created_date, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."'";
		    $query = "SELECT `c`.`name` as `client_name`,`pa`.`client_id` as `client_id`, count(`pa`.`pd_id`) as `no_of_products` FROM `products_analytics` AS `pa`, `client` AS `c` WHERE DATE_FORMAT(`pa`.`pd_created_date`, '%Y-%m-%d') BETWEEN '$start_date' AND '$start_date' AND `pa`.`pd_id` != 0 AND `pa`.`client_id` = `c`.`id` GROUP BY `pa`.`client_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientUserInfoByuserId($user_id)
	{
		try 
		{   

			$query = "SELECT * FROM `users` as `u` , `user_details` as `ud` where `u`.`user_id`='$user_id' and `u`.`user_id` = `ud`.`user_id`";
  		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getClientProductsByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT count(pd_id) AS 'productViews'  FROM `products_analytics` WHERE `user_id`!=0 AND `pd_id`!=0 AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `user_id`='".$sendArray['user_id']."' AND `pd_id` = '".$sendArray['product_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientClosetByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT  count(prod_ids) AS 'closetViews'  FROM `closet_analytics` WHERE `user_id`!=0 AND  DATE_FORMAT(`closet_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND `user_id`='".$sendArray['user_id']."' AND prod_ids LIKE '%|".$sendArray['product_id']."|%'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getClientWishlistByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT  count(prod_ids) AS 'wishlistViews'  FROM `wishlist_analytics` WHERE  `user_id`!=0 AND DATE_FORMAT(`wishlist_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND `user_id`='".$sendArray['user_id']."' AND prod_ids LIKE '%|".$sendArray['product_id']."|%'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientProductShareUsersByDpIds($sendArray)
	{
		try 
		{   
            $query = "SELECT `user_id`  FROM `products_analytics` WHERE `user_id`!=0 AND DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pd_id` = '".$sendArray['product_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function getClientProductViewsByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT  count(pd_id) AS 'productViews'  FROM `products_analytics` WHERE  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `user_id`='".$sendArray['user_id']."' AND `pd_id` = '".$sendArray['product_id']."'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getClientProductUsersByDpIdPdId($sendArray)
	{
		try 
		{   
            $query = "SELECT user_id  FROM `products_analytics` WHERE  `pd_id`!=0 AND `user_id`!=0 AND  DATE_FORMAT(`pd_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `pd_datapoint_id` IN (".$sendArray['datapoint_id'].") AND `pd_id` = '".$sendArray['product_id']."' GROUP BY `user_id`";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	
	public function getClientOfferViewsByDpIdUserId($sendArray)
	{
		try 
		{   
            $query = "SELECT  count(myoffers_ids) AS 'offerViews'  FROM `my_offers_analytics` WHERE  DATE_FORMAT(`myoffers_created_date`, '%Y-%m-%d') BETWEEN '".$sendArray['start_date']."' AND '".$sendArray['end_date']."' AND `datapoint_id`='".$sendArray['datapoint_id']."' AND `user_id`='".$sendArray['user_id']."' AND `myoffers_ids` LIKE '%|".$sendArray['offer_id']."|%'";  
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getProductsByCID($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			 {

			 	$query = "SELECT  `pd_id`  FROM `products` WHERE  `client_id` IN ('".$sendArray['search_client_id']."')";  
		     }
			 else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))//super admin and admin 
			 {
			 	$query = "SELECT  `pd_id`  FROM `products` WHERE  `client_id` IN ('".$sendArray['search_client_id']."')"; 
 
             }
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function getOffersByCID($sendArray)
	{
		try 
		{   
			if($sendArray['group_id']==3)
			 {
			 	$query = "SELECT  `offer_id`  FROM `offers` WHERE  `client_id` IN (".$sendArray['search_client_id'].")";  
		    
			 }
			 else if(($sendArray['group_id']==2) || ($sendArray['group_id']==1) || ($sendArray['group_id']==6))//admin and super admin
			 {
                $query = "SELECT  `offer_id`  FROM `offers` WHERE  `client_id` IN (".$sendArray['search_client_id'].")";  
             }
		    $result = $this->selectQuery($query);
		    return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	function __destruct() {
		//pg_close($this->con); 
	}
}
?>