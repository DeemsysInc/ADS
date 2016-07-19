<?php 
 class cClients{

	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objCommon;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/clients.model.class.php');
			$this->objClientsQuery = new mClients();
			
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetAllClients()
	{
		try
		{
			global $config;
			
			$outArrClientsInfo = array();
			$outArrClientsInfo = $this->objClientsQuery->getAllClientsInfo();
			
			echo json_encode($outArrClientsInfo);
			


        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
function search_array($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && $this->search_array($needle, $element))
               return true;
     }
   return false;
}

	function modShowMobileUseTrackDataByProducts()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['login_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
            $sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			//print_r($sendArray);
			$outArrResults = array();
			if(!empty($_REQUEST['campaign_id'])){
			//echo "campaign id exist";
			 $outArrResults = $this->modShowGetByProductsByCampaignId($sendArray);
			// print_r($outArrResults);
			}
			else{
		//	echo "campaign id not exist";
	        $outArrResults = $this->modShowGetByProductsByClientId($sendArray);
			}
	        echo json_encode($outArrResults);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
 
 
 function modShowGetByProductsByCampaignId($sendArray)
	{
		try
		{
			global $config;

			$arrClientCampaignInfo=array();
			$arrClientCampaignInfo=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			
		   // print_r($sendArray);
            $outArrResults = array();
		    $outArrClientCampaignsProductIds=array();
			$arrClientCampaignsProductViews=array();
			$outArrClientCampaignsProductIds=isset($arrClientCampaignInfo[0]['campaign_product_ids']) ? $arrClientCampaignInfo[0]['campaign_product_ids'] : 0;
			$arrClientCampaignsProductViews=explode(',', $outArrClientCampaignsProductIds);
			
			// print_r($arrClientCampaignsProductViews);
			 for($j=0;$j<count($arrClientCampaignsProductViews);$j++)
			    {
				
			   $sendArray=array();
			  //$sendArray['campaign_product_ids']=isset($arrClientCampaignInfo[0]['campaign_product_ids']) ? $arrClientCampaignInfo[0]['campaign_product_ids'] : '';
			   $sendArray['campaign_offer_ids']=isset($arrClientCampaignInfo[0]['campaign_offer_ids']) ? $arrClientCampaignInfo[0]['campaign_offer_ids'] : '';
			   $times=isset($arrClientCampaignInfo[0]['campaign_start_date']) ? $arrClientCampaignInfo[0]['campaign_start_date'] : 0;
			   $timess=isset($arrClientCampaignInfo[0]['campaign_end_date']) ? $arrClientCampaignInfo[0]['campaign_end_date'] : 0;
			    $sendArray['campaign_start_date'] = date('Y-m-d',strtotime($times));
		    	$sendArray['campaign_end_date'] = date('Y-m-d',strtotime($timess));	
				$sendArray['campaign_product_ids']=$arrClientCampaignsProductViews[$j];
				
				
				
				
		
            	$outArrCampaignProductInfo=array();
            	$outArrCampaignProductInfo = $this->objPublic->getProductInfoByCampaignProdId($sendArray);
			//  print_r($outArrCampaignProductInfo);
            	$outArrResults[$j]['productId']=$sendArray['campaign_product_ids'];
            	$outArrResults[$j]['campaign_id']=isset($outArrCampaignProductInfo[0]['campaign_id']) ? $outArrCampaignProductInfo[0]['campaign_id'] : '';
				$outArrResults[$j]['client_id']=isset($outArrCampaignProductInfo[0]['client_id']) ? $outArrCampaignProductInfo[0]['client_id'] : '';
            	$outArrResults[$j]['productName']=isset($outArrCampaignProductInfo[0]['pd_name']) ? $outArrCampaignProductInfo[0]['pd_name'] : '';
            	$outArrResults[$j]['pd_image']=isset($outArrCampaignProductInfo[0]['pd_image']) ? $outArrCampaignProductInfo[0]['pd_image'] : '';
				
				
				
				
				
			   
			    $sendArray['datapoint_id']="2,30";//scanned and financial related scan===product views page
				$outArrScannedCampaignPids=array();
				$outArrScannedCampaignPids = $this->objPublic->getClientCampaignProductsByDpIdPid($sendArray);
				$outArrResults[$j]['scannedViews'] = isset($outArrScannedCampaignPids[0]['productViews']) ? $outArrScannedCampaignPids[0]['productViews'] : 0;	
				//print_r($outArrResults[$j]['scannedViews']);
				
				$sendArray['datapoint_id']="6,7,8,18";//shared
			    $outArrShareCampaignPids=array();
  			    $outArrShareCampaignPids = $this->objPublic->getClientCampaignProductsByDpIdPid($sendArray);
  			    $outArrResults[$j]['shareViews'] = isset($outArrShareCampaignPids[0]['productViews']) ? $outArrShareCampaignPids[0]['productViews'] : 0;
                // print_r($outArrResults[$j]['shareViews']);
				 
				$sendArray['datapoint_id']=5;//cart views
			    $outArrCartCampaignPids=array();
			    $outArrCartCampaignPids = $this->objPublic->getClientCampaignProductsByDpIdPid($sendArray);
                $outArrResults[$j]['cartViews'] = isset($outArrCartCampaignPids[0]['productViews']) ? $outArrCartCampaignPids[0]['productViews'] : 0;
               // print_r($outArrResults[$j]['cartViews']);
				
				$sendArray['datapoint_id']='35,37';//my closet product details and my closet mybrands product details
				$outArrCampaignMyClosetPids=array();
				$outArrCampaignMyClosetPids = $this->objPublic->getClientCampaignClosetByDpId($sendArray);
				$outArrResults[$j]['closetViews'] = isset($outArrCampaignMyClosetPids[0]['closetViews']) ? $outArrCampaignMyClosetPids[0]['closetViews'] : 0;
				// print_r($outArrResults[$j]['closetViews']);
				 
				 	
				$sendArray['datapoint_id']='36';//wishlist product details 
				$outArrCampaignWishlistPids=array();
				$outArrCampaignWishlistPids = $this->objPublic->getClientCampaignWishlistByDpId($sendArray);
				$outArrResults[$j]['wishlistViews'] = isset($outArrCampaignWishlistPids[0]['wishlistViews']) ? $outArrCampaignWishlistPids[0]['wishlistViews'] : 0;
			  //  print_r($outArrResults[$j]['wishlistViews']);
				
				$sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
			    $outArrCampaignClosetPDetailPids=array();
			    $outArrCampaignClosetPDetailPids = $this->objPublic->getClientCampaignClosetProdutDetailsFromPAnalytics($sendArray);
                $outArrResults[$j]['product_detail_views_closet'] = isset($outArrCampaignClosetPDetailPids[0]['productViews']) ? $outArrCampaignClosetPDetailPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
			    $outArrCampaignWishlistPDetailPids=array();
			    $outArrCampaignWishlistPDetailPids = $this->objPublic->getClientCampaignWishlistProdutDetailsFromPAnalytics($sendArray);
                $outArrResults[$j]['product_detail_views_wishlist'] = isset($outArrCampaignWishlistPDetailPids[0]['productViews']) ? $outArrCampaignWishlistPDetailPids[0]['productViews'] : 0;
				
				$outArrResults[$j]['product_detail_views']=$outArrResults[$j]['product_detail_views_closet']+$outArrResults[$j]['product_detail_views_wishlist'];
				
				//total users from all datapoints in product_analytics table
			    $outArrCampaignUsers1=array();
				$outArrCampaignUsers1 = $this->objPublic->getClientCampaignProductUsersByDpIds($sendArray);//excluding datapoint 4
				//get total users from 20,21
				$outArrCampaignUsers2=array();
				$sendArray['datapoint_id']="35,37";//my closet product details and my closet mybrands product details
				$outArrCampaignUsers2 = $this->objPublic->getClientCampaignClosetUsersByDpIds($sendArray);
				$outArrCampaignUsers3=array();
				$sendArray['datapoint_id']=36;// wishlist product details 
				$outArrCampaignUsers3 = $this->objPublic->getClientCampaignWishlistUsersByDpIds($sendArray);


				$ourArrSortCampaignUsers=array_merge($outArrCampaignUsers1,$outArrCampaignUsers2,$outArrCampaignUsers3);
                $ourArrSortCampaignUsers = $this->arrayUnique($ourArrSortCampaignUsers);  


				//$outArrResults[$i]['no_of_users'] = $outArrResults[$i]['scannedViews'] + $outArrResults[$i]['product_detail_views'] + $outArrResults[$i]['shareViews'] +$outArrResults[$i]['cartViews'] +$outArrResults[$i]['closetViews'] + $outArrResults[$i]['wishlistViews'] ;
				$outArrResults[$j]['no_of_users'] = count($ourArrSortCampaignUsers);
				$outArrResults[$j]['totalViews'] = $outArrResults[$j]['scannedViews'] + $outArrResults[$j]['product_detail_views'] +$outArrResults[$j]['shareViews'] +$outArrResults[$j]['cartViews'] +$outArrResults[$j]['closetViews'] + $outArrResults[$j]['wishlistViews'] ;
			  //  print_r($outArrResults);
				}
            	 $outArrResults['campaign_info']=$arrClientCampaignInfo;
     // print_r($outArrResults);
			
			return $outArrResults; 
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
 
 
 
 
	function modShowGetByProductsByClientId($sendArray)
	{
		try
		{
			global $config;

			$arrClientInfo=array();
			$arrClientInfo=$this->objPublic->getClientInfoByCID($sendArray);

            $outArrayPIds=array();
		    $outArrayPIds = $this->objPublic->getProductsByCID($sendArray);

            $outArrResults = array();
            for($i=0;$i<count($outArrayPIds);$i++)
            {
            	$sendArray['product_id']=isset($outArrayPIds[$i]['pd_id']) ? $outArrayPIds[$i]['pd_id'] : 0;
            	
            	$outArrProductInfo = $this->objPublic->getProductInfoByProdId($sendArray);
            	$outArrResults[$i]['productId']=$sendArray['product_id'];
            	$outArrResults[$i]['client_id']=isset($outArrProductInfo[0]['client_id']) ? $outArrProductInfo[0]['client_id'] : '';
            	$outArrResults[$i]['productName']=isset($outArrProductInfo[0]['pd_name']) ? $outArrProductInfo[0]['pd_name'] : '';
            	$outArrResults[$i]['pd_image']=isset($outArrProductInfo[0]['pd_image']) ? $outArrProductInfo[0]['pd_image'] : '';
            	
            	$sendArray['datapoint_id']="2,30";//scanned and financial related scan===product views page
				$outArrScannedPids=array();
				$outArrScannedPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
				$outArrResults[$i]['scannedViews'] = isset($outArrScannedPids[0]['productViews']) ? $outArrScannedPids[0]['productViews'] : 0;
               // print_r($outArrResults[$i]['scannedViews']);
       //          $sendArray['datapoint_id']=3;//product details
			    // $outArrDetailPids=array();
			    // $outArrDetailPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
       //          $outArrResults[$i]['product_detail_views'] = isset($outArrDetailPids[0]['productViews']) ? $outArrDetailPids[0]['productViews'] : 0;
				
				
				$sendArray['datapoint_id']="6,7,8,18";//shared
			    $outArrSharePids=array();
  			    $outArrSharePids = $this->objPublic->getClientProductsByDpIdPid($sendArray);

  			    $outArrResults[$i]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;
               // print_r($outArrResults[$i]['shareViews']);
				$sendArray['datapoint_id']=5;//cart views
			    $outArrCartPids=array();
			    $outArrCartPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
                $outArrResults[$i]['cartViews'] = isset($outArrCartPids[0]['productViews']) ? $outArrCartPids[0]['productViews'] : 0;
                //  print_r($outArrResults[$i]['cartViews']);
			    
			    // $sendArray['datapoint_id']=20;//my closet
				// $outArrMyClosetPids=array();
				// $outArrMyClosetPids = $this->objPublic->getClientClosetByDpId($sendArray);
				// $outArrResults[$i]['closetViews'] = isset($outArrMyClosetPids[0]['closetViews']) ? $outArrMyClosetPids[0]['closetViews'] : 0;
				
				// $sendArray['datapoint_id']=21;// wishlist
				// $outArrWishlistPids=array();
				// $outArrWishlistPids = $this->objPublic->getClientWishlistByDpId($sendArray);
                // $outArrResults[$i]['wishlistViews'] = isset($outArrWishlistPids[0]['wishlistViews']) ? $outArrWishlistPids[0]['wishlistViews'] : 0;

                
                $sendArray['datapoint_id']='35,37';//my closet product details and my closet mybrands product details
				$outArrMyClosetPids=array();
				$outArrMyClosetPids = $this->objPublic->getClientClosetByDpId($sendArray);
				$outArrResults[$i]['closetViews'] = isset($outArrMyClosetPids[0]['closetViews']) ? $outArrMyClosetPids[0]['closetViews'] : 0;
				
				$sendArray['datapoint_id']='36';//wishlist product details 
				$outArrWishlistPids=array();
				$outArrWishlistPids = $this->objPublic->getClientWishlistByDpId($sendArray);
				$outArrResults[$i]['wishlistViews'] = isset($outArrWishlistPids[0]['wishlistViews']) ? $outArrWishlistPids[0]['wishlistViews'] : 0;
				
				$sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
			    $outArrClosetPDetailPids=array();
			    $outArrClosetPDetailPids = $this->objPublic->getClientClosetProdutDetailsFromPAnalytics($sendArray);
                $outArrResults[$i]['product_detail_views_closet'] = isset($outArrClosetPDetailPids[0]['productViews']) ? $outArrClosetPDetailPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
			    $outArrWishlistPDetailPids=array();
			    $outArrWishlistPDetailPids = $this->objPublic->getClientWishlistProdutDetailsFromPAnalytics($sendArray);
                $outArrResults[$i]['product_detail_views_wishlist'] = isset($outArrWishlistPDetailPids[0]['productViews']) ? $outArrWishlistPDetailPids[0]['productViews'] : 0;
				
				$outArrResults[$i]['product_detail_views']=$outArrResults[$i]['product_detail_views_closet']+$outArrResults[$i]['product_detail_views_wishlist'];
            
				//total users from all datapoints in product_analytics table
			    $outArrUsers1=array();
				$outArrUsers1 = $this->objPublic->getClientProductUsersByDpIds($sendArray);//excluding datapoint 4
				//get total users from 20,21
				$outArrUsers2=array();
				$sendArray['datapoint_id']="35,37";//my closet product details and my closet mybrands product details
				$outArrUsers2 = $this->objPublic->getClientClosetUsersByDpIds($sendArray);
				$outArrUsers3=array();
				$sendArray['datapoint_id']=36;// wishlist product details 
				$outArrUsers3 = $this->objPublic->getClientWishlistUsersByDpIds($sendArray);


				$ourArrSortUsers=array_merge($outArrUsers1,$outArrUsers2,$outArrUsers3);
                $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);  






				//$outArrResults[$i]['no_of_users'] = $outArrResults[$i]['scannedViews'] + $outArrResults[$i]['product_detail_views'] + $outArrResults[$i]['shareViews'] +$outArrResults[$i]['cartViews'] +$outArrResults[$i]['closetViews'] + $outArrResults[$i]['wishlistViews'] ;
				$outArrResults[$i]['no_of_users'] = count($ourArrSortUsers);
				$outArrResults[$i]['totalViews'] = $outArrResults[$i]['scannedViews'] + $outArrResults[$i]['product_detail_views'] +$outArrResults[$i]['shareViews'] +$outArrResults[$i]['cartViews'] +$outArrResults[$i]['closetViews'] + $outArrResults[$i]['wishlistViews'] ;
				


            }
            $outArrResults['client_info']=$arrClientInfo;
            //print_r($outArrResults);
			
			return $outArrResults;
			

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
function arrayUnique($array, $preserveKeys = false)  
{  
    // Unique Array for return  
    $arrayRewrite = array();  
    // Array with the md5 hashes  
    $arrayHashes = array();  
    foreach($array as $key => $item) {  
        // Serialize the current element and create a md5 hash  
        $hash = md5(serialize($item));  
        // If the md5 didn't come up yet, add the element to  
        // to arrayRewrite, otherwise drop it  
        if (!isset($arrayHashes[$hash])) {  
            // Save the current element hash  
            $arrayHashes[$hash] = $hash;  
            // Add element to the unique Array  
            if ($preserveKeys) {  
                $arrayRewrite[$key] = $item;  
            } else {  
                $arrayRewrite[] = $item;  
            }  
        }  
    }  
    return $arrayRewrite;  
}  
  

  function modShowMobileUseTrackDataByProductShares()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['login_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
            $sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			//print_r($sendArray);
			$outArrResults = array();
			if(!empty($_REQUEST['campaign_id'])){
			//echo "campaign id exist";
			 $outArrResults = $this->modShowGetByProductSharesByCampaignId($sendArray);
			// print_r($outArrResults);
			}
			else{
		//	echo "campaign id not exist";
	        $outArrResults = $this->modShowGetByProductSharesByClientId($sendArray);
			}
	        echo json_encode($outArrResults);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	function modShowGetByProductSharesByClientId($sendArray)
	{
		try
		{
			global $config;

			$arrClientInfo=array();
			$arrClientInfo=$this->objPublic->getClientInfoByCID($sendArray);

            $outArrayPIds=array();
		    $outArrayPIds = $this->objPublic->getProductsByCID($sendArray);
        //    print_r($outArrayPIds);
            $outArrResults = array();
            for($i=0;$i<count($outArrayPIds);$i++)
            {
            	$sendArray['product_id']=isset($outArrayPIds[$i]['pd_id']) ? $outArrayPIds[$i]['pd_id'] : 0;
            	
            	$outArrProductInfo = $this->objPublic->getProductInfoByProdId($sendArray);
            	$outArrResults[$i]['productId']=$sendArray['product_id'];
            	$outArrResults[$i]['client_id']=isset($outArrProductInfo[0]['client_id']) ? $outArrProductInfo[0]['client_id'] : '';
            	$outArrResults[$i]['productName']=isset($outArrProductInfo[0]['pd_name']) ? $outArrProductInfo[0]['pd_name'] : '';
            	$outArrResults[$i]['pd_image']=isset($outArrProductInfo[0]['pd_image']) ? $outArrProductInfo[0]['pd_image'] : '';
				
				$sendArray['datapoint_id']="6,7,8,18";//shared
			    $outArrSharePids=array();
  			    $outArrSharePids = $this->objPublic->getClientProductsByDpIdPid($sendArray);

  			    $outArrResults[$i]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;
            // print_r($outArrResults[$i]['shareViews']);
				
            }
            $outArrResults['client_info']=$arrClientInfo;
           // print_r($outArrResults);
			
			return $outArrResults;
			

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
  
  
  
 function modShowGetByProductSharesByCampaignId($sendArray)
	{
		try
		{
			global $config;

			$arrClientCampaignInfo=array();
			$arrClientCampaignInfo=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			
		   // print_r($sendArray);
            $outArrResults = array();
		    $outArrClientCampaignsProductIds=array();
			$arrClientCampaignsProductViews=array();
			$outArrClientCampaignsProductIds=isset($arrClientCampaignInfo[0]['campaign_product_ids']) ? $arrClientCampaignInfo[0]['campaign_product_ids'] : 0;
			$arrClientCampaignsProductViews=explode(',', $outArrClientCampaignsProductIds);
			
			// print_r($arrClientCampaignsProductViews);
			 for($j=0;$j<count($arrClientCampaignsProductViews);$j++)
			    {
				
			   $sendArray=array();
			  //$sendArray['campaign_product_ids']=isset($arrClientCampaignInfo[0]['campaign_product_ids']) ? $arrClientCampaignInfo[0]['campaign_product_ids'] : '';
			   $sendArray['campaign_offer_ids']=isset($arrClientCampaignInfo[0]['campaign_offer_ids']) ? $arrClientCampaignInfo[0]['campaign_offer_ids'] : '';
			   $times=isset($arrClientCampaignInfo[0]['campaign_start_date']) ? $arrClientCampaignInfo[0]['campaign_start_date'] : 0;
			   $timess=isset($arrClientCampaignInfo[0]['campaign_end_date']) ? $arrClientCampaignInfo[0]['campaign_end_date'] : 0;
			    $sendArray['campaign_start_date'] = date('Y-m-d',strtotime($times));
		    	$sendArray['campaign_end_date'] = date('Y-m-d',strtotime($timess));	
				$sendArray['campaign_product_ids']=$arrClientCampaignsProductViews[$j];
				
				
				
				
		
            	$outArrCampaignProductInfo=array();
            	$outArrCampaignProductInfo = $this->objPublic->getProductInfoByCampaignProdId($sendArray);
			    // print_r($outArrCampaignProductInfo);
            	$outArrResults[$j]['productId']=$sendArray['campaign_product_ids'];
            	$outArrResults[$j]['campaign_id']=isset($outArrCampaignProductInfo[0]['campaign_id']) ? $outArrCampaignProductInfo[0]['campaign_id'] : '';
				$outArrResults[$j]['client_id']=isset($outArrCampaignProductInfo[0]['client_id']) ? $outArrCampaignProductInfo[0]['client_id'] : '';
            	$outArrResults[$j]['productName']=isset($outArrCampaignProductInfo[0]['pd_name']) ? $outArrCampaignProductInfo[0]['pd_name'] : '';
            	$outArrResults[$j]['pd_image']=isset($outArrCampaignProductInfo[0]['pd_image']) ? $outArrCampaignProductInfo[0]['pd_image'] : '';
				
				
				$sendArray['datapoint_id']="6,7,8,18";//shared
			    $outArrShareCampaignPids=array();
  			    $outArrShareCampaignPids = $this->objPublic->getClientCampaignProductsByDpIdPid($sendArray);
  			    $outArrResults[$j]['shareViews'] = isset($outArrShareCampaignPids[0]['productViews']) ? $outArrShareCampaignPids[0]['productViews'] : 0;
                // print_r($outArrResults[$j]['shareViews']);
				}
            	 $outArrResults['campaign_info']=$arrClientCampaignInfo;
     // print_r($outArrResults);
			
			return $outArrResults; 
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
  
  
	function modShowMobileUserProductsFlow()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
            
			$outArrClientProdIds = array();
			$outArrClientProdIds = $this->objPublic->getProductInfoByProdId($sendArray);

			$outArrResults = array();
		    $productId=isset($outArrClientProdIds[0]['pd_id']) ? $outArrClientProdIds[0]['pd_id'] : '';
        	$outArrResults[0]['productId']=$productId;
        	$outArrResults[0]['client_id']=isset($outArrClientProdIds[0]['client_id']) ? $outArrClientProdIds[0]['client_id'] : '';
        	$outArrResults[0]['productName']=isset($outArrClientProdIds[0]['pd_name']) ? $outArrClientProdIds[0]['pd_name'] : '';
        	$outArrResults[0]['pd_image']=isset($outArrClientProdIds[0]['pd_image']) ? $outArrClientProdIds[0]['pd_image'] : '';
        	$outArrResults[0]['client_name']=isset($outArrClientProdIds[0]['client_name']) ? $outArrClientProdIds[0]['client_name'] : '';
        	$outArrResults[0]['client_logo']=isset($outArrClientProdIds[0]['logo']) ? $outArrClientProdIds[0]['logo'] : '';
        	
        	$sendArray['login_client_id']=isset($outArrClientProdIds[0]['client_id']) ? $outArrClientProdIds[0]['client_id'] : '';
            
			$sendArray['datapoint_id']="2,30";//scanned  and financial related scann=product views
			$outArrScannedPids=array();
			$outArrScannedPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['scannedViews'] = isset($outArrScannedPids[0]['productViews']) ? $outArrScannedPids[0]['productViews'] : 0;

			/*
			///////temperarly disabled/////////////
			$sendArray['datapoint_id']=3;//product details
		    $outArrDetailPids=array();
		    $outArrDetailPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
            $outArrResults[0]['product_detail_views'] = isset($outArrDetailPids[0]['productViews']) ? $outArrDetailPids[0]['productViews'] : 0;
			
			
			$sendArray['datapoint_id']=4;//shared
		    $outArrSharePids=array();
			$outArrSharePids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;

			*/
			$sendArray['datapoint_id']="6,7,8,18";//shared by email,fb,twitter,sms
		    $outArrSharePids=array();
			$outArrSharePids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;

			$sendArray['datapoint_id']="6";//shared by email
		    $outArrShareEmailPids=array();
			$outArrShareEmailPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['emailViews'] = isset($outArrShareEmailPids[0]['productViews']) ? $outArrShareEmailPids[0]['productViews'] : 0;

			$sendArray['datapoint_id']="7";//shared by fb
		    $outArrShareFBPids=array();
			$outArrShareFBPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['facebookViews'] = isset($outArrShareFBPids[0]['productViews']) ? $outArrShareFBPids[0]['productViews'] : 0;

			$sendArray['datapoint_id']="8";//shared by twitter
		    $outArrShareTwitterPids=array();
			$outArrShareTwitterPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['twitterViews'] = isset($outArrShareTwitterPids[0]['productViews']) ? $outArrShareTwitterPids[0]['productViews'] : 0;

			$sendArray['datapoint_id']="18";//shared by sms
		    $outArrShareSMSPids=array();
			$outArrShareSMSPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);
			$outArrResults[0]['smsViews'] = isset($outArrShareSMSPids[0]['productViews']) ? $outArrShareSMSPids[0]['productViews'] : 0;

		
			$sendArray['datapoint_id']=5;//cart views
		    $outArrCartPids=array();
		    $outArrCartPids = $this->objPublic->getClientProductsByDpIdPid($sendArray);

		    $outArrResults[0]['cartViews'] = isset($outArrCartPids[0]['productViews']) ? $outArrCartPids[0]['productViews'] : 0;

		    $sendArray['datapoint_id']="35,37";//my closet product details and mycloset mybrands product details
			$outArrMyClosetPids=array();
			$outArrMyClosetPids = $this->objPublic->getClientClosetByDpId($sendArray);
			$outArrResults[0]['closetViews'] = isset($outArrMyClosetPids[0]['closetViews']) ? $outArrMyClosetPids[0]['closetViews'] : 0;
			
			$sendArray['datapoint_id']=36;// wishlist product detals
			$outArrWishlistPids=array();
			$outArrWishlistPids = $this->objPublic->getClientWishlistByDpId($sendArray); 
            $outArrResults[0]['wishlistViews'] = isset($outArrWishlistPids[0]['wishlistViews']) ? $outArrWishlistPids[0]['wishlistViews'] : 0;

            $sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
		    $outArrClosetPDetailPids=array();
		    $outArrClosetPDetailPids = $this->objPublic->getClientClosetProdutDetailsFromPAnalytics($sendArray);
            $outArrResults[0]['product_detail_views_closet'] = isset($outArrClosetPDetailPids[0]['productViews']) ? $outArrClosetPDetailPids[0]['productViews'] : 0;
			
			$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
		    $outArrWishlistPDetailPids=array();
		    $outArrWishlistPDetailPids = $this->objPublic->getClientWishlistProdutDetailsFromPAnalytics($sendArray);
            $outArrResults[0]['product_detail_views_wishlist'] = isset($outArrWishlistPDetailPids[0]['productViews']) ? $outArrWishlistPDetailPids[0]['productViews'] : 0;
			
			$outArrResults[0]['product_detail_views']=$outArrResults[0]['product_detail_views_closet']+$outArrResults[0]['product_detail_views_wishlist'];
			//total users from all datapoints except 4
		    $outArrUsers1=array();
			$outArrUsers1 = $this->objPublic->getClientProductUsersByDpIds($sendArray);
			//get total users from 20,21
			$outArrUsers2=array();
			$sendArray['datapoint_id']="35,37";//my closet produt details and mycloset mybrands product details
			$outArrUsers2 = $this->objPublic->getClientClosetUsersByDpIds($sendArray);
			$outArrUsers3=array();
			$sendArray['datapoint_id']=36;// wishlist product details 
			$outArrUsers3 = $this->objPublic->getClientWishlistUsersByDpIds($sendArray);
			$ourArrSortUsers=array_merge($outArrUsers1,$outArrUsers2,$outArrUsers3);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);  
           $outArrResults[0]['no_of_users'] = count($ourArrSortUsers);
		   $outArrResults[0]['totalViews'] = $outArrResults[0]['scannedViews'] + $outArrResults[0]['product_detail_views'] + $outArrResults[0]['shareViews'] +$outArrResults[0]['cartViews'] +$outArrResults[0]['closetViews'] + $outArrResults[0]['wishlistViews'] ;
				
            echo json_encode($outArrResults);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowClientOffers()
	{
		try
		{
			global $config;
			$arrData=array();
            
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['login_client_id']=isset($_REQUEST['login_client_id']) ? $_REQUEST['login_client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_client_id']=isset($_REQUEST['search_client_id']) ? $_REQUEST['search_client_id'] :0;



           //get offer ids from my_offers_analytics table based on client
            $outArrMyOfferAnalytics = array();

			$outArrMyOfferAnalytics = $this->objPublic->getClientOffers($sendArray);//excluding datapoint 10,19,31
			$arrMyOffersAnalyticsUsers=array();
			$arrMyOffersAnalyticsOfferIds=array();
			for($i=0;$i<count($outArrMyOfferAnalytics);$i++)
			{
			  $arrMyOffersAnalyticsOfferIds[]['offer_id']=isset($outArrMyOfferAnalytics[$i]['offer_id']) ? $outArrMyOfferAnalytics[$i]['offer_id'] : 0;

			  $arrMyOffersAnalyticsUsers[]=isset($outArrMyOfferAnalytics[$i]['user_id']) ? $outArrMyOfferAnalytics[$i]['user_id'] : 0;
			}
            //get all offers by clientid from offers table
		    $outArrClientOfferIds=array();
			$outArrClientOfferIds = $this->objPublic->getOffersByCID($sendArray);

			$arrOfferIdsClosetAnalytics=array();
			$arrOfferIdsClosetAnalyticsUsers=array();
			$arrClosetUsers=array();
			$arrWishlistUsers=array();
			$arrMyoffersUsers=array();
			$arrOfferIdsMyOffersAnalytics=array();

			for($j=0;$j<count($outArrClientOfferIds);$j++)
		    {
		        $sendArray['offer_id']=isset($outArrClientOfferIds[$j]['offer_id']) ? $outArrClientOfferIds[$j]['offer_id'] :0;
		    	//get offer views from my_offers_analytics table
		    	$sendArray['datapoint_id']=9;//my offers
		        $outArrMyOffersAnalyticsViews = $this->objPublic->getClientAllMyOffers($sendArray);
		        if(count($outArrMyOffersAnalyticsViews)>0)
		        {
		        	$arrOfferIdsMyOffersAnalytics[]['offer_id']=isset($outArrClientOfferIds[$j]['offer_id']) ? $outArrClientOfferIds[$j]['offer_id'] :0;
	            }
				for($k=0;$k<count($outArrMyOffersAnalyticsViews);$k++)
				{
				   //get all users from closet table based on product id
				   $arrMyoffersUsers[]=isset($outArrMyOffersAnalyticsViews[$k]['user_id']) ? $outArrMyOffersAnalyticsViews[$k]['user_id'] : 0;
				   
				}
		        
			    
		    }
			$outArrayUserIdsForViews=array();
            $outArrayUserIdsForViews=array_merge($arrMyOffersAnalyticsUsers,$arrMyoffersUsers);
            $outArrayUserIds=array();
            $outArrayUserIds=array_merge($arrMyOffersAnalyticsUsers,$arrMyoffersUsers);
            $outArrayUserIds=array_map("unserialize", array_unique(array_map("serialize", $outArrayUserIds)));
            $outArrayUserIds=array_slice($outArrayUserIds, 0);
			$outArrayOfferIdsForViews=array();
            $outArrayOfferIdsForViews=array_merge($arrMyOffersAnalyticsOfferIds,$arrOfferIdsMyOffersAnalytics);
            $outArrayOfferIds=array();
            $outArrayOfferIds=array_merge($arrMyOffersAnalyticsOfferIds,$arrOfferIdsMyOffersAnalytics);
            $outArrayOfferIds=array_map("unserialize", array_unique(array_map("serialize", $outArrayOfferIds)));
            $outArrayOfferIds=array_slice($outArrayOfferIds, 0);
		    $outArrayCIds=array();
			for($i=0;$i<count($outArrayOfferIds);$i++)
			{
				$sendArray['offer_id']=isset($outArrayOfferIds[$i]['offer_id']) ? $outArrayOfferIds[$i]['offer_id'] : 0;
				$outArrGetOfferInfo = $this->objPublic->getOfferInfoByOfferId($sendArray);
				$outArrayCIds[]=isset($outArrGetOfferInfo[0]['client_id']) ? $outArrGetOfferInfo[0]['client_id'] :0;
				
			}
			$outArrayClientIds=array();
            $outArrayClientIds=array_map("unserialize", array_unique(array_map("serialize", $outArrayCIds)));
            $outArrayClientIds=array_slice($outArrayClientIds, 0);
			$arrPids=array();
            $outArrGetClientInfo = $this->objPublic->getClientInfoByCID($sendArray);
            $arrData[0]['client_id']=isset($outArrGetClientInfo[0]['id']) ? $outArrGetClientInfo[0]['id'] : '';
			$arrData[0]['client_name']=isset($outArrGetClientInfo[0]['name']) ? $outArrGetClientInfo[0]['name'] : '';
			$arrData[0]['no_of_offers']=count($outArrayOfferIds);
			$arrData[0]['no_of_users']=count($outArrayUserIds);
            echo json_encode($arrData);


        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUseTrackDataByOffers()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
            $sendArray['login_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
            $sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
		    $outArrResults=array();
			if(!empty($_REQUEST['campaign_id'])){
			 $outArrResults = $this->modShowGetByOffersByCampaignId($sendArray);
			}
			else{
	        $outArrResults = $this->modShowGetByOffersByClientId($sendArray);
			}
			   echo json_encode($outArrResults);
	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
						
	function modShowGetByOffersByCampaignId($sendArray)
	   {
		try
		{
			global $config;	
		   $arrClientCampaignInfo=array();
		   $arrClientCampaignInfo=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
		   $outArrClientCampaignsOfferIds =array();	
           $arrClientCampaignsOfferViews=array();	
	       $outArrClientCampaignsOfferIds =isset($arrClientCampaignInfo[0]['campaign_offer_ids']) ? $arrClientCampaignInfo[0]['campaign_offer_ids'] : '';
		   $arrClientCampaignsOfferViews=explode(',', $outArrClientCampaignsOfferIds);
           for ($i=0; $i < count($arrClientCampaignsOfferViews); $i++)
			 { 
			    $sendArray=array();
			    $sendArray['campaign_product_ids']=isset($arrClientCampaignInfo[0]['campaign_product_ids']) ? $arrClientCampaignInfo[0]['campaign_product_ids'] : '';
			    $times=isset($arrClientCampaignInfo[0]['campaign_start_date']) ? $arrClientCampaignInfo[0]['campaign_start_date'] : 0;
			    $timess=isset($arrClientCampaignInfo[0]['campaign_end_date']) ? $arrClientCampaignInfo[0]['campaign_end_date'] : 0;
			    $sendArray['campaign_start_date'] = date('Y-m-d',strtotime($times));
		    	$sendArray['campaign_end_date'] = date('Y-m-d',strtotime($timess));	
				$sendArray['campaign_offer_ids']=$arrClientCampaignsOfferViews[$i];
				
				
				$outArrCampaignOfferInfo=array();
				$outArrCampaignOfferInfo = $this->objPublic->getOfferInfoByCampaignsOfferId($sendArray);
				$outArrResults[$i]['client_id']=isset($outArrCampaignOfferInfo[0]['client_id']) ? $outArrCampaignOfferInfo[0]['client_id'] : '';
				$outArrResults[$i]['offer_id']=isset($outArrCampaignOfferInfo[0]['offer_id']) ? $outArrCampaignOfferInfo[0]['offer_id'] : 0;
				$outArrResults[$i]['offer_name']=isset($outArrCampaignOfferInfo[0]['offer_name']) ? $outArrCampaignOfferInfo[0]['offer_name'] : '';
				$outArrResults[$i]['offer_image']=isset($outArrCampaignOfferInfo[0]['offer_image']) ? $outArrCampaignOfferInfo[0]['offer_image'] : '';
            	
            	$sendArray['datapoint_id']="11,12,13,17";//share by email fb.,twitter,sms
				$outArrCampaignShareOfferIds=array();
				$outArrCampaignShareOfferIds = $this->objPublic->getClientCampaignOffersByDpId($sendArray);
				$outArrResults[$i]['shareViews'] = isset($outArrCampaignShareOfferIds[0]['offerViews']) ? $outArrCampaignShareOfferIds[0]['offerViews'] : 0;

				 $sendArray['datapoint_id']=14;//my offers removed 
				$outArrCampaignRemovedOfferIds=array();
				$outArrCampaignRemovedOfferIds = $this->objPublic->getClientCampaignOffersByDpId($sendArray);
				$outArrResults[$i]['removedViews'] = isset($outArrCampaignRemovedOfferIds[0]['offerViews']) ? $outArrCampaignRemovedOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=15;//my offers redeem page or offer details
				$outArrCampaignRedeemOfferIds=array();
				$outArrCampaignRedeemOfferIds = $this->objPublic->getClientCampaignOffersByDpId($sendArray);
				$outArrResults[$i]['redeemViews'] = isset($outArrCampaignRedeemOfferIds[0]['offerViews']) ? $outArrCampaignRedeemOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=11;//share offers by email 
				// $outArrShareEmailOfferIds=array();
				// $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['emailViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=12;//share offers by fb 
				// $outArrShareFBOfferIds=array();
				// $outArrShareFBOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['facebookViews'] = isset($outArrShareFBOfferIds[0]['offerViews']) ? $outArrShareFBOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=13;//share offers by twitter 
				// $outArrShareTwitterOfferIds=array();
				// $outArrShareTwitterOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['facebookViews'] = isset($outArrShareTwitterOfferIds[0]['offerViews']) ? $outArrShareTwitterOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=17;//share offers by sms 
				// $outArrShareSmsOfferIds=array();
				// $outArrShareSmsOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['smsViews'] = isset($outArrShareSmsOfferIds[0]['offerViews']) ? $outArrShareSmsOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=16;//my offers apply
				$outArrCampaignApplyOfferIds=array();
				$outArrCampaignApplyOfferIds = $this->objPublic->getClientCampaignOffersByDpId($sendArray);
				$outArrResults[$i]['applyViews'] = isset($outArrCampaignApplyOfferIds[0]['offerViews']) ? $outArrCampaignApplyOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']="19,31";//scann + financial scanning
				$outArrCampaignScannedOfferIds=array();
				$outArrCampaignScannedOfferIds = $this->objPublic->getClientCampaignOffersByDpId($sendArray);
				$outArrResults[$i]['scannedViews'] = isset($outArrCampaignScannedOfferIds[0]['offerViews']) ? $outArrCampaignScannedOfferIds[0]['offerViews'] : 0;
				
				$sendArray['datapoint_id']=9;//my offers home
				$outArrMyOfferIds=array();
				$outArrMyOfferIds = $this->objPublic->getClientCampaignAllMyOffers($sendArray);
				$outArrResults[$i]['myOfferViews'] = isset($outArrMyOfferIds[0]['offerViews']) ? $outArrMyOfferIds[0]['offerViews'] : 0;


				//total users from all datapoints except 10=share home
			    $outArrCampaignUsers1=array();
			    $outArrCampaignUsers1 = $this->objPublic->getClientCampaignOfferUsersByDpId($sendArray);
			  // print_r($outArrCampaignUsers1);

			    //get total users from 9
				$outArrCampaignUsers2=array();
				$sendArray['datapoint_id']=9;// my offers
				$outArrCampaignUsers2 = $this->objPublic->getClientCampaignAllMyOffersUsers($sendArray);
   
                 

				$ourArrSortCampaignUsers=array_merge($outArrCampaignUsers1,$outArrCampaignUsers2);
                $ourArrSortCampaignUsers = $this->arrayUnique($ourArrSortCampaignUsers);  
				


				$outArrResults[$i]['totalViews'] =$outArrResults[$i]['scannedViews'] + $outArrResults[$i]['shareViews'] + $outArrResults[$i]['removedViews'] + $outArrResults[$i]['redeemViews']  +$outArrResults[$i]['applyViews'] +$outArrResults[$i]['myOfferViews']  ;
				$outArrResults[$i]['no_of_users'] = count($ourArrSortCampaignUsers);
			
 
			}
			$outArrResults['campaign_info'] = $arrClientCampaignInfo;
		return $outArrResults;	
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}		
			
			
			
			
	function modShowGetByOffersByClientId($sendArray)
	   {
		try
		{
			global $config;	
		
            $arrClientInfo=array();
			$arrClientInfo=$this->objPublic->getClientInfoByCID($sendArray);

	
			$outArrayOfferIds =array();
			$outArrayOfferIds = $this->objPublic->getOffersByCID($sendArray);

            for ($i=0; $i < count($outArrayOfferIds); $i++)
			 { 
				$sendArray['offer_id']=isset($outArrayOfferIds[$i]['offer_id']) ? $outArrayOfferIds[$i]['offer_id'] : 0;
            	$outArrOfferInfo=array();
            	$outArrOfferInfo = $this->objPublic->getOfferInfoByOfferId($sendArray);
            	$outArrResults[$i]['client_id']=isset($outArrOfferInfo[0]['client_id']) ? $outArrOfferInfo[0]['client_id'] : '';
            	$outArrResults[$i]['offer_id']=isset($outArrOfferInfo[0]['offer_id']) ? $outArrOfferInfo[0]['offer_id'] : 0;
            	$outArrResults[$i]['offer_name']=isset($outArrOfferInfo[0]['offer_name']) ? $outArrOfferInfo[0]['offer_name'] : '';
            	$outArrResults[$i]['offer_image']=isset($outArrOfferInfo[0]['offer_image']) ? $outArrOfferInfo[0]['offer_image'] : '';
            	
            	$sendArray['datapoint_id']="11,12,13,17";//share by email fb.,twitter,sms
				$outArrShareOfferIds=array();
				$outArrShareOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				$outArrResults[$i]['shareViews'] = isset($outArrShareOfferIds[0]['offerViews']) ? $outArrShareOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=14;//my offers removed 
				$outArrRemovedOfferIds=array();
				$outArrRemovedOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				$outArrResults[$i]['removedViews'] = isset($outArrRemovedOfferIds[0]['offerViews']) ? $outArrRemovedOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=15;//my offers redeem page or offer details
				$outArrRedeemOfferIds=array();
				$outArrRedeemOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				$outArrResults[$i]['redeemViews'] = isset($outArrRedeemOfferIds[0]['offerViews']) ? $outArrRedeemOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=11;//share offers by email 
				// $outArrShareEmailOfferIds=array();
				// $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['emailViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=12;//share offers by fb 
				// $outArrShareFBOfferIds=array();
				// $outArrShareFBOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['facebookViews'] = isset($outArrShareFBOfferIds[0]['offerViews']) ? $outArrShareFBOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=13;//share offers by twitter 
				// $outArrShareTwitterOfferIds=array();
				// $outArrShareTwitterOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['facebookViews'] = isset($outArrShareTwitterOfferIds[0]['offerViews']) ? $outArrShareTwitterOfferIds[0]['offerViews'] : 0;

				// $sendArray['datapoint_id']=17;//share offers by sms 
				// $outArrShareSmsOfferIds=array();
				// $outArrShareSmsOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				// $outArrResults[$i]['smsViews'] = isset($outArrShareSmsOfferIds[0]['offerViews']) ? $outArrShareSmsOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=16;//my offers apply
				$outArrApplyOfferIds=array();
				$outArrApplyOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				$outArrResults[$i]['applyViews'] = isset($outArrApplyOfferIds[0]['offerViews']) ? $outArrApplyOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=9;//my offers home
				$outArrMyOfferIds=array();
				$outArrMyOfferIds = $this->objPublic->getClientAllMyOffers($sendArray);
				$outArrResults[$i]['myOfferViews'] = isset($outArrMyOfferIds[0]['offerViews']) ? $outArrMyOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']="19,31";//scann + financial scanning
				$outArrScannedOfferIds=array();
				$outArrScannedOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				$outArrResults[$i]['scannedViews'] = isset($outArrScannedOfferIds[0]['offerViews']) ? $outArrScannedOfferIds[0]['offerViews'] : 0;



				//total users from all datapoints except 10=share home
			    $outArrUsers1=array();
			    $outArrUsers1 = $this->objPublic->getClientOfferUsersByDpId($sendArray);
			    

			    //get total users from 9
				$outArrUsers2=array();
				$sendArray['datapoint_id']=9;// my offers
				$outArrUsers2 = $this->objPublic->getClientAllMyOffersUsers($sendArray);



				$ourArrSortUsers=array_merge($outArrUsers1,$outArrUsers2);
                $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);  
				


				$outArrResults[$i]['totalViews'] =$outArrResults[$i]['scannedViews'] + $outArrResults[$i]['shareViews'] + $outArrResults[$i]['removedViews'] + $outArrResults[$i]['redeemViews']  +$outArrResults[$i]['applyViews'] +$outArrResults[$i]['myOfferViews']  ;
				$outArrResults[$i]['no_of_users'] = count($ourArrSortUsers);
			
			}
			$outArrResults['client_info'] = $arrClientInfo;
		return $outArrResults;
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	function modShowMobileUseTrackDataByOfferShares()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
            $sendArray['login_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
            $sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
		    $outArrResults=array();
			if(!empty($_REQUEST['campaign_id'])){
			 $outArrResults = $this->modShowGetByOfferSharesByCampaignId($sendArray);
			}else{
	         $outArrResults = $this->modShowGetByOfferSharesByClientId($sendArray);
			 }
			echo json_encode($outArrResults);
	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function modShowGetByOfferSharesByClientId($sendArray)
	   {
		try
		{
			global $config;	
		
            $arrClientInfo=array();
			$arrClientInfo=$this->objPublic->getClientInfoByCID($sendArray);

	
			$outArrayOfferIds =array();
			$outArrayOfferIds = $this->objPublic->getOffersByCID($sendArray);

            for ($i=0; $i < count($outArrayOfferIds); $i++)
			 { 
				$sendArray['offer_id']=isset($outArrayOfferIds[$i]['offer_id']) ? $outArrayOfferIds[$i]['offer_id'] : 0;
            	$outArrOfferInfo=array();
            	$outArrOfferInfo = $this->objPublic->getOfferInfoByOfferId($sendArray);
            	$outArrResults[$i]['client_id']=isset($outArrOfferInfo[0]['client_id']) ? $outArrOfferInfo[0]['client_id'] : '';
            	$outArrResults[$i]['offer_id']=isset($outArrOfferInfo[0]['offer_id']) ? $outArrOfferInfo[0]['offer_id'] : 0;
            	$outArrResults[$i]['offer_name']=isset($outArrOfferInfo[0]['offer_name']) ? $outArrOfferInfo[0]['offer_name'] : '';
            	$outArrResults[$i]['offer_image']=isset($outArrOfferInfo[0]['offer_image']) ? $outArrOfferInfo[0]['offer_image'] : '';
            	
            	$sendArray['datapoint_id']="11,12,13,17";//share by email fb.,twitter,sms
				$outArrShareOfferIds=array();
				$outArrShareOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
				$outArrResults[$i]['shareViews'] = isset($outArrShareOfferIds[0]['offerViews']) ? $outArrShareOfferIds[0]['offerViews'] : 0;

			}
			$outArrResults['client_info'] = $arrClientInfo;
		return $outArrResults;

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function modShowGetByOfferSharesByCampaignId($sendArray)
	   {
		try
		{
			global $config;	
		   $arrClientCampaignInfo=array();
		   $arrClientCampaignInfo=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
		   $outArrClientCampaignsOfferIds =array();	
           $arrClientCampaignsOfferViews=array();	
	       $outArrClientCampaignsOfferIds =isset($arrClientCampaignInfo[0]['campaign_offer_ids']) ? $arrClientCampaignInfo[0]['campaign_offer_ids'] : '';
		   $arrClientCampaignsOfferViews=explode(',', $outArrClientCampaignsOfferIds);
            for ($i=0; $i < count($arrClientCampaignsOfferViews); $i++){ 
				$sendArray=array();
			    $sendArray['campaign_product_ids']=isset($arrClientCampaignInfo[0]['campaign_product_ids']) ? $arrClientCampaignInfo[0]['campaign_product_ids'] : '';
			    $times=isset($arrClientCampaignInfo[0]['campaign_start_date']) ? $arrClientCampaignInfo[0]['campaign_start_date'] : 0;
			    $timess=isset($arrClientCampaignInfo[0]['campaign_end_date']) ? $arrClientCampaignInfo[0]['campaign_end_date'] : 0;
			    $sendArray['campaign_start_date'] = date('Y-m-d',strtotime($times));
		    	$sendArray['campaign_end_date'] = date('Y-m-d',strtotime($timess));	
				$sendArray['campaign_offer_ids']=$arrClientCampaignsOfferViews[$i];
				
				
            	$outArrCampaignOfferInfo=array();
            	$outArrCampaignOfferInfo = $this->objPublic->getOfferInfoByCampaignsOfferId($sendArray);
            	$outArrResults[$i]['client_id']=isset($outArrCampaignOfferInfo[0]['client_id']) ? $outArrCampaignOfferInfo[0]['client_id'] : '';
            	$outArrResults[$i]['offer_id']=isset($outArrCampaignOfferInfo[0]['offer_id']) ? $outArrCampaignOfferInfo[0]['offer_id'] : 0;
            	$outArrResults[$i]['offer_name']=isset($outArrCampaignOfferInfo[0]['offer_name']) ? $outArrCampaignOfferInfo[0]['offer_name'] : '';
            	$outArrResults[$i]['offer_image']=isset($outArrCampaignOfferInfo[0]['offer_image']) ? $outArrCampaignOfferInfo[0]['offer_image'] : '';
            	
            	$sendArray['datapoint_id']="11,12,13,17";//share by email fb.,twitter,sms
				$outArrCampaignShareOfferIds=array();
				$outArrCampaignShareOfferIds = $this->objPublic->getClientCampaignOffersByDpId($sendArray);
				$outArrResults[$i]['shareViews'] = isset($outArrCampaignShareOfferIds[0]['offerViews']) ? $outArrCampaignShareOfferIds[0]['offerViews'] : 0;

			
 
			}
			$outArrResults['campaign_info'] = $arrClientCampaignInfo;
		return $outArrResults;	
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}		
	
	function modShowMobileUserOffersFlow()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
           
			$outArrClientOfferIds = array();
			$outArrClientOfferIds = $this->objPublic->getOfferInfoByOfferId($sendArray);

			$outArrResults = array();
			
			$offerId=isset($outArrClientOfferIds[0]['offer_id']) ? $outArrClientOfferIds[0]['offer_id'] : '';
        	$outArrResults[0]['offer_id']=$offerId;
        	$outArrResults[0]['client_id']=isset($outArrClientOfferIds[0]['client_id']) ? $outArrClientOfferIds[0]['client_id'] : '';
        	$outArrResults[0]['offer_name']=isset($outArrClientOfferIds[0]['offer_name']) ? $outArrClientOfferIds[0]['offer_name'] : '';
        	$outArrResults[0]['offer_image']=isset($outArrClientOfferIds[0]['offer_image']) ? $outArrClientOfferIds[0]['offer_image'] : '';
        	$outArrResults[0]['client_name']=isset($outArrClientOfferIds[0]['client_name']) ? $outArrClientOfferIds[0]['client_name'] : '';
        	$outArrResults[0]['client_logo']=isset($outArrClientOfferIds[0]['logo']) ? $outArrClientOfferIds[0]['logo'] : '';
        	
        	$sendArray['login_client_id']=isset($outArrClientProdIds[0]['client_id']) ? $outArrClientProdIds[0]['client_id'] : '';
            
        	$sendArray['datapoint_id']="11,12,13,17";//share by email , fb ,twitter and sms 
			$outArrShareOfferIds=array();
			$outArrShareOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['shareViews'] = isset($outArrShareOfferIds[0]['offerViews']) ? $outArrShareOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=11;//share offers by email 
			$outArrShareEmailOfferIds=array();
			$outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['emailViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=12;//share offers by fb 
			$outArrShareFBOfferIds=array();
			$outArrShareFBOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['facebookViews'] = isset($outArrShareFBOfferIds[0]['offerViews']) ? $outArrShareFBOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=13;//share offers by twitter 
			$outArrShareTwitterOfferIds=array();
			$outArrShareTwitterOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['twitterViews'] = isset($outArrShareTwitterOfferIds[0]['offerViews']) ? $outArrShareTwitterOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=17;//share offers by sms 
			$outArrShareSmsOfferIds=array();
			$outArrShareSmsOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['smsViews'] = isset($outArrShareSmsOfferIds[0]['offerViews']) ? $outArrShareSmsOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=14;//my offers removed 
			$outArrRemovedOfferIds=array();
			$outArrRemovedOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['removedViews'] = isset($outArrRemovedOfferIds[0]['offerViews']) ? $outArrRemovedOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=15;//my offers redeem page or offer details
			$outArrRedeemOfferIds=array();
			$outArrRedeemOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['redeemViews'] = isset($outArrRedeemOfferIds[0]['offerViews']) ? $outArrRedeemOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=16;//my offers apply
			$outArrApplyOfferIds=array();
			$outArrApplyOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['applyViews'] = isset($outArrApplyOfferIds[0]['offerViews']) ? $outArrApplyOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']=9;//my offers home
	        $outArrMyOfferIds=array();
			$outArrMyOfferIds = $this->objPublic->getClientAllMyOffers($sendArray);
			$outArrResults[0]['myOfferViews'] = isset($outArrMyOfferIds[0]['offerViews']) ? $outArrMyOfferIds[0]['offerViews'] : 0;

			$sendArray['datapoint_id']="19,31";//scanning + financial scanning
			$outArrScannedOfferIds=array();
			$outArrScannedOfferIds = $this->objPublic->getClientOffersByDpId($sendArray);
			$outArrResults[0]['scannedViews'] = isset($outArrScannedOfferIds[0]['offerViews']) ? $outArrScannedOfferIds[0]['offerViews'] : 0;

		   //total users from all datapoints except 10
		    $outArrUsers1=array();
		    $outArrUsers1 = $this->objPublic->getClientOfferUsersByDpId($sendArray);
		    
		    //get total users from 9
			$outArrUsers2=array();
			$sendArray['datapoint_id']=9;// my offers
			$outArrUsers2 = $this->objPublic->getClientAllMyOffersUsers($sendArray);

			$ourArrSortUsers=array_merge($outArrUsers1,$outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);  

			$outArrResults[0]['totalViews'] = $outArrResults[0]['scannedViews'] + $outArrResults[0]['shareViews'] + $outArrResults[0]['removedViews'] + $outArrResults[0]['redeemViews'] + $outArrResults[0]['applyViews']  +$outArrResults[0]['myOfferViews'] ;
			$outArrResults[0]['no_of_users'] = count($ourArrSortUsers);
            echo json_encode($outArrResults);
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowAnalyticDashoardByCampaign($sendArray)
	{
		try
		{
		     global $config;
	         $outArrResults=array();
			$arrTotalClients=array();
			$arrClientInfo=array();
        	$arrTotalClients=$this->objPublic->getTotalClients();
			
			if(!empty($_REQUEST['client_search']))
			{
				$sendArray['search_client_id']=isset($_REQUEST['client_search']) ? $_REQUEST['client_search'] : 0;
			    $arrClientInfo=$this->objPublic->getClientInfoByCID($sendArray);
			}
			//get Client campaignes
			$arrClientCampaigns=array();
			 $arr=array();
		   if(($_REQUEST['campaign_search'])!=0)
			{
			    $arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			 
			    for($k=0;$k<count($arrClientCampaigns);$k++){
					$arr[$k]['campaign_id']=isset($arrClientCampaigns[$k]['campaign_id']) ? $arrClientCampaigns[$k]['campaign_id'] : 0;
					$arr[$k]['campaign_name']=isset($arrClientCampaigns[$k]['campaign_name']) ? $arrClientCampaigns[$k]['campaign_name'] : 0;
					$times=isset($arrClientCampaigns[$k]['campaign_start_date']) ? $arrClientCampaigns[$k]['campaign_start_date'] : 0;
					$timess=isset($arrClientCampaigns[$k]['campaign_end_date']) ? $arrClientCampaigns[$k]['campaign_end_date'] : 0;
					$arr[$k]['start_date'] = date('Y-m-d',strtotime($times));
					$arr[$k]['end_date'] = date('Y-m-d',strtotime($timess)); 
               }
			}
			$outArrResults[0]['total_campaigns'] =$arr;
		    $outArrResults[0]['total_clients'] =$arrTotalClients; 
		return $outArrResults;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowAnalyticDashoardByClient($sendArray)
	{
		try
		{
		    global $config;
		    $outArrResults=array();
	    	$arrTotalClients=array();
			$arrClientInfo=array();
        	$arrTotalClients=$this->objPublic->getTotalClients();
			if(!empty($_REQUEST['client_search']))
			{
			    $sendArray['search_client_id']=isset($_REQUEST['client_search']) ? $_REQUEST['client_search'] : 0;
				$arrClientInfo=$this->objPublic->getClientInfoByCID($sendArray);
			    $arrInfo[0]['name']=isset($arrClientInfo[0]['name']) ? $arrClientInfo[0]['name'] : '';
				$arrInfo[0]['client_id']=isset($arrClientInfo[0]['client_id']) ? $arrClientInfo[0]['client_id'] : '';
				$times=isset($_SESSION['start_date']) ? $_SESSION['start_date'] : date('Y-m-d',strtotime('1 month ago'));
				$timess=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :date('Y-m-d');
				$arrInfo[0]['start_date'] = date('Y-m-d',strtotime($times));
				$arrInfo[0]['end_date'] = date('Y-m-d',strtotime($timess));

			}
			$arrClientCampaigns=array();
			$arr=array();
		   if(($_REQUEST['campaign_search'])!=0)
			{
				$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
				 
				for($i=0;$i<count($arrClientCampaigns);$i++){
				$arr[$i]['campaign_id']=isset($arrClientCampaigns[$i]['campaign_id']) ? $arrClientCampaigns[$i]['campaign_id'] : 0;
				$arr[$i]['campaign_name']=isset($arrClientCampaigns[$i]['campaign_name']) ? $arrClientCampaigns[$i]['campaign_name'] : 0;
				$times=isset($arrClientCampaigns[$i]['campaign_start_date']) ? $arrClientCampaigns[$i]['campaign_start_date'] : 0;
				$timess=isset($arrClientCampaigns[$i]['campaign_end_date']) ? $arrClientCampaigns[$i]['campaign_end_date'] : 0;
				$arr[$i]['start_date'] = date('Y-m-d',strtotime($times));
				$arr[$i]['end_date'] = date('Y-m-d',strtotime($timess)); 
				 
				}
				
			}
	        $outArrResults[0]['total_campaigns'] =$arr;
		    //$outArrResults[0]['client_info']=$arrInfo;
            //$outArrResults[0]['graph_results'] =$arrDataForGraph;
		    $outArrResults[0]['total_clients'] =$arrTotalClients;
		    return $outArrResults;			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowAnalyticDashoard()
	{
		try
		{
			global $config;
		    $sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['login_client_id']=isset($_REQUEST['login_client_id']) ? $_REQUEST['login_client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$outArrResults=array();
			// comparing with campaignid
			if($_REQUEST['campaign_search']!=0)
			{
			//echo "campaign exist";
			$outArrResults=$this->modShowAnalyticDashoardByCampaign($sendArray);		  
			}

			else{
            //echo "campaign not exist";
			  $outArrResults=$this->modShowAnalyticDashoardByClient($sendArray); 
            }
            echo json_encode($outArrResults);
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserClientCampaignDates()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] : 0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			//get client campaigns dates by campaign id
			$arrCampaignDates=array();
			$arrCampaignDates=$this->objPublic->getClientCampaignsDates($sendArray);
			echo json_encode($arrCampaignDates,true);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function modShowMobileUserClientCampaignDetails()
	{
		try
		{
			global $config;
			$sendArray=array();
			$outArrResults=array();
			//$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] : 0;
			$sendArray['client_id']=isset($_REQUEST['clientId']) ? $_REQUEST['clientId'] : 0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			//print_r($sendArray);
			$arrClientCampaign=array();
			$arrClientCampaign=$this->objPublic->getClientCampaignDetails($sendArray);
			$arr=array();
			for($i=0;$i<count($arrClientCampaign);$i++){
				$arr[$i]['campaign_id']=isset($arrClientCampaign[$i]['campaign_id']) ? $arrClientCampaign[$i]['campaign_id'] : 0;
				$arr[$i]['client_id']=isset($arrClientCampaign[$i]['client_id']) ? $arrClientCampaign[$i]['client_id'] :0;
				$arr[$i]['campaign_name']=isset($arrClientCampaign[$i]['campaign_name']) ? $arrClientCampaign[$i]['campaign_name'] : '';
				$times=isset($arrClientCampaign[$i]['campaign_start_date']) ? $arrClientCampaign[$i]['campaign_start_date'] : 0;
				$timess=isset($arrClientCampaign[$i]['campaign_end_date']) ? $arrClientCampaign[$i]['campaign_end_date'] : 0;
				$arr[$i]['start_date'] = date('Y-m-d',strtotime($times));
				$arr[$i]['end_date'] = date('Y-m-d',strtotime($timess));
			}
			echo json_encode($arr);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
	function modShowMobileUserCampaignDetails()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] : 0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$clientSearch=isset($_REQUEST['search_client_id']) ? $_REQUEST['search_client_id'] :$_SESSION['search_client_id'];
			if(($_REQUEST['campaign_id'])!=0){
				//get client campaigns  by campaign id
				$arrClientCampaign=array();
				$arrClientCampaign=$this->objPublic->getClientCampaignDetailsByCID($sendArray);
				
				$arr=array();
				$arr[0]['campaign_id']=isset($arrClientCampaign[0]['campaign_id']) ? $arrClientCampaign[0]['campaign_id'] : 0;
				$arr[0]['client_id']=isset($arrClientCampaign[0]['client_id']) ? $arrClientCampaign[0]['client_id'] :0;
				$arr[0]['campaign_name']=isset($arrClientCampaign[0]['campaign_name']) ? $arrClientCampaign[0]['campaign_name'] : '';
				$times=isset($arrClientCampaign[0]['campaign_start_date']) ? $arrClientCampaign[0]['campaign_start_date'] : 0;
				$timess=isset($arrClientCampaign[0]['campaign_end_date']) ? $arrClientCampaign[0]['campaign_end_date'] : 0;
				$arr[0]['start_date'] = date('Y-m-d',strtotime($times));
				$arr[0]['end_date'] = date('Y-m-d',strtotime($timess));
			}else{
				$arr=array();
				$arrData=array();
				$clientSearch=isset($_REQUEST['search_client_id']) ? $_REQUEST['search_client_id'] :$_SESSION['search_client_id'];
				$sendArray['search_client_id']=$clientSearch;
				$arr[0]['campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] : 0;
				$arr[0]['client_id']=$clientSearch;
				$arrData=$this->objPublic->getClientInfoByCID($sendArray);
				$arr[0]['client_name']=isset($arrData[0]['name']) ? $arrData[0]['name'] : '';
				$start_date=isset($_SESSION['start_date']) ? $_SESSION['start_date'] : date('Y-m-d',strtotime('1 month ago'));
				$end_date=isset($_SESSION['end_date']) ? $_SESSION['end_date'] :date('Y-m-d');
				$arr[0]['start_date'] = $start_date;
				$arr[0]['end_date'] = $end_date;
			}
			echo json_encode($arr);

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function modShowMobileUserProductViewsInfo()
	{
		try
		{
		    global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			
			if(($_REQUEST['campaign_id'])!=0){
				$arrClientCampaigns=array();
				$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
				$outArrClientCampaignsproductIds=array();
				$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
				$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
				
				 $outArrCampaignClosetProductDetailPids =array();
				$outArrClientCampaignsProductsViewsForGraph=array();
				$outArrCampaignWishlistProductDetailPids=array();
				$outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph=array();
				$myclosetClientCampaignsAnalyticsViewsForGraph=0;
				$outCampaignsProductAnalyticsViewsForGraph=0;
				$outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph=array();
				$wishlistClientCampaignsProdDetailsViewsForGraph=0;
				$grandTotalClientCampaignsProductViews=0;
				$totalClientCampaignsProductsViews=0;
				$campaignWishlistProductPidsViewsForGraph=0;
				 $campaignClosetProductDetailPidsViewsForGraph=0;
				$arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
				for($i=0;$i<count($arrBetweenDates);$i++)
				{
					$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
					$sendArray['start_date'] =$arrBetweenDates[$i];
				
					$arrClientCampaignsViews=array();
					$outArrClientCampaignsproductIds=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
					$arrClientCampaignsViews=explode(',', $outArrClientCampaignsproductIds);
				for($j=0;$j<count($arrClientCampaignsViews);$j++){
					//get campaign product views from my_offers_analytics table
					$sendArray['campaign_product_ids']=$arrClientCampaignsViews[$j];
					$outArrClientCampaignsProductsViewsForGraph = $this->objPublic->getClientCampaignsProductsForDashboardGraph($sendArray);	
					$outCampaignsProductAnalyticsViewsForGraph += isset($outArrClientCampaignsProductsViewsForGraph[0]['productViews']) ? $outArrClientCampaignsProductsViewsForGraph[0]['productViews'] : 0;
				
					$sendArray['datapoint_id']='35,37';//my closet product details
					 $outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph = $this->objPublic->getClientCampaignsAllClosetProductDetailsForDashboard($sendArray);
					$myclosetClientCampaignsAnalyticsViewsForGraph += isset($outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph[0]['closetViews']) ? $outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph[0]['closetViews'] : 0;
					
					$sendArray['datapoint_id']=36;//wishlist product details
					$outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph = $this->objPublic->getClientCampaignsAllWishlistProductDetailsForDashboard($sendArray);
					$wishlistClientCampaignsProdDetailsViewsForGraph += isset($outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph[0]['wishlistViews']) ? $outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph[0]['wishlistViews'] : 0;
					
					$sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
					$outArrCampaignClosetProductDetailPids = $this->objPublic->getClientCampaignAllClosetProdutDetailsFromPAnalytics($sendArray);
					$campaignClosetProductDetailPidsViewsForGraph += isset($outArrCampaignClosetProductDetailPids[0]['productViews']) ? $outArrCampaignClosetProductDetailPids[0]['productViews'] : 0;
					$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
					$outArrCampaignWishlistProductDetailPids = $this->objPublic->getClientCampaignAllWishlistProdutDetailsFromPAnalytics($sendArray);
					$campaignWishlistProductPidsViewsForGraph += isset($outArrCampaignWishlistProductDetailPids[0]['productViews']) ? $outArrCampaignWishlistProductDetailPids[0]['productViews'] : 0;
					   }
			   //total product views
				$grandTotalClientCampaignsProductViews=$outCampaignsProductAnalyticsViewsForGraph+$myclosetClientCampaignsAnalyticsViewsForGraph+$wishlistClientCampaignsProdDetailsViewsForGraph+$campaignClosetProductDetailPidsViewsForGraph+$campaignWishlistProductPidsViewsForGraph;
				$arrDataForGraph[$i]['productids'] = $grandTotalClientCampaignsProductViews;
				$totalClientCampaignsProductsViews = $grandTotalClientCampaignsProductViews;
				}
				$outArrResults[0]['productViews'] =  $totalClientCampaignsProductsViews;
			  }else{
				  
				 $outArrTotalProductViewsForGraph=array();
				 $prodAnalyticsViews=0;
				
				 $outArrTotalMyclosetProductDetailsViewsForGraph=array();
				 $myclosetProdDetailsViewsForGraph=0;

				 $outArrTotalWishlistProductDetailsViewsForGraph=array();
			     $wishlistProdDetailsViewsForGraph=0;
		
				 $outArrWishlistProductDetailPids=array();
				 $outArrClosetProductDetailPids=array();
				 $totalProductsViews =0;
			     $grandTotalProductViews=0;
				
			     $outArrWishlistProductPids=0;
			     $outArrClosetProductPids=0;
			   
				
				$arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			 for($i=0;$i<count($arrBetweenDates);$i++){
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
				$outArrTotalProductViewsForGraph = $this->objPublic->getClientAllProductsForDashboardGraph($sendArray);//excluding datapoint 4 and 3
				$prodAnalyticsViews+= isset($outArrTotalProductViewsForGraph[0]['productViews']) ? $outArrTotalProductViewsForGraph[0]['productViews'] : 0;
				//get all products by clientid from products table
			    $outArrClientPIds=array();
			    $outArrClientPIds = $this->objPublic->getProductsByCID($sendArray);
				//print_r($outArrClientPIds);
				for($j=0;$j<count($outArrClientPIds);$j++){
						$sendArray['product_id']=isset($outArrClientPIds[$j]['pd_id']) ? $outArrClientPIds[$j]['pd_id'] :0;
						$sendArray['end_date'] =$arrBetweenDates[$i];
						//mycloset product details views
						$sendArray['datapoint_id']='35,37';//my closet product details
						$outArrTotalMyclosetProductDetailsViewsForGraph = $this->objPublic->getClientAllClosetProductDetailsForDashboard($sendArray);
						$myclosetProdDetailsViewsForGraph += isset($outArrTotalMyclosetProductDetailsViewsForGraph[0]['closetViews']) ? $outArrTotalMyclosetProductDetailsViewsForGraph[0]['closetViews'] : 0;
						//wishlist product details views
						$sendArray['datapoint_id']=36;//wishlist product details
						$outArrTotalWishlistProductDetailsViewsForGraph = $this->objPublic->getClientAllWishlistProductDetailsForDashboard($sendArray);
						$wishlistProdDetailsViewsForGraph += isset($outArrTotalWishlistProductDetailsViewsForGraph[0]['wishlistViews']) ? $outArrTotalWishlistProductDetailsViewsForGraph[0]['wishlistViews'] : 0;
						$sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
						$outArrClosetProductDetailPids = $this->objPublic->getClientAllClosetProdutDetailsFromPAnalytics($sendArray);
						$outArrClosetProductPids += isset($outArrClosetProductDetailPids[0]['productViews']) ? $outArrClosetProductDetailPids[0]['productViews'] : 0;
						$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
						$outArrWishlistProductDetailPids = $this->objPublic->getClientAllWishlistProdutDetailsFromPAnalytics($sendArray);
						$outArrWishlistProductPids += isset($outArrWishlistProductDetailPids[0]['productViews']) ? $outArrWishlistProductDetailPids[0]['productViews'] : 0;
			        }
				//total product views
			    $grandTotalProductViews=$prodAnalyticsViews+$myclosetProdDetailsViewsForGraph+$wishlistProdDetailsViewsForGraph+$outArrClosetProductPids+$outArrWishlistProductPids;
		        $arrDataForGraph[$i]['productids'] = $grandTotalProductViews;
		        $totalProductsViews = $grandTotalProductViews;
		   	}
			  $outArrResults[0]['productViews'] =  $totalProductsViews;
		     }
		echo json_encode($outArrResults);
	    }catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	function modShowMobileUserOfferViewsInfo()
	  {
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			if(($_REQUEST['campaign_id'])!=0){
			$arrClientCampaigns=array();
			$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
			//offers
			$outArrOfferClientCampaignsAnalyticsViews=array();
			$outArrMyoffersClientCampaignsAnalyticsViews=array();
			 $grandTotalClientCampaignsOfferViews=0;
			$totaClientCampaignslOffersViews=0;
			$myOffersCampaignsAnalyticsViews=0;
		    $offerClientCampaignsAnalyticsViews=0;
			$arrDataForGraph=array();
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++){
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
               //offers  
				$outArrClientCampaignsOfferIds=array();
				$arrClientCampaignsViews=array();
				$outArrClientCampaignsOfferIds=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
				$arrClientCampaignsViews=explode(',', $outArrClientCampaignsOfferIds);
			    for($j=0;$j<count($arrClientCampaignsViews);$j++){
			    	//get campaign product views from my_offers_analytics table
					 $sendArray['campaign_offer_ids']=$arrClientCampaignsViews[$j];
			    	 $sendArray['datapoint_id']=9;//my offers
			    	 $sendArray['end_date'] =$arrBetweenDates[$i];
					 $outArrOfferClientCampaignsAnalyticsViews = $this->objPublic->getClientCampaignsAllOffersForDashboard($sendArray);//excluding 10
		        	 $offerClientCampaignsAnalyticsViews += isset($outArrOfferClientCampaignsAnalyticsViews[0]['offerViews']) ? $outArrOfferClientCampaignsAnalyticsViews[0]['offerViews'] : 0;
				     $outArrMyoffersClientCampaignsAnalyticsViews = $this->objPublic->getClientCampaignsOffersByDpIdOfferId($sendArray);
				     $myOffersCampaignsAnalyticsViews += isset($outArrMyoffersClientCampaignsAnalyticsViews[0]['offerViews']) ? $outArrMyoffersClientCampaignsAnalyticsViews[0]['offerViews'] : 0;
			          }
				 //total offer views
				$grandTotalClientCampaignsOfferViews=$offerClientCampaignsAnalyticsViews+$myOffersCampaignsAnalyticsViews;
				//print_r($offerClientCampaignsAnalyticsViews);
				//$arrDataForGraph[$i]['offerids'] = $grandTotalClientCampaignsOfferViews;
				$totaClientCampaignslOffersViews = $grandTotalClientCampaignsOfferViews;
			   }
			 $outArrResults[0]['offerViews'] = $totaClientCampaignslOffersViews;
			 }else{
				$outArrTotalOfferViewsForGraph=array();
				$outArrOfferAnalyticsViews=array();    
				$outArrMyoffersAnalyticsViews=array();
				$grandTotalOfferViews=0;
				$totalOffersViews =0;
				$offerAnalyticsViews=0; 
				$myOffersAnalyticsViews=0;
				$outArrClientOfferIds=array();
				$arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
				for($i=0;$i<count($arrBetweenDates);$i++)
				{
					$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
					$sendArray['start_date'] =$arrBetweenDates[$i];
					 ///////////offer views////////
					 //get all offers by clientid from offers table 
					$sendArray['end_date'] =$arrBetweenDates[$i];
					$outArrOfferAnalyticsViews = $this->objPublic->getClientAllOffersForDashboard($sendArray);//excluding 10
					$offerAnalyticsViews += isset($outArrOfferAnalyticsViews[0]['offerViews']) ? $outArrOfferAnalyticsViews[0]['offerViews'] : 0;
					//get all offers by clientid from offers table
					$outArrClientOfferIds = $this->objPublic->getOffersByCID($sendArray);
				    for($j=0;$j<count($outArrClientOfferIds);$j++){
						    $sendArray['offer_id']=isset($outArrClientOfferIds[$j]['offer_id']) ? $outArrClientOfferIds[$j]['offer_id'] :0;
							//get product views from my_offers_analytics table
							$sendArray['datapoint_id']=9;//my offers
							$sendArray['end_date'] =$arrBetweenDates[$i];
							$outArrMyoffersAnalyticsViews = $this->objPublic->getClientOffersByDpIdOfferId($sendArray);
							$myOffersAnalyticsViews += isset($outArrMyoffersAnalyticsViews[0]['offerViews']) ? $outArrMyoffersAnalyticsViews[0]['offerViews'] : 0;
					    }
				//offers total
				$grandTotalOfferViews=$offerAnalyticsViews+$myOffersAnalyticsViews;
				//$arrDataForGraph[$i]['offerids'] = $grandTotalOfferViews;
				$totalOffersViews = $grandTotalOfferViews;
			    }
			    $outArrResults[0]['offerViews'] = $totalOffersViews;
			  }
		  echo json_encode($outArrResults);
        }catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	} 	
	function modShowMobileUserProductShareViewsInfo()
	{
		try
		{
		    global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			
			if(($_REQUEST['campaign_id'])!=0){
			$arrClientCampaigns=array();
			$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
			
			//share
			 $totalClientCampaignsProductsShareViews=0;
			 $outArrShareClientCampaignsPidsForGraph=array();
			 $outArrClientCampaignsproductIds=array();
			$arrDataForGraph=array();
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
				$arrClientCampaignsViews=array();
			    $outArrClientCampaignsproductIds=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			    $arrClientCampaignsViews=explode(',', $outArrClientCampaignsproductIds);
			    for($j=0;$j<count($arrClientCampaignsViews);$j++){
			    	//get campaign product views from my_offers_analytics table
					$sendArray['campaign_product_ids']=$arrClientCampaignsViews[$j];
					//campaign product shareviews
					$sendArray['datapoint_id']="6,7,8,18";
					$outArrShareClientCampaignsPidsForGraph = $this->objPublic->getClientCampaignsShareProductsForDashboardGraph($sendArray);
					$arrDataForGraph[$i]['product_shareViews'] = isset($outArrShareClientCampaignsPidsForGraph[0]['productViews']) ? $outArrShareClientCampaignsPidsForGraph[0]['productViews'] : 0;
					$totalClientCampaignsProductsShareViews += $arrDataForGraph[$i]['product_shareViews'];
			    }
			}
			$outArrResults[0]['product_shareViews'] = $totalClientCampaignsProductsShareViews;
		    }else{
			    $outArrSharePidsForGraph=array();   
			    $totalProductsShareViews =0;
			    $arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
				for($i=0;$i<count($arrBetweenDates);$i++)
				{
					$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
					$sendArray['start_date'] =$arrBetweenDates[$i];
					
						//product share views
					$sendArray['datapoint_id']="6,7,8,18";
					$outArrSharePidsForGraph = $this->objPublic->getClientProductsForDashboardGraph($sendArray);
					$arrDataForGraph[$i]['product_shareViews'] = isset($outArrSharePidsForGraph[0]['productViews']) ? $outArrSharePidsForGraph[0]['productViews'] : 0;
					$totalProductsShareViews += $arrDataForGraph[$i]['product_shareViews'];
				}
				
				 $outArrResults[0]['product_shareViews'] = $totalProductsShareViews;
		
		  }
		echo json_encode($outArrResults);
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOfferShareViewsInfo()
	{
		try
		{
		    global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			if(($_REQUEST['campaign_id'])!=0){
			$arrClientCampaigns=array();
			$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
		
			//share
			 $totalClientCampaignsOffersShareViews=0;
			 $outArrShareClientCampaignsOfferIdsForGraph =array();
			 $outArrClientCampaignsOfferIds=array();
			 $arrClientCampaignsViews=array();
			$arrDataForGraph=array();
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
				//campaign offer shareviews
			    $outArrClientCampaignsOfferIds=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
		     	$arrClientCampaignsViews=explode(',', $outArrClientCampaignsOfferIds);
			    for($j=0;$j<count($arrClientCampaignsViews);$j++)
			    {
			    	//get campaign product views from my_offers_analytics table
					$sendArray['campaign_offer_ids']=$arrClientCampaignsViews[$j];
			    	$sendArray['datapoint_id']="11,12,13,17";
				    $outArrShareClientCampaignsOfferIdsForGraph = $this->objPublic->getClientCampaignsShareOffersForDashboardGraph($sendArray);
			        $arrDataForGraph[$i]['offer_shareViews'] = isset($outArrShareClientCampaignsOfferIdsForGraph[0]['offerViews']) ? $outArrShareClientCampaignsOfferIdsForGraph[0]['offerViews'] : 0;
			        $totalClientCampaignsOffersShareViews += $arrDataForGraph[$i]['offer_shareViews'];
			    }
			   
			    
			
			}
			$outArrResults[0]['offer_shareViews'] = $totalClientCampaignsOffersShareViews; 
		    }else{
		   
		    $outArrShareOfferIdsForGraph=array();
			$totalOffersShareViews=0;
            $arrDataForGraph=array();
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
                //offer share views
			    $sendArray['datapoint_id']="11,12,13,17";
			    $outArrShareOfferIdsForGraph = $this->objPublic->getClientOffersForDashboardGraph($sendArray);
			    $arrDataForGraph[$i]['offer_shareViews'] = isset($outArrShareOfferIdsForGraph[0]['offerViews']) ? $outArrShareOfferIdsForGraph[0]['offerViews'] : 0;
			    $totalOffersShareViews += $arrDataForGraph[$i]['offer_shareViews'];
		   	}
		     $outArrResults[0]['offer_shareViews'] = $totalOffersShareViews;
			
		  }
		echo json_encode($outArrResults);
	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOfferViewsForGraph()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			if(($_REQUEST['campaign_id'])!=0){
			$arrClientCampaigns=array();
			$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			//$outCampaignsProductIds=array();
			$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
		
			//offers
			$outArrOfferClientCampaignsAnalyticsViews=array();
			$outArrMyoffersClientCampaignsAnalyticsViews=array();
			$outArrClientCampaignsOfferIds=array();
			 $grandTotalClientCampaignsOfferViews=0;
			$totalClientCampaignsOffersViews=0;
			$arrDataForGraph=array();
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
                //offers
				$myOffersCampaignsAnalyticsViews=0;
				$offerClientCampaignsAnalyticsViews=0;
				$arrClientCampaignsViews=array();
				$outArrClientCampaignsOfferIds=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
				$arrClientCampaignsViews=explode(',', $outArrClientCampaignsOfferIds);
				for($j=0;$j<count($arrClientCampaignsViews);$j++)
					{
						//get campaign product views from my_offers_analytics table
						$sendArray['campaign_offer_ids']=$arrClientCampaignsViews[$j];
						$sendArray['end_date'] =$arrBetweenDates[$i];
						$outArrOfferClientCampaignsAnalyticsViews = $this->objPublic->getClientCampaignsAllOffersForDashboard($sendArray);//excluding 10
						$offerClientCampaignsAnalyticsViews += isset($outArrOfferClientCampaignsAnalyticsViews[0]['offerViews']) ? $outArrOfferClientCampaignsAnalyticsViews[0]['offerViews'] : 0;
						$sendArray['datapoint_id']=9;//my offers
						$outArrMyoffersClientCampaignsAnalyticsViews = $this->objPublic->getClientCampaignsOffersByDpIdOfferId($sendArray);
						$myOffersCampaignsAnalyticsViews += isset($outArrMyoffersClientCampaignsAnalyticsViews[0]['offerViews']) ? $outArrMyoffersClientCampaignsAnalyticsViews[0]['offerViews'] : 0;
					}
			    //total offer views
			    $grandTotalClientCampaignsOfferViews=$offerClientCampaignsAnalyticsViews+$myOffersCampaignsAnalyticsViews;
			    $arrDataForGraph[$i]['offerids'] = $grandTotalClientCampaignsOfferViews;
			    }
			}else{
				$outArrTotalOfferViewsForGraph=array();
			   
				$outArrOfferAnalyticsViews=array();    
				$outArrMyoffersAnalyticsViews=array();
				$grandTotalOfferViews=0;
				$totalOffersViews =0;
				$arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
				for($i=0;$i<count($arrBetweenDates);$i++)
				{
					$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
					$sendArray['start_date'] =$arrBetweenDates[$i];
					 ///////////offer views////////
					 //get all offers by clientid from offers table 
					$myOffersAnalyticsViews=0;
					$offerAnalyticsViews=0; 
					$sendArray['end_date'] =$arrBetweenDates[$i];
					$outArrOfferAnalyticsViews = $this->objPublic->getClientAllOffersForDashboard($sendArray);//excluding 10
					
					$offerAnalyticsViews += isset($outArrOfferAnalyticsViews[0]['offerViews']) ? $outArrOfferAnalyticsViews[0]['offerViews'] : 0;

			   
			
					//get all offers by clientid from offers table
					$outArrClientOfferIds=array();
					$outArrClientOfferIds = $this->objPublic->getOffersByCID($sendArray);

					for($j=0;$j<count($outArrClientOfferIds);$j++)
					{
						$sendArray['offer_id']=isset($outArrClientOfferIds[$j]['offer_id']) ? $outArrClientOfferIds[$j]['offer_id'] :0;
						//get product views from my_offers_analytics table
						$sendArray['datapoint_id']=9;//my offers
						$sendArray['end_date'] =$arrBetweenDates[$i];
						$outArrMyoffersAnalyticsViews = $this->objPublic->getClientOffersByDpIdOfferId($sendArray);
						$myOffersAnalyticsViews += isset($outArrMyoffersAnalyticsViews[0]['offerViews']) ? $outArrMyoffersAnalyticsViews[0]['offerViews'] : 0;
					}
				
				   //offers total for graph
					$grandTotalOfferViews=$offerAnalyticsViews+$myOffersAnalyticsViews;
					$arrDataForGraph[$i]['offerids'] = $grandTotalOfferViews;
			}
		}
			echo json_encode($arrDataForGraph);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	} 
	
	
	function modShowMobileUserProductViewsForGraph()
	{
		try
		{
		    global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			
			if(($_REQUEST['campaign_id'])!=0){
			$arrClientCampaigns=array();
			$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
			$outArrClientCampaignsProductsViewsForGraph=array();
			$outArrCampaignClosetProductDetailPids=array();
			$outArrCampaignWishlistProductDetailPids=array();
			$outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph=array();
			
            $grandTotalClientCampaignsProductViews=0;
			$outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph=array();
			
			$outArrClientCampaignsproductIds=array();
			$arrDataForGraph=array();
			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
			$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
			$sendArray['start_date'] =$arrBetweenDates[$i];
			$outCampaignsProductAnalyticsViewsForGraph=0;
			$myclosetClientCampaignsAnalyticsViewsForGraph=0;
			$wishlistClientCampaignsProdDetailsViewsForGraph=0;
			$campaignClosetProductDetailPidsViewsForGraph=0;
			$campaignWishlistProductPidsViewsForGraph=0;
			$arrClientCampaignsViews=array();
			$outArrClientCampaignsproductIds=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$arrClientCampaignsViews=explode(',', $outArrClientCampaignsproductIds);
			for($j=0;$j<count($arrClientCampaignsViews);$j++)
			    {
			    	//get campaign product views from my_offers_analytics table
				$sendArray['campaign_product_ids']=$arrClientCampaignsViews[$j];
				$outArrClientCampaignsProductsViewsForGraph = $this->objPublic->getClientCampaignsProductsForDashboardGraph($sendArray);	
				$outCampaignsProductAnalyticsViewsForGraph += isset($outArrClientCampaignsProductsViewsForGraph[0]['productViews']) ? $outArrClientCampaignsProductsViewsForGraph[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']='35,37';//my closet product details
				$outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph = $this->objPublic->getClientCampaignsAllClosetProductDetailsForDashboard($sendArray);
				$myclosetClientCampaignsAnalyticsViewsForGraph += isset($outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph[0]['closetViews']) ? $outArrTotalMyclosetClientCampaignsProductDetailsViewsForGraph[0]['closetViews'] : 0;
				
				$sendArray['datapoint_id']=36;//wishlist product details
				$outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph = $this->objPublic->getClientCampaignsAllWishlistProductDetailsForDashboard($sendArray);
				$wishlistClientCampaignsProdDetailsViewsForGraph += isset($outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph[0]['wishlistViews']) ? $outArrTotalClientCampaignsWishlistProductDetailsViewsForGraph[0]['wishlistViews'] : 0;
				
				$sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
				$outArrCampaignClosetProductDetailPids = $this->objPublic->getClientCampaignAllClosetProdutDetailsFromPAnalytics($sendArray);
				$campaignClosetProductDetailPidsViewsForGraph += isset($outArrCampaignClosetProductDetailPids[0]['productViews']) ? $outArrCampaignClosetProductDetailPids[0]['productViews'] : 0;
			
				$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
				$outArrCampaignWishlistProductDetailPids = $this->objPublic->getClientCampaignAllWishlistProdutDetailsFromPAnalytics($sendArray);
				$campaignWishlistProductPidsViewsForGraph += isset($outArrCampaignWishlistProductDetailPids[0]['productViews']) ? $outArrCampaignWishlistProductDetailPids[0]['productViews'] : 0;
				}
		    //total product views
		     $grandTotalClientCampaignsProductViews=$outCampaignsProductAnalyticsViewsForGraph+$myclosetClientCampaignsAnalyticsViewsForGraph+$wishlistClientCampaignsProdDetailsViewsForGraph+$campaignClosetProductDetailPidsViewsForGraph+$campaignWishlistProductPidsViewsForGraph;
			 $arrDataForGraph[$i]['productids'] = $grandTotalClientCampaignsProductViews;
		    }
		    }else{
				$outArrTotalProductViewsForGraph=array();
				$outArrTotalMyclosetProductDetailsViewsForGraph=array();
				$outArrTotalWishlistProductDetailsViewsForGraph=array();
				$outArrWishlistProductDetailPids=array();
				$outArrClosetProductDetailPids=array();
				$totalProductsViews =0;
				$grandTotalProductViews=0;
				$arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
				for($i=0;$i<count($arrBetweenDates);$i++)
				{
					$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
					$sendArray['start_date'] =$arrBetweenDates[$i];
					$prodAnalyticsViews=0;
					$outArrWishlistProductPids=0;
					$myclosetProdDetailsViewsForGraph=0;
					$wishlistProdDetailsViewsForGraph=0;
					$outArrClosetProductPids=0;
				    $outArrTotalProductViewsForGraph = $this->objPublic->getClientAllProductsForDashboardGraph($sendArray);//excluding datapoint 4 and 3
				    $prodAnalyticsViews+= isset($outArrTotalProductViewsForGraph[0]['productViews']) ? $outArrTotalProductViewsForGraph[0]['productViews'] : 0;
				    //get all products by clientid from products table
			        $outArrClientPIds=array();
			        $outArrClientPIds = $this->objPublic->getProductsByCID($sendArray);
					for($j=0;$j<count($outArrClientPIds);$j++)
					{
						$sendArray['product_id']=isset($outArrClientPIds[$j]['pd_id']) ? $outArrClientPIds[$j]['pd_id'] :0;
						$sendArray['end_date'] =$arrBetweenDates[$i];
				        //mycloset product details views
		                $sendArray['datapoint_id']='35,37';//my closet product details
			    	    $outArrTotalMyclosetProductDetailsViewsForGraph = $this->objPublic->getClientAllClosetProductDetailsForDashboard($sendArray);
		                $myclosetProdDetailsViewsForGraph += isset($outArrTotalMyclosetProductDetailsViewsForGraph[0]['closetViews']) ? $outArrTotalMyclosetProductDetailsViewsForGraph[0]['closetViews'] : 0;
						//wishlist product details views
						$sendArray['datapoint_id']=36;//wishlist product details
						$outArrTotalWishlistProductDetailsViewsForGraph = $this->objPublic->getClientAllWishlistProductDetailsForDashboard($sendArray);
						$wishlistProdDetailsViewsForGraph += isset($outArrTotalWishlistProductDetailsViewsForGraph[0]['wishlistViews']) ? $outArrTotalWishlistProductDetailsViewsForGraph[0]['wishlistViews'] : 0;
						$sendArray['datapoint_id']=3;// get mycloset pd deails from product_analytics 
						$outArrClosetProductDetailPids = $this->objPublic->getClientAllClosetProdutDetailsFromPAnalytics($sendArray);
						$outArrClosetProductPids += isset($outArrClosetProductDetailPids[0]['productViews']) ? $outArrClosetProductDetailPids[0]['productViews'] : 0;
						
						$sendArray['datapoint_id']=3;// get wishlist pd deails from product_analytics 
						$outArrWishlistProductDetailPids = $this->objPublic->getClientAllWishlistProdutDetailsFromPAnalytics($sendArray);
						$outArrWishlistProductPids += isset($outArrWishlistProductDetailPids[0]['productViews']) ? $outArrWishlistProductDetailPids[0]['productViews'] : 0;
			        }
					//total product views
					$grandTotalProductViews=$prodAnalyticsViews+$myclosetProdDetailsViewsForGraph+$wishlistProdDetailsViewsForGraph+$outArrClosetProductPids+$outArrWishlistProductPids;
					$arrDataForGraph[$i]['productids'] = $grandTotalProductViews;
		    	}
		     }
		echo json_encode($arrDataForGraph);
	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	 
		
	function modShowMobileUserProductShareViewsForGraph()
	{
		try
		{
		    global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			
			if(($_REQUEST['campaign_id'])!=0){
			
				$arrClientCampaigns=array();
				$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
				$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
				$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
				
				//share
				$totalClientCampaignsProductsShareViews=0;
				$outArrShareClientCampaignsPidsForGraph=array();
				$arrDataForGraph=array();
				$arrBetweenDates=array();
				$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
				$outArrClientCampaignsproductIds=array();
				for($i=0;$i<count($arrBetweenDates);$i++)
				{
					$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
					$sendArray['start_date'] =$arrBetweenDates[$i];
					$outArrShareCampaignsPidsForGraph=0;
					$arrClientCampaignsViews=array();
					$outArrClientCampaignsproductIds=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
					$arrClientCampaignsViews=explode(',', $outArrClientCampaignsproductIds);
					//print_r($arrClientCampaignsViews);
					for($j=0;$j<count($arrClientCampaignsViews);$j++){
						//get campaign product views from my_offers_analytics table
						$sendArray['campaign_product_ids']=$arrClientCampaignsViews[$j];
						//campaign product shareviews
						$sendArray['datapoint_id']="6,7,8,18";
						$outArrShareClientCampaignsPidsForGraph = $this->objPublic->getClientCampaignsShareProductsForDashboardGraph($sendArray);
						$outArrShareCampaignsPidsForGraph += isset($outArrShareClientCampaignsPidsForGraph[0]['productViews']) ? $outArrShareClientCampaignsPidsForGraph[0]['productViews'] : 0;
							}
			    $arrDataForGraph[$i]['product_shareViews']=  $outArrShareCampaignsPidsForGraph;
			 }
		  } else{
			  $outArrSharePidsForGraph=array();   
			  $arrDataForGraph=array();
			  $arrBetweenDates=array();
			  $arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			  for($i=0;$i<count($arrBetweenDates);$i++)
			  {
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
				$outArrShareProductPidsForGraph=0;
				//product share views
			    $sendArray['datapoint_id']="6,7,8,18";
			    $outArrSharePidsForGraph = $this->objPublic->getClientProductsForDashboardGraph($sendArray);
         		$outArrShareProductPidsForGraph +=isset($outArrSharePidsForGraph[0]['productViews']) ? $outArrSharePidsForGraph[0]['productViews'] : 0;
			    $arrDataForGraph[$i]['product_shareViews'] =$outArrShareProductPidsForGraph ;
		       }
		
		  }
			echo json_encode($arrDataForGraph);
	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
 function modShowMobileUserOfferShareViewsForGraph()
	{
		try
		{
		    global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['search_client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			$sendArray['search_campaign_id']=isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] :0;
			$outArrResults=array();
			if(($_REQUEST['campaign_id'])!=0){
			$arrClientCampaigns=array();
			$arrClientCampaigns=$this->objPublic->getClientCampaignsInfoByIds($sendArray);
			$sendArray['campaign_product_ids']=isset($arrClientCampaigns[0]['campaign_product_ids']) ? $arrClientCampaigns[0]['campaign_product_ids'] : 0;
			$sendArray['campaign_offer_ids']=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
		
			//share
			 $totalClientCampaignsOffersShareViews=0;
			 $outArrShareClientCampaignsOfferIdsForGraph =array();
			$arrDataForGraph=array();
			$arrBetweenDates=array();
			$outArrClientCampaignsOfferIds=array();
			$arrClientCampaignsViews=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
				//campaign offer shareviews
				$outArrShareCampaignsOfferIdsForGraph=0;
				$outArrClientCampaignsOfferIds=isset($arrClientCampaigns[0]['campaign_offer_ids']) ? $arrClientCampaigns[0]['campaign_offer_ids'] : 0;
				$arrClientCampaignsViews=explode(',', $outArrClientCampaignsOfferIds);
			    for($l=0;$l<count($arrClientCampaignsViews);$l++)
			    {
			    	//get campaign product views from my_offers_analytics table
					$sendArray['campaign_offer_ids']=$arrClientCampaignsViews[$l];
			    	$sendArray['datapoint_id']="11,12,13,17";
			    	$sendArray['end_date'] =$arrBetweenDates[$i];
					$outArrShareClientCampaignsOfferIdsForGraph = $this->objPublic->getClientCampaignsShareOffersForDashboardGraph($sendArray);
					$outArrShareCampaignsOfferIdsForGraph +=isset($outArrShareClientCampaignsOfferIdsForGraph[0]['offerViews']) ? $outArrShareClientCampaignsOfferIdsForGraph[0]['offerViews'] : 0;
			
			    }
			   
		    $arrDataForGraph[$i]['offer_shareViews']=$outArrShareCampaignsOfferIdsForGraph;
			 }
		}else{
		   
		    $outArrShareOfferIdsForGraph=array();
			$totalOffersShareViews=0;
	
		    $arrDataForGraph=array();
			$arrBetweenDates=array();
		
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
			    $outClientShareOfferIdsForGraph=0;
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				$sendArray['start_date'] =$arrBetweenDates[$i];
                //offer share views
			    $sendArray['datapoint_id']="11,12,13,17";
			    $outArrShareOfferIdsForGraph = $this->objPublic->getClientOffersForDashboardGraph($sendArray);
			    $outClientShareOfferIdsForGraph += isset($outArrShareOfferIdsForGraph[0]['offerViews']) ? $outArrShareOfferIdsForGraph[0]['offerViews'] : 0;
			    $arrDataForGraph[$i]['offer_shareViews']=$outClientShareOfferIdsForGraph ;
		   	}
		  }
			echo json_encode($arrDataForGraph);
	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserTrackDataByProductsViews()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			
			$outArrResults=array();

			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
            
            $arrDataForGraph=array();
            
            
            $outArrTotalProductViewsForGraph=array();
                    
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				
				$outArrTotalProductViewsForGraph = $this->objPublic->getClientAllProductsForDashboardGraph($arrBetweenDates[$i]);
		        $arrDataForGraph[$i]['productids'] = isset($outArrTotalProductViewsForGraph[0]['productViews']) ? $outArrTotalProductViewsForGraph[0]['productViews'] : 0;

	

			}
			echo json_encode($arrDataForGraph);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserClientProdViews()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			
			$outArrResults=array();
			$outArrResults = $this->objPublic->getClientProducts($sendArray);
		    
  
            for($i=0;$i<count($outArrResults);$i++)
			{
				$arrData[$i]['client_id']=isset($outArrResults[$i]['client_id']) ? $outArrResults[$i]['client_id'] : '';
				$arrData[$i]['client_name']=isset($outArrResults[$i]['client_name']) ? $outArrResults[$i]['client_name'] : '';
				$sendArray['client_id']=$arrData[$i]['client_id'];
				$outArrGetUsers = $this->objPublic->getClientProductsUsersByCID($sendArray);
				$outArrGetProducts = $this->objPublic->getClientProductsViewsByCID($sendArray);
				$arrData[$i]['no_of_users']=count($outArrGetUsers);
				$arrData[$i]['no_of_products']=count($outArrGetProducts);


			}
            
            echo json_encode($arrData);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserTrackDataByProductsViewsByDate()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			
			$outArrResults=array();
            $outArrGetProducts=array();

			$outArrClientProdIds = array();
			$outArrClientProdIds = $this->objPublic->getClientProductsDetails($sendArray);

			$outArrResults = array();
			for ($i=0; $i < count($outArrClientProdIds); $i++) 
			{ 
				$productId=isset($outArrClientProdIds[$i]['pd_id']) ? $outArrClientProdIds[$i]['pd_id'] : '';
            	$outArrResults[$i]['productId']=$productId;
            	$outArrResults[$i]['client_id']=isset($outArrClientProdIds[$i]['client_id']) ? $outArrClientProdIds[$i]['client_id'] : '';
            	$outArrResults[$i]['productName']=isset($outArrClientProdIds[$i]['pd_name']) ? $outArrClientProdIds[$i]['pd_name'] : '';
            	$outArrResults[$i]['pd_image']=isset($outArrClientProdIds[$i]['pd_image']) ? $outArrClientProdIds[$i]['pd_image'] : '';
            	$sendArray['product_id']=$productId;
            	$outArrGetProducts = $this->objPublic->getClientProductsViewsByPID($sendArray);
				$outArrResults[$i]['totalViews'] = count($outArrGetProducts);
                

			}
            echo json_encode($outArrResults);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function createDateRangeArray($strDateFrom,$strDateTo)
	{
	   try{
		// takes two dates formatted as YYYY-MM-DD and creates an
		// inclusive array of the dates between the from and to dates.

		// could test validity of dates here but I'm already doing
		// that in the main script

		$aryRange=array();

		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
		$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

		if ($iDateTo>=$iDateFrom)
		{
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo)
			{
				$iDateFrom+=86400; // add 24 hours
				array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
		return $aryRange;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserClientOfferViews()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			
			$outArrResults=array();
			$outArrResults = $this->objPublic->getClientOffers($sendArray);
		    
  
            for($i=0;$i<count($outArrResults);$i++)
			{
				$arrData[$i]['client_id']=isset($outArrResults[$i]['client_id']) ? $outArrResults[$i]['client_id'] : '';
				$arrData[$i]['client_name']=isset($outArrResults[$i]['client_name']) ? $outArrResults[$i]['client_name'] : '';
				$sendArray['client_id']=$arrData[$i]['client_id'];
				$outArrGetUsers = $this->objPublic->getClientOffersUsersByCID($sendArray);
				$outArrGetOffers = $this->objPublic->getClientOffersViewsByCID($sendArray);
				$arrData[$i]['no_of_users']=count($outArrGetUsers);
				$arrData[$i]['no_of_offers']=count($outArrGetOffers);


			}
           
            echo json_encode($arrData);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserTrackDataByOffersViews()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			
			$outArrResults=array();

			$arrBetweenDates=array();
			$arrBetweenDates=$this->createDateRangeArray($sendArray['start_date'],$sendArray['end_date']);
            
            $arrDataForGraph=array();
            
            
            $outArrTotalOfferViewsForGraph=array();
                    
			for($i=0;$i<count($arrBetweenDates);$i++)
			{
				$arrDataForGraph[$i]['dates']=$arrBetweenDates[$i];
				
				$outArrTotalOfferViewsForGraph = $this->objPublic->getClientAllOffersForDashboardGraph($arrBetweenDates[$i]);
		        $arrDataForGraph[$i]['offerids'] = isset($outArrTotalOfferViewsForGraph[0]['offerViews']) ? $outArrTotalOfferViewsForGraph[0]['offerViews'] : 0;

	

			}
			echo json_encode($arrDataForGraph);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserTrackDataByOffersViewsByDate()
	{
		try
		{
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['client_id']=isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] :0;
			
			$outArrResults=array();
            $outArrGetOffers=array();

			$outArrClientOfferIds = array();
			$outArrClientOfferIds = $this->objPublic->getClientOffersDetails($sendArray);

			$outArrResults = array();
			for ($i=0; $i < count($outArrClientOfferIds); $i++) 
			{ 
				$offerId=isset($outArrClientOfferIds[$i]['offer_id']) ? $outArrClientOfferIds[$i]['offer_id'] : '';
            	$outArrResults[$i]['offerId']=$offerId;
            	$outArrResults[$i]['client_id']=isset($outArrClientOfferIds[$i]['client_id']) ? $outArrClientOfferIds[$i]['client_id'] : '';
            	$outArrResults[$i]['offer_name']=isset($outArrClientOfferIds[$i]['offer_name']) ? $outArrClientOfferIds[$i]['offer_name'] : '';
            	$outArrResults[$i]['offer_image']=isset($outArrClientOfferIds[$i]['offer_image']) ? $outArrClientOfferIds[$i]['offer_image'] : '';
            	$sendArray['offer_id']=$offerId;
            	$outArrGetOffers = $this->objPublic->getClientOffersViewsByOfferID($sendArray);
				$outArrResults[$i]['totalViews'] = count($outArrGetOffers);
                

			}
        
            echo json_encode($outArrResults);

	    }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowUsers()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            
			

			//total users from all datapoints except 4
		    $outArrUsers1=array();
			$outArrUsers1 = $this->objPublic->getClientProductUsersByDpIds($sendArray);
		
			//get total users from 20,21
			$outArrUsers2=array();
			$sendArray['datapoint_id']=20;//closet
			$outArrUsers2 = $this->objPublic->getClientClosetUsersByDpIds($sendArray);
			$outArrUsers3=array();
			$sendArray['datapoint_id']=21;//wisjhlist
			$outArrUsers3 = $this->objPublic->getClientWishlistUsersByDpIds($sendArray);
		
			$ourArrSortUsers=array_merge($outArrUsers1,$outArrUsers2,$outArrUsers3);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            
            	$sendArray['datapoint_id']="2,30";//scanned  and financial related scan = product views
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    $outArrScannedPids=array();
			    $outArrScannedPids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
			    $arrData[$i]['scannedViews'] = isset($outArrScannedPids[0]['productViews']) ? $outArrScannedPids[0]['productViews'] : 0;

			    $sendArray['datapoint_id']=3;//product details
			    $outArrDetailPids=array();
			    $outArrDetailPids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
	            $arrData[$i]['product_detail_views'] = isset($outArrDetailPids[0]['productViews']) ? $outArrDetailPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']="6,7,8,18";//shared by email,fb,twitter,sms
			    $outArrSharePids=array();
				$outArrSharePids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
				$arrData[$i]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;

				$sendArray['datapoint_id']=5;//cart views
			    $outArrCartPids=array();
			    $outArrCartPids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
			    $arrData[$i]['cartViews'] = isset($outArrCartPids[0]['productViews']) ? $outArrCartPids[0]['productViews'] : 0;

			    $sendArray['datapoint_id']=20;//my closet
				$outArrMyClosetPids=array();
				$outArrMyClosetPids = $this->objPublic->getClientClosetByDpIdUserId($sendArray);
				$arrData[$i]['closetViews'] = isset($outArrMyClosetPids[0]['closetViews']) ? $outArrMyClosetPids[0]['closetViews'] : 0;
				
				$sendArray['datapoint_id']=21;// wishlist
				$outArrWishlistPids=array();
				$outArrWishlistPids = $this->objPublic->getClientWishlistByDpIdUserId($sendArray); 
	            $arrData[$i]['wishlistViews'] = isset($outArrWishlistPids[0]['wishlistViews']) ? $outArrWishlistPids[0]['wishlistViews'] : 0;


            }  


            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowScanned()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']="2,30";//scanned and financial related scan===product views
			$outArrUsers2 = $this->objPublic->getClientProductShareUsersByDpIds($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];

            	$sendArray['datapoint_id']="2,30";//scanned  and financial related scan = product views
            	$outArrScannedPids=array();
			    $outArrScannedPids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
			    $arrData[$i]['scannedViews'] = isset($outArrScannedPids[0]['productViews']) ? $outArrScannedPids[0]['productViews'] : 0;

			    $sendArray['datapoint_id']=3;//product details
			    $outArrDetailPids=array();
			    $outArrDetailPids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
	            $arrData[$i]['product_detail_views'] = isset($outArrDetailPids[0]['productViews']) ? $outArrDetailPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']="6,7,8,18";//shared by email,fb,twitter,sms
			    $outArrSharePids=array();
				$outArrSharePids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
				$arrData[$i]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;

				$sendArray['datapoint_id']=5;//cart views
			    $outArrCartPids=array();
			    $outArrCartPids = $this->objPublic->getClientProductsByDpIdUserId($sendArray);
			    $arrData[$i]['cartViews'] = isset($outArrCartPids[0]['productViews']) ? $outArrCartPids[0]['productViews'] : 0;

			    $sendArray['datapoint_id']=20;//my closet
				$outArrMyClosetPids=array();
				$outArrMyClosetPids = $this->objPublic->getClientClosetByDpIdUserId($sendArray);
				$arrData[$i]['closetViews'] = isset($outArrMyClosetPids[0]['closetViews']) ? $outArrMyClosetPids[0]['closetViews'] : 0;
				
				$sendArray['datapoint_id']=21;// wishlist
				$outArrWishlistPids=array();
				$outArrWishlistPids = $this->objPublic->getClientWishlistByDpIdUserId($sendArray); 
	            $arrData[$i]['wishlistViews'] = isset($outArrWishlistPids[0]['wishlistViews']) ? $outArrWishlistPids[0]['wishlistViews'] : 0;

			    
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowCloset()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=20;//closet
			$outArrUsers2 = $this->objPublic->getClientClosetUsersByDpIds($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientClosetByDpIdUserId($sendArray);
				$arrData[$i]['closetViews'] = isset($outArrMyClosetPids[0]['closetViews']) ? $outArrMyClosetPids[0]['closetViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowWishlist()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=21;//wishlist
			$outArrUsers2 = $this->objPublic->getClientWishlistUsersByDpIds($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientWishlistByDpIdUserId($sendArray);
				$arrData[$i]['wishlistViews'] = isset($outArrMyClosetPids[0]['wishlistViews']) ? $outArrMyClosetPids[0]['wishlistViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowShare()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']="6,7,8,18";//share by email ,fb ,twitter,sms
			$outArrUsers2 = $this->objPublic->getClientProductShareUsersByDpIds($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
		    for($i=0;$i<count($ourArrSortUsers);$i++)
            {

            	
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            		
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$sendArray['datapoint_id']="6,7,8,18";//share by email ,fb ,twitter,sms
            	$outArrSharePids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['shareViews'] = isset($outArrSharePids[0]['productViews']) ? $outArrSharePids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']="6";//share by email
            	$outArrShareEmailPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['emailViews'] = isset($outArrShareEmailPids[0]['productViews']) ? $outArrShareEmailPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']="7";//share by fb 
            	$outArrShareFBPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['facebookViews'] = isset($outArrShareFBPids[0]['productViews']) ? $outArrShareFBPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']="8";//share by twitter
            	$outArrShareTwitterPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['twitterViews'] = isset($outArrShareTwitterPids[0]['productViews']) ? $outArrShareTwitterPids[0]['productViews'] : 0;
				
				$sendArray['datapoint_id']="18";//share by email ,fb ,twitter,sms
            	$outArrShareSMSPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['smsViews'] = isset($outArrShareSMSPids[0]['productViews']) ? $outArrShareSMSPids[0]['productViews'] : 0;
				

            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowShareEmail()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=6;//share product by email
			$outArrUsers2 = $this->objPublic->getClientProductUsersByDpIdPdId($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['emailViews'] = isset($outArrMyClosetPids[0]['productViews']) ? $outArrMyClosetPids[0]['productViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowShareFB()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=7;//share product by FB
			$outArrUsers2 = $this->objPublic->getClientProductUsersByDpIdPdId($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['facebookViews'] = isset($outArrMyClosetPids[0]['productViews']) ? $outArrMyClosetPids[0]['productViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowShareTwitter()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=8;//share product by twitter
			$outArrUsers2 = $this->objPublic->getClientProductUsersByDpIdPdId($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['twitterViews'] = isset($outArrMyClosetPids[0]['productViews']) ? $outArrMyClosetPids[0]['productViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowShareSMS()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=18;//share product by SMS
			$outArrUsers2 = $this->objPublic->getClientProductUsersByDpIdPdId($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['smsViews'] = isset($outArrMyClosetPids[0]['productViews']) ? $outArrMyClosetPids[0]['productViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowCart()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=5;//product cart
			$outArrUsers2 = $this->objPublic->getClientProductUsersByDpIdPdId($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['cartViews'] = isset($outArrMyClosetPids[0]['productViews']) ? $outArrMyClosetPids[0]['productViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserProductsFlowDetails()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['product_id']=isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] :0;
            

			$outArrUsers2=array();
			$sendArray['datapoint_id']=3;//product details 
			$outArrUsers2 = $this->objPublic->getClientProductUsersByDpIdPdId($sendArray);
			$ourArrSortUsers = $this->arrayUnique($outArrUsers2);

            $arrData=array();
            $outArrGetUserInfo =array();
            $outArrMyClosetPids=array();
				
            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            	$outArrMyClosetPids = $this->objPublic->getClientProductViewsByDpIdUserId($sendArray);
				$arrData[$i]['detailsViews'] = isset($outArrMyClosetPids[0]['productViews']) ? $outArrMyClosetPids[0]['productViews'] : 0;
				
            }  

                
            echo json_encode($arrData);

			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	


	function modShowMobileUserOffersFlowUsers()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            
			
			$outArrUsers1=array();
		    $outArrUsers1 = $this->objPublic->getClientOfferUsersByDpIds($sendArray);
			//print_r($outArrUsers1);
			//get total users from 9
			$outArrUsers2=array();
			$sendArray['datapoint_id']=9;//My offers home
			$outArrUsers2 = $this->objPublic->getClientMyOffersUsersByDpIds($sendArray);
			

			$ourArrSortUsers=array_merge($outArrUsers1,$outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            
            	$sendArray['datapoint_id']=14;//removed 
            	$outArrRemoveOfferIds=array();
			    $outArrRemoveOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['removedViews'] = isset($outArrRemoveOfferIds[0]['offerViews']) ? $outArrRemoveOfferIds[0]['offerViews'] : 0;


			    $sendArray['datapoint_id']=15;//redeem  of offer details
			    $outArrRedeemOfferIds=array();
			    $outArrRedeemOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
	            $arrData[$i]['redeemViews'] = isset($outArrRedeemOfferIds[0]['offerViews']) ? $outArrRedeemOfferIds[0]['offerViews'] : 0;
				
				$sendArray['datapoint_id']="11,12,13,17";//shared by email,fb,twitter,sms
			    $outArrShareOfferIds=array();
				$outArrShareOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
				$arrData[$i]['shareViews'] = isset($outArrShareOfferIds[0]['offerViews']) ? $outArrSharePids[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=9;//myoffers home
				$outArrMyOffersOfferIds = $this->objPublic->getClientMyOffersByDpIdUserId($sendArray);
				$arrData[$i]['myOfferViews'] = isset($outArrMyOffersOfferIds[0]['offerViews']) ? $outArrMyOffersOfferIds[0]['offerViews'] : 0;
				

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOffersFlowMyOffers()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            
			$outArrUsers2=array();
			$sendArray['datapoint_id']=9;//My offers home
			$outArrUsers2 = $this->objPublic->getClientMyOffersUsersByDpIds($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
            
				$sendArray['datapoint_id']=9;//myoffers home
				$outArrMyOffersOfferIds = $this->objPublic->getClientMyOffersByDpIdUserId($sendArray);
				$arrData[$i]['myOfferViews'] = isset($outArrMyOffersOfferIds[0]['offerViews']) ? $outArrMyOffersOfferIds[0]['offerViews'] : 0;
				

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOffersFlowShare()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            



			$outArrUsers2=array();
			$sendArray['datapoint_id']="11,12,13,17";//share by email ,fb,twitter,sms
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
                $sendArray['datapoint_id']="11,12,13,17";//share by email ,fb,twitter,sms
            	$outArrShareOfferIds=array();
			    $outArrShareOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['shareViews'] = isset($outArrShareOfferIds[0]['offerViews']) ? $outArrShareOfferIds[0]['offerViews'] : 0;

				$sendArray['datapoint_id']=11;//share by email 
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['emailViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

			    $sendArray['datapoint_id']=12;//share by fb 
            	$outArrShareFBOfferIds=array();
			    $outArrShareFBOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['facebookViews'] = isset($outArrShareFBOfferIds[0]['offerViews']) ? $outArrShareFBOfferIds[0]['offerViews'] : 0;

			    $sendArray['datapoint_id']=13;//share by twitter 
            	$outArrShareTwitterOfferIds=array();
			    $outArrShareTwitterOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['twitterViews'] = isset($outArrShareTwitterOfferIds[0]['offerViews']) ? $outArrShareTwitterOfferIds[0]['offerViews'] : 0;

			    $sendArray['datapoint_id']=17;//share by sms 
            	$outArrShareSMSOfferIds=array();
			    $outArrShareSMSOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['smsViews'] = isset($outArrShareSMSOfferIds[0]['offerViews']) ? $outArrShareSMSOfferIds[0]['offerViews'] : 0;



				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	function modShowMobileUserOffersFlowShareEmail()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            



			$outArrUsers2=array();
			$sendArray['datapoint_id']="11";//share by email ,fb,twitter,sms
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']=11;//share by email 
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['emailViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOffersFlowShareFB()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            



			$outArrUsers2=array();
			$sendArray['datapoint_id']="12";//share by fb
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']=12;//share by fb 
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['facebookViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOffersFlowShareTwitter()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            



			$outArrUsers2=array();
			$sendArray['datapoint_id']="13";//share by twitter
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']=13;//share by twitter
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['twitterViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    function modShowMobileUserOffersFlowShareSMS()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
            



			$outArrUsers2=array();
			$sendArray['datapoint_id']="17";//share by sms
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']=17;//share by sms
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['smsViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 function modShowMobileUserOffersFlowRedeem()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
    
			$outArrUsers2=array();
			$sendArray['datapoint_id']="15";//redeem
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']=15;//redeem
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['redeemViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 function modShowMobileUserOffersFlowRemove()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
    
			$outArrUsers2=array();
			$sendArray['datapoint_id']="14";//remove
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']=14;//remove
            	$outArrShareEmailOfferIds=array();
			    $outArrShareEmailOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['removedViews'] = isset($outArrShareEmailOfferIds[0]['offerViews']) ? $outArrShareEmailOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowMobileUserOffersFlowScanned()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['offer_id']=isset($_REQUEST['offer_id']) ? $_REQUEST['offer_id'] :0;
    
			$outArrUsers2=array();
			$sendArray['datapoint_id']="19,31";//scan + financial scan
			$outArrUsers2 = $this->objPublic->getClientOffersUsersByDpIdPdId($sendArray);
			
            $ourArrSortUsers=array_merge($outArrUsers2);
            $ourArrSortUsers = $this->arrayUnique($ourArrSortUsers);

            $arrData=array();
            $outArrGetUserInfo =array();

            //print_r($ourArrSortUsers);

            for($i=0;$i<count($ourArrSortUsers);$i++)
            {
            	$arrData[$i]['user_id']=isset($ourArrSortUsers[$i]['user_id']) ? $ourArrSortUsers[$i]['user_id'] : '';
            	$outArrGetUserInfo = $this->objPublic->getClientUserInfoByuserId($arrData[$i]['user_id']);
            	$arrData[$i]['user_firstname']=isset($outArrGetUserInfo[0]['user_firstname']) ? $outArrGetUserInfo[0]['user_firstname'] : '';
            	$arrData[$i]['user_lastname']=isset($outArrGetUserInfo[0]['user_lastname']) ? $outArrGetUserInfo[0]['user_lastname'] : '';
            	$arrData[$i]['user_username']=isset($outArrGetUserInfo[0]['user_username']) ? $outArrGetUserInfo[0]['user_username'] : '';
            	$arrData[$i]['user_email_id']=isset($outArrGetUserInfo[0]['user_email_id']) ? $outArrGetUserInfo[0]['user_email_id'] : '';
            	$arrData[$i]['user_status']=isset($outArrGetUserInfo[0]['user_status']) ? $outArrGetUserInfo[0]['user_status'] : '';
            	$arrData[$i]['user_details_nickname']=isset($outArrGetUserInfo[0]['user_details_nickname']) ? $outArrGetUserInfo[0]['user_details_nickname'] : '';
            	$arrData[$i]['user_details_phone']=isset($outArrGetUserInfo[0]['user_details_phone']) ? $outArrGetUserInfo[0]['user_details_phone'] : '';
            	$sendArray['user_id']=$arrData[$i]['user_id'];
			    
               
				$sendArray['datapoint_id']="19,31";//scan+financial scan
            	$outArrScannedOfferIds=array();
			    $outArrScannedOfferIds = $this->objPublic->getClientOffersByDpIdUserId($sendArray);
			    $arrData[$i]['scannedViews'] = isset($outArrScannedOfferIds[0]['offerViews']) ? $outArrScannedOfferIds[0]['offerViews'] : 0;

				
            }  

           // print_r($arrData);
            echo json_encode($arrData);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modShowAllDownloads()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			
			$outArrGetDownlods=array();
			$outArrGetDownlods = $this->objPublic->getAllDownloadsByDate($sendArray);
			
			//print_r($arrData);

            echo json_encode($outArrGetDownlods);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

   function modShowOnlineUsers()
	{
		try
		{
			global $config;
			
			$outArrGetOnlineUsers=array();
			$outArrGetOnlineUsers = $this->objPublic->getAllOnlineUsers();
	        //print_r($outArrGetOnlineUsers);
            echo json_encode($outArrGetOnlineUsers);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

function modShowMobileUserAllDownloads()
	{
		try
		{
			global $config;
			$sendArray=array();
			$sendArray['start_date']=isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',strtotime('1 month ago'));
			$sendArray['end_date']=isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] :date('Y-m-d');
			$sendArray['group_id']=isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] :0;
			//get all app downloads tracking data
			$outArrResults=array();
			$arrTotalDownloads=array();
			$arrTotalDownloads=$this->objPublic->getAllDownloads($sendArray);
	        $outArrResults[0]['total_downloads'] =isset($arrTotalDownloads[0]['totalDownloads']) ? $arrTotalDownloads[0]['totalDownloads'] : 0;
		//	print_r( $outArrResults);
            echo json_encode($outArrResults);
			
        }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function modGetAllClientVerticals()
	{
		try
		{
			global $config;
			$outArrClientVerticals = array();
			$outArrClientVerticals = $this->objClientsQuery->getAllClientVerticals();
			echo json_encode($outArrClientVerticals);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetAllCountries()
	{
		try
		{
			global $config;
			$outArrCountries = array();
			$outArrCountries = $this->objClientsQuery->getAllCountries();
			echo json_encode($outArrCountries);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modGetStatesByCountry($pUrl)
	{
		try
		{
			global $config;
			$countryCode=isset($_REQUEST['country_code']) ? $_REQUEST['country_code'] : $pUrl[3];
			$outArrStates = array();
			$outArrStates = $this->objClientsQuery->getAllStatesByCountry($countryCode);
			echo json_encode($outArrStates);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
}  /*** end of class ****/
?>