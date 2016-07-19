package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class GameOffer extends Activity {
	String className = getClass().getSimpleName();
	JSONObject jsonObject;
	String bgColor="ff2600",lightColor="ff2600",darkColor="ff2600",logo="null",offerId;
	Button btnaddoffer,btnGameRules;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_game_offer);		
		try{
			jsonObject = new JSONObject(getIntent().getStringExtra("jsonObject"));
			Log.e("jsonObject",""+jsonObject);
			
		
			JSONArray clientInfoArray = new JSONArray(jsonObject.getString("client_info"));
			if(clientInfoArray.length() > 0){
				for(int i=0;i<clientInfoArray.length();i++){
					 JSONObject json_obj = clientInfoArray.getJSONObject(i);	
					 Common.sessionClientLogo      = json_obj.getString("logo");
					 Common.sessionClientBgColor   = json_obj.getString("background_color");
					 Common.sessionClientBackgroundLightColor  = json_obj.getString("light_color");
					 Common.sessionClientBackgroundDarkColor   = json_obj.getString("dark_color");
				}
				
			}
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					GameOffer.this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "");
			
			
			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCameraIcon |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(GameOffer.this,errorMsg);
					}
				}
			});
			ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);
			imgvBtnBack.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnBack |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(GameOffer.this,errorMsg);
					}
				}
			});
			
			ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			imgvBtnShare.setImageAlpha(0);
			
			ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);
						
			TextView txtMess = (TextView)findViewById(R.id.txtMess);
			RelativeLayout.LayoutParams rlpTxtMess = (RelativeLayout.LayoutParams) txtMess.getLayoutParams();		
			rlpTxtMess.topMargin=(int) (0.05738 * Common.sessionDeviceHeight);
			txtMess.setLayoutParams(rlpTxtMess);
			txtMess.setTextSize((float) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			
			ImageView imgvGameOffer = (ImageView)findViewById(R.id.imgvGameOffer);
			RelativeLayout.LayoutParams rlpImgvGameOffer = (RelativeLayout.LayoutParams) imgvGameOffer.getLayoutParams();			
			rlpImgvGameOffer.width = (int) (0.8334 * Common.sessionDeviceWidth);
			rlpImgvGameOffer.height = (int) (0.25615 * Common.sessionDeviceHeight);
			rlpImgvGameOffer.topMargin=(int) (0.05636 * Common.sessionDeviceHeight);
			imgvGameOffer.setLayoutParams(rlpImgvGameOffer);
			
			TextView txtofferDesc = (TextView)findViewById(R.id.txtofferDesc);
			RelativeLayout.LayoutParams rlpTxtOfferDesc = (RelativeLayout.LayoutParams) txtofferDesc.getLayoutParams();			
			rlpTxtOfferDesc.width = (int) (0.8334 * Common.sessionDeviceWidth);
			rlpTxtOfferDesc.topMargin=(int) (0.0205 * Common.sessionDeviceHeight);
			txtofferDesc.setLayoutParams(rlpTxtOfferDesc);
			txtofferDesc.setTextSize((float) ((0.04167 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			
			TextView txtofferShortDesc = (TextView)findViewById(R.id.txtofferShortDesc);
			RelativeLayout.LayoutParams rlpTxtofferShortDesc = (RelativeLayout.LayoutParams) txtofferShortDesc.getLayoutParams();
			rlpTxtofferShortDesc.width = (int) (0.8334 * Common.sessionDeviceWidth);
			rlpTxtofferShortDesc.topMargin=(int) (0.0205 * Common.sessionDeviceHeight);
			txtofferShortDesc.setLayoutParams(rlpTxtofferShortDesc);
			txtofferShortDesc.setTextSize((float) ((0.0334 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			
			/*TextView txtValue = (TextView)findViewById(R.id.txtValue);				
			RelativeLayout.LayoutParams rlptxtValue = (RelativeLayout.LayoutParams) txtValue.getLayoutParams();			
			rlptxtValue.width = (int) (0.6667 * Common.sessionDeviceWidth);
			txtValue.setLayoutParams(rlptxtValue);
			txtValue.setTextSize((float) ((0.04167 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));*/
			
			View viewLine = (View)findViewById(R.id.view1);
			RelativeLayout.LayoutParams rlpViewLine = (RelativeLayout.LayoutParams) viewLine.getLayoutParams();			
			rlpViewLine.width = (int) (0.8 * Common.sessionDeviceWidth);
			rlpViewLine.height = (int) (0.001025 * Common.sessionDeviceHeight);
			rlpViewLine.bottomMargin = (int) (0.0922* Common.sessionDeviceHeight);
			viewLine.setLayoutParams(rlpViewLine);
			
			
			
			TextView txtvDisclaimer = (TextView)findViewById(R.id.txtvDisclaimer);				
			RelativeLayout.LayoutParams rlpTxtvDisclaimer = (RelativeLayout.LayoutParams) txtvDisclaimer.getLayoutParams();			
			rlpTxtvDisclaimer.width = (int) (0.6667 * Common.sessionDeviceWidth);
			rlpTxtvDisclaimer.bottomMargin = (int) (0.0492* Common.sessionDeviceHeight);
			txtvDisclaimer.setLayoutParams(rlpTxtvDisclaimer);
			txtvDisclaimer.setTextSize((float) ((0.04167 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			
			
			btnaddoffer = (Button)findViewById(R.id.btnOffer);
			RelativeLayout.LayoutParams rlpBtnaddoffer = (RelativeLayout.LayoutParams) btnaddoffer.getLayoutParams();
			rlpBtnaddoffer.width = (int) (0.5* Common.sessionDeviceWidth);
			rlpBtnaddoffer.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlpBtnaddoffer.topMargin = (int) (0.02152 * Common.sessionDeviceHeight);
			btnaddoffer.setLayoutParams(rlpBtnaddoffer);
			
		
			
			btnGameRules = (Button)findViewById(R.id.btnGameRules);
			RelativeLayout.LayoutParams rlpBtnGameRules = (RelativeLayout.LayoutParams) btnGameRules.getLayoutParams();
			rlpBtnGameRules.width = (int) (0.2667* Common.sessionDeviceWidth);
			rlpBtnGameRules.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlpBtnGameRules.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
			btnGameRules.setLayoutParams(rlpBtnGameRules);
			
			btnGameRules.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(GameOffer.this,GameRules.class);
						intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
						intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
						startActivity(intent);
					}catch(Exception e){
						e.printStackTrace();
					}
					
				}
			});
			
			
			JSONArray offerInfoArray = new JSONArray(jsonObject.getString("offer_info"));
			if(offerInfoArray.length() > 0){
				for(int i=0;i<offerInfoArray.length();i++){
					 JSONObject jsonObj = offerInfoArray.getJSONObject(i);
					 String tbUrl = jsonObj.getString("offer_image").toString().replaceAll(" ", "%20");
					 AQuery aq2 = new AQuery(GameOffer.this);
					 aq2.id(R.id.imgvGameOffer).image(tbUrl, false, false);
					 String value;
					 if(jsonObj.getString("offer_discount_type").equals("A")){
						value = "$" +jsonObj.getString("offer_discount_value");
					}else if(jsonObj.getString("offer_discount_type").equals("P")){
						value = jsonObj.getString("offer_discount_value")+"%";
					}else{
						value = jsonObj.getString("offer_discount_value")+" points";
					}
				//	txtValue.setText(value);				
					txtofferDesc.setText(jsonObj.getString("offer_description"));		
					txtofferShortDesc.setText(jsonObj.getString("offer_short_description"));
					//viewLine.setBackgroundColor(Color.parseColor("#"+bgColor));
					offerId = jsonObj.getString("offer_id");
				}
				btnaddoffer.setOnClickListener(new OnClickListener() {				
					@Override
					public void onClick(View v) {
						try{
							if(Common.isNetworkAvailable(GameOffer.this)){							
								ArrayList<String> stringArrayList =  new ArrayList<String>();	
								stringArrayList.add(offerId);
								stringArrayList.add("OfferView");
								new Common().getLoginDialog(GameOffer.this, MyOffers.class, "OfferViewMyOffers", stringArrayList );
							}
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | btnaddoffer |   " +e.getMessage();
				       	 	Common.sendCrashWithAQuery(GameOffer.this,errorMsg);
						}
					}
				});
			}
			
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(GameOffer.this,errorMsg);
		}
	}
}
