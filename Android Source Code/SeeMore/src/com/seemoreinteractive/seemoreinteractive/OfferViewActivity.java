package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.List;
import java.util.regex.Pattern;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserMyOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class OfferViewActivity extends Activity {
	String current;
	public boolean isBackPressed = false;
	String className = this.getClass().getSimpleName();
	final Context context = this;
	public Boolean alertErrorType = true;
	
	SessionManager session;
	AQuery aq;
	ArrayList<String> stringArrayList;
	String[] stringArray = {};
	String id="";
	String offerId, imageName, offerPurchaseUrl,pageRedirectFlag="null";
	Boolean changeFlag = false;
	FileTransaction file; 
	String offerUrl = Constants.Live_Android_Url +"offers/"+ Common.sessionClientId;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		try{			
		super.onCreate(savedInstanceState);
		setContentView(R.layout.offer_layout_tab7);
		aq = new AQuery(this);
		stringArrayList = new ArrayList<String>();
		Intent intent = getIntent();	
		if(intent.getExtras()!=null){

			imageName = intent.getStringExtra("imagename");
			Log.i("imageName", ""+imageName);
			offerId = intent.getStringExtra("offerId");
			offerPurchaseUrl = intent.getStringExtra("offerPurchaseUrl");
			pageRedirectFlag = intent.getStringExtra("pageRedirectFlag");	
			
			Common.sessionClientBgColor = intent.getStringExtra("clientBackgroundColor");
			Common.sessionClientBackgroundLightColor = intent.getStringExtra("clientBackgroundLightColor");
			Common.sessionClientBackgroundDarkColor = intent.getStringExtra("clientBackgroundDarkColor");
			
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "OfferView", "");	
			
			ImageView imgOffer = (ImageView) findViewById(R.id.offerimage);
			RelativeLayout.LayoutParams rlpForImgOffer = (RelativeLayout.LayoutParams) imgOffer.getLayoutParams();
			rlpForImgOffer.width = LayoutParams.MATCH_PARENT;
			rlpForImgOffer.height = (int) (0.33 * Common.sessionDeviceHeight);
			rlpForImgOffer.topMargin = (int) (0.29 * Common.sessionDeviceHeight);
			imgOffer.setLayoutParams(rlpForImgOffer);
			
			Button noThanks =(Button)findViewById(R.id.btnnothanks);		
			RelativeLayout.LayoutParams rlpForBtnNoThanks = (RelativeLayout.LayoutParams) noThanks.getLayoutParams();
			rlpForBtnNoThanks.width = (int) (0.39 * Common.sessionDeviceWidth);
			rlpForBtnNoThanks.height = (int) (0.066 * Common.sessionDeviceHeight);
			noThanks.setLayoutParams(rlpForBtnNoThanks);
			
			Log.i("offerId", ""+offerId);
			/*final String clientId = intent.getStringExtra("clientId");
			final String clientLogo = intent.getStringExtra("clientLogo");
			final String clientBackgroundImage = intent.getStringExtra("clientBackgroundImage");
			final String clientBackgroundColor = intent.getStringExtra("clientBackgroundColor");*/
			stringArrayList.add(offerId);
			stringArrayList.add("OfferView");
			//stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
			
			file = new FileTransaction();	   

			
			if(Common.isNetworkAvailable(OfferViewActivity.this)){		
				Offers offers = file.getOffers();			        
				UserOffers offerExist = offers.getUserOffers(Integer.parseInt(offerId));
				if(offerExist == null){						
					getOffersFromServer(offerUrl+"/"+offerId);					
				}else{	    					
					changeFlag = new Common().checkOfferExistinChangeLog("offer",offerId);
	    			if(!changeFlag){
	    				offerListXmlResultFromFile();
	    			}else{
	    				getOffersFromServer(offerUrl+"/"+offerId);	    				
	    			}
				}
				
			}else{
				offerListXmlResultFromFile();
			}
			//new Common().DownloadImageFromUrlWithLoader(this, imageName, R.id.offerimage, R.id.progressLoader);
			
			
  
			  noThanks.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{					
						if(pageRedirectFlag == null || pageRedirectFlag.equals("null")){
							finish();
						}else{
							if(pageRedirectFlag.equals("RecentlyScanned")){
								Intent intent = new Intent(OfferViewActivity.this, RecentlyScanned.class);									
								startActivityForResult(intent, 1);
								finish();
								overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							}
						}
					/*if(!offerPurchaseUrl.equals("null")){
						Intent intent = new Intent(getApplicationContext(), OfferWebUrlActivity.class);		
				    	intent.putExtra("offerPurchseUrl", offerPurchaseUrl);
				    	finish();
				    	startActivity(intent);
				    	
					}else{
						finish();
					}*/
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | noThanks click |   " +e.getMessage();
				       	Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
					}
				}			
			  });
			  
				
				// Session class instance
		        session = new SessionManager(context);

				 Button btnaddoffer =(Button)findViewById(R.id.btnaddoffer);
				RelativeLayout.LayoutParams rlpForBtnAddOffer = (RelativeLayout.LayoutParams) btnaddoffer.getLayoutParams();
				rlpForBtnAddOffer.width = (int) (0.39 * Common.sessionDeviceWidth);
				rlpForBtnAddOffer.height = (int) (0.066 * Common.sessionDeviceHeight);
				rlpForBtnAddOffer.topMargin = (int) (0.024 * Common.sessionDeviceHeight);
				btnaddoffer.setLayoutParams(rlpForBtnAddOffer);
				
				 btnaddoffer.setOnClickListener(new OnClickListener() {	
					@Override
					public void onClick(View v) {	
						try{
						if(Common.isNetworkAvailable(OfferViewActivity.this)){							
							new Common().getLoginDialog(OfferViewActivity.this, MyOffers.class, "OfferViewMyOffers", stringArrayList);
							finish();
							
						}else{
							//new Common().instructionBox(ARDisplayActivity.class,R.string.title_case7,R.string.instruction_case7);
							Intent returnIntent = new Intent();
							returnIntent.putExtra("activity","menu");
							setResult(RESULT_OK,returnIntent);
							finish();					
							 
						}
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | btnaddoffer click |   " +e.getMessage();
					       	Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
						}
					}
				});
			}
	
			
			String screenName = "/offers/"+Common.sessionClientId+"/"+offerId;
			String productIds = "";
			String offerIds = offerId;
			Common.sendJsonWithAQuery(this, id, screenName, productIds, offerIds);
			
		} catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error : OfferView Activity onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			 String errorMsg = className+" | onCreate |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
		}
	}
	private void offerListXmlResultFromFile() {
		try{
			Offers offers = file.getOffers();				
			UserOffers offerExist = offers.getUserOffers(Integer.parseInt(offerId));
			if(offerExist != null){
				stringArrayList = new ArrayList<String>();
				Log.e("offerExist.getOfferImage()",""+offerExist.getOfferImage());						
				stringArrayList.add(""+offerExist.getOfferId());
				stringArrayList.add("OfferView");
				Bitmap bitmap = aq.getCachedImage(offerExist.getOfferImage());
				if(bitmap == null){
					aq.cache(offerExist.getOfferImage(), 1440000);
				}
				ImageView img = (ImageView)findViewById(R.id.offerimage);
				img.setImageBitmap(bitmap);
			}		
		}catch(Exception e){
			e.printStackTrace();
		}
		
	}
	
	 public void getOffersFromServer(String offerUrl){
		  try{			 
		  final AQuery aq1 = new AQuery(OfferViewActivity.this);
		  aq1.ajax(offerUrl,  XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try {  
	        			Log.e("xml",""+xml);
	        		    if(!xml.tags("products").equals(null)){
	        		    	List<XmlDom> entries = xml.tags("products");
	        		    	FileTransaction file = new FileTransaction();
	        		        Offers offers = file.getOffers();
	        		        if(offers.size() == 0){
	        		        	offers = new Offers();
	        		        }
	        		    	if(entries.size() > 0){
	        		    		 stringArrayList = new ArrayList<String>();
	       	    				for(XmlDom myOfferXml: entries){	 
	       	    				 String curveImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
	       	    				 stringArrayList.add(myOfferXml.text("offer_id").toString());
	       	    				 stringArrayList.add("OfferView");    
	       	    			  	new Common().DownloadImageFromUrlWithLoader(OfferViewActivity.this, curveImagesUrl, R.id.offerimage, R.id.progressLoader);
				        				 Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
											if(bitmap == null){
												aq.cache(curveImagesUrl, 1440000);
											}
				        				  String symbol = new Common().getCurrencySymbol(myOfferXml.text("country_languages").toString(), myOfferXml.text("country_code_char2").toString());
				        				  UserOffers checkUserOffer = offers.getUserOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
				        				   if(changeFlag){
				        						  if(checkUserOffer != null){
				        							  offers.removeItem(checkUserOffer);
				        							  checkUserOffer = null;
				        						  }
				        					  }
				        					  if(checkUserOffer == null){				        					  
						        				    UserOffers userOffer = new UserOffers();
												   	userOffer.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));										   	
												   	userOffer.setOfferImage(myOfferXml.text("offer_image").toString().replaceAll(" ", "%20"));
												   	userOffer.setOfferClientName(myOfferXml.text("name").toString());
												   	userOffer.setOfferName(myOfferXml.text("offer_name").toString());
												   	if(myOfferXml.text("offer_discount_type").toString().equals("A")){
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
													userOffer.setOfferIsSharable(myOfferXml.text("offer_is_sharable").toString());
													userOffer.setClientLocationBased(myOfferXml.text("is_location_based").toString());
													userOffer.setOfferBackImage(myOfferXml.text("offer_back_image").toString());
													userOffer.setOfferMultiRedeem(myOfferXml.text("offer_is_multi_redeem").toString());
												   	offers.add(userOffer);
				        				  }
				        					  if(changeFlag){
				        						  new Common().deleteChangeLogFields("offer",Integer.parseInt(myOfferXml.text("offer_id").toString()));
				        					  }
	       	    				}
	       	    			 if(offers.size() >0){	                           		
	                           	file.setOffers(offers);	                           		
	                           	}
	       	    			
	        		    }
	        		    }	
	        		   }catch(Exception e){
	        			   e.printStackTrace();
	        			   String errorMsg = className+" | getAllClientOffersFromServer ajax call back    |   " +e.getMessage();
	        			   Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
	        			   
	        		   }
	        	}  		
	        	
	        	
		  });
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getAllClientOffersFromServer    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
		  }
	  }


	int alreadyCountFlag = 0;
	int successCountFlag = 0;
	int failedCountFlag = 0;
	public void insertOfferToMyOffersDbUsingXml(String xmlUrl, final Activity activityThis,final String pageRedirectFlag) {
		// TODO Auto-generated method stub
		Log.i("xmlUrl", ""+xmlUrl);
		aq = new AQuery(this);
		aq.ajax(xmlUrl, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
					Log.i("XmlDom", ""+xml);
    				if(xml!=null){
    					if(xml.text("msg").equals("already")){
    						//alreadyCountFlag++;
	    					//Toast.makeText(activityThis, "Already have same Offer(s) in this my offers.", Toast.LENGTH_LONG).show();
    						
    						try{
		    					final List<XmlDom> myOffers = xml.tags("myOffers");
		    				    if(myOffers.size()>0){	    		    				    	
							    	Offers newoffers = new Offers();
							    	Offers offersModel = new Offers();

								    FileTransaction file = new FileTransaction();
							    	Offers offers = file.getOffers();
							    	Offers myoffers = file.getMyOffers();
								   
								    for(final XmlDom myOfferXml : myOffers){   						 
								    	
								    	if(myOfferXml.tag("offer_id")!=null){
								    		UserMyOffers offermyExist = myoffers.getUserMyOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
								    		Log.i("offerExist",""+offermyExist);
								    		if(offermyExist == null){
								    		

											UserMyOffers usermyOffers= new UserMyOffers();
											usermyOffers.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));
											usermyOffers.setOfferClientId(myOfferXml.text("client_id").toString());
											usermyOffers.setOfferClientName(myOfferXml.text("name").toString());
											usermyOffers.setOfferName(myOfferXml.text("offer_name").toString());			
											usermyOffers.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
											usermyOffers.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
											offersModel.addUserMyOffers(usermyOffers);
								    		}
			                    		
											UserOffers offerExist = offers.getUserOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
											if(offerExist == null){
											UserOffers userOffer = new UserOffers();
										   	userOffer.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));										   	
										   	userOffer.setOfferImage(myOfferXml.text("offer_image").toString().replaceAll(" ", "%20"));
										   	userOffer.setOfferClientName(myOfferXml.text("name").toString());
										   	userOffer.setOfferName(myOfferXml.text("offer_name").toString());
										   	//userOffer.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
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
										   	userOffer.setOfferPurchaseUrl(myOfferXml.text("offer_purchase_url").toString());
										   	userOffer.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
											userOffer.setOfferDescription(myOfferXml.text("offer_description").toString());
											userOffer.setClientVerticalId(myOfferXml.text("client_vertical_id").toString());
											userOffer.setOfferButtonName(myOfferXml.text("offer_button_name").toString());
											userOffer.setOfferClientId(myOfferXml.text("client_id").toString());
											userOffer.setOfferClientBgColor(myOfferXml.text("background_color").toString());
											userOffer.setOfferClientBgLightColor(myOfferXml.text("light_color").toString());
											userOffer.setOfferClientBgDarkColor(myOfferXml.text("dark_color").toString());
											userOffer.setOfferIsSharable(myOfferXml.text("offer_is_sharable").toString());
											userOffer.setClientLocationBased(myOfferXml.text("is_location_based").toString());
											userOffer.setOfferBackImage(myOfferXml.text("offer_back_image").toString());
											userOffer.setOfferMultiRedeem(myOfferXml.text("offer_is_multi_redeem").toString());
										   
										   	newoffers.add(userOffer);
								    		
								    		}
								    		
								    		if(offers.size() >0){
								    			offers.mergeWithOffers(newoffers);
								    			myoffers.mergeWithMyOffers(offersModel);
								    			file.setOffers(offers);
								    			file.setMyOffers(myoffers);
										   	}else{
										   		file.setOffers(newoffers);
										   		file.setMyOffers(myoffers);
										   	}
								    		}
								    	
								    	
		    						    }
								    
								}
									
									
					    	}catch(Exception e){
					    		e.printStackTrace();
					    		String errorMsg = className+" | insertOfferToMyOffersDbUsingXml if already exist |   " +e.getMessage();
						       	Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
					    	}
	    				} else if(xml.text("msg").equals("success")){
	    					try{
		    					final List<XmlDom> myOffers = xml.tags("myOffers");
		    				    if(myOffers.size()>0){	    		    				    	
							    	Offers newoffers = new Offers();
							    	Offers offersModel = new Offers();
							    	
								    FileTransaction file = new FileTransaction();
							    	Offers offers = file.getOffers();
							    	Offers myoffers = file.getMyOffers();
							    	
								    for(final XmlDom myOfferXml : myOffers){   						 
								    	
								    	if(myOfferXml.tag("offer_id")!=null){
								    		UserMyOffers offermyExist = myoffers.getUserMyOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
									    		Log.i("offerExist",""+offermyExist);
									    		if(offermyExist == null){									    		
													UserMyOffers usermyOffers= new UserMyOffers();
													usermyOffers.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));
													usermyOffers.setOfferClientId(myOfferXml.text("client_id").toString());
													usermyOffers.setOfferClientName(myOfferXml.text("name").toString());
													usermyOffers.setOfferName(myOfferXml.text("offer_name").toString());			
													usermyOffers.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
													usermyOffers.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
													offersModel.addUserMyOffers(usermyOffers);
			                    		
									    		}
									    UserOffers offerExist = offers.getUserOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
										if(offerExist == null){	
										   	UserOffers userOffer = new UserOffers();
										   	userOffer.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));										   	
										   	userOffer.setOfferImage(myOfferXml.text("offer_image").toString().replaceAll(" ", "%20"));
										   	userOffer.setOfferClientName(myOfferXml.text("name").toString());
										   	userOffer.setOfferName(myOfferXml.text("offer_name").toString());
										   	userOffer.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
										   	userOffer.setOfferDiscountType(myOfferXml.text("offer_discount_type").toString());
										   	userOffer.setOfferPurchaseUrl(myOfferXml.text("offer_purchase_url").toString());
										   	userOffer.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
											userOffer.setOfferDescription(myOfferXml.text("offer_description").toString());
											userOffer.setClientVerticalId(myOfferXml.text("client_vertical_id").toString());
											userOffer.setOfferButtonName(myOfferXml.text("offer_button_name").toString());
											userOffer.setOfferClientId(myOfferXml.text("client_id").toString());
											userOffer.setOfferClientBgColor(myOfferXml.text("background_color").toString());
											userOffer.setOfferClientBgLightColor(myOfferXml.text("light_color").toString());
											userOffer.setOfferClientBgDarkColor(myOfferXml.text("dark_color").toString());
											userOffer.setOfferIsSharable(myOfferXml.text("offer_is_sharable").toString());
											userOffer.setClientLocationBased(myOfferXml.text("is_location_based").toString());
											userOffer.setOfferBackImage(myOfferXml.text("offer_back_image").toString());
											userOffer.setOfferMultiRedeem(myOfferXml.text("offer_is_multi_redeem").toString());
										   	newoffers.add(userOffer);
										   	
								    		}
								    	 } 
		    						   }
								  
								    if(offers.size() >0){
						    			offers.mergeWithOffers(newoffers);
						    			myoffers.mergeWithMyOffers(offersModel);
						    			file.setOffers(offers);
						    			file.setMyOffers(myoffers);
								   	}else{
								   		file.setOffers(newoffers);
								   		file.setMyOffers(myoffers);
								   	}
								    
									
								}
		    				    }catch(Exception e){
						    		e.printStackTrace();
						    		String errorMsg = className+" | insertOfferToMyOffersDbUsingXml else success |   " +e.getMessage();
							       	Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
						    	}
									
	    				} else {
	    					//failedCountFlag++;
	    					Toast.makeText(activityThis, "Offer(s) adding failed. Please try again!", Toast.LENGTH_LONG).show();
	    				}

					    Intent intent = new Intent(activityThis, MyOffers.class);	
						intent.putExtra("productId", Common.sessionProductId);
						intent.putExtra("productName", Common.sessionProductName);
						intent.putExtra("productPrice", Common.sessionProductPrice);
						intent.putExtra("productShortDesc", Common.sessionProductShortDesc);
						intent.putExtra("clientLogo", Common.sessionClientLogo);
						intent.putExtra("clientId", Common.sessionClientId);
						intent.putExtra("clientBackgroundImage", Common.sessionClientBgImage);
						intent.putExtra("clientImageName", Common.sessionClientName);
						intent.putExtra("clientBackgroundColor", Common.sessionClientBgColor);
						intent.putExtra("pageRedirectFlag", pageRedirectFlag);
						
						activityThis.startActivityForResult(intent, 1);
						activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						
    				}
				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | insertOfferToMyOffersDbUsingXml |   " +e.getMessage();
			       	 Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
				}
			}			            			
		});	
	}

	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			// Log.i("Press Back", "BACK PRESSED EVENT");
			onBackPressed();
			isBackPressed = true;
		}

		// Call super code so we dont limit default interaction
		return super.onKeyDown(keyCode, event);
	}

	@Override
	public void onBackPressed() {
		try{
		new Common().deleteFiles(Constants.Products_Location);
		 /*Intent Home = new Intent(getApplicationContext(), ARDisplayActivity.class);
         setResult(1,Home);
	     finish();
	     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		super.onBackPressed();
        return;*/

		new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, ARDisplayActivity.class, "0");
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onBackPressed |   " +e.getMessage();
		    Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
			
		}
	}
	 public boolean checkEmail(String email) {
	        return EMAIL_ADDRESS_PATTERN.matcher(email).matches();
	    }

		public final Pattern EMAIL_ADDRESS_PATTERN = Pattern.compile(
		          "[a-zA-Z0-9+._%-+]{1,256}" +
		      "@" +
		      "[a-zA-Z0-9][a-zA-Z0-9-]{0,64}" +
		      "(" +
		      "." +
		      "[a-zA-Z0-9][a-zA-Z0-9-]{0,25}" +
		      ")+"
		);
	 @Override
		public void onStart() {
		 try{
		    super.onStart();
		    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		    String[] segments = new String[1];
			segments[0] = "Single Offer  Visual"; 
			QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart |   " +e.getMessage();
		     Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
		 }
		}
		 @Override
		public void onStop() {
		try{
			super.onStop();
			EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
			QuantcastClient.activityStop();
		}catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStop |   " +e.getMessage();
			     Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
			 }
		}
		 
		 @Override
			protected void onPause() 
			{
				try{
					super.onPause();
					Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(OfferViewActivity.this);
					if(appInBackgrnd){
						 Common.isAppBackgrnd = true;
					}					
				}catch (Exception e) {		
					e.printStackTrace();
					String errorMsg = className+" | onPause | " +e.getMessage();
		        	Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
				}
				
			}
		 
			@Override
			protected void onResume() {
				try{
				super.onResume();
				if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(OfferViewActivity.this);			
					Common.isAppBackgrnd = false;
				}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onResume     |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(OfferViewActivity.this,errorMsg);
				}
			}
		 
}