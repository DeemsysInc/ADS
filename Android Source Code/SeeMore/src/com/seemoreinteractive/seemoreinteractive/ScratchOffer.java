package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.callback.ImageOptions;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.UserMyOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.library.ScracthListAdapter;

public class ScratchOffer extends Activity {
	String offerInfo;
	AQuery aq = new AQuery(ScratchOffer.this);
	String className = getClass().getSimpleName();
	String[] scratchFinalArray;
	ArrayList<String>scratchResArrays;
	Button btnGameRules,btnaddoffer;
	String offerId;
	ArrayList<String> arrofferId;
	String arroffIds="";
	int t=0;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		try{
			setContentView(R.layout.activity_scratch_offer);
			
			RelativeLayout bgRelativeLayout = (RelativeLayout)findViewById(R.id.bgRelativeLayout);
			RelativeLayout.LayoutParams rlpbgRelativeLayout = (RelativeLayout.LayoutParams) bgRelativeLayout.getLayoutParams();
			rlpbgRelativeLayout.width = (int) (0.875* Common.sessionDeviceWidth);
			rlpbgRelativeLayout.height = (int) (0.4919 * Common.sessionDeviceHeight);
			rlpbgRelativeLayout.topMargin = (int) (0.018443 * Common.sessionDeviceHeight);
			bgRelativeLayout.setLayoutParams(rlpbgRelativeLayout);
			
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
						Intent output = new Intent();
						setResult(RESULT_OK, output);
						finish();
						//ScratchAndWin.scratchWin.finish();
					}catch(Exception e){
						e.printStackTrace();
					}
				}
			});
			btnaddoffer = (Button)findViewById(R.id.btnOffer);
			RelativeLayout.LayoutParams rlpBtnaddoffer = (RelativeLayout.LayoutParams) btnaddoffer.getLayoutParams();
			rlpBtnaddoffer.width = (int) (0.5* Common.sessionDeviceWidth);
			rlpBtnaddoffer.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlpBtnaddoffer.bottomMargin = (int) (0.03279 * Common.sessionDeviceHeight);
			btnaddoffer.setLayoutParams(rlpBtnaddoffer);
			
			btnaddoffer.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(ScratchOffer.this)){				
							/*ArrayList<String> stringArrayList =  new ArrayList<String>();
							if(!offerId.equals("null")){
								stringArrayList.add(offerId);
								stringArrayList.add("OfferView");
								new Common().getLoginDialog(ScratchOffer.this, MyOffers.class, "OfferViewMyOffers", stringArrayList );
							}*/
							ScratchAndWin.aList.clear();
							saveAndSetCalendarReminders();
						}
					}catch(Exception e){
						e.printStackTrace();
					}
					
				}
			});
			
			
			
			btnGameRules = (Button)findViewById(R.id.btnGameRules);
			RelativeLayout.LayoutParams rlpBtnGameRules = (RelativeLayout.LayoutParams) btnGameRules.getLayoutParams();
			rlpBtnGameRules.width = (int) (0.2667* Common.sessionDeviceWidth);
			rlpBtnGameRules.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlpBtnGameRules.leftMargin = (int) (0.03334* Common.sessionDeviceWidth);
			btnGameRules.setLayoutParams(rlpBtnGameRules);
			
			btnGameRules.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					
					Intent intent = new Intent(ScratchOffer.this,GameRules.class);
					intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
					intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
					startActivity(intent);
				}
			});
			
			TextView txtvDisclaimer = (TextView)findViewById(R.id.txtvDisclaimer);
			RelativeLayout.LayoutParams rlptxtvDisclaimer = (RelativeLayout.LayoutParams) txtvDisclaimer.getLayoutParams();
			rlptxtvDisclaimer.width = (int) (0.6667* Common.sessionDeviceWidth);
			//rlptxtvDisclaimer.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlptxtvDisclaimer.topMargin = (int) (0.0205* Common.sessionDeviceHeight);
			txtvDisclaimer.setLayoutParams(rlptxtvDisclaimer);
			
			
			TextView txtvMess = (TextView)findViewById(R.id.txtvMess);
			RelativeLayout.LayoutParams rlptxtvMess = (RelativeLayout.LayoutParams) txtvMess.getLayoutParams();
			//rlptxtvMess.width = (int) (0.6667* Common.sessionDeviceWidth);
			//rlptxtvDisclaimer.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlptxtvMess.topMargin = (int) (0.02562* Common.sessionDeviceHeight);
			txtvMess.setTextSize((int)(0.05 * Common.sessionDeviceWidth /Common.sessionDeviceDensity));
			txtvMess.setLayoutParams(rlptxtvMess);
			
			
			ListView listViewScratch = (ListView)findViewById(R.id.listViewScratch);
			RelativeLayout.LayoutParams rlplistViewScratch = (RelativeLayout.LayoutParams) listViewScratch.getLayoutParams();
			rlplistViewScratch.width = (int) (0.825* Common.sessionDeviceWidth);
			rlplistViewScratch.height = (int) (0.23566 * Common.sessionDeviceHeight);
			rlplistViewScratch.topMargin = (int) (0.01025* Common.sessionDeviceHeight);				
			rlplistViewScratch.leftMargin = (int) (0.025* Common.sessionDeviceWidth);
			listViewScratch.setLayoutParams(rlplistViewScratch);
			
			ArrayList<String> arrList = getIntent().getStringArrayListExtra("aList");
			arrofferId = new ArrayList<String>();
			
			if(arrList.size() >0 ){
				scratchFinalArray = new String[arrList.size()];
				for(int i=0;i<arrList.size(); i++){
					 offerInfo = arrList.get(i);
					 scratchResArrays = new ArrayList<String>(); 	
					 JSONArray offerInfoArray = new JSONArray(offerInfo);
						if(offerInfoArray.length() > 0){
							for(int j=0;j<offerInfoArray.length();j++){
								 JSONObject jsonObj = offerInfoArray.getJSONObject(j);	
								 if(jsonObj != null){
									 String offrDis;
		                		String curveImagesUrl = jsonObj.getString("offer_image").replaceAll(" ", "%20");	
		                		scratchResArrays.add(curveImagesUrl);
		                		
		                		 if(jsonObj.getString("offer_discount_type").equals("A")){
									 offrDis = "( $" +jsonObj.getString("offer_discount_value")+" off )";
								}else if(jsonObj.getString("offer_discount_type").equals("P")){
									offrDis = "( " + jsonObj.getString("offer_discount_value")+"% off )";
								}else{
									offrDis = "( "+jsonObj.getString("offer_discount_value")+" points )";
								}
		                		 offerId = jsonObj.getString("offer_id");
		                		 if(arroffIds.equals("")){
		                			 arroffIds = offerId;		                			 
		                		 }else{
		                			 arroffIds = arroffIds +"," + offerId;
		                		 }
		                		
		                		 arrofferId.add(offerId);
		                		 scratchResArrays.add(jsonObj.getString("offer_id"));
		                		 scratchResArrays.add(jsonObj.getString("offer_name"));
		                		 scratchResArrays.add(offrDis);
		                		 scratchResArrays.add(jsonObj.getString("offer_description"));
		                		 txtvDisclaimer.setText(jsonObj.getString("offer_description"));
		                		
							 }
							}
							scratchFinalArray[i] = scratchResArrays.toString();							
						}
				}
				listViewScratch.setAdapter(new ScracthListAdapter(this, scratchFinalArray));
			}
				
			
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	int imyOfferslistItem;
		private ArrayAdapter<String> renderForCoverFlow(){	    	
			 AQUtility.debug("render setup");
			 imyOfferslistItem = R.layout.listview_scratch_layout;	
			  ArrayAdapter<String> aa = new ArrayAdapter<String>(this, imyOfferslistItem){				
				@Override
				public View getView(final int position, View convertView, ViewGroup parent) {
					try {						
						if(convertView == null){
							//convertView = aq.inflate(convertView, gridItemLayout, parent);
							//convertView = getLayoutInflater().inflate(imyOfferslistItem, parent, false);
							convertView = aq.inflate(convertView, imyOfferslistItem, parent);
						}	
						AQuery aq2 = new AQuery(convertView);		
						JSONArray offerInfoArray = new JSONArray(offerInfo);
						if(offerInfoArray.length() > 0){
							for(int j=0;j<offerInfoArray.length();j++){
								 JSONObject jsonObj = offerInfoArray.getJSONObject(j);	
								 if(jsonObj != null){
									 String offrDis;
									 Log.e("offer_image",jsonObj.getString("offer_image"));
									 ImageView img =(ImageView) convertView.findViewById(R.id.imgOffer);
									 Bitmap bitmap1 = aq2.getCachedImage(jsonObj.getString("offer_image"));
						    			if(bitmap1==null) {
						    				aq2.cache(jsonObj.getString("offer_image"), 144000);
						    			}
								       
								       
										ImageOptions options = new ImageOptions();
										options.fileCache = true;
										options.memCache = true;
										options.targetWidth = 0;
										options.fallback = 0;
										options.preset = null;
										options.ratio = 0;
										options.animation = com.androidquery.util.Constants.FADE_IN;	

						    			aq2.id(R.id.imgOffer).progress(R.id.progressBarForMyOffers).image(jsonObj.getString("offer_image"), options);
										
									 if(jsonObj.getString("offer_discount_type").equals("A")){
										 offrDis = "( $" +jsonObj.getString("offer_discount_value")+" off )";
									}else if(jsonObj.getString("offer_discount_type").equals("P")){
										offrDis = "( " + jsonObj.getString("offer_discount_value")+"% off )";
									}else{
										offrDis = "( "+jsonObj.getString("offer_discount_value")+" points )";
									}
									 TextView offerDiscount =(TextView) convertView.findViewById(R.id.offerDiscount);
									 offerDiscount.setText(offrDis);	
									 
									 TextView txtvOfferName =(TextView) convertView.findViewById(R.id.txtvOfferName);
									 txtvOfferName.setText(jsonObj.getString("offer_name"));	
									 
									 TextView txtvDisclaimer =(TextView) findViewById(R.id.txtvDisclaimer);
									 
									if(!jsonObj.getString("offer_description").equals("null")){
										txtvDisclaimer.setText(jsonObj.getString("offer_description"));
									}
								 }
							}
						}
												
					} catch (Exception e){
						e.printStackTrace();
						String errorMsg = className+" | renderForCoverFlow  |   " +e.getMessage();
				       	Common.sendCrashWithAQuery(ScratchOffer.this,errorMsg);
					}
					return convertView;					
				}			
			};			
			//aq.id(R.id.listViewScratch).adapter(aa);
			return aa;
		}	
		
		
		public void saveAndSetCalendarReminders(){
			try{						
							Map<String, Object> params = new HashMap<String, Object>();
							params.put("clientId", getIntent().getStringExtra("clientId"));
						    //Log.i("userId", ""+userId);
						    params.put("userId", Common.sessionIdForUserLoggedIn);
						    params.put("offerIdsWithSymbol", arroffIds);
						    params.put("checkFlag", "Yes");
							aq = new AQuery(ScratchOffer.this);
							Log.i(" Common.sessionIdForUserLoggedIn", ""+ Common.sessionIdForUserLoggedIn);
							Log.i("arroffIds", ""+ arroffIds);
							aq.ajax(Constants.OfferInfo_Url, params, XmlDom.class, new AjaxCallback<XmlDom>() {
				                @Override
				                public void callback(String url, XmlDom xml, AjaxStatus status) {
				                	try{
				                    	Log.i("xml", ""+xml);
					                    if(xml != null){
					                    	List<XmlDom> entries = xml.tags("clientOffers");   	
					                    	//Log.i("entries", ""+entries.size()+" "+entries);
					                    	 FileTransaction file = new FileTransaction();
										    	Offers offers = file.getOffers();
										    	Offers myoffers = file.getMyOffers();
										    	Offers newoffers = new Offers();
										    	Offers offersModel = new Offers();	
					                    	if(entries.size()>0){
						                    	for(XmlDom entry: entries){
													t++;
														try{	  
															//if(entry.text("insertMsg").toString().equals("success")){												
															
															Log.e("offer_is_sharable",""+entry.text("offer_is_sharable").toString());
														    		if(entry.text("offer_id").toString() !=null){
														    			UserMyOffers offermyExist = myoffers.getUserMyOffers(Integer.parseInt(entry.text("offer_id").toString()));
															    		Log.i("offerExist",""+offermyExist);
															    		if(offermyExist == null){
																			UserMyOffers usermyOffers= new UserMyOffers();
																			usermyOffers.setOfferId(Integer.parseInt(entry.text("offer_id").toString()));
																			usermyOffers.setOfferClientId(entry.text("client_id").toString());
																			usermyOffers.setOfferClientName(entry.text("name").toString());
																			usermyOffers.setOfferName(entry.text("offer_name").toString());			
																			usermyOffers.setOfferDiscountValue(entry.text("offer_discount_value").toString());
																			usermyOffers.setOfferValidDate(entry.text("offer_valid_to").toString());
																			offersModel.addUserMyOffers(usermyOffers);
																    		}
																    		UserOffers offerExist = offers.getUserOffers(Integer.parseInt(entry.text("offer_id").toString()));
																			if(offerExist == null){ 	
																			UserOffers userOffer = new UserOffers();
																		   	userOffer.setOfferId(Integer.parseInt(entry.text("offer_id").toString()));										   	
																		   	userOffer.setOfferImage(entry.text("offer_image").toString().replaceAll(" ", "%20"));
																		   	userOffer.setOfferClientName(entry.text("name").toString());
																		   	userOffer.setOfferName(entry.text("offer_name").toString());
																		   	userOffer.setOfferDiscountValue(entry.text("offer_discount_value").toString());
																		   	userOffer.setOfferDiscountType(entry.text("offer_discount_type").toString());
																		   	userOffer.setOfferPurchaseUrl(entry.text("offer_purchase_url").toString());
																		   	userOffer.setOfferValidDate(entry.text("offer_valid_to").toString());
																			userOffer.setOfferDescription(entry.text("offer_description").toString());
																			userOffer.setClientVerticalId(entry.text("client_vertical_id").toString());
																			userOffer.setOfferButtonName(entry.text("offer_button_name").toString());
																			userOffer.setOfferClientId(entry.text("client_id").toString());
																			userOffer.setOfferClientBgColor(entry.text("background_color").toString());
																			userOffer.setOfferClientBgLightColor(entry.text("light_color").toString());
																			userOffer.setOfferClientBgDarkColor(entry.text("dark_color").toString());
																			userOffer.setOfferIsSharable(entry.text("offer_is_sharable").toString());
																			userOffer.setClientLocationBased(entry.text("is_location_based").toString());
																			userOffer.setOfferBackImage(entry.text("offer_back_image").toString());
																			userOffer.setOfferMultiRedeem(entry.text("offer_is_multi_redeem").toString());
																		   	newoffers.add(userOffer);
																    		}
																    
														   }
														    
															//}
																
												    	}catch(Exception e){
												    		e.printStackTrace();
												    	}
													}
													
						                    	} 		
									    		if(offers.size() >0){
									    			offers.mergeWithOffers(newoffers);
									    			myoffers.mergeWithMyOffers(offersModel);
									    			file.setOffers(offers);
									    			file.setMyOffers(myoffers);
									    			Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
		        								    intent.putExtra("offerName", "My Offers" );	        								    
		        					                startActivity(intent);
		        					                overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
		        					      	      	finish();
											   	}else{
											   		file.setOffers(newoffers);
											   		file.setMyOffers(myoffers);
											   		Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
		        								    intent.putExtra("offerName", "My Offers" );	        								    
		        					                startActivity(intent);
		        					                overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
		        					      	      	finish();
											   	}	
									    		
									    		/*if(t==entries.size()){													
													Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
		        								    intent.putExtra("offerName", "My Offers" );	        								    
		        					                startActivity(intent);
		        					                overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
		        					      	      	finish();
											     }*/
				                    		}/*else{				                    																
													Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
		        								    intent.putExtra("offerName", "My Offers" );	        								    
		        					                startActivity(intent);
		        					                overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
		        					      	      	finish();
													}*/
				                	}catch(Exception e){
										e.printStackTrace();
									} 
				                 }				                
							});			
						}catch(Exception e){
							e.printStackTrace();
						} 
					}
	
}
