package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
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
import com.seemoreinteractive.seemoreinteractive.Model.ClientStores;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.ProfileModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.Stores;
import com.seemoreinteractive.seemoreinteractive.Model.UserMyOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserProfile;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
public class LoginActivity extends Activity {	
	
	EditText etxtEmailId,etxtPwd;
	public boolean alertErrorType = true;
	String checkLoginFlag, stringArrayList2;
	ArrayList<String> offerStringArrayListValues;
	Intent loginIntent;
	SessionManager session;
	ArrayList<String> userArrayList;
	String[] userArray;
	AQuery aq = new AQuery(LoginActivity.this);
	ProgressBar progress;
	Button  btnLogin, btnForgotPwd;
	TextView errorMsg;
	FileTransaction file;
	Common commonForStored;
	String className = this.getClass().getSimpleName();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		try{
			progress = (ProgressBar) findViewById(R.id.registerProgressBar);
			RelativeLayout.LayoutParams rlpProgressBar = (RelativeLayout.LayoutParams) progress.getLayoutParams();
			rlpProgressBar.topMargin = (int) (0.026 * Common.sessionDeviceHeight);
			rlpProgressBar.rightMargin = (int) (0.042 * Common.sessionDeviceWidth);
			rlpProgressBar.bottomMargin = (int) (0.026 * Common.sessionDeviceHeight);
			rlpProgressBar.leftMargin = (int) (0.042 * Common.sessionDeviceWidth);		
			progress.setLayoutParams(rlpProgressBar);
			
			commonForStored = new Common();
			offerStringArrayListValues = new ArrayList<String>();
			Intent intent = getIntent();
			if(intent.getExtras()!=null){
				checkLoginFlag = intent.getStringExtra("checkLoginFlag");
				stringArrayList2 = intent.getStringExtra("stringArrayList2");
				offerStringArrayListValues = intent.getStringArrayListExtra("offerStringArrayListValues");
			}
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "SeeMore Login", "");
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
						String errorMsg = className+" | onCreate   imgvBtnBack |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
					}
				}
			});
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);    
			imgBtnCart.setImageAlpha(0);
	    	imgBtnCart.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), AccountLaunchActivity.class);
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						e.printStackTrace();	
						String errorMsg = className+" | onCreate   imgBtnCart |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
					}
				}
			});
			String screenName = "/login";
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
			
			errorMsg = (TextView) findViewById(R.id.errorMsg);
			errorMsg.setTextSize(txtSize); 
			RelativeLayout.LayoutParams rlpMsg = (RelativeLayout.LayoutParams) errorMsg.getLayoutParams();			
			rlpMsg.setMargins(lrMargin, tbMargin, lrMargin, tbMargin);
			errorMsg.setLayoutParams(rlpMsg);
			
			
			btnLogin = (Button) findViewById(R.id.btnLogin);
			RelativeLayout.LayoutParams rlpLogin = (RelativeLayout.LayoutParams) btnLogin.getLayoutParams();		
			rlpLogin.width = btnRegWidth;
			rlpLogin.height = btnRegHeight;
			rlpLogin.setMargins(lrMargin, btnRegTopMargin, lrMargin, tbMargin);
			btnLogin.setLayoutParams(rlpLogin);
			
			
			btnForgotPwd = (Button) findViewById(R.id.btnForgotPwd);
			RelativeLayout.LayoutParams rlpForgotPwd = (RelativeLayout.LayoutParams) btnForgotPwd.getLayoutParams();		
			rlpForgotPwd.width = btnRegWidth;
			rlpForgotPwd.height = btnRegHeight;
			rlpForgotPwd.setMargins(lrMargin, btnRegTopMargin, lrMargin, tbMargin);
			btnForgotPwd.setLayoutParams(rlpForgotPwd);
					
			btnLogin.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					if(etxtEmailId.getText().toString().equals("")){
						etxtEmailId.setError("Please enter email id.");
						etxtEmailId.requestFocus();
						alertErrorType = false;
						Toast.makeText(getApplicationContext(), "Please enter email id.", Toast.LENGTH_LONG).show();
						return;
					}
					if(etxtPwd.getText().toString().equals("")){
						etxtPwd.setError("Please enter password.");
						etxtPwd.requestFocus();
						Toast.makeText(getApplicationContext(), "Please enter password.", Toast.LENGTH_LONG).show();
						alertErrorType = false;
						return;
					}					
					else {
											
						errorMsg.setText("");
						session = new SessionManager(LoginActivity.this);
						final Map<String, String> loginParams = new HashMap<String, String>();
						loginParams.put("username", etxtEmailId.getText().toString());
						loginParams.put("password", etxtPwd.getText().toString());
						loginParams.put("register_through", "0");
						loginParams.put("email_id", etxtEmailId.getText().toString());
				        Log.i("loginParams",""+loginParams);
				        aq.progress(R.id.registerProgressBar).ajax(Constants.loginURL, loginParams, XmlDom.class, new AjaxCallback<XmlDom>(){
				        	@Override
							public void callback(String url, XmlDom xml, AjaxStatus status) {
				        		try{
				        			if(xml!=null){
								        Log.i("loginParams url",""+url);
				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
				        				final ProfileModel profile = new ProfileModel();
				        				 Log.i("xmlMsg ",""+xmlMsg);
				        				
				        				for(XmlDom xmlMsg1 : xmlMsg){
					        				if(xmlMsg1.text("msg").toString().equals("success")){
					        					//progress.setVisibility(View.INVISIBLE);
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
					        					boolean resultForUser = commonForStored.getStoredUserSessionDetails(LoginActivity.this, checkLoginFlag, stringArrayList2);
					        					Log.e("resultForUser", ""+resultForUser);
					        					new Constants();
					        						
        				        					UserProfile userProfile = new UserProfile();
        				        					userProfile.setId(Long.parseLong(xmlMsg1.text("user_details_id").toString()));
        				        					userProfile.setFirstName(xmlMsg1.text("user_firstname").toString());
        				        					userProfile.setLastname(xmlMsg1.text("user_lastname").toString());
        				        					userProfile.setGender(xmlMsg1.text("user_details_gender").toString());
        				        					userProfile.setDateofBirth(xmlMsg1.text("user_details_dob").toString());
        				        					userProfile.setPhone(xmlMsg1.text("user_details_phone").toString());
        				        					userProfile.setEmail(xmlMsg1.text("email_id").toString());
        				        					userProfile.setAddress1(xmlMsg1.text("user_details_address1").toString());
        				        					userProfile.setAddress2(xmlMsg1.text("user_details_address2").toString());
        				        					userProfile.setCity(xmlMsg1.text("user_details_city").toString());
        				        					userProfile.setState(xmlMsg1.text("user_details_state").toString());
        				        					userProfile.setCountry(xmlMsg1.text("user_details_country").toString());
        				        					userProfile.setZip(xmlMsg1.text("user_details_zip").toString());
        				        					profile.add(userProfile);    
        				        					file = new FileTransaction();
        				        					file.setProfile(profile);
        				        				
					        				} else {
												Toast.makeText(LoginActivity.this, "Login failed. Please enter valid username and password.",
														Toast.LENGTH_LONG).show();
												errorMsg.setText("Login failed. Please enter valid username and password.");
					        				}
				        				}
				        			}
				        		} catch(Exception e){
				        			e.printStackTrace();
				        			String errorMsg = className+" | onCreate   ajax call |   " +e.getMessage();
				               	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
				        		}
				        	}
				        });
    				}	
				}
			});
			
			btnForgotPwd.setOnClickListener(new OnClickListener() {							
				@Override
				public void onClick(View v) {
					try{
					Intent intent = new Intent(getApplicationContext(),ForgotPasswordActivity.class);
					intent.putExtra("checkLoginFlag", checkLoginFlag);						
					intent.putExtra("stringArrayList2", stringArrayList2);
					if(checkLoginFlag.equals("OfferCalendarMyOffers")){
						intent.putStringArrayListExtra("offerStringArrayListValues", offerStringArrayListValues);
					}
					finish();
					startActivity(intent);
					overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   btnForgotPwd click |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
					}
				}
			});
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
		}
	}
	
	public void getUserOffersDetails(String userId,String checkLoginFlag1,String stringArrayList, final Activity activityThis){
		try{
			file = new FileTransaction();
			final String getOfferUrl = Constants.MyOffers_Url+"recent/"+userId+"/";
			final String loggedInUser = userId;
			checkLoginFlag = checkLoginFlag1;
			stringArrayList2 = stringArrayList;
			Log.i("getOfferUrl", ""+getOfferUrl);
			aq = new AQuery(activityThis);
			aq.progress(R.id.registerProgressBar).ajax(getOfferUrl, XmlDom.class, new AjaxCallback<XmlDom>(){
				@Override
				public void callback(String url, XmlDom xml, AjaxStatus status){
				  	try {
				  		Log.i("url", ""+url+" "+status);
				  		if(xml!=null){
						    final List<XmlDom> myOffers = xml.tags("myOffers");
						    Log.i("myOffers", ""+myOffers+" ");
						    if(myOffers.size()>0){	    
						    	Offers offers = new Offers();	
						    	Offers offersModel = new Offers();
							    
						    	String clientIds ="";
						    	ArrayList<String>  clientIdsList= new ArrayList<String>();
						    	int j = 0;
							    for(final XmlDom myOfferXml : myOffers){
							    	try {
									     
										if(myOfferXml.tag("offer_id")!=null){

											UserMyOffers usermyOffers= new UserMyOffers();
											usermyOffers.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));
											usermyOffers.setOfferClientId(myOfferXml.text("client_id").toString());
											usermyOffers.setOfferClientName(myOfferXml.text("name").toString());
											usermyOffers.setOfferName(myOfferXml.text("offer_name").toString());			
											if(myOfferXml.text("offer_discount_type").toString().equals("A")){
											   	String symbol = new Common().getCurrencySymbol(myOfferXml.text("country_languages").toString(), myOfferXml.text("country_code_char2").toString());
											   	usermyOffers.setCurrencySymbol(symbol);
												if (myOfferXml.text("offer_discount_value").toString().equals("null") || 
														myOfferXml.text("offer_discount_value").toString().equals("") || 
														myOfferXml.text("offer_discount_value").toString().equals("0") || 
														myOfferXml.text("offer_discount_value").toString().equals("0.00") || 
														myOfferXml.text("offer_discount_value").toString() == null) {
													usermyOffers.setOfferDiscountValue("0");
												} else {
													//userOffer.setOfferDiscountValue(symbol+myOfferXml.text("offer_discount_value").toString());
													usermyOffers.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
												}
										   	} else {
										   		usermyOffers.setCurrencySymbol("");
										   		usermyOffers.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
										   	}
										   	
											usermyOffers.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
											usermyOffers.setOfferDiscountType(myOfferXml.text("offer_discount_type").toString());
											   
											offersModel.addUserMyOffers(usermyOffers);
			                    			
											   	UserOffers userOffer = new UserOffers();
											   	userOffer.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));										   	
											   	userOffer.setOfferImage(myOfferXml.text("offer_image").toString().replaceAll(" ", "%20"));
											   	userOffer.setOfferClientName(myOfferXml.text("name").toString());
											   	userOffer.setOfferName(myOfferXml.text("offer_name").toString());
											   	if(myOfferXml.text("offer_discount_type").toString().equals("A")){
												   	String symbol = new Common().getCurrencySymbol(myOfferXml.text("country_languages").toString(), myOfferXml.text("country_code_char2").toString());
												   	userOffer.setCurrencySymbol(symbol);
													if (myOfferXml.text("offer_discount_value").toString().equals("null") || 
															myOfferXml.text("offer_discount_value").toString().equals("") || 
															myOfferXml.text("offer_discount_value").toString().equals("0") || 
															myOfferXml.text("offer_discount_value").toString().equals("0.00") || 
															myOfferXml.text("offer_discount_value").toString() == null) {
														userOffer.setOfferDiscountValue("0");
													} else {
														//userOffer.setOfferDiscountValue(symbol+myOfferXml.text("offer_discount_value").toString());
														userOffer.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
													}
											   	} else {
											   		userOffer.setCurrencySymbol("");
											   		userOffer.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
											   	}
											   	
											   	userOffer.setOfferDiscountType(myOfferXml.text("offer_discount_type").toString());
											   	if(myOfferXml.text("offer_purchase_url").toString().equals("")){
											   		userOffer.setOfferPurchaseUrl("null");
											   	}else{
											   		userOffer.setOfferPurchaseUrl(myOfferXml.text("offer_purchase_url").toString());
											   	}
											   	userOffer.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
											   	userOffer.setOfferDescription(myOfferXml.text("offer_description").toString());
											   	userOffer.setClientVerticalId(myOfferXml.text("client_vertical_id").toString());
											   	userOffer.setOfferButtonName(myOfferXml.text("offer_button_name").toString());
											   	userOffer.setOfferClientId(myOfferXml.text("client_id").toString());
												userOffer.setOfferClientBgColor(myOfferXml.text("background_color").toString());
												userOffer.setOfferClientBgLightColor(myOfferXml.text("light_color").toString());
												userOffer.setOfferClientBgDarkColor(myOfferXml.text("dark_color").toString());
												userOffer.setProdRelatedId(myOfferXml.text("related_prodid").toString());
												userOffer.setOfferRelatedId(myOfferXml.text("related_offerid").toString());
												userOffer.setOfferIsSharable(myOfferXml.text("offer_is_sharable").toString());
												userOffer.setClientLocationBased(myOfferXml.text("is_location_based").toString());
												userOffer.setOfferBackImage(myOfferXml.text("offer_back_image").toString());
												userOffer.setOfferMultiRedeem(myOfferXml.text("offer_is_multi_redeem").toString());
											   	offers.add(userOffer);
											   	if(!clientIdsList.contains(myOfferXml.text("client_id").toString())){										   		
											   		clientIdsList.add(myOfferXml.text("client_id").toString());
											   	}
								   				j++;
								   				String curveImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");	
						                		
								   				Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
								    			if(bitmap1==null) {
								    				aq.cache(curveImagesUrl, 144000);
								    			}
			                    			 }
										

										file.setMyOffers(offersModel);
										file.setOffers(offers);
										if(j == myOffers.size()){
											clientIds = TextUtils.join(",", clientIdsList);
											Log.i("clientIds",clientIds);
											final Map<String, String> params = new HashMap<String, String>();
											params.put("clientIds", clientIds);
									        Log.i("params",""+params);
									        Log.i("ClientStore_Url",Constants.ClientStore_Url);
									         final Stores stores = file.getStores();
									         Log.i("getStores count",""+stores.size());
									        AQuery aq1 = new AQuery(LoginActivity.this);
									        aq1.ajax(Constants.ClientStore_Url, params, XmlDom.class, new AjaxCallback<XmlDom>(){
									        	@Override
												public void callback(String url, XmlDom xml, AjaxStatus status) {
									        		 final List<XmlDom> xmldom = xml.tags("resultXml");
									        		 int k = 0;
									        		 Log.i("xmldom",""+xmldom);
									        		 Stores newstores = new Stores();
													    for(final XmlDom xmllist : xmldom){
													    	try {		
													    		if(xmllist != null && xmllist.tag("name") !=null){
													    		//Log.i("clientStores",""+xmllist.text("store_name").toString());
													    		ClientStores clientStores = new ClientStores(k);
													    		clientStores.setStoreClientName(xmllist.text("name").toString());
													    		clientStores.setStoreCode(xmllist.text("store_code").toString());
													    		clientStores.setStoreName(xmllist.text("store_name").toString());
													    		clientStores.setStoreSearchType(xmllist.text("store_search_type").toString());
													    		clientStores.setClientId(xmllist.text("client_id").toString());
													    		clientStores.setLatitude(Double.parseDouble(xmllist.text("latitude").toString()));
													    		clientStores.setLongitude(Double.parseDouble(xmllist.text("longitude").toString()));
													    		clientStores.setAddress1(xmllist.text("address_1").toString());
													    		clientStores.setAddress2(xmllist.text("address_2").toString());												    		
													    		clientStores.setCity(xmllist.text("city").toString());
													    		clientStores.setState(xmllist.text("state").toString());
													    		clientStores.setZip(xmllist.text("zip").toString());
													    		clientStores.setPhone(xmllist.text("phone").toString());
													    		clientStores.setEmail(xmllist.text("email").toString());
													    		clientStores.setStoreNotifyMsg(xmllist.text("store_notify_msg").toString());
													    		clientStores.setStoreTriggerThreshold(xmllist.text("store_trigger_threshold").toString());
													    		clientStores.setStoreTriggerUpdate(Integer.parseInt(xmllist.text("store_update_threshold").toString()));
													    		newstores.add(clientStores);
													    		}
																}catch (Exception e) {
																	e.printStackTrace();
																}	
													    	}
	
											    		if(stores.size() >0){
											    			stores.mergeWithStores(newstores);
											    			file.setStores(stores);
													   	}else{
													   		file.setStores(newstores); 
													   	}
	
														Intent loginIntent;
													    Log.i("checkLoginFlag",checkLoginFlag);
														if(checkLoginFlag.equals("OfferViewMyOffers")){
															new OfferViewActivity().insertOfferToMyOffersDbUsingXml(
																	Constants.MyOffers_Url+"insert_offers/"+loggedInUser+"/"+stringArrayList2+"/", LoginActivity.this,"Login");
															loginIntent = new Intent(activityThis, MyOffers.class);
														}else if(checkLoginFlag.equals("ARDisplay")){
															loginIntent = new Intent(activityThis, ARDisplayActivity.class);
														}else if(checkLoginFlag.equals("MyOffers")){
															loginIntent = new Intent(activityThis, MyOffers.class);
														}else if(checkLoginFlag.equals("OfferCalendarMyOffers")){
															Log.i("offerStringArrayListValues", ""+offerStringArrayListValues);																		
															new OfferCalendarActivity().saveAndSetCalendarReminders(loggedInUser, "Yes", offerStringArrayListValues);
															loginIntent = new Intent(activityThis, MyOffers.class);
															
														}else if(checkLoginFlag.equals("ClosetInsert")){
															String insertClosetUrl = Constants.Closet_Url+"insert/"+loggedInUser+"/"+stringArrayList2+"/";
															new Closet().insertUpdateDeleteProductsToClosetDbUsingXml(insertClosetUrl, "insert");
															loginIntent = new Intent(activityThis, Closet.class);
														} else if(checkLoginFlag.equals("Recently Scanned")){
															loginIntent = new Intent(activityThis, RecentlyScanned.class);
														} else if(checkLoginFlag.equals("OrderConfirmation")){
															loginIntent = new Intent(activityThis, ProductsCheckout.class);
														}else if(checkLoginFlag.equals("ProductDetails")){
															loginIntent = new Intent(activityThis, OrderConfirmation.class);
														}else if(checkLoginFlag.equals("Products")){
															loginIntent = new Intent(activityThis, Products.class);
														}
														else{
															
															loginIntent = new Intent(activityThis, Closet.class);
														}
																
														String xmlAjaxUrl = Constants.WishListYourCloset_Url+loggedInUser+"/";
														Log.i("xmlAjaxUrl",xmlAjaxUrl);
														new Common().getYourWishListTableWithXml(xmlAjaxUrl, LoginActivity.this);
														try {
															Thread.sleep(1000);
														} catch (InterruptedException e) {
															e.printStackTrace();
														}
														
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
														activityThis.finish();
														if(!checkLoginFlag.equals("ARDisplay")){
														//activityThis.startActivityForResult(loginIntent, 1);
															loginIntent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
															activityThis.startActivity(loginIntent);
													    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
														}
														overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
									        	}
									        });
										} 
										
							    	}catch(Exception e){
							    		e.printStackTrace();
							    	}
							    }
							    
						    }else{	
								Intent loginIntent;
							    Log.i("checkLoginFlag",checkLoginFlag);
								if(checkLoginFlag.equals("OfferViewMyOffers")){
									new OfferViewActivity().insertOfferToMyOffersDbUsingXml(
											Constants.MyOffers_Url+"insert_offers/"+loggedInUser+"/"+stringArrayList2+"/", LoginActivity.this,"Login");
									loginIntent = new Intent(activityThis, MyOffers.class);
								}else if(checkLoginFlag.equals("ARDisplay")){
									loginIntent = new Intent(activityThis, ARDisplayActivity.class);
								}else if(checkLoginFlag.equals("MyOffers")){
									loginIntent = new Intent(activityThis, MyOffers.class);
								}else if(checkLoginFlag.equals("OfferCalendarMyOffers")){
									Log.i("offerStringArrayListValues", ""+offerStringArrayListValues);																		
									new OfferCalendarActivity().saveAndSetCalendarReminders(loggedInUser, "Yes", offerStringArrayListValues);
									loginIntent = new Intent(activityThis, MyOffers.class);
									
								}else if(checkLoginFlag.equals("ClosetInsert")){
									String insertClosetUrl = Constants.Closet_Url+"insert/"+loggedInUser+"/"+stringArrayList2+"/";
									new Closet().insertUpdateDeleteProductsToClosetDbUsingXml(insertClosetUrl, "insert");
									loginIntent = new Intent(activityThis, Closet.class);
								} 
								else{
									
									loginIntent = new Intent(activityThis, Closet.class);
								}
										
								String xmlAjaxUrl = Constants.WishListYourCloset_Url+loggedInUser+"/";
								Log.i("xmlAjaxUrl",xmlAjaxUrl);
								new Common().getYourWishListTableWithXml(xmlAjaxUrl, LoginActivity.this);
								try {
									Thread.sleep(1000);
								} catch (InterruptedException e) {
									e.printStackTrace();
									String errorMsg = className+" | onCreate   Thread sleep |   " +e.getMessage();
				               	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
								}
								
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
								loginIntent.putExtra("pageRedirectFlag", checkLoginFlag);
								activityThis.finish();
								if(!checkLoginFlag.equals("ARDisplay")){
									activityThis.startActivityForResult(loginIntent, 1);
								}
								//activityThis.finish();
								//activityThis.startActivityForResult(loginIntent, 1);
								overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
							
						    }
				  		}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | getUserOffersDetails ajax  call back |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
					}
				}
			});		
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getUserOffersDetails |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
			
		}
	}
	
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(LoginActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
			}
			
		}
	 
	 @Override
		protected void onResume() 
		{
			try{
				super.onResume();					
				if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(LoginActivity.this);			
					Common.isAppBackgrnd = false;
				}
				
			}catch (Exception e) 
			{		
				e.printStackTrace();
				String errorMsg = className+" | onResume | " +e.getMessage();
	        	Common.sendCrashWithAQuery(LoginActivity.this,errorMsg);
			
			}
			
		}	
}
