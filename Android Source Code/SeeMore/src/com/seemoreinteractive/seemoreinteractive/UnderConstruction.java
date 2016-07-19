package com.seemoreinteractive.seemoreinteractive;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.TextView;

import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class UnderConstruction extends Activity {
	String className ="UnderConstruction";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_under_construction);
		try{
		new Common().clickingOnBackButtonWithAnimation(UnderConstruction.this, WishListPage.class,"0");
		new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
		ImageView imgvBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
    	/*ViewGroup.LayoutParams iv_params_b = imgvBtnCart.getLayoutParams();
    	iv_params_b.height = 40;
    	iv_params_b.width = 40;
    	imgvBtnCart.setScaleType(ScaleType.FIT_CENTER);
    	imgvBtnCart.setLayoutParams(iv_params_b);*/
		imgvBtnCart.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				//new Common().clickingOnBackButtonWithAnimationWithBackPressed(UnderConstruction.this, ARDisplayActivity.class, "0");
				Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
				startActivity(intent); // Launch the HomescreenActivity
				finish(); 
				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
			}
		});
		//new Common().pageHeaderTitle(UnderConstruction.this, "Friends List");
		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, "ff2600",
				"ff2600",
				"ff2600",
				Common.sessionClientLogo, "Friends List", "");
		
		TextView txtNotAvail = (TextView) findViewById(R.id.txtNotAvail);
		txtNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
		}catch(Exception e){
			e.printStackTrace();
			 String errorMsg = className+" | onCreate      |   " +e.getMessage();
			Common.sendCrashWithAQuery(UnderConstruction.this,errorMsg);
		}
	}



	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    // The rest of your onStart() code.
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart      |   " +e.getMessage();
			Common.sendCrashWithAQuery(UnderConstruction.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		//The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop      |   " +e.getMessage();
			Common.sendCrashWithAQuery(UnderConstruction.this,errorMsg);
		 }
	}
}
