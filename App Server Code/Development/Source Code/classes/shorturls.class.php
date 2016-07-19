<?php 
 class cShortUrls{

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
			require_once(SRV_ROOT.'model/shorturls.model.class.php');
			$this->objShortUrlsQuery = new mShortUrls();
			
			/**** Create Model Class and Object ****/
			//require_once(SRV_ROOT.'classes/common.class.php');
			//$this->objCommon = new cCommon();
			require_once(SRV_ROOT.'classes/Array2XML.php');
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function modCreateShortUrl($pUrl)
	{
		try
		{
			//print_r($pUrl);
			global $config;
			$longUrl=isset($_REQUEST['longurl']) ? $_REQUEST['longurl'] : $pUrl[2];
			
			$url_to_shorten = get_magic_quotes_gpc() ? stripslashes(trim($longUrl)) : trim($longUrl);
			$sendArray=array();
			$sendArray['long_url']=$url_to_shorten;
			
			$arrResults=array();
			//if(!empty($url_to_shorten) && preg_match('|^https?://|', $url_to_shorten))
			if(!empty($url_to_shorten))
			{
				
				
				$file_headers = @get_headers($url_to_shorten);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
					//$exists = false;
					$arrResults['msg']="fail";
					$arrResults['error']="URL not Valid";
					
				}
				else {
					//$exists = true;
					// check if the URL has already been shortened
					$already_shortened = $this->objShortUrlsQuery->checkUrlShortened($sendArray);
					
					if(!empty($already_shortened))
					{
						// URL has already been shortened
						$shortened_url = isset($already_shortened[0]['code']) ? $already_shortened[0]['code'] :'';
						//echo "already".$shortened_url;
					}
					else
					{
						
						$pArray=array();
						$shortCode = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5); 
						//echo $shortCode;
						$pArray['long_url']=$url_to_shorten;
						$pArray['code']=$shortCode;
						$pTableName="short_urls";
						$arrInsertShortUrl = $this->objShortUrlsQuery->insertQuery($pArray, $pTableName, "true");
						//$shortened_url =  $this->getShortenedURLFromID($arrInsertShortUrl);
						$shortened_url=$shortCode;
					}
					$arrResults['msg']="success";
					$arrResults['short_url']=$config['LIVE_URL'].'shorturl/'.$shortened_url;
				}

				
			    
            }
			else{
				$arrResults['msg']="fail";	
			}
			
			
			echo json_encode($arrResults);

			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getShortenedURLFromID ($integer, $base = ALLOWED_CHARS)
	{
		$length = strlen($base);
		while($integer > $length - 1)
		{
			$out = $base[fmod($integer, $length)] . $out;
			$integer = floor( $integer / $length );
		}
		return $base[$integer] . $out;
	}
	
	function modGetShortUrl($pUrl)
	{
		try
		{
			$sendArray=array();
			$sendArray['short_code']=isset($_REQUEST['longurl']) ? $_REQUEST['longurl'] : $pUrl[1];
			$arrResults=array();
			if(!preg_match('|^[0-9a-zA-Z]{1,6}$|', $sendArray['short_code']))
			{
				die('That is not a valid short url');
			}
			else
			{
				$arrShortUrlInfo=array();
				$arrShortUrlInfo = $this->objShortUrlsQuery->getShortUrlsInfoByCode($sendArray);
				//print_r($arrShortUrlInfo);
				
				$long_url=isset($arrShortUrlInfo[0]['long_url']) ? $arrShortUrlInfo[0]['long_url'] :"";
				ob_start();
				$long_url="http://".$long_url;
				echo '<script>window.location = "'.$long_url.'";</script>';
				
				/* header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' .  $long_url);
				exit; */
			}
			
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function getIDFromShortenedURL ($string, $base = ALLOWED_CHARS)
	{
		$length = strlen($base);
		$size = strlen($string) - 1;
		$string = str_split($string);
		$out = strpos($base, array_pop($string));
		foreach($string as $i => $char)
		{
			$out += strpos($base, $char) * pow($length, $size - $i);
		}
		return $out;
	}


	public function __destruct(){
		/*** Destroy and unset the object ***/
	}
	
}  /*** end of class ****/
?>