package com.seemoreinteractive.seemoreinteractive;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.ECLAIR)
public class Recommendation extends Activity {
	public boolean isBackPressed = false;
	final Context context = this;
	String className ="Recommendation";
	public Boolean alertErrorType = true;
	String getProductId, getProductName, getProductPrice, getClientLogo, getClientId, getClientBackgroundImage, getClientBackgroundColor;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_recommendation);
		try{
			//new Common().clientBgImgWithColor(Recommendation.this);	
			//new Common().pageHeaderTitle(Recommendation.this, "Recommendation");	
	    	//new Common().clientThemeWithOrWithoutLogo(this);	

			/*new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "Recommendation", "Yes");	*/

			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Recommendation", "");
			
			TextView txtNoRecom = (TextView) findViewById(R.id.txtvNoRecom);
			txtNoRecom.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	

	       
			//String joinedWithPipe = TextUtils.join("|", Common.arrPdIdsForUserAnalytics);
			//Log.i("joined", ""+joinedWithPipe);
			String screenName = "/mycloset/recommendation";
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
				
			/*//Intent getIntVals = getIntent();  
			RelativeLayout bgRelativeLayout = (RelativeLayout) findViewById(R.id.bgRelativeLayout);
			LinearLayout headerLinearLayout = (LinearLayout) findViewById(R.id.headerLinearLayout);
			//LinearLayout footerLinearLayout = (LinearLayout) findViewById(R.id.footerLinearLayout);

			if(!Common.sessionClientBgColor.equals("null")){
				headerLinearLayout.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
				//footerLinearLayout.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
			}
			
			if(Common.sessionClientBgImage.equals("null")){
				bgRelativeLayout.setBackgroundResource(R.drawable.bg_closet);
				//topImgHeadBg.setVisibility(View.VISIBLE);
			} 
			else {
				String selectedClientBackgroundPath = (Constants.Client_Logo_Location
						+ Common.sessionClientId + "/background/" + Common.sessionClientBgImage)
						.toString().replaceAll(" ", "%20");			
		    	new Common().DownloadImageFromUrlInBackgroundDrawable(this, selectedClientBackgroundPath, bgRelativeLayout);	
		    	
			}
			*/
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					 Intent returnIntent = new Intent(Recommendation.this, Closet.class);
					 returnIntent.putExtra("instruction_type","0");
					// Closing all the Activities
					 //returnIntent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);			
					// Add new Flag to start new Activity
					 //returnIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
					 startActivity(returnIntent);
					 finish();
					 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				}
			});

			//new Common().clickingOnBackButtonWithAnimation(Recommendation.this, Closet.class,"0");
			
			/*ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			int resID = getResources().getIdentifier("camera_icon", "drawable",  getPackageName());
			imgBtnCart.setImageResource(resID);*/
			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_2, R.id.imgvBtnCart);

			ImageView imgvBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgvBtnCart.setImageBitmap(null);
	    	/*ViewGroup.LayoutParams iv_params_b = imgvBtnCart.getLayoutParams();
	    	iv_params_b.height = 40;
	    	iv_params_b.width = 40;
	    	imgvBtnCart.setScaleType(ScaleType.FIT_CENTER);
	    	imgvBtnCart.setLayoutParams(iv_params_b);*/
			imgvBtnCart.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					//Log.i("viewAnimator1", ""+viewAnimator.getDisplayedChild());
					/*Intent intent = new Intent(Recommendation.this, ARDisplayActivity.class);	
					startActivityForResult(intent, 1);
					finish();
					overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);*/
				}
			});

		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: Recommendation onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +e.getMessage();
			Common.sendCrashWithAQuery(Recommendation.this,errorMsg);
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
    	} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: Recommendation onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown    |   " +e.getMessage();
			Common.sendCrashWithAQuery(Recommendation.this,errorMsg);
			return false;
		}
    }
    
    @Override
	public void onBackPressed() {
    	try{
    		Intent intent;
			intent = new Intent(getApplicationContext(), Closet.class);		
        	startActivity(intent);
        	finish();
    	} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: Recommendation onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed    |   " +e.getMessage();
			Common.sendCrashWithAQuery(Recommendation.this,errorMsg);
		}
    }
	 @Override
	public void onStart() {
		try{
	    super.onStart();
	    Tracker easyTracker = EasyTracker.getInstance(this);
		easyTracker.set(Fields.SCREEN_NAME, "/mycloset/recommendation");
		easyTracker.send(MapBuilder
		    .createAppView()
		    .build()
		);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onStart    |   " +e.getMessage();
			Common.sendCrashWithAQuery(Recommendation.this,errorMsg);
		}
	}
	 @Override
	public void onStop() {
		 try{
			 super.onStop();
			 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop    |   " +e.getMessage();
			Common.sendCrashWithAQuery(Recommendation.this,errorMsg);
		 }
	}
}
