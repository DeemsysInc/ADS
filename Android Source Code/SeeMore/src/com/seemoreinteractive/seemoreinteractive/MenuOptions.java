package com.seemoreinteractive.seemoreinteractive;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.LinearLayout;

import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class MenuOptions extends Activity {
	
	Context context = this;
	String className = this.getClass().getSimpleName();
	SessionManager session;
	public Boolean alertErrorType = true;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_options_menu);
		try{
			// Session class instance
	        session = new SessionManager(context);
			final Button btnCancel = (Button) findViewById(R.id.btnCancel);
			LinearLayout.LayoutParams llpForBtnCancel = (LinearLayout.LayoutParams) btnCancel.getLayoutParams();
			llpForBtnCancel.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnCancel.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnCancel.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnCancel.setLayoutParams(llpForBtnCancel);
			btnCancel.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnCancel.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
					btnCancel.setTextColor(Color.parseColor("#000000"));
					btnCancel.setBackgroundResource(R.drawable.button_background);
					finish();
					overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
			
			final Button btnHowTo = (Button) findViewById(R.id.btnHowTo);
			LinearLayout.LayoutParams llpForBtnHowTo = (LinearLayout.LayoutParams) btnHowTo.getLayoutParams();
			llpForBtnHowTo.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnHowTo.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnHowTo.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnHowTo.setLayoutParams(llpForBtnHowTo);
			btnHowTo.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnHowTo.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
					if(Common.isNetworkAvailable(MenuOptions.this))
					{
					
					btnHowTo.setTextColor(Color.parseColor("#FFFFFF"));
					btnHowTo.setBackgroundResource(R.drawable.button_background_black);
					Intent intent = new Intent(context, OptionsHowTo.class);
					context.startActivity(intent);
					//alertDialog.cancel();
					finish();
					overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}else{
						//new Common().instructionBox(ARDisplayActivity.class,R.string.title_case7,R.string.instruction_case7);
						Intent returnIntent = new Intent();
						returnIntent.putExtra("activity","menu");
						setResult(RESULT_OK,returnIntent);
						finish();					
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
						 
					}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnHowTo click    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
			
			final Button btnSettings = (Button) findViewById(R.id.btnSettings);
			LinearLayout.LayoutParams llpForBtnSettings = (LinearLayout.LayoutParams) btnSettings.getLayoutParams();
			llpForBtnSettings.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnSettings.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnSettings.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnSettings.setLayoutParams(llpForBtnSettings);
			btnSettings.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnSettings.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						btnSettings.setTextColor(Color.parseColor("#FFFFFF"));
						btnSettings.setBackgroundResource(R.drawable.button_background_black);
						Intent intent = new Intent(context, SettingAcitivty.class);
						context.startActivity(intent);
						//alertDialog.cancel();
						finish();
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnSettings click    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
			final Button btnPrivacyPolicy = (Button) findViewById(R.id.btnPrivacyPolicy);
			LinearLayout.LayoutParams llpForBtnPrivacy = (LinearLayout.LayoutParams) btnPrivacyPolicy.getLayoutParams();
			llpForBtnPrivacy.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnPrivacy.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnPrivacy.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnPrivacyPolicy.setLayoutParams(llpForBtnPrivacy);
			btnPrivacyPolicy.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnPrivacyPolicy.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(MenuOptions.this))
						{
							btnPrivacyPolicy.setTextColor(Color.parseColor("#FFFFFF"));
							btnPrivacyPolicy.setBackgroundResource(R.drawable.button_background_black);
							Intent intent = new Intent(context, OptionsPrivacyPolicy.class);
							context.startActivity(intent);
							//alertDialog.cancel();
							finish();
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}else{
							//new Common().instructionBox(ARDisplayActivity.class,R.string.title_case7,R.string.instruction_case7);
							Intent returnIntent = new Intent();
							returnIntent.putExtra("activity","menu");
							setResult(RESULT_OK,returnIntent);
							finish();					
							overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
							 
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnPrivacyPolicy click    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
			
			final Button btnTermsConditions = (Button) findViewById(R.id.btnTermsConditions);
			LinearLayout.LayoutParams llpForBtnTerms = (LinearLayout.LayoutParams) btnTermsConditions.getLayoutParams();
			llpForBtnTerms.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnTerms.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnTerms.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnTermsConditions.setLayoutParams(llpForBtnTerms);
			btnTermsConditions.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnTermsConditions.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
					if(Common.isNetworkAvailable(MenuOptions.this))
					{
						btnTermsConditions.setTextColor(Color.parseColor("#FFFFFF"));
						btnTermsConditions.setBackgroundResource(R.drawable.button_background_black);
						Intent intent = new Intent(context, OptionsTermsConditions.class);
						context.startActivity(intent);
						//alertDialog.cancel();
						finish();
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}else{
						//new Common().instructionBox(ARDisplayActivity.class,R.string.title_case7,R.string.instruction_case7);
						Intent returnIntent = new Intent();
						returnIntent.putExtra("activity","menu");
						setResult(RESULT_OK,returnIntent);
						finish();					
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
						 
					}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnPrivacyPolicy click    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
	
			
			final Button btnAbout = (Button) findViewById(R.id.btnAbout);
			LinearLayout.LayoutParams llpForBtnAbout = (LinearLayout.LayoutParams) btnAbout.getLayoutParams();
			llpForBtnAbout.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnAbout.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnAbout.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnAbout.setLayoutParams(llpForBtnAbout);
			btnAbout.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnAbout.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						btnAbout.setTextColor(Color.parseColor("#FFFFFF"));
						btnAbout.setBackgroundResource(R.drawable.button_background_black);
						Intent intent = new Intent(context, OptionsAbout.class);
						context.startActivity(intent);
						//alertDialog.cancel();
						finish();
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnAbout click    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
			
			/*final Button btnReset = (Button) findViewById(R.id.btnReset);
			LinearLayout.LayoutParams llpForBtnReset = (LinearLayout.LayoutParams) btnReset.getLayoutParams();
			llpForBtnReset.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnReset.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnReset.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnReset.setLayoutParams(llpForBtnReset);
			btnReset.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnReset.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					btnReset.setTextColor(Color.parseColor("#FFFFFF"));
					btnReset.setBackgroundResource(R.drawable.button_background_black);
					
					new AlertDialog.Builder(MenuOptions.this)
				    .setTitle("Reset App")
				    .setMessage("Are you sure you want to reset app?")
				    .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
				        @Override
						public void onClick(DialogInterface dialog, int which) { 
				            // continue with delete
				        	 dialog.cancel();
				        	 new MainActivity();
							MainActivity.ResetFlag =true;
				        	 new Common().deleteFiles(Constants.Trigger_Location);
							 finish();
							 Intent intent = new Intent(context, ARDisplayActivity.class);
							 context.startActivity(intent);
							 overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
							
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
			});*/
			final Button btnFindAStore = (Button) findViewById(R.id.btnFindAStore);
			LinearLayout.LayoutParams llpForBtnFindAStore = (LinearLayout.LayoutParams) btnFindAStore.getLayoutParams();
			llpForBtnFindAStore.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnFindAStore.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnFindAStore.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnFindAStore.setLayoutParams(llpForBtnFindAStore);
			btnFindAStore.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnFindAStore.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(MenuOptions.this))
						{
							btnFindAStore.setTextColor(Color.parseColor("#FFFFFF"));
							btnFindAStore.setBackgroundResource(R.drawable.button_background_black);
							Intent intent = new Intent(context, FindAStore.class);
							context.startActivity(intent);
							finish();
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}else{
							//new Common().instructionBox(ARDisplayActivity.class,R.string.title_case7,R.string.instruction_case7);
							Intent returnIntent = new Intent();
							returnIntent.putExtra("activity","menu");
							setResult(RESULT_OK,returnIntent);
							finish();					
							overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);						 
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnFindAStore click    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
					}
				}
			});
			
			
			/*final Button btnLogout = (Button) findViewById(R.id.btnLogout);
			RelativeLayout.LayoutParams llpForBtnLogout = (RelativeLayout.LayoutParams) btnLogout.getLayoutParams();
			llpForBtnLogout.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnLogout.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnLogout.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnLogout.setLayoutParams(llpForBtnLogout);
			btnLogout.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			
			final Button btnLogin = (Button) findViewById(R.id.btnLogin);
			RelativeLayout.LayoutParams llpForBtnLogin = (RelativeLayout.LayoutParams) btnLogin.getLayoutParams();
			llpForBtnLogin.width = (int) (0.667 * Common.sessionDeviceWidth);
			llpForBtnLogin.height = (int) (0.062 * Common.sessionDeviceHeight);
			llpForBtnLogin.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			btnLogin.setLayoutParams(llpForBtnLogin);
			btnLogin.setTextSize((float) (0.0584 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);*/
	        /*if(session.isLoggedIn())
	        {
	    		btnLogin.setVisibility(View.INVISIBLE);
	    		btnLogout.setVisibility(View.VISIBLE);
	    		btnLogout.setOnClickListener(new View.OnClickListener() {
	    			
	    			@Override
	    			public void onClick(View v) {
	    				// TODO Auto-generated method stub
	    				btnLogout.setTextColor(Color.parseColor("#FFFFFF"));
	    				btnLogout.setBackgroundResource(R.drawable.button_background_black);
	    				session.logoutUser();
	    				//alertDialog.cancel();
	    				finish();
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
	    			}
	    		});
	        
	        } else {		
				// Session class instance
		        session = new SessionManager(context);
		        
	    		btnLogin.setVisibility(View.VISIBLE);
	    		btnLogout.setVisibility(View.INVISIBLE);
	    		btnLogin.setOnClickListener(new View.OnClickListener() {
	    			
					@Override
	    			public void onClick(View v) {
	    				// TODO Auto-generated method stub
						btnLogin.setTextColor(Color.parseColor("#FFFFFF"));
						btnLogin.setBackgroundResource(R.drawable.button_background_black);
	
	    				new ComSmon().getLoginDialog(MenuOptions.this, ARDisplayActivity.class, "ARDisplay", new ArrayList<String>());
	
	    				//MenuOptions.this.finish();
	    			}
	    		});
	        	
	        }*/
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MenuOptions.this,errorMsg);
		}
	}
}
