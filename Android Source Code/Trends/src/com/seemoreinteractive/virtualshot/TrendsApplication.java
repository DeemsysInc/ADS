package com.seemoreinteractive.virtualshot;


import java.io.IOException;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.StrictMode;
import android.util.Log;
import android.view.View;
import android.widget.Toast;

import com.metaio.sdk.MetaioDebug;
import com.metaio.tools.io.AssetsManager;


@SuppressLint({ "SetJavaScriptEnabled", "NewApi" })
public class TrendsApplication extends Activity
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
	
	@TargetApi(Build.VERSION_CODES.GINGERBREAD)
	@SuppressLint("NewApi")
	@Override
	protected void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);		
		setContentView(R.layout.splash);
		
		// Enable metaio SDK log messages based on build configuration
		MetaioDebug.enableLogging(BuildConfig.DEBUG);
		 
		
		// extract all the assets
		mTask = new AssetsExtracter();
		mTask.execute(0);
		
		if (android.os.Build.VERSION.SDK_INT > 9) {
			StrictMode.ThreadPolicy policy = 
			        new StrictMode.ThreadPolicy.Builder().permitAll().build();
			StrictMode.setThreadPolicy(policy);
		}
		
		if(!isNetworkAvailable()){			
				Toast.makeText(getApplicationContext(), "You don't have Internet Connection. Please try agian!", Toast.LENGTH_LONG).show();
	        	finish();
			}
		final Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
		Intent intent  = new Intent(TrendsApplication.this, MainActivity.class);
		startActivity(intent);		
		finish();
            }
        }, 1000);
		
		
	}
	@Override
	protected void onResume() 
	{
		super.onResume();		
		mLaunchingTutorial = false;
	}
	
	@Override
	protected void onPause() 
	{
		super.onPause();
	}

	@Override
	public void onBackPressed() 
	{
		// if web view can go back, go back
		
			finish();
			super.onBackPressed();
			
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
				return false;
			}

			return true;
		}
		
		
		
	}
	
	public boolean isNetworkAvailable() {
		 boolean connected = false;
			ConnectivityManager connectivityManager = (ConnectivityManager)getSystemService(Context.CONNECTIVITY_SERVICE);
			    if(connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).getState() == NetworkInfo.State.CONNECTED || 
			            connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).getState() == NetworkInfo.State.CONNECTED) {
			        //we are connected to a network
			        connected = true;
			    }
			    else
			    	connected = false;
				return connected;
			   
	}
	
	
	
}

