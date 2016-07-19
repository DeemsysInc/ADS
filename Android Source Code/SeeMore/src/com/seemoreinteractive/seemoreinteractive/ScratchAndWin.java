package com.seemoreinteractive.seemoreinteractive;


import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Bitmap.Config;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.PorterDuff.Mode;
import android.graphics.PorterDuffXfermode;
import android.graphics.Rect;
import android.graphics.RectF;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.SurfaceView;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.a.a.a.e.f.a;
import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.callback.BitmapAjaxCallback;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.UserMyOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.helper.WScratchView;
import com.seemoreinteractive.seemoreinteractive.library.ScracthListAdapter;

public class ScratchAndWin extends  Activity {
	private WScratchView scratchView;
	private TextView percentageView,txtvScratch;
	private float mPercentage;
	String className = getClass().getSimpleName();
	ImageView offerImage; 
	String gameId,offerId="null",logo="null";
	ProgressBar progressBar;
	RelativeLayout offerLayout;
	Button btnaddoffer,btnGameRules;
	TextView txtvMess,txtvOfferName,offerDiscount,txtvDisclaimer;
	int imageWidth,imageHeight,offerImageWidth,offerImageHeight,newWidth,newHeight,imageXPos,imageYPos,newXPos,newYPos;
	JSONArray jsonObject;
	AsyncTask<String, String, String> asynTask;
	public static ArrayList<String> aList  = new ArrayList<String>(); 
	ArrayList<String> arrOfferId=  new ArrayList<String>();
	ArrayList<String> arrListForCheckedProdImages =  new ArrayList<String>();
	ListView listViewScratch;
	boolean imageFlag = true;
	String clientName,clientId;
	public static Activity scratchWin;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_scratch_and_win);
		try{	
			aList  = new ArrayList<String>(); 
			scratchWin = this;
			jsonObject = new JSONArray(getIntent().getStringExtra("json"));		
			JSONArray clientInfoArray = new JSONArray(jsonObject.getJSONObject(0).getString("client_info"));
			if(clientInfoArray.length() > 0){
				for(int i=0;i<clientInfoArray.length();i++){					
					 JSONObject json_obj = clientInfoArray.getJSONObject(i);	
					 Common.sessionClientLogo      = json_obj.getString("logo");
					 Common.sessionClientBgColor   = json_obj.getString("background_color");
					 Common.sessionClientBackgroundLightColor  = json_obj.getString("light_color");
					 Common.sessionClientBackgroundDarkColor   = json_obj.getString("dark_color");
					 clientName =  json_obj.getString("name");
					 clientId   =  json_obj.getString("id");
				}				
			}			
			
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					ScratchAndWin.this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "");
			 
			gameId = getIntent().getStringExtra("gameId");			
			
			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						aList.clear();
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCameraIcon click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
					}
				}
			});
			ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);
			imgvBtnBack.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						aList.clear();
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnBack click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
					}
				}
			});
			
			ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			imgvBtnShare.setImageAlpha(0);
			
			ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);		
			
			getDynamicLayout();
	
			offerImageWidth  =  (int)(0.8334 * Common.sessionDeviceWidth);
			offerImageHeight = (int)(0.308 * Common.sessionDeviceHeight);	
			
			
			/* listViewScratch = (ListView) findViewById(R.id.listViewScratch);
			 RelativeLayout.LayoutParams rlplistViewScratch = (RelativeLayout.LayoutParams) listViewScratch.getLayoutParams();
			 rlplistViewScratch.height = (int) (0.205 * Common.sessionDeviceHeight);
			 listViewScratch.setLayoutParams(rlplistViewScratch);*/
			
			/*txtvDisclaimer = (TextView)findViewById(R.id.txtvDisclaimer);
			RelativeLayout.LayoutParams rlpTxtvDisclaimer = (RelativeLayout.LayoutParams) txtvDisclaimer.getLayoutParams();
			rlpTxtvDisclaimer.bottomMargin = (int) (0.03074 * Common.sessionDeviceHeight);
			txtvDisclaimer.setLayoutParams(rlpTxtvDisclaimer);
			txtvDisclaimer.setTextSize((float) ((0.025 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			btnaddoffer.setOnClickListener(new OnClickListener() {	
						@Override
						public void onClick(View v) {	
							try{
								if(Common.isNetworkAvailable(ScratchAndWin.this)){				
									Log.e("arrListForCheckedProdImages",""+arrListForCheckedProdImages);
									ArrayList<String> stringArrayList =  new ArrayList<String>();
									if(!offerId.equals("null")){
										stringArrayList.add(offerId);
										stringArrayList.add("OfferView");
										new Common().getLoginDialog(ScratchAndWin.this, MyOffers.class, "OfferViewMyOffers", stringArrayList );
									}
								}
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | btnaddoffer click  |   " +e.getMessage();
					       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
							}
						}
					});
		*/
			updatePercentage(0f);
			//asynTask = new AsyncTaskRunner().execute();
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | oncreate click  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
		}
	}
	private void getDynamicLayout() {
		try{
			
			RelativeLayout relativeLay = (RelativeLayout)findViewById(R.id.relativeLay);			
			RelativeLayout.LayoutParams rlpRelativeLay = (RelativeLayout.LayoutParams) relativeLay.getLayoutParams();
			//rlpRelativeLay.width  	 = (int) (0.8334* Common.sessionDeviceWidth);
			rlpRelativeLay.height	 = (int) (0.8402  * Common.sessionDeviceHeight);
			relativeLay.setLayoutParams(rlpRelativeLay);
			
			RelativeLayout relativeSingleLay = (RelativeLayout)findViewById(R.id.relativeSingleLay);
			RelativeLayout relativeMultiLay = (RelativeLayout)findViewById(R.id.relativeMultiLay);	
			if(jsonObject.length()>1){
				progressBar = (ProgressBar)findViewById(R.id.progressBar1);
				relativeSingleLay.setVisibility(View.INVISIBLE);
				relativeMultiLay.setVisibility(View.VISIBLE);
				
				txtvMess = (TextView)findViewById(R.id.txtvMess);
				RelativeLayout.LayoutParams rlptxtvMess = (RelativeLayout.LayoutParams) txtvMess.getLayoutParams();			
				//rlptxtvMess.topMargin = (int) (0.0882 * Common.sessionDeviceHeight);
				txtvMess.setLayoutParams(rlptxtvMess);
				txtvMess.setTextSize((float) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
				
				offerImage =  (ImageView) findViewById(R.id.offerImage1); 
				RelativeLayout.LayoutParams rlpofferImage = (RelativeLayout.LayoutParams) offerImage.getLayoutParams();			
				offerImage.setLayoutParams(rlpofferImage);
				
				offerLayout  =  (RelativeLayout) findViewById(R.id.offerLayout1); 
				RelativeLayout.LayoutParams rlpOfferLayout = (RelativeLayout.LayoutParams) offerLayout.getLayoutParams();
				rlpOfferLayout.width  	 = (int) (0.8334* Common.sessionDeviceWidth);
				rlpOfferLayout.height	 = (int) (0.308  * Common.sessionDeviceHeight);
				rlpOfferLayout.topMargin = (int) (0.041 * Common.sessionDeviceHeight);			
				offerLayout.setLayoutParams(rlpOfferLayout);
				
				 listViewScratch = (ListView) findViewById(R.id.listViewScratch);
				 RelativeLayout.LayoutParams rlplistViewScratch = (RelativeLayout.LayoutParams) listViewScratch.getLayoutParams();
				 rlplistViewScratch.height = (int) (0.205 * Common.sessionDeviceHeight);
				 listViewScratch.setLayoutParams(rlplistViewScratch);
				 
				 
			    btnaddoffer = (Button)findViewById(R.id.btnOffer);
				RelativeLayout.LayoutParams rlpBtnaddoffer = (RelativeLayout.LayoutParams) btnaddoffer.getLayoutParams();
				rlpBtnaddoffer.width = (int) (0.5* Common.sessionDeviceWidth);
				rlpBtnaddoffer.height = (int) (0.0615 * Common.sessionDeviceHeight);
				rlpBtnaddoffer.bottomMargin = (int) (0.023565 * Common.sessionDeviceHeight);
				btnaddoffer.setLayoutParams(rlpBtnaddoffer);					
				btnaddoffer.setOnClickListener(new OnClickListener() {						
						@Override
						public void onClick(View v) {
							try{
								if(Common.isNetworkAvailable(ScratchAndWin.this)){				
									/*ArrayList<String> stringArrayList =  new ArrayList<String>();
									if(!offerId.equals("null")){
										stringArrayList.add(offerId);
										stringArrayList.add("OfferView");
										new Common().getLoginDialog(ScratchOffer.this, MyOffers.class, "OfferViewMyOffers", stringArrayList );
									}*/
									saveMutlipleOffer();
									
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
							try{							
								Intent intent = new Intent(ScratchAndWin.this,GameRules.class);
								intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
								intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
								startActivity(intent);
							 }catch(Exception e){
								e.printStackTrace();
							 }
						  }
					});				
				
			}else{			
				progressBar = (ProgressBar)findViewById(R.id.progressBar);
				relativeSingleLay.setVisibility(View.VISIBLE);
				relativeMultiLay.setVisibility(View.INVISIBLE);
				offerImage =  (ImageView) findViewById(R.id.offerImage); 
				RelativeLayout.LayoutParams rlpofferImage = (RelativeLayout.LayoutParams) offerImage.getLayoutParams();			
				offerImage.setLayoutParams(rlpofferImage);
				
				offerLayout  =  (RelativeLayout) findViewById(R.id.offerLayout); 
				RelativeLayout.LayoutParams rlpOfferLayout = (RelativeLayout.LayoutParams) offerLayout.getLayoutParams();
				rlpOfferLayout.width  	 = (int) (0.8334* Common.sessionDeviceWidth);
				rlpOfferLayout.height	 = (int) (0.308  * Common.sessionDeviceHeight);
				//rlpOfferLayout.topMargin = (int) (0.041 * Common.sessionDeviceHeight);			
				offerLayout.setLayoutParams(rlpOfferLayout);
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getDynamicLayout  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
		}
		
	}
	Handler mHandler;
	
	
	@Override
	protected void onStart() {
		super.onStart();
		try{
			/*
			if(imageFlag){
				displayGameOfferNew();
			}*/
			/*mHandler = new Handler();
			mHandler.postDelayed(new Runnable() {
		        public void run() {
		        	Log.e("asynTask",""+asynTask.getStatus());
		        	if(asynTask.getStatus() == AsyncTask.Status.FINISHED){        	
		        		 try{
		        			 displayGameOffer();
		        		 }catch(Exception e){
		        			   e.printStackTrace();
		        		   }
		        		 mHandler.removeCallbacksAndMessages(null);
		        	}else{
		        		mHandler.postDelayed(this, 2000);
		        	}
		        	
		        }
		    }, 2000);*/
			mHandler = new Handler();
			mHandler.postDelayed(new Runnable() {
		        public void run() {       	
	        		 try{
	        			 if(imageFlag){
	        					displayGameOfferNew();
	        				}
	        		 }catch(Exception e){
	        			   e.printStackTrace();
	        		  }
		        }
		    }, 1000);
			
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	  JSONArray json;
	  int scratchWidth, scratchHeight,newScratchWidth,newScratchHeight;
	  String[] SizeArray; 
	  Bitmap scrImageBitmap ,backgroundBitmap,resizedBitmap ;
	  Drawable d;
    
	 private class AsyncTaskRunner extends AsyncTask<String, String, String> {

		  private String resp;
		  
	 @Override
	  protected String doInBackground(String... params) {
	   try {
			int i=0;
			Bundle b = getIntent().getExtras();
			String jsons=b.getString("json");
			json = new JSONArray(jsons);
			final JSONObject json_obj = json.getJSONObject(i);
			offerId =  json_obj.getString("offer_id").replaceAll(" ", "%20");
			Log.e("offerId", offerId);
			
			String value =  json_obj.getString("value");
		    SizeArray = value.split(",");
			
			if(json.getJSONObject(i).optString("game_image")!=null){					
				String scratchImagesUrl = json_obj.getString("scratch_image").replaceAll(" ", "%20");			
				 curveImagesUrl = json_obj.getString("game_image").replaceAll(" ", "%20");
				 final AQuery aq= new AQuery(ScratchAndWin.this);
				 
				 URL newurl = new URL(curveImagesUrl); 
				 backgroundBitmap = BitmapFactory.decodeStream(newurl.openConnection().getInputStream());
				
				 URL scrImageUrl = new URL(scratchImagesUrl); 
				 scrImageBitmap = BitmapFactory.decodeStream(scrImageUrl.openConnection().getInputStream());
				 resp = "true";
		            
		   }
	    
	   }catch (Exception e) {
	    e.printStackTrace();	    
	   }
	   return resp;
	  }

	 }
	 
	 
	 public void displayGameOffer(){
			try{
				Bundle b = getIntent().getExtras();
				String jsons=b.getString("json");
				final JSONArray json = new JSONArray(jsons);
				if(json.length()>0){
					final WScratchView[] scratchView = new WScratchView[json.length()];
					for(int i=0;i<json.length();i++)
					{
						 Log.e("i","loop "+i);
						final int  index =i;
						final JSONObject json_obj = json.getJSONObject(i);
						offerId =  json_obj.getString("offer_id").replaceAll(" ", "%20");
						
						String value =  json_obj.getString("value");
						final String[] SizeArray = value.split(",");
						
						if(json.getJSONObject(i).optString("game_image")!=null){
							curveImagesUrl = json_obj.getString("game_image").replaceAll(" ", "%20");
							 final AQuery aq = new AQuery(ScratchAndWin.this);
							 /*aq.id(R.id.offerImage).image(curveImagesUrl, true, true, 0, 0, new BitmapAjaxCallback(){
							        @Override
							        public void callback(String url, ImageView iv, Bitmap bm, AjaxStatus status){
							        	try{*/
							 
							 aq.ajax(curveImagesUrl, Bitmap.class, new AjaxCallback<Bitmap>() {
						         @Override
						         public void callback(String url, Bitmap bm, AjaxStatus status) {
						        	 try{
							        		/*URL newurl = new URL(curveImagesUrl); 
							        		Bitmap backgroundBitmap = BitmapFactory.decodeStream(newurl.openConnection().getInputStream());
							        		*/
							        		
							        		final Bitmap backgroundBitmap = bm;
							        		
							        		String scratchImagesUrl = json_obj.getString("scratch_image").replaceAll(" ", "%20");
							        		 aq.ajax(scratchImagesUrl, Bitmap.class, new AjaxCallback<Bitmap>() {
										         @Override
										         public void callback(String url, Bitmap bm, AjaxStatus status) {
										        	 try{
							        		
							        		//URL scrImageUrl = new URL(scratchImagesUrl); 
											//Bitmap scrImageBitmap = BitmapFactory.decodeStream(scrImageUrl.openConnection().getInputStream());
							        		/*Bitmap scrImageBitmap = aq.getCachedImage(scratchImagesUrl);
							        		if(scrImageBitmap == null){
							        			  aq.cache(scratchImagesUrl, 14400000);
							        			  scrImageBitmap = aq.getCachedImage(scratchImagesUrl);
							        		}*/
											 imageWidth  = backgroundBitmap.getWidth();
									         imageHeight = backgroundBitmap.getHeight();	
									         newWidth = imageWidth;
									         newHeight = imageHeight;
					         
								             if(imageWidth > offerImageWidth){
								            	newWidth  = offerImageWidth;
								            	newHeight =  newWidth * imageHeight/imageWidth;
								             }
								             if(newHeight > offerImageHeight){
								            	newHeight = offerImageHeight;
								            	newWidth  = newHeight * imageWidth/imageHeight;					            	
								             }
					            
									         Bitmap resizedBitmap = Bitmap.createScaledBitmap(backgroundBitmap, newWidth, newHeight, false);
									         ImageView offerImage = (ImageView)findViewById(R.id.offerImage);
									            
									         offerImage.setPadding((int)(0.00667 * Common.sessionDeviceWidth),(int)(0.0041 * Common.sessionDeviceHeight),(int)(0.00667 * Common.sessionDeviceWidth),(int)(0.0041 * Common.sessionDeviceHeight));
									         offerImage.setImageBitmap(resizedBitmap);
									         offerImage.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
									         //offerImage.setLayoutParams(params);
							        	/*}catch(Exception e){
							        		e.printStackTrace();
							        	}
							        }
							    });
							    String scratchImagesUrl = json_obj.getString("scratch_image").replaceAll(" ", "%20");
								aq.ajax(scratchImagesUrl, Bitmap.class, new AjaxCallback<Bitmap>() {
							         @Override
							         public void callback(String url, Bitmap scrImageBitmap, AjaxStatus status) {
							        	 try{*/
									         Log.e("newHeight",newHeight+"newWidth"+newWidth);
									         imageXPos = Integer.parseInt(json_obj.getString("x"));
									         imageYPos = Integer.parseInt(json_obj.getString("y"));
									           
									         newXPos = newWidth * imageXPos /imageWidth;
									         newYPos = newHeight * imageYPos /imageHeight;
									         newYPos = newYPos +(int)(0.0041 * Common.sessionDeviceHeight);
								           // newXPos = newXPos +(int)(0.00667 * Common.sessionDeviceWidth);
								            Log.e("newXPos",newXPos+"newYPos"+newYPos);
								            Log.e("scratch_type",json_obj.getString("scratch_type"));
								            if(scrImageBitmap != null){
								        	     int scratchWidth  = Integer.parseInt(SizeArray[0]);
								        	     int scratchHeight = Integer.parseInt(SizeArray[1]);
					        	     
								        	     int newScratchWidth = newWidth * scratchWidth /imageWidth;
								        	     int newScratchHeight = newHeight * scratchHeight /imageHeight;
								        	     SurfaceView srfc = new SurfaceView(ScratchAndWin.this);
					        	    
								        	     	if(SizeArray.length > 0){									
													  Log.e("scratch_type",json_obj.getString("scratch_type"));
													  if(json_obj.getString("scratch_type").equals("C")){
															// scrImageBitmap =  getRoundedCroppedBitmap(scrImageBitmap,newScratchWidth);
														  scrImageBitmap =  getCroppedBitmap(scrImageBitmap,newScratchWidth,newScratchHeight);
											  
														    /*if (scratchWidth > scratchHeight) {
															  scrImageBitmap = getResizedBitmap(scrImageBitmap, scratchHeight, scratchHeight);										        
														    } else {
														    	scrImageBitmap = getResizedBitmap(scrImageBitmap, scratchWidth, scratchWidth);
														    }
														 */
														  
														   // Bitmap resizedBitmap2 = Bitmap.createScaledBitmap(scrImageBitmap, scratchWidth, scratchWidth, false);
														   // scrImageBitmap = resizedBitmap2
														    //scrImageBitmap =  getCircularBitmap(resizedBitmap2);
												 
											     }
											     scratchView[index] = new WScratchView(ScratchAndWin.this,null,srfc,scrImageBitmap);									
							   			     	 RelativeLayout.LayoutParams rlpScratchView = new RelativeLayout.LayoutParams(newScratchWidth, newScratchHeight);
							   			     	 rlpScratchView.setMargins(newXPos,newYPos, 0, 0);
							   			         scratchView[index].setLayoutParams(rlpScratchView);	
							   			         scratchView[index].setId(R.string.scratchID+index);
							   			         scratchView[index].setTag(index);
											     offerLayout.addView(scratchView[index]);
											     
											     if(index == json.length()-1){
													  offerImage.setVisibility(View.VISIBLE);						  
													  progressBar.setVisibility(View.INVISIBLE);
										          }
									     }
										 
								          Log.e("newScratchWidth",newScratchWidth+"newScratchHeight"+newScratchHeight);						        	   
										  
										  scratchView[index].setOnScratchCallback(new WScratchView.OnScratchCallback() {		
												@Override
												public void onScratch(float percentage) {											
														try{
															updatePercentage(percentage);
													}catch(Exception e){
														e.printStackTrace();
														String errorMsg = className+" | onScratch click  |   " +e.getMessage();
											       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
													}
													//txtvScratch.setText("");
												}		
												@Override
												public void onDetach(boolean fingerDetach) {
													try{
														if(mPercentage > 80 ){
															updatePercentage(100);
															int s = Integer.parseInt(scratchView[index].getTag().toString());
															scratchView[index].setScratchAll(true);
															Log.e("aList",""+aList);
															aList.add(json.getJSONObject(s).getString("offer_info"));
															Intent intent = new Intent(ScratchAndWin.this,ScratchOffer.class);
															intent.putStringArrayListExtra("aList", aList);
															intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
															intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
															intent.putExtra("clientId",clientId);
															startActivityForResult(intent, 1);													
															scratchView[index].setScratchable(false);													
															mPercentage = 0;
														}
													}catch(Exception  e){
														e.printStackTrace();
														String errorMsg = className+" | onDetach click  |   " +e.getMessage();
											       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
													}
												}
											});	
						            }
								            

											        	}catch(Exception e){
											        		e.printStackTrace();
											        	}
											        }
											  });	
							        	}catch(Exception e){
							        		e.printStackTrace();
							        	}
							        }
							  });						      		
						}
					}
				}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | displayGameOffer callback  |   " +e.getMessage();
				Common.sendCrashWithAQuery(ScratchAndWin.this, errorMsg);
			}
			
	 }
	
	public void displayGameOfferNew(){
		try{
			Bundle b = getIntent().getExtras();
			String jsons = b.getString("json");
			final JSONArray json = new JSONArray(jsons);
			if(json.length()>0){
				final WScratchView[] scratchView = new WScratchView[json.length()];
				for(int i=0;i<json.length();i++)
				{
					final int  index =i;
					final JSONObject json_obj = json.getJSONObject(i);
					offerId =  json_obj.getString("offer_id").replaceAll(" ", "%20");
					
					String value =  json_obj.getString("value");
					final String[] SizeArray = value.split(",");
					
					if(json.getJSONObject(i).optString("game_image")!=null){	
						
						String scratchImagesUrl = json_obj.getString("scratch_image").replaceAll(" ", "%20");
						curveImagesUrl = json_obj.getString("game_image").replaceAll(" ", "%20");
						
						 URL scrImageUrl = new URL(scratchImagesUrl); 
						 Bitmap scrImageBitmap = BitmapFactory.decodeStream(scrImageUrl.openConnection().getInputStream());
						 
						 URL newurl = new URL(curveImagesUrl); 
						 Bitmap backgroundBitmap = BitmapFactory.decodeStream(newurl.openConnection().getInputStream());
						 
						 imageWidth  = backgroundBitmap.getWidth();
				         imageHeight = backgroundBitmap.getHeight();	
				         newWidth = imageWidth;
				         newHeight = imageHeight;
				         
			             if(imageWidth > offerImageWidth){
			            	newWidth  = offerImageWidth;
			            	newHeight =  newWidth * imageHeight/imageWidth;
			             }
			             if(newHeight > offerImageHeight){
			            	newHeight = offerImageHeight;
			            	newWidth  = newHeight * imageWidth/imageHeight;					            	
			             }
				            
				         Bitmap resizedBitmap = Bitmap.createScaledBitmap(backgroundBitmap, newWidth, newHeight, false);
				         ImageView offerImage;
				         if(json.length() >1){
				        	  offerImage = (ImageView)findViewById(R.id.offerImage1);
				         }else{
				        	 offerImage = (ImageView)findViewById(R.id.offerImage);
				         }
				        
				            
				         offerImage.setPadding((int)(0.00667 * Common.sessionDeviceWidth),(int)(0.0041 * Common.sessionDeviceHeight),(int)(0.00667 * Common.sessionDeviceWidth),(int)(0.0041 * Common.sessionDeviceHeight));
				         offerImage.setImageBitmap(resizedBitmap);
				         offerImage.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
				            //offerImage.setLayoutParams(params);
				        	
				         Log.e("newHeight",newHeight+"newWidth"+newWidth);
				         imageXPos = Integer.parseInt(json_obj.getString("x"));
				         imageYPos = Integer.parseInt(json_obj.getString("y"));
				           
				         newXPos = newWidth * imageXPos /imageWidth;
				         newYPos = newHeight * imageYPos /imageHeight;
				         newYPos = newYPos +(int)(0.0041 * Common.sessionDeviceHeight);
				           // newXPos = newXPos +(int)(0.00667 * Common.sessionDeviceWidth);
				            Log.e("newXPos",newXPos+"newYPos"+newYPos);
				            if(scrImageBitmap != null){
				        	     int scratchWidth  = Integer.parseInt(SizeArray[0]);
				        	     int scratchHeight = Integer.parseInt(SizeArray[1]);
				        	     
				        	     int newScratchWidth = newWidth * scratchWidth /imageWidth;
				        	     int newScratchHeight = newHeight * scratchHeight /imageHeight;
				        	     SurfaceView srfc = new SurfaceView(ScratchAndWin.this);
				        	    
								 if(SizeArray.length > 0){									
									  Log.e("scratch_type",json_obj.getString("scratch_type"));
									  if(json_obj.getString("scratch_type").equals("C")){
											// scrImageBitmap =  getRoundedCroppedBitmap(scrImageBitmap,newScratchWidth);
										    scrImageBitmap =  getCroppedBitmap(scrImageBitmap,newScratchWidth,newScratchHeight);
										  
										    /*if (scratchWidth > scratchHeight) {
											  scrImageBitmap = getResizedBitmap(scrImageBitmap, scratchHeight, scratchHeight);										        
										    } else {
										    	scrImageBitmap = getResizedBitmap(scrImageBitmap, scratchWidth, scratchWidth);
										    }
										 */
										  
										   // Bitmap resizedBitmap2 = Bitmap.createScaledBitmap(scrImageBitmap, scratchWidth, scratchWidth, false);
										   // scrImageBitmap = resizedBitmap2
										    //scrImageBitmap =  getCircularBitmap(resizedBitmap2);
											 
										 }
									     scratchView[index] = new WScratchView(ScratchAndWin.this,null,srfc,scrImageBitmap);									
					   			     	 RelativeLayout.LayoutParams rlpScratchView = new RelativeLayout.LayoutParams(newScratchWidth, newScratchHeight);
					   			     	 rlpScratchView.setMargins(newXPos,newYPos, 0, 0);
					   			         scratchView[index].setLayoutParams(rlpScratchView);	
					   			          scratchView[index].setId(R.string.scratchID+i);
					   			         scratchView[index].setTag(i);
									     offerLayout.addView( scratchView[index]);
								     }
								 
						          Log.e("newScratchWidth",newScratchWidth+"newScratchHeight"+newScratchHeight);
				        	     // Drawable d = new BitmapDrawable(getResources(),scrImageBitmap);
								 // scratchView.setScratchDrawable(d);
								 // scratchView.setVisibility(View.VISIBLE);
								  offerImage.setVisibility(View.VISIBLE);						  
								  //progressBar.setVisibility(View.INVISIBLE);
								  if(index == json.length()-1){					  
									  progressBar.setVisibility(View.INVISIBLE);
						          }
								  
								  scratchView[index].setOnScratchCallback(new WScratchView.OnScratchCallback() {		
										@Override
										public void onScratch(float percentage) {											
												try{
													updatePercentage(percentage);
											}catch(Exception e){
												e.printStackTrace();
												String errorMsg = className+" | onScratch click  |   " +e.getMessage();
									       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
											}
											//txtvScratch.setText("");
										}		
										@Override
										public void onDetach(boolean fingerDetach) {
											try{
												Log.e("mPercentage",""+mPercentage);
												if(mPercentage > 80 ){
													Log.e("mPercentage if",""+mPercentage);
													updatePercentage(100);
													int s = Integer.parseInt(scratchView[index].getTag().toString());
													scratchView[index].setScratchAll(true);
													Log.e("aList",""+aList);
													aList.add(json.getJSONObject(s).getString("offer_info"));
													analytics(json.getJSONObject(s));
													getDetailListView();
													/*Intent intent = new Intent(ScratchAndWin.this,ScratchOffer.class);
													intent.putStringArrayListExtra("aList", aList);
													intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
													intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
													startActivity(intent);
													intent.putExtra("clientId",clientId);
													startActivityForResult(intent, 1);			*/										
													scratchView[index].setScratchable(false);													
													mPercentage = 0;
												}
											}catch(Exception  e){
												e.printStackTrace();
												String errorMsg = className+" | onDetach click  |   " +e.getMessage();
									       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
											}
										}
										
									});	
				            }
				            
					         /*listViewScratch.setOnItemClickListener(new OnItemClickListener() {
								@Override
								public void onItemClick(AdapterView<?> parent,
										View view, int position, long id) {
									try{
										ImageView check = (ImageView)view.findViewById(R.id.imgarrow);	
										LinearLayout.LayoutParams rlpForImgCheck = (LinearLayout.LayoutParams) check.getLayoutParams();
										rlpForImgCheck.width = (int)(0.0667 * Common.sessionDeviceWidth);
										rlpForImgCheck.height = (int) (0.041 * Common.sessionDeviceHeight);
										check.setLayoutParams(rlpForImgCheck);
										if(check.getVisibility()==View.INVISIBLE){
						    				check.setVisibility(View.VISIBLE);    
						    				arrListForCheckedProdImages.add(arrOfferId.get(position));			    				  				
										} else {
						    				check.setVisibility(View.INVISIBLE);   
						    				arrListForCheckedProdImages.remove(arrOfferId.get(position));			
										}
									}catch(Exception e){
										e.printStackTrace();
									}
								   }
							    });			*/			
					}
					

				     
				}
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | displayGameOffer callback  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ScratchAndWin.this, errorMsg);
		}
		
 }
	ArrayList<String> arrofferId;
	String arroffIds="";
	boolean scratchflag = true;
	public void getDetailListView() {
		    try{		
		    	
				if(jsonObject.length()>1){
					btnaddoffer.setVisibility(View.VISIBLE);
					btnGameRules.setVisibility(View.VISIBLE);
					txtvMess.setVisibility(View.VISIBLE);
					arrofferId = new ArrayList<String>();		
						if(aList.size() >0 ){
							String[] scratchFinalArray = new String[aList.size()];
							for(int i=0;i<aList.size(); i++){
								 String offerInfo = aList.get(i);
								ArrayList<String> scratchResArrays = new ArrayList<String>(); 	
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
				                		 //txtvDisclaimer.setText(jsonObj.getString("offer_description"));
				                		
									 }
									}
									scratchFinalArray[i] = scratchResArrays.toString();							
								}
						}
						listViewScratch.setAdapter(new ScracthListAdapter(this, scratchFinalArray));
						listViewScratch.setOnItemClickListener(new OnItemClickListener() {
							@Override
							public void onItemClick(AdapterView<?> parent,
									View view, int position, long id) {
								try{
									ImageView check = (ImageView)view.findViewById(R.id.imgcheck);										
									if(check.getVisibility()==View.INVISIBLE){
					    				check.setVisibility(View.VISIBLE);    
					    				arrListForCheckedProdImages.add(arrOfferId.get(position));			    				  				
									} else {
					    				check.setVisibility(View.INVISIBLE);   
					    				arrListForCheckedProdImages.remove(arrOfferId.get(position));			
									}
								}catch(Exception e){
									e.printStackTrace();
								}
							   }
						    });	
					}
				}else{
					if(scratchflag){						
						Intent intent = new Intent(ScratchAndWin.this,ScratchOffer.class);
						intent.putStringArrayListExtra("aList", aList);
						intent.putExtra("game_rules", getIntent().getStringExtra("game_rules"));
						intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
						startActivity(intent);
						intent.putExtra("clientId",clientId);
						startActivityForResult(intent, 1);
						scratchflag = false;
					}
				}
				
				
				
			}catch(Exception e){
				e.printStackTrace();
		    }
	}
	public void analytics(JSONObject jsonObject2){
		try{
			String offer_id = null,offer_name = null,client_games_item_id= null;
			JSONArray offerInfoArray = new JSONArray(jsonObject2.getString("offer_info"));					
					if(offerInfoArray.length() > 0){
						for(int j=0;j<offerInfoArray.length();j++){
							 JSONObject jsonObj = offerInfoArray.getJSONObject(j);	
							 if(jsonObj != null){
								 offer_id = jsonObj.getString("offer_id");
								 offer_name = jsonObj.getString("offer_name");	                		
						     }
						}						
					}			
					client_games_item_id = jsonObject2.getString("client_games_item_id");
					String screenName = "/games/used/"+jsonObject.getJSONObject(0).getString("game_id")+"/"+client_games_item_id+"/"+clientId+"/"+clientName+"/"+offer_id+"/"+offer_name;			
					Common.sendJsonWithAQuery(ScratchAndWin.this, ""+Common.sessionIdForUserLoggedIn, screenName, "", "");
		  }catch(Exception e){
			e.printStackTrace();
		  }
		
	}
	public void saveMutlipleOffer(){
		try{						
						Map<String, Object> params = new HashMap<String, Object>();
						params.put("clientId", clientId);
					    //Log.i("userId", ""+userId);
					    params.put("userId", Common.sessionIdForUserLoggedIn);
					    params.put("offerIdsWithSymbol", arroffIds);
					    params.put("checkFlag", "Yes");
						AQuery aq = new AQuery(ScratchAndWin.this);
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
													try{	  
														if(entry.text("offer_id").toString() !=null){
													    			UserMyOffers offermyExist = myoffers.getUserMyOffers(Integer.parseInt(entry.text("offer_id").toString()));
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
								    			aList.clear();
								    			Intent intent = new Intent(getApplicationContext(), MyOffers.class);			                             
	        								    intent.putExtra("offerName", "My Offers" );	        								    
	        					                startActivity(intent);
	        					                overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
	        					      	      	finish();
	        					      	       
										   	}else{
										   		file.setOffers(newoffers);
										   		file.setMyOffers(myoffers);
										   		aList.clear();
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
	
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			Log.e("onActivityResult",""+requestCode);
			imageFlag = false;
		}catch (Exception e){	
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);			
		}		
	}
	
	protected void updatePercentage(float percentage) {
		try{
			mPercentage = percentage;
			String percentage2decimal = String.format("%.2f", percentage) + " %";
			//percentageView.setText(percentage2decimal);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | updatePercentage click  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ScratchAndWin.this,errorMsg);
		}
	}
	 String curveImagesUrl ="null";
	 boolean displayFlag = false;

	public Bitmap processBitmap(Bitmap bitmap) {
	    int pixels = 0;
	    int mRound = 0;
		if (mRound == 0)
	        pixels = 120;
	    else
	        pixels = mRound;
	    Bitmap output = Bitmap.createBitmap(bitmap.getWidth(),
	            bitmap.getHeight(), Config.ARGB_8888);
	    Canvas canvas = new Canvas(output);

	    final int color = 0xff424242;
	    final Paint paint = new Paint();
	    final Rect rect = new Rect(0, 0, bitmap.getWidth(), bitmap.getHeight());
	    final RectF rectF = new RectF(rect);
	    final float roundPx = pixels;

	    paint.setAntiAlias(true);
	    canvas.drawARGB(0, 0, 0, 0);
	    paint.setColor(color);
	    canvas.drawRoundRect(rectF, roundPx, roundPx, paint);

	    paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
	    canvas.drawBitmap(bitmap, rect, rect, paint);

	    return output;
	}
	
	public Bitmap getCroppedBitmap(Bitmap scaleBitmapImage,int targetWidth,int targetHeight) {	
		  Bitmap targetBitmap = Bitmap.createBitmap(targetWidth, 
		                            targetHeight,Bitmap.Config.ARGB_8888);

		                Canvas canvas = new Canvas(targetBitmap);
		  Path path = new Path();
		  path.addCircle(((float) targetWidth - 1) / 2,
		  ((float) targetHeight - 1) / 2,
		  (Math.min(((float) targetWidth), 
		                ((float) targetHeight)) / 2),
		          Path.Direction.CCW);

		                canvas.clipPath(path);
		  Bitmap sourceBitmap = scaleBitmapImage;
		  canvas.drawBitmap(sourceBitmap, 
		                                new Rect(0, 0, sourceBitmap.getWidth(),
		    sourceBitmap.getHeight()), 
		                                new Rect(0, 0, targetWidth,
		    targetHeight), null);
		  return targetBitmap;
	    
	}   
	public static Bitmap getRoundedRectBitmap(Bitmap bitmap, int pixels) {
		Bitmap result = null;
		try {
		result = Bitmap.createBitmap(bitmap.getWidth(), bitmap.getHeight(),
		Bitmap.Config.ARGB_8888);
		Canvas canvas = new Canvas(result);

		int color = 0xff424242;
		Paint paint = new Paint();
		Rect rect = new Rect(0, 0, bitmap.getWidth(), bitmap.getHeight());
		RectF rectF = new RectF(rect);
		float roundPx = pixels;

		paint.setAntiAlias(true);
		canvas.drawARGB(0, 0, 0, 0);
		paint.setColor(color);
		canvas.drawRoundRect(rectF, roundPx, roundPx, paint);

		paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
		canvas.drawBitmap(bitmap, rect, rect, paint);
		} catch (NullPointerException e) {
		// return bitmap;
		} catch (OutOfMemoryError o){}
		return result;
		}
	
	public static Bitmap getRoundedCroppedBitmap(Bitmap bitmap, int radius) {
		   Bitmap finalBitmap;
		   radius = Math.min(bitmap.getHeight() / 2, bitmap.getWidth() / 2);
		   /* if(bitmap.getWidth() != radius || bitmap.getHeight() != radius)
		        finalBitmap = Bitmap.createScaledBitmap(bitmap, radius, radius, false);
		    else
		        finalBitmap = bitmap;*/
		   finalBitmap = Bitmap.createScaledBitmap(bitmap, radius, radius, false); 
		    Bitmap output = Bitmap.createBitmap(finalBitmap.getWidth(),
		            finalBitmap.getHeight(), Config.ARGB_8888);
		    Canvas canvas = new Canvas(output);

		    final Paint paint = new Paint();
		    final Rect rect = new Rect(0, 0, finalBitmap.getWidth(), finalBitmap.getHeight());

		    paint.setAntiAlias(true);
		    paint.setFilterBitmap(true);
		    paint.setDither(true);
		    canvas.drawARGB(0, 0, 0, 0);
		    paint.setColor(Color.parseColor("#BAB399"));
		    /*canvas.drawCircle(finalBitmap.getWidth() / 2+0.7f, finalBitmap.getHeight() / 2+0.7f,
		            finalBitmap.getWidth() / 2+0.1f, paint);*/
		    canvas.drawCircle(finalBitmap.getWidth() / 2+0.7f, finalBitmap.getHeight() / 2+0.7f,
		    		finalBitmap.getWidth() / 2+0.1f, paint);
		    paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
		    canvas.drawBitmap(finalBitmap, rect, rect, paint);


		            return output;


 }
	
	
	public static Bitmap getCircularBitmap(Bitmap bitmap) {
	    Bitmap output;

	    if (bitmap.getWidth() > bitmap.getHeight()) {
	        output = Bitmap.createBitmap(bitmap.getHeight(), bitmap.getHeight(), Config.ARGB_8888);
	    } else {
	        output = Bitmap.createBitmap(bitmap.getWidth(), bitmap.getWidth(), Config.ARGB_8888);
	    }

	    Canvas canvas = new Canvas(output);

	    final int color = 0xff424242;
	    final Paint paint = new Paint();
	    final Rect rect = new Rect(0, 0, bitmap.getWidth(), bitmap.getHeight());

	    float r = 0;

	    if (bitmap.getWidth() > bitmap.getHeight()) {
	        r = bitmap.getHeight() / 2;
	    } else {
	        r = bitmap.getWidth() / 2;
	    }

	    paint.setAntiAlias(true);
	    canvas.drawARGB(0, 0, 0, 0);
	    paint.setColor(color);
	    canvas.drawCircle(r, r, r, paint);
	    paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
	    canvas.drawBitmap(bitmap, rect, rect, paint);
	    return output;
	}

	public Bitmap getResizedBitmap(Bitmap bm, int newWidth, int newHeight) {
	    int width = bm.getWidth();
	    int height = bm.getHeight();
	    float scaleWidth = ((float) newWidth) / width;
	    float scaleHeight = ((float) newHeight) / height;
	    // CREATE A MATRIX FOR THE MANIPULATION
	    Matrix matrix = new Matrix();
	    // RESIZE THE BIT MAP
	    matrix.postScale(scaleWidth, scaleHeight);

	    // "RECREATE" THE NEW BITMAP
	    Bitmap resizedBitmap = Bitmap.createBitmap(bm, 0, 0, width, height, matrix, false);
	    return resizedBitmap;
	}
	/*public void onClickHandler(View view) {
		switch (view.getId()) {
		case R.id.reset_button:
			scratchView.resetView();
			scratchView.setScratchAll(false); // todo: should include to resetView?
			updatePercentage(0f);
			break;
		}
	}*/
}
