package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.media.AudioManager;
import android.media.MediaPlayer;
import android.media.MediaPlayer.OnCompletionListener;
import android.media.MediaPlayer.OnPreparedListener;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.SurfaceHolder;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.MediaController;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.VideoView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class FinancialActivity extends Activity {

	 VideoView mVideoView;
	 FancyCoverFlow fancyCoverFlow;
	 AQuery aq;
	 ImageView imgvBtnStar,imgvForPd,imgBtnCloset;	
	 MediaPlayer player;
	 SurfaceHolder surfaceHolder;
	 VideoView playerSurfaceView;
	 SessionManager session;
	 String discriminator,productId,productPrice,clientId ,buttonName,buttonUrl,offerDiscType,offerId,offerValidTo,
	 getProductImageUrl, offerTitle = "",locationBased="null",pageRedirectFlag="null",relatedType = "",playerType ="video",videoUrl="";
	 Intent intExtra; 			
	 boolean financialBigImageType = false;		
	 String className = this.getClass().getSimpleName();
     FileTransaction file;
	 ArrayList<String> financialResArrays;
	 ProductModel productModel;
	 ProductModel  getProdDetail;
	 Offers newoffers;
  	 Offers offers;
  	 String[] financialFinalStrArray = {};  	
  	 Boolean changeFlag = false;
  	 TextView txtvForValue;
  	 ArrayList<String> arrBackProductList = new ArrayList<String>();
  	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_financial);
		try{
			 aq = new AQuery(this);
			 file = new FileTransaction();
			 
			 new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					FinancialActivity.this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "");
			 new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			
			 intExtra = getIntent();
			 discriminator = intExtra.getStringExtra("discriminator");
			 productId = intExtra.getStringExtra("productId");
			 clientId = intExtra.getStringExtra("clientId");
			 buttonName = intExtra.getStringExtra("buttonName");
			 buttonUrl = intExtra.getStringExtra("buttonUrl");
			 productPrice= intExtra.getStringExtra("productPrice");
			 offerId = intExtra.getStringExtra("offerId");
			 offerTitle = intExtra.getStringExtra("offerTitle");
			 getProductImageUrl = intExtra.getStringExtra("product_image_url");	
			 offerValidTo  = intExtra.getStringExtra("offerValidTo");	
			 pageRedirectFlag = getIntent().getStringExtra("pageRedirectFlag");	
			// Log.e("pageRedirectFlag",""+pageRedirectFlag);
			 session = new SessionManager(this);
			
			
			RelativeLayout llForBigImgBg = (RelativeLayout) findViewById(R.id.llForBigImgBg);
			RelativeLayout.LayoutParams rlForLlBigImgBg = (RelativeLayout.LayoutParams) llForBigImgBg.getLayoutParams();
			rlForLlBigImgBg.width = (int) (0.864 * Common.sessionDeviceWidth);
			rlForLlBigImgBg.height = (int) (0.454 * Common.sessionDeviceHeight);
			rlForLlBigImgBg.topMargin = (int) (0.0185 * Common.sessionDeviceHeight);
			rlForLlBigImgBg.leftMargin = (int) (0.0684 * Common.sessionDeviceWidth);
			llForBigImgBg.setLayoutParams(rlForLlBigImgBg);
			
			//checking whether closet contains anyitem 
		
			 txtvForValue = (TextView) findViewById(R.id.txtvForValue);
			txtvForValue.setTextSize((float) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			RelativeLayout.LayoutParams rlForTxtVal = (RelativeLayout.LayoutParams) txtvForValue.getLayoutParams();
			rlForTxtVal.width = (int) (0.3584 * Common.sessionDeviceWidth);
			rlForTxtVal.height = (int) (0.0707 * Common.sessionDeviceHeight);
			txtvForValue.setLayoutParams(rlForTxtVal);
			
			Button imgvBtnFinancial = (Button) findViewById(R.id.imgvBtnFinancial);
			RelativeLayout.LayoutParams rlForBtnFinancial = (RelativeLayout.LayoutParams) imgvBtnFinancial.getLayoutParams();
			rlForBtnFinancial.width = (int) (0.312 * Common.sessionDeviceWidth);
			rlForBtnFinancial.height = (int) (0.0707 * Common.sessionDeviceHeight);
			rlForBtnFinancial.rightMargin = (int) (0.0167 * Common.sessionDeviceWidth);
			rlForBtnFinancial.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgvBtnFinancial.setLayoutParams(rlForBtnFinancial);
			imgvBtnFinancial.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			imgvBtnFinancial.setText(buttonName);
			if(buttonName.equals("null")){
				imgvBtnFinancial.setVisibility(View.INVISIBLE);
			}
			//locationBased ="2";
			
			imgvBtnFinancial.setOnClickListener(new OnClickListener() {					
				@Override
				public void onClick(View v) {
					try{						
						if(!locationBased.equals("null")){
						   if(locationBased.equals("0")){
							String[] separated = buttonUrl.split(":");
							//Log.e("separated", ""+separated[0]+" "+separated[1]);
							if(separated[0]!=null && separated[0].equals("tel")){
								Intent callIntent = new Intent(Intent.ACTION_CALL);
								callIntent.setData(Uri.parse("tel://"+separated[1]));
			                   	startActivity(callIntent);
							} else if(separated[0]!=null && separated[0].equals("telprompt")){
								Intent callIntent = new Intent(Intent.ACTION_CALL);
			                    callIntent.setData(Uri.parse("tel://"+separated[1]));
		                    	startActivity(callIntent);
							} else {
								if(Common.isNetworkAvailable(FinancialActivity.this)){
								Intent intent = new Intent(getApplicationContext(), OfferWebUrlActivity.class);	
								//Log.e("offer web", ""+buttonUrl+" "+offerTitle);
						    	intent.putExtra("offerPurchseUrl", buttonUrl);
						    	intent.putExtra("offerTitle", offerTitle);
						    	startActivity(intent);
								}else{
									new Common().instructionBox(FinancialActivity.this,R.string.title_case7,R.string.instruction_case7);
								}
							}
						}else if(locationBased.equals("1")){
							String[] separated = buttonUrl.split(":");
							//Log.e("separated", ""+separated[0]+" "+separated[1]);
							if(separated[0]!=null && separated[0].equals("tel")){
								Intent callIntent = new Intent(Intent.ACTION_CALL);
								callIntent.setData(Uri.parse("tel://"+separated[1]));
			                   	startActivity(callIntent);
							} else if(separated[0]!=null && separated[0].equals("telprompt")){
								Intent callIntent = new Intent(Intent.ACTION_CALL);
			                    callIntent.setData(Uri.parse("tel://"+separated[1]));
		                    	startActivity(callIntent);
							} else {
								if(Common.isNetworkAvailable(FinancialActivity.this)){
								Intent intent = new Intent(getApplicationContext(), OfferWebUrlActivity.class);	
								//Log.e("offer web", ""+buttonUrl+" "+offerTitle);
						    	intent.putExtra("offerPurchseUrl", buttonUrl);
						    	intent.putExtra("offerTitle", offerTitle);
						    	startActivity(intent);
								}else{
									new Common().instructionBox(FinancialActivity.this,R.string.title_case7,R.string.instruction_case7);
								}
							}
							
						}else if(locationBased.equals("2")){
							if(Common.isNetworkAvailable(FinancialActivity.this)){
								Intent intent = new Intent(getApplicationContext(), NearbystoreActivity.class);	
								//Log.e("offer web", ""+buttonUrl+" "+offerTitle);
						    	intent.putExtra("offerPurchseUrl", buttonUrl);
						    	intent.putExtra("offerTitle", offerTitle);
						    	startActivity(intent);
								}else{
									new Common().instructionBox(FinancialActivity.this,R.string.title_case7,R.string.instruction_case7);
								}
						}
						
							}else{
								String[] separated = buttonUrl.split(":");
								//Log.e("separated", ""+separated[0]+" "+separated[1]);
								if(separated[0]!=null && separated[0].equals("tel")){
									Intent callIntent = new Intent(Intent.ACTION_CALL);
									callIntent.setData(Uri.parse("tel://"+separated[1]));
				                   	startActivity(callIntent);
								} else if(separated[0]!=null && separated[0].equals("telprompt")){
									Intent callIntent = new Intent(Intent.ACTION_CALL);
				                    callIntent.setData(Uri.parse("tel://"+separated[1]));
			                    	startActivity(callIntent);
								} else {
									if(Common.isNetworkAvailable(FinancialActivity.this)){
									Intent intent = new Intent(getApplicationContext(), OfferWebUrlActivity.class);	
									//Log.e("offer web", ""+buttonUrl+" "+offerTitle);
							    	intent.putExtra("offerPurchseUrl", buttonUrl);
							    	intent.putExtra("offerTitle", offerTitle);
							    	startActivity(intent);
									}else{
										new Common().instructionBox(FinancialActivity.this,R.string.title_case7,R.string.instruction_case7);
									}
							}
							}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnFinancial Click |  " +e.getMessage();
					    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
						
					}
				}
			});
			
			
			RelativeLayout rlForCarousel = (RelativeLayout) findViewById(R.id.rlForCarousel);
			RelativeLayout.LayoutParams rlForRlCarosel = (RelativeLayout.LayoutParams) rlForCarousel.getLayoutParams();
			rlForRlCarosel.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlForRlCarosel.height = (int) (0.287 * Common.sessionDeviceHeight);
			rlForRlCarosel.bottomMargin = (int) (0.021 * Common.sessionDeviceHeight);
			rlForCarousel.setLayoutParams(rlForRlCarosel);
			
			fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlowForPds);
	        RelativeLayout.LayoutParams rlpForFancyCover = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();
	        rlpForFancyCover.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
	        rlpForFancyCover.height = (int) (0.287 * Common.sessionDeviceHeight);
	        fancyCoverFlow.setLayoutParams(rlpForFancyCover);
	        
			RelativeLayout rlForBtns = (RelativeLayout) findViewById(R.id.rlForBtns);
			RelativeLayout.LayoutParams llForRlBtns = (RelativeLayout.LayoutParams) rlForBtns.getLayoutParams();
			llForRlBtns.bottomMargin = (int) (0.0052 * Common.sessionDeviceHeight);
	    	
			
			playerSurfaceView = (VideoView)findViewById(R.id.playersurface);		 
		    imgvForPd = (ImageView) findViewById(R.id.imgvForPd);
		    
				
		 	imgvBtnStar = (ImageView) findViewById(R.id.imgvBtnStar);
			RelativeLayout.LayoutParams rlForBtnStar = (RelativeLayout.LayoutParams) imgvBtnStar.getLayoutParams();
			rlForBtnStar.width = (int) (0.167 * Common.sessionDeviceWidth);
			rlForBtnStar.height = (int) (0.0707 * Common.sessionDeviceHeight);
			rlForBtnStar.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgvBtnStar.setLayoutParams(rlForBtnStar);
			
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {		
				@Override
				public void onClick(View v) {
					try{
						if(pageRedirectFlag == null || pageRedirectFlag.equals("null")){
							Log.e("arrBackProductList",""+arrBackProductList.size()+"values"+arrBackProductList);
							
							if(arrBackProductList.size() >0){
								/*for(int j=0;j<arrBackProductList.size();j++){
									String[] strParams = arrBackProductList.get(j).split("`");
									if(!strParams[0].equals("null") || !strParams[0].equals("")){
										arrBackProductList.remove(j);
									}
								}*/
								Log.e("arrBackProductList",""+arrBackProductList.size()+"values"+arrBackProductList);
								
								int index = arrBackProductList.size()-1;
								Log.e("index",""+index);
								String[] strParams = arrBackProductList.get(index).split("`");
								if(!strParams[0].equals("null") || !strParams[0].equals("")){
									if(strParams[1].equals("offer")){
										offerId   = strParams[0];
										productId = "null";									
									}else{
										productId   = strParams[0];
										offerId = "null";	
									}
									//discriminator = strParams[2];
									String videoPath = strParams[3];
									Log.e("strParams[0]",""+strParams[0]);
									if(arrBackProductList.size() ==1){
										loadMain();
									}else{
										loadMainRelated(videoPath,strParams[2]);
									}
									arrBackProductList.remove(index);
								}
								else{
									arrBackProductList.remove(index);
									if(arrBackProductList.size() == 0){
										finish();
										overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
									}
								}
								
							}else{
								finish();
								overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							}
							
						}else{
							if(pageRedirectFlag.equals("RecentlyScanned")){
								Intent intent = new Intent(FinancialActivity.this, RecentlyScanned.class);									
								startActivityForResult(intent, 1);
								finish();
								overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							}
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBackButton click   |   " +e.getMessage();
					    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
					}
				}

				
			});			
			ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle);
			imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
					Intent intent = new Intent(getApplicationContext(), MenuOptions.class);
					int requestCode = 0;
					startActivityForResult(intent, requestCode);
					overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgFooterMiddle click   |   " +e.getMessage();
					    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
					}
				}
			});
			
	    	ImageView imgBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
	    	imgBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{						
						ImageView bigImage = (ImageView) findViewById(R.id.imgvForPd);
						if(bigImage!=null){
							BitmapDrawable test = (BitmapDrawable) bigImage.getDrawable();
							Bitmap bitmap = test.getBitmap();
							ByteArrayOutputStream baos = new ByteArrayOutputStream();
							bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
							byte[] b = baos.toByteArray();
							Intent intent = new Intent(FinancialActivity.this, ShareActivity.class);
							if(relatedType.equals("product")){
							intent.putExtra("tapOnImage", false);				
							intent.putExtra("image", b);		
							intent.putExtra("productId", productId);
							intent.putExtra("clientId", clientId);	
							}else if(relatedType.equals("offer")){
								intent.putExtra("pageFlag", "myOffers");		
								intent.putExtra("image", b);		
								intent.putExtra("offerUrl",  buttonUrl);
								intent.putExtra("offerId",offerId);
							}
							startActivityForResult(intent, 1);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnShare click   |   " +e.getMessage();
					    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
					}
				}
			});
	    	
	    	ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);        
	    	imgBtnCart.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						finish();
					} catch (Exception e) {						
						String errorMsg = className+" | imgBtnCart click   |   " +e.getMessage();
					    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
					}
				}
			});
			
			imgBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			player = new MediaPlayer();
			Log.i("discriminator", ""+discriminator);
			if(productId != null){
				if(productId.equals("0")){
					productId ="null";
				}			
			}
	
			loadMain();
			 	
				imgvBtnStar.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View view) {
							try{
								if(Common.isNetworkAvailable(FinancialActivity.this)){	
								imgvBtnStar.setImageResource(R.drawable.btn_saved_heart);
								if(relatedType.equals("offer")){
									if(session.isLoggedIn()){								
										new OfferViewActivity().insertOfferToMyOffersDbUsingXml(Constants.MyOffers_Url+"insert_offers/"+Common.sessionIdForUserLoggedIn+"/"+offerId+"/", FinancialActivity.this,"Financial");
										
									}else{
										ArrayList<String> arrayListCloset = new ArrayList<String>();
										arrayListCloset.add(offerId);	
										arrayListCloset.add("Financial");
										new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
									}
								}else{
									if(session.isLoggedIn())
							        {			        
								        String insertClosetUrl = Constants.Closet_Url+"insert/"+Common.sessionIdForUserLoggedIn+"/"+productId+"/";
								     	new Closet().insertUpdateDeleteProductsToClosetDbUsingXml(insertClosetUrl, "insert");
										
									}else{
										ArrayList<String> arrayListCloset = new ArrayList<String>();
										arrayListCloset.add(productId);
										new Common().getLoginDialog(FinancialActivity.this, Closet.class, "ClosetInsert", arrayListCloset);
									}
								
								}
								
								}else{
									new Common().instructionBox(FinancialActivity.this,R.string.title_case7,R.string.instruction_case7);
								}
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | imgvBtnStar |  " +e.getMessage();
								  Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
							}
					}
				});
				
				if(relatedType.equals("product")){
					
					imgBtnCloset.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {
							try{						
								new Common().getLoginDialog(FinancialActivity.this, Closet.class, "Closet", new ArrayList<String>());
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | imgBtnCloset click   |   " +e.getMessage();
							    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
							}
						}
					});

				}else if(relatedType.equals("offer")){
					new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
					imgBtnCloset.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {
							try{
							ArrayList<String> arrayListCloset = new ArrayList<String>();
							arrayListCloset.add(offerId);			
							arrayListCloset.add("Financial");
							new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | imgBtnCloset click   |   " +e.getMessage();
							    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
							}
						}
					});
				}
			 rlForBtns.setLayoutParams(llForRlBtns);
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate   |   " +e.getMessage();
		    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		}
	}
	
	private void loadMainRelated(String videoPath, String playerType) {
		try{
						    		                		
			if(!videoPath.equals("image")){
		           if(playerType.equals("VIDEO")){
						playFinVideo(videoPath);	
					if(offerId != null){
						if(!offerId.equals("null") && !offerId.equals("")) {
							financialBigImageType = false;
							String pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+offerId+"/financial/";
							relatedType = "offer";
							getRelatedResultsFromServerWithXml(pdUrl);
						
						 }								
					}
		           if(productId != null){
						 if(!productId.equals("null") && !productId.equals("")) {
							financialBigImageType = true;
							String pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+productId+"/financial/";
							Log.i("pdUrl",pdUrl);
							relatedType = "product";
							getRelatedResultsFromServerWithXml(pdUrl);						
						 }	
		           	}
		           }
		           
			}else {	
						if(offerId != "null"){													
						checkOfferExistinChangeLog(offerId);
						new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
						imgBtnCloset.setOnClickListener(new OnClickListener() {
							@Override
							public void onClick(View v) {
								try{
									ArrayList<String> arrayListCloset = new ArrayList<String>();
									arrayListCloset.add(offerId);
									arrayListCloset.add("Financial");	
									new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
								}catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | fancyCoverFlow |BUTTON | imgBtnCloset click |  " +e.getMessage();
									Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
									}
									}
							});
					}
						if(productId != "null"){					
							checkProductExistinChangeLog(productId); 								
							new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_heart_icon, R.id.imgvBtnCloset);
							imgBtnCloset.setOnClickListener(new OnClickListener() {
								@Override
							public void onClick(View v) {
							try{
								new Common().getLoginDialog(FinancialActivity.this, Closet.class, "Closet", new ArrayList<String>());
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | fancyCoverFlow |BUTTON | imgBtnCloset click|  " +e.getMessage();
								Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
								}
							}
						});
					}		    							
					imgvBtnStar.setImageResource(R.drawable.btn_unsaved_heart);	
						if(!videoPath.equals("image")){
							if(playerType.equals("AUDIO")){
								player = new MediaPlayer();
								checkProductExistinChangeLog(productId); 
								try {
									player.setAudioStreamType(AudioManager.STREAM_MUSIC);
									player.setDataSource(videoPath);
									player.prepare();
									player.start();		    		                					
								} catch (Exception e) {		    		                				    
									e.printStackTrace();
								}
							}
						}
			}
		}catch(Exception e){
			e.printStackTrace();
		}
		
	}
	public void loadMain(){
		try{
		 if(discriminator.equalsIgnoreCase("VIDEO")){
			 	try{
			 		if(Common.isNetworkAvailable(FinancialActivity.this)){	
			 			
			 			  videoUrl = intExtra.getStringExtra("videoUrl");				 			  
						  playFinVideo(videoUrl);
						  if(offerId.equals("null") && productId.equals("null")){					  
								String screenName = "/video/"+clientId;
								String productIds = "";
								String offerIds = "";
								Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);	
							    Log.i("pdUrl screenName null", ""+screenName);				  
						  }
				 
						  if(!offerId.equals("null")){
							  
							  financialBigImageType = false;								
							  relatedType = "offer";	
							  
							  checkOfferExistinChangeLog(offerId);
							  
							  String screenName = "/video/offers/"+clientId+"/"+offerId;
							  String productIds = "";
			    		      String offerIds = offerId;
							  Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						  
						  }
						  if(!productId.equals("null")){
							  
							  financialBigImageType = true;
							  relatedType = "product";
							  
							  checkProductExistinChangeLog(productId);
							
							  String screenName = "/video/product/"+clientId+"/"+productId;
							  String productIds = productId;
							  String offerIds = "";
							  Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						  }
			 		}else{
						if(!offerId.equals("null")){								  
						     getMainOfferFromFile();
						  }
						  if(!productId.equals("null")){								 
							  getMainProductFromFile();
						  }
						  findViewById(R.id.rlForVideos).setVisibility(View.INVISIBLE);
					      findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
					      imgvForPd.setVisibility(View.VISIBLE);
					     // Log.e("getProductImageUrl",getProductImageUrl);
					      getRelatedResultsFromFile();
						 
					 	
			 		}
			 	}catch(Exception e){
			 		e.printStackTrace();
			 		String errorMsg = className+" | discriminator | VIDEO |  " +e.getMessage();
				    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
			 	}
			  
		 } else if(discriminator.equalsIgnoreCase("AUDIO")){
			 	try{
			 		 if(Common.isNetworkAvailable(FinancialActivity.this)){
			 			 
						  videoUrl = intExtra.getStringExtra("videoUrl");			     
						  findViewById(R.id.rlForVideos).setVisibility(View.INVISIBLE);
					      playerSurfaceView.setVisibility(View.INVISIBLE);
					      findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
					      imgvForPd.setVisibility(View.VISIBLE);					      
					  
						 // new Common().DownloadImageFromUrl(FinancialActivity.this, getProductImageUrl, R.id.imgvForPd);
						      
						  player.setAudioStreamType(AudioManager.STREAM_MUSIC);
		  				  player.setDataSource(videoUrl);
		  				  player.prepare();
		  				  player.start();		  					
		  				
						  if(offerId.equals("null") && productId.equals("null")){					  
								String screenName = "/video/"+clientId;
								String productIds = "";
								String offerIds = "";
								Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);	
							    Log.i("pdUrl screenName null", ""+screenName);				  
						  }
				 
						  if(!offerId.equals("null")){
							  	financialBigImageType = false;
							  	relatedType = "offer";								  
							    checkOfferExistinChangeLog(offerId);
							  
								String screenName = "/video/offers/"+clientId+"/"+offerId;
								String productIds = "";
			    		        String offerIds = offerId;
								Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						  
						  }
						  if(!productId.equals("null")){
							  financialBigImageType = true;
							  relatedType = "product";		
							  
							  checkProductExistinChangeLog(productId);
							   
							  String screenName = "/video/product/"+clientId+"/"+productId;
							  String productIds = productId;
							  String offerIds = "";
							  Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						  }
			 		 }else{						
							
							if(!offerId.equals("null")){									
								getMainOfferFromFile();								
								 					    		
							  }
							  if(!productId.equals("null")){
								  getMainProductFromFile();
							  }
							  findViewById(R.id.rlForVideos).setVisibility(View.INVISIBLE);
						      findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
						      imgvForPd.setVisibility(View.VISIBLE);
						      //Log.e("getProductImageUrl",getProductImageUrl);
						 	  getRelatedResultsFromFile();
								
			 		 }
			 	}catch(Exception e){
			 		e.printStackTrace();
			 		String errorMsg = className+" | discriminator | Audio |  " +e.getMessage();
				    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
			 	}
			  
		 }else{
				  try{  						  
				      findViewById(R.id.rlForVideos).setVisibility(View.INVISIBLE);
				      playerSurfaceView.setVisibility(View.INVISIBLE);
				      findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
				      imgvForPd.setVisibility(View.VISIBLE);
			      
				      RelativeLayout  rlForImgs = (RelativeLayout)findViewById(R.id.rlForImgs);
				      RelativeLayout.LayoutParams rlForImgsLay= (RelativeLayout.LayoutParams) rlForImgs.getLayoutParams();					     
				      rlForImgsLay.bottomMargin = (int) (0.0102 * Common.sessionDeviceHeight);
				      rlForImgsLay.leftMargin   = (int) (0.00834 * Common.sessionDeviceWidth);
				      rlForImgsLay.rightMargin  = (int) (0.00834 * Common.sessionDeviceWidth);
				      rlForImgs.setLayoutParams(rlForImgsLay);
					  
				      RelativeLayout.LayoutParams rlForImgvForPd = (RelativeLayout.LayoutParams) imgvForPd.getLayoutParams();
					  rlForImgvForPd.width = (int) (0.85 * Common.sessionDeviceWidth);
					  rlForImgvForPd.height = (int) (0.365 * Common.sessionDeviceHeight);
					  rlForImgvForPd.bottomMargin = (int) (0.00204 * Common.sessionDeviceHeight);
					  imgvForPd.setLayoutParams(rlForImgvForPd);
					  
					//  new Common().DownloadImageFromUrl(FinancialActivity.this, getProductImageUrl, R.id.imgvForPd);	
					  
					  if(discriminator.equalsIgnoreCase("BUTTON"))
					  {
						  
						  if(Common.isNetworkAvailable(FinancialActivity.this)){							  
							  financialBigImageType = true;
							  relatedType = "product";
							  Log.e("checkProductExistinChangeLog if",""+productId);
							  checkProductExistinChangeLog(productId);	
							
							  String screenName = "/product/"+clientId+"/"+productId;
							  String productIds = productId;
							  String offerIds = "";
							  Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds); 
							  
						  }else{				
							  Log.e("getMainProductFromFile else",""+productId);
							 getMainProductFromFile();
							 getRelatedResultsFromFile();
							
						  }
					  }else if(discriminator.equalsIgnoreCase("OFFERS")){
						  
								financialBigImageType = false;
								offerId = intExtra.getStringExtra("offerId");
								offerDiscType =  intExtra.getStringExtra("offerDiscType");
								
								if(Common.isNetworkAvailable(FinancialActivity.this)){			
								   relatedType = "offer"; 
								   checkOfferExistinChangeLog(offerId);								   
								   if(!productPrice.equals("null")){
									    if(offerDiscType.equals("R"))
											txtvForValue.setText(productPrice +" Points");
									   else if(offerDiscType.equals("P"))
											txtvForValue.setText(productPrice+"% Off");
										else
												 txtvForValue.setText(productPrice);
									}else if(productPrice.equals("0.00") || productPrice.equals("0"))
											 txtvForValue.setText("Free");
									 else if(productPrice.equals("")){
											 txtvForValue.setText("");
									 }
										 
									String screenName = "/offers/"+clientId+"/"+offerId;	 
									String productIds = "";
									String offerIds = offerId;
									Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);							 
				    		}else{
								 relatedType = "offer";
								 getMainOfferFromFile();
								 getRelatedResultsFromFile();
							}
					 
				     }
			  }catch(Exception e){
				  e.printStackTrace();
				  String errorMsg = className+" | discriminator     | OFFERS |  " +e.getMessage();
				  Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
			  }
		 }
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	
	public void getMainOfferFromFile(){
		try{
			financialBigImageType = false;
			newoffers = new Offers();						    	
			offers = file.getOffers();
			relatedType = "offer";
			
		    UserOffers offerExist = offers.getUserOffers(Integer.parseInt(offerId));			
			if(offerExist != null){
				 locationBased = offerExist.getClientLocationBased();
				 Bitmap bm = aq.getCachedImage(offerExist.getOfferImage());				
				 if(bm != null)
					 imgvForPd.setImageBitmap(bm);
				 else{
				 		Intent returnIntent = new Intent();
						returnIntent.putExtra("activity","menu");
						setResult(RESULT_OK,returnIntent);
						finish();												
				 	}
			}	
			
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	public void getMainProductFromFile(){
		try{
			  financialBigImageType = true;
			  relatedType = "product";
			  productModel = new ProductModel();
			  getProdDetail = file.getProduct();
			  Log.e("getMainProductFromFile",productId);
			  UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(productId));
			  if(chkProdExist != null){
		    	     locationBased = chkProdExist.getClientLocationBased();
					 Bitmap bm = aq.getCachedImage(chkProdExist.getImageFile());
					 if(bm != null)
						 imgvForPd.setImageBitmap(bm);
					 else{
					 		Intent returnIntent = new Intent();
							returnIntent.putExtra("activity","menu");
							setResult(RESULT_OK,returnIntent);
							finish();												
					 	}
					 Log.e("price file",chkProdExist.getProductPrice());
					 if(!chkProdExist.getProductPrice().equals("null")){						
							 if(chkProdExist.getProductPrice().equals("0.00") || chkProdExist.getProductPrice().equals("0")){
								 if(chkProdExist.getProductPrice().equals("$0.00")) 
										 txtvForValue.setText("Free");
							 } else if(chkProdExist.getProductPrice().equals("")){
								 txtvForValue.setText("");
							 } else {
								 txtvForValue.setText(chkProdExist.getProductPrice());
							 }						 
					}else{
						txtvForValue.setText("");
					}
					 if(chkProdExist.getProdIsTryOn()== 1){
						 Button btnSeeItLive = (Button) findViewById(R.id.btnSeeItLive);
						 btnSeeItLive.setVisibility(View.VISIBLE);
						 new Common().btnForSeeItLiveWithAllColors(FinancialActivity.this, btnSeeItLive, "relative", "width", "financial", 
								 ""+chkProdExist.getProductId(), ""+chkProdExist.getClientId(),chkProdExist.getProdIsTryOn() ,
								 chkProdExist.getClientBackgroundColor(), chkProdExist.getClientLightColor(), 
								 chkProdExist.getClientDarkColor());
	    					}else{
	    						 Button btnSeeItLive = (Button) findViewById(R.id.btnSeeItLive);
	    						 btnSeeItLive.setVisibility(View.INVISIBLE);
	    						 new Common().btnForSeeItLiveWithAllColors(FinancialActivity.this, btnSeeItLive, "relative", "width", "financial", 
	    								 ""+chkProdExist.getProductId(), ""+chkProdExist.getClientId(),chkProdExist.getProdIsTryOn() ,
	    								 chkProdExist.getClientBackgroundColor(), chkProdExist.getClientLightColor(), 
	    								 chkProdExist.getClientDarkColor());
	    					}
				}	
			
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	public void getRelatedResultsFromFile() {
	try{
				  				
				 productModel = new ProductModel();
				 getProdDetail = file.getProduct();
				 
				  newoffers = new Offers();						    	
			      offers = file.getOffers();
			     
			      String prodRelatedIds,offerRelatedIds;
			      String[] prodRelatedIdsArr = null,offerRelatedIdsArr = null;
			      Log.e("productId",""+productId);
			      if(relatedType.equals("product")){
			    	  if(!productId.equalsIgnoreCase("null")){
			    		  if(!productId.equalsIgnoreCase("")){
				            UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(productId));
				             if(chkProdExist != null){
				        	 int count =0;				        	
	         				 prodRelatedIds    = chkProdExist.getProductRelatedId();
	         				 offerRelatedIds   = chkProdExist.getOfferRelatedId();
	         				Log.e("prodRelatedIds",""+prodRelatedIds);
				    		  if(prodRelatedIds != null){
				    					prodRelatedIdsArr  = chkProdExist.getProductRelatedId().split(",");
				    			 		count = count + prodRelatedIdsArr.length;
				    			}
				    			if(offerRelatedIds != null){
					    			if(!offerRelatedIds.equals("null")){
					    				offerRelatedIdsArr = chkProdExist.getOfferRelatedId().split(",");
					    				 count = count + offerRelatedIdsArr.length;
					    			}
				    			}	         				
				    		Log.e("prodRelatedIdsArr",""+prodRelatedIdsArr);
	         				 int i=0;
         					 List<UserProduct> chkRelatedProdExist = getProdDetail.getRelatedProduct(prodRelatedIds);         					
	         					 if(chkRelatedProdExist.size() > 0){
	         						 financialFinalStrArray = new String[chkRelatedProdExist.size()];  		
	         						
	         						for(UserProduct userProduct : chkRelatedProdExist){
	         							Log.e("userProduct.getProductId()",""+userProduct.getProductId());
	         							financialResArrays = new ArrayList<String>(); 
	        							financialResArrays.add(""+userProduct.getClientId());
	        							financialResArrays.add(""+userProduct.getProductId());
	        							financialResArrays.add(userProduct.getImageFile());
	        							financialResArrays.add(userProduct.getProductShortDesc());
	        							financialResArrays.add(userProduct.getProductPrice());
	        							financialResArrays.add(userProduct.getButtonName());
	        							financialResArrays.add(userProduct.getProductUrl());
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add("");
	        							financialResArrays.add(userProduct.getClientBackgroundColor());
	        							financialResArrays.add(userProduct.getClientLightColor());
	        							financialResArrays.add(userProduct.getClientDarkColor());
	        							financialResArrays.add("null");
	        							financialResArrays.add(userProduct.getProductName());
	        							financialResArrays.add(""+userProduct.getProdIsTryOn());
	        							
	        							
	        							financialFinalStrArray[i] = financialResArrays.toString();
	         				    		i++;
	         					 }
	         						
	    				}
         					if(offerRelatedIds != null){
         					 if(!offerRelatedIds.equals("")){
		         					List<UserOffers> offerExist = offers.getRelatedOffers(offerRelatedIds);
		     						int j=chkRelatedProdExist.size();
		     						if(offerExist.size() > 0){
		     							int totalArrSize = chkRelatedProdExist.size()+offerExist.size();
		     							financialFinalStrArray = Arrays.copyOf(financialFinalStrArray,totalArrSize);
		     							for(UserOffers userofferExist : offerExist){
		     								financialResArrays = new ArrayList<String>();
			     				    		if(offerExist != null){			     				    			
			        							
												financialResArrays.add(""+userofferExist.getOfferClientId());
			     				    			financialResArrays.add("null");
			     				    			financialResArrays.add("null");
			     				    			financialResArrays.add("null");
			     				    			financialResArrays.add("null");
			     				    			financialResArrays.add("null");
			     				    			financialResArrays.add("null");         				    			
			        							financialResArrays.add(""+userofferExist.getOfferId());
			        							financialResArrays.add(userofferExist.getOfferName());
			        							financialResArrays.add(userofferExist.getOfferImage());
			        			    			financialResArrays.add(userofferExist.getOfferDescription());
			        							if(userofferExist.getOfferDiscountValue() != ""){financialResArrays.add(userofferExist.getOfferDiscountValue());}else{financialResArrays.add("null");}
			        							financialResArrays.add(userofferExist.getOfferButtonName());
			        							financialResArrays.add(userofferExist.getofferPurchaseUrl());
			        							financialResArrays.add(userofferExist.getOfferDiscountType());
			        							financialResArrays.add(userofferExist.getOfferValidDate());
			        							financialResArrays.add("");
			        							financialResArrays.add(userofferExist.getOfferClientBgColor());
			        							financialResArrays.add(userofferExist.getOfferClientBgLightColor());
			        							financialResArrays.add(userofferExist.getOfferClientBgDarkColor());
			        							financialResArrays.add("null");
			        							financialResArrays.add("null");		      
			        							financialResArrays.add("");
			        							
			        							if(j<count){
				        							financialFinalStrArray[j] = financialResArrays.toString();
				        							Log.e("financialFinalStrArray"+j,""+financialFinalStrArray[j]);
										    		//financialOfferFinalArray[c] = financialProdResArrays.toString();
				         				    		j++;
			        							}
			     				    			}
		     							}
		     						}
         					 }
         					}
         			 	}
			    		  }
			    	  }
				   }else{
					   if(offerId != null){
			        	UserOffers offerExist = offers.getUserOffers(Integer.parseInt(offerId));
			    		if(offerExist != null){ 
			    			prodRelatedIds  = offerExist.getProdRelatedId();
			    			offerRelatedIds = offerExist.getOfferRelatedId();		
			    			// int count =0;
			    			 if(prodRelatedIds != null){
			    				 prodRelatedIdsArr  = offerExist.getProdRelatedId().split(",");
			    			 		//count = count + prodRelatedIdsArr.length;
			    			}
			    			if( offerRelatedIds != null || !offerRelatedIds.equals("")){
			    				offerRelatedIdsArr = offerExist.getOfferRelatedId().split(",");
			    				// count = count + offerRelatedIdsArr.length;
			    			}
			    		
			    			List<UserOffers> chkRelatedOfferExist = null;
	         				if(!offerRelatedIds.equals("")){
			    			  chkRelatedOfferExist = offers.getRelatedOffers(offerRelatedIds);
	         				}
			    			//  count = prodRelatedIdsArr.length + offerRelatedIdsArr.length;
			    			  
			    			         				 
			    			 int i=0;
			    			 if(chkRelatedOfferExist != null){
         					 if(chkRelatedOfferExist.size() > 0){         						 
         						financialFinalStrArray = new String[chkRelatedOfferExist.size()];				    		
   				  			    
         						for(UserOffers userOffers : chkRelatedOfferExist){     
         								financialResArrays = new ArrayList<String>();
	         							financialResArrays.add(""+userOffers.getOfferClientId());
	     				    			financialResArrays.add("");
	     				    			financialResArrays.add("");
	     				    			financialResArrays.add("");
	     				    			financialResArrays.add("");
	     				    			financialResArrays.add("");
	     				    			financialResArrays.add("");     							
        								financialResArrays.add(""+userOffers.getOfferId());
	        							financialResArrays.add(userOffers.getOfferName());
	        							financialResArrays.add(userOffers.getOfferImage());
	        			    			financialResArrays.add(userOffers.getOfferDescription());
	        							if(userOffers.getOfferDiscountValue() != ""){financialResArrays.add(userOffers.getOfferDiscountValue());}else{financialResArrays.add("null");}
	        							financialResArrays.add(userOffers.getOfferButtonName());
	        							financialResArrays.add(userOffers.getofferPurchaseUrl());
	        							financialResArrays.add(userOffers.getOfferDiscountType());
	        							financialResArrays.add(userOffers.getOfferValidDate());	
	        							financialResArrays.add("");	        						
	        							financialResArrays.add(userOffers.getOfferClientBgColor());
	        							financialResArrays.add(userOffers.getOfferClientBgLightColor());
	        							financialResArrays.add(userOffers.getOfferClientBgDarkColor());
	        							financialResArrays.add("null");
	        							financialResArrays.add("null");		   
	        							financialResArrays.add("");
	        							//financialResArrays.add(listEachXmlVal.text("financialVideoPdImgs").toString());        							
        							
        							financialFinalStrArray[i] = financialResArrays.toString();
        					
         				    		i++;
         						}
         						
         					 }
			    			 }

								List<UserProduct> chkProdExist = getProdDetail.getRelatedProduct(prodRelatedIds);
						        if(chkProdExist != null){
						        	if(chkProdExist.size()> 0){
									      	//int j=offerRelatedIdsArr.length;
						        		int j=0;
						        		int totalArrSize = chkProdExist.size();
						        		if(chkRelatedOfferExist != null){
						        			 j = chkRelatedOfferExist.size();
						        			 totalArrSize = chkRelatedOfferExist.size()+chkProdExist.size();
						        		}
									    
			     							financialFinalStrArray = Arrays.copyOf(financialFinalStrArray,totalArrSize);
									      	for(UserProduct userProdExist : chkProdExist){
			  									financialResArrays = new ArrayList<String>();
			  									financialResArrays.add(""+userProdExist.getClientId());
						         					financialResArrays.add(""+userProdExist.getProductId());
				        							financialResArrays.add(userProdExist.getImageFile());
				        							financialResArrays.add(userProdExist.getProductShortDesc());
				        							financialResArrays.add(userProdExist.getProductPrice());
				        							financialResArrays.add(userProdExist.getButtonName());
				        							financialResArrays.add(userProdExist.getProductUrl());
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add("");
				        							financialResArrays.add(userProdExist.getClientBackgroundColor());
				        							financialResArrays.add(userProdExist.getClientLightColor());
				        							financialResArrays.add(userProdExist.getClientDarkColor());
				        							financialResArrays.add("null");
				        							financialResArrays.add(userProdExist.getProductName());
				        							financialResArrays.add(""+userProdExist.getProdIsTryOn());
				        							
				        							financialFinalStrArray[j] = financialResArrays.toString();
				        							//Log.e("financialFinalStrArray[j]",""+financialFinalStrArray[j]);
										    		//financialOfferFinalArray[c] = financialProdResArrays.toString();
				         				    		j++;
			     				    		}
						        		}
						         }
			    			
			    		}
					   }
				   }
			//Log.e("financialFinalStrArray.len",""+financialFinalStrArray.length);
			   
			    if(financialFinalStrArray.length>0){
			 
			    	fancyCoverFlow.setVisibility(View.VISIBLE);
			        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
			        fancyCoverFlow.setMaxRotation(90);
			        fancyCoverFlow.setAdapter(renderForFiancialCarousel(financialFinalStrArray));	
			        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
						@Override
						public void onItemClick(AdapterView<?> arg0, View arg1,
								int arg2, long arg3) {
							try{
    							if(fancyCoverFlow.getSelectedView()!=null){
    								fancyCoverflowItemFromFile(arg1,arg2);
    							} 
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | fancyCoverFlow | click |  " +e.getMessage();
							    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
							}
						}		    			
		    		});
			    }
	  		
	}catch(Exception e){
		e.printStackTrace();
		String errorMsg = className+" | getRelatedResultsFromFile  " +e.getMessage();
	    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
	}
		
	}
	
	public void fancyCoverflowItemFromFile(View arg1, int arg2){
	 try{
							
				if(discriminator.equalsIgnoreCase("VIDEO")){				 	
					playerSurfaceView.setVisibility(View.INVISIBLE);
				      findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
				      imgvForPd.setVisibility(View.VISIBLE);
				}
				
				ImageView prodImage = (ImageView)arg1.findViewById(R.id.imgForCarousel);				
				if(offerId != null){
					 if(!offerId.equals("null") && !offerId.equals("")) {
						 arrBackProductList.add(offerId+"`"+"offer"+"`"+"OFFER"+"`"+"image"); 		
					 }
				}				  
				if(productId != null){
					 if(!productId.equals("null") && !productId.equals("")) {
					  		arrBackProductList.add(productId+"`"+"product"+"`"+"BUTTON"+"`"+"image");
					 }
				  }
				
				if(!prodImage.getTag(R.string.videoId).equals("")){    		        				 	
					  videoUrl = prodImage.getTag(R.string.videoId).toString();
				    playFinVideo(videoUrl);
				    
					  if(!offerId.equals("null") || !offerId.equals("") || offerId != null){
						 // arrBackProductList.add(offerId+"`"+"offer"+"`"+discriminator);
						  financialBigImageType = false;
						  relatedType = "offer";
						  offerId = prodImage.getTag(R.string.offerId).toString();
						  
					  }
					  if(!productId.equals("null")){
						 // arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
						  financialBigImageType = true;
						  productId = prodImage.getTag(R.string.productId).toString(); 
						  relatedType = "product";    		 
					  }		    		          					  
				} else {
					if(prodImage.getDrawable()==null){
					} else{
						BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
						Bitmap bitmap = test.getBitmap();
						ByteArrayOutputStream baos = new ByteArrayOutputStream();
						bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
						ImageView imgvForPd = (ImageView)findViewById(R.id.imgvForPd);
						imgvForPd.setImageBitmap(bitmap);
					  if(!prodImage.getTag(R.string.offerId).equals("") || !prodImage.getTag(R.string.offerId).equals("null")){		    								 
							  financialBigImageType = false;
							  relatedType = "offer";
							  offerId = prodImage.getTag(R.string.offerId).toString();	          					  
							
						  }
						if(!prodImage.getTag(R.string.productId).equals("") || !prodImage.getTag(R.string.productId).equals("null")){
						//	arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
							  financialBigImageType = true;
							  relatedType = "product";
							 
						  }	
						
					}
				}
				String s = "[";
				String q = "]";
				String w = "";
				String strReplaceSymbol = String.valueOf(financialFinalStrArray[arg2]).replace(s, w).replace(q, w);
				
				String[] expFinancialArray = strReplaceSymbol.split(",");
				
				String finalImagePath = "", finalImageId = "", finalImageClientId = "", 
						finalImageBtnName = "", finalImageBtnUrl = "", finalImagePrice = "", finalImageDiscType = "";
				String expClientId = expFinancialArray[0].trim(); //0 is client ID		
				String expProductId = expFinancialArray[1].trim(); //1 is product id
				String expProductImage = expFinancialArray[2].trim(); //2 is product image
				String expProductShortDesc = expFinancialArray[3].trim(); //3 is product short description
				String expProductPrice = expFinancialArray[4].trim(); //4 is product price
				String expProductButtonName = expFinancialArray[5].trim(); //5 is product button name
				String expProductUrl = expFinancialArray[6].trim(); //6 is product url
				String expOfferid = expFinancialArray[7].trim(); //7 is offer id
				String expOfferName = expFinancialArray[8].trim(); //8 is offer name
				String expOfferImage = expFinancialArray[9].trim(); //9 is offer image
				String expOfferShortDesc = expFinancialArray[10].trim(); //10 is Offer ShortDesc
				String expOfferDiscountVal = expFinancialArray[11].trim(); //11 is OfferDiscountVal
				String expOfferButtonName = expFinancialArray[12].trim(); //12 is OfferButtonName
				String expOfferPurchaseUrl = expFinancialArray[13].trim(); //13 is OfferPurchaseUrl
				String expOfferDiscountType = expFinancialArray[14].trim(); //14 is OfferDiscountType
				String expOfferValidFrom = expFinancialArray[15].trim(); //15 is OfferValidFrom
				String expfinancialVideoPdImgs = expFinancialArray[16].trim(); //16 is financialVideoPdImgs
				String expClientBgColor = expFinancialArray[17].trim(); //17 is ClientBgColor
				String expClientLightColor = expFinancialArray[18].trim(); //18 is ClientLightColor
				String expClientDarkColor = expFinancialArray[19].trim(); //19 is ClientDarkColor
				String expProdName = expFinancialArray[21].trim(); //20 is prodName
				
				if(relatedType.equals("product")){
					
					if(expProductId.equals("") || expProductId.equals("null")){
						financialBigImageType = false;
						finalImagePath = expOfferImage;
						finalImageId = expOfferid;
						finalImageBtnName = expOfferButtonName;
						finalImageBtnUrl = expOfferPurchaseUrl;
						finalImagePrice = expOfferDiscountVal;
						finalImageDiscType = expOfferDiscountType;
						offerTitle = expOfferName;
					} else {
						financialBigImageType = true;
						finalImagePath = expProductImage;
						finalImageId = expProductId;
						finalImageBtnName = expProductButtonName;
						finalImageBtnUrl = expProductUrl;
						finalImagePrice = expProductPrice;
						finalImageDiscType = "";
						offerTitle = expProdName;
					}
					finalImageClientId = expClientId;
				} else {
					
					if(expProductId.equals("")|| expProductId.equals("null")){
						financialBigImageType = false;
						finalImagePath = expOfferImage;
						finalImageId = expOfferid;
						finalImageClientId = expClientId;
						finalImageBtnName = expOfferButtonName;
						finalImageBtnUrl = expOfferPurchaseUrl;
						finalImagePrice = expOfferDiscountVal;
						finalImageDiscType = expOfferDiscountType;
						offerTitle = expOfferName;
					} else {
						financialBigImageType = true;
						finalImagePath = expProductImage;
						finalImageId = expProductId;
						finalImageBtnName = expProductButtonName;
						finalImageBtnUrl = expProductUrl;
						finalImagePrice = expProductPrice;
						finalImageDiscType = "";
						offerTitle = expProdName;
					}
				}
				
				Button imgvBtnFinancial = (Button) findViewById(R.id.imgvBtnFinancial);
				if(finalImageBtnName.equals("null")){  								
					imgvBtnFinancial.setVisibility(View.INVISIBLE);
				}else{
					imgvBtnFinancial.setVisibility(View.VISIBLE);
					imgvBtnFinancial.setText(finalImageBtnName);
				}
				
				productId = prodImage.getTag(R.string.productId).toString();	    							
				buttonUrl = finalImageBtnUrl;
				String pdUrl ="";
				if(financialBigImageType==false &&( discriminator.equals("BUTTON")  || discriminator.equals("VIDEO") || discriminator.equals("AUDIO"))){
					financialBigImageType = false;
					relatedType = "offer";
					offerId = prodImage.getTag(R.string.offerId).toString();		    							
					if(!finalImagePrice.equals("null")){
						 if(finalImageDiscType.equals("R"))
							txtvForValue.setText(finalImagePrice +" Points");
						 else if(finalImageDiscType.equals("P")){
							txtvForValue.setText(finalImagePrice+"% Off");
						 }else{
							 if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
								 txtvForValue.setText("Free");
							 } else if(finalImagePrice.equals("")){
								 txtvForValue.setText("");
							 } else {
								 txtvForValue.setText(finalImagePrice);
							 }
						 }
					}else{
						txtvForValue.setText("");
					}
					if(Common.isNetworkAvailable(FinancialActivity.this)){
						 checkOfferExistinChangeLog(offerId);
					 }else{
							getRelatedResultsFromFile();		    							
						}
					
					
					new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
					imgBtnCloset.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {
							ArrayList<String> arrayListCloset = new ArrayList<String>();
							arrayListCloset.add(offerId);					
							new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
						}
					});
				}
				else if(discriminator.equals("BUTTON") || discriminator.equals("VIDEO")  || discriminator.equals("AUDIO") ||(discriminator.equals("OFFERS") && financialBigImageType==true)){
					financialBigImageType = true;
					relatedType = "product";
					if(!finalImagePrice.equals("null")){
				
						 if(finalImageDiscType.equals("R"))
							txtvForValue.setText(finalImagePrice +" Points");
						 else if(finalImageDiscType.equals("P")){
							txtvForValue.setText(finalImagePrice+"% Off");
						 }else{
							 if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
								 txtvForValue.setText("Free");
							 } else if(finalImagePrice.equals("")){
								 txtvForValue.setText("");
							 } else {
								 txtvForValue.setText(finalImagePrice);
							 }
						 }
					}else{
						txtvForValue.setText("");
					}
					if(Common.isNetworkAvailable(FinancialActivity.this)){
						checkProductExistinChangeLog(productId);		    								
					}else{
						getRelatedResultsFromFile();		    							
					}
					new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_heart_icon, R.id.imgvBtnCloset);	
					imgBtnCloset.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {
							new Common().getLoginDialog(FinancialActivity.this, Closet.class, "Closet", new ArrayList<String>());
						}
					});
				
				
				}else if(discriminator.equals("OFFERS")){
					financialBigImageType = false;
					relatedType = "offer";
					offerId = prodImage.getTag(R.string.offerId).toString();
					if(!finalImagePrice.equals("null") ){
						 if(finalImageDiscType.equals("R"))
							txtvForValue.setText(finalImagePrice +" Points");
						 else if(finalImageDiscType.equals("P")){
							txtvForValue.setText(finalImagePrice+"% Off");
						 }else{
							 Log.i("finalImagePrice", ""+finalImagePrice);
							 if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
								 txtvForValue.setText("Free");
							 } else if(finalImagePrice.equals("")){
								 txtvForValue.setText("");
							 } else {
								 txtvForValue.setText(finalImagePrice);
							 }
						 }
					}else{
						txtvForValue.setText("");
					}
					 if(Common.isNetworkAvailable(FinancialActivity.this)){
						 checkOfferExistinChangeLog(offerId);
					 }else{
						getRelatedResultsFromFile();		    							
					 }
					
					new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
					imgBtnCloset.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {
							ArrayList<String> arrayListCloset = new ArrayList<String>();
							arrayListCloset.add(offerId);					
							new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
						}
					});
				}
				imgvBtnStar.setImageResource(R.drawable.btn_unsaved_heart);	
				//getRelatedResultsFromFile();
				
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	  public void getProductDetailsFromServer(String productUrl){
		  try{
			  final AQuery aq1 = new AQuery(FinancialActivity.this);		 
			  aq1.ajax(productUrl,  XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try {  
	        		    if(!xml.tags("products").equals(null)){
	        		    	ProductModel  getProdDetail = file.getProduct();
	        		    	 List<XmlDom> entries = xml.tags("products");
	       	    			if(entries.size() > 0){
	       	    				for(XmlDom entry: entries){  		        				
			        				if(getProdDetail.size() == 0){
			        					getProdDetail  = new ProductModel();
			        				}
			        				 String curveImagesUrl = entry.text("pdImage").toString().replaceAll(" ", "%20");	
			        				 new Common().DownloadImageFromUrl(FinancialActivity.this, curveImagesUrl, R.id.imgvForPd);
			        				 String symbol = new Common().getCurrencySymbol(entry.text("country_languages").toString(), entry.text("country_code_char2").toString());
			        				 String price ="";
			    					
			        				 productPrice = entry.text("pdPrice").toString();
			        				 Log.e("productPrice",""+productPrice);
			        				/* if(!productPrice.equals("null")){			
			        					 price = symbol+entry.text("pdPrice").toString();
													 txtvForValue.setText(productPrice);
										}else if(productPrice.equals("0.00") || productPrice.equals("0"))
											price ="Free";
										 else if(productPrice.equals("")){
											 price ="";
										 }*/
			        				 
			        				 if(!productPrice.equals("null")){						
										 if(productPrice.equals("0.00") || productPrice.equals("0") ){
											 if(productPrice.equals("$0.00") )
												 txtvForValue.setText("Free");
										 } else if(productPrice.equals("") || productPrice.equals("$") ){
											 txtvForValue.setText("");
										 } else {
											 txtvForValue.setText(productPrice);
										 }						 
									}else{
										txtvForValue.setText("");
									}
			        				 
			        				 txtvForValue.setText(price);
			        				 locationBased = entry.text("is_location_based").toString();
			        				 buttonName = entry.text("pd_button_name").toString();
		       	    						UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(entry.text("prodId").toString()));
		       	    						changeFlag = new Common().checkOfferExistinChangeLog("product",entry.text("prodId").toString());
		       	    						if(changeFlag){
			       	    						if(chkProdExist != null){
			       	    							getProdDetail.removeItem(chkProdExist);
			       	    							chkProdExist = null;
			       	    						}
		       	    						}
		       	    						if(chkProdExist == null){		       	    						
			                    			 UserProduct userProduct = new UserProduct();
			                    			 userProduct.setClientId(Integer.parseInt(entry.text("clientId").toString()));
			                    			 userProduct.setClientName(entry.text("name").toString());
			                    			 userProduct.setClientUrl(entry.text("clientUrl").toString());
			                    			 userProduct.setImageFile(curveImagesUrl);
			                    			 userProduct.setProductId(Integer.parseInt(entry.text("prodId").toString()));
			                    			 userProduct.setProductName(entry.text("prodName").toString());
			                    			 userProduct.setProductPrice(price);
			                    			 userProduct.setProductShortDesc(entry.text("pd_short_description").toString());
			                    			 userProduct.setProductUrl(entry.text("productUrl").toString());
			                    			 userProduct.setProdIsTryOn(Integer.parseInt(entry.text("pd_istryon").toString()));
			                    			 userProduct.setClientBackgroundColor(entry.text("background_color").toString());
			                    			 userProduct.setClientLightColor(entry.text("light_color").toString());
			                    			 userProduct.setClientDarkColor(entry.text("dark_color").toString());
			                    			 userProduct.setClientLogo(entry.text("clientLogo").toString());
			                    			 userProduct.setProductRelatedId(entry.text("related_id").toString());
			                    			 userProduct.setOfferRelatedId(entry.text("related_offerid").toString());
			                    			 userProduct.setButtonName(entry.text("pd_button_name").toString());
			                    			 userProduct.setClientLocationBased(entry.text("is_location_based").toString());
			                    			 getProdDetail.add(userProduct);		                    			 
		       	    					}
		       	    						if(entry.text("pd_istryon").toString().equals("1")){
											 Button btnSeeItLive = (Button) findViewById(R.id.btnSeeItLive);
											 btnSeeItLive.setVisibility(View.VISIBLE);
											 new Common().btnForSeeItLiveWithAllColors(FinancialActivity.this, btnSeeItLive, "relative", "width", "financial", 
														entry.text("prodId").toString(), entry.text("clientId").toString(), Integer.parseInt(entry.text("pd_istryon").toString()),
														entry.text("background_color").toString(), entry.text("light_color").toString(), 
														entry.text("dark_color").toString());
			       	    					}else{
			   	    						 Button btnSeeItLive = (Button) findViewById(R.id.btnSeeItLive);
				    						 btnSeeItLive.setVisibility(View.INVISIBLE);
				    						
				    					}
	       	    				}	       	    			
	                			 if(getProdDetail.size() >0){	                           		
	                           		file.setProduct(getProdDetail);                          		
	                           	}
	                			
	                			 String pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+productId+"/financial/"; 
								 getRelatedResultsFromServerWithXml(pdUrl);
								 
								 if(changeFlag){
		                				new Common().deleteChangeLogFields("product",Integer.parseInt(Common.sessionProductId));
		                			 }
	       	    			}						
	       	    		}
	        		   }catch(Exception e){
	        			   e.printStackTrace();
	        			   String errorMsg = className+" | getProductDetailsFromServer ajax call back    |   " +e.getMessage();
	        			   Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);	        			   
	        		   }
	        	}
		  });
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getProductDetailsFromServer    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		  }
	  }
	  ArrayList<String> financialResArraysProd,financialResArraysOffer,financialResArraysProdMain,financialResArraysOfferMain;
	
	public void getRelatedResultsFromServerWithXml(String getProductUrl){
		try{
			//Log.e("getProductUrl",getProductUrl);
		aq.ajax(getProductUrl, XmlDom.class, new AjaxCallback<XmlDom>(){
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status){
			  	try {
			  		if(xml != null){
			  	
				    if(!xml.tags("products").equals(null)){
				  		List<XmlDom> listAllXmlVals = xml.tags("products");
				  		if(listAllXmlVals.size()>0){	 
				  			Log.e("count", ""+listAllXmlVals.size());
				  			Log.e("count", ""+financialFinalStrArray.length);
				  			
							int c=0;
							 productModel = new ProductModel();
							 getProdDetail = file.getProduct();
							 
							  newoffers = new Offers();						    	
						      offers = file.getOffers();
						      
						      
							  String relatedIds ="";
							  String offerrelatedIds ="";
							  int ProdSingleArrSize =0;
							  int OfferSingleArrSize =0;
					    		String[] financialResArraysProdStrArray = new String[ProdSingleArrSize];
					    		String[] financialResArraysOfferStrArray = new String[OfferSingleArrSize];
					    		
					    		
						    for(XmlDom listEachXmlVal : listAllXmlVals){
						    	if(listEachXmlVal.tag("prodId")!=null || !listEachXmlVal.text("prodId").toString().equals("")){
							    	try { 							    		 
							    		if(c==0){
								  			financialFinalStrArray = new String[listAllXmlVals.size()];
							    		}
							    		String symbol = new Common().getCurrencySymbol(listEachXmlVal.text("country_languages").toString(), listEachXmlVal.text("country_code_char2").toString());
							    		if(listEachXmlVal.text("prodId").toString().equals("")){
	    									if(!listEachXmlVal.text("offer_id").toString().equals("")){
	    										financialResArraysOffer = new ArrayList<String>();
										  			financialResArraysOffer.add(listEachXmlVal.text("clientId").toString());
										  			financialResArraysOffer.add(listEachXmlVal.text("prodId").toString());
										  			financialResArraysOffer.add(listEachXmlVal.text("pdImage").toString());
										  			financialResArraysOffer.add(listEachXmlVal.text("pd_short_description").toString());
													if(listEachXmlVal.text("pdPrice").toString().equals("") || 
															listEachXmlVal.text("pdPrice").toString().equals("null")){
														financialResArraysOffer.add("");
													} else if(listEachXmlVal.text("pdPrice").toString().equals("0.00") ||
															listEachXmlVal.text("pdPrice").toString().equals("0")){
														financialResArraysOffer.add("Free");
													} else {
														financialResArraysOffer.add(symbol+listEachXmlVal.text("pdPrice").toString());
													}
													
													financialResArraysOffer.add(listEachXmlVal.text("pd_button_name").toString());
													financialResArraysOffer.add(listEachXmlVal.text("productUrl").toString());
													financialResArraysOffer.add(listEachXmlVal.text("offer_id").toString());
													financialResArraysOffer.add(listEachXmlVal.text("offer_name").toString());
													financialResArraysOffer.add(listEachXmlVal.text("offer_image").toString());
													financialResArraysOffer.add(listEachXmlVal.text("offer_short_description").toString());
													if(listEachXmlVal.text("offer_discount_value").toString() != ""){
															financialResArraysOffer.add(listEachXmlVal.text("offer_discount_value").toString());
														}else{
															financialResArraysOffer.add("null");
															}
													
	    									    financialResArraysOffer.add(listEachXmlVal.text("offer_button_name").toString());
		    									financialResArraysOffer.add(listEachXmlVal.text("offer_purchase_url").toString());
		    									financialResArraysOffer.add(listEachXmlVal.text("offer_discount_type").toString());
		    									financialResArraysOffer.add(listEachXmlVal.text("offer_valid_from").toString());
		    									financialResArraysOffer.add(listEachXmlVal.text("financialVideoPdImgs").toString());
												financialResArraysOffer.add(listEachXmlVal.text("background_color").toString());
												financialResArraysOffer.add(listEachXmlVal.text("light_color").toString());
												financialResArraysOffer.add(listEachXmlVal.text("dark_color").toString());
												financialResArraysOffer.add(listEachXmlVal.text("financialVideoType").toString());
												financialResArraysOffer.add(listEachXmlVal.text("prodName").toString());
												financialResArraysOffer.add(listEachXmlVal.text("pd_istryon").toString());
												
												OfferSingleArrSize++;
												financialResArraysOfferStrArray = Arrays.copyOf(financialResArraysOfferStrArray,OfferSingleArrSize);
												financialResArraysOfferStrArray[OfferSingleArrSize-1] = financialResArraysOffer.toString();
	    									}
							    		}else{

										    financialResArraysProd = new ArrayList<String>();
								  			financialResArraysProd.add(listEachXmlVal.text("clientId").toString());
								  			financialResArraysProd.add(listEachXmlVal.text("prodId").toString());
								  			financialResArraysProd.add(listEachXmlVal.text("pdImage").toString());
								  			financialResArraysProd.add(listEachXmlVal.text("pd_short_description").toString());
											if(listEachXmlVal.text("pdPrice").toString().equals("") || 
													listEachXmlVal.text("pdPrice").toString().equals("null")){
												financialResArraysProd.add("");
											} else if(listEachXmlVal.text("pdPrice").toString().equals("0.00") ||
													listEachXmlVal.text("pdPrice").toString().equals("0")){
												financialResArraysProd.add("Free");
											} else {
												financialResArraysProd.add(symbol+listEachXmlVal.text("pdPrice").toString());
											}
											
											financialResArraysProd.add(listEachXmlVal.text("pd_button_name").toString());
											financialResArraysProd.add(listEachXmlVal.text("productUrl").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_id").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_name").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_image").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_short_description").toString());
											if(listEachXmlVal.text("offer_discount_value").toString() != ""){
												financialResArraysProd.add(listEachXmlVal.text("offer_discount_value").toString());
												}else{
													financialResArraysProd.add("null");
													}
											
											financialResArraysProd.add(listEachXmlVal.text("offer_button_name").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_purchase_url").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_discount_type").toString());
											financialResArraysProd.add(listEachXmlVal.text("offer_valid_from").toString());
											financialResArraysProd.add(listEachXmlVal.text("financialVideoPdImgs").toString());
											financialResArraysProd.add(listEachXmlVal.text("background_color").toString());
											financialResArraysProd.add(listEachXmlVal.text("light_color").toString());
											financialResArraysProd.add(listEachXmlVal.text("dark_color").toString());
											financialResArraysProd.add(listEachXmlVal.text("financialVideoType").toString());
											financialResArraysProd.add(listEachXmlVal.text("prodName").toString());
											financialResArraysProd.add(listEachXmlVal.text("pd_istryon").toString());
											//financialResArraysProdMain.addAll(financialResArraysProd);											
											ProdSingleArrSize++;
											financialResArraysProdStrArray = Arrays.copyOf(financialResArraysProdStrArray,ProdSingleArrSize);
											financialResArraysProdStrArray[ProdSingleArrSize-1] = financialResArraysProd.toString();
											
							    		}
										
										locationBased = listEachXmlVal.text("is_location_based").toString();
										if(relatedIds !=""){
											if( !listEachXmlVal.text("prodId").toString().equals(""))
												relatedIds = relatedIds+","+listEachXmlVal.text("prodId").toString();
										}else{
											if( !listEachXmlVal.text("prodId").toString().equals(""))
												relatedIds = listEachXmlVal.text("prodId").toString();
										}
										if(offerrelatedIds !="" && !listEachXmlVal.text("offer_id").toString().equals("")){
											if( !listEachXmlVal.text("offer_id").toString().equals(""))
												offerrelatedIds = offerrelatedIds+","+listEachXmlVal.text("offer_id").toString();
										}else{
											if( !listEachXmlVal.text("offer_id").toString().equals(""))
												offerrelatedIds = listEachXmlVal.text("offer_id").toString();
										}
										

										if(relatedType.equals("product"))
										{		    								
		    								if(listEachXmlVal.text("prodId").toString().equals("")){
		    									if(!listEachXmlVal.text("offer_id").toString().equals("")){
		    										UserOffers offerExist = offers.getUserOffers(Integer.parseInt(listEachXmlVal.text("offer_id").toString()));		    										 
										    		if(offerExist == null){
										    			UserOffers userOffer = new UserOffers();
													   	userOffer.setOfferId(Integer.parseInt(listEachXmlVal.text("offer_id").toString()));										   	
													   	userOffer.setOfferImage(listEachXmlVal.text("offer_image").toString().replaceAll(" ", "%20"));
													   	userOffer.setOfferClientName(listEachXmlVal.text("name").toString());
													   	userOffer.setOfferName(listEachXmlVal.text("offer_name").toString());
													   	if(listEachXmlVal.text("offer_discount_type").toString().equals("A")){
														  	userOffer.setCurrencySymbol(symbol);
															if (listEachXmlVal.text("offer_discount_value").toString().equals("null") || 
																	listEachXmlVal.text("offer_discount_value").toString().equals("") || 
																	listEachXmlVal.text("offer_discount_value").toString().equals("0") || 
																	listEachXmlVal.text("offer_discount_value").toString().equals("0.00") || 
																	listEachXmlVal.text("offer_discount_value").toString() == null) {
																userOffer.setOfferDiscountValue("0");
															} else {
																userOffer.setOfferDiscountValue(listEachXmlVal.text("offer_discount_value").toString());
															}
													   	} else {
													   		userOffer.setCurrencySymbol("");
													   		userOffer.setOfferDiscountValue(listEachXmlVal.text("offer_discount_value").toString());
													   	}
													   	
													   	userOffer.setOfferDiscountType(listEachXmlVal.text("offer_discount_type").toString());
													   	userOffer.setOfferPurchaseUrl(listEachXmlVal.text("offer_purchase_url").toString());
													   	userOffer.setOfferValidDate(listEachXmlVal.text("offer_valid_to").toString());
													   	userOffer.setOfferDescription(listEachXmlVal.text("offer_description").toString());
													   	userOffer.setClientVerticalId(listEachXmlVal.text("client_vertical_id").toString());
													   	userOffer.setOfferButtonName(listEachXmlVal.text("offer_button_name").toString());
													   	userOffer.setOfferClientId(listEachXmlVal.text("client_id").toString());
														userOffer.setOfferClientBgColor(listEachXmlVal.text("background_color").toString());
														userOffer.setOfferClientBgLightColor(listEachXmlVal.text("light_color").toString());
														userOffer.setOfferClientBgDarkColor(listEachXmlVal.text("dark_color").toString());
														userOffer.setOfferRelatedId(listEachXmlVal.text("related_offerid").toString());
														userOffer.setProdRelatedId(listEachXmlVal.text("related_id").toString());
														userOffer.setClientLocationBased(listEachXmlVal.text("is_location_based").toString());
														userOffer.setOfferIsSharable(listEachXmlVal.text("offer_is_sharable").toString());
														userOffer.setOfferBackImage(listEachXmlVal.text("offer_back_image").toString());
														userOffer.setOfferMultiRedeem(listEachXmlVal.text("offer_is_multi_redeem").toString());
														newoffers.add(userOffer);												   	
										    		
										    	}   
		    									}
		    								}else{		    									
			    					    		 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(listEachXmlVal.text("prodId").toString()));
			    					    		 if(chkProdExist == null){
			    		                			 UserProduct userProduct = new UserProduct();
			    		                			 userProduct.setClientId(Integer.parseInt(listEachXmlVal.text("clientId").toString()));
			    		                			 userProduct.setClientName(listEachXmlVal.text("name").toString());
			    		                			 userProduct.setClientUrl(listEachXmlVal.text("clientUrl").toString());
			    		                			 userProduct.setImageFile(listEachXmlVal.text("pdImage").toString().replaceAll(" ", "%20"));
			    		                			 userProduct.setProductId(Integer.parseInt(listEachXmlVal.text("prodId").toString()));
			    		                			 userProduct.setProductName(listEachXmlVal.text("prodName").toString());			    		                			
			    		                			 if(listEachXmlVal.text("pdPrice").toString().equals("null") || listEachXmlVal.text("pdPrice").toString().equals("") || listEachXmlVal.text("pdPrice").toString().equals("0")   || listEachXmlVal.text("pdPrice").toString().equals("0.00")  ){
			    		                				 userProduct.setProductPrice(listEachXmlVal.text("pdPrice").toString());			    		                				 
			    		                			 }
			    		                			 else{
			    		                				 userProduct.setProductPrice(symbol+listEachXmlVal.text("pdPrice").toString());
			    		                			  }
			    		                			 userProduct.setProductShortDesc(listEachXmlVal.text("pd_short_description").toString());
			    		                			 userProduct.setProductUrl(listEachXmlVal.text("productUrl").toString());
			    		                			 userProduct.setProdIsTryOn(Integer.parseInt(listEachXmlVal.text("pd_istryon").toString()));
			    		                			 userProduct.setClientBackgroundColor(listEachXmlVal.text("background_color").toString());
			    		                			 userProduct.setClientLightColor(listEachXmlVal.text("light_color").toString());
			    		                			 userProduct.setClientDarkColor(listEachXmlVal.text("dark_color").toString());
			    		                			 userProduct.setClientLogo(listEachXmlVal.text("clientLogo").toString());
			    		                			 userProduct.setProductRelatedId(listEachXmlVal.text("related_id").toString());
			    		                			 userProduct.setOfferRelatedId(listEachXmlVal.text("related_offerid").toString());
			    		                			 userProduct.setButtonName(listEachXmlVal.text("pd_button_name").toString());
			    		                			 userProduct.setClientLocationBased(listEachXmlVal.text("is_location_based").toString());
			    		                			 productModel.add(userProduct);
			    		            			 } 
		    								}
		    				    		}else{		    								
		    				    			if(listEachXmlVal.text("prodId").toString().equals("")){			    									
		    									UserOffers offerExist = offers.getUserOffers(Integer.parseInt(listEachXmlVal.text("offer_id").toString()));
									    		if(offerExist == null){
									    			UserOffers userOffer = new UserOffers();
												   	userOffer.setOfferId(Integer.parseInt(listEachXmlVal.text("offer_id").toString()));										   	
												   	userOffer.setOfferImage(listEachXmlVal.text("offer_image").toString().replaceAll(" ", "%20"));
												   	userOffer.setOfferClientName(listEachXmlVal.text("name").toString());
												   	userOffer.setOfferName(listEachXmlVal.text("offer_name").toString());
												   	if(listEachXmlVal.text("offer_discount_type").toString().equals("A")){
													  	userOffer.setCurrencySymbol(symbol);
														if (listEachXmlVal.text("offer_discount_value").toString().equals("null") || 
																listEachXmlVal.text("offer_discount_value").toString().equals("") || 
																listEachXmlVal.text("offer_discount_value").toString().equals("0") || 
																listEachXmlVal.text("offer_discount_value").toString().equals("0.00") || 
																listEachXmlVal.text("offer_discount_value").toString() == null) {
															userOffer.setOfferDiscountValue("0");
														} else {															
															userOffer.setOfferDiscountValue(listEachXmlVal.text("offer_discount_value").toString());
														}
												   	} else {
												   		userOffer.setCurrencySymbol("");
												   		userOffer.setOfferDiscountValue(listEachXmlVal.text("offer_discount_value").toString());
												   	}
												   	
												   	userOffer.setOfferDiscountType(listEachXmlVal.text("offer_discount_type").toString());
												   	userOffer.setOfferPurchaseUrl(listEachXmlVal.text("offer_purchase_url").toString());
												   	userOffer.setOfferValidDate(listEachXmlVal.text("offer_valid_to").toString());
												   	userOffer.setOfferDescription(listEachXmlVal.text("offer_description").toString());
												   	userOffer.setClientVerticalId(listEachXmlVal.text("client_vertical_id").toString());
												   	userOffer.setOfferButtonName(listEachXmlVal.text("offer_button_name").toString());
												   	userOffer.setOfferClientId(listEachXmlVal.text("client_id").toString());
													userOffer.setOfferClientBgColor(listEachXmlVal.text("background_color").toString());
													userOffer.setOfferClientBgLightColor(listEachXmlVal.text("light_color").toString());
													userOffer.setOfferClientBgDarkColor(listEachXmlVal.text("dark_color").toString());
													userOffer.setOfferRelatedId(listEachXmlVal.text("related_offerid").toString());
													userOffer.setProdRelatedId(listEachXmlVal.text("related_id").toString());
													userOffer.setClientLocationBased(listEachXmlVal.text("is_location_based").toString());
													userOffer.setOfferIsSharable(listEachXmlVal.text("offer_is_sharable").toString());
													userOffer.setOfferBackImage(listEachXmlVal.text("offer_back_image").toString());
													userOffer.setOfferMultiRedeem(listEachXmlVal.text("offer_is_multi_redeem").toString());
												   	newoffers.add(userOffer);
									    			}   	
			    								}else{			    									
				    					    		 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(listEachXmlVal.text("prodId").toString()));
				    					    		 if(chkProdExist == null){
				    		                			 UserProduct userProduct = new UserProduct();
				    		                			 userProduct.setClientId(Integer.parseInt(listEachXmlVal.text("clientId").toString()));
				    		                			 userProduct.setClientName(listEachXmlVal.text("name").toString());
				    		                			 userProduct.setClientUrl(listEachXmlVal.text("clientUrl").toString());
				    		                			 userProduct.setImageFile(listEachXmlVal.text("pdImage").toString().replaceAll(" ", "%20"));
				    		                			 userProduct.setProductId(Integer.parseInt(listEachXmlVal.text("prodId").toString()));
				    		                			 userProduct.setProductName(listEachXmlVal.text("prodName").toString());
				    		                			 if(!listEachXmlVal.text("pdPrice").toString().equals("null")|| !listEachXmlVal.text("pdPrice").toString().equals("0.00")  || !listEachXmlVal.text("pdPrice").toString().equals("0") || !listEachXmlVal.text("pdPrice").toString().equals(""))
				    		                				 userProduct.setProductPrice(symbol+listEachXmlVal.text("pdPrice").toString());
				    		                			 else
				    		                				 userProduct.setProductPrice(listEachXmlVal.text("pdPrice").toString());			    		                				 
				    		                				 
				    		                			 userProduct.setProductShortDesc(listEachXmlVal.text("pd_short_description").toString());
				    		                			 userProduct.setProductUrl(listEachXmlVal.text("productUrl").toString());
				    		                			 userProduct.setProdIsTryOn(Integer.parseInt(listEachXmlVal.text("pd_istryon").toString()));
				    		                			 userProduct.setClientBackgroundColor(listEachXmlVal.text("background_color").toString());
				    		                			 userProduct.setClientLightColor(listEachXmlVal.text("light_color").toString());
				    		                			 userProduct.setClientDarkColor(listEachXmlVal.text("dark_color").toString());
				    		                			 userProduct.setClientLogo(listEachXmlVal.text("clientLogo").toString());
				    		                			 userProduct.setProductRelatedId(listEachXmlVal.text("related_id").toString());
				    		                			 userProduct.setOfferRelatedId(listEachXmlVal.text("related_offerid").toString());
				    		                			 userProduct.setButtonName(listEachXmlVal.text("pd_button_name").toString());
				    		                			 userProduct.setClientLocationBased(listEachXmlVal.text("is_location_based").toString());
				    		                			 productModel.add(userProduct);
				    		            			 } 
			    							}
		    				    		}
										/*Bitmap bitmap = aq.getCachedImage(listEachXmlVal.text("offer_image").toString().replaceAll(" ", "%20"));
	    								if(bitmap==null){
	    									aq.cache(listEachXmlVal.text("offer_image").toString(), 14400000);
	    								}
	    								 Bitmap bitmap1 = aq.getCachedImage(listEachXmlVal.text("pdImage").toString().replaceAll(" ", "%20"));
	    								 if(bitmap1==null){
	    									aq.cache(listEachXmlVal.text("pdImage").toString(), 14400000);
	    								}*/
//							    		Log.i("financialFinalStrArray "+c, c+" "+financialResArrays.toString());
							    		//financialFinalStrArray[c] = financialResArrays.toString();							    		
							    		c++;
							    	} catch (Exception e){
							    		e.printStackTrace();
							    		String errorMsg = className+" | getRelatedResultsFromServerWithXml | for loop | " +e.getMessage();
	    								Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
							    	}
						    	}
						    }	    
						    /*financialResArraysProd.addAll(financialResArraysOffer);
						    financialFinalStrArray = new String[financialResArraysProd.size()];
						    if(financialResArraysProd.size() >0 ){
						    	for(int j=0;j<financialResArraysProd.size();j++ ){
						    		financialFinalStrArray[j] = financialResArraysProd.toString();
						    	}
						    }*/
						    
						   // String[] financialFinalStrArray = ArrayUtils.addAll(first, second);
						    financialFinalStrArray = new String[financialResArraysProdStrArray.length + financialResArraysOfferStrArray.length];
						    System.arraycopy(financialResArraysProdStrArray, 0, financialFinalStrArray, 0, financialResArraysProdStrArray.length);
						    System.arraycopy(financialResArraysOfferStrArray, 0, financialFinalStrArray, financialResArraysProdStrArray.length, financialResArraysOfferStrArray.length);

						    if(financialFinalStrArray.length>0){
						    	fancyCoverFlow.setVisibility(View.VISIBLE);
						        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
						        fancyCoverFlow.setMaxRotation(90);
						        fancyCoverFlow.setAdapter(renderForFiancialCarousel(financialFinalStrArray));
						        
						        if(relatedType.equals("product")){
						         UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(productId));
		            			 if(chkProdExist != null){		            				 
		            				 chkProdExist.setProductRelatedId(relatedIds);
		            				 chkProdExist.setOfferRelatedId(offerrelatedIds);
		            				 productModel.add(chkProdExist);
		            				 getProdDetail.removeItem(chkProdExist);
		            				
		            			 }
						        }else{
						        	UserOffers offerExist = offers.getUserOffers(Integer.parseInt(offerId));
						    		if(offerExist != null){
						    			offerExist.setProdRelatedId(relatedIds);
						    			offerExist.setOfferRelatedId(offerrelatedIds);	
						    			newoffers.add(offerExist);
						    			offers.removeItem(offerExist);						    			
						    		}
						        }
						    if(productModel.size() >0){
	                     		getProdDetail.mergeWith(productModel);
	                     		file.setProduct(getProdDetail);
		                    }
					    	if(offers.size() >0){
				    			offers.mergeWithOffers(newoffers);
				    			file.setOffers(offers);
							}else{
							   	file.setOffers(newoffers);
							}					    	
						     fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
		    						@Override
		    						public void onItemClick(AdapterView<?> arg0, View arg1,
		    								int arg2, long arg3) {
		    							try{ 
			    							if(fancyCoverFlow.getSelectedView()!=null){
			    								fancyCoverFlowItem(arg1,arg2);
			    							} 
		    							}catch(Exception e){
		    								e.printStackTrace();
		    								String errorMsg = className+" | fancyCoverFlow |  " +e.getMessage();
		    								Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		    							}
		    						}
		    		    			
		    		    		});
	
						    }else{
						    	fancyCoverFlow.setVisibility(View.INVISIBLE);						    	
						    }
				  		}
				    }
			  		}else{
				    	fancyCoverFlow.setVisibility(View.INVISIBLE);						    	
				    }
			  	} catch (Exception e){
			  		e.printStackTrace();
			  		String errorMsg = className+" | getRelatedResultsFromServerWithXml |  " +e.getMessage();
					Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
			  	}
			}
			
		});
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getRelatedResultsFromServerWithXml  " +e.getMessage();
		    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		}
	}
	
	public void fancyCoverFlowItem(View arg1, int arg2){
		try{
			 if (player != null) {
				 player.release();
				 player = null;
			   }
			 
				if(discriminator.equalsIgnoreCase("VIDEO")){			    								 	
						playerSurfaceView.setVisibility(View.INVISIBLE);
						findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
						imgvForPd.setVisibility(View.VISIBLE);
					}
					/*if(offerId != null){
						if(!offerId.equals("null") && !offerId.equals("")) {
							arrBackProductList.add(offerId+"`"+"offer"+"`"+discriminator); 		
						}
					  }
								
					 if(productId != null){
						 if(!productId.equals("null") && !productId.equals("")) {
								arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
							}
					 	}
							*/	
								
						ImageView prodImage = (ImageView)arg1.findViewById(R.id.imgForCarousel);				    		                		
						if(!prodImage.getTag(R.string.videoId).equals("")){
					           if(playerType.equals("video")){					    		                		
									videoUrl = prodImage.getTag(R.string.videoId).toString();
									playFinVideo(videoUrl);							
								if(offerId.equals("null") && productId.equals("null")){					  
										String screenName = "/video/"+clientId;
										String productIds = "";
										String offerIds = "";
										Common.sendJsonWithAQuery(FinancialActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);					  
									}
									
								if(offerId != null){
									if(!offerId.equals("null") && !offerId.equals("")) {
									financialBigImageType = false;
									String pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+offerId+"/financial/";
									relatedType = "offer";
									getRelatedResultsFromServerWithXml(pdUrl);
									//arrBackProductList.add(offerId+"`"+"offer"+"`"+discriminator);
									String screenName = "/video/offers/"+clientId+"/"+offerId;
									String productIds = "";
									String offerIds = offerId;
									Common.sendJsonWithAQuery(FinancialActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
									 }								
									}
								arrBackProductList.add(productId+"`"+"product"+"`"+"VIDEO"+"`"+prodImage.getTag(R.string.videoId));
								}else{
									arrBackProductList.add(productId+"`"+"product"+"`"+"AUDIO"+"`"+prodImage.getTag(R.string.videoId));
								}
					           if(productId != null){
									 if(!productId.equals("null") && !productId.equals("")) {
									financialBigImageType = true;
									String pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+productId+"/financial/";
									Log.i("pdUrl",pdUrl);
									relatedType = "product";
									getRelatedResultsFromServerWithXml(pdUrl);
									// arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
									String screenName = "/video/product/"+clientId+"/"+productId;
									String productIds = productId;
									String offerIds = "";
									Common.sendJsonWithAQuery(FinancialActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
								}		    		          					  
							}
					           
						}else {			    		                			
								if(prodImage.getDrawable()==null){
								} else{
								BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
								Bitmap bitmap = test.getBitmap();
								ByteArrayOutputStream baos = new ByteArrayOutputStream();
								bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
								ImageView imgvForPd = (ImageView)findViewById(R.id.imgvForPd);
								imgvForPd.setImageBitmap(bitmap);
								findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
								imgvForPd.setVisibility(View.VISIBLE);
								findViewById(R.id.rlForVideos).setVisibility(View.INVISIBLE);
								}
								if(offerId != null){
									if(!offerId.equals("null") && !offerId.equals("")) {
										arrBackProductList.add(offerId+"`"+"offer"+"`"+"OFFER"+"`"+"image"); 		
									}
								  }
											
								 if(productId != null){
									 if(!productId.equals("null") && !productId.equals("")) {
											arrBackProductList.add(productId+"`"+"product"+"`"+"BUTTON"+"`"+"image");
										}
								 	}
							}
								
								TextView txtvForValue = (TextView)findViewById(R.id.txtvForValue);
								String s = "[";
								String q = "]";
								String w = "";
								String strReplaceSymbol = String.valueOf(financialFinalStrArray[arg2]).replace(s, w).replace(q, w);	
								String[] expFinancialArray = strReplaceSymbol.split(",");				    							
								String finalImagePath = "", finalImageId = "", finalImageClientId = "", 
								finalImageBtnName = "", finalImageBtnUrl = "", finalImagePrice = "", finalImageDiscType = "";
								String expClientId = expFinancialArray[0].trim(); //0 is client ID		
								String expProductId = expFinancialArray[1].trim(); //1 is product id
								String expProductImage = expFinancialArray[2].trim(); //2 is product image
								String expProductShortDesc = expFinancialArray[3].trim(); //3 is product short description
								String expProductPrice = expFinancialArray[4].trim(); //4 is product price
								String expProductButtonName = expFinancialArray[5].trim(); //5 is product button name
								String expProductUrl = expFinancialArray[6].trim(); //6 is product url
								String expOfferid = expFinancialArray[7].trim(); //7 is offer id
								String expOfferName = expFinancialArray[8].trim(); //8 is offer name
								String expOfferImage = expFinancialArray[9].trim(); //9 is offer image
								String expOfferShortDesc = expFinancialArray[10].trim(); //10 is Offer ShortDesc
								String expOfferDiscountVal = expFinancialArray[11].trim(); //11 is OfferDiscountVal
								String expOfferButtonName = expFinancialArray[12].trim(); //12 is OfferButtonName
								String expOfferPurchaseUrl = expFinancialArray[13].trim(); //13 is OfferPurchaseUrl
								String expOfferDiscountType = expFinancialArray[14].trim(); //14 is OfferDiscountType
								String expOfferValidFrom = expFinancialArray[15].trim(); //15 is OfferValidFrom
								String expfinancialVideoPdImgs = expFinancialArray[16].trim(); //16 is financialVideoPdImgs
								String expClientBgColor = expFinancialArray[17].trim(); //17 is ClientBgColor
								String expClientLightColor = expFinancialArray[18].trim(); //18 is ClientLightColor
								String expClientDarkColor = expFinancialArray[19].trim(); //19 is ClientDarkColor
								String expProdName = expFinancialArray[21].trim(); //20 is prodName
								Log.e("relatedType",relatedType);
								//if(relatedType.equals("product")){								
									//if(expProductId.equals("")){
										//if(expProductId == null){
								
									if(expProductId.trim().equals("") || expProductId.equals("null") || expProductId.trim().length() <= 0) {
										Log.e("expProductId","expProductId"+expProductId);
												financialBigImageType = false;
												finalImagePath = expOfferImage;
												finalImageId = expOfferid;
												finalImageBtnName = expOfferButtonName;
												finalImageBtnUrl = expOfferPurchaseUrl;
												finalImagePrice = expOfferDiscountVal;
												finalImageDiscType = expOfferDiscountType;
												offerTitle = expOfferName;
												//relatedType = "product";
									} else {
										financialBigImageType = true;
										finalImagePath = expProductImage;
										finalImageId = expProductId;
										finalImageBtnName = expProductButtonName;
										finalImageBtnUrl = expProductUrl;
										finalImagePrice = expProductPrice;
										finalImageDiscType = "";
										offerTitle = expProdName;
										String curveImagesUrl = expProductImage;	
										relatedType = "offer";
									}
									finalImageClientId = expClientId;		
									//arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
									/*} else {								
										if(expProductId.equals("")){
											financialBigImageType = false;
											finalImagePath = expOfferImage;
											finalImageId = expOfferid;
											finalImageClientId = expClientId;
											finalImageBtnName = expOfferButtonName;
											finalImageBtnUrl = expOfferPurchaseUrl;
											finalImagePrice = expOfferDiscountVal;
											finalImageDiscType = expOfferDiscountType;
											offerTitle = expOfferName;
										} else {
											financialBigImageType = true;
											finalImagePath = expProductImage;
											finalImageId = expProductId;
											finalImageBtnName = expProductButtonName;
											finalImageBtnUrl = expProductUrl;
											finalImagePrice = expProductPrice;
											finalImageDiscType = "";
											offerTitle = expProdName;
										}								
									}*/
								Button imgvBtnFinancial = (Button) findViewById(R.id.imgvBtnFinancial);
								if(finalImageBtnName.equals("null")){				    											    								
									imgvBtnFinancial.setVisibility(View.INVISIBLE);
								}else{				    								
									imgvBtnFinancial.setVisibility(View.VISIBLE);
									imgvBtnFinancial.setText(finalImageBtnName);
								}
								Log.e("discriminator",discriminator);
								productId = finalImageId;
								buttonUrl = finalImageBtnUrl;
								String pdUrl ="";
									if(financialBigImageType==false &&( discriminator.equals("BUTTON")  || discriminator.equals("VIDEO") || discriminator.equals("AUDIO"))){
										financialBigImageType = false;
										pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+finalImageId+"/financial/";
										relatedType = "offer";
										offerId = finalImageId;		    										 
									if(!finalImagePrice.equals("null") || !finalImagePrice.equals("")){							
										if(finalImageDiscType.equals("R"))
											txtvForValue.setText(finalImagePrice +" Points");
										else if(finalImageDiscType.equals("P")){
											txtvForValue.setText(finalImagePrice+"% Off");
										}else{				    										 
										if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
											txtvForValue.setText("Free");
										} else if(finalImagePrice.equals("")){
											txtvForValue.setText("");
										} else {
											txtvForValue.setText(finalImagePrice);
										}
										}
									}else{
										txtvForValue.setText("");
									}
									
									checkOfferExistinChangeLog(offerId);
									new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
									imgBtnCloset.setOnClickListener(new OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												ArrayList<String> arrayListCloset = new ArrayList<String>();
												arrayListCloset.add(offerId);
												arrayListCloset.add("Financial");	
												new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
											}catch(Exception e){
												e.printStackTrace();
												String errorMsg = className+" | fancyCoverFlow |BUTTON | imgBtnCloset click |  " +e.getMessage();
												Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
												}
												}
										});
								}
								else if(discriminator.equals("BUTTON") || discriminator.equals("VIDEO") || discriminator.equals("AUDIO")||(discriminator.equals("OFFERS") && financialBigImageType==true) && financialBigImageType==true){
									financialBigImageType = true;
									pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+finalImageId+"/financial/";
									relatedType = "product";		    										
									if(!finalImagePrice.equals("null") || !finalImagePrice.equals("")){					    								 
										if(finalImageDiscType.equals("R"))
											txtvForValue.setText(finalImagePrice +" Points");
										else if(finalImageDiscType.equals("P")){
											txtvForValue.setText(finalImagePrice+"% Off");
									   }else{
										   Log.i("finalImagePrice", ""+finalImagePrice);
										   if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
											   txtvForValue.setText("Free");
										   } else if(finalImagePrice.equals("")){
											   txtvForValue.setText("");
										   }  else {
											   txtvForValue.setText(finalImagePrice);
										   }
									   }
								}else{
									txtvForValue.setText("");
								}					    							
								checkProductExistinChangeLog(productId); 								
								
								new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_heart_icon, R.id.imgvBtnCloset);
								imgBtnCloset.setOnClickListener(new OnClickListener() {
									@Override
									public void onClick(View v) {
									try{
										new Common().getLoginDialog(FinancialActivity.this, Closet.class, "Closet", new ArrayList<String>());
									}catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | fancyCoverFlow |BUTTON | imgBtnCloset click|  " +e.getMessage();
										Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
										}
									}
								});
								}else if(discriminator.equals("OFFERS")){
									financialBigImageType = false;
									pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+finalImageId+"/financial/";
									relatedType = "offer";
									offerId = finalImageId;		    										
								if(!finalImagePrice.equals("null") || !finalImagePrice.equals("")){					    								 
									if(finalImageDiscType.equals("R"))
										txtvForValue.setText(finalImagePrice +" Points");
									else if(finalImageDiscType.equals("P")){
										txtvForValue.setText(finalImagePrice+"% Off");
									}else{
										Log.i("finalImagePrice", ""+finalImagePrice);
										if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
											txtvForValue.setText("Free");
										} else if(finalImagePrice.equals("")){
											txtvForValue.setText("");
										}  else {
											txtvForValue.setText(finalImagePrice);
										}
									}
								}else{
									txtvForValue.setText("");
								}
								checkOfferExistinChangeLog(offerId);
								new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
								imgBtnCloset.setOnClickListener(new OnClickListener() {
									@Override
									public void onClick(View v) {
										try{
											ArrayList<String> arrayListCloset = new ArrayList<String>();
											arrayListCloset.add(offerId);		
											arrayListCloset.add("Financial");
											new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
										}catch(Exception e){
											e.printStackTrace();
											String errorMsg = className+" | fancyCoverFlow |OFFERS | imgBtnCloset click|  " +e.getMessage();
											Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
											}
									}
									});
								}				    							
								imgvBtnStar.setImageResource(R.drawable.btn_unsaved_heart);	
									if(!prodImage.getTag(R.string.videoId).equals("")){
										if(playerType.equals("audio")){
											player = new MediaPlayer();
											BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
											Bitmap bitmap = test.getBitmap();
											ByteArrayOutputStream baos = new ByteArrayOutputStream();
											bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
											ImageView imgvForPd = (ImageView)findViewById(R.id.imgvForPd);
											imgvForPd.setImageBitmap(bitmap);				
											try {
												videoUrl = prodImage.getTag(R.string.videoId).toString();
												player.setAudioStreamType(AudioManager.STREAM_MUSIC);
												player.setDataSource(videoUrl);
												player.prepare();
												player.start();		    		                					
											} catch (Exception e) {		    		                				    
												e.printStackTrace();
											}
										}
								}	
				}catch(Exception e){
				e.printStackTrace();
		 }		
	}
	public void fancyCoverFlowItem1(View arg1, int arg2){
		try{
			 if (player != null) {
				 player.release();
				 player = null;
			   }
			 
				if(discriminator.equalsIgnoreCase("VIDEO")){			    								 	
						playerSurfaceView.setVisibility(View.INVISIBLE);
						findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
						imgvForPd.setVisibility(View.VISIBLE);
					}
					if(offerId != null){
						if(!offerId.equals("null") && !offerId.equals("")) {
							arrBackProductList.add(offerId+"`"+"offer"+"`"+discriminator); 		
						}
					  }
								
					 if(productId != null){
						 if(!productId.equals("null") && !productId.equals("")) {
								arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
							}
					 	}
								
								
						ImageView prodImage = (ImageView)arg1.findViewById(R.id.imgForCarousel);				    		                		
						if(!prodImage.getTag(R.string.videoId).equals("")){
					           if(playerType.equals("video")){					    		                		
									videoUrl = prodImage.getTag(R.string.videoId).toString();
									playFinVideo(videoUrl);							
								if(offerId.equals("null") && productId.equals("null")){					  
										String screenName = "/video/"+clientId;
										String productIds = "";
										String offerIds = "";
										Common.sendJsonWithAQuery(FinancialActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);					  
									}
									
								if(offerId != null){
									if(!offerId.equals("null") && !offerId.equals("")) {
									financialBigImageType = false;
									String pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+offerId+"/financial/";
									relatedType = "offer";
									getRelatedResultsFromServerWithXml(pdUrl);
									//arrBackProductList.add(offerId+"`"+"offer"+"`"+discriminator);
									String screenName = "/video/offers/"+clientId+"/"+offerId;
									String productIds = "";
									String offerIds = offerId;
									Common.sendJsonWithAQuery(FinancialActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
									 }								
									}
									
								}
					           if(productId != null){
									 if(!productId.equals("null") && !productId.equals("")) {
									financialBigImageType = true;
									String pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+productId+"/financial/";
									Log.i("pdUrl",pdUrl);
									relatedType = "product";
									getRelatedResultsFromServerWithXml(pdUrl);
									// arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
									String screenName = "/video/product/"+clientId+"/"+productId;
									String productIds = productId;
									String offerIds = "";
									Common.sendJsonWithAQuery(FinancialActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
								}		    		          					  
							}
						}else {			    		                			
								if(prodImage.getDrawable()==null){
								} else{
								BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
								Bitmap bitmap = test.getBitmap();
								ByteArrayOutputStream baos = new ByteArrayOutputStream();
								bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
								ImageView imgvForPd = (ImageView)findViewById(R.id.imgvForPd);
								imgvForPd.setImageBitmap(bitmap);
								findViewById(R.id.rlForImgs).setVisibility(View.VISIBLE);
								imgvForPd.setVisibility(View.VISIBLE);
								findViewById(R.id.rlForVideos).setVisibility(View.INVISIBLE);
								}							
							}
								
								TextView txtvForValue = (TextView)findViewById(R.id.txtvForValue);
								String s = "[";
								String q = "]";
								String w = "";
								String strReplaceSymbol = String.valueOf(financialFinalStrArray[arg2]).replace(s, w).replace(q, w);	
								String[] expFinancialArray = strReplaceSymbol.split(",");				    							
								String finalImagePath = "", finalImageId = "", finalImageClientId = "", 
								finalImageBtnName = "", finalImageBtnUrl = "", finalImagePrice = "", finalImageDiscType = "";
								String expClientId = expFinancialArray[0].trim(); //0 is client ID		
								String expProductId = expFinancialArray[1].trim(); //1 is product id
								String expProductImage = expFinancialArray[2].trim(); //2 is product image
								String expProductShortDesc = expFinancialArray[3].trim(); //3 is product short description
								String expProductPrice = expFinancialArray[4].trim(); //4 is product price
								String expProductButtonName = expFinancialArray[5].trim(); //5 is product button name
								String expProductUrl = expFinancialArray[6].trim(); //6 is product url
								String expOfferid = expFinancialArray[7].trim(); //7 is offer id
								String expOfferName = expFinancialArray[8].trim(); //8 is offer name
								String expOfferImage = expFinancialArray[9].trim(); //9 is offer image
								String expOfferShortDesc = expFinancialArray[10].trim(); //10 is Offer ShortDesc
								String expOfferDiscountVal = expFinancialArray[11].trim(); //11 is OfferDiscountVal
								String expOfferButtonName = expFinancialArray[12].trim(); //12 is OfferButtonName
								String expOfferPurchaseUrl = expFinancialArray[13].trim(); //13 is OfferPurchaseUrl
								String expOfferDiscountType = expFinancialArray[14].trim(); //14 is OfferDiscountType
								String expOfferValidFrom = expFinancialArray[15].trim(); //15 is OfferValidFrom
								String expfinancialVideoPdImgs = expFinancialArray[16].trim(); //16 is financialVideoPdImgs
								String expClientBgColor = expFinancialArray[17].trim(); //17 is ClientBgColor
								String expClientLightColor = expFinancialArray[18].trim(); //18 is ClientLightColor
								String expClientDarkColor = expFinancialArray[19].trim(); //19 is ClientDarkColor
								String expProdName = expFinancialArray[21].trim(); //20 is prodName
								Log.e("relatedType",relatedType);
								//if(relatedType.equals("product")){								
									//if(expProductId.equals("")){
										//if(expProductId == null){
								
									if(expProductId.trim().equals("") || expProductId.equals("null") || expProductId.trim().length() <= 0) {
										Log.e("expProductId","expProductId"+expProductId);
												financialBigImageType = false;
												finalImagePath = expOfferImage;
												finalImageId = expOfferid;
												finalImageBtnName = expOfferButtonName;
												finalImageBtnUrl = expOfferPurchaseUrl;
												finalImagePrice = expOfferDiscountVal;
												finalImageDiscType = expOfferDiscountType;
												offerTitle = expOfferName;
												//relatedType = "product";
									} else {
										financialBigImageType = true;
										finalImagePath = expProductImage;
										finalImageId = expProductId;
										finalImageBtnName = expProductButtonName;
										finalImageBtnUrl = expProductUrl;
										finalImagePrice = expProductPrice;
										finalImageDiscType = "";
										offerTitle = expProdName;
										String curveImagesUrl = expProductImage;	
										relatedType = "offer";
									}
									finalImageClientId = expClientId;		
									//arrBackProductList.add(productId+"`"+"product"+"`"+discriminator);
									/*} else {								
										if(expProductId.equals("")){
											financialBigImageType = false;
											finalImagePath = expOfferImage;
											finalImageId = expOfferid;
											finalImageClientId = expClientId;
											finalImageBtnName = expOfferButtonName;
											finalImageBtnUrl = expOfferPurchaseUrl;
											finalImagePrice = expOfferDiscountVal;
											finalImageDiscType = expOfferDiscountType;
											offerTitle = expOfferName;
										} else {
											financialBigImageType = true;
											finalImagePath = expProductImage;
											finalImageId = expProductId;
											finalImageBtnName = expProductButtonName;
											finalImageBtnUrl = expProductUrl;
											finalImagePrice = expProductPrice;
											finalImageDiscType = "";
											offerTitle = expProdName;
										}								
									}*/
								Button imgvBtnFinancial = (Button) findViewById(R.id.imgvBtnFinancial);
								if(finalImageBtnName.equals("null")){				    											    								
									imgvBtnFinancial.setVisibility(View.INVISIBLE);
								}else{				    								
									imgvBtnFinancial.setVisibility(View.VISIBLE);
									imgvBtnFinancial.setText(finalImageBtnName);
								}
								Log.e("discriminator",discriminator);
								productId = finalImageId;
								buttonUrl = finalImageBtnUrl;
								String pdUrl ="";
									if(financialBigImageType==false &&( discriminator.equals("BUTTON")  || discriminator.equals("VIDEO") || discriminator.equals("AUDIO"))){
										financialBigImageType = false;
										pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+finalImageId+"/financial/";
										relatedType = "offer";
										offerId = finalImageId;		    										 
									if(!finalImagePrice.equals("null") || !finalImagePrice.equals("")){							
										if(finalImageDiscType.equals("R"))
											txtvForValue.setText(finalImagePrice +" Points");
										else if(finalImageDiscType.equals("P")){
											txtvForValue.setText(finalImagePrice+"% Off");
										}else{				    										 
										if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
											txtvForValue.setText("Free");
										} else if(finalImagePrice.equals("")){
											txtvForValue.setText("");
										} else {
											txtvForValue.setText(finalImagePrice);
										}
										}
									}else{
										txtvForValue.setText("");
									}
									
									checkOfferExistinChangeLog(offerId);
									new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
									imgBtnCloset.setOnClickListener(new OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												ArrayList<String> arrayListCloset = new ArrayList<String>();
												arrayListCloset.add(offerId);
												arrayListCloset.add("Financial");	
												new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
											}catch(Exception e){
												e.printStackTrace();
												String errorMsg = className+" | fancyCoverFlow |BUTTON | imgBtnCloset click |  " +e.getMessage();
												Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
												}
												}
										});
								}
								else if(discriminator.equals("BUTTON") || discriminator.equals("VIDEO") || discriminator.equals("AUDIO")||(discriminator.equals("OFFERS") && financialBigImageType==true) && financialBigImageType==true){
									financialBigImageType = true;
									pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+finalImageId+"/financial/";
									relatedType = "product";		    										
									if(!finalImagePrice.equals("null") || !finalImagePrice.equals("")){					    								 
										if(finalImageDiscType.equals("R"))
											txtvForValue.setText(finalImagePrice +" Points");
										else if(finalImageDiscType.equals("P")){
											txtvForValue.setText(finalImagePrice+"% Off");
									   }else{
										   Log.i("finalImagePrice", ""+finalImagePrice);
										   if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
											   txtvForValue.setText("Free");
										   } else if(finalImagePrice.equals("")){
											   txtvForValue.setText("");
										   }  else {
											   txtvForValue.setText(finalImagePrice);
										   }
									   }
								}else{
									txtvForValue.setText("");
								}					    							
								checkProductExistinChangeLog(productId); 								
								
								new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_heart_icon, R.id.imgvBtnCloset);
								imgBtnCloset.setOnClickListener(new OnClickListener() {
									@Override
									public void onClick(View v) {
									try{
										new Common().getLoginDialog(FinancialActivity.this, Closet.class, "Closet", new ArrayList<String>());
									}catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | fancyCoverFlow |BUTTON | imgBtnCloset click|  " +e.getMessage();
										Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
										}
									}
								});
								}else if(discriminator.equals("OFFERS")){
									financialBigImageType = false;
									pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+finalImageId+"/financial/";
									relatedType = "offer";
									offerId = finalImageId;		    										
								if(!finalImagePrice.equals("null") || !finalImagePrice.equals("")){					    								 
									if(finalImageDiscType.equals("R"))
										txtvForValue.setText(finalImagePrice +" Points");
									else if(finalImageDiscType.equals("P")){
										txtvForValue.setText(finalImagePrice+"% Off");
									}else{
										Log.i("finalImagePrice", ""+finalImagePrice);
										if(finalImagePrice.equals("0.00") || finalImagePrice.equals("0")){
											txtvForValue.setText("Free");
										} else if(finalImagePrice.equals("")){
											txtvForValue.setText("");
										}  else {
											txtvForValue.setText(finalImagePrice);
										}
									}
								}else{
									txtvForValue.setText("");
								}
								checkOfferExistinChangeLog(offerId);
								new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.btn_myoffer, R.id.imgvBtnCloset);
								imgBtnCloset.setOnClickListener(new OnClickListener() {
									@Override
									public void onClick(View v) {
										try{
											ArrayList<String> arrayListCloset = new ArrayList<String>();
											arrayListCloset.add(offerId);		
											arrayListCloset.add("Financial");
											new Common().getLoginDialog(FinancialActivity.this, MyOffers.class, "OfferViewMyOffers", arrayListCloset);
										}catch(Exception e){
											e.printStackTrace();
											String errorMsg = className+" | fancyCoverFlow |OFFERS | imgBtnCloset click|  " +e.getMessage();
											Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
											}
									}
									});
								}				    							
								imgvBtnStar.setImageResource(R.drawable.btn_unsaved_heart);	
									if(!prodImage.getTag(R.string.videoId).equals("")){
										if(playerType.equals("audio")){
											player = new MediaPlayer();
											BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
											Bitmap bitmap = test.getBitmap();
											ByteArrayOutputStream baos = new ByteArrayOutputStream();
											bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
											ImageView imgvForPd = (ImageView)findViewById(R.id.imgvForPd);
											imgvForPd.setImageBitmap(bitmap);				
											try {
												videoUrl = prodImage.getTag(R.string.videoId).toString();
												player.setAudioStreamType(AudioManager.STREAM_MUSIC);
												player.setDataSource(videoUrl);
												player.prepare();
												player.start();		    		                					
											} catch (Exception e) {		    		                				    
												e.printStackTrace();
											}
										}
								}	
				}catch(Exception e){
					e.printStackTrace();
				}		
		}
		
	 public void getOffersFromServer(String offerUrl){
		  try{			 
			  final AQuery aq1 = new AQuery(FinancialActivity.this);
			  aq1.ajax(offerUrl,  XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try { 
	        		    if(!xml.tags("products").equals(null)){
	        		    	List<XmlDom> entries = xml.tags("products");
	        		    	FileTransaction file = new FileTransaction();
	        		        Offers offers = file.getOffers();
	        		        if(offers.size() == 0){
	        		        	offers = new Offers();
	        		        }
	        		    	if(entries.size() > 0){	        		    		 
	       	    				for(XmlDom myOfferXml: entries){	 
	       	    					locationBased = myOfferXml.text("is_location_based").toString();
	       	    					String curveImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
	       	    				    new Common().DownloadImageFromUrl(FinancialActivity.this, curveImagesUrl, R.id.imgvForPd);
	       	    					Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
									if(bitmap == null){
										aq.cache(curveImagesUrl, 1440000);
									}
									 buttonName = myOfferXml.text("offer_button_name").toString();
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
											userOffer.setOfferRelatedId(myOfferXml.text("related_offerid").toString());
											userOffer.setProdRelatedId(myOfferXml.text("related_id").toString());
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
	       	    			  String pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+offerId+"/financial/"; 
							 getRelatedResultsFromServerWithXml(pdUrl);
	       	    			}
	        		    }	
	        		   }catch(Exception e){
	        			   e.printStackTrace();
	        			   String errorMsg = className+" | getOffersFromServer ajax call back    |   " +e.getMessage();
	        			   Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
	        			   
	        		   }
	        	}  		
	        });
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getOffersFromServer |   " +e.getMessage();
			  Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		  }
	  }
	public void checkProductExistinChangeLog(String prodId){
	  	try{ 	  		  
	  		 Boolean dataInDB    =  new Common().checkProductOfferExistindb("product", prodId);
	  		 Boolean updateProd  = false;
	  		 Boolean updateOffer = false;
	  		  Log.e("if checkProductExistinChangeLog",prodId);
	  		 if(dataInDB){
			  changeFlag = new Common().checkOfferExistinChangeLog("product", prodId);	
			  Log.e("if changeFlag",""+changeFlag);
			  if(changeFlag){				  
				  String productUrl = Constants.Client_Url+clientId+"/products/"+prodId+"/financial/";
				  getProductDetailsFromServer(productUrl);
			  }else{
				  if(!discriminator.equalsIgnoreCase("VIDEO")){
					  productModel = new ProductModel();
					  getProdDetail = file.getProduct();				
					  Log.e("if changeFlag",""+changeFlag);
			    	  UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(prodId));
			    	  if(chkProdExist != null){
						 Bitmap bm = aq.getCachedImage(chkProdExist.getImageFile());
						 if(bm != null)
							 imgvForPd.setImageBitmap(bm);
						 else{							 
							 if(Common.isNetworkAvailable(FinancialActivity.this)){
								    Log.i("if checkProductExistinChangeLog",chkProdExist.getImageFile());
								 	new Common().DownloadImageFromUrl(FinancialActivity.this, chkProdExist.getImageFile(), R.id.imgvForPd);
								 }else{
									Log.i("else checkProductExistinChangeLog",chkProdExist.getImageFile());
							 		Intent returnIntent = new Intent();
									returnIntent.putExtra("activity","menu");
									setResult(RESULT_OK,returnIntent);
									finish();	
								 }											
						 	}
						 Log.e("price",""+chkProdExist.getProductPrice());
						 if(!chkProdExist.getProductPrice().equals("null")){						
							 if(chkProdExist.getProductPrice().equals("0.00") || chkProdExist.getProductPrice().equals("0") ){
								 if(chkProdExist.getProductPrice().equals("$0.00") )
									 txtvForValue.setText("Free");
							 } else if(chkProdExist.getProductPrice().equals("") || chkProdExist.getProductPrice().equals("$") ){
								 txtvForValue.setText("");
							 } else {
								 txtvForValue.setText(chkProdExist.getProductPrice());
							 }						 
						}else{
							txtvForValue.setText("");
						}
						 Log.e("istryon",""+chkProdExist.getProdIsTryOn());
						 if(chkProdExist.getProdIsTryOn()== 1){
							 Button btnSeeItLive = (Button) findViewById(R.id.btnSeeItLive);
							 btnSeeItLive.setVisibility(View.VISIBLE);
							 new Common().btnForSeeItLiveWithAllColors(FinancialActivity.this, btnSeeItLive, "relative", "width", "financial", 
									 ""+chkProdExist.getProductId(), ""+chkProdExist.getClientId(),chkProdExist.getProdIsTryOn() ,
									 chkProdExist.getClientBackgroundColor(), chkProdExist.getClientLightColor(), 
									 chkProdExist.getClientDarkColor());
		    					}else{
		    						 Button btnSeeItLive = (Button) findViewById(R.id.btnSeeItLive);
		    						 btnSeeItLive.setVisibility(View.INVISIBLE);
		    						 
		    					}
						 
						//checking related product exist in db
						 if(chkProdExist.getProductRelatedId() != null && !chkProdExist.getProductRelatedId().equals("")){								 
							 String[] relatedProdIds   = chkProdExist.getProductRelatedId().split(",");							
							 if(relatedProdIds.length > 0){
								 for(int i=0;i<relatedProdIds.length;i++){
									 if(!relatedProdIds[i].equals("")){
										 UserProduct chkProd = getProdDetail.getUserProductById(Integer.parseInt(relatedProdIds[i]));
										 if(chkProd == null){
											 updateProd = true;
										 }
									 }
								 }							 
								
							 }							 							
							 
						 }
						 
						//checking related offer exist in db
						 if(chkProdExist.getOfferRelatedId() != null  && !chkProdExist.getOfferRelatedId().equals("")){
							 String[] relatedOfferIds  = chkProdExist.getOfferRelatedId().split(",");							
							 if(relatedOfferIds.length > 0){
								 offers = file.getOffers();
								 for(int j=0;j<relatedOfferIds.length;j++){									 	
									 if(!relatedOfferIds[j].equals("")){
										 UserOffers offer= offers.getUserOffers(Integer.parseInt(relatedOfferIds[j]));
										 if(offer == null){
											 updateOffer = true;
										 }
									 }
								 }
								 
							 }								 
						 }
						 
						 if(updateProd || updateOffer){
							 String pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+prodId+"/financial/";
	      					 getRelatedResultsFromServerWithXml(pdUrl);
						 }else{						 
							 getRelatedResultsFromFile();
						 }
					}
				  }else{
					  if(Common.isNetworkAvailable(FinancialActivity.this)){
					  String productUrl = Constants.Client_Url+clientId+"/products/"+productId+"/financial/";
					  getProductDetailsFromServer(productUrl);
					  
					  String pdUrl = Constants.Live_Android_Url+"client_product/"+clientId+"/related_products/"+prodId+"/financial/";				
   					  getRelatedResultsFromServerWithXml(pdUrl);
					  }else{
						  getMainProductFromFile();
						  getRelatedResultsFromFile();
					  }
				 }
				  
				  
			  }
			}else{
				  String productUrl = Constants.Client_Url+clientId+"/products/"+productId+"/financial/";
				  getProductDetailsFromServer(productUrl);
			 }
	  		 
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | changeLogResultFromServer |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
			}    	
	}
	
	
	public void checkOfferExistinChangeLog(String offerId){
	  	try{ 	  		  
	  		if(!offerId.equals("")){
	  		 Boolean dataInDB =  new Common().checkProductOfferExistindb("offer", offerId);
	  		 Boolean updateProd = false;
	  		 Boolean updateOffer = false;
			  if(dataInDB){
				  changeFlag = new Common().checkOfferExistinChangeLog("offer", offerId);	
				  if(changeFlag){
					  String offerUrl= Constants.Live_Android_Url +"offers/"+clientId+"/"+offerId;					
					  getOffersFromServer(offerUrl);
				  }else{
					  if(!discriminator.equalsIgnoreCase("VIDEO")){
					     offers = file.getOffers();
					     relatedType = "offer";										     
						 UserOffers offerExist = offers.getUserOffers(Integer.parseInt(offerId));						
						 if(offerExist != null){							
							 Bitmap bm = aq.getCachedImage(offerExist.getOfferImage());							
							 if(bm != null)
								 imgvForPd.setImageBitmap(bm);
							 else{
								 if(Common.isNetworkAvailable(FinancialActivity.this)){
								   new Common().DownloadImageFromUrl(FinancialActivity.this, offerExist.getOfferImage(), R.id.imgvForPd);
								 }else{
							 		Intent returnIntent = new Intent();
									returnIntent.putExtra("activity","menu");
									setResult(RESULT_OK,returnIntent);
									finish();	
								 }
							 	}
							 if(!offerExist.getOfferDiscountValue().equals("null") || !offerExist.getOfferDiscountValue().equals("")){							
									if(offerExist.getOfferDiscountType().equals("R"))
										txtvForValue.setText(offerExist.getOfferDiscountValue() +" Points");
									else if(offerExist.getOfferDiscountValue().equals("P")){
										txtvForValue.setText(offerExist.getOfferDiscountValue()+"% Off");
									}else{				    										 
									if(offerExist.getOfferDiscountValue().equals("0.00") || offerExist.getOfferDiscountValue().equals("0") || offerExist.getOfferDiscountValue().equals("$0.00") ){
										txtvForValue.setText("Free");
									} else if(offerExist.getOfferDiscountValue().equals("")){
										txtvForValue.setText("");
									} else {
										txtvForValue.setText(offerExist.getCurrencySymbol()+offerExist.getOfferDiscountValue());
									}
									}
								}else{
									txtvForValue.setText("");
								}
								
							 
							//checking related product exist in db
							 if(offerExist.getProdRelatedId() != null && !offerExist.getProdRelatedId().equals("")){								 
								 String[] relatedProdIds   = offerExist.getProdRelatedId().split(",");										 
								 if(relatedProdIds.length > 0){
									 getProdDetail = file.getProduct();
									 for(int i=0;i<relatedProdIds.length;i++){										
										 if(!relatedProdIds[i].equals("")){
											 UserProduct chkProd = getProdDetail.getUserProductById(Integer.parseInt(relatedProdIds[i]));
											 if(chkProd == null){
												updateProd = true;
									        }
										 }
									 }
								 }									 
							 }
							 
							//checking related offer exist in db
							 if(offerExist.getOfferRelatedId() != null && !offerExist.getOfferRelatedId().equals("") ){								
								 String[] relatedOfferIds  = offerExist.getOfferRelatedId().split(",");
								 if(relatedOfferIds.length > 0){
									 for(int j=0;j<relatedOfferIds.length;j++){
										 if(!relatedOfferIds[j].equals("")){
											 UserOffers offer= offers.getUserOffers(Integer.parseInt(relatedOfferIds[j]));
											 if(offer == null){
												 updateOffer = true;
											 }
										 }
									 }
									
								 }								 
							 }
							
							 if(updateProd || updateOffer){
								 String pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+offerId+"/financial/"; 
								 getRelatedResultsFromServerWithXml(pdUrl);
							 }else{
								 getRelatedResultsFromFile();
							 }
							 
						}
					  }else{
						  String pdUrl = Constants.Live_Android_Url+"my_offers/related_offers/"+clientId+"/"+offerId+"/financial/"; 
							 getRelatedResultsFromServerWithXml(pdUrl);
					  }
				  }
				}else{
					  String offerUrl= Constants.Live_Android_Url +"offers/"+clientId+"/"+offerId;					
					  getOffersFromServer(offerUrl);
				 }
	  		}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | checkOfferExistinChangeLog |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
			}    	
	}
	int stopPosition = -1; 
	public void playFinVideo(String videoUrl){
		try{			  
			  
		      findViewById(R.id.rlForVideos).setVisibility(View.VISIBLE);
		      findViewById(R.id.rlForImgs).setVisibility(View.INVISIBLE);
		      imgvForPd.setVisibility(View.INVISIBLE);
		      new Common().DownloadImageFromUrl(FinancialActivity.this, getProductImageUrl, R.id.imgvForPd);
		      playerSurfaceView.setVisibility(View.VISIBLE);
		    
		      RelativeLayout rlForVideo = (RelativeLayout) findViewById(R.id.rlForVideos);
		      RelativeLayout.LayoutParams rlpVideoParams = (RelativeLayout.LayoutParams) rlForVideo.getLayoutParams();
		      rlpVideoParams.height			 = (int)(0.365 * Common.sessionDeviceHeight);
		      rlpVideoParams.bottomMargin	 = (int)(0.0082  * Common.sessionDeviceHeight);
		      rlpVideoParams.leftMargin		 = (int)(0.008334 * Common.sessionDeviceWidth);
		      rlpVideoParams.rightMargin 	 = (int)(0.008334 * Common.sessionDeviceWidth);
		      rlForVideo.setLayoutParams(rlpVideoParams);
		      
		      
		      RelativeLayout.LayoutParams rlSurfaceView = (RelativeLayout.LayoutParams) playerSurfaceView.getLayoutParams();
			  rlSurfaceView.width     = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			  rlSurfaceView.height    = (int) (0.3074 * Common.sessionDeviceHeight);
			  playerSurfaceView.setLayoutParams(rlSurfaceView);
			
			  
			   ImageView imgvPlayPause = (ImageView) findViewById(R.id.imgvPlayPause);
			   RelativeLayout.LayoutParams rlImgvPlayPause = (RelativeLayout.LayoutParams) imgvPlayPause.getLayoutParams();
			   rlImgvPlayPause.width      = (int)(0.05834 * Common.sessionDeviceWidth);
			   rlImgvPlayPause.height     = (int)(0.03587 * Common.sessionDeviceHeight);
			   rlImgvPlayPause.leftMargin = (int)(0.11834 * Common.sessionDeviceWidth);
			   imgvPlayPause.setLayoutParams(rlImgvPlayPause);				
			   imgvPlayPause.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					if(stopPosition !=0){
						if(playerSurfaceView.isPlaying()){
							playerSurfaceView.pause();
						    stopPosition = playerSurfaceView.getCurrentPosition();
						    new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.play_player_button, R.id.imgvPlayPause);
						}else{
							new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.pause_player_button, R.id.imgvPlayPause);
							//playerSurfaceView.resume();
							playerSurfaceView.seekTo(stopPosition);
							playerSurfaceView.start(); 
						}			
					}else{
						playerSurfaceView.resume(); 
						new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.pause_player_button, R.id.imgvPlayPause);
					}
				}
			  });
			  			  
			   ImageView imgvStop = (ImageView) findViewById(R.id.imgvStop);
			   RelativeLayout.LayoutParams rlImgvStop = (RelativeLayout.LayoutParams) imgvStop.getLayoutParams();
			   rlImgvStop.width        = (int)(0.05834 * Common.sessionDeviceWidth);
			   rlImgvStop.height       = (int)(0.03587 * Common.sessionDeviceHeight);
			   rlImgvStop.rightMargin  = (int)(0.11834 * Common.sessionDeviceWidth);
			   imgvStop.setLayoutParams(rlImgvStop);
			   imgvStop.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					playerSurfaceView.stopPlayback();
					stopPosition = 0;
					new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.play_player_button, R.id.imgvPlayPause);
				}
			  });
			  
			  playerSurfaceView.setVideoPath(videoUrl); 
			  playerSurfaceView.start();
			  playerSurfaceView.requestFocus();
			  playerSurfaceView.setOnPreparedListener(new OnPreparedListener() {
			        @Override
					public void onPrepared(MediaPlayer arg0) {
					    findViewById(R.id.rlForVideos).setVisibility(View.VISIBLE);
			        	playerSurfaceView.bringToFront();
			        	playerSurfaceView.requestFocus();
			        	playerSurfaceView.start(); 	
			        	stopPosition =1;
			           }
			    });
			  
			  playerSurfaceView.setOnCompletionListener(new OnCompletionListener() {					
					@Override
					public void onCompletion(MediaPlayer mp) {						
						stopPosition = -1;
						new Common().showDrawableImageFromAquery(FinancialActivity.this, R.drawable.play_player_button, R.id.imgvPlayPause);
					}
				});     
			  
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | playFinVideo |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		}
	}
	int gridItemLayout = 0;
	private ArrayAdapter<String> renderForFiancialCarousel(
			final String[] financialFinalStrArray2) {
		gridItemLayout = R.layout.financial_carousel;		
		ArrayAdapter<String> aa = new ArrayAdapter<String>(FinancialActivity.this, gridItemLayout, financialFinalStrArray2){				
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, gridItemLayout, parent);
					}
					AQuery aq2 = new AQuery(convertView);	
					
					if(financialFinalStrArray2!=null || financialFinalStrArray2[position]!=null || !financialFinalStrArray2[position].equals("null")){
						
						
						String s = "[";
						String q = "]";
						String w = "";
						String strReplaceSymbol = String.valueOf(financialFinalStrArray2[position]).replace(s, w).replace(q, w);
						String[] expFinancialArray = strReplaceSymbol.split(",");
						
						/*for(int a=0; a<expFinancialArray.length; a++){
							//Log.e("expFinancialArray"+a, ""+expFinancialArray[a]);
						}*/
						if(expFinancialArray[1] != null){
						String finalImagePath = "", finalImageId = "", finalImageClientId = "", finalImageBtnName = "", 
								finalImageTitle = "";
						String expClientId = expFinancialArray[0].trim(); //0 is client ID		
						String expProductId = expFinancialArray[1].trim(); //1 is product id
						String expProductImage = expFinancialArray[2].trim(); //2 is product image
						String expProductShortDesc = expFinancialArray[3].trim(); //3 is product short description
						String expProductPrice = expFinancialArray[4].trim(); //4 is product price
						String expProductButtonName = expFinancialArray[5].trim(); //5 is product button name
						String expProductUrl = expFinancialArray[6].trim(); //6 is product url
						String expOfferid = expFinancialArray[7].trim(); //7 is offer id
						String expOfferName = expFinancialArray[8].trim(); //8 is offer name
						String expOfferImage = expFinancialArray[9].trim(); //9 is offer image
						String expOfferShortDesc = expFinancialArray[10].trim(); //10 is offer image
						String expOfferDiscountVal = expFinancialArray[11].trim(); //11 is offer image
						String expOfferButtonName = expFinancialArray[12].trim(); //12 is offer image
						String expOfferPurchaseUrl = expFinancialArray[13].trim(); //13 is offer image
						String expOfferDiscountType = expFinancialArray[14].trim(); //14 is offer image
						String expOfferValidFrom = expFinancialArray[15].trim(); //15 is offer image
						String expVideoUrl = expFinancialArray[16].trim(); //16 is video url
						String expTapForDetailsImgs = expFinancialArray[20].trim(); //16 is video url
						String expProductName       = expFinancialArray[21].trim(); //16 is video url
						String expProductIsTryOn    = expFinancialArray[22].trim(); 
						
						if(expTapForDetailsImgs.equals("audio")){
							playerType ="audio";
						}
						Common.sessionClientBgColor = expFinancialArray[17].trim();
						Common.sessionClientBackgroundLightColor = expFinancialArray[18].trim();
						Common.sessionClientBackgroundDarkColor = expFinancialArray[19].trim();
						
						new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
								FinancialActivity.this, Common.sessionClientBgColor,
								Common.sessionClientBackgroundLightColor,
								Common.sessionClientBackgroundDarkColor,
								Common.sessionClientLogo, "", "");
						
						
			    		if(relatedType.equals("product")){									
							if(expProductId.equals("") || expProductId.equals("null")){
								finalImagePath = expOfferImage;
								finalImageId = expOfferid;
								finalImageBtnName = expOfferButtonName;
								financialBigImageType = false;
								finalImageTitle = expOfferName;
							} else {
								financialBigImageType = true;
								finalImagePath = expProductImage;
								finalImageId = expProductId;
								finalImageBtnName = expProductButtonName;
								finalImageTitle = expProductName;
							}
							finalImageClientId = expClientId;
			    		} else {
							if(expProductId.equals("") || expProductId.equals("null")){
								finalImagePath = expOfferImage;
								finalImageId = expOfferid;
								finalImageBtnName = expOfferButtonName;
								financialBigImageType = false;
								finalImageTitle = expOfferName;
							} else {
								financialBigImageType = true;
								finalImagePath = expProductImage;
								finalImageId = expProductId;
								finalImageBtnName = expProductButtonName;
								finalImageTitle = expProductName;
							}
			    		}
						Bitmap placeholder = aq2.getCachedImage(finalImagePath);
						if(placeholder==null){
							aq2.cache(finalImagePath, 14400000);					
						}
						
						RelativeLayout rlForCarouselRelativeLayout = (RelativeLayout) convertView.findViewById(R.id.rlForCarouselRelativeLayout);
						RelativeLayout.LayoutParams rlpForRl = (RelativeLayout.LayoutParams) rlForCarouselRelativeLayout.getLayoutParams();
						rlpForRl.width = (int) (0.667 * Common.sessionDeviceWidth);
						rlpForRl.height = (int) (0.359 * Common.sessionDeviceHeight);
						rlForCarouselRelativeLayout.setLayoutParams(rlpForRl);
						int setPaddHorizontal = (int) (0.0167 * Common.sessionDeviceWidth);
						int setPaddVertical = (int) (0.0103 * Common.sessionDeviceHeight);
						rlForCarouselRelativeLayout.setPadding(setPaddHorizontal, setPaddVertical, setPaddHorizontal, setPaddVertical);
						
						ImageView img =(ImageView) convertView.findViewById(R.id.imgForCarousel);						
						 if(Common.isNetworkAvailable(FinancialActivity.this)){
							 if(placeholder != null)
								 aq2.id(R.id.imgForCarousel).image(finalImagePath, true, true, 0, 0, placeholder, 0, 0);
							 else{				
								 img.setImageBitmap(placeholder);							 
							 }
						 }else{
							 Bitmap bm = aq2.getCachedImage(finalImagePath);								 
							 if(bm != null){
								 img.setImageBitmap(bm);
							 }else{
								 rlForCarouselRelativeLayout.setVisibility(View.INVISIBLE);
							 }
						 }
						img.setTag(R.string.productId, expProductId);	
						img.setTag(R.string.offerId, expOfferid);	
						img.setTag(R.string.clientId, finalImageClientId);
						img.setTag(R.string.videoId, expVideoUrl);					

						RelativeLayout.LayoutParams rlForCoverFlowImg = (RelativeLayout.LayoutParams) img.getLayoutParams();
						rlForCoverFlowImg.width = (int) (0.6334 * Common.sessionDeviceWidth);
						rlForCoverFlowImg.height = (int) (0.3382 * Common.sessionDeviceHeight);
						img.setLayoutParams(rlForCoverFlowImg);	
						
						LinearLayout llTextLayout = (LinearLayout) convertView.findViewById(R.id.llOverlayText);
						RelativeLayout.LayoutParams rlForOverlayText = (RelativeLayout.LayoutParams) llTextLayout.getLayoutParams();
						rlForOverlayText.width = (int) (0.6334 * Common.sessionDeviceWidth);
						rlForOverlayText.height = (int) (0.0615 * Common.sessionDeviceHeight);
						llTextLayout.setLayoutParams(rlForOverlayText);	
						
						TextView txtImgName = (TextView) convertView.findViewById(R.id.txtImageTitle);
						LinearLayout.LayoutParams llForText = (LinearLayout.LayoutParams) txtImgName.getLayoutParams();
						llForText.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
						llForText.leftMargin = (int) (0.01667 * Common.sessionDeviceWidth);
						txtImgName.setLayoutParams(llForText);
						txtImgName.setTextSize( (int) ((0.025 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
						txtImgName.setText(finalImageTitle);
						if(expProductIsTryOn.equals("1")){
							Button btnSeeItLive = (Button) convertView.findViewById(R.id.btnSeeItLive);
							new Common().btnForSeeItLiveWithAllColors(FinancialActivity.this, btnSeeItLive, "relative", "width", "financial", 
									expProductId, expClientId, Integer.parseInt(expProductIsTryOn), expFinancialArray[17].trim(), expFinancialArray[18].trim(), 
									expFinancialArray[19].trim());
						}
					}
					}
					//ARDisplayActivity.endnow = android.os.SystemClock.uptimeMillis();
					//Log.i("MYTAG financial ", "Excution time: "+(ARDisplayActivity.endnow-ARDisplayActivity.startnow)/1000+" s");
					
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForFiancialCarousel  " +e.getMessage();
				    Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
				}
				return convertView;					
			}			
		};	
	return aa;
	}
	
	 @Override
		public void onStart() {
		 try{
			    super.onStart();
			    Tracker easyTracker = EasyTracker.getInstance(this);
				easyTracker.set(Fields.SCREEN_NAME, "/financial");
				easyTracker.send(MapBuilder
				    .createAppView()
				    .build()
				);
				 String[] segments = new String[1];
				 segments[0] = "Financial Layout"; 
				 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
			 }catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStart  |   " +e.getMessage();
		       	 Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
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
			 String errorMsg = className+" | onStop  |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		 }
		}	
	
	@Override
	protected void onResume() {
		try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(FinancialActivity.this);			
				Common.isAppBackgrnd = false;
			}
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onResume |  " +e.getMessage();
			Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		}
	    
	}
	
	@Override
	protected void onPause() {
		try{
		// animateIn this activity
		super.onPause();
		  if (player != null) {
	        	player.release();
	        	player = null;
	        }
		    Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(FinancialActivity.this);
			if(appInBackgrnd){
				 Common.isAppBackgrnd = true;
			}	
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onPause |  " +e.getMessage();
			Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		}
	    
	}
	@Override
	protected void onDestroy() {
		try{
        super.onDestroy();
        if (player != null) {
        	player.release();
        	player = null;
        }
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onDestroy |  " +e.getMessage();
			Common.sendCrashWithAQuery(FinancialActivity.this,errorMsg);
		}
    }	
}
