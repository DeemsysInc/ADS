// Copyright 2007-2013 metaio GmbH. All rights reserved.
package com.seemoreinteractive.seemoreinteractive;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Set;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.PowerManager;
import android.os.PowerManager.WakeLock;
import android.os.StrictMode;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.google.analytics.tracking.android.EasyTracker;
import com.metaio.sdk.MetaioDebug;
import com.metaio.tools.io.AssetsManager;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.helper.FirstTimePreference;


@TargetApi(Build.VERSION_CODES.ECLAIR)
public class MainActivity extends Activity
{
	/**
	 * Task that will extract all the assets
	 */
	AssetsExtracter mTask;
	protected View mGUIView;
	boolean isMarkerLoadingComplete;
	SharedPreferences mPrefs;
	/**
	 * True while launching a tutorial, used to prevent
	 * multiple launches of the tutorial
	 */
	boolean mLaunchingTutorial;
	//View mProgress;
	private WakeLock mWakeLock;
	SessionManager session;

	DisplayMetrics displayMetrics;
	int deviceWidth = 0, deviceHeight = 0;
	int deviceDensity = 0;
	ArrayList<String> displayStringArray, AndroidVerStringArray;
	String[] stringArray1, strArrForAndroidVerName;
	public static boolean ResetFlag = false;
	String className ="MainActivity";
	AQuery aq;
	public static long mstartnow,mendnow;
	@TargetApi(Build.VERSION_CODES.GINGERBREAD)
	@Override
	protected void onCreate(Bundle savedInstanceState) 
	{
		try{
			super.onCreate(savedInstanceState);		
			setContentView(R.layout.activity_splash);
			
			mstartnow = android.os.SystemClock.uptimeMillis();
			
			// extract all the assets
			mTask = new AssetsExtracter();
			mTask.execute(0);
			
			//aq = new AQuery(MainActivity.this);
					
			PackageManager manager = this.getPackageManager();
			PackageInfo info = manager.getPackageInfo(this.getPackageName(), 0);
	        AndroidVerStringArray = new ArrayList<String>();
	        AndroidVerStringArray.add("_"+info.versionName);
	        strArrForAndroidVerName = AndroidVerStringArray.toArray(new String[AndroidVerStringArray.size()]);
			//Log.e("info", ""+info.versionCode+" "+info.versionName);
			final Intent getIntent = getIntent();
			//Log.i("main","before notify");
			//Log.i("main",""+getIntent.getExtras());
	        session = new SessionManager(getApplicationContext());
	        session.createForStoredAndroidVersionName(strArrForAndroidVerName);
			new Common().getCommonStoredAndroidVersionName(MainActivity.this);
			Log.i("android version name", ""+Common.sessionForAndroidVersionName);
			Log.i("userLogin1", ""+Common.sessionIdForUserLoggedIn);
			Log.i("userLogin2", ""+Common.sessionIdForUserGroupId);
			if(session.isLoggedIn()){
				new Common().getStoredUserSessionDetails(MainActivity.this, "ARDisplay", null);
				Log.i("userLogin3", ""+Common.sessionIdForUserLoggedIn);
				Log.i("userLogin4", ""+Common.sessionIdForUserGroupId);
				new Constants();
			} else {
				new Constants();
			}
			
			FirstTimePreference prefFirstTime = new FirstTimePreference(getApplicationContext());			
			if (prefFirstTime.runTheFirstTime("myKey")) {
				//Toast.makeText(this, "Test myKey & coutdown: "+ prefFirstTime.getCountDown("myKey"),Toast.LENGTH_LONG).show();
				new Common().deleteFiles(Constants.LOCATION);
				if(session.isLoggedIn()){
					File file = new File(Constants.LOCATION+"userOffers");
					if(file.exists()){
						boolean deleted = file.delete();
						Log.i("deleted",""+deleted);
					}
					File fileStore = new File(Constants.LOCATION+"clientstores");
					if(fileStore.exists()){
						fileStore.delete();
						Log.i("file storesdelete",""+fileStore.delete());
					}
					session.logoutUser();
				}
			}
			
			Log.i("mainActive", ""+Common.sessionDeviceWidth);
			//session = new SessionManager(getApplicationContext());
			displayMetrics = getResources().getDisplayMetrics();
			deviceDensity = (int)(displayMetrics.density);
	        deviceWidth = displayMetrics.widthPixels;
	        deviceHeight = displayMetrics.heightPixels;
	        Log.i("1deviceDensity", ""+deviceDensity);
	        Log.i("1deviceWidth", ""+deviceWidth);
	        Log.i("1deviceHeight", ""+deviceHeight);
			displayStringArray = new ArrayList<String>();
	        displayStringArray.add(""+deviceWidth);
	        displayStringArray.add(""+deviceHeight);
	        displayStringArray.add(""+deviceDensity);
			stringArray1 = displayStringArray.toArray(new String[displayStringArray.size()]);
			session.createDisplayMetrics(stringArray1);	
			new Common().getStoredDisplayMetricsSessionValues(this);
			Log.i("mainActive", ""+Common.sessionDeviceWidth+" "+Common.sessionClientBgColor);
			if(Common.sessionClientBgColor.equals("null")){
				Common.sessionClientBgColor = "ff2600";
			}
			if(Common.sessionClientBackgroundLightColor.equals("null")){
				Common.sessionClientBackgroundLightColor = "ff2600";
			}
			if(Common.sessionClientBackgroundDarkColor.equals("null")){
				Common.sessionClientBackgroundDarkColor = "ff2600";
			}	
			Constants.ARFlag =true;
			
			// Enable metaio SDK log messages based on build configuration
			MetaioDebug.enableLogging(BuildConfig.DEBUG);
			 
			//mProgress = findViewById(R.id.progress);
			
			if (android.os.Build.VERSION.SDK_INT > 9) {
				StrictMode.ThreadPolicy policy = 
				        new StrictMode.ThreadPolicy.Builder().permitAll().build();
				StrictMode.setThreadPolicy(policy);
			}
			final PowerManager pm = (PowerManager) getSystemService(Context.POWER_SERVICE);
	        this.mWakeLock = pm.newWakeLock(PowerManager.SCREEN_DIM_WAKE_LOCK, "My Tag");
	        this.mWakeLock.acquire();

			final ImageView im = (ImageView)findViewById(R.id.imageSplash);
			final Handler handler = new Handler();
	        handler.postDelayed(new Runnable() {
	            @Override
	            public void run() {
	                // Do something after 5s = 5000ms
	            	  File theDir = new File(Constants.Trigger_Location);	    			  
	    	            // if the directory does not exist, create it
	    	            if (!theDir.exists()) {
	    	              theDir.mkdir();	    	              
	    	            }
	            	//im.setScaleType(ImageView.ScaleType.FIT_XY);
	            	if(getIntent.getExtras()!=null){

	    				Log.i("all",getIntent.getExtras().toString());
	    				
	    				 Bundle bundle = getIntent.getExtras();
	    				    if (bundle != null) {
	    				        Set<String> keys = bundle.keySet();
	    				        Iterator<String> it = keys.iterator();
	    				        Log.i("LOG_TAG","Dumping Intent start");
	    				        while (it.hasNext()) {
	    				            String key = it.next();
	    				            Log.i("LOG_TAG","[" + key + "=" + bundle.get(key)+"]");
	    				        }
	    				        Log.i("LOG_TAG","Dumping Intent end");
	    				    }
	    				Log.i("notify",getIntent.getExtras().getString("notify"));
	    				Log.i("latitude",getIntent.getExtras().getString("lat"));
	    				Log.i("longitude",getIntent.getExtras().getString("long"));
	    				if(getIntent.getStringExtra("notify").equals("true")){
	    					Intent intent  = new Intent(MainActivity.this, FindAStore.class);
	    					intent.putExtra("latitude", getIntent.getStringExtra("lat"));
	    					intent.putExtra("longitude", getIntent.getStringExtra("long"));
	    					intent.putExtra("search_name", getIntent.getStringExtra("search_name"));
	    					intent.putExtra("search_type", getIntent.getStringExtra("search_type"));
	    					intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
	    		    		startActivity(intent);
	    		    		overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
	    		    		finish();
	    				}
	    			}else{
		            	Intent intent  = new Intent(MainActivity.this, ARDisplayActivity.class);
						intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
			    		startActivity(intent);		    		
			    		overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
			    		finish();
	    			}
		    		
	            	
	            }
	        }, 1500);

			/*
	        try {
	            PackageInfo info1 = getPackageManager().getPackageInfo(this.getPackageName(), PackageManager.GET_SIGNATURES);
	            for (Signature signature : info1.signatures) {
	                MessageDigest md = MessageDigest.getInstance("SHA");
	                md.update(signature.toByteArray());
	                Log.i("KeyHash:", Base64.encodeToString(md.digest(), Base64.DEFAULT));
	                Toast.makeText(getApplicationContext(), "keyhash "+Base64.encodeToString(md.digest(), Base64.DEFAULT), Toast.LENGTH_LONG).show();
	            }
	        } catch (NameNotFoundException e) {

	        } catch (NoSuchAlgorithmException e) {

	        }*/
				
		} catch (Exception e) {
            e.printStackTrace();
           // Toast.makeText(getApplicationContext(),"Error: MainActivity onCreate", Toast.LENGTH_LONG).show();            
        	String errorMsg = className+" | onCreate | "+e.getMessage();
        	Common.sendCrashWithAQuery(MainActivity.this,errorMsg);	        		
      }
		
	}
	
	@TargetApi(Build.VERSION_CODES.HONEYCOMB_MR1)
	@Override
	protected void onResume() 
	{
		try{
			super.onResume();		
			mLaunchingTutorial = false;
		}catch (Exception e) {
            e.printStackTrace();            
            String errorMsg = className+" | onResume | "+e.getMessage();
        	Common.sendCrashWithAQuery(MainActivity.this,errorMsg);	  
      }
	}
	
	@Override
	protected void onPause() 
	{
		try{
			Log.i("Mainactivty","onpause");
		super.onPause();
		}catch (Exception e) {
            e.printStackTrace();          
            String errorMsg = className+" | onPause | "+e.getMessage();
        	Common.sendCrashWithAQuery(MainActivity.this,errorMsg);	
      }
	}

	@Override
    public void onDestroy() {
	try{
        this.mWakeLock.release();
        super.onDestroy();       
	}catch(Exception e){
		e.printStackTrace(); 
		String errorMsg = className+" | onDestroy | "+e.getMessage();
    	Common.sendCrashWithAQuery(MainActivity.this,errorMsg);	
	}
  }

	@Override
	public void onBackPressed() 
	{
		try{			
			finish();
	        this.moveTaskToBack(true);
			super.onBackPressed();
		}catch (Exception e) {
            e.printStackTrace();           
            String errorMsg = className+" | onBackPressed | "+e.getMessage();
        	Common.sendCrashWithAQuery(MainActivity.this,errorMsg);
      }			
	}
	
	/**
	 * This task extracts all the assets to an external or internal location
	 * to make them accessible to metaio SDK
	 */
	private class AssetsExtracter extends AsyncTask<Integer, Integer, Boolean>
	{
		@Override
		protected Boolean doInBackground(Integer... params) 
		{
			try 
			{
				AssetsManager.extractAllAssets(getApplicationContext(), true);
			} 
			catch (IOException e) 
			{
				MetaioDebug.printStackTrace(Log.ERROR, e);
				Toast.makeText(getApplicationContext(),"Error: MainActivity AssetsExtracter", Toast.LENGTH_LONG).show();
				String errorMsg = className+" | AssetsExtracter | "+e.getMessage();
		        Common.sendCrashWithAQuery(MainActivity.this,errorMsg);
				return false;
			}

			return true;
		}
	}
	 @Override
	public void onStart() {
		 try{
			 super.onStart();
			 EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
		 }	    
	}
	 @Override
	public void onStop() {
		 try{
			 super.onStop();
			 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
		 }catch(Exception e){
			 e.printStackTrace();
		 }
	}
}

