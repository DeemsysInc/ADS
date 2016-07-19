package com.seemoreinteractive.seemoreinteractive.Utils;

import android.util.Log;

import com.seemoreinteractive.seemoreinteractive.helper.Common;


public class Constants {
	
	// LIVE SERVER
	public static String Live_Url = "http://arapps.seemoreinteractive.com/";
	public static String Live_Url_Main = "http://arapps.seemoreinteractive.com/";	
	public static String Live_Url_Staging = "http://stagingarapps.seemoreinteractive.com/";
	public static String GOOGLE_ANALYTICS = "UA-33916771-2";
	public static String Quantcast_Key= "0luz8yemes2zuazu-p2ttt86s03wyepne";
	public static String Webservices_Folder = "android_1.15";
	/*************************************************************************************/
	
	//DEV SERVER
	/*public static String Live_Url_Dev = "http://devarapps.seemoreinteractive.com/";	
	public static String Live_Url_Staging = "http://stagingarapps.seemoreinteractive.com/";
	public static String GOOGLE_ANALYTICS = "UA-44202140-3";	
	public static String Quantcast_Key = "04rfo14n7u8f1xiz-s7wk616kh7jy4vwh";	
	public static String Live_Url = Live_Url_Dev;
	public static String Live_Url_Main = Live_Url_Dev;
	public static String Webservices_Folder = "android_1.15";*/
	
	/*************************************************************************************/

	public static String Live_Url_Secondary = "http://stagingarapps.seemoreinteractive.com/";	
	//public static String Webservices_Folder = "android"+Common.sessionForAndroidVersionName;	
	//public static String Webservices_Folder = "android_1.14";	
	public static String Live_Android_Url = Live_Url+"mobileapps/"+Webservices_Folder+"/";
	
	public static String Triggers_URL = Live_Android_Url+"triggers_xml/";
	public static String Client_Triggers_Xml_URL = Live_Android_Url+"client_trigger_xml/";
	public static String Client_Xml_URL = Live_Android_Url+"client_xml/";
	public static String Client_Url = Live_Android_Url+"client_product/";

	public static String Closet_Url = Live_Android_Url+"closet/";
	public static String ClosetWishlist_Url = Live_Android_Url+"closet_wishlist/";
	public static String WishList_Url = Live_Android_Url+"wishlist/";
	public static String WishListYourCloset_Url = WishList_Url+"your_closet/";
	public static String ShareWishList_Url = WishList_Url+"share/";
	public static String FriendsWishList_Url = WishList_Url+"friends_wishlist/";
	public static String FriendsProductWishList_Url = WishList_Url+"friends_wishlist_product/";
	public static String AddItemtoWishList_Url = WishList_Url+"additemtowishlist/";
	public static String AddOffersWishList_Url = Live_Android_Url+"addofferstowishlist/";
	public static String DeleteWishList_Url = Live_Android_Url+"deletewishlist/";
	public static String DeleteWishListPd_Url = Live_Android_Url+"deletewishlistproduct/";
	public static String TapForDetials_Url = Live_Android_Url+"tap_for_details/";
	public static String ClientProdDetails_Url = Live_Android_Url+"get_client_with_product_details/";
	public static String MyOffers_Url = Live_Android_Url+"my_offers/";
	public static String MyOffersReddem_Url = Live_Android_Url+"my_offers/redeem/";
	public static String DeleteMyOffers_Url = Live_Android_Url+"my_offers/delete/";
	public static String OfferInfo_Url = MyOffers_Url+"offer_info/";
	public static String ClientStore_Url = Live_Android_Url+"client_stores/";

	public static String userURL = Constants.Live_Android_Url+"user_details/";
	public static String loginURL = Constants.Live_Android_Url+"login/";
	public static String registerURL = Constants.Live_Android_Url+"register/";
	public static String forgotPasswordURL = Constants.Live_Android_Url+"forgotpassword/";
	public static String stateURL = Constants.Live_Android_Url+"state_list/";
	public static String updateUserURL = Constants.Live_Android_Url+"user_details/update/";	
	public static String crashLog = Constants.Live_Android_Url+"crash_log/";	
	public static String changeLog = Constants.Live_Android_Url+"change_log/";	
	public static String visualUrl = Constants.Live_Android_Url+"visual_xml/";	
	public static String Client_Logo_Location = Live_Url+"files/clients/";
	public static Boolean ARFlag = true;
	public Constants() {
		Log.i("Change url3", Common.sessionIdForUserGroupId+" "+Live_Url);
		if(Common.sessionIdForUserGroupId==4){
			Log.i("Webservices_Folder", Common.sessionForAndroidVersionName+" ---- "+Webservices_Folder);
			Live_Url = Live_Url_Secondary;	
			//Live_Url = Live_Url_Dev;
			Live_Android_Url = Live_Url+"mobileapps/"+Webservices_Folder+"/";
						
			Triggers_URL = Live_Android_Url+"triggers_xml/";
			Client_Triggers_Xml_URL = Live_Android_Url+"client_trigger_xml/";
			Client_Xml_URL = Live_Android_Url+"client_xml/";
			Client_Url = Live_Android_Url+"client_product/";

			Closet_Url = Live_Android_Url+"closet/";
			ClosetWishlist_Url = Live_Android_Url+"closet_wishlist/";
			WishList_Url = Live_Android_Url+"wishlist/";
			WishListYourCloset_Url = WishList_Url+"your_closet/";
			ShareWishList_Url = WishList_Url+"share/";
			FriendsWishList_Url = WishList_Url+"friends_wishlist/";
			FriendsProductWishList_Url = WishList_Url+"friends_wishlist_product/";
			AddItemtoWishList_Url = WishList_Url+"additemtowishlist/";
			AddOffersWishList_Url = Live_Android_Url+"addofferstowishlist/";
			DeleteWishList_Url = Live_Android_Url+"deletewishlist/";
			DeleteWishListPd_Url = Live_Android_Url+"deletewishlistproduct/";
			TapForDetials_Url = Live_Android_Url+"tap_for_details/";
			ClientProdDetails_Url = Live_Android_Url+"get_client_with_product_details/";
			MyOffers_Url = Live_Android_Url+"my_offers/";
			MyOffersReddem_Url = Live_Android_Url+"my_offers/redeem/";
			DeleteMyOffers_Url = Live_Android_Url+"my_offers/delete/";
			OfferInfo_Url = MyOffers_Url+"offer_info/";
			ClientStore_Url = Live_Android_Url+"client_stores/";

			userURL = Constants.Live_Android_Url+"user_details/";
			loginURL = Constants.Live_Android_Url+"login/";
			registerURL = Constants.Live_Android_Url+"register/";
			forgotPasswordURL = Constants.Live_Android_Url+"forgotpassword/";
			stateURL = Constants.Live_Android_Url+"state_list/";
			updateUserURL = Constants.Live_Android_Url+"user_details/update/";	
			crashLog = Constants.Live_Android_Url+"crash_log/";
			changeLog = Constants.Live_Android_Url+"change_log/";
			visualUrl = Constants.Live_Android_Url+"visual_xml/";
			Client_Logo_Location = Live_Url+"files/clients/";
		} else {
			Log.i("Webservices_Folder", Common.sessionForAndroidVersionName+" ---- "+Webservices_Folder);
			Live_Url = Live_Url_Main;
			//Live_Android_Url = Live_Url+"mobileapps/android_1.10/";
			Live_Android_Url = Live_Url+"mobileapps/"+Webservices_Folder+"/";
						
			Triggers_URL = Live_Android_Url+"triggers_xml/";
			Client_Triggers_Xml_URL = Live_Android_Url+"client_trigger_xml/";
			Client_Xml_URL = Live_Android_Url+"client_xml/";
			Client_Url = Live_Android_Url+"client_product/";

			Closet_Url = Live_Android_Url+"closet/";
			ClosetWishlist_Url = Live_Android_Url+"closet_wishlist/";
			WishList_Url = Live_Android_Url+"wishlist/";
			WishListYourCloset_Url = WishList_Url+"your_closet/";
			ShareWishList_Url = WishList_Url+"share/";
			FriendsWishList_Url = WishList_Url+"friends_wishlist/";
			FriendsProductWishList_Url = WishList_Url+"friends_wishlist_product/";
			AddItemtoWishList_Url = WishList_Url+"additemtowishlist/";
			AddOffersWishList_Url = Live_Android_Url+"addofferstowishlist/";
			DeleteWishList_Url = Live_Android_Url+"deletewishlist/";
			DeleteWishListPd_Url = Live_Android_Url+"deletewishlistproduct/";
			TapForDetials_Url = Live_Android_Url+"tap_for_details/";
			ClientProdDetails_Url = Live_Android_Url+"get_client_with_product_details/";
			MyOffers_Url = Live_Android_Url+"my_offers/";
			MyOffersReddem_Url = Live_Android_Url+"my_offers/redeem/";
			DeleteMyOffers_Url = Live_Android_Url+"my_offers/delete/";
			OfferInfo_Url = MyOffers_Url+"offer_info/";
			ClientStore_Url = Live_Android_Url+"client_stores/";

			userURL = Constants.Live_Android_Url+"user_details/";
			loginURL = Constants.Live_Android_Url+"login/";
			registerURL = Constants.Live_Android_Url+"register/";
			forgotPasswordURL = Constants.Live_Android_Url+"forgotpassword/";
			stateURL = Constants.Live_Android_Url+"state_list/";
			updateUserURL = Constants.Live_Android_Url+"user_details/update/";
			crashLog = Constants.Live_Android_Url+"crash_log/";
			changeLog = Constants.Live_Android_Url+"change_log/";
			visualUrl = Constants.Live_Android_Url+"visual_xml/";
			Client_Logo_Location = Live_Url+"files/clients/";
		}
		Log.i("Change url4", Common.sessionIdForUserGroupId+" "+Live_Url);
	}	
	
	//public static String Product_Url = Live_Url_Android+"product_details/";
	//public static String Client_Product_Url = Live_Url_Android+"product/";

	/*public static String LOCATION = Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/";
	public static String Model_Location= Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/model/";
	public static String Products_Location = Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/products/";
	*/
	//public static String LOCATION = Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/";
	public static String LOCATION = "/data/data/com.seemoreinteractive.seemoreinteractive/files/";
	public static String Trigger_Location= LOCATION+"all_triggers/";
	public static String Products_Location = LOCATION+"products/";
	public static String Model_Location = LOCATION+"model/";

	public static String FACEBOOK_APP_ID = "141475799356297";
	public static String FACEBOOK_TOKEN_ID = "7a3a803df217b8b5d5ac6fd5077b4b5a";
	//public static String FACEBOOK_APP_ID = "646865485392712";
	//public static String FACEBOOK_TOKEN_ID = "1855a45b769f396715c641be3a050402";
	
	//old key
//public static String GOOGLE_API_KEY = "AIzaSyA5_UuK9Ct293FhhjTDnUCQyfYIBKocu0E";

	
//new key 
public static String GOOGLE_API_KEY = "AIzaSyDudAw2GaSztN__znSH1P3RUl7bMT0S-ig";	

	
	
}
