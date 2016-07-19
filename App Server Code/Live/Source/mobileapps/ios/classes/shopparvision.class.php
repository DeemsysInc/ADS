<?php 
class cShopparVision{

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
			require_once(SRV_ROOT.'model/shopparvision.model.class.php');
			$this->objShoppar = new mShopparVision();
			
			require_once SRV_ROOT.'classes/public.class.php';
			$this->objPublic = new cPublic();

			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	// public function modClientDB($pClientID){
	// 	try{
			
	// 		global $config;
	// 		$arrClientDB = array();
	// 	    $this->objStore->connectToDB("devarapp_masters");
	// 		$arrClientDB = $this->objShoppar->getAllClientDBs();
	// 		$dbName = isset($arrClientDB[0]['client_db_name']) ? $arrClientDB[0]['client_db_name'] :'';
	// 	    return $dbName;
	// 	}
	// 	catch ( Exception $e ) {
	// 		echo 'Message: ' .$e->getMessage();
	// 	}
	// }
	public function modMatching($pShopparInfo){
		try{
			global $config;	

			// $this->getMatchingColor();
			$arrShopparInfo = array();
			$arrClientDbs = array();
			$arrSearchedProds = array();
			$arrAttribsResults = array();
			$outSearchedProducts = array();
			$catColorCode = '';

			$arrShopparInfo = $pShopparInfo;
			
			// $arrShopparInfo = $this->getDummyMatchingSet();
			// print_r($arrShopparInfo);

			$colorSearch = isset($arrShopparInfo['colorCode']) ? $arrShopparInfo['colorCode'] : '';
			$prodType = isset($arrShopparInfo['prodType']) ? $arrShopparInfo['prodType'] : '';

			$catColorCode = $this->getMatchingColor($colorSearch);

			$this->objShoppar->connectToDB('devarapp_cms');
			$arrClientDbs = $this->objShoppar->getAllClientDBs();
			// print_r($arrClientDbs);
			for ($i=0; $i < count($arrClientDbs); $i++) { 
				// $dbName = $this->modClientDB($clientId);
				$dbName = isset($arrClientDbs[$i]['client_db_name']) ? $arrClientDbs[$i]['client_db_name'] : '';
				if ($dbName !='') {
					$this->objShoppar->connectToDB($dbName);
					$arrAttribsResults = $this->objShoppar->getProdIDsByColor($catColorCode, $prodType);
					// print_r($arrAttribsResults);
					for ($j=0; $j < count($arrAttribsResults); $j++) { 
						$prodID = isset($arrAttribsResults[$j]['prod_id']) ? $arrAttribsResults[$j]['prod_id'] : 0;
						if ($prodID !=0) {
							$arrSearchedProds[]=$prodID;
						}
					}
				}
	        	
			}
			// print_r($arrSearchedProds);

			$this->objShoppar->connectToDB('devarapp_cms');
			$c=0;
			for ($i=0; $i < count($arrSearchedProds); $i++) { 
				$arrProdDetails = array();
				$pProdId = isset($arrSearchedProds[$i]) ? $arrSearchedProds[$i] : 0;
				if ($pProdId !=0) {
					$arrProdDetails = $this->objPublic->getProductDetails($pProdId);
	        		$outSearchedProducts[$c] = $arrProdDetails;
	        		$c++;
				}
        		
			}
			
        	echo json_encode($outSearchedProducts);
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getMatchingColor($pSourceColor){
		try{
			global $config;	
			$color_categories_main = array();
			$color_sub_categories = array();

			$colorVal = $pSourceColor;
			$color = $this->hex2rgb($colorVal);//return an rgb array
			
			$color_sub_categories = $this->objShoppar->getColorSubCategories();
			// print_r($color_sub_categories);

			$color_categories_main = $this->objShoppar->getColorCategories();
			// print_r($color_categories_main);


			//creating rgb value as an array
		$rgb_value_sub_categories = explode(",",$color_sub_categories[0]['color_sub_cat_rgb']);

		//assiging first sub category rgb value as basic
		$closest = $rgb_value_sub_categories;

		//getting the distance between two colors
		$mindist=$this->dist($color,$rgb_value_sub_categories);

		$ncolors=sizeof($color_sub_categories);
		for($i = 0; $i < $ncolors; ++$i)
		{
			$rgb_sub_categories = explode(",",$color_sub_categories[$i]['color_sub_cat_rgb']);
		    $currdist = $this->dist($color,$rgb_sub_categories);
		    if($currdist<$mindist) {
		      $mindist=$currdist;
		      $closest=$color_sub_categories[$i];
		    }
		}
		for($i = 0; $i < count($color_categories_main); ++$i)
		{	
		    if($color_categories_main[$i]['color_cat_id'] == $closest['color_cat_id']) {	
		     $mclosest = $color_categories_main[$i];
		    }
		}
		//displaying results
		$sub_rgb_result = explode(",",$closest['color_sub_cat_rgb']);
		$sub_category_value=str_pad(dechex(($sub_rgb_result[0] << 16) + ($sub_rgb_result[1] << 8) + $sub_rgb_result[2]), 6, 0, STR_PAD_LEFT);
		$main_rgb_result = explode(",",$mclosest['color_rgb']);
		$main_category_value=str_pad(dechex(($main_rgb_result[0] << 16) + ($main_rgb_result[1] << 8) + $main_rgb_result[2]), 6, 0, STR_PAD_LEFT);

		// echo 'final color: '.$mclosest['color_name'];
		return $main_category_value;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	public function getDummyMatchingSet(){
		try{
			global $config;	
			$arrShopparInfo = array();

			$arrShopparInfo['colorCode'] = '#90EE90';
			$arrShopparInfo['prodType'] = 'Jeans';
			
			return $arrShopparInfo;

		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	//function to convert hex value to rgb
	function hex2rgb($hex)
	{
	    return array(
	            hexdec(substr($hex,1,2)),
	            hexdec(substr($hex,3,2)),
	            hexdec(substr($hex,5,2))
	        );
	}

	//calculate the distance between two colors
	function dist($col1,$col2) {
	  $delta_r = $col1[0] - $col2[0];
	  $delta_g = $col1[1] - $col2[1];
	  $delta_b = $col1[2] - $col2[2];
	  return $delta_r * $delta_r + $delta_g *$delta_g + $delta_b * $delta_b;
	} 
	public function __destruct(){
		/*** Destroy and unset the object ***/
		unset($objLoginQuery);
		unset($_pageSlug);
		unset($objConfig);
		unset($getConfig);
		unset($objCommon);
		unset($this->objPublic);
		unset($this->objShoppar);
	}
	
} /*** end of class ***/
?>