package com.seemoreinteractive.seemoreinteractive;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.RelativeLayout;

import com.google.android.gms.games.GamesActivityResultCodes;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class TopMenuInfo extends Activity {

	String className = this.getClass().getSimpleName();
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_top_menu_info);
		try{
			final Button btnCancel = (Button) findViewById(R.id.btnCancel);
			RelativeLayout.LayoutParams llpForBtnCancel = (RelativeLayout.LayoutParams) btnCancel.getLayoutParams();
			llpForBtnCancel.width = (int) (0.5 * Common.sessionDeviceWidth);
			llpForBtnCancel.height = (int) (0.0615 * Common.sessionDeviceHeight);
			llpForBtnCancel.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			btnCancel.setLayoutParams(llpForBtnCancel);
			btnCancel.setTextSize((float) (0.05 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnCancel.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						finish();
						//overridePendingTransition(R.anim.slide_out_to_top, R.anim.slide_in_from_top);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  btnCancel click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
					}
				}
			});
			final Button btnRecentlyScanned = (Button) findViewById(R.id.btnRecentlyScanned);
			RelativeLayout.LayoutParams llpForBtnRecentlyScanned = (RelativeLayout.LayoutParams) btnRecentlyScanned.getLayoutParams();
			llpForBtnRecentlyScanned.width = (int) (0.5 * Common.sessionDeviceWidth);
			llpForBtnRecentlyScanned.height = (int) (0.0615 * Common.sessionDeviceHeight);
			llpForBtnRecentlyScanned.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			btnRecentlyScanned.setLayoutParams(llpForBtnRecentlyScanned);
			btnRecentlyScanned.setTextSize((float) (0.05 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnRecentlyScanned.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(TopMenuInfo.this, RecentlyScanned.class);	
						startActivityForResult(intent, 1);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  btnRecentlyScanned click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
					}
				}
			});
			final Button btnSettings = (Button) findViewById(R.id.btnSettings);
			RelativeLayout.LayoutParams llpForBtnSettings = (RelativeLayout.LayoutParams) btnSettings.getLayoutParams();
			llpForBtnSettings.width = (int) (0.5 * Common.sessionDeviceWidth);
			llpForBtnSettings.height = (int) (0.0615 * Common.sessionDeviceHeight);
			llpForBtnSettings.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			btnSettings.setLayoutParams(llpForBtnSettings);
			btnSettings.setTextSize((float) (0.05 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnSettings.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), SettingAcitivty.class);
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  btnSettings click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
					}
				}
			});
			final Button btnFindAStore = (Button) findViewById(R.id.btnFindAStore);
			RelativeLayout.LayoutParams llpForBtnFindAStore = (RelativeLayout.LayoutParams) btnFindAStore.getLayoutParams();
			llpForBtnFindAStore.width = (int) (0.5 * Common.sessionDeviceWidth);
			llpForBtnFindAStore.height = (int) (0.0615 * Common.sessionDeviceHeight);
			llpForBtnFindAStore.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			btnFindAStore.setLayoutParams(llpForBtnFindAStore);
			btnFindAStore.setTextSize((float) (0.05 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnFindAStore.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(TopMenuInfo.this))
						{
							Intent intent = new Intent(getApplicationContext(), FindAStore.class);
							startActivity(intent);
							finish();
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}else{
							Intent returnIntent = new Intent();
							returnIntent.putExtra("activity", "menu");
							setResult(RESULT_OK,returnIntent);
							finish();					
							overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);						 
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  btnFindAStore click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
					}
				}
			});
			
			
			/*final Button btnGames = (Button) findViewById(R.id.btnGames);
			RelativeLayout.LayoutParams llpForBtnGames = (RelativeLayout.LayoutParams) btnGames.getLayoutParams();
			llpForBtnGames.width = (int) (0.5 * Common.sessionDeviceWidth);
			llpForBtnGames.height = (int) (0.0615 * Common.sessionDeviceHeight);
			llpForBtnGames.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			btnGames.setLayoutParams(llpForBtnGames);
			btnGames.setTextSize((float) (0.05 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnGames.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), GameActivity.class);
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  btnCancel click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
					}
				}
			});*/
			/*final Button btnMyOrders = (Button) findViewById(R.id.btnMyOrders);
			RelativeLayout.LayoutParams llpForBtnMyOrders = (RelativeLayout.LayoutParams) btnMyOrders.getLayoutParams();
			llpForBtnMyOrders.width = (int) (0.5 * Common.sessionDeviceWidth);
			llpForBtnMyOrders.height = (int) (0.0615 * Common.sessionDeviceHeight);
			llpForBtnMyOrders.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			btnMyOrders.setLayoutParams(llpForBtnMyOrders);
			btnMyOrders.setTextSize((float) (0.05 *Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			btnMyOrders.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), SaveOrderInformation.class);
	    				intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
	    				startActivity(intent); // Launch the HomescreenActivity
	    				finish(); 
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  btnMyOrders click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
					}
				}
			});*/
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
		}
	}
	
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			// TODO Auto-generated method stub
			super.onActivityResult(requestCode, resultCode, data);
			Log.i("requestCode", ""+requestCode);
			Log.i("resultCode", ""+resultCode);				
			 if(resultCode==2){
					//Log.e("return for", "Recently Scanned page "+data);
					if(data != null){
						try{ 
							 Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
					    	 intent.putExtra("triggerId", data.getStringExtra("triggerId"));
					    	 intent.putExtra("cosId", data.getStringExtra("cosId"));
					    	 intent.putExtra("productId", data.getStringExtra("productId"));
					    	 intent.putExtra("offerId", data.getStringExtra("offerId"));
					         setResult(2,intent);
						     finish();
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" resultCode |onActivityResult |   " +e.getMessage();
				       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
						}
						}
			 }else{
				 finish();
			 }
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" |onActivityResult |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(TopMenuInfo.this,errorMsg);
		}
	}
					
}
