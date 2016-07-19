package com.seemoreinteractive.seemoreinteractive;

import org.apache.http.util.EncodingUtils;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class OfferWebUrlActivity extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;
	String className = this.getClass().getSimpleName();
	public Boolean alertErrorType = true;
	String getProductId, getProductName, getProductPrice, getClientLogo, getClientId, getClientBackgroundImage, getClientBackgroundColor;
	String strFirstName="",strLastName="",strDOB="",strGender="",strEmail="";
	SessionManager session;
	AQuery aq;
	String postData = "", offerPurchaseUrl;
	 
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_options_terms_conditions);
		try{			
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);
			Intent getExtra = getIntent();
			offerPurchaseUrl = getExtra.getStringExtra("offerPurchseUrl");
			
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, getExtra.getStringExtra("offerTitle"), "");
			
			session = new SessionManager(context);
	        if(session.isLoggedIn()){
				    	                  		    	
		       /* aq = new AQuery(OfferWebUrlActivity.this);		
		        aq.ajax(Constants.userURL+"/"+Common.sessionIdForUserLoggedIn, XmlDom.class, new AjaxCallback<XmlDom>(){
		        	@Override
					public void callback(String url, XmlDom xml, AjaxStatus status) {
		        		try{
		        			if(xml!=null){
		        				List<XmlDom> xmlMsg = xml.tags("resultXml");
		        				Log.i("xmlMsg",""+xmlMsg);
		        				
		        				for(XmlDom xmlMsg1 : xmlMsg){		        					
		        				
		        					strFirstName = xmlMsg1.text("user_firstname").toString();
		        					strLastName = xmlMsg1.text("user_lastname").toString();
		        					strGender = xmlMsg1.text("user_details_gender").toString();
		        					strEmail = xmlMsg1.text("user_email_id").toString();
		        					
		        					if(!xmlMsg1.text("user_details_dob").toString().equals("0000-00-00 00:00:00")){
		        						String date = xmlMsg1.text("user_details_dob").toString();
		        						   SimpleDateFormat sdf = new SimpleDateFormat("yyyy-mm-dd");
		        					        Date testDate = null;
		        					        try {
		        					            testDate =  sdf.parse(date);
		        					        }catch(Exception ex){
		        					            ex.printStackTrace();
		        					        }
		        					        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-mm-dd");
		        					        String newFormat = formatter.format(testDate);
		        					        strDOB = newFormat;
		        						}		        						
		        						
		        					}
		        				
		        				postData = "first_name="+strFirstName+"&last_name="+strLastName+"&date_of_birth="+strDOB+"&email="+strEmail+"&client_name="+Common.sessionClientName;;
		        				WebView webview = (WebView)findViewById(R.id.wbvTermsCond);
		        				webview.getSettings().setJavaScriptEnabled(true);
		        				//webview.loadUrl(offerPurchaseUrl);
		        				 webview.postUrl(offerPurchaseUrl, EncodingUtils.getBytes(postData, "base64"));
		        				
		        				}
		        					}catch(Exception e){
		        						e.printStackTrace();
		        					}
		        	}
		        			});*/
	        	
	        	postData = "?uid="+Common.sessionIdForUserLoggedIn;
	        	Log.e("offerPurchaseUrl+postData",offerPurchaseUrl+postData);
				WebView webview = (WebView)findViewById(R.id.wbvTermsCond);
				webview.getSettings().setJavaScriptEnabled(true);
				//webview.loadUrl(offerPurchaseUrl);
				// webview.postUrl(offerPurchaseUrl, EncodingUtils.getBytes(postData, "base64"));
				webview.loadUrl(offerPurchaseUrl+postData);
		         
	        }
	        else{
	        	WebView webview = (WebView)findViewById(R.id.wbvTermsCond);
				webview.getSettings().setJavaScriptEnabled(true);
				//webview.loadUrl(offerPurchaseUrl);
				 webview.postUrl(offerPurchaseUrl, EncodingUtils.getBytes(postData, "base64"));
				
	        }
			
			String screenName = "/web/offers/?="+offerPurchaseUrl;
			String productIds = "";
			String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
			
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
					finish();
		    		overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);	
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBackButton |   " +e.getMessage();
				       	Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
					}
				}
			});
					
			//new Common().clickingOnBackButtonWithAnimation(OfferWebUrlActivity.this, ProductList.class,"0");			
	    	findViewById(R.id.closetProgressBar).setVisibility(View.INVISIBLE);

		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: Offer WebUrl  onCreate.", Toast.LENGTH_LONG).show();
			ex.printStackTrace();
			 String errorMsg = className+" | onCreate |   " +ex.getMessage();
	       	 Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
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
			Toast.makeText(getApplicationContext(), "Error: Offer WebUrl  onKeyDown.", Toast.LENGTH_LONG).show();
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
			Toast.makeText(getApplicationContext(), "Error: Offer WebUrl onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed |   " +ex.getMessage();
	       	Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
		}
        return;
    }
	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    /* Tracker easyTracker = EasyTracker.getInstance(this);
	  		easyTracker.set(Fields.SCREEN_NAME, " /terms&conditions/?="+getString(R.string.url_terms_condition));
	  		easyTracker.send(MapBuilder
	  			    .createAppView()
	  			    .build()
	  			);*/
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart |   " +e.getMessage();
		     Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		 super.onStop();
		//The rest of your onStop() code.
		//EasyTracker.getInstance(this).activityStop(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop |   " +e.getMessage();
		     Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
			 
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(OfferWebUrlActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(OfferWebUrlActivity.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(OfferWebUrlActivity.this,errorMsg);
			}
		}
	 
}
