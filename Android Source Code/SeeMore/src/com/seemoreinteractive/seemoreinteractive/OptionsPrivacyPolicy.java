package com.seemoreinteractive.seemoreinteractive;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.os.Build;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class OptionsPrivacyPolicy extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;
	String className =this.getClass().getSimpleName();
	public Boolean alertErrorType = true;
	String getProductId, getProductName, getProductPrice, getClientLogo, getClientId, getClientBackgroundImage, getClientBackgroundColor;

	SessionManager session;
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_options_privacy_policy);
		try{
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Privacy Policy", "");
				
			//findViewById(R.id.imgvBtnCart).setVisibility(View.INVISIBLE);
			//new Common().showDrawableImageFromAquery(this, R.id.closetProgressBar, R.id.imgvBtnCart);
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);
			
			
			WebView webview = (WebView)findViewById(R.id.wbvPrivacy);
			webview.getSettings().setJavaScriptEnabled(true);
			webview.loadUrl(getString(R.string.url_privacy_policy));
			
			new Common().clickingOnBackButtonWithAnimation(OptionsPrivacyPolicy.this, ProductList.class,"0");
	    	findViewById(R.id.closetProgressBar).setVisibility(View.INVISIBLE);
			
	    	/*ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);        
	    	imgBtnCart.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						Intent prodInfo = new Intent(getApplicationContext(), ProductList.class);    
						setResult(RESULT_OK, prodInfo);
					    finish();
					    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Privacy Policy imgBtnCart onClick.", Toast.LENGTH_LONG).show();
					}
				}
			});*/
	    	
			String screenName = "/web/privacypolicy/?url="+getString(R.string.url_privacy_policy);
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: Privacy Policy onCreate.", Toast.LENGTH_LONG).show();
			ex.printStackTrace();
			String errorMsg = className+" | onCreate |   " +ex.getMessage();
		    Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
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
			Toast.makeText(getApplicationContext(), "Error: HowTo onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown |   " +ex.getMessage();
		    Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
			return false;
		}
    }
    
    @Override
	public void onBackPressed() {
    	try{
			//new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, ProductList.class, "0");
    		finish();
    		overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
    	} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: HowTo onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed |   " +ex.getMessage();
		    Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
		}
        return;
    }
	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	     Tracker easyTracker = EasyTracker.getInstance(this);
		easyTracker.set(Fields.SCREEN_NAME, " /privacypolicy/?="+getString(R.string.url_privacy_policy));
		easyTracker.send(MapBuilder
			    .createAppView()
			    .build()
			);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart |   " +e.getMessage();
			 Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
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
			 Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(OptionsPrivacyPolicy.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(OptionsPrivacyPolicy.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(OptionsPrivacyPolicy.this,errorMsg);
			}
		}

}
