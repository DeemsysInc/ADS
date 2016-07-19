package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.TimeZone;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.provider.CalendarContract;
import android.provider.CalendarContract.Reminders;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
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

public class OfferCalendarActivity extends Activity {
	String className = this.getClass().getSimpleName();
	AQuery aq;
	ArrayList<String> clientOfferArrListImages;
	ArrayList<String> clientChkOfferArrListImages, chkArrListImageOfferIds, chkArrListImageCalEventStartdate, 
	chkArrListImageCalEventEnddate, chkArrListImageCalEventAllDay, chkArrListImageCalEventHasAlarm, 
	chkArrListImageCalReminderDays;
	ArrayList<String> stringArrayOfferCalImages, stringArrOfferWithClientIds, stringArrOfferIds, 
	stringArrCalendarEventStartDate, stringArrCalendarEventEndDate, stringArrCalendarEventAllDay, 
	stringArrCalendarEventHasAlarm, stringArrCalendarReminderDays, stringArrOffersWithLoginValues, stringArrOfferFlagCount;
	ImageView image;
	
	public String getClientId = "null", getOfferId = "null", getOfferName = "null", getOfferShortDesc = "null", 
			getOfferDesc = "null", getOfferCalEventStartDate = "null", getOfferCalEventEndDate = "null", 
			getOfferCalEventAllDay = "null", getOfferCalEventHasAlarm = "null", getOfferCalReminderDays = "null",pageRedirectFlag="null";
	int t=0;
	int w=0;
	 ArrayList<String> arrOfferFlagCount;
	 ArrayList<String> arrOfferImages;
	 ArrayList<String> arrOfferIds;
	 ArrayList<String> arrOfferWithClientIds;
	 ArrayList<String> arrOfferExist;
	
	SessionManager session;
	String id="";
	public boolean alertErrorType = true;
	private ProgressDialog pDialog;
	Boolean changeFlag = false;
	FileTransaction file; 
	String offerUrl = Constants.Live_Android_Url +"offers/"+ Common.sessionClientId;
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.calendar_grid_layout);
		try{			
	    	// Session class instance
	       	session = new SessionManager(getApplicationContext());
	       	
	        new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					OfferCalendarActivity.this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "");
	        
	        ImageView imgGoBack = (ImageView) findViewById(R.id.go_back); 
			RelativeLayout.LayoutParams rlpForImgGoBack = (RelativeLayout.LayoutParams) imgGoBack.getLayoutParams();
			rlpForImgGoBack.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForImgGoBack.height = (int) (0.066 * Common.sessionDeviceHeight);
			imgGoBack.setLayoutParams(rlpForImgGoBack);
			
    		ImageView imgSave= (ImageView) findViewById(R.id.save_it); 
			RelativeLayout.LayoutParams rlpForImgSave = (RelativeLayout.LayoutParams) imgSave.getLayoutParams();
			rlpForImgSave.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForImgSave.height = (int) (0.066 * Common.sessionDeviceHeight);
			imgSave.setLayoutParams(rlpForImgSave);
			
    		ImageView imgNoThanks = (ImageView) findViewById(R.id.imgNoThanks); 
			RelativeLayout.LayoutParams rlpForNoThanks = (RelativeLayout.LayoutParams) imgNoThanks.getLayoutParams();
			rlpForNoThanks.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForNoThanks.height = (int) (0.066 * Common.sessionDeviceHeight);
			imgNoThanks.setLayoutParams(rlpForNoThanks);
			
    		ImageView imgAddToMyOffers = (ImageView) findViewById(R.id.imgAddToMyOffers); 
			RelativeLayout.LayoutParams rlpForImgAddToMyOffers = (RelativeLayout.LayoutParams) imgAddToMyOffers.getLayoutParams();
			rlpForImgAddToMyOffers.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForImgAddToMyOffers.height = (int) (0.066 * Common.sessionDeviceHeight);
			imgAddToMyOffers.setLayoutParams(rlpForImgAddToMyOffers);
			
			
			final GridView gridView = (GridView) findViewById(R.id.grid_view);  
			RelativeLayout.LayoutParams rlpForGridView = (RelativeLayout.LayoutParams) gridView.getLayoutParams();
			rlpForGridView.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlpForGridView.height = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlpForGridView.bottomMargin = (int) (0.066 * Common.sessionDeviceHeight);
			gridView.setLayoutParams(rlpForGridView);
			
			
	       	
	        file = new FileTransaction();	        
	        Offers offers = file.getOffers();
	        
			aq = new AQuery(this);
			stringArrayOfferCalImages = new ArrayList<String>();	  
			stringArrOfferWithClientIds = new ArrayList<String>();
			stringArrOfferIds = new ArrayList<String>();	  
			stringArrOffersWithLoginValues = new ArrayList<String>();
			stringArrOfferFlagCount = new ArrayList<String>();		

			Intent prointent = getIntent();
			if(prointent.getExtras() != null){
				stringArrOfferFlagCount = prointent.getStringArrayListExtra("stringArrOfferFlagCount");
				stringArrayOfferCalImages = prointent.getStringArrayListExtra("stringArrayOfferCalImages");
				stringArrOfferWithClientIds = prointent.getStringArrayListExtra("stringArrOfferWithClientIds");
				stringArrOfferIds = prointent.getStringArrayListExtra("stringArrOfferIds");
				pageRedirectFlag = prointent.getStringExtra("pageRedirectFlag");	
			
				clientOfferArrListImages = new ArrayList<String>();	  
				clientChkOfferArrListImages = new ArrayList<String>();
				chkArrListImageOfferIds = new ArrayList<String>();

				Log.i("stringArrOfferFlagCount", stringArrOfferFlagCount.size()+" "+stringArrOfferFlagCount.get(0));
			/*if(stringArrayOfferCalImages.size()>0){
				if(Common.isNetworkAvailable(OfferCalendarActivity.this)){
					for(int w=0; w<stringArrayOfferCalImages.size(); w++)
					{	    				
						//Log.i("stringArrayOfferCalImages.get(w)", ""+stringArrayOfferCalImages.get(w));
						clientOfferArrListImages.add(stringArrayOfferCalImages.get(w));
		    			Common.arrOfferIdsForUserAnalytics.add(stringArrOfferIds.get(w));							
		    			
					}
				}else{
					offerListXmlResultFromFile();
				}
			}*/
			
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
	    	imgBtnCart.setImageBitmap(null);
	    	
	    	ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
	    	imgvBtnBack.setImageBitmap(null);
	    	
			
		
			arrOfferExist = new ArrayList<String>();	
			if(stringArrayOfferCalImages.size()>0){
				if(Common.isNetworkAvailable(OfferCalendarActivity.this)){
					for(int w=0; w<stringArrayOfferCalImages.size(); w++)
					{	 			
						UserOffers offerExist = offers.getUserOffers(Integer.parseInt(stringArrOfferIds.get(w)));
						if(offerExist == null){						
							arrOfferExist.add(stringArrOfferIds.get(w));									
						}
					}
					Log.e("arrOfferExist.size()",""+arrOfferExist.size());
					if(arrOfferExist.size()>0){
						if(arrOfferExist.size()==stringArrayOfferCalImages.size()){
						 String ids = TextUtils.join(",", arrOfferExist.toArray());
						 Log.e("ids",ids);
						 getAllClientOffersFromServer(offerUrl+"/"+ids);
						}else{
							for(int w=0; w<stringArrOfferIds.size(); w++)
							{
								if(!arrOfferExist.contains(stringArrOfferIds.get(w))){
									arrOfferExist.add(stringArrOfferIds.get(w));
								}
							}
							 String ids = TextUtils.join(",", arrOfferExist.toArray());
							 Log.e("else ids",ids);
							 getAllClientOffersFromServer(offerUrl+"/"+ids);
						}
					}
					else{	    					
						checkOfferExistinChangeLog();
					}
				}else{
					offerListXmlResultFromFile();
				}
			}
			String joinedWithPipe = TextUtils.join("|", Common.arrOfferIdsForUserAnalytics);
			Log.i("joined", ""+joinedWithPipe);
			String screenName = "/offers/calendar/"+Common.sessionClientId;
			String productIds = "";
			String offerIds = joinedWithPipe;
			Common.sendJsonWithAQuery(this, id, screenName, productIds, offerIds);
			
    		gridView.setOnItemClickListener(new OnItemClickListener() {
    			@Override
    			public void onItemClick(AdapterView<?> parent, View v,
    					final int position, long id) {
    				try{
    				ImageView check = (ImageView)v.findViewById(R.id.check);
    				image = (ImageView)v.findViewById(R.id.image);
    				if(check.getVisibility()==View.INVISIBLE){
        				check.setVisibility(View.VISIBLE);    
        				clientChkOfferArrListImages.add(image.getTag().toString());
        				chkArrListImageOfferIds.add(stringArrOfferIds.get(position));   				
    				} else {
        				check.setVisibility(View.INVISIBLE);   
        				clientChkOfferArrListImages.remove(image.getTag().toString());
        				chkArrListImageOfferIds.remove(stringArrOfferIds.get(position));  				
    				}
    				}catch(Exception e){
    					e.printStackTrace();
    					String errorMsg = className+" | onCreate gridView click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
    				}
    			}    				
    		});
		/*	TextView txtHeadLbl = (TextView) findViewById(R.id.txtHeadLbl);
			txtHeadLbl.setTextSize((float) ((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
*/
    		

			Log.i("stringArrOfferFlagCount", stringArrOfferFlagCount.size()+" "+stringArrOfferFlagCount.get(0));
    		if(stringArrOfferFlagCount.size()>0 && stringArrOfferFlagCount.get(0).equals("My Offers")){
    			//txtHeadLbl.setText("My Offers");
    			//Log.i("txtHeadLbl", txtHeadLbl.getText()+"");
    			imgGoBack.setVisibility(View.INVISIBLE);
    			imgSave.setVisibility(View.INVISIBLE);
        		imgAddToMyOffers.setVisibility(View.VISIBLE);
        		imgNoThanks.setVisibility(View.VISIBLE);
        		imgNoThanks.setOnClickListener(new OnClickListener() {
    				@Override
    				public void onClick(View arg0) {
    					try{ 			
    						if(pageRedirectFlag == null || pageRedirectFlag.equals("null")){
    							finish();
    						}else{
    							if(pageRedirectFlag.equals("RecentlyScanned")){
    								Intent intent = new Intent(OfferCalendarActivity.this, RecentlyScanned.class);									
    								startActivityForResult(intent, 1);
    								finish();
    								overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
    							}
    						}
    					}catch(Exception e){
    						e.printStackTrace();
    						String errorMsg = className+" | onCreate imgNoThanks click |   " +e.getMessage();
    			       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
    					}
    				}
    			});   
        		imgAddToMyOffers.setOnClickListener(new OnClickListener() {
    				@Override
    				public void onClick(View arg0) {
    					if(Common.isNetworkAvailable(OfferCalendarActivity.this)){							
    					if(clientChkOfferArrListImages.size()>0){
								
	    					Log.i("stringArrOfferIds", ""+stringArrOfferIds);
	    					//Log.i("clientChkOfferArrListImages 1 "+w, ""+clientChkOfferArrListImages);
	    					for(w=0; w<stringArrayOfferCalImages.size(); w++){
	    						//Log.i("stringArrayOfferCalImages 1 "+w, ""+stringArrayOfferCalImages.get(w));
	    						if(clientChkOfferArrListImages.contains(stringArrayOfferCalImages.get(w))){
	    							if(getOfferIdsWithSymbol.equals("")){
	    								getOfferIdsWithSymbol = stringArrOfferIds.get(w);
	    							} else {
	    								getOfferIdsWithSymbol += ","+stringArrOfferIds.get(w);
	    							}
	    							getClientId = stringArrOfferWithClientIds.get(w);
	    							//Log.i("stringArrayOfferCalImages "+w, ""+stringArrayOfferCalImages.get(w));
	    							Log.i("getOfferId 1", ""+getOfferIdsWithSymbol+" "+getClientId);
	    						}
	    					}
	    					Log.i("getOfferId 1", ""+getOfferIdsWithSymbol+" "+getClientId);
							stringArrOffersWithLoginValues.add(getOfferIdsWithSymbol);
							stringArrOffersWithLoginValues.add(getClientId);
	    					//new Common().getLoginDialog(OfferCalendarActivity.this, MyOffers.class, "OfferCalendarMyOffers", stringArrOffersWithLoginValues);
	    					saveAndSetCalendarReminders(id, "Yes", stringArrOffersWithLoginValues);

						} else {
	            			Toast.makeText(getApplicationContext(), "Please check atleast one offer.", Toast.LENGTH_LONG).show();
	            		}
    					}else{
								new Common().instructionBox(OfferCalendarActivity.this,R.string.title_case7,R.string.instruction_case7);
							
							}
    				}
    			});   	

    		} else {
        		imgAddToMyOffers.setVisibility(View.INVISIBLE);
        		imgNoThanks.setVisibility(View.INVISIBLE);
    			imgGoBack.setVisibility(View.VISIBLE);
    			imgSave.setVisibility(View.VISIBLE);
        		imgGoBack.setOnClickListener(new OnClickListener() {    				
    				@Override
    				public void onClick(View arg0) {
    					try{
    						if(pageRedirectFlag == null || pageRedirectFlag.equals("null")){
    							finish();
    						}else{
    							if(pageRedirectFlag.equals("RecentlyScanned")){
    								Intent intent = new Intent(OfferCalendarActivity.this, RecentlyScanned.class);									
    								startActivityForResult(intent, 1);
    								finish();
    								overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
    							}
    						}
    					}catch(Exception e){
    						e.printStackTrace();
    						String errorMsg = className+" | onCreate imgGoBack click |   " +e.getMessage();
    			       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
    					}
    				}
    			});    		
        		imgSave.setOnClickListener(new OnClickListener() {				
    				@Override
    				public void onClick(View arg0) {
    					// TODO Auto-generated method stub	
    					try{
    						ProgressBar progressBar = (ProgressBar)findViewById(R.id.progressBar);
    						progressBar.setVisibility(View.VISIBLE);
    						if(Common.isNetworkAvailable(OfferCalendarActivity.this)){
    						if(clientChkOfferArrListImages.size()>0){
    							if(session.isLoggedIn()){									
									saveAndSetCalendarReminders(""+Common.sessionIdForUserLoggedIn, "Yes", stringArrOffersWithLoginValues);
								} else {			    					
							    	// Session class instance
							       	session = new SessionManager(OfferCalendarActivity.this);
							        pDialog = new ProgressDialog(OfferCalendarActivity.this);
							        pDialog.setMessage("Loading...");
							        pDialog.setIndeterminate(false);
							        pDialog.setCancelable(false);
			    					for(w=0; w<stringArrayOfferCalImages.size(); w++){
			    						if(clientChkOfferArrListImages.contains(stringArrayOfferCalImages.get(w))){
			    							if(getOfferIdsWithSymbol.equals("")){
			    								getOfferIdsWithSymbol = stringArrOfferIds.get(w);
			    							} else {
			    								getOfferIdsWithSymbol += ","+stringArrOfferIds.get(w);
			    							}
			    							getClientId = stringArrOfferWithClientIds.get(w);
			    							Log.i("getOfferId 1", ""+getOfferIdsWithSymbol+" "+getClientId);
			    						}
			    					}
	    							stringArrOffersWithLoginValues.add(getOfferIdsWithSymbol);
	    							stringArrOffersWithLoginValues.add(getClientId);
									new Common().getLoginDialog(OfferCalendarActivity.this, MyOffers.class, "OfferCalendarMyOffers", stringArrOffersWithLoginValues);						    
								}
    						} else {
                    			Toast.makeText(getApplicationContext(), "Please check atleast one offer.", Toast.LENGTH_LONG).show();
                    		}
    						}else{
    							new Common().instructionBox(OfferCalendarActivity.this,R.string.title_case7,R.string.instruction_case7);
    						}
    					} catch (Exception e){
    						e.printStackTrace();    						
    						String errorMsg = className+" | onCreate imgSave click |   " +e.getMessage();
    			       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
    					}
    				}
    			});
    		}
			}
		} catch(Exception e){
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: OfferCalendarActivity onCreate.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onCreate |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		}
	}
	private void checkOfferExistinChangeLog() {
		try{ 
	  		   //function 2 to check whether offer exist in change log
	  			Log.i("trigger checkProductExistinChangeLog","trigger checkProductExistinChangeLog");	 					
				/*ChangeLogModel changeLogModelData = file.getChangeLog();		
				final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
				if(userChangeLogList.size() > 0){
 	  			Log.e("userChangeLogList",""+userChangeLogList.size());    	 			
	    			for(UserChangeLog userChangeLog : userChangeLogList){	
	    				Log.e("userChangeLog.getOfferId()",""+userChangeLog.getOfferId()+"offerId"+stringArrOfferIds); 
		    			if(stringArrOfferIds.contains(""+userChangeLog.getOfferId())){
		    				changeFlag = true;		    			
		    				 String ids = TextUtils.join(",", stringArrOfferIds.toArray());
		    				getAllClientOffersFromServer(offerUrl+"/"+ids);			
		    				}    			 
		    			}	    			
	    			}*/	  			
	  		String ids = TextUtils.join(",", stringArrOfferIds.toArray()); 					
	  		changeFlag = new Common().checkOfferExistinChangeLog("offerCalender",ids);
    		if(!changeFlag){ 				
 				offerListXmlResultFromFile();
 			}else{
 				getAllClientOffersFromServer(offerUrl+"/"+ids);	
 			}
	    			
 		}catch(Exception e){
 			e.printStackTrace();
 			String errorMsg = className+" | changeLogResultFromServer |   " +e.getMessage();
        	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
 		}    
		
	}
	private void offerListXmlResultFromFile() {
		try{
			Offers offers = file.getOffers();
			
			if(stringArrayOfferCalImages.size()>0){				 	  
				stringArrOfferWithClientIds = new ArrayList<String>();
				for(int w=0; w<stringArrayOfferCalImages.size(); w++)
				{
					Log.e("stringArrayOfferCalImages",""+stringArrayOfferCalImages);
					UserOffers offerExist = offers.getUserOffers(Integer.parseInt(stringArrOfferIds.get(w)));
					if(offerExist != null){
						Log.e("offerExist.getOfferImage()",""+offerExist.getOfferImage());						
   	    				 clientOfferArrListImages.add(offerExist.getOfferImage());
   	    				 stringArrOfferWithClientIds.add(String.valueOf(offerExist.getOfferClientId()));
		        		  
					}
				}
				if(stringArrayOfferCalImages.size()>0){       	 				
	    				render(clientOfferArrListImages);
	    				 ARDisplayActivity.endnow = android.os.SystemClock.uptimeMillis();
							Log.e("MYTAG calendar ", "Excution time: "+(ARDisplayActivity.endnow-ARDisplayActivity.startnow)/1000+" s");
							
	    			}	
			}
		}catch(Exception e){
			e.printStackTrace();
		}
		
	}
	ArrayList<String> stringArrayOfferSelected;
	String getOfferIdsWithSymbol = "";
	int insertSuccessFlag = 0;
	int insertAlreadyFlag = 0;
	int insertFailedFlag = 0;
	public void saveAndSetCalendarReminders(final String userId, final String checkFlag, ArrayList<String> stringArrayList2){
		try{
			Map<String, Object> params = new HashMap<String, Object>();
			stringArrayOfferSelected = new ArrayList<String>();
			Log.i("stringArrayList2", stringArrayList2.size()+" "+stringArrayList2);
			if(stringArrayList2.size()>0){
				/*for(int v=0; v<stringArrayList2.size(); v++){
					Log.i("stringArrayList2 "+v, ""+stringArrayList2.get(v));		
				}*/
				getOfferIdsWithSymbol = stringArrayList2.get(0);
				getClientId = stringArrayList2.get(1);
				Log.i("getOfferId", ""+getOfferIdsWithSymbol+" "+getClientId);
			}
			else if(clientChkOfferArrListImages.size()>0){
				for(w=0; w<stringArrayOfferCalImages.size(); w++){
					Log.i("stringArrayOfferCalImages "+w, ""+stringArrayOfferCalImages.get(w));
					if(clientChkOfferArrListImages.contains(stringArrayOfferCalImages.get(w))){
						if(getOfferIdsWithSymbol.equals("")){
							getOfferIdsWithSymbol = stringArrOfferIds.get(w);
						} else {
							getOfferIdsWithSymbol += ","+stringArrOfferIds.get(w);
						}
						getClientId = stringArrOfferWithClientIds.get(w);
						//Log.i("stringArrayOfferCalImages "+w, ""+stringArrayOfferCalImages.get(w));
						Log.i("getOfferId", ""+getOfferIdsWithSymbol+" "+getClientId);
					}
				}
			} else {
				Toast.makeText(aq.getContext(), "Please check atleast one offer.", Toast.LENGTH_LONG).show();
			}

			if(!getClientId.equals("null") && !getOfferIdsWithSymbol.equals("")){
            	//Log.i("id", ""+id);
			    params.put("clientId", getClientId);
			    //Log.i("userId", ""+userId);
			    params.put("userId", userId);
			    params.put("checkFlag", checkFlag);
			    params.put("offerIdsWithSymbol", getOfferIdsWithSymbol);
            	//Log.i("params", ""+params);
            	aq = new AQuery(this);
				aq.ajax(Constants.OfferInfo_Url, params, XmlDom.class, new AjaxCallback<XmlDom>() {
	                @Override
	                public void callback(String url, XmlDom xml, AjaxStatus status) {
	                	try{
	                    	//Log.i("xml", ""+xml);
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
			                    		getOfferName = entry.text("offer_name").toString();
										getClientId = entry.text("client_id").toString();
										if(!entry.text("offer_short_description").toString().equals("null")){
											getOfferShortDesc = entry.text("offer_short_description").toString();
										}
										getOfferDesc = entry.text("offer_description").toString();
										getOfferCalEventStartDate = entry.text("offer_info_event_start").toString();
										getOfferCalEventEndDate = entry.text("offer_info_event_end").toString();
										getOfferCalEventHasAlarm = entry.text("offer_info_event_has_alarm").toString();
										getOfferCalEventAllDay = entry.text("offer_info_event_allday").toString();
										getOfferCalReminderDays = entry.text("offer_info_reminder_days").toString();
										if(entry.text("offer_is_calendar_based").toString().equals("1")){
											createAnEvent(getOfferName,
													getOfferDesc,
													getOfferCalEventStartDate,
													getOfferCalEventEndDate,
													getOfferCalEventHasAlarm,
													getOfferCalEventAllDay,
													getOfferCalReminderDays);											
										}
										Log.i("checkFlag", ""+checkFlag);
										Log.i("insertMsg", ""+entry.text("insertMsg").toString());
										if(checkFlag.equals("Yes")){
											if(entry.text("insertMsg").toString().equals("success")){
												insertSuccessFlag++;
												insertAlreadyFlag = 0;
												insertFailedFlag = 0;
											} else if(entry.text("insertMsg").toString().equals("already")){
												insertAlreadyFlag++;
												insertSuccessFlag = 0;
												insertFailedFlag = 0;												
											} else {
												insertFailedFlag++;
												insertAlreadyFlag = 0;
												insertSuccessFlag = 0;												
											}
											
											
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
										Log.i("clientChkOfferArrListImages", ""+t+"=="+clientChkOfferArrListImages.size());
										Log.i("entries.size()", ""+entries.size()+"=="+insertSuccessFlag+" "+insertAlreadyFlag+" "+insertFailedFlag);
										Log.i("entries.size() all", ""+entries.size()+"=="+(insertSuccessFlag+insertAlreadyFlag+insertFailedFlag));
										if(t==clientChkOfferArrListImages.size()){
											ProgressBar progressBar = (ProgressBar)findViewById(R.id.progressBar);
				    						progressBar.setVisibility(View.INVISIBLE);
											t=0;
											if(insertSuccessFlag>0 || entries.size()==insertSuccessFlag || entries.size()==(insertSuccessFlag+insertAlreadyFlag+insertFailedFlag)){
	    	    								//Toast.makeText(getApplicationContext(), "Offer(s) added successfully to Wish List.", Toast.LENGTH_LONG).show();
	    	    								Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
	        								    intent.putExtra("offerName", "My Offers" );	        								    
	        					                startActivity(intent);
	        					                overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
	        					      	      	finish();
	    	    							} else if(entries.size()==insertAlreadyFlag || entries.size()==(insertSuccessFlag+insertAlreadyFlag+insertFailedFlag)){
												//Toast.makeText(getApplicationContext(), "Already have same Offer(s) in this my offers.", Toast.LENGTH_LONG).show();
	    	    								Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
	        								    intent.putExtra("offerName", "My Offers" );	        								   
	        								    startActivity(intent);
	        								    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
	        					      	      	finish();
											} else if(entries.size()==insertFailedFlag || entries.size()==(insertSuccessFlag+insertAlreadyFlag+insertFailedFlag)){
	    	    								Toast.makeText(getApplicationContext(), "Offer(s) adding failed. Please try again!", Toast.LENGTH_LONG).show();
	    	    								finish();
	    	    							} else{
	    	    								finish();
	    	    							}
											getOfferIdsWithSymbol = "";
											insertSuccessFlag = 0;
											insertAlreadyFlag = 0;
											insertFailedFlag = 0;
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
	                    		} else {
	                    			Toast.makeText(aq.getContext(), "Please check atleast one offer.", Toast.LENGTH_LONG).show();
	                    		}               
		                    }else{
		                    	try{
		                    		Toast.makeText(aq.getContext(), "Error:" + status.getCode(), Toast.LENGTH_LONG).show();
		                    	} catch(Exception e){
		                    		e.printStackTrace();
		                    	}
		                    }
	                	} catch(Exception e){
	                		e.printStackTrace();
	                	}
	                }
				});			
			}			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | saveAndSetCalendarReminders |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		}
	}	
	
	Context ctx = this;
	ContentValues values;
	@TargetApi(Build.VERSION_CODES.ICE_CREAM_SANDWICH)
	public void createAnEvent(String getProductName2, String getProductDesc2, String getOfferCalEventStartDate2, String getOfferCalEventEndDate2, String getOfferCalEventHasAlarm2, String getOfferCalEventAllDay2, String getOfferCalReminderDays2){
		try{
		Calendar cal = Calendar.getInstance();
		TimeZone tz = cal.getTimeZone();
		cal.setTimeZone(tz);
		java.sql.Timestamp tsStart = java.sql.Timestamp.valueOf(getOfferCalEventStartDate2);
	    java.sql.Timestamp tsEnd = java.sql.Timestamp.valueOf(getOfferCalEventEndDate2);        

	    long startTime = tsStart.getTime();
	    long endTime = tsEnd.getTime();
		
		ContentResolver cr = ctx.getContentResolver();
        values = new ContentValues();
		values.put(CalendarContract.Events.DTSTART, startTime);
		values.put(CalendarContract.Events.DTEND, endTime);
		values.put(CalendarContract.Events.TITLE, getProductName2);
		values.put(CalendarContract.Events.DESCRIPTION, getProductDesc2);		
		TimeZone timeZone = TimeZone.getDefault();
		values.put(CalendarContract.Events.EVENT_TIMEZONE, timeZone.getID());		
		  // default calendar
		values.put(CalendarContract.Events.CALENDAR_ID, 1);		
		values.put(CalendarContract.Events.RRULE, "FREQ=DAILY;COUNT=1;BYDAY=SU,MO,TU,WE,TH,FR,SA;WKST=MO");
		//for one hour
		//values.put(CalendarContract.Events.DURATION, "+P1H");		
		values.put(CalendarContract.Events.HAS_ALARM, getOfferCalEventHasAlarm2);
		// insert event to calendar
		Uri eventUri = cr.insert(CalendarContract.Events.CONTENT_URI, values);
		
		String[] splitOfReminderDays = getOfferCalReminderDays2.split(",");
		//Log.i("splitOfReminderDays", splitOfReminderDays[0]+" "+splitOfReminderDays[1]);
		if(splitOfReminderDays.length>0){
			for(int s=0; s<splitOfReminderDays.length; s++){
				setCustomReminder( (int)Long.parseLong(eventUri.getLastPathSegment()), Integer.parseInt(splitOfReminderDays[s]) * 24*60);			
			}
		}
		//After this line, all devices see this reminder
		//setCustomReminder((int)Long.parseLong(eventUri.getLastPathSegment()), (int) (1 * 24*60));
		//After this line, locally there will be two reminders but on other devices
		//this 15 minute reminder will REPLACE the original reminder!
		//setCustomReminder((int)Long.parseLong(eventUri.getLastPathSegment()), (int) (2 * 24*60));
		//finish();
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | createAnEvent |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		}
	}
	
	@TargetApi(Build.VERSION_CODES.ICE_CREAM_SANDWICH)
	public void setCustomReminder(int eventId, int minutes) {
		try{
		//Uri REMINDERS_URI = Uri.parse(getCalendarUriBase(this) + "reminders");
		values = new ContentValues();
	    values.put(Reminders.EVENT_ID, eventId);
	    values.put(Reminders.MINUTES, minutes);
	    values.put(Reminders.METHOD, Reminders.METHOD_ALERT);

	    ctx.getContentResolver().insert(Reminders.CONTENT_URI, values);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | setCustomReminder |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		}
	}
	private void render(ArrayList<String> stringArrayOfferCalImages){	    	
		try{
	    AQUtility.debug("render setup");	    	
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, R.layout.griditem, stringArrayOfferCalImages){				
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {			
				if(convertView == null){
					convertView = aq.inflate(convertView, R.layout.griditem, parent);
				}	

				String photo = getItem(position);	
				ImageView img =(ImageView) convertView.findViewById(R.id.image);
				RelativeLayout.LayoutParams rlpForImg = (RelativeLayout.LayoutParams) img.getLayoutParams();
				//rlpForImg.width = (int) (.8334* Common.sessionDeviceWidth);
				rlpForImg.height = (int) (0.287 * Common.sessionDeviceHeight);
				img.setLayoutParams(rlpForImg);
				img.setTag(photo);
				//Log.i("position", ""+position);
				//Log.i("photo", ""+photo);
				AQuery aq2 = new AQuery(convertView);		
				String tbUrl = photo;					
				Bitmap placeholder = aq2.getCachedImage(tbUrl);
				if(placeholder==null){
					aq2.id(R.id.image).progress(R.id.progressBarGrid).image(tbUrl, false, false);
					aq2.cache(tbUrl, 14400000);					
				}else{
					img.setImageBitmap(placeholder);
				}
				
				//aq2.id(R.id.image).progress(R.id.progressBarGrid).image(tbUrl, true, true, (int) (0.334 * Common.sessionDeviceWidth), 0, placeholder, 0, 0.75f);
			//	aq2.id(R.id.image).progress(R.id.progressBarGrid).image(tbUrl, true, true, 600, 0, placeholder, 0, 0.75f);
				//aq2.id(R.id.image).image(placeholder, 0.75f);
				
				
				//img.setImageBitmap(placeholder);
				/*if(convertView == null){
					convertView = aq.inflate(convertView, R.layout.griditem, parent);
				}					
				String photo = getItem(position);					
				AQuery aq = aq2.recycle(convertView);					
				String tbUrl = photo;					
				//Bitmap placeholder = aq.getCachedImage(R.drawable.image_ph);					
				if(aq.shouldDelay(position, convertView, parent, tbUrl)){								
					//aq.id(R.id.tb).image(placeholder);
					aq.id(R.id.image).clear();						
				}else{						
					aq.id(R.id.image).image(tbUrl, true, true, 200, R.drawable.loader, null, 0, 0);
					ImageView img =(ImageView) convertView.findViewById(R.id.image);
					img.setTag(photo);						
				}*/					
				return convertView;					
			}			
		};			
		aq.id(R.id.grid_view).adapter(aa);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | render |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		}
	}
	
	  public void getAllClientOffersFromServer(String offerUrl){
		  try{
		  final AQuery aq1 = new AQuery(OfferCalendarActivity.this);
		    arrOfferFlagCount = new ArrayList<String>();
		    clientOfferArrListImages = new ArrayList<String>();
		    stringArrayOfferCalImages = new ArrayList<String>();	  
			stringArrOfferWithClientIds = new ArrayList<String>();
			stringArrOfferIds = new ArrayList<String>();	 
			Log.e("offerUrl",offerUrl);
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
	       	    				for(XmlDom myOfferXml: entries){	 
	       	    				 String curveImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
	       	    				 stringArrayOfferCalImages.add(curveImagesUrl);
	       	    				 clientOfferArrListImages.add(curveImagesUrl);
	       	    				 stringArrOfferWithClientIds.add(String.valueOf(myOfferXml.text("client_id").toString()));
	       	    				 stringArrOfferIds.add(String.valueOf(myOfferXml.text("offer_id").toString()));
				        		    	
				        				 Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
											if(bitmap == null){
												aq.cache(curveImagesUrl, 1440000);
											}
				        				  String symbol = new Common().getCurrencySymbol(myOfferXml.text("country_languages").toString(), myOfferXml.text("country_code_char2").toString());
				        				  UserOffers checkUserOffer = offers.getUserOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
				        				   if(changeFlag){
				        						  if(checkUserOffer != null){
				        							  offers.removeItem(checkUserOffer);
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
	       	    			if(stringArrayOfferCalImages.size()>0){       	 				
	       	    				render(clientOfferArrListImages);
	       	    			}	
	        		    }
	        		    }	
	        		   }catch(Exception e){
	        			   e.printStackTrace();
	        			   String errorMsg = className+" | getAllClientOffersFromServer ajax call back    |   " +e.getMessage();
	        			   Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
	        			   
	        		   }
	        	}  		
	        	
	        	
		  });
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getAllClientOffersFromServer    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		  }
	  }

	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
	    String[] segments = new String[1];
		segments[0] = "Offer Calendar Visual"; 
		QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception  e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
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
	       	 Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(OfferCalendarActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(OfferCalendarActivity.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(OfferCalendarActivity.this,errorMsg);
			}
		}
	 
}
