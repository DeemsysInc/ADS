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
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class RegistrationActivity extends Activity {
	
	EditText etxtFirstName,etxtLastName,etxtEmailId,etxtPwd,etxtCPwd;
	CheckBox chkTerms;
	public boolean alertErrorType = true;
	String checkLoginFlag, stringArrayList2;
	ArrayList<String> offerStringArrayListValues;
	Intent loginIntent;
	SessionManager session;
	ArrayList<String> userArrayList;
	String[] userArray;
	AQuery aq = new AQuery(RegistrationActivity.this);
	ProgressBar progress;
	Button  btnRegister;
	String className = this.getClass().getSimpleName();
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN) @Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_registration_form);		
		try{			
			offerStringArrayListValues = new ArrayList<String>();
			Intent intent = getIntent();
			if(intent.getExtras()!=null){
				checkLoginFlag = intent.getStringExtra("checkLoginFlag");
				stringArrayList2 = intent.getStringExtra("stringArrayList2");
				offerStringArrayListValues = intent.getStringArrayListExtra("offerStringArrayListValues");
			}
			//new Common().pageHeaderTitle(RegistrationActivity.this, "SeeMore Registration");
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "SeeMore Registration", "");
			/*new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "SeeMore Registration", "");*/
			
			new Common().showDrawableImageFromAquery(this, R.drawable.closelogin, R.id.imgvBtnCart);

			ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), AccountLaunchActivity.class);
						intent.putExtra("checkLoginFlag", checkLoginFlag);						
						intent.putExtra("stringArrayList2", stringArrayList2);
						if(checkLoginFlag.equals("OfferCalendarMyOffers")){
							intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
						}
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnBack    |   " +e.getMessage();
						Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
						Toast.makeText(getApplicationContext(), "Error: SeeMore Registration imgvBtnBack.", Toast.LENGTH_LONG).show();
					}
				}
			});
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);    
			imgBtnCart.setImageAlpha(0);
	    	/*imgBtnCart.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						Intent intent = new Intent(getApplicationContext(), AccountLaunchActivity.class);
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: SeeMore Registration imgBtnCart.", Toast.LENGTH_LONG).show();
					}
				}
			});*/
			
			

			String screenName = "/registration";
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, "", screenName, productIds, offerIds);	
			
			int txtSize = (int) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			int lrMargin= (int) (0.042 * Common.sessionDeviceWidth);
			int tbMargin= (int) (0.017 * Common.sessionDeviceWidth);
			int btnRegTopMargin= (int) (0.080 * Common.sessionDeviceWidth/Common.sessionDeviceDensity);
			int btnRegWidth= (int) (0.5 * Common.sessionDeviceWidth);
			int btnRegHeight= (int) (0.0656 * Common.sessionDeviceHeight);
			int padding = (int) (0.042 * Common.sessionDeviceWidth/Common.sessionDeviceDensity);
			int lblTopMargin = (int) (0.0462 * Common.sessionDeviceHeight/Common.sessionDeviceDensity);
			int lblBottomMargin = (int) (0.0205 * Common.sessionDeviceHeight/Common.sessionDeviceDensity);
			
			TextView lblAccInfo = (TextView) findViewById(R.id.lblAccInfo);
			lblAccInfo.setTextSize(txtSize);
			RelativeLayout.LayoutParams rlpLblAccInfo = (RelativeLayout.LayoutParams) lblAccInfo.getLayoutParams();			
			rlpLblAccInfo.topMargin = lblTopMargin;
			rlpLblAccInfo.bottomMargin = lblBottomMargin;
			lblAccInfo.setLayoutParams(rlpLblAccInfo);
			
			etxtFirstName = (EditText) findViewById(R.id.etxtFirstName);
			etxtFirstName.setTextSize(txtSize);
			etxtFirstName.setPadding(padding,padding, padding, padding);
			RelativeLayout.LayoutParams rlpFirstName = (RelativeLayout.LayoutParams) etxtFirstName.getLayoutParams();			
			rlpFirstName.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			etxtFirstName.setLayoutParams(rlpFirstName);
			
	        
			etxtLastName = (EditText) findViewById(R.id.etxtLastName);
			etxtLastName.setTextSize(txtSize); 
			etxtLastName.setPadding(padding,padding, padding, padding);
			RelativeLayout.LayoutParams rlpLastName = (RelativeLayout.LayoutParams) etxtLastName.getLayoutParams();			
			rlpLastName.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			etxtLastName.setLayoutParams(rlpLastName);
			
			etxtEmailId = (EditText) findViewById(R.id.etxtEmailId);
			etxtEmailId.setTextSize(txtSize); 
			etxtEmailId.setPadding(padding,padding, padding, padding);
			RelativeLayout.LayoutParams rlpEmailId = (RelativeLayout.LayoutParams) etxtEmailId.getLayoutParams();			
			rlpEmailId.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			etxtEmailId.setLayoutParams(rlpEmailId);
			
			etxtPwd = (EditText) findViewById(R.id.etxtPwd);
			etxtPwd.setTextSize(txtSize); 
			etxtPwd.setPadding(padding,padding, padding, padding);
			RelativeLayout.LayoutParams rlpPwd = (RelativeLayout.LayoutParams) etxtPwd.getLayoutParams();			
			rlpPwd.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			etxtPwd.setLayoutParams(rlpPwd);
			
			etxtCPwd = (EditText) findViewById(R.id.etxtCPwd);
			etxtCPwd.setTextSize(txtSize); 
			etxtCPwd.setPadding(padding,padding, padding, padding);
			RelativeLayout.LayoutParams rlpCPwd = (RelativeLayout.LayoutParams) etxtCPwd.getLayoutParams();			
			rlpCPwd.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			etxtCPwd.setLayoutParams(rlpCPwd);
			
			
		/*	etxtCPwd.setOnFocusChangeListener(new OnFocusChangeListener() {
				
				@Override
				public void onFocusChange(View v, boolean hasFocus) {
					// TODO Auto-generated method stub
					
					if(!etxtPwd.getText().toString().equals("") && !etxtCPwd.getText().toString().equals("")){
						if(etxtPwd.getText().toString().equals(etxtCPwd.getText().toString())){							
							etxtCPwd.setError(null);
						}
					}
				}
			});
			*/
			TextView  txtTerms = (TextView) findViewById(R.id.txtTerms);
			txtTerms.setTextSize(txtSize);
			//RelativeLayout.LayoutParams rlpTerms = (RelativeLayout.LayoutParams) txtTerms.getLayoutParams();			
			//rlpTerms.topMargin = margin;
			//txtTerms.setLayoutParams(rlpTerms);
			TextView  txtAgreeTo = (TextView) findViewById(R.id.txtAgreeTo);
			RelativeLayout.LayoutParams rlpTxtAgreeTo = (RelativeLayout.LayoutParams) txtAgreeTo.getLayoutParams();			
			//rlpTxtAgreeTo.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			txtAgreeTo.setLayoutParams(rlpTxtAgreeTo);
			
			chkTerms = (CheckBox) findViewById(R.id.chkTermConds);
			chkTerms.setTextSize(txtSize);
			RelativeLayout.LayoutParams rlpChkTerms = (RelativeLayout.LayoutParams) chkTerms.getLayoutParams();			
			rlpChkTerms.leftMargin = lrMargin;
			//rlpChkTerms.width = (int) (0.117 * Common.sessionDeviceWidth);
			//rlpChkTerms.height = (int) (0.072 * Common.sessionDeviceHeight);
			chkTerms.setLayoutParams(rlpChkTerms);
			chkTerms.setOnCheckedChangeListener(new OnCheckedChangeListener() {				
				@Override
				public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
					// TODO Auto-generated method stub
					if(isChecked){
						chkTerms.setButtonDrawable(R.drawable.checkbox_checked);
					} else{
						chkTerms.setButtonDrawable(R.drawable.checkbox);						
					}
				}
			});
			
			btnRegister = (Button) findViewById(R.id.btnRegister);
			RelativeLayout.LayoutParams rlpRegister = (RelativeLayout.LayoutParams) btnRegister.getLayoutParams();		
			rlpRegister.width = btnRegWidth;
			rlpRegister.height = btnRegHeight;
			rlpRegister.setMargins(lrMargin, btnRegTopMargin, lrMargin, tbMargin);
			btnRegister.setLayoutParams(rlpRegister);
			
			
			/*if(!chkTerms.isChecked()){				
				btnRegister.setEnabled(false);
			}else{
				btnRegister.setEnabled(true);
			}*/
			
			txtTerms.setOnClickListener(new View.OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
					Intent intent = new Intent(getApplicationContext(), OptionsTermsConditions.class);
					startActivity(intent);
					//alertDialog.cancel();
					finish();
					overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | txtTerms click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
					}
				}
			});
			
			
			btnRegister.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					Log.e("url",Constants.registerURL);
					
					if(etxtFirstName.getText().toString().equals("")){
						etxtFirstName.setError("Please enter firstname.");
						etxtFirstName.requestFocus();
						alertErrorType = false;
						Toast.makeText(getApplicationContext(), "Please enter firstname.", Toast.LENGTH_LONG).show();
						return;
					}
					if(etxtLastName.getText().toString().equals("")){
						etxtLastName.setError("Please enter lastname.");
						etxtLastName.requestFocus();
						alertErrorType = false;
						Toast.makeText(getApplicationContext(), "Please enter lastname.", Toast.LENGTH_LONG).show();
						return;
					}
					
					if(etxtEmailId.getText().toString().equals("")){
						etxtEmailId.setError("Please enter email id.");
						etxtEmailId.requestFocus();
						alertErrorType = false;
						Toast.makeText(getApplicationContext(), "Please enter email id.", Toast.LENGTH_LONG).show();
						return;
					}
					String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";  				   
 					if(!(etxtEmailId.getText().toString().matches(emailPattern))){
						etxtEmailId.setError("Invalid email id!");
						etxtEmailId.requestFocus();
						Toast.makeText(getApplicationContext(), "Invalid email id!", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}
					if(etxtPwd.getText().toString().equals("")){
						etxtPwd.setError("Please enter password.");
						etxtPwd.requestFocus();
						Toast.makeText(getApplicationContext(), "Please enter password.", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}
					if(etxtPwd.getText().toString().length()<4){
						etxtPwd.setError("Password should not be less than 4 characters.");
						etxtPwd.requestFocus();
						Toast.makeText(getApplicationContext(), "Password should not be less than 4 characters.", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}
					if(etxtCPwd.getText().toString().equals("")){
						etxtCPwd.setError("Please enter confirm password.");
						etxtCPwd.requestFocus();
						Toast.makeText(getApplicationContext(), "Please enter confirm password.", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}
					if(!etxtPwd.getText().toString().equals(etxtCPwd.getText().toString())){
						etxtCPwd.setError("Both passwords are not same!");
						etxtCPwd.requestFocus();
						Toast.makeText(getApplicationContext(), "Both passwords are not same!", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}
					if(!chkTerms.isChecked()){
						chkTerms.setError("Please check terms and conditions.");
						chkTerms.requestFocus();
						Toast.makeText(getApplicationContext(), "Please check terms and conditions.", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}
					else {
						progress = (ProgressBar) findViewById(R.id.registerProgressBar);
						RelativeLayout.LayoutParams rlpProgressBar = (RelativeLayout.LayoutParams) progress.getLayoutParams();
						rlpProgressBar.topMargin = (int) (0.026 * Common.sessionDeviceHeight);
						rlpProgressBar.rightMargin = (int) (0.042 * Common.sessionDeviceWidth);
						rlpProgressBar.bottomMargin = (int) (0.026 * Common.sessionDeviceHeight);
						rlpProgressBar.leftMargin = (int) (0.042 * Common.sessionDeviceWidth);		
						progress.setLayoutParams(rlpProgressBar);						
						progress.setVisibility(View.VISIBLE);
						btnRegister.setEnabled(false);
						final Map<String, String> regParams = new HashMap<String, String>();
						regParams.put("first_name", etxtFirstName.getText().toString());
						regParams.put("last_name", etxtLastName.getText().toString());
						regParams.put("username", etxtEmailId.getText().toString());
						regParams.put("email_id", etxtEmailId.getText().toString());
						regParams.put("password", etxtPwd.getText().toString());
						regParams.put("register_through", "0");

						session = new SessionManager(RegistrationActivity.this);
				        aq.ajax(Constants.registerURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
				        	@Override
							public void callback(String url, XmlDom xml, AjaxStatus status) {
				        		try{
				        			if(xml!=null){
				        				alertErrorType = true;
				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
				        				for(XmlDom xmlMsg1 : xmlMsg){
				        					if(xmlMsg1.text("msg").toString().equals("already")){
					        					Toast.makeText(getApplicationContext(), "Already registered.", Toast.LENGTH_LONG).show();
					    						btnRegister.setEnabled(true);
					        					progress.setVisibility(View.INVISIBLE);
					        				} else if(xmlMsg1.text("msg").toString().equals("success")){
					        					//Toast.makeText(activityThis, "Successfully registered.", Toast.LENGTH_LONG).show();
					        					final Map<String, String> loginParams = new HashMap<String, String>();
												loginParams.put("username", etxtEmailId.getText().toString());
												loginParams.put("password", etxtPwd.getText().toString());
												loginParams.put("register_through", "0");
												loginParams.put("email_id", etxtEmailId.getText().toString());

										        Log.e("Constants.loginURL",""+Constants.loginURL+" "+etxtFirstName.getText().toString()+" "+etxtPwd.getText().toString());
										        aq.ajax(Constants.loginURL, loginParams, XmlDom.class, new AjaxCallback<XmlDom>(){
										        	@Override
													public void callback(String url, XmlDom xml, AjaxStatus status) {
										        		try{
										        			if(xml!=null){
														        Log.e("Constants.loginURL",""+url);
										        				List<XmlDom> xmlMsg = xml.tags("resultXml");
										        				for(XmlDom xmlMsg1 : xmlMsg){
											        				if(xmlMsg1.text("msg").toString().equals("success")){
																		btnRegister.setEnabled(true);
											        					progress.setVisibility(View.INVISIBLE);
											        					userArrayList = new ArrayList<String>();
											        					userArrayList.add(xmlMsg1.text("id").toString());
											        					userArrayList.add(xmlMsg1.text("username").toString());
											        					userArrayList.add(xmlMsg1.text("email_id").toString());
											        					userArrayList.add(xmlMsg1.text("user_firstname").toString());
											        					userArrayList.add(xmlMsg1.text("user_lastname").toString());		
																		userArrayList.add(xmlMsg1.text("user_group_id").toString());		
																		userArrayList.add(etxtPwd.getText().toString());						        					
																		userArray = userArrayList.toArray(new String[userArrayList.size()]);
																		
											        					session.createLoginSession(userArray);
											        					boolean resultForUser = new Common().getStoredUserSessionDetails(RegistrationActivity.this, checkLoginFlag, stringArrayList2);
											        					new Constants();
											        					Log.i("checkLoginFlag",checkLoginFlag);
																		if(checkLoginFlag.equals("OfferViewMyOffers")){
																			new OfferViewActivity().insertOfferToMyOffersDbUsingXml(
																					Constants.MyOffers_Url+"insert_offers/"+xmlMsg1.text("id").toString()+"/"+stringArrayList2+"/", RegistrationActivity.this,"Register");
																			loginIntent = new Intent(RegistrationActivity.this, MyOffers.class);
																		}else if(checkLoginFlag.equals("MyOffers")){
																			loginIntent = new Intent(RegistrationActivity.this, MyOffers.class);
																		}else if(checkLoginFlag.equals("OfferCalendarMyOffers")){
																			Log.i("offerStringArrayListValues", ""+offerStringArrayListValues);																		
								    										new OfferCalendarActivity().saveAndSetCalendarReminders(xmlMsg1.text("id").toString(), "Yes", offerStringArrayListValues);
								    										loginIntent = new Intent(RegistrationActivity.this, MyOffers.class);
								    										
																		}
																		else{
																			loginIntent = new Intent(RegistrationActivity.this, Closet.class);
																		}
																				
																		String xmlAjaxUrl = Constants.WishListYourCloset_Url+xmlMsg1.text("id").toString()+"/";
																		Log.i("xmlAjaxUrl",xmlAjaxUrl);
																		new Common().getYourWishListTableWithXml(xmlAjaxUrl, RegistrationActivity.this);
																		try {
																			Thread.sleep(1000);
																		} catch (InterruptedException e) {
																			// TODO Auto-generated catch block
																			e.printStackTrace();
																		}
																		Log.i("afterxmlAjaxUrl", xmlAjaxUrl);
																		//FacebookActivity.this.finish();
																		//Intent intent = new Intent(FacebookActivity.this, ARDisplayActivity.class);
																		loginIntent.putExtra("productId", Common.sessionProductId);
																		loginIntent.putExtra("productName", Common.sessionProductName);
																		loginIntent.putExtra("productPrice", Common.sessionProductPrice);
																		loginIntent.putExtra("productShortDesc", Common.sessionProductShortDesc);
																		loginIntent.putExtra("clientLogo", Common.sessionClientLogo);
																		loginIntent.putExtra("clientId", Common.sessionClientId);
																		loginIntent.putExtra("clientBackgroundImage", Common.sessionClientBgImage);
																		loginIntent.putExtra("clientImageName", Common.sessionClientName);
																		loginIntent.putExtra("clientBackgroundColor", Common.sessionClientBgColor);
																		// Closing all the Activities
																		//intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);			
																		// Add new Flag to start new Activity
																		//intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
																		RegistrationActivity.this.finish();
																		startActivityForResult(loginIntent, 1);
																		overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
											        				} else {
											    						Intent intent = new Intent(getApplicationContext(), AccountLaunchActivity.class);
											    						intent.putExtra("checkLoginFlag", checkLoginFlag);						
											    						intent.putExtra("stringArrayList2", stringArrayList2);
											    						if(checkLoginFlag.equals("OfferCalendarMyOffers")){
											    							intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
											    						}
											    						startActivity(intent);
											    						finish();
											    						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
																		Toast.makeText(RegistrationActivity.this, "Login failed. Please enter valid username and password.",
																				Toast.LENGTH_LONG).show();
																		btnRegister.setEnabled(true);
											        				}
										        				}
										        				//Log.i("xml", xml.tag("msg").toString()+" "+xml+" "+url+" "+loginParams);
										        			}
										        		} catch(Exception e){
										        			e.printStackTrace();
										        			String errorMsg = className+" | btnRegister callback   |   " +e.getMessage();
															Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
										        		}
										        	}
										        });
					        				} else {
												Toast.makeText(getApplicationContext(), "Registration failed. Please try agian!", Toast.LENGTH_LONG).show();
												btnRegister.setEnabled(true);
											}
				        				}
				        			}
				        		} catch(Exception e){
				        			e.printStackTrace();
				        			String errorMsg = className+" | btnRegister click   |   " +e.getMessage();
									Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
				        		}
				        	}
				        });
					}
				}
			});

			
			
		
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +e.getMessage();
			Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
		}
	}
	
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(RegistrationActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(RegistrationActivity.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(RegistrationActivity.this,errorMsg);
			}
		}
}
