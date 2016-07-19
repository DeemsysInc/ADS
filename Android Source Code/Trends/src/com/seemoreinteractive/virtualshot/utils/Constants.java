package com.seemoreinteractive.virtualshot.utils;



public class Constants {
	public final static long CHARACTER_TIMEOUT_IN_MILLIS = 30000;
	public static final String FB_PREF = "fb_pref";
	//public static final String FACEBOOK_APP_ID = "325690967527295";
	public static final String FACEBOOK_APP_ID = "418465138283334";
	public final static String TAG = "Trends";
	// flickr
	public static final String FLICKR_CALLBACK_SCHEME = "trends-flickr";
	public static final String FLICKR_TOKEN = "flickr_token";
	public static final String FLICKR_TOKEN_SECRET = "flickr_token_secret";
	public static final String FLICKR_APP_KEY = "260b1f102259c0dfb7c29acc009fb57f";
	public static final String FLICKR_APP_KEY_SECRET = "7171f3eb0d8a4ea6";
	public static final String FLICKR_PREF = "flickr_pref";
	// twitter
	public static final String TWITTER_PREF = "twitter_pref";
	public static final String TWITTER_CALLBACK_SCHEME = "trends-twitter";
	public static final String TWITTER_CALLBACK_HOST = "callback";
	public static final String TWITTER_CALLBACK_URL = TWITTER_CALLBACK_SCHEME + "://" + TWITTER_CALLBACK_HOST;
	public static final String TWITTER_CONSUMER_KEY = "QEtZ8wBGuw752ffqWoRWA";
	public static final String TWITTER_CONSUMER_SECRET = "IMdpooAynW5nsbQQ7wqGKGFEJscSng2BpggPIFeCw";
	public static final String TWITTER_REQUEST_URL = "https://api.twitter.com/oauth/request_token";
	public static final String TWITTER_ACCESS_URL = "https://api.twitter.com/oauth/access_token";
	public static final String TWITTER_AUTHORIZE_URL = "https://api.twitter.com/oauth/authorize";
	public static final String TWITTER_TOKEN = "twitter_token";
	public static final String TWITTER_TOKEN_SECRET = "twitter_token_secret";
	// local
	public static final String TEMP_IMAGE_NAME = "Trends.jpg";
	public static final String TRENDS_PREF = "trends_pref";
	public static final String TRENDS_CMS_URL = "http://trends-prod.herokuapp.com/public/retrieveMarkers";
	public final static String LOCATION = "/data/data/com.seemoreinteractive.virtualshot/files/";
	//public final static String LOCATION = Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.seemoreinteractive.virtualshot/files/";
}
