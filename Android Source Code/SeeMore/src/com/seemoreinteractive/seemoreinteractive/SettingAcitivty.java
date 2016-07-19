package com.seemoreinteractive.seemoreinteractive;

import java.io.File;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class SettingAcitivty extends Activity {

	final Context context = this;
	SessionManager session;
	String userName="",userFName="",userLName="";
	String className = this.getClass().getSimpleName();
	Button btnReset,btnAccount;
	ImageView btnLoginInOut;
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_settings);
		try{
			//new Common().pageHeaderTitle(SettingAcitivty.this, "Settings");		
			new Common().showDrawableImageFromAquery(this, R.drawable.btn_seemore_login, R.id.btnLoginOut);
	    	//new Common().clientThemeWithOrWithoutLogo(this);
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Settings", "");

			ImageView imgvBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgvBtnCart.setImageAlpha(0);
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
					 Intent returnIntent = new Intent(SettingAcitivty.this, ARDisplayActivity.class);
					 returnIntent.putExtra("instruction_type", "0");
					// Closing all the Activities
					 //returnIntent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);			
					// Add new Flag to start new Activity
					 //returnIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
					 setResult(1, returnIntent);
					 finish();
					 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBackButton click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
					}
				}
			});
			
		     int btnBottomMargin= (int) (0.0257 * Common.sessionDeviceHeight);
		     //int btnAccTopMargin= (int) (0.462 * Common.sessionDeviceHeight);
		     int btnWidth= (int) (0.5 * Common.sessionDeviceWidth);
			 int btnHeight= (int) (0.0656 * Common.sessionDeviceHeight);
			 
			 
			btnAccount = (Button) findViewById(R.id.btnAccount);
			RelativeLayout.LayoutParams rlpAccount = (RelativeLayout.LayoutParams) btnAccount.getLayoutParams();		
			rlpAccount.width = btnWidth;
			rlpAccount.height = btnHeight;
			rlpAccount.bottomMargin = btnBottomMargin;
			btnAccount.setLayoutParams(rlpAccount);
			
	        btnLoginInOut = (ImageView) findViewById(R.id.btnLoginOut);
			RelativeLayout.LayoutParams rlpLogin = (RelativeLayout.LayoutParams) btnLoginInOut.getLayoutParams();		
			rlpLogin.width = btnWidth;
			rlpLogin.height = btnHeight;
			rlpLogin.bottomMargin = (int) (0.123 * Common.sessionDeviceHeight);;
			btnLoginInOut.setLayoutParams(rlpLogin);				
			
			btnReset = (Button) findViewById(R.id.btnReset);
			RelativeLayout.LayoutParams rlpReset = (RelativeLayout.LayoutParams) btnReset.getLayoutParams();		
			rlpReset.width = btnWidth;
			rlpReset.height = btnHeight;
			rlpReset.bottomMargin = btnBottomMargin;
			btnReset.setLayoutParams(rlpReset);
		
			btnReset.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {					 
					 new AlertDialog.Builder(SettingAcitivty.this)
					    .setTitle("Reset App")
					    .setMessage("Are you sure you want to reset app?")
					    .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
					        @Override
							public void onClick(DialogInterface dialog, int which) {
					        	try{
					            // continue with delete
					        	 dialog.cancel();
					        	 new MainActivity();
								 MainActivity.ResetFlag =true;
								 Constants.ARFlag =true;
					        	 new Common().deleteFiles(Constants.Trigger_Location);
					        	 new Common().deleteFiles(Constants.LOCATION);
								 finish();
								 Intent intent = new Intent(context, ARDisplayActivity.class);
								 context.startActivity(intent);
								 overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					        	}catch(Exception e){
					        		e.printStackTrace();
					        		String errorMsg = className+" | btnReset click   |   " +e.getMessage();
									Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
					        	}
								
					        }
					     })
					    .setNegativeButton(android.R.string.no, new DialogInterface.OnClickListener() {
					        @Override
							public void onClick(DialogInterface dialog, int which) { 
					            // do nothing
					        	 dialog.cancel();
					        }
					     })
					     .show();
					
				}
			});
			
			btnLoginInOut.setOnClickListener(new View.OnClickListener() {    			
    			@Override
    			public void onClick(View v) {
    				try{
    				Intent intent = new Intent(getApplicationContext(), AccountLaunchActivity.class);
    				intent.putExtra("checkLoginFlag", "ARDisplay");						
    				intent.putExtra("stringArrayList2", "");    				
    				finish();
    				startActivity(intent);
    				}catch(Exception e){
    					e.printStackTrace();
    					String errorMsg = className+" | btnLoginInOut click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
    				}

    			}
    		});
			session = new SessionManager(context);
	        if(session.isLoggedIn()){      	
	        	
		        userFName = Common.sessionIdForUserLoggedFname;
		        userLName = Common.sessionIdForUserLoggedLname;
		        TextView username = (TextView)findViewById(R.id.username);
		        //username.setTextSize((float) (0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
		        RelativeLayout.LayoutParams rlpUserName = (RelativeLayout.LayoutParams) username.getLayoutParams();		
		        //rlpUserName.width = btnWidth;
		        //rlpUserName.height = btnHeight;
		        rlpUserName.topMargin = (int)(0.0308 * Common.sessionDeviceHeight);
		        username.setLayoutParams(rlpUserName);
				
		        userName = Common.sessionIdForUserLoggedName+"("+userFName+" "+userLName+")";
		        username.setText("@"+userName);
		        				
		        new Common().clickingOnBackButtonWithAnimation(SettingAcitivty.this, ProductList.class,"1");  		        
				btnAccount.setVisibility(View.VISIBLE);		        				
				new Common().showDrawableImageFromAquery(this, R.drawable.btn_seemore_logout, R.id.btnLoginOut);
				btnAccount.setOnClickListener(new View.OnClickListener() {	    			
	    			@Override
	    			public void onClick(View v) {
	    				try{
	    				Intent intent = new Intent(getApplicationContext(), AccountSettings.class);	    								
	    				finish();
	    				startActivity(intent);
	    				}catch(Exception e){
	    					e.printStackTrace();
	    					String errorMsg = className+" | btnAccount click   |   " +e.getMessage();
							Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
	    				}
	    			}
	    		});

				Log.i("Change url when logout 1", ""+Constants.Live_Url);
				btnLoginInOut.setOnClickListener(new View.OnClickListener() {	    			
	    			@Override
	    			public void onClick(View v) {
	    				try{
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
						File fileProfile = new File(Constants.LOCATION+"userprofile");
		    				if(fileProfile.exists()){
		    					fileProfile.delete();
		    				Log.i("fileProfile",""+fileProfile.delete());
		    				}

	    				Log.i("Change url when logout 2", ""+Constants.Live_Url);
    					Constants.Live_Url = Constants.Live_Url_Main;
    					Common.sessionIdForUserGroupId = 0;
    					Common.sessionIdForUserLoggedIn = 0;
	    				Log.i("Change url when logout 3", ""+Constants.Live_Url);
	    				new Constants();
	    				session.logoutUser();
	    				SharedPreferences mPrefs = getSharedPreferences("fb_prefs", Context.MODE_PRIVATE);
	    				SharedPreferences.Editor editor = mPrefs.edit();
			            editor.putString("access_token", "");
			            editor.putLong("access_expires", 0);
			            editor.commit();
	    				//alertDialog.cancel();
	    				/*Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
	    				intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK );			    
						setResult(1, intent);
						finish();*/
	    			/*	
	    				Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
	    				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);	    				
	    				startActivity(intent);*/
	    				
	    				Constants.ARFlag = true;
	    				Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
	    				intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
	    				startActivity(intent); // Launch the HomescreenActivity
	    				finish(); 
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
	    			}catch(Exception e){
	    				e.printStackTrace();
	    				String errorMsg = className+" | btnLoginInOut click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
	    			}
	    			}
	    		});
				
				/*Button btnSaveMyOrder = (Button) findViewById(R.id.btnSaveMyOrder);
				RelativeLayout.LayoutParams rlpSaveMyOrder = (RelativeLayout.LayoutParams) btnSaveMyOrder.getLayoutParams();		
				rlpSaveMyOrder.width = btnWidth;
				rlpSaveMyOrder.height = btnHeight;
				rlpSaveMyOrder.bottomMargin = btnBottomMargin;
				btnSaveMyOrder.setLayoutParams(rlpSaveMyOrder);
				btnSaveMyOrder.setTextSize((float) (0.04167 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
				btnSaveMyOrder.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View v) {
						// TODO Auto-generated method stub
	    				Intent intent = new Intent(getApplicationContext(), SaveOrderInformation.class);
	    				startActivity(intent); // Launch the HomescreenActivity
	    				finish(); 
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}
				});*/
				//btnSaveMyOrder.setVisibility(View.INVISIBLE);
	        }
			String screenName = "/settings";
			if(Common.sessionIdForUserLoggedIn!=0){
				screenName += "/"+Common.sessionIdForUserLoggedIn+"/"+userName;
			}
			String productIds = "";
			String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);      
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | OnCreate    |   " +e.getMessage();
			Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
		}
	}
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(SettingAcitivty.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(SettingAcitivty.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(SettingAcitivty.this,errorMsg);
			}
		}
}
