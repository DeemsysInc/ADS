package com.seemoreinteractive.seemoreinteractive;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class OptionsShowWebsiteUrls extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;
	String className =this.getClass().getSimpleName();
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_show_website_urls);

		try{
			//findViewById(R.id.imgvBtnCart).setVisibility(View.INVISIBLE);
			findViewById(R.id.closetProgressBar).setVisibility(View.VISIBLE);
			//new Common().showDrawableImageFromAquery(this, R.id.progressBar1, R.id.imgvBtnCart);
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);
			

			Intent getIntVals = getIntent();	   
			//new Common().pageHeaderTitle(OptionsShowWebsiteUrls.this, getIntVals.getStringExtra("headTitle"));
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, getIntVals.getStringExtra("headTitle"), "");			
			
			WebView webview = (WebView)findViewById(R.id.webView1);
			webview.getSettings().setJavaScriptEnabled(true);
    		if(getIntVals.getExtras()!=null){
    			//txtHeadTitle.setText(getIntVals.getStringExtra("headTitle"));
    			webview.loadUrl(getIntVals.getStringExtra("redirectToWebsiteUrl"));	
    		}
			
			new Common().clickingOnBackButtonWithAnimation(OptionsShowWebsiteUrls.this, OptionsAbout.class,"0");
			
	    	findViewById(R.id.closetProgressBar).setVisibility(View.INVISIBLE);
			
			String screenName = "/web/"+getIntVals.getStringExtra("headTitle")+"/?url="+getIntVals.getStringExtra("redirectToWebsiteUrl");
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);

		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: Show website urls onCreate.", Toast.LENGTH_LONG).show();
			ex.printStackTrace();
			String errorMsg = className+" | onStart |   " +ex.getMessage();
			Common.sendCrashWithAQuery(OptionsShowWebsiteUrls.this,errorMsg);
		}
	}
	@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
		try{
	        if (keyCode == KeyEvent.KEYCODE_BACK) {
	            //Log.i("Press Back", "BACK PRESSED EVENT");
	            onBackPressed();
	            isBackPressed = true;
	        }
	        // Call super code so we dont limit default interaction
	        return super.onKeyDown(keyCode, event);
		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: Show website urls onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown |   " +ex.getMessage();
			Common.sendCrashWithAQuery(OptionsShowWebsiteUrls.this,errorMsg);
			return false;
		}
    }
    
    @Override
	public void onBackPressed() {
    	try{
			new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, OptionsAbout.class, "0");
    	} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: Show website urls onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed |   " +ex.getMessage();
			Common.sendCrashWithAQuery(OptionsShowWebsiteUrls.this,errorMsg);
		}
        return;
    }
	 @Override
	public void onStart() {
		try{
	    super.onStart();
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart |   " +e.getMessage();
			Common.sendCrashWithAQuery(OptionsShowWebsiteUrls.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop |   " +e.getMessage();
			Common.sendCrashWithAQuery(OptionsShowWebsiteUrls.this,errorMsg);
		 }
	}
}
