package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
public class ForgotPasswordActivity extends Activity {

	EditText etxtEmailId;	
	ArrayList<String> userArrayList;
	String[] userArray;
	AQuery aq = new AQuery(ForgotPasswordActivity.this);
	ProgressBar progress;
	Button   btnForgotPwd;
	String checkLoginFlag, stringArrayList2;
	ArrayList<String> offerStringArrayListValues;
	Intent loginIntent;
	SessionManager session;
	String className ="ForgotPasswordActivity";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_forgot_password);
		
		try{			
			
			offerStringArrayListValues = new ArrayList<String>();
			Intent intent = getIntent();
			if(intent.getExtras()!=null){
				checkLoginFlag = intent.getStringExtra("checkLoginFlag");
				stringArrayList2 = intent.getStringExtra("stringArrayList2");
				offerStringArrayListValues = intent.getStringArrayListExtra("offerStringArrayListValues");
			}
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "SeeMore Login", "");
			new Common().showDrawableImageFromAquery(this, R.drawable.closelogin, R.id.imgvBtnCart);

			ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), LoginActivity.class);
						intent.putExtra("checkLoginFlag", checkLoginFlag);						
						intent.putExtra("stringArrayList2", stringArrayList2);
						if(checkLoginFlag.equals("OfferCalendarMyOffers")){
							intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
						}
						finish();
						startActivity(intent);
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: SeeMore Registration imgvBtnBack.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | onCreate   imgvBtnBack click |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(ForgotPasswordActivity.this,errorMsg);
					}
				}
			});
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);    
			imgBtnCart.setImageAlpha(0);
	    	imgBtnCart.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error:  imgBtnCart.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | onCreate   imgBtnCart click |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(ForgotPasswordActivity.this,errorMsg);
					}
				}
			});
	    	
	    	
	    	
	    	int txtSize = (int) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			int lrMargin= (int) (0.042 * Common.sessionDeviceWidth);
			int tbMargin= (int) (0.017 * Common.sessionDeviceWidth);
			int btnRegTopMargin= (int) (0.080 * Common.sessionDeviceWidth/Common.sessionDeviceDensity);
			int btnRegWidth= (int) (0.5 * Common.sessionDeviceWidth);
			int btnRegHeight= (int) (0.0656 * Common.sessionDeviceHeight);
			int padding = (int) (0.042 * Common.sessionDeviceWidth/Common.sessionDeviceDensity);
			
			
			etxtEmailId = (EditText) findViewById(R.id.etxtEmailId);
			etxtEmailId.setTextSize(txtSize); 
			etxtEmailId.setPadding(padding,padding, padding, padding);
			RelativeLayout.LayoutParams rlpEmailId = (RelativeLayout.LayoutParams) etxtEmailId.getLayoutParams();			
			rlpEmailId.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			etxtEmailId.setLayoutParams(rlpEmailId);
			
			
			btnForgotPwd = (Button) findViewById(R.id.btnForgotPwd);
			RelativeLayout.LayoutParams rlpForgotPwd = (RelativeLayout.LayoutParams) btnForgotPwd.getLayoutParams();		
			rlpForgotPwd.width = btnRegWidth;
			rlpForgotPwd.height = btnRegHeight;
			rlpForgotPwd.setMargins(lrMargin, btnRegTopMargin, lrMargin, tbMargin);
			btnForgotPwd.setLayoutParams(rlpForgotPwd);
			
			btnForgotPwd.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
					
					if(etxtEmailId.getText().toString().equals("")){
						etxtEmailId.setError("Please enter email id.");
						etxtEmailId.requestFocus();						
						Toast.makeText(getApplicationContext(), "Please enter email id.", Toast.LENGTH_LONG).show();
						return;
					}
					else {
						/*progress = (ProgressBar) findViewById(R.id.registerProgressBar);
						progress.setVisibility(View.VISIBLE);*/
						btnForgotPwd.setEnabled(false);
						session = new SessionManager(ForgotPasswordActivity.this);
						final Map<String, String> loginParams = new HashMap<String, String>();
						loginParams.put("username", etxtEmailId.getText().toString());	
				        aq.ajax(Constants.forgotPasswordURL, loginParams, XmlDom.class, new AjaxCallback<XmlDom>(){
				        	@Override
							public void callback(String url, XmlDom xml, AjaxStatus status) {
				        		try{
				        			if(xml!=null){
				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
				        				Log.i("xmlMsg",""+xmlMsg);
				        				for(XmlDom xmlMsg1 : xmlMsg){
					        				if(xmlMsg1.text("msg").toString().equals("success")){
					        					finish();
					        					Intent intent = new Intent(getApplicationContext(),LoginActivity.class);
					        					intent.putExtra("checkLoginFlag", checkLoginFlag);						
					        					intent.putExtra("stringArrayList2", stringArrayList2);
					        					if(checkLoginFlag.equals("OfferCalendarMyOffers")){
					        						intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
					        					}
					        					startActivity(intent);
					        				}
				        				}
				        			}
				        		}catch(Exception e){
				        			e.printStackTrace();
				        			String errorMsg = className+" | onCreate  forgotPasswordURL ajax |   " +e.getMessage();
				               	 	Common.sendCrashWithAQuery(ForgotPasswordActivity.this,errorMsg);
				        		}
				        	}
				        });
					}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate   btnForgotPwd click |   " +e.getMessage();
               	 	Common.sendCrashWithAQuery(ForgotPasswordActivity.this,errorMsg);
				}
				}
			});
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ForgotPasswordActivity.this,errorMsg);
		}
	}
}
