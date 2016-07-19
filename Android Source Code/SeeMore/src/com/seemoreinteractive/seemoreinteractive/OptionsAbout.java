package com.seemoreinteractive.seemoreinteractive;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class OptionsAbout extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;
	String className = this.getClass().getSimpleName(); 
	@SuppressLint("NewApi")
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_options_about);
		try{
			//new Common().showDrawableImageFromAquery(OptionsAbout.this, R.id.progressBar1, R.id.imgvBtnCart);
			
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);
			
			findViewById(R.id.closetProgressBar).setVisibility(View.VISIBLE);
			//new Common().showProgressLoaderFromAQuery(OptionsAbout.this, R.id.progressBar1); 
			PackageManager manager = this.getPackageManager();
			PackageInfo info = manager.getPackageInfo(this.getPackageName(), 0);
			TextView txtVersion = (TextView) findViewById(R.id.txtvVersion);
			txtVersion.setText("Version "+info.versionName);
			txtVersion.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);

			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "About", "");
			//new Common().pageHeaderTitle(OptionsAbout.this, "About");
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					 Intent returnIntent = new Intent(OptionsAbout.this, ARDisplayActivity.class);
					 returnIntent.putExtra("instruction_type", "0");
					// Closing all the Activities
					 //returnIntent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);			
					// Add new Flag to start new Activity
					 //returnIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
					 setResult(1, returnIntent);
					 finish();
					 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				}
			});

			TextView btnSeemoreSite = (TextView) findViewById(R.id.btnCheckoutConfirm);
			btnSeemoreSite.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnSeemoreSite.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					if(Common.isNetworkAvailable(OptionsAbout.this)){
						
					Intent intent = new Intent(OptionsAbout.this, OptionsShowWebsiteUrls.class);
					intent.putExtra("headTitle", "SeeMore Interactive");
					intent.putExtra("redirectToWebsiteUrl", "http://www.seemoreinteractive.com/");
					startActivity(intent);
					finish();
					overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
					}else{
						new Common().instructionBox(OptionsAbout.this,R.string.title_case7,R.string.instruction_case7);
					}
				}
			});
			
			TextView txtAbtDesc2 = (TextView) findViewById(R.id.TextView01);
			RelativeLayout.LayoutParams rlpForAbtDesc2 = (RelativeLayout.LayoutParams) txtAbtDesc2.getLayoutParams();
			rlpForAbtDesc2.topMargin = (int) (0.1 * Common.sessionDeviceHeight);
			txtAbtDesc2.setLayoutParams(rlpForAbtDesc2);
			txtAbtDesc2.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			
			TextView txtPoweredBy = (TextView) findViewById(R.id.txtvPoweredBy);
			RelativeLayout.LayoutParams rlpForPoweredBy = (RelativeLayout.LayoutParams) txtPoweredBy.getLayoutParams();
			rlpForPoweredBy.topMargin = (int) (0.37 * Common.sessionDeviceHeight);
			txtPoweredBy.setLayoutParams(rlpForPoweredBy);
			txtPoweredBy.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			
			TextView btnMetaioSDK = (TextView) findViewById(R.id.Button01);
			btnMetaioSDK.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnMetaioSDK.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					if(Common.isNetworkAvailable(OptionsAbout.this)){						
					Intent intent = new Intent(OptionsAbout.this, OptionsShowWebsiteUrls.class);
					intent.putExtra("headTitle", "Metaio Mobile SDK");
					intent.putExtra("redirectToWebsiteUrl", "http://www.metaio.com/imprint/");
					startActivity(intent);
					finish();
					overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);				
					}else{
						new Common().instructionBox(OptionsAbout.this,R.string.title_case7,R.string.instruction_case7);
					}
				}
			});


	    	findViewById(R.id.closetProgressBar).setVisibility(View.INVISIBLE);

			String screenName = "/about";
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
			
			

		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: About onCreate.", Toast.LENGTH_LONG).show();
			ex.printStackTrace();
			String errorMsg = className+" | onCreate |   " +ex.getMessage();
	       	Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
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
			Toast.makeText(getApplicationContext(), "Error: About onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown |   " +ex.getMessage();
	       	Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
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
			Toast.makeText(getApplicationContext(), "Error: About onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed |   " +ex.getMessage();
	       	Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
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
		     Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
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
		     Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(OptionsAbout.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(OptionsAbout.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(OptionsAbout.this,errorMsg);
			}
		}
}
