<?php 
/**** Include interfaces link ****/
//require_once(SRV_ROOT.'interfaces/interfaces.inc.php');

class cPageBuilder{

	/*** define public & private properties ***/
	
	 public $objConfig;
	 public $getConfig;
	 public $objQuery;
	 private $objModules;
	 
	/*** the constructor ***/
	public function __construct(){
		 require_once SRV_ROOT.'classes/modules.class.php';
		$this->objModules = new cModules();

		require_once(SRV_ROOT.'model/model.class.php');
			$this->objQuery = new Model();
	}

	/**** function to get all functions of Header and assign to header template ****/
	public function pageHeader($pUrl){
		try{
			global $config;
			/**** assign variables/array to header.tpl.php ****/
			//echo "header";
			 $pAction =  isset($pUrl[0]) ? $pUrl[0] :'';//for live
			
			if($pAction == 'press')
			{
			 $metaTitle="Mobile Commerce News – SeeMore Interactive";
			 $metaDescription="Get latest news on mobile commerce and interactive shopping from SeeMore Interactive,Inc. Our services include mobile commerce, mobile retail, image recognition, augmented reality apps, and more.";
			 $metaKeywords="mobile commerce news, m-commerce news, retail news, retail technology news, mobile technology news, augmented reality news, interactive shopping news, mobile shopping news";
			 
			
			}
			else if($pAction == 'features')
			{
			
			 $metaTitle="Features - Possibilities with SeeMore Interactive";
			 $metaDescription="Unlock exclusive content like promotions, videos, offers, tips and advice, offers and more with SeeMore Interactive. Our ShopparVision technology merges both physical and virtual world together into one seamless, engaging experience that captures valuable data and insights to increase brand engagement and conversion. SeeMore's cutting edge technology offers interactive experience to existing and new customers and our real-time analytics dashboard helps you to monitor and instantly improve campaigns.";
			 $metaKeywords="m-commerce, mobile coupon, image-recognition platform, 3D augmented reality, Virtual Closet, ShopparVision, Interactive content, Social Shopping, Omni-channel marketing,mobile augmented reality,augmented reality solutions";
			 
			}
			else if($pAction == 'capabilities')
			{
			
			 $metaTitle="Our Capabilities – What we Offer?";
			 $metaDescription="SeeMore Interactive, Inc. is one of the leading augmented reality firms in USA. We combine integrated marketing campaign with interactive content to create better sales opportunities.";
			 $metaKeywords="mobile commerce platform, mobile ecommerce, m-commerce, augmented reality application, augmented reality in retail, interactive marketing, interactive shopping, image recognition technology";
			 
			}
			else if($pAction == 'casestudies')
			{
			
			 $metaTitle="M-Commerce & Augmented Reality Case Studies – SeeMore Interactive";
			 $metaDescription="At SeeMore Interactive, we develop augmented reality apps and mobile retail solution for brands. We offer mobile ecommerce, interactive marketing, mobile shopping, image recognition technology and more.";
			 $metaKeywords="";
			 
			}
			else if($pAction == 'howitworks')
			{
			 $metaTitle="How it Works – Our Technology";
			 $metaDescription="At SeeMore Interactive, we merge the real-world retail environment with cloud-based technology for creating a dynamic shopping experience. We offer m-commerce, image recognition, augmented reality, and more.";
			 $metaKeywords="image recognition technology, mobile technology, retail technology, mobile commerce, augmented reality apps, interactive shopping, m-commerce, mobile shopping, mobile retail solutions";
			 
			
			}
			else if($pAction == '' )
			{
			 $metaTitle="Mobile Commerce, Augmented Reality & Interactive Shopping- SeeMore Interactive";
			 $metaDescription="SeeMore Interactive, Inc. is a leading augmented reality company in USA. We develop retail marketing platform providing augmented reality apps, m-commerce and integrated mobile marketing solution.";
			 $metaKeywords="mobile ecommerce, m-commerce, augmented reality apps, augmented reality iphone, m-commerce, augmented reality retail, interactive marketing, interactive content, interactive shopping, mobile shopping, image recognition app, augmented reality application, mobile commerce, retail augmented reality, augmented reality shopping, augmented reality in retail";
			 
			
			}
			else if($pAction == 'news')
			{
			
			  if($pUrl[1]=='detail' && $pUrl[2]=='1' ){
			 
			  $metaTitle="Infographic - Evolution of Shopping";
			  $metaDescription="SeeMore Interactive's infographic explores the evolution of shopping and how technology has impacted the way we shop.";
			  $metaKeywords="Infographic,infograph,mobile commerce infographic, shopping infographic,e-commerce infographic,mobile infographic,augmented reality infographic,retail infographic";
			  }
			  else{
			  
			  $metaTitle="News - SeeMore Interactive";
			  $metaDescription="Stay up-to-date with all the latest news from SeeMore Interactive.";
			  $metaKeywords="";
			  }
			}
			else if($pAction == 'pressrelease')
			{
			  $metaTitle="Press Releases - SeeMore Interactive";
			  $metaDescription="Stay updated about everything related to SeeMore Interactive. Check out SeeMore Interactive Press Releases to know more about the latest happenings about SeeMore Interactive.";
			  $metaKeywords="";
			  
			}
			else if($pAction == 'contact')
			{
			  $metaTitle="Contact Us - SeeMore Interactive";
			  $metaDescription="Want to connect with us ? Need a demo ? Have a question or enquiry ? We'd love to hear your thought so mail us or leave your details using our contact form. We'll get back to you at the earliest.";
			  $metaKeywords="";
			  
			}
			else if($pAction == 'howitworks')
			{
			  $metaTitle="";
			  $metaDescription="";
			  $metaKeywords="";
			  
			}
			else
			{
			  $metaTitle="";
			  $metaDescription="";
			  $metaKeywords="";
			  
			}
			include SRV_ROOT.'views/header.tpl.php';
			
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	/**** function to get all functions of Content and assign to content template ****/
	public function pageContent($pUrl){
		try{
			global $config;
			//print_r($pUrl);
			$pAction =  isset($pUrl[0]) ? $pUrl[0] :'';//for live
			//$pAction =  isset($pUrl[1]) ? $pUrl[1] :'';//for localhost
			//print_r($pUrl);
			/*** Declare local variable for this function ***/
			if ($pAction=='howitworks'){
				//include SRV_ROOT.'views/howitworks.tpl.php';
				//include SRV_ROOT.'views/home.tpl.php';
				header("Location: ".$config['LIVE_URL']); 
			}elseif ($pAction=='capabilities'){
				//include SRV_ROOT.'views/home.tpl.php';
				//include SRV_ROOT.'views/capabilities.tpl.php';
				header("Location: ".$config['LIVE_URL']); 
			}elseif ($pAction=='whyseemore'){
				include SRV_ROOT.'views/whyseemore.tpl.php';
			}elseif ($pAction=='press'){
				$pressReleasesArray=$this->objQuery->getPressReleases();
				$newsArray=$this->objQuery->getNewsList();
			   include SRV_ROOT.'views/press.tpl.php';
			}
			elseif ($pAction=='news'){
				if(isset($pUrl[1]) && $pUrl[1]=='detail'){//for live
				//if(isset($pUrl[2]) && $pUrl[2]=='detail'){//for localhost
					//news details page
					$arrNewsDetailsById=$this->objQuery->getNewsListById($pUrl[2]);
					//print_r($arrNewsDetailsById);
					include SRV_ROOT.'views/news_details_page.tpl.php';
				}else
				{
					$newsArray=$this->objQuery->getNewsList();
					include SRV_ROOT.'views/news_list.tpl.php';
				}
				
				
			}
			elseif ($pAction=='pressrelease'){
				$pressReleasesArray=$this->objQuery->getPressReleases();
			        include SRV_ROOT.'views/press_release_archive.tpl.php';
			}
			elseif ($pAction=='casestudies'){
 				   // include SRV_ROOT.'views/casestudies.tpl.php';
				//include SRV_ROOT.'views/home.tpl.php';
				header("Location: ".$config['LIVE_URL']); 
 			}
 			elseif ($pAction=='features'){
 				    //include SRV_ROOT.'views/features.tpl.php';
 				//include SRV_ROOT.'views/home.tpl.php';
 				header("Location: ".$config['LIVE_URL']); 
 			}
 			elseif ($pAction=='offline'){
 				    include SRV_ROOT.'views/underconstruction.tpl.php';
 			}
 			elseif ($pAction=='jobs'){
 				    //include SRV_ROOT.'views/jobs.tpl.php';
 				    //include SRV_ROOT.'views/home.tpl.php';

                    header("Location: ".$config['LIVE_URL']); 

 			}
			elseif ($pAction=='home'){
			   if(isset($pUrl[1]) && $pUrl[1]=='privacy'){//for live
			   //if(isset($pUrl[2]) && $pUrl[2]=='privacy'){//for localhost
			   include SRV_ROOT.'views/home/privacy.tpl.php';
			   }
			   else if(isset($pUrl[1]) && $pUrl[1]=='terms'){//for live
			   //if(isset($pUrl[2]) && $pUrl[2]=='terms'){//for localhost
			   include SRV_ROOT.'views/home/terms_and_conditions.tpl.php';
			   }
			   else
			   {
			   include SRV_ROOT.'views/home.tpl.php';
			   }
			   
			}elseif ($pAction=='contact'){
			include SRV_ROOT.'views/contact.tpl.php';
			}
			
			elseif(empty($pAction))
			{
			include SRV_ROOT.'views/home.tpl.php';
			}
			else{
			include SRV_ROOT.'views/404.tpl.php';
			}
		
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	/**** function to get Page Footer ****/
	public function pageFooter(){
		try{
			global $config;
			include SRV_ROOT.'views/footer.tpl.php';
			
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