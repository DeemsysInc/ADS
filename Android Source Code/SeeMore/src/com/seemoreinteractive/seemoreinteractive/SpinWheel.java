package com.seemoreinteractive.seemoreinteractive;

import java.net.URL;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONObject;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.wheel.SpinCircle;

import android.app.Activity;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;

public class SpinWheel extends Activity {
	String className = getClass().getSimpleName();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		try{
			setContentView(R.layout.activity_spin_wheel);
			   Bundle b = getIntent().getExtras();
				String jsons=b.getString("json");
				JSONArray jsonObject = new JSONArray(jsons);
			    JSONArray clientInfoArray = new JSONArray(jsonObject.getJSONObject(0).getString("client_info"));
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
						SpinWheel.this, Common.sessionClientBgColor,
						Common.sessionClientBackgroundLightColor,
						Common.sessionClientBackgroundDarkColor,
						Common.sessionClientLogo, "", "");
				
				ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
				imgvBtnShare.setImageAlpha(0);
				
				ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
				imgvBtnCloset.setImageAlpha(0);
				
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
							String errorMsg = className+" | imgBtnCameraIcon click  |   " +e.getMessage();
				       	 	Common.sendCrashWithAQuery(SpinWheel.this,errorMsg);
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
						}catch(Exception  e){
							e.printStackTrace();
							String errorMsg = className+" | imgvBtnBack click  |   " +e.getMessage();
				       	 	Common.sendCrashWithAQuery(SpinWheel.this,errorMsg);
						}
					}
				});
				
				ImageView pointer = (ImageView) findViewById(R.id.pointer);
				RelativeLayout.LayoutParams rlpPointer = (RelativeLayout.LayoutParams) pointer.getLayoutParams();
				rlpPointer.width  = (int) (0.08334* Common.sessionDeviceWidth);
				rlpPointer.height = (int) (0.05123* Common.sessionDeviceHeight);		
				rlpPointer.topMargin = (int) (0.133 * Common.sessionDeviceHeight);		
				//rlpPointer.rightMargin = (int) (0.03334 * Common.sessionDeviceWidth);		
				pointer.setLayoutParams(rlpPointer);
				displayGameOffer();
		}catch(Exception e){
			e.printStackTrace();
		}
	}

	
	JSONArray jsonOfferDetails;
	public static ArrayList<JSONObject> arrOfferDetails;
	public static String gameRules;
	public  ArrayList<String> arrImagesOfferId;
	public void displayGameOffer(){
		try{	
			
			Bundle b = getIntent().getExtras();
			String jsons=b.getString("json");
			jsonOfferDetails = new JSONArray(jsons);		
			 Log.e("jsonOfferDetails",""+jsonOfferDetails);	
			 String[] Color = null,name = null;
			if(jsonOfferDetails.length() > 0){							
				 Color = new String[jsonOfferDetails.length()];
				 name = new String[jsonOfferDetails.length()];
				 arrImagesOfferId = new ArrayList<String>();
					arrOfferDetails  = new ArrayList<JSONObject>();
					for(int s=0; s<jsonOfferDetails.length(); s++){					
						 JSONObject json_obj = jsonOfferDetails.getJSONObject(s);	
						 final String curveImagesUrl = json_obj.getString("game_image").replaceAll(" ", "%20");
						 Color[s] = json_obj.getString("seg_color");  
						 
						 Log.e("curveImagesUrl",curveImagesUrl);
						 arrImagesOfferId.add(json_obj.getString("offer_id"));
						 arrOfferDetails.add(json_obj);
						 
						 JSONArray offerInfoArray = new JSONArray(jsonOfferDetails.getJSONObject(s).getString("offer_info"));
							if(offerInfoArray.length() > 0){
								for(int j=0;j<offerInfoArray.length();j++){
									 JSONObject jsonObj = offerInfoArray.getJSONObject(j);	
									 if(jsonObj != null){
										 String offrDis;
			                	
			                		 if(jsonObj.getString("offer_discount_type").equals("A")){
										 offrDis = " $" +jsonObj.getString("offer_discount_value")+" OFF ";
									}else if(jsonObj.getString("offer_discount_type").equals("P")){
										offrDis = " " + jsonObj.getString("offer_discount_value")+"% OFF ";
									}else{
										offrDis = " "+jsonObj.getString("offer_discount_value")+" points ";
									}
			                		 name[s] = offrDis;  
								  }
								}
							}
			                		
			                	
					}
					
			}
			
			FrameLayout dial = (FrameLayout) findViewById(R.id.dial);
			dial.addView(new SpinCircle(this,name,Color,jsonOfferDetails.length(),SpinWheel.this));
			gameRules = getIntent().getStringExtra("game_rules_url");  
				
	}catch(Exception e){
		e.printStackTrace();
		String errorMsg = className+" | displayGameOffer callback  |   " +e.getMessage();
		Common.sendCrashWithAQuery(SpinWheel.this, errorMsg);
	}
 }
	
	
}
