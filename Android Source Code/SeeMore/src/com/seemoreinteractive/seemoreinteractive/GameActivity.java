package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.callback.BitmapAjaxCallback;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class GameActivity extends Activity {
	String className =  getClass().getSimpleName();
	ArrayList<HashMap<String, String>> arrListForGames;
	Button btnPlayNow;
	ProgressBar progressBar;
	JSONObject json_obj;
	JSONArray json;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);		
		try{
			setContentView(R.layout.activity_game);
			RelativeLayout bgRelativeLayout  = (RelativeLayout)findViewById(R.id.bgRelativeLayout);
			RelativeLayout.LayoutParams rlBgRelativeLayout  = (RelativeLayout.LayoutParams)bgRelativeLayout.getLayoutParams();
			rlBgRelativeLayout.width  = (int)(0.875 * Common.sessionDeviceWidth);
			rlBgRelativeLayout.height = (int)(0.4919 * Common.sessionDeviceHeight);	
			//rlBgRelativeLayout.topMargin = (int)(0.0185 * Common.sessionDeviceHeight);
			bgRelativeLayout.setLayoutParams(rlBgRelativeLayout);
			
			
			RelativeLayout rlForImgs  = (RelativeLayout)findViewById(R.id.rlForImgs);
			RelativeLayout.LayoutParams rlBgrlForImgs  = (RelativeLayout.LayoutParams)rlForImgs.getLayoutParams();
			rlBgrlForImgs.width  = (int)(0.8 * Common.sessionDeviceWidth);
			rlBgrlForImgs.height = (int)(0.41 * Common.sessionDeviceHeight);	
			//rlBgrlForImgs.bottomMargin = (int)(0.01025 * Common.sessionDeviceHeight);
			//rlBgrlForImgs.leftMargin = (int)(0.008334 * Common.sessionDeviceWidth);
			//rlBgrlForImgs.rightMargin = (int)(0.008334 * Common.sessionDeviceWidth);
			rlForImgs.setLayoutParams(rlBgrlForImgs);			
			
			btnPlayNow  =  (Button)findViewById(R.id.btnPlayNow);
			RelativeLayout.LayoutParams rlBtnPlayNow  = (RelativeLayout.LayoutParams)btnPlayNow.getLayoutParams();
			rlBtnPlayNow.width  = (int)(0.4584 * Common.sessionDeviceWidth);
			rlBtnPlayNow.height = (int)(0.082 * Common.sessionDeviceHeight);
			rlBtnPlayNow.bottomMargin = (int)(0.02562 * Common.sessionDeviceHeight);
			btnPlayNow.setLayoutParams(rlBtnPlayNow);
			btnPlayNow.setTextSize((int)(0.041667 * Common.sessionDeviceWidth /Common.sessionDeviceDensity));
			btnPlayNow.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{						
						//displayGamesList();
						if(json_obj != null){
							if(json_obj.optString("game_id")!=null){
								if(json_obj.getString("game_id").equals("1")){
				            		Intent intent = new Intent(GameActivity.this,ScratchAndWin.class);		
				            		intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
				            		intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
									intent.putExtra("json", json.toString());																			
									startActivity(intent);
									overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
									finish();
								}else if(json_obj.getString("game_id").equals("2")){
									Intent intent = null;
									if(getIntent().getStringExtra("game_diretion_type").equals("0")){
										 intent = new Intent(GameActivity.this,WheelOfDeals.class);	
									}else if(getIntent().getStringExtra("game_diretion_type").equals("2")){
										 intent = new Intent(GameActivity.this,SpinWheel.class);
									}
									intent.putExtra("json", json.toString());			
									intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
									
									startActivity(intent);									
									overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
									finish();
	    						}
							}
						}
					}catch(Exception e){
						e.printStackTrace();
					}
				}
			});
			
			final ImageView imgvGame = (ImageView)findViewById(R.id.imgvGame);
			RelativeLayout.LayoutParams rlImgvGame  = (RelativeLayout.LayoutParams)imgvGame.getLayoutParams();			
			rlImgvGame.topMargin = (int)(0.02767 * Common.sessionDeviceHeight);
			imgvGame.setLayoutParams(rlImgvGame);
			
			progressBar = (ProgressBar)findViewById(R.id.progressBar);
			
			ImageView imgvClose  =  (ImageView)findViewById(R.id.imgvClose);
			RelativeLayout.LayoutParams rlImgvClose  = (RelativeLayout.LayoutParams)imgvClose.getLayoutParams();
			rlImgvClose.width  = (int)(0.06667 * Common.sessionDeviceWidth);
			rlImgvClose.height = (int)(0.041 * Common.sessionDeviceHeight);		
			rlImgvClose.rightMargin = (int)(0.1 * Common.sessionDeviceWidth);
			//rlImgvClose.topMargin = (int)(0.00308 * Common.sessionDeviceHeight);	
			imgvClose.setLayoutParams(rlImgvClose);
			imgvClose.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						finish();
					}catch(Exception e){
						e.printStackTrace();
					}
				}
			});		
			displayGamesList();	
			final AQuery aq = new AQuery(GameActivity.this);
			Bitmap placeholder = aq.getCachedImage(getIntent().getStringExtra("image"));		
			if(placeholder==null){
				//aq.id(R.id.imgvGame).progress(R.id.progressBar).image(getIntent().getStringExtra("image"), false, false);
				aq.id(R.id.imgvGame).image(getIntent().getStringExtra("image"), false, false, 0, 0, new BitmapAjaxCallback(){
			        @Override
			        public void callback(String url, ImageView iv, Bitmap bm, AjaxStatus status){
			        	try{
			                iv.setImageBitmap(bm);
			                progressBar.setVisibility(View.INVISIBLE);
				        	btnPlayNow.setVisibility(View.VISIBLE);
			        	}catch(Exception e){
			        		e.printStackTrace();
			        	}
			        }
			});
			aq.cache(getIntent().getStringExtra("image"), 14400000);				
			}else{
				 imgvGame.setImageBitmap(placeholder);
				 progressBar.setVisibility(View.INVISIBLE);
	        	 btnPlayNow.setVisibility(View.VISIBLE);
			}
			
					
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" |displayGamesList  ajax  callback  |   " +e.getMessage();
			Common.sendCrashWithAQuery(GameActivity.this, errorMsg);
		}
		
	}

	public void displayGamesList(){
		try{
			final String clientGameId = getIntent().getStringExtra("client_game_id");	
			final String clientId = getIntent().getStringExtra("client_id");
    		String orderUrl = Constants.Live_Url+"mobileapps/ios/public/games/";	
			final AQuery aq = new AQuery(GameActivity.this);
			Map<String, String> params = new HashMap<String, String>();			
			params.put("client_game_id", clientGameId);
			params.put("client_id", ""+clientId);
			params.put("user_id", ""+Common.sessionIdForUserLoggedIn);		
			JSONObject jsonObject = new JSONObject(params);		
			Map<String, String> jsonParams = new HashMap<String, String>();
			jsonParams.put("json", jsonObject.toString());	
			aq.ajax(orderUrl,jsonParams,  JSONArray.class, new AjaxCallback<JSONArray>(){			
				@Override
				public void callback(String url, JSONArray jsonv, AjaxStatus status) {
					try{
						     int k=0;
    						 json_obj = jsonv.getJSONObject(k);
    						 json = jsonv;    			
    						 String clientName = null;
    						 JSONArray clientInfoArray = new JSONArray(json_obj.getString("client_info"));
    							if(clientInfoArray.length() > 0){
    								for(int i=0;i<clientInfoArray.length();i++){					
    									 JSONObject json_obj = clientInfoArray.getJSONObject(i);	
    									 clientName    = json_obj.getString("name");
    								}
    							}
    					String screenName = "/games/"+json_obj.getString("game_id")+"/"+clientId+"/"+clientName;			
    					Common.sendJsonWithAQuery(GameActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, "", "");    					
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | displayGamesList aquery callback  |   " +e.getMessage();
						Common.sendCrashWithAQuery(GameActivity.this, errorMsg);
					}
				}
			});
		
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | displayGamesList order update callback  |   " +e.getMessage();
			Common.sendCrashWithAQuery(GameActivity.this, errorMsg);
	    }
 }

	
}
