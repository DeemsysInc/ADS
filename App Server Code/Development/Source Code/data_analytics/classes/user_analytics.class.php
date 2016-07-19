<?php 
class cUserAnalytics{

	/*** define public & private properties ***/
	private $objLoginQuery;
	private $_pageSlug;		
	public $objConfig;
	public $getConfig;
	public $objCommon;
	
	/*** the constructor ***/
	public function __construct(){
		try{
			
			ini_set("display_errors", 1);
			error_reporting(E_ALL);
			/**** Create Model Class and Object ****/
			require_once(SRV_ROOT.'model/user_analytics.model.class.php');
			$this->objUserAnalyticsQuery = new mUserAnalytics();
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modTracking($jsonArray){
		try{
			global $config;	    
			//$arrExplScrNames = array(); 
			$outArrayResults = array();    
			$screenPath = '';
			//print_r($jsonArray);
			$screenPath = isset($jsonArray['screen_path']) ? $jsonArray['screen_path'] : '';
			if($screenPath!=""){
				$arrExplScrNames = explode('/', $screenPath);   
			}
			//echo count($screenPath)."<br>"; 
			//echo count($arrExplScrNames);
            if(isset($arrExplScrNames) && count($arrExplScrNames)>0)
            {
            	
            	if(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='web'))
            	{

            		$url=explode('?', $screenPath);
            		$webUrl=isset($url[1]) ? $url[1]:'';
            		if(!empty($webUrl))
            		{
            			$webUrl=str_replace('url=', '', $webUrl);
            		}
    				$jsonArray['weburl']=$webUrl;
        		    $jsonArray['datapoint_id']=33;//
        		    $jsonArray['tracking_path']=$screenPath;
            	    $outArrayResults=$this->modWebViewTracking($jsonArray);


            	}
            	elseif(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='downloads'))
            	{
            		//app downloads
            		$jsonArray['datapoint_id']=34;//
        		    $jsonArray['tracking_path']=$screenPath;
            	    $outArrayResults=$this->modDownloadTracking($jsonArray);

            	
            	}
				elseif(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='games'))
            	{
					if(isset($arrExplScrNames[2]) && ($arrExplScrNames[2]=='used'))
            	   	{
						 //games used
						 $jsonArray['game_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
						 $jsonArray['client_games_item_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
						 $jsonArray['client_id']=isset($arrExplScrNames[5]) ? $arrExplScrNames[5] : 0;
						 $jsonArray['offer_id']=isset($arrExplScrNames[7]) ? $arrExplScrNames[7] : 0;
						 $jsonArray['datapoint_id']=47;
						 $jsonArray['games_tracking_path']=$screenPath;
						 $outArrayResults=$this->modGamesTracking($jsonArray);
					}
					else
					{
						 //games hompage
						 $jsonArray['game_id']=isset($arrExplScrNames[2]) ? $arrExplScrNames[2] : 0;
						 $jsonArray['client_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
						 $jsonArray['datapoint_id']=46;
						 $jsonArray['games_tracking_path']=$screenPath;
						 $outArrayResults=$this->modGamesTracking($jsonArray);
					}
				}
				elseif(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='shoppingcart'))
            	{
				    if(isset($arrExplScrNames[2]) && ($arrExplScrNames[2]=='product'))
            	   	{
					    if(isset($arrExplScrNames[3]) && ($arrExplScrNames[3]=='details'))
            	   	    {
						    //shopping cart product details page
						    if(isset($arrExplScrNames[4]) && $arrExplScrNames[4]=='cart')
							{
								//shopping cart Product details cart
								 $jsonArray['product_id']=isset($arrExplScrNames[7]) ? $arrExplScrNames[7] : 0;
							     $jsonArray['datapoint_id']=40;
							     $jsonArray['shoppingcart_tracking_path']=$screenPath;
							     $outArrayResults=$this->modShoppingCartTracking($jsonArray);

							}
							else
							{
								//shopping cart product details tracking
								 $jsonArray['product_id']=isset($arrExplScrNames[6]) ? $arrExplScrNames[6] : 0;
								 $jsonArray['datapoint_id']=39;
								 $jsonArray['shoppingcart_tracking_path']=$screenPath;
								 $outArrayResults=$this->modShoppingCartTracking($jsonArray);	
								
							}
						
						}
						
					
					}
					else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='checkout')
					{
					    if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='payment')
						{
							
							if(isset($arrExplScrNames[4]) && $arrExplScrNames[4]=='final')
							{
								//Shopping cart after paypal final process
								 $jsonArray['order_id']=isset($arrExplScrNames[5]) ? $arrExplScrNames[5] : 0;
								 $jsonArray['datapoint_id']=44;
								 $jsonArray['shoppingcart_tracking_path']=$screenPath;
								 $outArrayResults=$this->modShoppingCartTracking($jsonArray);

							}
							else
							{
								//shopping cart checkout payment selection page
								 $jsonArray['order_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
								 $jsonArray['datapoint_id']=43;
								 $jsonArray['shoppingcart_tracking_path']=$screenPath;
								 $outArrayResults=$this->modShoppingCartTracking($jsonArray);
							 }

						}
						else
						{
						     if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]!='')
							{
								//shopping cart checkout order page
								 $jsonArray['order_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
								 $jsonArray['datapoint_id']=42;
								 $jsonArray['shoppingcart_tracking_path']=$screenPath;
								 $outArrayResults=$this->modShoppingCartTracking($jsonArray);

							}
							else 
							{
							//shopping cart checkout page
							 $jsonArray['datapoint_id']=41;
							 $jsonArray['shoppingcart_tracking_path']=$screenPath;
							 $outArrayResults=$this->modShoppingCartTracking($jsonArray);
							
							}
							

						}
					}	
						
					else{
					    //shopping cart home page 
						$jsonArray['prod_ids']=isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : 0;
				    	
            			 $jsonArray['datapoint_id']=38;
            		     $jsonArray['shoppingcart_tracking_path']=$screenPath;
            		     $outArrayResults=$this->modShoppingCartTracking($jsonArray);
						 
					
					}
				
				}
            	elseif(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='video'))
            	{
            		if(isset($arrExplScrNames[2]) && ($arrExplScrNames[2]=='product'))
            		{
            			//scanning product
	    				 $jsonArray['product_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	        		     $jsonArray['datapoint_id']=30;//product scanning
	        		     $jsonArray['pd_tracking_path']=$screenPath;
	            	     $outArrayResults=$this->modProductTracking($jsonArray);

            		}
            		elseif(isset($arrExplScrNames[2]) && ($arrExplScrNames[2]=='offers'))
            		{
            			//scanning offer
            			 $jsonArray['offer_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	        		     $jsonArray['datapoint_id']=31;//offer scanning
	        		     $jsonArray['myoffers_tracking_path']=$screenPath;
	            	     $outArrayResults=$this->modOfferTracking($jsonArray);	

            		}
            		else
            		{
            			//video 
            			 $jsonArray['client_id']=isset($arrExplScrNames[2]) ? $arrExplScrNames[2] : 0;
	        		     $jsonArray['datapoint_id']=32;//
	        		     $jsonArray['tracking_path']=$screenPath;
	            	     $outArrayResults=$this->modVideoTracking($jsonArray);

            		}
            	}
            	// else if(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='product') || (isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='product'))
            	// {
            	else if(isset($arrExplScrNames[1]) && ($arrExplScrNames[1]=='product'))
            	{	
            		//product related functions /product/<clientId>/<productId>
            		if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='details')
            		{
            			if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='cart')
            			{
            				//Product details cart
            				 // $jsonArray['product_id']=isset($arrExplScrNames[5]) ? $arrExplScrNames[5] : 0;
	            		  //    $jsonArray['datapoint_id']=5;
	            		  //    $jsonArray['pd_tracking_path']=$screenPath;
	            		  //    $outArrayResults=$this->modProductTracking($jsonArray);

            			}
            			else
            			{
							//details tracking
            				 $jsonArray['product_id']=isset($arrExplScrNames[5]) ? $arrExplScrNames[5] : 0;
	            		     $jsonArray['datapoint_id']=3;
	            		     $jsonArray['pd_tracking_path']=$screenPath;
	            		     $outArrayResults=$this->modProductTracking($jsonArray);	
	            			
            			}

            		}

            		else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='shareproduct')
            		{
            			if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='email')
				        {
				        	//email
				        	
							$jsonArray['product_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
		            		$jsonArray['datapoint_id']=6;
		            		$jsonArray['pd_tracking_path']=$screenPath;
		            		$outArrayResults=$this->modProductTracking($jsonArray);
				        }
				        elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='facebook')
				        {
				        	//facebook
				        	$jsonArray['product_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
		            		$jsonArray['datapoint_id']=7;
		            		$jsonArray['pd_tracking_path']=$screenPath;
		            		$outArrayResults=$this->modProductTracking($jsonArray);

							
				        }
				        elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='twitter')
				        {
				        	//email
				        	
				        	$jsonArray['product_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
		            		$jsonArray['datapoint_id']=8;
		            		$jsonArray['pd_tracking_path']=$screenPath;
		            		$outArrayResults=$this->modProductTracking($jsonArray);
							
				        }
				        elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='sms')
				        {
				        	//email
				        	
				        	$jsonArray['product_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
		            		$jsonArray['datapoint_id']=18;
		            		$jsonArray['pd_tracking_path']=$screenPath;
		            		$outArrayResults=$this->modProductTracking($jsonArray);
							
				        }
				        else
				        {
				        	
				        	//share product home
		        			$jsonArray['product_id']=isset($arrExplScrNames[5]) ? $arrExplScrNames[5] : 0;
		            		$jsonArray['datapoint_id']=4;
		            		$jsonArray['pd_tracking_path']=$screenPath;
		            		$outArrayResults=$this->modProductTracking($jsonArray);
							
				        }
                    }
                    else
                    {
            			//Scan product
            			$jsonArray['product_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
            			$jsonArray['datapoint_id']=2;
            			$jsonArray['pd_tracking_path']=$screenPath;
            			$outArrayResults=$this->modProductTracking($jsonArray);	
            		}



            	}
            	else if(isset($arrExplScrNames[1]) && $arrExplScrNames[1]=='wishlist')
				{
					if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='product')
				    {
				    	if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='details')
				        {
				        	//details page
				        	$jsonArray['product_id']=isset($arrExplScrNames[6]) ? $arrExplScrNames[6] : 0;
	            		    $jsonArray['datapoint_id']=36;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);

				        }

				    }
					else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='sharewishlist')
				    {

				    	if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='email')
				    	{
				    		//sharewishlist by email
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	            		    $jsonArray['datapoint_id']=25;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);
	            		}
	            		elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='facebook')
				    	{
				    		//sharewishlist by fb
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	            		    $jsonArray['datapoint_id']=26;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);
	            		}
	            		elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='twitter')
				    	{
				    		//sharewishlist by twitter
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	            		    $jsonArray['datapoint_id']=27;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);
	            		}
	            		elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='sms')
				    	{
				    		//sharewishlist by sms
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	            		    $jsonArray['datapoint_id']=18;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);
	            		}
	            		else
				    	{
				    		//sharewishlist
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
	            		    $jsonArray['datapoint_id']=24;
	            		     $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);
	            		}    
				    	
				    	
				    }
				}
            	else if(isset($arrExplScrNames[1]) && $arrExplScrNames[1]=='mycloset')
				{
					if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='wishlists')
				    {
				    	if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='update')
				    	{
				    		//mycloset wishlist update or add
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	            		    $jsonArray['datapoint_id']=22;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);


				    	}
				    	elseif(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='removed')
				    	{
				    		//mycloset wishlist removed
				    		$jsonArray['wishlist_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
	            		    $jsonArray['datapoint_id']=23;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);


				    	}
				    	else
				    	{
	                        //mycloset wishlists

					    	$jsonArray['prod_ids']=isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : 0;
					    	if(!empty($jsonArray['prod_ids']))
					    	{
					    	  $jsonArray['prod_ids'] = "|".$jsonArray['prod_ids']."|";
	            		    }
	            		    $jsonArray['datapoint_id']=21;
	            		    $jsonArray['wishlist_tracking_path']=$screenPath;
	            		    $outArrayResults=$this->modWishlistTracking($jsonArray);

				    	}
				    	

				    }
				    elseif(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='recommendation')
				    {
				    	//recommendation
				    	//$jsonArray['prod_ids']=isset($jsonArray['product_ids']) ? $jsonArray['product_ids'] : 0;
            		    $jsonArray['datapoint_id']=28;
            		    $jsonArray['closet_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modClosetTracking($jsonArray);


				    }
				    elseif(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='removed')
				    {
				    	//mycloset removed
				    	$jsonArray['product_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
	            		$jsonArray['datapoint_id']=29;
	            		$jsonArray['closet_tracking_path']=$screenPath;
	            		$outArrayResults=$this->modClosetTracking($jsonArray);


				    }
				    else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='bybrands')
				    {
				    	if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='product')
					    {
					    	if(isset($arrExplScrNames[4]) && $arrExplScrNames[4]=='details')
					        {
					        	//mycloset bybrand product details
						    	$jsonArray['product_id']=isset($arrExplScrNames[7]) ? $arrExplScrNames[7] : 0;
			            		$jsonArray['datapoint_id']=37;
			            		$jsonArray['closet_tracking_path']=$screenPath;
			            		$outArrayResults=$this->modClosetTracking($jsonArray);

					        }
					    	
					    }

				    }
				    elseif(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='product')
				    {
				    	if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='details')
				        {
				        	//mycloset product details
					    	$jsonArray['product_id']=isset($arrExplScrNames[6]) ? $arrExplScrNames[6] : 0;
		            		$jsonArray['datapoint_id']=35;
		            		$jsonArray['closet_tracking_path']=$screenPath;
		            		$outArrayResults=$this->modClosetTracking($jsonArray);

				        }
				    	
				    }
				    else
				    {
				    	//mycloset data
				    	$jsonArray['prod_ids']=isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : 0;
				    	if(!empty($jsonArray['prod_ids']))
				    	{
				    	  $jsonArray['prod_ids'] = "|".$jsonArray['prod_ids']."|";
            		    }
				    	$jsonArray['datapoint_id']=20;
            		    $jsonArray['closet_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modClosetTracking($jsonArray);
				    }
				}
            	else if(isset($arrExplScrNames[1]) && $arrExplScrNames[1]=='myoffers')
				{
				     if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='details')
				    {
				    	//details
						$jsonArray['offer_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
            		    $jsonArray['datapoint_id']=45;
            		    $jsonArray['myoffers_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modOfferTracking($jsonArray);	
					}
					else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='redeem')
				    {
				    	//redeem
				    	$jsonArray['offer_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
            		    $jsonArray['datapoint_id']=15;
            		    $jsonArray['myoffers_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modOfferTracking($jsonArray);	
					}
				    else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='apply')
				    {
				    	//apply
				    	
				    	$jsonArray['offer_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
            		    $jsonArray['datapoint_id']=16;
            		    $jsonArray['myoffers_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modOfferTracking($jsonArray);	
                    }
                    else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='removed')
				    {
				    	//offer details or redeem page
				    	$jsonArray['offer_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
            		    $jsonArray['datapoint_id']=14;
            		    $jsonArray['myoffers_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modOfferTracking($jsonArray);
				    }
				    else if(isset($arrExplScrNames[2]) && $arrExplScrNames[2]=='shareoffer')
				    {
				    	if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='email')
				        {
				        	//email
				        	$jsonArray['offer_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
            		        $jsonArray['datapoint_id']=11;
            		        $jsonArray['myoffers_tracking_path']=$screenPath;
            		        $outArrayResults=$this->modOfferTracking($jsonArray);
				        	
						}
						else if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='facebook')
				        {
				        	//facebook
				        	$jsonArray['offer_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
            		        $jsonArray['datapoint_id']=12;
            		        $jsonArray['myoffers_tracking_path']=$screenPath;
            		        $outArrayResults=$this->modOfferTracking($jsonArray);

						}
						else if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='twitter')
				        {
				        	//twitter
				        	$jsonArray['offer_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
            		        $jsonArray['datapoint_id']=13;
            		        $jsonArray['myoffers_tracking_path']=$screenPath;
            		        $outArrayResults=$this->modOfferTracking($jsonArray);
				        	

						}
						else if(isset($arrExplScrNames[3]) && $arrExplScrNames[3]=='sms')
				        {
				        	//twitter
				        	$jsonArray['offer_id']=isset($arrExplScrNames[4]) ? $arrExplScrNames[4] : 0;
            		        $jsonArray['datapoint_id']=17;
            		        $jsonArray['myoffers_tracking_path']=$screenPath;
            		        $outArrayResults=$this->modOfferTracking($jsonArray);
				        	

						}
				        else
				        {
				        	//shareoffers
				            $jsonArray['offer_id']=isset($arrExplScrNames[3]) ? $arrExplScrNames[3] : 0;
            		        $jsonArray['datapoint_id']=10;
            		        $jsonArray['myoffers_tracking_path']=$screenPath;
            		        $outArrayResults=$this->modOfferTracking($jsonArray);

						}

				    }
				    else
				    {
				    	//myoffers data
				    	$jsonArray['my_offer_ids']=isset($jsonArray['offer_ids']) ? $jsonArray['offer_ids'] : 0;
				    	if(!empty($jsonArray['my_offer_ids']))
				    	{
				    	  $jsonArray['my_offer_ids'] = "|".$jsonArray['my_offer_ids']."|";
            		    }
            		    $jsonArray['datapoint_id']=9;
            		    $jsonArray['myoffers_tracking_path']=$screenPath;
            		    $outArrayResults=$this->modOfferTracking($jsonArray);
				    }


        		}
        		else
	            {
	            	//home page 
	            	//echo "home screen";
	            	$jsonArray['datapoint_id']=1;
	            	$jsonArray['home_tracking_path']=$screenPath;
	            	$outArrayResults=$this->modHomeTracking($jsonArray);
	            }

            }
			else
			{
				//home page 
				//echo "home screen";
				$jsonArray['datapoint_id']=1;
				$jsonArray['home_tracking_path']=$screenPath;
				$outArrayResults = $this->modHomeTracking($jsonArray);
			}
            
		return $outArrayResults;

             
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modDownloadTracking($jsonArray){
		try{
			global $config;
			$tableName="downloads_analytics";
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            
            $pArray =array();	
            $pArray['session_id'] = $session_id;
            $pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['created_date'] = 'NOW()';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : 0;
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['tracking_path'] = isset($jsonArray['tracking_path']) ? $jsonArray['tracking_path'] : '';


			$checkSessionIdTemperary=array();
			$checkSessionIdTemperary=$this->objUserAnalyticsQuery->checkSessionId($session_id);
			$insertData=array();
			if(count($checkSessionIdTemperary)==0)
			{
				$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);
				$_SESSION['duplicate_entry']=$session_id;
				$msg=array();
				if(count($insertData)>0)
				{
					$msg['status']="success";
				}
				else
				{
					$msg['status']="fail";
				}
				echo json_encode($msg);
			}

		
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modVideoTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="video_analytics";
            

			$pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['created_date'] = 'NOW()';
			$pArray['client_id'] = isset($jsonArray['client_id']) ? $jsonArray['client_id'] : 0;
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : 0;
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['tracking_path'] = isset($jsonArray['tracking_path']) ? $jsonArray['tracking_path'] : '';


			$insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);	
		
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modHomeTracking($jsonArray){
		try{
			global $config;
			//print_r($jsonArray);
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="home_analytics";
            $pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['home_created_date'] = 'NOW()';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : '';
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['home_tracking_path'] = isset($jsonArray['home_tracking_path']) ? $jsonArray['home_tracking_path'] : '';

            //print_r($pArray);
		
			$insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modWebViewTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="webview_analytics";
            

			$pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['created_date'] = 'NOW()';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : 0;
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['tracking_path'] = isset($jsonArray['tracking_path']) ? $jsonArray['tracking_path'] : '';
			$pArray['weburl'] = isset($jsonArray['weburl']) ? $jsonArray['weburl'] : '';
			

		    $insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);	
			
		
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modProductTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="products_analytics";
            $productId=isset($jsonArray['product_id']) ? $jsonArray['product_id'] : '0';
            $outArrayProductInfo=array();
			$outArrayProductInfo=$this->objUserAnalyticsQuery->getProductInfo($productId);
		

			$pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['pd_created_date'] = 'NOW()';
			$pArray['client_id'] = isset($outArrayProductInfo[0]['client_id']) ? $outArrayProductInfo[0]['client_id'] : '';
			$pArray['pd_id'] =$productId;
			$pArray['pd_datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : 0;
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['pd_tracking_path'] = isset($jsonArray['pd_tracking_path']) ? $jsonArray['pd_tracking_path'] : '';


		    $insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modClosetTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="closet_analytics";
            

			$pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['closet_created_date'] = 'NOW()';
			$pArray['prod_ids'] = isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : '';
			$pArray['product_id'] = isset($jsonArray['product_id']) ? $jsonArray['product_id'] : 0;
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : 0;
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['closet_tracking_path'] = isset($jsonArray['closet_tracking_path']) ? $jsonArray['closet_tracking_path'] : '';
			$insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);
			
			//print_r($pArray);
			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modWishlistTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="wishlist_analytics";
            

			$pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['wishlist_created_date'] = 'NOW()';
			$pArray['product_id'] = isset($jsonArray['product_id']) ? $jsonArray['product_id'] : '';
			$pArray['wishlist_id'] = isset($jsonArray['wishlist_id']) ? $jsonArray['wishlist_id'] : '';
			$pArray['prod_ids'] = isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : '';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : '';
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['wishlist_tracking_path'] = isset($jsonArray['wishlist_tracking_path']) ? $jsonArray['wishlist_tracking_path'] : '';



		
			$insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);		
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modOfferTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="my_offers_analytics";
            $offerId=isset($jsonArray['offer_id']) ? $jsonArray['offer_id'] : '0';
            $outArrayOfferInfo=array();
			$outArrayOfferInfo=$this->objUserAnalyticsQuery->getOfferInfo($offerId);
		    $pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['myoffers_created_date'] = 'NOW()';
			$pArray['client_id'] = isset($outArrayOfferInfo[0]['client_id']) ? $outArrayOfferInfo[0]['client_id'] : '';
			$pArray['offer_id'] = isset($jsonArray['offer_id']) ? $jsonArray['offer_id'] : '';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : '';
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['myoffers_ids'] = isset($jsonArray['my_offer_ids']) ? $jsonArray['my_offer_ids'] : '';
			$pArray['myoffers_tracking_path'] = isset($jsonArray['myoffers_tracking_path']) ? $jsonArray['myoffers_tracking_path'] : '';

		
			$insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modShoppingCartTracking($jsonArray){
		try{
			global $config;
			$userId=isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$session_id=isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
            $tableName="shoppingcart_analytics";
            
			$productId=isset($jsonArray['product_id']) ? $jsonArray['product_id'] : '0';
			$orderId=isset($jsonArray['order_id']) ? $jsonArray['order_id'] : '0';
            
			//get product info by pid
            $outArrayProductInfo=array();
			$outArrayProductInfo=$this->objUserAnalyticsQuery->getProductInfo($productId);

			$pArray =array();	
			$pArray['user_id'] = $userId;
			$pArray['session_id'] = $session_id;
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['created_date'] = 'NOW()';
			$pArray['client_id'] = isset($outArrayProductInfo[0]['client_id']) ? $outArrayProductInfo[0]['client_id'] : '';
			$pArray['pd_id'] =$productId;
			$pArray['order_id'] =$orderId;
			$pArray['prod_ids'] = isset($jsonArray['prod_ids']) ? $jsonArray['prod_ids'] : '';
			$pArray['additional_info'] = isset($jsonArray['additional']) ? $jsonArray['additional'] : '';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : 0;
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['shoppingcart_tracking_path'] = isset($jsonArray['shoppingcart_tracking_path']) ? $jsonArray['shoppingcart_tracking_path'] : '';


		    $insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function modGamesTracking($jsonArray){
		try{
			global $config;
			
			$tableName="games_analytics";
            
		    $pArray =array();	
			$pArray['user_id'] = isset($jsonArray['user_id']) ? $jsonArray['user_id'] : '0';
			$pArray['client_id'] = isset($jsonArray['client_id']) ? $jsonArray['client_id'] : '';
			$pArray['offer_id'] = isset($jsonArray['offer_id']) ? $jsonArray['offer_id'] : '';
			$pArray['game_id'] = isset($jsonArray['game_id']) ? $jsonArray['game_id'] : '';
			$pArray['client_games_item_id'] = isset($jsonArray['client_games_item_id']) ? $jsonArray['client_games_item_id'] : '';
			$pArray['session_id'] =isset($jsonArray['session_id']) ? $jsonArray['session_id'] : '';
			$pArray['device_type'] = isset($jsonArray['device_type']) ? $jsonArray['device_type'] : '';
			$pArray['device_os'] = isset($jsonArray['device_os']) ? $jsonArray['device_os'] : '';
			$pArray['lat_long'] = isset($jsonArray['lat_long']) ? $jsonArray['lat_long'] : '';
			$pArray['device_os_version'] = isset($jsonArray['device_os_version']) ? $jsonArray['device_os_version'] : '';
			$pArray['device_brand'] = isset($jsonArray['device_brand']) ? $jsonArray['device_brand'] : '';
			$pArray['created_date'] = 'NOW()';
			$pArray['datapoint_id'] = isset($jsonArray['datapoint_id']) ? $jsonArray['datapoint_id'] : '';
			$pArray['build_number'] = isset($jsonArray['device_bundle_version']) ? $jsonArray['device_bundle_version'] : '';
			$pArray['games_tracking_path'] = isset($jsonArray['games_tracking_path']) ? $jsonArray['games_tracking_path'] : '';

		
			$insertData=array();
			$insertData=$this->objUserAnalyticsQuery->insertQuery($pArray, $tableName, false);

			$msg=array();
			if(count($insertData)>0)
			{
				$msg['status']="success";
			}
			else
			{
				$msg['status']="fail";
			}
			echo json_encode($msg);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}

	
	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
} /*** end of class ***/
?>