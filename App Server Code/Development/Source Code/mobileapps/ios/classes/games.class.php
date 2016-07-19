<?php 
class cGames{

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
			require_once(SRV_ROOT.'model/games.model.class.php');
			$this->objGames = new mGames();
			
			require_once SRV_ROOT.'classes/public.class.php';
			$this->objPublic = new cPublic();
	
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	public function modClientGameDetailsByCGID($client_game_id){
		try{
		
			global $config;			
			$sendArray=array();
			$sendArray['client_game_id'] = $client_game_id;
			
			$outArrGameDetails=array();
			$outArrGameDetails=$this->objGames->getClientGameDetailsByCGID($sendArray);
			
			$arrData=array();
			$arrData['client_games_details_id']=isset($outArrGameDetails[0]['client_games_details_id']) ? $outArrGameDetails[0]['client_games_details_id'] : 0;
			$arrData['client_game_id']=isset($outArrGameDetails[0]['client_game_id']) ? $outArrGameDetails[0]['client_game_id'] : 0;
			$arrData['games_id']=isset($outArrGameDetails[0]['games_id']) ? $outArrGameDetails[0]['games_id'] : 0;
			$arrData['game_permission_msg']=isset($outArrGameDetails[0]['game_permission_msg']) ? $outArrGameDetails[0]['game_permission_msg'] : 0;
			$arrData['game_rules']=isset($outArrGameDetails[0]['game_rules']) ? $outArrGameDetails[0]['game_rules'] : 0;
			$arrData['game_rules_url']=isset($outArrGameDetails[0]['game_rules_url']) ? $outArrGameDetails[0]['game_rules_url'] : 0;
			$arrData['direction_type']=isset($outArrGameDetails[0]['direction_type']) ? $outArrGameDetails[0]['direction_type'] : 0;
			$instructionsImage=isset($outArrGameDetails[0]['image']) ? $outArrGameDetails[0]['image'] : 0;
			$arrData['image']="http://".$_SERVER['HTTP_HOST']."/files/clients/".$sendArray['client_id']."/games/".$outArrGameDetails[0]['game_id']."/".$instructionsImage;
			
			return $outArrGameDetails;
				
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}	
	public function modGames($pID){
		try{
		
			global $config;			
			$arrGamesInfo = $this->objGames->getGames();
			echo json_encode($arrGamesInfo);
				
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}	
	public function modClientGames($pID){		
		try{
			global $config;
			$sendArray=array();
			$sendArray['client_game_id'] = isset($pID['client_game_id']) ? $pID['client_game_id'] : '1';
			$sendArray['user_id'] = isset($pID['user_id']) ? $pID['user_id'] : '0';
			//$sendArray['client_id']=isset($pID['client_id']) ? $pID['client_id'] : '0';
			$outArrGame = array();
			if($sendArray['client_game_id']  != ""){
				
				//first get game_id by client_game_id
				$arrGameId=$this->objGames->getGameIdByCGId($sendArray);
				$game_id=isset($arrGameId[0]['game_id']) ? $arrGameId[0]['game_id'] : 0;
				
				//get random client_game_id from game_id and client id
				$sendArray['game_id']=$game_id;
				$sendArray['client_id']=isset($arrGameId[0]['client_id']) ? $arrGameId[0]['client_id'] : 0;
				$arrRandomClientGameId=$this->objGames->getRandomClientGameId($sendArray);
				$randomClientGameId=isset($arrRandomClientGameId[0]['client_game_id']) ? $arrRandomClientGameId[0]['client_game_id'] : 0;
				$sendArray['client_game_id'] = $randomClientGameId;
				
				$arrGamesInfo = $this->objGames->getGamesInfo($sendArray);
				$outArrGame = array();
				$arrFinalResult=array();
				if(count($arrGamesInfo) > 0){
					for($i=0;$i<count($arrGamesInfo);$i++)
					{
						
						$clientGamesItemId=isset($arrGamesInfo[$i]['client_games_item_id']) ? $arrGamesInfo[$i]['client_games_item_id'] : 0;
						
						//check used scratched offers in the client_games_users table with client_game_item_id,user_id before getting random client_game_id
						$sendArray['client_games_item_id']=$clientGamesItemId;
						$checkUsedOffers=$this->objGames->checkGameItemsUsed($sendArray);
						if($checkUsedOffers[0]['client_games_item_id']!=$clientGamesItemId)
						{
							$arrFinalResult[$i]['client_games_item_id']=isset($arrGamesInfo[$i]['client_games_item_id']) ? $arrGamesInfo[$i]['client_games_item_id'] : 0;
							
							$arrFinalResult[$i]['client_game_id']=isset($arrGamesInfo[$i]['client_game_id']) ? $arrGamesInfo[$i]['client_game_id'] : 0;
							$arrFinalResult[$i]['game_id']=isset($arrGamesInfo[$i]['game_id']) ? $arrGamesInfo[$i]['game_id'] : 0;						$arrFinalResult[$i]['client_id']=isset($arrGamesInfo[$i]['client_id']) ? $arrGamesInfo[$i]['client_id'] : 0;				$arrFinalResult[$i]['offer_id']=isset($arrGamesInfo[$i]['offer_id']) ? $arrGamesInfo[$i]['offer_id'] : 0;
							
							$arrFinalResult[$i]['game_permission_msg']=isset($arrGamesInfo[$i]['game_permission_msg']) ? $arrGamesInfo[$i]['game_permission_msg'] : 0;	
							
							$sendArray=array();
							$sendArray['offer_id']=isset($arrGamesInfo[$i]['offer_id']) ? $arrGamesInfo[$i]['offer_id'] : 0;
							$sendArray['client_id']=isset($arrGamesInfo[$i]['client_id']) ? $arrGamesInfo[$i]['client_id'] : 0;
							$sendArray['file_url']="http://".$_SERVER['HTTP_HOST']."/files/clients/".$arrGamesInfo[$i]['client_id'] ."/products/";
							
							$sendArray['client_logo_url']="http://".$_SERVER['HTTP_HOST']."/files/clients/".$arrGamesInfo[$i]['client_id'] ."/logo/";
							
							$arrOfferInfo = $this->objGames->getClientWithOfferDetailsWithOfferID($sendArray);
							$clientInfo= $this->objGames->getClientInfoByClientId($sendArray);
							
							
							$arrFinalResult[$i]['scratch_type']=isset($arrGamesInfo[$i]['scratch_type']) ? $arrGamesInfo[$i]['scratch_type'] : 0;
							$arrFinalResult[$i]['x']=isset($arrGamesInfo[$i]['x']) ? $arrGamesInfo[$i]['x'] : 0;
							$arrFinalResult[$i]['y']=isset($arrGamesInfo[$i]['y']) ? $arrGamesInfo[$i]['y'] : 0;
							$arrFinalResult[$i]['value']=isset($arrGamesInfo[$i]['value']) ? $arrGamesInfo[$i]['value'] : 0;
							$scratchImage=isset($arrGamesInfo[$i]['scratch_image']) ? $arrGamesInfo[$i]['scratch_image'] : 0;						    $arrFinalResult[$i]['scratch_image']="http://".$_SERVER['HTTP_HOST']."/files/clients/".$sendArray['client_id']."/games/".$arrGamesInfo[$i]['game_id']."/".$scratchImage;
							$arrFinalResult[$i]["game_image"] = "http://".$_SERVER['HTTP_HOST']."/files/clients/".$sendArray['client_id']."/games/".$arrGamesInfo[$i]['game_id']."/".$arrGamesInfo[$i]["game_image"];
							$arrFinalResult[$i]['seg_color']=isset($arrGamesInfo[$i]['seg_color']) ? $arrGamesInfo[$i]['seg_color'] : 0;
								
							$arrFinalResult[$i]['client_info']=$clientInfo;
							$arrFinalResult[$i]['offer_info']=$arrOfferInfo;
						
							
							
						}
							
						
				
						
						
						
						
					}
				}
			    //print_r($arrFinalResult);
				echo json_encode($arrFinalResult);
			}
			else
			{
				echo "No data available";
			}
		}
		catch ( Exception $e )
		{
			echo 'Message: ' .$e->getMessage();
		}
	}
								
	
	public function __destruct(){
		/*** Destroy and unset the object ***/
		/*** Destroy and unset the object ***/
		unset($objLoginQuery);
		unset($_pageSlug);
		unset($objConfig);
		unset($getConfig);
		unset($objCommon);
		unset($this->objPublic);
	}
	
} /*** end of class ***/
?>