package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class AccountLaunchActivity extends Activity {

	
	String checkLoginFlag, stringArrayList2;
	ArrayList<String> offerStringArrayListValues;
	String className ="AccountLaunchActivity";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_sign_in);
		
		try{	
			Intent pintent = getIntent();
			if(pintent.getExtras()!=null){
				checkLoginFlag = pintent.getStringExtra("checkLoginFlag");
				stringArrayList2 = pintent.getStringExtra("stringArrayList2");
				offerStringArrayListValues = pintent.getStringArrayListExtra("offerStringArrayListValues");
			}
	
			ImageView imgvCloseLogin = (ImageView) findViewById(R.id.imgvCloseLogin);
			RelativeLayout.LayoutParams rlpCloseLogin = (RelativeLayout.LayoutParams) imgvCloseLogin.getLayoutParams();
			rlpCloseLogin.width = (int) (0.08 * Common.sessionDeviceWidth);
			rlpCloseLogin.height = (int) (0.0492 * Common.sessionDeviceHeight);
			imgvCloseLogin.setLayoutParams(rlpCloseLogin);
			imgvCloseLogin.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{
					   finish();
					}catch(Exception e){
						e.printStackTrace();
					}
				}
			});
	
		ImageView imgLogo = (ImageView) findViewById(R.id.imgSeemoreLogo);
		RelativeLayout.LayoutParams rlpLogo = (RelativeLayout.LayoutParams) imgLogo.getLayoutParams();
		rlpLogo.topMargin = (int) (0.0615 * Common.sessionDeviceHeight);
		imgLogo.setLayoutParams(rlpLogo);
		
		int txtSize = (int) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		int BtnTxtSize = (int) ((0.041 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		
		TextView txtJoin = (TextView) findViewById(R.id.txtJoin);
		txtJoin.setTextSize(txtSize);
		
		TextView txtEmailLbl = (TextView) findViewById(R.id.txtEmailLbl);
		txtEmailLbl.setTextSize(txtSize);	
		
		TextView txtLoginLbl = (TextView) findViewById(R.id.txtLoginLbl);
		txtLoginLbl.setTextSize(txtSize);	
		
		Button btnSignUP  = (Button) findViewById(R.id.btnSignUp);
		btnSignUP.setTextSize(BtnTxtSize);
		

		Button btnLogin  = (Button) findViewById(R.id.btnLogin);
		btnLogin.setTextSize(BtnTxtSize);
		
		
		ImageView imgLoginWithFb = (ImageView) findViewById(R.id.imgLoginWithFb);
		RelativeLayout.LayoutParams rlpLoginWithFb = (RelativeLayout.LayoutParams) imgLoginWithFb.getLayoutParams();
		rlpLoginWithFb.bottomMargin = (int) (0.0307 * Common.sessionDeviceHeight/2);
		rlpLoginWithFb.width = (int) (0.666 * Common.sessionDeviceWidth);
		rlpLoginWithFb.height = (int) (0.0922 * Common.sessionDeviceHeight);
		imgLoginWithFb.setLayoutParams(rlpLoginWithFb);
		
		RelativeLayout.LayoutParams rlpSignUp = (RelativeLayout.LayoutParams) btnSignUP.getLayoutParams();
		rlpSignUp.width = (int) (0.666 * Common.sessionDeviceWidth);
		rlpSignUp.height = (int) (0.0922 * Common.sessionDeviceHeight);
		btnSignUP.setLayoutParams(rlpSignUp);
		
		RelativeLayout.LayoutParams rlpLogin = (RelativeLayout.LayoutParams) btnLogin.getLayoutParams();
		rlpLogin.width = (int) (0.666 * Common.sessionDeviceWidth);
		rlpLogin.height = (int) (0.0922 * Common.sessionDeviceHeight);
		btnLogin.setLayoutParams(rlpLogin);
		
		imgLoginWithFb.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
					if(Common.isNetworkAvailable(AccountLaunchActivity.this)){
						SharedPreferences mPrefs = getSharedPreferences("fb_prefs", Context.MODE_PRIVATE);
						Editor editor = mPrefs.edit();
						editor.putString("fb_photo_description", "New SeeMore Image.");
						editor.commit();
						
						Intent intent = new Intent(getApplicationContext(), FacebookActivity.class);
						intent.putExtra("checkLoginFlag", checkLoginFlag);						
						intent.putExtra("stringArrayList2", stringArrayList2);
						if(checkLoginFlag.equals("OfferCalendarMyOffers")){
							intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
						}
						finish();
						startActivity(intent);					
					}else{
						new Common().instructionBox(AccountLaunchActivity.this,R.string.title_case7,R.string.instruction_case7);
						
					}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate | imgLoginWithFb click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(AccountLaunchActivity.this,errorMsg);
					}				
			}
		});
		btnSignUP.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
					if(Common.isNetworkAvailable(AccountLaunchActivity.this)){
						Intent intent = new Intent(getApplicationContext(), RegistrationActivity.class);
						intent.putExtra("checkLoginFlag", checkLoginFlag);						
						intent.putExtra("stringArrayList2", stringArrayList2);
						if(checkLoginFlag.equals("OfferCalendarMyOffers")){
							intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
						}
						finish();
						startActivity(intent);
					}else{
						new Common().instructionBox(AccountLaunchActivity.this,R.string.title_case7,R.string.instruction_case7);
					}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate | btnSignUP click |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(AccountLaunchActivity.this,errorMsg);
				}
				
			}
		});		
		
		btnLogin.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
					if(Common.isNetworkAvailable(AccountLaunchActivity.this)){						
						Intent intent = new Intent(getApplicationContext(), LoginActivity.class);
						intent.putExtra("checkLoginFlag", checkLoginFlag);						
						intent.putExtra("stringArrayList2", stringArrayList2);
						if(checkLoginFlag.equals("OfferCalendarMyOffers")){
							intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
						}
						finish();
						startActivity(intent);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}else{
						new Common().instructionBox(AccountLaunchActivity.this,R.string.title_case7,R.string.instruction_case7);
					}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate | btnLogin click |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(AccountLaunchActivity.this,errorMsg);
				}
			}
		});		
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(AccountLaunchActivity.this,errorMsg);
		}
		
	}
	 @Override
		public void onStart() {
			 try{
			    super.onStart();
			    Tracker easyTracker = EasyTracker.getInstance(this);
				easyTracker.set(Fields.SCREEN_NAME, "/mycloset/brands");
				easyTracker.send(MapBuilder
				    .createAppView()
				    .build()
				);
			 }catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStart  |   " +e.getMessage();
		       	 Common.sendCrashWithAQuery(AccountLaunchActivity.this,errorMsg);
			 }
		}
		 @Override
		public void onStop() {
			 try{
				super.onStop();
				EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
			 }catch(Exception e){
				 e.printStackTrace(); 
				 String errorMsg = className+" | onStop  |   " +e.getMessage();
		       	 Common.sendCrashWithAQuery(AccountLaunchActivity.this,errorMsg);
			 }
		}

}
