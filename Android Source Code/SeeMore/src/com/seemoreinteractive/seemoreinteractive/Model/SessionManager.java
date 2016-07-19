package com.seemoreinteractive.seemoreinteractive.Model;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Set;

import org.json.JSONArray;
import org.json.JSONException;

import android.annotation.TargetApi;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.Build;
import android.util.Log;

import com.seemoreinteractive.seemoreinteractive.ARDisplayActivity;
import com.seemoreinteractive.seemoreinteractive.ProductList;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.HONEYCOMB)
public class SessionManager {
	// Shared Preferences
	SharedPreferences pref;
	
	// Editor for Shared preferences
	public static Editor editor;
	
	// Context
	Context _context;
	
	// Shared pref mode
	int PRIVATE_MODE = 0;
	
	// Sharedpref file name
	private static final String PREF_NAME = "LoginPrefs";
	
	// All Shared Preferences Keys
	private static final String IS_LOGIN = "IsLoggedIn";

	// User id (make variable public to access from outside)
	public static final String KEY_SESSUSERID = "sessionLoggedInUserId";
	public static final String KEY_SESSUSERGROUPID = "sessionLoggedInUserGroupId";
	
	// User name (make variable public to access from outside)
	public static final String KEY_NAME = "name";
	
    public static final String KEY_FNAME = "fname";
    
	public static final String KEY_LNAME = "lname";
    
	public static final String KEY_PASSWORD = "password";
	
	// Email address (make variable public to access from outside)
	public static final String KEY_EMAIL = "email";
	
	public static final Set<String> closetSessionVals = new HashSet<String>();
	public static final Set<String> ownitSessionVals = new HashSet<String>();
	public static final Set<String> wantitSessionVals = new HashSet<String>();
	public static final Set<String> userClientsSessionVals = new HashSet<String>();
	public static final JSONArray a = new JSONArray();
	// Constructor
	public SessionManager(Context context){
		this._context = context;
		pref = _context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
		editor = pref.edit();
	}
	
	/**
	 * Create login session
	 * */
	public void createLoginSession(String arrValues[]){
		Log.i("arrValues",""+arrValues);
		// Storing login value as TRUE
		editor.putBoolean(IS_LOGIN, true);

		// Storing id in pref
		editor.putString(KEY_SESSUSERID, arrValues[0]);
		
		// Storing name in pref
		editor.putString(KEY_NAME, arrValues[1]);
		editor.putString(KEY_EMAIL, arrValues[2]);
		editor.putString(KEY_FNAME, arrValues[3]);
		editor.putString(KEY_LNAME, arrValues[4]);
		editor.putString(KEY_SESSUSERGROUPID, arrValues[5]);
		editor.putString(KEY_PASSWORD, arrValues[6]);
		
		
		// Storing email in pref
		
		// commit changes
		editor.commit();
	}	

	public static final String KEY_ANDROID_VERSION_NAME = "androidVersionName";
	public void createForStoredAndroidVersionName(String stringArray[]){
		// Storing login value as TRUE
		editor.putString(KEY_ANDROID_VERSION_NAME, stringArray[0]);			
		
		// commit changes
		editor.commit();
	}	
	public HashMap<String, String> getStoredAndroidVersionName(){
		HashMap<String, String> storedAndroidVer = new HashMap<String, String>();
		storedAndroidVer.put(KEY_ANDROID_VERSION_NAME, pref.getString(KEY_ANDROID_VERSION_NAME, null));
				
		return storedAndroidVer;
	}

	public static final String KEY_DEVICE_WIDTH = "deviceWidth";
	public static final String KEY_DEVICE_HEIGHT = "deviceHeight";
	public static final String KEY_DEVICE_DENSITY = "deviceDensity";
	public static final String KEY_DEVICE_OPEN_DATE = "devicepenDate";
	public void createDisplayMetrics(String stringArray[]){
		// Storing login value as TRUE
		editor.putString(KEY_DEVICE_WIDTH, stringArray[0]);		
		editor.putString(KEY_DEVICE_HEIGHT, stringArray[1]);
		editor.putString(KEY_DEVICE_DENSITY, stringArray[2]);		
		
		//For User ANalytics only............
		editor.putString(KEY_SESSID_USERANALYTICS, Common.randomString(40));
		// commit changes
		editor.commit();
	}	
	public HashMap<String, String> getDisplayMetricsDetails(){
		HashMap<String, String> displayMetrics = new HashMap<String, String>();
		displayMetrics.put(KEY_DEVICE_WIDTH, pref.getString(KEY_DEVICE_WIDTH, null));
		displayMetrics.put(KEY_DEVICE_HEIGHT, pref.getString(KEY_DEVICE_HEIGHT, null));
		displayMetrics.put(KEY_DEVICE_DENSITY, pref.getString(KEY_DEVICE_DENSITY, null));
		//For User Logged in
		displayMetrics.put(KEY_SESSUSERID, pref.getString(KEY_SESSUSERID, null));
		
		//For User ANalytics only............
		displayMetrics.put(KEY_SESSID_USERANALYTICS, ""+pref.getString(KEY_SESSID_USERANALYTICS, null));
		
		//App opened date
		displayMetrics.put(KEY_DEVICE_OPEN_DATE, ""+pref.getString(KEY_DEVICE_OPEN_DATE, null));
		
		return displayMetrics;
	}
	
	public void createAppOpenDate(){
		
		//App opened date
			Date d = Calendar.getInstance().getTime(); // Current time
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd"); // Set your date format
			String date = sdf.format(d);
			editor.putString(KEY_DEVICE_OPEN_DATE, date);
			// commit changes
			editor.commit();
	
	}
	
	public HashMap<String, String> getAppOpenDate(){
		HashMap<String, String> displayDate = new HashMap<String, String>();	
		//App opened date
		displayDate.put(KEY_DEVICE_OPEN_DATE, ""+pref.getString(KEY_DEVICE_OPEN_DATE, null));	
		return displayDate;
	}

	
	
	
	/**
	 * Create product and client values in session
	 * */
	public static final String KEY_SESSCLIENT_ID = "sessionClientId";
	public static final String KEY_SESSCLIENT_LOGO = "sessionClientLogo";
	public static final String KEY_SESSCLIENT_NAME = "sessionClientImageName";
	public static final String KEY_SESSCLIENT_BGIMAGE = "sessionClientBackgroundImage";
	public static final String KEY_SESSCLIENT_BGCOLOR = "sessionClientBackgroundColor";
	public static final String KEY_SESSCLIENT_URL = "sessionClientUrl";
	public static final String KEY_SESSPROD_ID = "sessionProductId";
	public static final String KEY_SESSPROD_NAME = "sessionProductName";
	public static final String KEY_SESSPROD_PRICE = "sessionProductPrice";
	public static final String KEY_SESSPROD_SHORTDESC = "sessionProductShortDesc";
	public static final String KEY_SESSTAP_ID = "sessionTapForDetailsId";
	public static final String KEY_SESSTAP_PRODID = "sessionTapForDetailsPdId";
	public static final String KEY_SESSPROD_URL = "sessionProductUrl";
	public static final String KEY_SESSPROD_BGCOLOR = "sessionProductBgColor";
	public static final String KEY_SESSPROD_HideImage = "sessionProductHideImage";
	public static final String KEY_SESSPROD_BuyButtonName = "sessionBuyButtonName";
	public static final String KEY_SESSPROD_BuyButtonUrl = "sessionBuyButtonUrl";
	public static String KEY_SESSPROD_ISTRYON = "sessionProdIsTryOn";
	public static String KEY_SESSPROD_OwnIt = "sessionOwnIt";
	public static  String KEY_SESSPROD_WantIt = "sessionWantIt";
	public static final String KEY_SESSPROD_CLOSET = "sessionCloset";
	public static final String KEY_SESSCLIENT_BGLIGHTCOLOR = "sessionClientBackgroundLightColor";
	public static final String KEY_SESSCLIENT_BGDARKCOLOR = "sessionClientBackgroundDarkColor";
	
	public static final String KEY_SESSID_USERANALYTICS = "sessionIdForUserAnalytics";

	public static final String KEY_SESSPROD_UserClients= "sessionUserClient";
	
	public void createClientProductValuesInSession(String arrValues[]){
		editor.putBoolean("values", false);
		//Log.i("arrValues", ""+arrValues[3]);
		if(arrValues.length>0){
			editor.putBoolean("values", true);
			editor.putString(KEY_SESSCLIENT_ID, arrValues[0]);
			editor.putString(KEY_SESSCLIENT_LOGO, arrValues[1]);
			editor.putString(KEY_SESSCLIENT_NAME, arrValues[2]);
			editor.putString(KEY_SESSCLIENT_BGIMAGE, arrValues[3]);
			editor.putString(KEY_SESSCLIENT_BGCOLOR, arrValues[4]);
			editor.putString(KEY_SESSCLIENT_URL, arrValues[5]);
			editor.putString(KEY_SESSPROD_ID, arrValues[6]);
			editor.putString(KEY_SESSPROD_NAME, arrValues[7]);
			editor.putString(KEY_SESSPROD_PRICE, arrValues[8]);
			editor.putString(KEY_SESSPROD_SHORTDESC, arrValues[9]);
			editor.putString(KEY_SESSTAP_ID, arrValues[10]);
			editor.putString(KEY_SESSTAP_PRODID, arrValues[11]);
			editor.putString(KEY_SESSPROD_URL, arrValues[12]);
			editor.putString(KEY_SESSPROD_BGCOLOR, arrValues[13]);
			editor.putString(KEY_SESSPROD_HideImage, arrValues[14]);
			editor.putString(KEY_SESSPROD_BuyButtonName, arrValues[15]);
			editor.putString(KEY_SESSPROD_BuyButtonUrl, arrValues[16]);
			editor.putInt(KEY_SESSPROD_ISTRYON, Integer.parseInt(arrValues[17]));
			editor.putString(KEY_SESSCLIENT_BGLIGHTCOLOR, arrValues[18]);
			editor.putString(KEY_SESSCLIENT_BGDARKCOLOR, arrValues[19]);
		}
		/*// Storing login value as TRUE
		editor.putBoolean(IS_LOGIN, true);

		// Storing id in pref
		editor.putString(KEY_SESSUSERID, id);
		
		// Storing name in pref
		editor.putString(KEY_NAME, name);
		
		// Storing email in pref
		editor.putString(KEY_EMAIL, email);*/
		
		// commit changes
		editor.commit();
	}		
	/**
	 * Get stored session data
	 * */
	public HashMap<String, String> getClientProductValuesFromSession(){
		HashMap<String, String> sessionVals = new HashMap<String, String>();
		
		sessionVals.put(KEY_SESSCLIENT_ID, pref.getString(KEY_SESSCLIENT_ID, null));
		sessionVals.put(KEY_SESSCLIENT_LOGO, pref.getString(KEY_SESSCLIENT_LOGO, null));
		sessionVals.put(KEY_SESSCLIENT_NAME, pref.getString(KEY_SESSCLIENT_NAME, null));
		sessionVals.put(KEY_SESSCLIENT_BGIMAGE, pref.getString(KEY_SESSCLIENT_BGIMAGE, null));
		sessionVals.put(KEY_SESSCLIENT_BGCOLOR, pref.getString(KEY_SESSCLIENT_BGCOLOR, null));
		sessionVals.put(KEY_SESSCLIENT_URL, pref.getString(KEY_SESSCLIENT_URL, null));
		sessionVals.put(KEY_SESSPROD_ID, pref.getString(KEY_SESSPROD_ID, null));
		sessionVals.put(KEY_SESSPROD_NAME, pref.getString(KEY_SESSPROD_NAME, null));
		sessionVals.put(KEY_SESSPROD_PRICE, pref.getString(KEY_SESSPROD_PRICE, null));
		sessionVals.put(KEY_SESSPROD_SHORTDESC, pref.getString(KEY_SESSPROD_SHORTDESC, null));
		sessionVals.put(KEY_SESSTAP_ID, pref.getString(KEY_SESSTAP_ID, null));
		sessionVals.put(KEY_SESSTAP_PRODID, pref.getString(KEY_SESSTAP_PRODID, null));
		sessionVals.put(KEY_SESSPROD_URL, pref.getString(KEY_SESSPROD_URL, null));
		sessionVals.put(KEY_SESSPROD_BGCOLOR, pref.getString(KEY_SESSPROD_BGCOLOR, null));
		sessionVals.put(KEY_SESSPROD_HideImage, pref.getString(KEY_SESSPROD_HideImage, null));
		sessionVals.put(KEY_SESSPROD_BuyButtonName, pref.getString(KEY_SESSPROD_BuyButtonName, null));
		sessionVals.put(KEY_SESSPROD_BuyButtonUrl, pref.getString(KEY_SESSPROD_BuyButtonUrl, null));
		sessionVals.put(KEY_SESSPROD_ISTRYON, ""+pref.getInt(KEY_SESSPROD_ISTRYON, 0));
		sessionVals.put(KEY_SESSCLIENT_BGLIGHTCOLOR, ""+pref.getString(KEY_SESSCLIENT_BGLIGHTCOLOR, null));
		sessionVals.put(KEY_SESSCLIENT_BGDARKCOLOR, ""+pref.getString(KEY_SESSCLIENT_BGDARKCOLOR, null));
		return sessionVals;
	}
	
	/**
	 * Check login method wil check user login status
	 * If false it will redirect user to login page
	 * Else won't do anything
	 * */
	public void checkLogin(){
		// Check login status
		if(!this.isLoggedIn()){
			// user is not logged in redirect him to Login Activity
			Intent i = new Intent(_context, ProductList.class);
			// Closing all the Activities
			i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
			
			// Add new Flag to start new Activity
			i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			
			// Staring Login Activity
			_context.startActivity(i);
		}
		
	}
	
	
	
	/**
	 * Get stored session data
	 * */
	public HashMap<String, String> getUserDetails(){
		HashMap<String, String> user = new HashMap<String, String>();
		// user id
		user.put(KEY_SESSUSERID, pref.getString(KEY_SESSUSERID, null));
		
		// user name
		user.put(KEY_NAME, pref.getString(KEY_NAME, null));
		
		// user email id
		user.put(KEY_EMAIL, pref.getString(KEY_EMAIL, null));
		user.put(KEY_FNAME, pref.getString(KEY_FNAME, null));
		user.put(KEY_LNAME, pref.getString(KEY_LNAME, null));
		user.put(KEY_SESSUSERGROUPID, pref.getString(KEY_SESSUSERGROUPID, null));
		user.put(KEY_PASSWORD, pref.getString(KEY_PASSWORD, null));
		
		// return user
		return user;
	}
	
	/**
	 * Clear session details
	 * */
	public void logoutUser(){
		// Clearing all data from Shared Preferences
		editor.remove(IS_LOGIN);

		editor.remove(KEY_SESSUSERID);
		editor.remove(KEY_NAME);
		editor.remove(KEY_EMAIL);
		editor.remove(KEY_SESSUSERGROUPID);
		editor.remove(KEY_PASSWORD);
		editor.commit();
		
		// After logout redirect user to Loing Activity
		Intent i = new Intent(_context, ARDisplayActivity.class);
		// Closing all the Activities
		i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
		
		// Add new Flag to start new Activity
		i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		
		// Staring Login Activity
		_context.startActivity(i);
	}
	
	/**
	 * Quick check for login
	 * **/
	// Get Login State
	public boolean isLoggedIn(){
		return pref.getBoolean(IS_LOGIN, false);
	}
	
/*	public void createOwnItProductValuesInSession(String proID){
		editor.putBoolean("own_it", false);
		if(proID != ""){
			editor.putBoolean("own_it", true);	
			if(KEY_SESSPROD_OwnIt !=""){
				proID = KEY_SESSPROD_OwnIt+","+proID;	
			}
			editor.putString(KEY_SESSPROD_OwnIt, proID);
			
		}
		editor.commit();
	}		
	*//**
	 * Get stored session data
	 * *//*
	public HashMap<String, String> getOwnItProductValuesFromSession(){
		HashMap<String, String> sessionVals = new HashMap<String, String>();
		
		sessionVals.put(KEY_SESSPROD_OwnIt, pref.getString(KEY_SESSPROD_OwnIt, null));
		
		return sessionVals;
	}*/
	
	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	public void createClosetInSession(String userId, String productId){
        editor.putBoolean("closet", false);
        if(productId != "null" && productId != null){
                editor.putBoolean("closet", true);                                
                closetSessionVals.add(userId+","+productId);
                //Log.i("sessionmanager",""+closetSessionVals);
                editor.putStringSet(KEY_SESSPROD_CLOSET,  closetSessionVals);                        
        }
        editor.commit();
	}		
	/**
	 * Get stored session data
	 * */
	public HashSet<String> getClosetFromSession(){
		//return   (HashSet<String>) pref.getStringSet(KEY_SESSPROD_CLOSET, new HashSet<String>());
		
		HashSet<String> closetSet = (HashSet<String>) pref.getStringSet(KEY_SESSPROD_CLOSET, new HashSet<String>());		
		return closetSet;
	}
	
	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	public void createOwnItInSession(String userId, String productId){
        editor.putBoolean("own_it", false);
        if(productId != "null" && productId != null){
                editor.putBoolean("own_it", true);   
				ownitSessionVals.add(userId+","+productId);
                editor.putStringSet(KEY_SESSPROD_OwnIt,  ownitSessionVals);
        }
        editor.commit();
	}		
	/**
	 * Get stored session data
	 * */
	public HashSet<String> getOwnItFromSession(){
		//Log.i("KEY_SESSPROD_OwnIt",""+ (HashSet<String>) pref.getStringSet(KEY_SESSPROD_OwnIt, new HashSet<String>()));
		//return   (HashSet<String>) pref.getStringSet(KEY_SESSPROD_OwnIt, new HashSet<String>());
		HashSet<String> ownItSet = (HashSet<String>) pref.getStringSet(KEY_SESSPROD_OwnIt, new HashSet<String>());
		//return   (HashSet<String>) pref.getStringSet(KEY_SESSPROD_WantIt, new HashSet<String>());
		return  ownItSet;
	
	}
	
	
	
	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	public void createWantItInSession(String userId, String productId){
        editor.putBoolean("want_it", false);
        if(productId != "null" && productId != null){
                editor.putBoolean("want_it", true);                                
               
				wantitSessionVals.add(userId+","+productId);
                editor.putStringSet(KEY_SESSPROD_WantIt,  wantitSessionVals);                        
        }
        editor.commit();
	}		
	/**
	 * Get stored session data
	 * */
	public HashSet<String> getWantItFromSession(){
		HashSet<String> wantItSet = (HashSet<String>) pref.getStringSet(KEY_SESSPROD_WantIt, new HashSet<String>());
		//return   (HashSet<String>) pref.getStringSet(KEY_SESSPROD_WantIt, new HashSet<String>());
		return  wantItSet;
	}

	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	public void createUserClientsInSession(String clientVal){
        editor.putBoolean("user_client", false);
        if(clientVal != "null" && clientVal != null){
                editor.putBoolean("user_client", true);                                
               
                userClientsSessionVals.add(clientVal);
                editor.putStringSet(KEY_SESSPROD_UserClients,  userClientsSessionVals);                        
        }
        editor.commit();
	}		
	/**
	 * Get stored session data
	 * */
	public HashSet<String> getUserClientsFromSession(){
		HashSet<String> userSet = (HashSet<String>) pref.getStringSet(KEY_SESSPROD_UserClients, new HashSet<String>());
		//return   (HashSet<String>) pref.getStringSet(KEY_SESSPROD_WantIt, new HashSet<String>());
		return  userSet;
	}

/*	public void createUserClientsArrayInSession(String[] arrayIndex) {
		// TODO Auto-generated method stub
		editor.putBoolean("user_client", false);
        if(arrayIndex.length>0){
        editor.putInt("user_client", arrayIndex.length);
        for(int i=0;i<arrayIndex.length; i++)
        	editor.putString("array_" + i, arrayIndex[i]);
        }
        editor.commit();
	}
	public String[] getUserClientsArrayFromSession(){
		int size = pref.getInt("user_client", 0);
		String[] array = new String[size];
		for(int i=0; i<size; i++){
			array[i] = pref.getString("array_" + i, null);
			Log.i("array"+i,""+array[i].toString());
		}
		
		return array;
	}
	*/
	public void createUserClientsArrayInSession(String arrayIndex) {
		// TODO Auto-generated method stub
		editor.putBoolean("user_client", false);
		if(arrayIndex != "null" && arrayIndex != null){
			JSONArray jArray = new JSONArray();
			jArray.put(arrayIndex);
			editor.putString("jArray", jArray.toString());
		}
		editor.commit();
	}
	JSONArray jArray = null;
	public JSONArray getUserClientsArrayFromSession(){
		
		try {
		     jArray = new JSONArray(pref.getString("jArray", null));
		} catch (JSONException e) {
		    e.printStackTrace();
		}
		
		return jArray;
	}
	  
	public  void setStringArrayPref(Context context, String key, ArrayList<String> values) {
	  
	    for (int i = 0; i < values.size(); i++) {
	        a.put(values.get(i));
	    }
	    if (!values.isEmpty()) {
	        editor.putString(key, a.toString());
	    } else {
	        editor.putString(key, null);
	    }
	    editor.commit();
	}

	public  ArrayList<String> getStringArrayPref(Context context, String key) {
	    String json = pref.getString(key, null);
	    ArrayList<String> urls = new ArrayList<String>();
	    if (json != null) {
	        try {
	            JSONArray a = new JSONArray(json);
	            for (int i = 0; i < a.length(); i++) {
	                String url = a.optString(i);
	                urls.add(url);
	            }
	        } catch (JSONException e) {
	            e.printStackTrace();
	        }
	    }
	    return urls;
	}

	public static final String KEY_SCANNED_VALUES = "scanned_values";	

	public static Set<String> storedScannedVals3;	
	public void createSessionForRecentlyScanned3(
			ArrayList<String> finalArrForAllMarkerInfo) {
		// TODO Auto-generated method stub
		storedScannedVals3 = new HashSet<String>(finalArrForAllMarkerInfo);
		editor.putStringSet(KEY_SCANNED_VALUES, storedScannedVals3);
		// commit changes
		editor.commit();
	}
	
	public HashSet<String> getStoredSessionForRecentlyScanned(){
		HashSet<String> recentlyScanned = (HashSet<String>) pref.getStringSet(KEY_SCANNED_VALUES, new HashSet<String>());
		return recentlyScanned;
	}
}
