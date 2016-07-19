package com.seemoreinteractive.seemoreinteractive;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.text.Html;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
public class ThankYou extends Activity {

	AQuery aq;
	String className = this.getClass().getSimpleName();
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_thank_you);
		aq = new AQuery(this);		
		try{
	        new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Thank You", "");	

			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{	
						new Common().clickingOnBackButtonWithAnimationWithBackPressed(ThankYou.this, ARDisplayActivity.class, "0");
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnCameraIcon click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ThankYou.this, errorMsg);
					}
				}
			});
	    	
	    	ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);      
	    	imgvBtnCloset.setImageAlpha(0);
			//new Common().showDrawableImageFromAquery(this, R.drawable.btn_trash, R.id.imgvBtnCloset);
	    	ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);      
	    	imgvBtnShare.setImageAlpha(0);
	    	
	    	ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);  
	    	imgvBtnBack.setImageAlpha(0);
			/*imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{		
				    	 Intent Home = new Intent(getApplicationContext(), ARDisplayActivity.class);
				         setResult(1,Home);
					     finish();
					     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);	
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						//Toast.makeText(getApplicationContext(), "Error: SeeMore Login imgvBtnBack.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | Oncreate imgvBtnBack click     |   " +e.getMessage();
						Common.sendCrashWithAQuery(ThankYou.this,errorMsg);
					}
				}
			});*/
	    	
			ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle);
			imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
						Intent intent = new Intent(getApplicationContext(), MenuOptions.class);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
						//hideInstruction(view);
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | Oncreate imgFooterMiddle click     |   " +e.getMessage();
						Common.sendCrashWithAQuery(ThankYou.this,errorMsg);
					}
					
				}
			});

			TextView txtvThankYouMsg = (TextView) findViewById(R.id.txtvThankYouMsg);
			RelativeLayout.LayoutParams rlpFortxtvThankYouMsg = (RelativeLayout.LayoutParams) txtvThankYouMsg.getLayoutParams();
			rlpFortxtvThankYouMsg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			rlpFortxtvThankYouMsg.width = (int) (0.9167 * Common.sessionDeviceWidth);
			rlpFortxtvThankYouMsg.bottomMargin = (int) (0.2234 * Common.sessionDeviceHeight);
			txtvThankYouMsg.setLayoutParams(rlpFortxtvThankYouMsg);
			txtvThankYouMsg.setTextSize((float) (0.0367* Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			if(getIntent().getExtras()!=null){
				Log.e("finalMsg here", ""+getIntent().getStringExtra("finalMsg"));
				String strFinalMsg = getIntent().getStringExtra("finalMsg").toString();
				//Log.e("strFinalMsg", ""+Html.fromHtml(strFinalMsg));
				txtvThankYouMsg.setText(Html.fromHtml(strFinalMsg.replace("\\n\\n", "<br><br>")));				
			}
			
			Button btnShopAgain = (Button) findViewById(R.id.btnShopAgain);
			RelativeLayout.LayoutParams rlpForBtnShopAgain = (RelativeLayout.LayoutParams) btnShopAgain.getLayoutParams();
			rlpForBtnShopAgain.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			rlpForBtnShopAgain.width = (int) (0.334 * Common.sessionDeviceWidth);
			rlpForBtnShopAgain.height = (int) (0.0656 * Common.sessionDeviceHeight);
			rlpForBtnShopAgain.leftMargin = (int) (0.1384 * Common.sessionDeviceWidth);
			btnShopAgain.setLayoutParams(rlpForBtnShopAgain);
			btnShopAgain.setTextSize((float) (0.0367* Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			btnShopAgain.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
				    	 Intent intent = new Intent(getApplicationContext(), Products.class);
				    	 startActivity(intent);
					     finish();
					     overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnShopAgain click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ThankYou.this, errorMsg);
					}
				}
			});
			Button btnMyOrders = (Button) findViewById(R.id.btnMyOrders);
			RelativeLayout.LayoutParams rlpForBtnMyOrders = (RelativeLayout.LayoutParams) btnMyOrders.getLayoutParams();
			rlpForBtnMyOrders.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			rlpForBtnMyOrders.width = (int) (0.334 * Common.sessionDeviceWidth);
			rlpForBtnMyOrders.height = (int) (0.0656 * Common.sessionDeviceHeight);
			rlpForBtnMyOrders.bottomMargin = (int) (0.1466 * Common.sessionDeviceHeight);
			btnMyOrders.setLayoutParams(rlpForBtnMyOrders);
			btnMyOrders.setTextSize((float) (0.0367* Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			btnMyOrders.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
				    	 Intent intent = new Intent(getApplicationContext(), SaveOrderInformation.class);
				    	 startActivity(intent);
					     finish();
					     overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnShopAgain click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ThankYou.this, errorMsg);
					}
				}
			});
		} catch(Exception e)
		{
			e.printStackTrace();
			String errorMsg = className+" | onCreate     |   " +e.getMessage();
			Common.sendCrashWithAQuery(ThankYou.this,errorMsg);
		} 
	}
}
