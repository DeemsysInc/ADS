package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;

import org.json.JSONArray;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.ClosetModel;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserCloset;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
public class Closet extends Activity {
	
	public boolean isBackPressed = false;
	final Context context = this;

	String getProductId = "null", getProductName = "null", getProductPrice = "null", getClientLogo = "null", getClientId = "null", 
			getClientBackgroundImage = "null", getFinalWebSiteUrl = "null", getClientBackgroundColor = "null", 
			getClientImageName = "null", getClientUrl = "null", getProductUrl = "null", getProductShortDesc = "null", 
			getProductDesc = "null";
	String arrProdID=""; 
	String flag="0";
	public List<Bitmap> imagesList;
	public ArrayList<String> imagesUrlArrList;
	public ArrayList<String> imagesClientIdArrList, imagesBgColorCodeArrListWithPdId;
	public ArrayList<String> imagesProdIdArrList, imagesBgColorCodeArrList, imagesClientLogoArrList;
	public ArrayList<String> imagesProdNameArrList, imagesClosetSelArrList, imagesProdIsTryOnArrList;
	public JSONArray userJsonArray = null;
	SessionManager session;
	SessionManager sessionOwnIt;
	SessionManager sessionWantIt;
	AQuery aq;
	HashSet<String> arrproductIds;
	FileTransaction file; 
	ImageView imageFlag;
	TextView txtvProdNotAvail;
	FancyCoverFlow fancyCoverFlow;
	Common commonLib;
	String className = this.getClass().getSimpleName();
	long startnow,endnow;
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_your_closet);
        aq = new AQuery(this);
		try{     
			file = new FileTransaction();
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					Closet.this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Favorites", "");

			Common.sessionClientName = "My Closet";
			commonLib = new Common();
						
			txtvProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
			txtvProdNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
			
	        fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlow1);
	        RelativeLayout.LayoutParams rlpForFancyCover = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();
	        rlpForFancyCover.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
	        rlpForFancyCover.height = android.view.ViewGroup.LayoutParams.WRAP_CONTENT;
	        fancyCoverFlow.setLayoutParams(rlpForFancyCover);
						
			new Common().showDrawableImageFromAquery(this, R.drawable.btn_trash, R.id.imgvBtnCloset);		
			if(Common.isNetworkAvailable(this))
			{		
	        	// Session class instance
		        session = new SessionManager(context);
		        sessionOwnIt = new SessionManager(context);
		        sessionWantIt = new SessionManager(context);
				Log.i("loggedin", ""+Common.sessionIdForUserLoggedIn);
				if(session.isLoggedIn() && Common.sessionIdForUserLoggedIn!=0)
		        {
					Log.i("loggedin", ""+Common.sessionIdForUserLoggedIn);
			        String closetUrl = Constants.Closet_Url+"xml/"+Common.sessionIdForUserLoggedIn;
			        startnow = android.os.SystemClock.uptimeMillis();
					 Log.e("MYTAG startnow ", "start time: "+0+" s");
	    			getYourClosetResultsFromDBTable(closetUrl, 0);
	    		}
			}else{
				 // get user data from session			
    		//	getYourClosetResultsFromFile("0");
				new LoadCloset().execute();
			}
						
			ImageView imgvHelp =  (ImageView) findViewById(R.id.imgvHelp); 
			RelativeLayout.LayoutParams rlpHelp = (RelativeLayout.LayoutParams) imgvHelp.getLayoutParams();
			rlpHelp.width = (int) (0.09 * Common.sessionDeviceWidth);
			rlpHelp.height = (int) (0.09 * Common.sessionDeviceHeight);
			imgvHelp.setLayoutParams(rlpHelp);
			imgvHelp.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{									
					Intent intent = new Intent(getApplicationContext(),HelpActivity.class);
					intent.putExtra("screen_name", "closet");
					startActivity(intent);		
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvHelp   click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
				}
			});			
			
			ImageView ownit =  (ImageView) findViewById(R.id.ownit); 
			RelativeLayout.LayoutParams rlpForOwnIt = (RelativeLayout.LayoutParams) ownit.getLayoutParams();
			rlpForOwnIt.width = (int) (0.23 * Common.sessionDeviceWidth);
			rlpForOwnIt.height = (int) (0.06 * Common.sessionDeviceHeight);
			ownit.setLayoutParams(rlpForOwnIt);
			ownit.setOnClickListener(new View.OnClickListener() {
	            @Override
	            public void onClick(View view) {
	            	try{
	            		if(Common.isNetworkAvailable(Closet.this))
	            		{
	            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_ownit_ind_enable, R.id.ownit);
	            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_wantit_ind_disable, R.id.wantit);
	            		if(fancyCoverFlow.getChildCount() > 0){
		            		LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
		            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);
		            		ImageView prodImage = (ImageView) ll2.getChildAt(0);
		            		ImageView flag = (ImageView)ll2.getChildAt(2);
		            		flag.setImageResource(R.drawable.icon_ownit);
		            		insertUpdateDeleteProductsToClosetDbUsingXml(Constants.Closet_Url+"update/"+Common.sessionIdForUserLoggedIn+"/"+prodImage.getTag()+"/2/", "update");
	            			}
	            		}else{
	            			new Common().instructionBox(Closet.this,R.string.title_case7,R.string.instruction_case7);
	            		}
	            	} catch (Exception ex) {
	            		ex.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: Closet ownit button onClick.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | ownit   click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
	            }
		    });
			ImageView wantit =  (ImageView) findViewById(R.id.wantit); 
			RelativeLayout.LayoutParams rlpForWantIt = (RelativeLayout.LayoutParams) wantit.getLayoutParams();
			rlpForWantIt.width = (int) (0.23 * Common.sessionDeviceWidth);
			rlpForWantIt.height = (int) (0.06 * Common.sessionDeviceHeight);
			rlpForWantIt.bottomMargin = (int) (0.031 * Common.sessionDeviceHeight);
			wantit.setLayoutParams(rlpForWantIt);
			wantit.setOnClickListener(new View.OnClickListener() {
	            @Override
	            public void onClick(View view) {
	            	try{
	            		if(Common.isNetworkAvailable(Closet.this))
	            		{
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_ownit_ind_disable, R.id.ownit);
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_wantit_ind_enable, R.id.wantit);
		            		if(fancyCoverFlow.getChildCount() > 0){
			            		LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
			            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);
			            		ImageView prodImage = (ImageView) ll2.getChildAt(0);
			            		ImageView flag = (ImageView)ll2.getChildAt(2);
			            		flag.setImageResource(R.drawable.icon_wantit);
			            		insertUpdateDeleteProductsToClosetDbUsingXml(Constants.Closet_Url+"update/"+Common.sessionIdForUserLoggedIn+"/"+prodImage.getTag()+"/1/", "update");
		            	
		            		}
	            		}else{
	            			new Common().instructionBox(Closet.this,R.string.title_case7,R.string.instruction_case7);
	            		}
	            	} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Closet wantit button onClick.", Toast.LENGTH_LONG).show();
	            		ex.printStackTrace();
	            		String errorMsg = className+" | wantit   click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
	            }
		    });
			
			ImageView imvBtnOwnIt =  (ImageView) findViewById(R.id.imvBtnOwnIt); 
			RelativeLayout.LayoutParams rlpForTabImgOwnIt = (RelativeLayout.LayoutParams) imvBtnOwnIt.getLayoutParams();
			rlpForTabImgOwnIt.width = (int) (0.175 * Common.sessionDeviceWidth);
			rlpForTabImgOwnIt.height = (int) (0.065 * Common.sessionDeviceHeight);
			imvBtnOwnIt.setLayoutParams(rlpForTabImgOwnIt);
			imvBtnOwnIt.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
						
							new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_ownit_enable, R.id.imvBtnOwnIt);
							new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_wantit_disable, R.id.imvBtnWantIt);
							new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_all_disable, R.id.imgvBtnAll);
							if(Common.isNetworkAvailable(Closet.this))
		            		{
								closetFinalArray = new String[0];
								fancyCoverFlow.setAdapter(renderForCoverFlow(closetFinalArray));
								txtvProdNotAvail.setVisibility(View.INVISIBLE);
								String closetUrl = Constants.Closet_Url+"xml/"+Common.sessionIdForUserLoggedIn;
								getYourClosetResultsFromDBTable(closetUrl, 2);
		            		}else{
		            			flag = "2";
		            			//getYourClosetResultsFromFile("2");
		            			new LoadCloset().execute();
		            		}
	            	} catch (Exception ex) {
	            		ex.printStackTrace();
						//Toast.makeText(getApplicationContext(), "Error: Closet ownit button onClick.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imvBtnOwnIt   click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
	            }
		    });
			ImageView imvBtnWantIt =  (ImageView) findViewById(R.id.imvBtnWantIt); 
			RelativeLayout.LayoutParams rlpForTabImgWantIt = (RelativeLayout.LayoutParams) imvBtnWantIt.getLayoutParams();
			rlpForTabImgWantIt.width = (int) (0.175 * Common.sessionDeviceWidth);
			rlpForTabImgWantIt.height = (int) (0.065 * Common.sessionDeviceHeight);
			rlpForTabImgWantIt.leftMargin = (int) (0.01 * Common.sessionDeviceHeight);
			imvBtnWantIt.setLayoutParams(rlpForTabImgWantIt);
			imvBtnWantIt.setOnClickListener(new View.OnClickListener() {
		            @Override
		            public void onClick(View view) {
		            	try{
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_ownit_disable, R.id.imvBtnOwnIt);
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_wantit_enable, R.id.imvBtnWantIt);
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_all_disable, R.id.imgvBtnAll);
		            			
		            		if(Common.isNetworkAvailable(Closet.this))
		            		{
		            				closetFinalArray = new String[0];
					      		    fancyCoverFlow.setAdapter(renderForCoverFlow(closetFinalArray));
					      		    txtvProdNotAvail.setVisibility(View.INVISIBLE);	
									String closetUrl = Constants.Closet_Url+"xml/"+Common.sessionIdForUserLoggedIn;
									getYourClosetResultsFromDBTable(closetUrl, 1);
								
		            		}else{
		            			//getYourClosetResultsFromFile("1");
		            			flag = "1";		            			
		            			new LoadCloset().execute();
		            		}
		            	} catch (Exception ex) {
		            		 ex.printStackTrace();
							//Toast.makeText(getApplicationContext(), "Error: Closet wantit button onClick.", Toast.LENGTH_LONG).show();
							String errorMsg = className+" | imvBtnWantIt   click  |   " +ex.getMessage();
				       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
						}
		            }
		    });
			ImageView imgvBtnAll =  (ImageView) findViewById(R.id.imgvBtnAll); 
			RelativeLayout.LayoutParams rlpForTabImgAll = (RelativeLayout.LayoutParams) imgvBtnAll.getLayoutParams();
			rlpForTabImgAll.width = (int) (0.175 * Common.sessionDeviceWidth);
			rlpForTabImgAll.height = (int) (0.065 * Common.sessionDeviceHeight);
			rlpForTabImgAll.rightMargin = (int) (0.01 * Common.sessionDeviceHeight);
			imgvBtnAll.setLayoutParams(rlpForTabImgAll);
			imgvBtnAll.setOnClickListener(new View.OnClickListener() {
		            @Override
		            public void onClick(View view) {
		            	try{
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_ownit_disable, R.id.imvBtnOwnIt);
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_wantit_disable, R.id.imvBtnWantIt);
		            		new Common().showDrawableImageFromAquery(Closet.this, R.drawable.tab_all_enable, R.id.imgvBtnAll);
		            		
		            		if(Common.isNetworkAvailable(Closet.this))
		            		{
								    fancyCoverFlow.setAdapter(renderForCoverFlow(closetFinalArray));
					      		    txtvProdNotAvail.setVisibility(View.INVISIBLE);
									String closetUrl = Constants.Closet_Url+"xml/"+Common.sessionIdForUserLoggedIn;
									getYourClosetResultsFromDBTable(closetUrl, 0);
								
							}else{
								flag = "0";		            			
		            			new LoadCloset().execute();
		            		}
		            	} catch (Exception ex) {
							Toast.makeText(getApplicationContext(), "Error: Closet imgvBtnAll button onClick.", Toast.LENGTH_LONG).show();
							String errorMsg = className+" | imgvBtnAll  click  |   " +ex.getMessage();
				       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
						}
		            }
		    });
		    

			ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle); 
		    imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
		            @Override
		            public void onClick(View view) {
		            	try{
							Intent intent = new Intent(getApplicationContext(), MenuOptions.class);
							int requestCode = 1;
							startActivityForResult(intent, requestCode);
							overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
		            	} catch (Exception ex) {
							Toast.makeText(getApplicationContext(), "Error: Closet imgFooterMiddle onClick.", Toast.LENGTH_LONG).show();
							String errorMsg = className+" | imgFooterMiddle  click  |   " +ex.getMessage();
				       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
						}
		            }
		    });
		    
			new Common().clickingOnBackButtonWithAnimation(Closet.this, ProductList.class,"1");
				        
			ImageView imgvWishList = (ImageView) findViewById(R.id.imgvWishList);
			RelativeLayout.LayoutParams rlpForImgWishList = (RelativeLayout.LayoutParams) imgvWishList.getLayoutParams();
			rlpForImgWishList.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgWishList.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgWishList.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgWishList.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgvWishList.setLayoutParams(rlpForImgWishList);
			imgvWishList.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), WishListPage.class);
						intent.putExtra("offerViewActivity", false);
						intent.putExtra("pageRedirectFlag", "Closet");
						startActivity(intent);
					    finish();
					    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Closet imgvWishList onTouch.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgvWishList  click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
				}
			});

			ImageView imgvRecommended = (ImageView) findViewById(R.id.imgvRecommended);
			RelativeLayout.LayoutParams rlpForImgRecommended = (RelativeLayout.LayoutParams) imgvRecommended.getLayoutParams();
			rlpForImgRecommended.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgRecommended.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgRecommended.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgRecommended.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			imgvRecommended.setLayoutParams(rlpForImgRecommended);
			imgvRecommended.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						if(Common.isNetworkAvailable(Closet.this))
						{
							Intent intent = new Intent(getApplicationContext(), Products.class);   
							intent.putExtra("pageRedirectFlag", "Closet");
							startActivity(intent);
						    finish();
						    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
						}else{
							new Common().instructionBox(Closet.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Closet imgvRecommended onTouch.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgvRecommended  click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}					
				}
			});

	    	ImageView imgBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);  
	    	imgBtnCloset.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {	
					if(Common.isNetworkAvailable(Closet.this))
					{
						if(fancyCoverFlow.getSelectedView()!=null){
						AlertDialog.Builder alertDialog = new AlertDialog.Builder(Closet.this);		
				        alertDialog.setTitle("Confirm Delete...");		
				        alertDialog.setMessage("Are you sure you want delete the product?");
				        alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
				            @Override
							public void onClick(DialogInterface dialog,int which) {			           
								try{									
				            		LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
				            		Log.i("ll", ""+ll+" "+ll.getChildCount());
				            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);
				            		Log.i("ll2", ""+ll2.getChildCount()+" "+ll2.getChildAt(0));	            		
			                		ImageView prodImage = (ImageView)ll2.getChildAt(0);
				            		insertUpdateDeleteProductsToClosetDbUsingXml(Constants.Closet_Url+"delete/"+Common.sessionIdForUserLoggedIn+"/"+prodImage.getTag()+"/2/", "delete");

									String closetUrl = Constants.Closet_Url+"xml/"+Common.sessionIdForUserLoggedIn;
									getYourClosetResultsFromDBTable(closetUrl, 0);
									
									String screenName = "/mycloset/removed/"+prodImage.getTag()+"/2";
									String productIds = "";
									String offerIds = "";
									Common.sendJsonWithAQuery(Closet.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
								} catch (Exception e) {
									// TODO: handle exception
									e.printStackTrace();	
									String errorMsg = className+" | imgBtnCloset Delete click  |   " +e.getMessage();
						       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
								}
				            }
				        });
				        alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
				            @Override
							public void onClick(DialogInterface dialog, int which) {			         
				            dialog.cancel();
				            }
				        });
				        alertDialog.show();
					} else {
						//Toast.makeText(getApplicationContext(), "There are no product(s) to delete.", Toast.LENGTH_LONG).show();
					}
				}else{
					new Common().instructionBox(Closet.this,R.string.title_case7,R.string.instruction_case7);
					}			
				}
			});
	
	    	ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);        
	    	imgBtnCart.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{						
							if(fancyCoverFlow.getSelectedView()!=null){	
								LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
			            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);           		
		                		ImageView prodImage = (ImageView)ll2.getChildAt(0);
		                		ImageView prodClient  = (ImageView)ll2.getChildAt(2);
								BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
								Bitmap bitmap = test.getBitmap();
								ByteArrayOutputStream baos = new ByteArrayOutputStream();
								bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
								byte[] b = baos.toByteArray();
								Intent intent = new Intent(Closet.this, ProductInfo.class);
								intent.putExtra("tapOnImage", false);		
								//intent.putExtra("image", b);		
								intent.putExtra("productId",  ""+prodImage.getTag());
								intent.putExtra("clientId",""+prodClient.getTag());		
								startActivity(intent);
								overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
							}
						 
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Closet imgBtnCart.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgBtnCart click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}	
				}
			});
	    	
			ImageView imgvMyOffers = (ImageView) findViewById(R.id.imgvMyOffers); 
			RelativeLayout.LayoutParams rlpForImgMyOffers = (RelativeLayout.LayoutParams) imgvMyOffers.getLayoutParams();
			rlpForImgMyOffers.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgMyOffers.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgMyOffers.rightMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgMyOffers.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			imgvMyOffers.setLayoutParams(rlpForImgMyOffers);
			imgvMyOffers.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						Intent intent = new Intent(getApplicationContext(), MyOffers.class);   
						intent.putExtra("pageRedirectFlag", "Closet");						
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Closet imgvMyOffers.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgvMyOffers click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}					
				}
			});

	    	ImageView imgBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
	    	imgBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{
	            		if(fancyCoverFlow.getSelectedView()!=null){						
		            		LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
		            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);       		
	                		ImageView prodImage = (ImageView)ll2.getChildAt(0);
	                		ImageView prodClient  = (ImageView)ll2.getChildAt(2);
	    					BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
	    					Bitmap bitmap = test.getBitmap();
	    					if(bitmap != null){    					
	    					Intent intent = new Intent(Closet.this, ShareActivity.class);   
							intent.putExtra("pageRedirectFlag", "Closet");
	    					intent.putExtra("tapOnImage", false);		
	    					//intent.putExtra("image", b);		
	    					intent.putExtra("image", ""+prodImage.getTag(R.string.imageUrl));
	    					intent.putExtra("productId",  ""+prodImage.getTag());
	    					intent.putExtra("clientId",""+prodClient.getTag());		
	    					startActivity(intent);
	    					overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
	    					}
	            		} else {
	            			txtvProdNotAvail.setVisibility(View.VISIBLE);
	            		}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnShare click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
				}
			});
			
			ImageView imgvByBrands = (ImageView) findViewById(R.id.imgvByBrands);
			RelativeLayout.LayoutParams rlpForImgByBrands = (RelativeLayout.LayoutParams) imgvByBrands.getLayoutParams();
			rlpForImgByBrands.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgByBrands.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgByBrands.rightMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgByBrands.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgvByBrands.setLayoutParams(rlpForImgByBrands);
			imgvByBrands.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), ByBrands.class);   
						intent.putExtra("pageRedirectFlag", "Closet");
						startActivity(intent);
						finish();
						overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
					} catch (Exception ex) {
						Toast.makeText(getApplicationContext(), "Error: Closet imgvByBrands.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgvByBrands click  |   " +ex.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}					
				}
			});
		} catch (Exception e) {
			//Toast.makeText(getApplicationContext(), "Error: Closet onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onCreate  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
		}
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			if(requestCode == 1){
				if(!Common.isNetworkAvailable(Closet.this)){
					if(data != null){
						 String activity=data.getStringExtra("activity");						 
						 if(activity.equals("menu")){
							 new Common().instructionBox(Closet.this, R.string.title_case7, R.string.instruction_case7);
						 }
					 }
				}
			}
			
		}catch (Exception e){	
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);			
		}		
	}
	
	private class LoadCloset extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... params) {
        	try{
        		getYourClosetResultsFromFile(flag);
        	}catch(Exception e){
        		e.printStackTrace();
        	}
			return null;
        }
        @Override
        protected void onPostExecute(String result) {
        	try{
        	if(closetFinalArray != null){
        	fancyCoverFlow.setAdapter(renderForCoverFlow(closetFinalArray));
	        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
	        fancyCoverFlow.setMaxRotation(120);
	        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
				@Override
				public void onItemClick(AdapterView<?> arg0, View arg1,
						int arg2, long arg3) {
					try{
						if(fancyCoverFlow.getSelectedView()!=null){	
							String s ="[";
							String q ="]";
							String w ="";
							String strReplaceSymbol = String.valueOf(closetFinalArray[arg2]).replace(s, w).replace(q, w);
							
							String[] expClosetArray = strReplaceSymbol.split(",");
							String expBgColorCode = expClosetArray[6].trim();		
							String expClientLogo = expClosetArray[7].trim();	
							Common.sessionClientBgColor = expBgColorCode;
							Common.sessionClientLogo = expClientLogo;
							
							LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
		            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);
		            		ImageView prodImage = (ImageView)ll2.getChildAt(0);
	                		ImageView prodClient  = (ImageView)ll2.getChildAt(2);
							BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
							Bitmap bitmap = test.getBitmap();
							if(bitmap != null){
								Intent intent = new Intent(Closet.this, ProductInfo.class);
								intent.putExtra("tapOnImage", false);		
								//intent.putExtra("image", b);		
								intent.putExtra("productId",  ""+prodImage.getTag());
								intent.putExtra("clientId",""+prodClient.getTag());		
								startActivity(intent);
								overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
							}
						} else {
							txtvProdNotAvail.setVisibility(View.VISIBLE);
	            		}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onPostExecute  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
					}
				}
    			
    		});
        } else {
			txtvProdNotAvail.setVisibility(View.VISIBLE);
		}
        }catch(Exception e){
        	e.printStackTrace();
        }
        }
	}
	
	
	ArrayList<String> closetResArrays;
	String[] closetFinalArray;
	 List<UserCloset> userCloset;
	private void getYourClosetResultsFromFile(String selectionStatus) {
		try{
			ClosetModel closetmodel = file.getCloset();
			ProductModel  getProdDetail = file.getProduct();
			if(closetmodel.size() >0){
				if(!selectionStatus.equals("0"))
					userCloset = closetmodel.getClosetListByFlag(selectionStatus);					
				else
					userCloset = closetmodel.getClosetList();
				
				closetFinalArray = new String[userCloset.size()];
				int c=0;
				if(userCloset != null){
					for ( final UserCloset usercloset : userCloset) {
						closetResArrays = new ArrayList<String>(); 	    			   
                		 
            			 UserProduct chkProdExist = getProdDetail.getUserProductById(usercloset.getProductId());
            			 if(chkProdExist != null){
	                 		String curveImagesUrl = chkProdExist.getImageFile().replaceAll(" ", "%20");	
	                		closetResArrays.add(curveImagesUrl);
	                		closetResArrays.add(""+chkProdExist.getClientId());
	                		closetResArrays.add(""+chkProdExist.getProductId());
	                		closetResArrays.add(chkProdExist.getProductName());
	                		closetResArrays.add(usercloset.getClosetSelectionStatus());
	                		closetResArrays.add(""+chkProdExist.getProdIsTryOn());
	                		closetResArrays.add(chkProdExist.getClientBackgroundColor());
	                		closetResArrays.add(chkProdExist.getClientLogo());
	                		closetResArrays.add(chkProdExist.getClientLightColor());
	                		closetResArrays.add(chkProdExist.getClientDarkColor());
	                		closetResArrays.add(chkProdExist.getButtonName());
	                		closetResArrays.add(chkProdExist.getProductPrice());
	                		closetResArrays.add(chkProdExist.getClientName());
	                		closetResArrays.add("");
	                		closetResArrays.add(chkProdExist.getClientUrl());
	                		closetResArrays.add(chkProdExist.getProductDesc());

	                		closetResArrays.add(chkProdExist.getProductShortDesc());
	                		closetResArrays.add(chkProdExist.getProdTapForDetailsImg());
	                		Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
			    			if(bitmap1==null) {
			    				aq.cache(curveImagesUrl, 144000);
			    			}
	                    	closetFinalArray[c] = closetResArrays.toString();
			    			c++;
            			 }
					}							
					
			} else {
				txtvProdNotAvail.setVisibility(View.VISIBLE);
			}
		}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getYourClosetResultsFromFile  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
		}
		
	}
	
	public void getYourClosetResultsFromDBTable(String closetUrl, final int closetSelectionStatus) {
		aq = new AQuery(this);
		aq.ajax(closetUrl+"/"+closetSelectionStatus, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
    				if(xml!=null){
    					endnow = android.os.SystemClock.uptimeMillis();
    		  			 Log.e("MYTAG closet endnow ", "Excution time: "+(endnow-startnow)/1000+" s");
    					ProductModel productModel = new ProductModel();
    					 ProductModel  getProdDetail = file.getProduct();
    					List<XmlDom> closetXmlTag = xml.tags("prodCloset");
    					if(closetXmlTag.size()>0 ){
	                    	closetFinalArray = new String[closetXmlTag.size()];
	                    	
	        		        ClosetModel getClosetmodel = file.getCloset();
	        		        if(getClosetmodel.size() >0){
	        		        	if(closetSelectionStatus ==0)
	        		        		getClosetmodel.removeAll();
	        		        }
	        		        ClosetModel closetModel = null;
	        		        if(getClosetmodel.size() ==0 ){
	        		        	if(closetSelectionStatus ==0)
	        		        		closetModel = new ClosetModel();
	        		        }
    						int c=0;
	                    	for(XmlDom closetXml: closetXmlTag){
	                    		if(closetXml.tag("pd_id")!=null){
	                    			closetResArrays = new ArrayList<String>();		    	    			   
		                    		String curveImagesUrl = closetXml.text("image").toString().replaceAll(" ", "%20");	
		                    		closetResArrays.add(curveImagesUrl);
		                    		closetResArrays.add(closetXml.text("client_id").toString());
		                    		closetResArrays.add(closetXml.text("pd_id").toString());
		                    		closetResArrays.add(closetXml.text("pd_name").toString());
		                    		closetResArrays.add(closetXml.text("closet_selection_status").toString());
		                    		closetResArrays.add(closetXml.text("pd_istryon").toString());
		                    		closetResArrays.add(closetXml.text("background_color").toString());
		                    		closetResArrays.add(closetXml.text("client_logo").toString());
		                    		closetResArrays.add(closetXml.text("light_color").toString());
		                    		closetResArrays.add(closetXml.text("dark_color").toString());
		                    		//Log.e("pd button name", ""+closetXml.text("pd_button_name").toString());
		                    		closetResArrays.add(closetXml.text("pd_button_name").toString());
		                    		String symbol = new Common().getCurrencySymbol(closetXml.text("country_languages").toString(), closetXml.text("country_code_char2").toString());
		                    		closetResArrays.add(symbol+closetXml.text("pd_price").toString());
			                		closetResArrays.add(closetXml.text("client_name").toString());
			                		closetResArrays.add("");
			                		closetResArrays.add(closetXml.text("client_url").toString());
		                    		closetResArrays.add(closetXml.text("pd_description").toString());
		                    		closetResArrays.add(closetXml.text("tapForDetailsImgs").toString());
		                    		
		                    		if(getClosetmodel.size() ==0){
		                    			if(closetSelectionStatus ==0){
		                    			 UserCloset userCloset = new UserCloset();
		                    			 userCloset.setClientId(Integer.parseInt(closetXml.text("client_id").toString()));
		                    			 userCloset.setProductId(Integer.parseInt(closetXml.text("pd_id").toString()));
		                    			 userCloset.setClientName(closetXml.text("client_name").toString());
		                    			 userCloset.setClosetSelectionStatus(closetXml.text("closet_selection_status").toString());
		                    			 userCloset.setProductRelatedId(closetXml.text("related_id").toString());
		                    			 userCloset.setClosetFlag(true);
		                    			 closetModel.add(userCloset);
		                    			
		                    			 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(closetXml.text("pd_id").toString()));
		                    			 if(chkProdExist == null){		                    				 
			                    			 UserProduct userProduct = new UserProduct();
			                    			 userProduct.setClientId(Integer.parseInt(closetXml.text("client_id").toString()));
			                    			 userProduct.setClientName(closetXml.text("client_name").toString());
			                    			 userProduct.setClientUrl(closetXml.text("client_url").toString());
			                    			 userProduct.setImageFile(curveImagesUrl);
			                    			 userProduct.setProductId(Integer.parseInt(closetXml.text("pd_id").toString()));
			                    			 userProduct.setProductName(closetXml.text("pd_name").toString());			                    			
			                    			 if (closetXml.text("pd_price").toString().equals("null") || 
			                    						 closetXml.text("pd_price").toString().equals("") || 
			                    						 closetXml.text("pd_price").toString().equals("0") || 
			                    						 closetXml.text("pd_price").toString().equals("0.00") || 
			                    						 closetXml.text("pd_price").toString() == null) {
			                    				 	userProduct.setProductPrice(closetXml.text("pd_price").toString());
												} else {
													 userProduct.setProductPrice(symbol+closetXml.text("pd_price").toString());
												}
			                    			
			                    			 userProduct.setProductShortDesc(closetXml.text("pd_short_description").toString());
			                    			 userProduct.setProductDesc(closetXml.text("pd_description").toString());
			                    			 userProduct.setProductUrl(closetXml.text("pd_url").toString());
			                    			 userProduct.setProdIsTryOn(Integer.parseInt(closetXml.text("pd_istryon").toString()));
			                    			 userProduct.setClientBackgroundColor(closetXml.text("background_color").toString());
			                    			 userProduct.setClientLightColor(closetXml.text("light_color").toString());
			                    			 userProduct.setClientDarkColor(closetXml.text("dark_color").toString());
			                    			 userProduct.setClientLogo(closetXml.text("client_logo").toString());
			                    			 userProduct.setProductRelatedId(closetXml.text("related_id").toString());
			                    			 userProduct.setButtonName(closetXml.text("pd_button_name").toString());			                    			 
			                    			 userProduct.setProductShortDesc(closetXml.text("pd_short_description").toString());
			                    			 userProduct.setProdTapForDetailsImg(closetXml.text("tapForDetailsImgs").toString());
			                    			 userProduct.setProdTapForDetailsImgId(closetXml.text("tapForDetailsImgId").toString());
			                    			 userProduct.setProdTapForDetailsImgPdId(closetXml.text("tapForDetailsImgPdId").toString());
			                    			 productModel.add(userProduct);
		                    			 }
		                    			}
		                    		}
		                    		Common.arrPdIdsForUserAnalytics.add(closetXml.text("pd_id").toString());
		                    		Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
		                    		if(bitmap1==null) {
	    			    				aq.cache(curveImagesUrl, 144000);
		    		    			}
		                    	
		                    		if(closetXml.text("client_logo").toString() != "")
		                    		{
			    		    			Bitmap bitmap2 = aq.getCachedImage(closetXml.text("client_logo").toString().replaceAll(" ", "%20"));
			    		    			if(bitmap2==null) {
			    		    				String logoUrl = (Constants.Client_Logo_Location+closetXml.text("client_id").toString()+"/logo/"+closetXml.text("client_logo").toString()).toString().replaceAll(" ", "%20");
			    		    				aq.cache(logoUrl, 144000);
			    		    				bitmap2 = aq.getCachedImage(logoUrl);
			    		    			}
		    		    			}
			                    	closetFinalArray[c] = closetResArrays.toString();
		    		    			c++;
	                    		} else {
	                    			closetFinalArray = new String[0];
	                    			txtvProdNotAvail.setVisibility(View.VISIBLE);
	        					}
	                    	}
	                    	if(getClosetmodel.size() ==0){
	                    		file.setCloset(closetModel);
	                    	}
	                    	if(productModel.size() >0){
	                    		getProdDetail.mergeWith(productModel);
	                    		file.setProduct(getProdDetail);
	                    	}	                    	
        				    fancyCoverFlow.setAdapter(renderForCoverFlow(closetFinalArray));
	    			        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
	    			        fancyCoverFlow.setMaxRotation(120);
	    			        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
	    						@Override
	    						public void onItemClick(AdapterView<?> arg0, View arg1,
	    								int arg2, long arg3) {
	    							try{
		    							if(fancyCoverFlow.getSelectedView()!=null){	
		    								String s ="[";
		    								String q ="]";
		    								String w ="";
		    								String strReplaceSymbol = String.valueOf(closetFinalArray[arg2]).replace(s, w).replace(q, w);
		    								
		    								String[] expClosetArray = strReplaceSymbol.split(",");
		    								String expBgColorCode = expClosetArray[6].trim();		
		    								String expClientLogo = expClosetArray[7].trim();
		    								String expPdButtonName = expClosetArray[10].trim();				
		    								//Log.e("expClosetArray", ""+expClosetArray);
		    								Log.i("my", expClosetArray[8].trim()+" "+expClosetArray[9].trim()+" "+expClosetArray[10].trim());
		    								Common.sessionClientBgColor = expBgColorCode;
		    								Common.sessionClientLogo = expClientLogo;
		    								
		    								LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
		    			            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(1);
		    			            		ImageView prodImage = (ImageView)ll2.getChildAt(0);
		    		                		ImageView prodClient  = (ImageView)ll2.getChildAt(2);
		    		                		
		    		                		
			    							
			    							Intent intent;
			    							/*if(expPdButtonName.equals("Buy")){
			    								intent = new Intent(Closet.this, ProductDetails.class);
				    							intent.putExtra("prodStrArr",  expClosetArray);
				    							Log.e("expClosetArray ", ""+expClosetArray.length);
				    							for(int i=0; i<expClosetArray.length; i++){
				    								Log.e("expClosetArray "+i, ""+expClosetArray[i].trim());
				    							}
			    							} else {
			    								intent = new Intent(Closet.this, ProductInfo.class);			    								
			    							}*/
			    							intent = new Intent(Closet.this, ProductInfo.class);
			    							intent.putExtra("tapOnImage", false);	
			    							intent.putExtra("pageRedirectFlag", "Closet");		
			    							//intent.putExtra("image", b);		
			    							intent.putExtra("productId",  ""+prodImage.getTag());
			    							intent.putExtra("clientId",""+prodClient.getTag());		
			    							startActivity(intent);
			    							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
		    							} else {
	    									txtvProdNotAvail.setVisibility(View.VISIBLE);
	    			            		}
	    							}catch(Exception e){
	    								e.printStackTrace();
	    								String errorMsg = className+" | getYourClosetResultsFromDBTable fancyCoverFlow |   " +e.getMessage();
	    					       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
	    							}
	    						}	    		    			
	    		    		});	                    	
    					} else {
    						txtvProdNotAvail.setVisibility(View.VISIBLE);
    					}
    				} else {
						txtvProdNotAvail.setVisibility(View.VISIBLE);
					}
    				
					String joinedWithPipe = TextUtils.join("|", Common.arrPdIdsForUserAnalytics);
					Log.i("joined", ""+joinedWithPipe);
					String screenName = "/mycloset";
					String productIds = joinedWithPipe;
					String offerIds = "";
					Common.sendJsonWithAQuery(Closet.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);

				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | getYourClosetResultsFromDBTable  |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(Closet.this,errorMsg);
				}
			}			            			
		});	
	}	
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            onBackPressed();
            isBackPressed = true;
        }
        return super.onKeyDown(keyCode, event);
    }
	 @Override
	public void onBackPressed(){
		 try{
		// new Common().clickingOnBackButtonWithAnimationWithBackPressed(Closet.this, ARDisplayActivity.class, "0");
		 Intent returnIntent = new Intent(Closet.this, ProductList.class);		
		 setResult(1, returnIntent);
		 finish();
		 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onBackPressed  |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(Closet.this,errorMsg);
		 }
	 }

	 @Override
	public void onStart() {
	 try{
		    super.onStart();
		    Tracker easyTracker = EasyTracker.getInstance(this);
			easyTracker.set(Fields.SCREEN_NAME, "/mycloset");
			easyTracker.send(MapBuilder
			    .createAppView()
			    .build()
			);
			 String[] segments = new String[1];
			 segments[0] = "Closet"; 
			 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart  |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(Closet.this,errorMsg);
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
       	 Common.sendCrashWithAQuery(Closet.this,errorMsg);
	 }
	}	
	public void insertUpdateDeleteProductsToClosetDbUsingXml(String getUrl, final String checkFlag) {
		aq = new AQuery(this);
		aq.ajax(getUrl, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
					if(xml!=null){
    					if(xml.text("msg").equals("already")){
	    					//Toast.makeText(getApplicationContext(), "Already have same Product(s) in this closet.", Toast.LENGTH_LONG).show();
	    				} else if(xml.text("msg").equals("success")){
	    					if(checkFlag.equals("insert")){
	    						//Toast.makeText(getApplicationContext(), "Product(s) added successfully to closet.", Toast.LENGTH_LONG).show();
	    					} else if(checkFlag.equals("update")){
	    						//Toast.makeText(getApplicationContext(), "Product(s) updated successfully to closet.", Toast.LENGTH_LONG).show();
	    					} else if(checkFlag.equals("delete")){
	    						//Toast.makeText(getApplicationContext(), "Product(s) deleted successfully to closet.", Toast.LENGTH_LONG).show();	
	    					}
	    				} else {
	    					if(checkFlag.equals("insert")){
	    						//Toast.makeText(getApplicationContext(), "Product(s) adding failed. Please try again!", Toast.LENGTH_LONG).show();
	    					} else if(checkFlag.equals("update")){
	    						//Toast.makeText(getApplicationContext(), "Product(s) updating failed. Please try again!", Toast.LENGTH_LONG).show();
	    					} else if(checkFlag.equals("delete")){
	    						//Toast.makeText(getApplicationContext(), "Product(s) deleting failed. Please try again!", Toast.LENGTH_LONG).show();	
	    					}
	    				}
    				}
				} catch(Exception e){
					e.printStackTrace();
					 String errorMsg = className+" | insertUpdateDeleteProductsToClosetDbUsingXml  |   " +e.getMessage();
			       	 Common.sendCrashWithAQuery(Closet.this,errorMsg);
				}
			}			            			
		});	
	}
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
    int gridItemLayout = 0;    
    ArrayList<String> closetEachArray;
	private ArrayAdapter<String> renderForCoverFlow(final String[] closetFinalArray2){	    	
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.coverflowitem_closet;	
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, closetFinalArray2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					
					if(convertView == null){
						//convertView = aq.inflate(convertView, gridItemLayout, parent);
						convertView = getLayoutInflater().inflate(gridItemLayout, parent, false);
					}	
					AQuery aq2 = new AQuery(convertView);						
					if(closetFinalArray2[position]!=null){
						String s ="[";
						String q ="]";
						String w ="";
						String strReplaceSymbol = String.valueOf(closetFinalArray2[position]).replace(s, w).replace(q, w);					
						String[] expClosetArray = strReplaceSymbol.split(",");
						
						String expImageUrl = expClosetArray[0].trim();		
						String expClientId = expClosetArray[1].trim();		
						String expProductId = expClosetArray[2].trim();		
						String expProductName = expClosetArray[3].trim();		
						String expClosetSeleStatus = expClosetArray[4].trim();		
						String expProductIsTryOn = expClosetArray[5].trim();		
						String expBgColorCode = expClosetArray[6].trim();		
						String expClientLogo = expClosetArray[7].trim();	
						
						//0 is image url
						//1 is client id
						//2 is product id
						//3 is product name
						//4 is closet_selection_status
						//5 is product is try on
						//6 is Theme background color code
						//7 is client logo	
						
						Bitmap placeholder = aq2.getCachedImage(expImageUrl);
						if(placeholder==null){
							aq2.cache(expImageUrl, 1440000);					
						}
						TextView txtProdName =(TextView) convertView.findViewById(R.id.txtvProdName);
						txtProdName.setText(expProductName);
						txtProdName.setTextSize((float) ((0.026 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
						
						ImageView img =(ImageView) convertView.findViewById(R.id.coverFlowImage);
						img.setImageBitmap(placeholder);
						img.setTag(expProductId);	
						img.setTag(R.string.imageUrl, expImageUrl);	
	
						RelativeLayout coverflowLlayout1 = (RelativeLayout) convertView.findViewById(R.id.coverflowLlayoutImage);
						LinearLayout.LayoutParams llpForLl = (LinearLayout.LayoutParams) coverflowLlayout1.getLayoutParams();
						llpForLl.width = (int) (0.667 * Common.sessionDeviceWidth);
						llpForLl.height = (int) (0.4611 * Common.sessionDeviceHeight);
						coverflowLlayout1.setLayoutParams(llpForLl);
						new Common().gradientDrawableCorners(Closet.this, null, coverflowLlayout1, 0.0334, 0.0167);
						
						RelativeLayout.LayoutParams llForCoverFlowImg1 = (RelativeLayout.LayoutParams) img.getLayoutParams();
						llForCoverFlowImg1.width = LayoutParams.MATCH_PARENT;
						llForCoverFlowImg1.height = LayoutParams.MATCH_PARENT;
						img.setLayoutParams(llForCoverFlowImg1);
	
						Button btnSeeItLive = (Button) convertView.findViewById(R.id.btnSeeItLive);
						commonLib.btnForSeeItLiveWithAllColors(Closet.this, btnSeeItLive, "relative", "width", "products", 
								expProductId, expClientId, Integer.parseInt(expProductIsTryOn), expBgColorCode, expClosetArray[8].trim(), 
								expClosetArray[9].trim());
						
						Button btnSeeVideo = (Button) convertView.findViewById(R.id.btnSeeVideo);
						commonLib.btnForVideoWithAllColors(Closet.this, btnSeeVideo, "relative", "width", "products", 
								expProductId, expClientId, expClosetArray[16].trim(), expBgColorCode, expClosetArray[8].trim(), 
								expClosetArray[9].trim(),expClosetArray[7].trim());
						
						ImageView flag = (ImageView)convertView.findViewById(R.id.flag);
						RelativeLayout.LayoutParams rlForFlag = (RelativeLayout.LayoutParams) flag.getLayoutParams();
						rlForFlag.width = (int) (0.1 * Common.sessionDeviceWidth);
						rlForFlag.height = (int) (0.0615 * Common.sessionDeviceHeight);
						flag.setLayoutParams(rlForFlag);
						if(expClosetSeleStatus.equals("2")){
							 flag.setImageResource(R.drawable.icon_ownit);
				         } else {
				        	 flag.setImageResource(R.drawable.icon_wantit);	        	 
				         }		
						flag.setTag(expClientId);	
					
					}						
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForCoverFlow  |   " +e.getMessage();
			       	Common.sendCrashWithAQuery(Closet.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		//aq.id(R.id.grid_view).adapter(aa);
		return aa;
	}	
	
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(Closet.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(Closet.this,errorMsg);
			}
			
		}
	 
	 @Override
		protected void onResume() 
		{
			try{
				super.onResume();					
				if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(Closet.this);			
					Common.isAppBackgrnd = false;
				}
				
			}catch (Exception e) 
			{		
				e.printStackTrace();
				String errorMsg = className+" | onResume | " +e.getMessage();
	        	Common.sendCrashWithAQuery(Closet.this,errorMsg);
			
			}			
		}
	
}