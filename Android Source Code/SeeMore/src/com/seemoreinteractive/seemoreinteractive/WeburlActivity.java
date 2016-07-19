package com.seemoreinteractive.seemoreinteractive;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.metaio.sdk.MetaioDebug;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class WeburlActivity extends Activity {

	WebView mWebView;

	/**
	 * Task that will extract all the assets
	 */
	AssetsExtracter mTask;

	/**
	 * Progress view
	 */
	View mProgress;

	/**
	 * True while launching a tutorial, used to prevent multiple launches of the
	 * tutorial
	 */
	boolean mLaunchingTutorial;
	String url;
	String className ="WeburlActivity";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		setContentView(R.layout.activity_webview);

		try {
			Intent getIntVals = getIntent();
			url = getIntVals.getStringExtra("url");
			// Enable metaio SDK log messages based on build configuration

			mProgress = findViewById(R.id.progress);
			mWebView = (WebView) findViewById(R.id.webview);

			// extract all the assets
			mTask = new AssetsExtracter();
			mTask.execute(0);
			String getClientLogo = getIntVals.getStringExtra("clientLogo");
			String getClientId = getIntVals.getStringExtra("clientId");
			String getClientBackgroundImage = getIntVals
					.getStringExtra("clientBackgroundImage");
			String getClientBackgroundColor = getIntVals
					.getStringExtra("clientBackgroundColor");

			//new Common().clientBgImgWithColor(WeburlActivity.this);
			//new Common().pageHeaderTitle(WeburlActivity.this, "");
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "Yes");
			/*
			 * RelativeLayout bgRelativeLayout = (RelativeLayout)
			 * findViewById(R.id.bgRelativeLayout); //ImageView topImgHeadBg =
			 * (ImageView) findViewById(R.id.imgvHead); LinearLayout
			 * headerLinearLayout = (LinearLayout)
			 * findViewById(R.id.headerLinearLayout); LinearLayout
			 * footerLinearLayout = (LinearLayout)
			 * findViewById(R.id.footerLinearLayout);
			 * 
			 * if(getClientBackgroundImage.equals("null")){
			 * bgRelativeLayout.setBackgroundResource(R.drawable.bg_closet); }
			 * else { String selectedClientBackgroundPath =
			 * (Constants.Client_Logo_Location
			 * +getClientId+"/background/"+getClientBackgroundImage
			 * ).toString().replaceAll(" ", "%20"); new
			 * Common().DownloadImageFromUrlInBackgroundDrawable(this,
			 * selectedClientBackgroundPath, bgRelativeLayout); }
			 * headerLinearLayout
			 * .setBackgroundColor(Color.parseColor("#"+getClientBackgroundColor
			 * )); footerLinearLayout.setBackgroundColor(Color.parseColor("#"+
			 * getClientBackgroundColor));
			 */

			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try {
						onBackPressed();
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(),
								"Error: Weburl imgBackButton onClick.",
								Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgBackButton      |   " +ex.getMessage();
						Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
					}
				}
			});
		} catch (Exception e) {
			Toast.makeText(getApplicationContext(),
					"Error: WeburlActivity onCreate", Toast.LENGTH_LONG).show();
			 String errorMsg = className+" | onCreate      |   " +e.getMessage();
				Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}

	}

	@Override
	protected void onResume() {
		try{
		super.onResume();
		mWebView.resumeTimers();
		mLaunchingTutorial = false;
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onResume      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}
	}

	@Override
	protected void onPause() {
		try{
		super.onPause();
		mWebView.pauseTimers();
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onPause      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}
		
	}

	@Override
	public void onBackPressed() {
		try{
		// if web view can go back, go back
		if (mWebView.canGoBack())
			mWebView.goBack();
		else
			super.onBackPressed();
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onBackPressed      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}
	}

	/**
	 * This task extracts all the assets to an external or internal location to
	 * make them accessible to metaio SDK
	 */
	private class AssetsExtracter extends AsyncTask<Integer, Integer, Boolean> {

		@Override
		protected void onPreExecute() {
			mProgress.setVisibility(View.VISIBLE);
		}

		@Override
		protected Boolean doInBackground(Integer... params) {

			return true;
		}

		@Override
		protected void onPostExecute(Boolean result) {
			try{
			mProgress.setVisibility(View.GONE);

			if (result) {
				WebSettings settings = mWebView.getSettings();

				settings.setCacheMode(WebSettings.LOAD_NO_CACHE);
				settings.setJavaScriptEnabled(true);
				Log.i("info", url);
				mWebView.setVerticalScrollBarEnabled(true);
				mWebView.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);
				mWebView.setWebViewClient(new WebViewHandler());
				mWebView.loadUrl(url);
				mWebView.setVisibility(View.VISIBLE);
				
				
				String screenName = "/web/?url="+url;
				String productIds = "";
		    	String offerIds = "";
				Common.sendJsonWithAQuery(WeburlActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
			} else {
				MetaioDebug.log(Log.ERROR,
						"Error extracting assets, closing the application...");
				finish();
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onPostExecute      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}
		}
	}

	class WebViewHandler extends WebViewClient {
		@Override
		public void onPageStarted(WebView view, String url, Bitmap favicon) {
			mProgress.setVisibility(View.VISIBLE);
		}

		@Override
		public void onPageFinished(WebView view, String url) {
			mProgress.setVisibility(View.GONE);
		}

	}

	@Override
	public void onStart() {
		try{
		super.onStart();
		// The rest of your onStart() code.
		EasyTracker.getInstance(this).activityStart(this); // Add this method.
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onStart      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}
	}

	@Override
	public void onStop() {
		try{
		super.onStop();
		// The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this); // Add this method.
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onStop      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WeburlActivity.this,errorMsg);
		}
	}
}
