<?php 

class cProductWishlist{



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

			require_once(SRV_ROOT.'model/app.product.wishlist.model.class.php');

			$this->objProductWishlist = new mProductWishlist();		

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modGetWishListProducts($loggedInUserId,$wishListName,$fileType){

		try{

			$outResults = array();

			$wishListName = str_replace("%20", " ", $wishListName);

			$outResults['prodWishList'] = $this->objProductWishlist->getUserWishListProducts($loggedInUserId,$wishListName);

			//print_r($outResults);

			for($i=0;$i<count($outResults['prodWishList']);$i++)

			{

				$clientId = isset($outResults['prodWishList'][$i]["client_id"]) ? $outResults['prodWishList'][$i]["client_id"] : 0;

				$image = isset($outResults['prodWishList'][$i]["pd_image"]) ? $outResults['prodWishList'][$i]["pd_image"] : "";

				if($clientId!=0){

					$outResults['prodWishList'][$i]["pd_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;

				}

			}

			if($fileType=="json")

			{

			   echo json_encode($outResults);

			}

			else if($fileType=="xml")

			{

			   $xml = Array2XML::createXML('productsWishlist', $outResults);

			   echo $xml->saveXML();

			}

			else

			{

			    print_r($outResults);

			}

			

			//echo json_encode($outResults);

			/*$xml = Array2XML::createXML('productsWishlist', $outResults);

			echo $xml->saveXML();

			*/

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modGetYourClosetFromWishListProducts($loggedInUserId,$fileType){

		try{

			$outResults = array();

			$outResults['prodClosetFromWishList'] = $this->objProductWishlist->getYourClosetFromWishListProducts($loggedInUserId);

			//print_r($outResults);

			for($i=0;$i<count($outResults['prodClosetFromWishList']);$i++)

			{

				$clientId = isset($outResults['prodClosetFromWishList'][$i]["client_id"]) ? $outResults['prodClosetFromWishList'][$i]["client_id"] : 0;

				$image = isset($outResults['prodClosetFromWishList'][$i]["image"]) ? $outResults['prodClosetFromWishList'][$i]["image"] : "";

				$outResults['prodClosetFromWishList'][$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;

			}

			if($fileType=="json")

			{

			   echo json_encode($outResults);

			}

			else if($fileType=="xml")

			{

			   //$xml = Array2XML::createXML('triggerAll', $outResults);

			   //echo $xml->saveXML();

			}

			else

			{

			    print_r($outResults);

			}

			//echo json_encode($outResults);

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modAddWishListFromOffersProducts($loggedInUserId, $productId){

		try{

			$outResults = array();

			

			$wTableName = "wish_list";

			$wArray = array();

			$wArray['name'] = "My Offers";

			$wArray['user_id'] = $loggedInUserId;

			$wArray['shared'] = 0;

 			$checkedRecord = $this->objProductWishlist->checkingWishListName($wArray);



			$wTableName1 = "wish_list_item";

			if(count($checkedRecord) > 0){

				$uArray = array();

				$uArray['wish_list_id'] = isset($checkedRecord[0]['id']) ? $checkedRecord[0]['id'] : 0;

				$uArray['product_id'] = $productId;

				$insertRecord2 = $this->objProductWishlist->insertQuery($uArray, $wTableName1, false);

				if($insertRecord2)

				{

					$outResults['msg'] = 'success';

				} else {

					$outResults['msg'] = 'failed';

				}

			} else {

				$insertRecord = $this->objProductWishlist->insertQuery($wArray, $wTableName, true);

				if ($insertRecord && $productId!=""){				

					$wArray1 = array();

					$wArray1['wish_list_id'] = $insertRecord;

					$wArray1['product_id'] = $productId;

					$insertRecord1 = $this->objProductWishlist->insertQuery($wArray1, $wTableName1, false);

					if($insertRecord1)

					{

						$outResults['msg'] = 'success';

					} else {

						$outResults['msg'] = 'failed';

					}

				}

			}

			

			echo json_encode($outResults); 

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modAddWishListName($loggedInUserId, $wishListName,$fileType){

		try{

			$outResults = array();

			

			$wTableName = "wishlist";

			$wArray = array();

			$wArray['wishlist_name'] = str_replace("%20", " ", $wishListName);

			$wArray['user_id'] = $loggedInUserId;

			$wArray['wishlist_created_date'] = 'NOW()';

			$wArray['wishlist_created_by'] = $loggedInUserId;

			$wArray['wishlist_status'] = 1;

			

 			$checkedRecord = $this->objProductWishlist->checkingWishListName($wArray);

 

			if(count($checkedRecord) > 0){

				$outResults['msg'] = 'already';

			} else if($wishListName!=""){

				$insertRecord = $this->objProductWishlist->insertQuery($wArray, $wTableName, false);

				if ($insertRecord){

					$outResults['msg'] = 'success';

				} else {

					$outResults['msg'] = 'failed';

				}

			} else {

				$outResults['msg'] = 'failed';

			}

			if($fileType=="json")

			{

			   echo json_encode($outResults);

			}

			else if($fileType=="xml")

			{

			    $xml = Array2XML::createXML('addToWishlist', $outResults);

			    echo $xml->saveXML();

			}

			else

			{

			    print_r($outResults);

			}			

			//echo json_encode($outResults); 

			/*$xml = Array2XML::createXML('addToWishlist', $outResults);

			echo $xml->saveXML();

			*/

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modGetSelectedWishListNames($loggedInUserId,$fileType){

		try{

			$outResults = array();

			

 			$outResults['wishLists'] = $this->objProductWishlist->getUserRelatedWishListNames($loggedInUserId);

			

			if($fileType=="json")

			{

			   echo json_encode($outResults);

			}

			else if($fileType=="xml")

			{

			   $xml = Array2XML::createXML('userRelatedWishListNames', $outResults);

			   echo $xml->saveXML();

			}

			else

			{

			   print_r($outResults);

			}

			//echo json_encode($outResults);

		    /*$xml = Array2XML::createXML('userRelatedWishListNames', $outResults);

			echo $xml->saveXML();*/

		} catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modAddItemsToWishListInProducts($loggedInUserId, $productId, $wishListName){

		try{

			$outResults = array();

			$wArray = array();

			$wArray['name'] = $wishListName;

			$wArray['user_id'] = $loggedInUserId;

			$wArray['shared'] = 0;

 			$checkedRecord = $this->objProductWishlist->checkingWishListName($wArray);

			if(count($checkedRecord) > 0){				

				$wTableName1 = "wish_list_item";

				$uArray = array();

				$uArray['wish_list_id'] = isset($checkedRecord[0]['id']) ? $checkedRecord[0]['id'] : 0;

				$uArray['product_id'] = $productId;

				$insertRecord2 = $this->objProductWishlist->insertQuery($uArray, $wTableName1, false);

				if($insertRecord2)

				{

					$outResults['msg'] = 'success';

				} else {

					$outResults['msg'] = 'failed';

				}

			} else {

					$outResults['msg'] = 'failed';

			}

			echo json_encode($outResults);

		} catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modDelSelectedWishList($loggedInUserId,$wishListName,$fileType){

		try{

			$outResults = array();

			$updateRecord ="";

			

			$checkedRecord = $this->objProductWishlist->getUserWishListProducts($loggedInUserId, $wishListName);

			//print_r($checkedRecord);

			if(count($checkedRecord)>0){

				$j=0;		

				for($i=0;$i<count($checkedRecord);$i++){

					$con = array();

					$wdArray = array();	

					$con['wishlist_id'] = isset($checkedRecord[$i]['wishlist_id']) ? $checkedRecord[$i]['wishlist_id'] : 0;	

					$con['wishlist_details_id'] = isset($checkedRecord[$i]['wishlist_details_id']) ? $checkedRecord[$i]['wishlist_details_id'] : 0;

					if($con['wishlist_details_id'] !=0){

						$wdArray['wishlist_status'] = 2;

						$updateRecord = $this->objProductWishlist->updateRecordQuery($wdArray, "wishlist_details", $con);

					}

					$j++;

				}

				if($j==count($checkedRecord)){

					$wlistTablName ="wishlist";

					if($wishListName !=""){	

						$wArray = array();	

						$wArray['wishlist_status'] = 2;				

						$dArray = array();

						$dArray['wishlist_id'] = isset($checkedRecord[0]['wishlist_id']) ? $checkedRecord[0]['wishlist_id'] : 0;	

						$dArray['user_id'] = $loggedInUserId;

						$updateRecord = $this->objProductWishlist->updateRecordQuery($wArray, "wishlist", $dArray);

					}

				}

				if($updateRecord)

				{

					$outResults['msg'] = 'success';

				} else {

					$outResults['msg'] = 'failed';

				}

			} else {

				$outResults['msg'] = 'failed';

			}

			if($fileType=="json")

			{

			   echo json_encode($outResults);

			}

			else if($fileType=="xml")

			{

			   $xml = Array2XML::createXML('deleteWishlistName', $outResults);

			   echo $xml->saveXML();

			}

			else

			{

			    print_r($outResults);

			}

			//echo json_encode($outResults);

			/*$xml = Array2XML::createXML('deleteWishlistName', $outResults);

			echo $xml->saveXML();*/

		} catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	public function modShareWishList($loggedInUserId,$wishListName){

		try{

			$outResults = array();

			$pArray = array();

			$con = array();

			$updateRecord ="";

			

			$checkedRecord = $this->objProductWishlist->getUserWishListProducts($loggedInUserId,$wishListName);

			if(count($checkedRecord)>0){

				$shared = isset($checkedRecord[0]['shared']) ? $checkedRecord[0]['shared'] : 0;	

				if($shared == 0){

					$pArray['shared'] = 1;

				}else{

					$pArray['shared'] = 0;

				}

			$con['user_id']	= 	$loggedInUserId;

			$con['name']	= 	$wishListName;

 			$updateRecord = $this->objProductWishlist->updateRecordQuery($pArray,"wish_list",$con);

			}

			if($updateRecord)

				{

					

					$outResults['msg'] = 'success';

					$outResults['share'] = $pArray['shared'];

				} else {

					$outResults['msg'] = 'failed';

				}

			echo json_encode($outResults);

		} catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modGetFriendsWishListProducts($loggedInUserId,$wishListId){

		try{

			

			$outResults = array();			

			$outResults['prodWishList'] = $this->objProductWishlist->getFriendsWishListProducts($loggedInUserId,$wishListId);

			//print_r($outResults);

			for($i=0;$i<count($outResults['prodWishList']);$i++)

			{

				$clientId = isset($outResults['prodWishList'][$i]["client_id"]) ? $outResults['prodWishList'][$i]["client_id"] : 0;

				$image = isset($outResults['prodWishList'][$i]["image"]) ? $outResults['prodWishList'][$i]["image"] : "";

				$outResults['prodWishList'][$i]["image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$clientId."/products/".$image;

			}

			echo json_encode($outResults);

		}

		catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	public function modGetFriendsWishListNames($loggedInUserId){

		try{

			$outResults = array();

			

 			$outResults['friendsRelatedWishListNames'] = $this->objProductWishlist->getFriendsRelatedWishListNames($loggedInUserId);

			echo json_encode($outResults);

		} catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function modDelSelectedYourCloset($loggedInUserId, $closetImgProdId){

		try{

			$outResults = array();

			$checkedRecord = $this->objProductWishlist->getYourClosetUserWishListProducts($loggedInUserId, $closetImgProdId);

			$uArray = array();

			$uArray['wish_list_id'] = isset($checkedRecord[0]['id']) ? $checkedRecord[0]['id'] : 0;	

			$uArray['id'] = isset($checkedRecord[$i]['item_id']) ? $checkedRecord[$i]['item_id'] : 0;

			if($uArray['id'] !=0){

				$delRecord = $this->objProductWishlist->delete($wTableName1, $uArray);

			}

			

 			//$outResults['friendsRelatedWishListNames'] = $this->objProductWishlist->getFriendsRelatedWishListNames($loggedInUserId);

			echo json_encode($outResults);

		} catch ( Exception $e ) {

			echo 'Message: ' .$e->getMessage();

		}

	}

	

	public function __destruct(){

		/*** Destroy and unset the object ***/
		unset($objLoginQuery);
		unset($_pageSlug);
		unset($objConfig);
		unset($getConfig);
		unset($objCommon);
		unset($this->objProductWishlist);

	}

	

} /*** end of class ***/

?>