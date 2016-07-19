package com.seemoreinteractive.seemoreinteractive;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class PurchaseProductWithClientUrl extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;
	String className = this.getClass().getSimpleName();
	public Boolean alertErrorType = true;
	String getProductId, getProductName, getProductPrice, getClientLogo, getClientId, getClientBackgroundImage, getClientBackgroundColor;
	String finalWebSiteUrl;

	SessionManager session;
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_purchase_product_with_client_url);		
		try{	
			ProgressBar prgBar = (ProgressBar) findViewById(R.id.closetProgressBar);
			prgBar.setVisibility(View.VISIBLE);
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);
				
			Intent getIntVals = getIntent();			
			//new Common().pageHeaderTitle(PurchaseProductWithClientUrl.this, getIntVals.getStringExtra("productName"));	
	    	//new Common().clientLogoOrTitleWithThemePassingColor(this, Common.sessionClientBgColor, Common.sessionClientLogo);		

			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, getIntVals.getStringExtra("productName"), "");	
			
			if(getIntVals.getExtras()!=null){
				finalWebSiteUrl = getIntVals.getStringExtra("finalWebSiteUrl");
				//Log.i("finalWebSiteUrl", ""+finalWebSiteUrl);
				//TextView txtProdNameOnHead = (TextView) findViewById(R.id.txtvHeadTitle);
				//txtProdNameOnHead.setText(getIntVals.getStringExtra("productName"));
				if(!finalWebSiteUrl.equals("null")){
					WebView webview = (WebView)findViewById(R.id.wbvProductPage);
					webview.getSettings().setCacheMode(WebSettings.LOAD_NO_CACHE);
					webview.getSettings().setJavaScriptEnabled(true);
					webview.setVerticalScrollBarEnabled(true);
					webview.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);
					webview.loadUrl(finalWebSiteUrl);
					prgBar.setVisibility(View.INVISIBLE);
				} else {
					prgBar.setVisibility(View.INVISIBLE);				
				}
			}
			
			//new Common().clickingOnBackButtonWithAnimation(PurchaseProductWithClientUrl.this, ProductList.class,"0");
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
					finish();
					overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBackButton click    |   " +e.getMessage();
						Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
					}
					
				}
			});
			
			String screenName = "/web/"+getIntVals.getStringExtra("productName")+"/?url="+finalWebSiteUrl;
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);

			
		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: Purchase Product page onCreate.", Toast.LENGTH_LONG).show();
			ex.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +ex.getMessage();
			Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
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
			Toast.makeText(getApplicationContext(), "Error: Purchase Product page onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown    |   " +ex.getMessage();
			Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
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
			Toast.makeText(getApplicationContext(), "Error: Purchase Product page onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed     |   " +ex.getMessage();
			Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
		}
        return;
    }
	 @Override
	public void onStart() {
		try{
		    super.onStart();
		    Tracker easyTracker = EasyTracker.getInstance(this);
			easyTracker.set(Fields.SCREEN_NAME, " /cart/?="+finalWebSiteUrl);
			easyTracker.send(MapBuilder
			    .createAppView()
			    .build()
			);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart     |   " +e.getMessage();
			Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
			 super.onStop();
			 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop     |   " +e.getMessage();
			 Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
		 }
	}
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(PurchaseProductWithClientUrl.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(PurchaseProductWithClientUrl.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(PurchaseProductWithClientUrl.this,errorMsg);
			}
		}
}
