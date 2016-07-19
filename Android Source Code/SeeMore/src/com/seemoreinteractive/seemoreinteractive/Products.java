package com.seemoreinteractive.seemoreinteractive;

import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import org.json.JSONArray;
import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.BitmapDrawable;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class Products extends Activity {	
	String className = this.getClass().getSimpleName();
	AQuery aq;
	String flagForBackButton = "";
	public static Activity prds;
	ProgressBar progressBar;
	public  ArrayList<HashMap<String, String>> arrPdInfoForClientProdList;
	TextView txtvMessgae;
	String shopFlag ="null",orderId="null";
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_products);
		try{
			prds = this;
			aq = new AQuery(this);
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Category List", "");
			
			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			new Common().showDrawableImageFromAquery(this, R.drawable.btn_share, R.id.imgvBtnCloset);
			new Common().showDrawableImageFromAquery(this, R.drawable.btn_cart_saved, R.id.imgvBtnShare);
			
			/*ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);*/
			
			progressBar = (ProgressBar)findViewById(R.id.progressBar);
			txtvMessgae = (TextView)findViewById(R.id.txtvMessgae);

			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{	
						//new Common().clickingOnBackButtonWithAnimationWithBackPressed(Products.this, ARDisplayActivity.class, "0");
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnCameraIcon click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Products.this, errorMsg);
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
			            	
		            	} catch (Exception e) {
							//Toast.makeText(getApplicationContext(), "Error: ProductInfo imgFooterMiddle.", Toast.LENGTH_LONG).show();
		            		e.printStackTrace();
		            		String errorMsg = className+" | imgFooterMiddle  click  |   " +e.getMessage();
							Common.sendCrashWithAQuery(Products.this, errorMsg);
						}
		            }
		    });
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {		
				@Override
				public void onClick(View v) {
					try{	
						//Log.e("flagForBackButton", ""+flagForBackButton);
						if(flagForBackButton.equals("")){					    
						     finish();
						     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
						} else if(flagForBackButton.equals("mainClientCategory")){
							horizontalScrollView1.setVisibility(View.VISIBLE);
							horizontalScrollView2.setVisibility(View.VISIBLE);
							Products.this.findViewById(dynamicHorizScrView.getId()).setVisibility(View.INVISIBLE);
							flagForBackButton = "";
						} else if(flagForBackButton.startsWith("mainClientSubCategory")){
							horizontalScrollView1.setVisibility(View.VISIBLE);
							horizontalScrollView2.setVisibility(View.VISIBLE);
							flagForBackButton = "";
							
						} else if(flagForBackButton.equals(""+dynamicHorizScrView.getId())) {								
							horizontalScrollView1.setVisibility(View.GONE);
							horizontalScrollView2.setVisibility(View.GONE);
							Products.this.findViewById(Integer.parseInt(flagForBackButton)).setVisibility(View.VISIBLE);
							Products.this.findViewById(dynamicHorizScrView.getId()).setVisibility(View.VISIBLE);
							flagForBackButton = "mainClientSubCategory";
						} else {
							horizontalScrollView1.setVisibility(View.GONE);
							horizontalScrollView2.setVisibility(View.GONE);
							Products.this.findViewById(Integer.parseInt(flagForBackButton)).setVisibility(View.VISIBLE);
							//Log.e("dynamicHorizScrView.getId()",""+dynamicHorizScrView.getId());
							HorizontalScrollView hrScrlview = (HorizontalScrollView) findViewById(dynamicHorizScrView.getId());
							if(hrScrlview != null){
								horizontalScrollView2.setVisibility(View.VISIBLE);
								Products.this.findViewById(dynamicHorizScrView.getId()).setVisibility(View.INVISIBLE);
								flagForBackButton = "mainClientSubCategory";
							}else{
								horizontalScrollView2.setVisibility(View.VISIBLE);
								flagForBackButton = "mainClientSubCategory";
							}
							
							
						}
						
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnBackButton click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Products.this, errorMsg);
					}
				}
			});
			

	    	ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
	    	imgvBtnCloset.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{
            		if(fancyCoverFlow.getSelectedView()!=null){						
	            		LinearLayout ll =(LinearLayout)fancyCoverFlow.getSelectedView();
	            		RelativeLayout ll2 =(RelativeLayout) ll.getChildAt(0);       
	            		RelativeLayout rl2 =(RelativeLayout) ll2.getChildAt(0);  
                		ImageView prodImage = (ImageView)rl2.getChildAt(0);
    					BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
    					Bitmap bitmap = test.getBitmap();
    					if(bitmap != null){
    					Intent intent = new Intent(Products.this, ShareActivity.class);   
						intent.putExtra("pageRedirectFlag", "Products");
    					intent.putExtra("tapOnImage", false);		
    					//intent.putExtra("image", b);		
    					intent.putExtra("image", ""+prodImage.getTag(R.string.imageUrl));
    					intent.putExtra("productId",  ""+prodImage.getTag(R.string.productId));
    					intent.putExtra("clientId",""+prodImage.getTag(R.string.clientId));		
    					startActivity(intent);
    					overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
    					}
            		}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnShare click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Products.this,errorMsg);
					}
				}
			});
			
			
			ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);   
			RelativeLayout.LayoutParams rlpForImgvBtnShare = (RelativeLayout.LayoutParams) imgvBtnShare.getLayoutParams();
			rlpForImgvBtnShare.width = (int) (0.25 * Common.sessionDeviceWidth);
			rlpForImgvBtnShare.height = (int) (0.082 * Common.sessionDeviceHeight);
			imgvBtnShare.setLayoutParams(rlpForImgvBtnShare);
			imgvBtnShare.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{	
						Intent intent = new Intent(Products.this,SaveOrderInformation.class);
						startActivity(intent);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnCameraIcon click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(Products.this, errorMsg);
					}
				}
			});
			mainCategoriesList(indexBtnMainCat1, 0, "mainCategory");
		} catch(Exception e){
			e.printStackTrace();
    		String errorMsg = className+" | oncreate  click  |   " +e.getMessage();
			Common.sendCrashWithAQuery(Products.this, errorMsg);
		}
	}
	HorizontalScrollView horizontalScrollView1, horizontalScrollView2, dynamicHorizScrView;
	LinearLayout horScrViewLineLayout1, horScrViewLineLayout2, llForHSV;
	JSONArray jsonClientCate, jsonMainCateArray = new JSONArray();
	int indexBtnMainCat1 = 0, indexBtnMainCatNew=0, indexBtnClientCat = 0, indexBtnClientCat2 = 0, indexBtnClientSubCatNew = 0;
	private void mainCategoriesList(final int indexBtnMainCat2, final int catMainId2, final String strFlag2) {		
		try{
			horizontalScrollView1 = (HorizontalScrollView) findViewById(R.id.horizontalScrollView1);
			horScrViewLineLayout1 = (LinearLayout) findViewById(R.id.horScrViewLineLayout1);
			String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/maincategories";
			aq.ajax(productUrl, JSONArray.class, new AjaxCallback<JSONArray>(){			
				@Override
				public void callback(String url, JSONArray json, AjaxStatus status) {
					try{    					
	    				if(json!=null && strFlag2.equals("mainCategory")){
	    					progressBar.setVisibility(View.INVISIBLE);
	    					jsonMainCateArray = json;
    						final Button[] btnMainCategory = new Button[json.length()];
	    					for(int i=indexBtnMainCat1; i<json.length(); i++){
	    						if(json.getJSONObject(i).optString("cat_main_name")!=null){		    						
		    						final int indexBtnMainCat = i;
		    						indexBtnMainCatNew = i;
		    						final int catMainId = json.getJSONObject(indexBtnMainCat).getInt("cat_main_id");		    						
		    						final String catMainName = json.getJSONObject(indexBtnMainCat).getString("cat_main_name");	
		    						btnMainCategory[indexBtnMainCat] = new Button(Products.this);
		    						btnMainCategory[indexBtnMainCat].setId(indexBtnMainCat);
		    						btnMainCategory[indexBtnMainCat].setText(catMainName);
		    						btnMainCategory[indexBtnMainCat].setWidth((int) (0.334 * Common.sessionDeviceWidth));
		    						btnMainCategory[indexBtnMainCat].setHeight((int) (0.0614 * Common.sessionDeviceHeight));
		    						btnMainCategory[indexBtnMainCat].setTextSize((float)(0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
		    						btnMainCategory[indexBtnMainCat].setTypeface(null, Typeface.BOLD);
		    						btnMainCategory[indexBtnMainCat].setBackgroundResource(R.drawable.pd_client_horiz_bg);
		    						
		    						horScrViewLineLayout1.addView(btnMainCategory[indexBtnMainCat]);
		    						btnMainCategory[indexBtnMainCat].setOnClickListener(new OnClickListener() {
										@Override
										public void onClick(View v) {											
											try{												
												mainCategoriesList(indexBtnMainCat, catMainId, "mainClientCategory");												
												btnMainCategory[indexBtnMainCat].setTextColor(Color.parseColor("#000000"));
												for(int s=0; s<=indexBtnMainCatNew; s++){													
													if(s==indexBtnMainCat){														
														btnMainCategory[indexBtnMainCat].setBackgroundResource(R.drawable.pd_client_bg_enable);
													} 
													else if(s!=indexBtnMainCat){														
														Products.this.findViewById(s).setBackgroundResource(R.drawable.pd_client_horiz_bg);
													}
												}
											}catch(Exception e){
												e.printStackTrace();
									    		String errorMsg = className+" | btnMainCategory  click  |   " +e.getMessage();
												Common.sendCrashWithAQuery(Products.this, errorMsg);
											}
										}
									});
		    						if(i==1 && json.getJSONObject(1).optJSONArray("clients")!=null){
			    						btnMainCategory[1].setBackgroundResource(R.drawable.pd_client_bg_enable);
		    							jsonClientCate = json.getJSONObject(1).getJSONArray("clients");
		    						}
	    						}
	    					}
	    					horizontalScrollView1.removeAllViews();
	    					horizontalScrollView1.addView(horScrViewLineLayout1);	

	    					
	    					if(json.getJSONObject(1).optJSONArray("clients")!=null){
	    						mainClientCategoryList(indexBtnMainCat1, jsonClientCate);
	    					}
	    				} else if(strFlag2.equals("mainClientCategory")){
	    					
	    					if(json.getJSONObject(indexBtnMainCat2).optJSONArray("clients")!=null){
	    						JSONArray jsonClientCate2 = json.getJSONObject(indexBtnMainCat2).getJSONArray("clients");	    						
	    						mainClientCategoryList(indexBtnMainCat2, jsonClientCate2);
								horizontalScrollView1.setVisibility(View.VISIBLE);
								horizontalScrollView2.setVisibility(View.VISIBLE);
								progressBar.setVisibility(View.INVISIBLE);
								//horizontalScrollView3.setVisibility(View.GONE);
	    					}
	    				}
					} catch(Exception e){
						e.printStackTrace();
			    		String errorMsg = className+" | aq.ajax MainCategoriesList callback  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(Products.this, errorMsg);
					}
				}
			});
		}catch(Exception e){
			e.printStackTrace();
    		String errorMsg = className+" | mainCategoriesList  click  |   " +e.getMessage();
			Common.sendCrashWithAQuery(Products.this, errorMsg);
		}
	}
	
	int countForButtons = 0, countForImageViews = 0;
	int subCatID =0;
	@SuppressLint("NewApi") 
	public void mainClientCategoryList(final int indexBtnMainCat3, final JSONArray jsonClientCate3){
		try{
			
			horizontalScrollView2 = (HorizontalScrollView) findViewById(R.id.horizontalScrollView2);
			horizontalScrollView2.setBackgroundResource(R.drawable.pd_client_horiz_bg);
			horScrViewLineLayout2 = (LinearLayout) findViewById(R.id.horScrViewLineLayout2);
			
				
			for(int j=0; j<jsonClientCate3.length(); j++){
				if(jsonClientCate3.getJSONObject(j).optString("clientName")!=null){
					final String catClientUrl = jsonClientCate3.getJSONObject(j).getString("clientLogoURL");	
					final String clientTransLogoURL = jsonClientCate3.getJSONObject(j).getString("clientTransLogoURL");
					if(catClientUrl.equals("") && clientTransLogoURL.equals("")){
						countForButtons++;
					} else {
						countForImageViews++;
					}
				}
			}
			
			horScrViewLineLayout2.removeAllViews();			
			for(int j=0; j<jsonClientCate3.length(); j++){				
				if(jsonClientCate3.getJSONObject(j).optString("clientName")!=null){					
					final int catClientId = jsonClientCate3.getJSONObject(j).getInt("clientId");
					final String catClientName = jsonClientCate3.getJSONObject(j).getString("clientName");
					final String catClientUrl = jsonClientCate3.getJSONObject(j).getString("clientLogoURL");	
					final String clientTransLogoURL = jsonClientCate3.getJSONObject(j).getString("clientTransLogoURL");
					
					
					if(jsonClientCate3.getJSONObject(j).optJSONArray("clientCategories")!=null){						
						JSONArray jsonClientMainSubCate = jsonClientCate3.getJSONObject(j).getJSONArray("clientCategories");						
						subCatID =  Integer.parseInt(jsonClientMainSubCate.getJSONObject(0).getString("catID"));						
					}
					
					if(catClientUrl.equals("") && clientTransLogoURL.equals("")){						
						indexBtnClientCat = j;
						final int indexBtnClientCatNew = j;
						final Button btnClientCategory = new Button(Products.this);
						btnClientCategory.setId(indexBtnClientCat);
						btnClientCategory.setText(catClientName);
						if(j==0){
							btnClientCategory.setBackgroundResource(R.drawable.pd_client_bg_enable);
						} else {
							btnClientCategory.setBackgroundResource(R.drawable.pd_client_horiz_bg);
						}
						btnClientCategory.setWidth((int) (0.334 * Common.sessionDeviceWidth));
						btnClientCategory.setHeight((int) (0.0614 * Common.sessionDeviceHeight));
						btnClientCategory.setTextSize((float)(0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						btnClientCategory.setTypeface(null, Typeface.BOLD);
						
						horScrViewLineLayout2.addView(btnClientCategory);
						btnClientCategory.setOnClickListener(new OnClickListener() {
							@Override
							public void onClick(View v) {								
								try{									
									if(jsonClientCate3.getJSONObject(indexBtnClientCatNew).optJSONArray("clientCategories")!=null){
										flagForBackButton = "mainClientCategory";
			    						JSONArray jsonClientSubCate = jsonClientCate3.getJSONObject(indexBtnClientCatNew).getJSONArray("clientCategories");
										
			    						horizontalScrollView2.removeView(btnClientCategory);
										mainClientSubCategoryListWithDynamically(indexBtnClientCatNew, jsonClientSubCate, R.id.horizontalScrollView2, catClientId, R.id.horScrViewLineLayout2,"sub");
										
										subClientCatIdArrays.clear();
										JSONArray jsonClientSubSubSubCate = jsonClientSubCate.getJSONObject(0).getJSONArray("subCat");
										JSONArray ja = new JSONArray();
										
										for(int l=0; l<jsonClientSubSubSubCate.length(); l++){
											
											if(Integer.parseInt(jsonClientSubSubSubCate.getJSONObject(l).getString("prodCount")) >0){												
												//ja.put(jsonClientSubSubSubCate.getJSONObject(l));
												subClientCatIdArrays.add(""+jsonClientSubSubSubCate.getJSONObject(l).getInt("catID"));
											}										
											
										}
										clientBasedProductsInfo(catClientId, "main", subCatID, subClientCatIdArrays);
										
										horizontalScrollView1.setVisibility(View.GONE);
										horizontalScrollView2.setVisibility(View.VISIBLE);
										
										for(int s=0; s<=indexBtnClientCat; s++){											
											if(s==indexBtnClientCatNew){
												v.findViewById(btnClientCategory.getId()).setBackgroundResource(R.drawable.pd_client_bg_enable);
											}else{
												horScrViewLineLayout2.findViewById(s).setBackgroundResource(R.drawable.pd_client_horiz_bg);
											}
										}
									} else {
										v.findViewById(btnClientCategory.getId()).setBackgroundResource(R.drawable.pd_client_bg_enable);
										fancyCoverFlow.setVisibility(View.INVISIBLE);
									}
								} catch(Exception e){
									e.printStackTrace();
						    		String errorMsg = className+" | btnClientCategory  click  |   " +e.getMessage();
									Common.sendCrashWithAQuery(Products.this, errorMsg);
								}
							}
						});
						countForButtons = 0;
						//horScrViewLineLayout2.removeView(btnClientCategory);
					} else {						
						indexBtnClientCat = j;

						Boolean imageFlag = true;
						Bitmap clientLogoBmp = null;
						if(clientTransLogoURL.equals("")){
							clientLogoBmp = aq.getCachedImage(catClientUrl);
							if(clientLogoBmp==null){
								boolean bResponse = Common.ServerFileexists(clientTransLogoURL);
								  if (bResponse==true)
								   {
									URL url1 = new URL(catClientUrl.replaceAll(" ", "%20"));   
									clientLogoBmp = BitmapFactory.decodeStream(url1.openStream());
				                    aq.cache(catClientUrl, 14400000);
								   }else{
								    	imageFlag = false;
								    }
								//clientLogoBmp = aq.getCachedImage(catClientUrl);
							}
						} else {
							clientLogoBmp = aq.getCachedImage(clientTransLogoURL);
							if(clientLogoBmp==null){
								 boolean bResponse = Common.ServerFileexists(clientTransLogoURL);
								  if (bResponse==true)
								   {
								    	URL url1 = new URL(clientTransLogoURL.replaceAll(" ", "%20"));   
								    	clientLogoBmp = BitmapFactory.decodeStream(url1.openStream());								
								    	aq.cache(catClientUrl, 14400000);
								    }else{
								    	imageFlag = false;
								    }
								//clientLogoBmp = aq.getCachedImage(catClientUrl);
							}
						}
						
						
						
						if(imageFlag){
							
							final int indexImgClientCatNew = j;
							final ImageView imgvClientCategory = new ImageView(Products.this);
							imgvClientCategory.setId(indexBtnClientCat);
							imgvClientCategory.setLayoutParams(new LayoutParams((int) (0.334 * Common.sessionDeviceWidth), (int) (0.0614 * Common.sessionDeviceHeight)));
							
							imgvClientCategory.setImageBitmap(clientLogoBmp);
						
							if(j==0){
								imgvClientCategory.setBackgroundResource(R.drawable.pd_client_bg_enable);
							} else {
								imgvClientCategory.setBackgroundResource(R.drawable.pd_client_horiz_bg);
							}
						
						horScrViewLineLayout2.addView(imgvClientCategory);
						imgvClientCategory.setOnClickListener(new OnClickListener() {
							@Override
							public void onClick(View v) {
								try{
									
									if(jsonClientCate3.getJSONObject(indexImgClientCatNew).optJSONArray("clientCategories")!=null){
										flagForBackButton = "mainClientCategory";
			    						JSONArray jsonClientSubCate = jsonClientCate3.getJSONObject(indexImgClientCatNew).getJSONArray("clientCategories");
									
			    						horizontalScrollView2.removeView(imgvClientCategory);
										mainClientSubCategoryListWithDynamically(indexImgClientCatNew, jsonClientSubCate, R.id.horizontalScrollView2, catClientId, R.id.horScrViewLineLayout2,"subImg");


										subClientCatIdArrays.clear();
										JSONArray jsonClientSubSubSubCate = jsonClientSubCate.getJSONObject(0).getJSONArray("subCat");							
										
										for(int l=0; l<jsonClientSubSubSubCate.length(); l++){
											
											if(Integer.parseInt(jsonClientSubSubSubCate.getJSONObject(l).getString("prodCount")) >0){												
												//ja.put(jsonClientSubSubSubCate.getJSONObject(l));
												subClientCatIdArrays.add(""+jsonClientSubSubSubCate.getJSONObject(l).getInt("catID"));
											}										
											
										}
										clientBasedProductsInfo(catClientId, "main", subCatID, subClientCatIdArrays);
										//horizontalScrollView1.removeAllViews();
										horizontalScrollView1.setVisibility(View.GONE);
										horizontalScrollView2.setVisibility(View.VISIBLE);
										//horizontalScrollView3.setVisibility(View.VISIBLE);
										for(int s=0; s<=indexBtnClientCat; s++){
											if(s==indexImgClientCatNew){
												v.findViewById(imgvClientCategory.getId()).setBackgroundResource(R.drawable.pd_client_bg_enable);
											}else{
												horScrViewLineLayout2.findViewById(s).setBackgroundResource(R.drawable.pd_client_horiz_bg);
											}
										}
									} else {
										v.findViewById(imgvClientCategory.getId()).setBackgroundResource(R.drawable.pd_client_bg_enable);
										fancyCoverFlow.setVisibility(View.INVISIBLE);
									}
								} catch(Exception e){
									e.printStackTrace();
						    		String errorMsg = className+" | imgvClientCategory  click  |   " +e.getMessage();
									Common.sendCrashWithAQuery(Products.this, errorMsg);
								}
							}
						});
						countForImageViews = 0;
						}else{
							final int indexBtnClientCatNew = j;
							final Button btnClientCategory = new Button(Products.this);
							btnClientCategory.setId(indexBtnClientCat);
							btnClientCategory.setText(catClientName);
							if(j==0){
								btnClientCategory.setBackgroundResource(R.drawable.pd_client_bg_enable);
							} else {
								btnClientCategory.setBackgroundResource(R.drawable.pd_client_horiz_bg);
							}
							btnClientCategory.setWidth((int) (0.334 * Common.sessionDeviceWidth));
							btnClientCategory.setHeight((int) (0.0614 * Common.sessionDeviceHeight));
							btnClientCategory.setTextSize((float)(0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
							btnClientCategory.setTypeface(null, Typeface.BOLD);
							
							horScrViewLineLayout2.addView(btnClientCategory);
							btnClientCategory.setOnClickListener(new OnClickListener() {
								@Override
								public void onClick(View v) {
									try{
										
										if(jsonClientCate3.getJSONObject(indexBtnClientCatNew).optJSONArray("clientCategories")!=null){
											flagForBackButton = "mainClientCategory";
				    						JSONArray jsonClientSubCate = jsonClientCate3.getJSONObject(indexBtnClientCatNew).getJSONArray("clientCategories");
											
				    						horizontalScrollView2.removeView(btnClientCategory);
											mainClientSubCategoryListWithDynamically(indexBtnClientCatNew, jsonClientSubCate, R.id.horizontalScrollView2, catClientId, R.id.horScrViewLineLayout2,"sub");


											subClientCatIdArrays.clear();
											JSONArray jsonClientSubSubSubCate = jsonClientSubCate.getJSONObject(0).getJSONArray("subCat");
											
											
											for(int l=0; l<jsonClientSubSubSubCate.length(); l++){
												
												if(Integer.parseInt(jsonClientSubSubSubCate.getJSONObject(l).getString("prodCount")) >0){												
													//ja.put(jsonClientSubSubSubCate.getJSONObject(l));
													subClientCatIdArrays.add(""+jsonClientSubSubSubCate.getJSONObject(l).getInt("catID"));
												}										
												
											}
											clientBasedProductsInfo(catClientId, "main", subCatID, subClientCatIdArrays);
											//horizontalScrollView1.removeAllViews();
											horizontalScrollView1.setVisibility(View.GONE);
											horizontalScrollView2.setVisibility(View.VISIBLE);
											//horizontalScrollView3.setVisibility(View.VISIBLE);
											for(int s=0; s<=indexBtnClientCat; s++){
												
												if(s==indexBtnClientCat){
													v.findViewById(btnClientCategory.getId()).setBackgroundResource(R.drawable.pd_client_bg_enable);
												}else{
													horScrViewLineLayout2.findViewById(s).setBackgroundResource(R.drawable.pd_client_horiz_bg);
												}
											}
										} else {
											v.findViewById(btnClientCategory.getId()).setBackgroundResource(R.drawable.pd_client_bg_enable);
											fancyCoverFlow.setVisibility(View.INVISIBLE);
										}
									} catch(Exception e){
										e.printStackTrace();
							    		String errorMsg = className+" | btnClientCategory  click  |   " +e.getMessage();
										Common.sendCrashWithAQuery(Products.this, errorMsg);
									}
								}
							});
							countForButtons = 0;
						}
						//horScrViewLineLayout2.removeView(imgvClientCategory);
					}
					if(j==0){
						clientBasedProductsInfo(catClientId, "main", 0, subClientCatIdArrays);
					}
				}
			}
			horizontalScrollView2.removeAllViews();
			horizontalScrollView2.addView(horScrViewLineLayout2);
		} catch(Exception e){
			e.printStackTrace();
    		String errorMsg = className+" | mainClientCategoryList  method  |   " +e.getMessage();
			Common.sendCrashWithAQuery(Products.this, errorMsg);
		}
	}
	int countForArrLength = 0;
	String[] prodFinalArray, prodFinalArray2;
	FancyCoverFlow fancyCoverFlow;
	ArrayList<String> prodResArrays, prodResIndexArrays = new ArrayList<String>();
	private void clientBasedProductsInfo(final int catClientId, final String strFlagForClientCarPds, final int clientSubCatId2, final ArrayList<String> subClientCatIdArrays2) {
		// TODO Auto-generated method stub
		try{
			
	        fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlow1);
	        RelativeLayout.LayoutParams rlFancyCoverFlow = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();
	        rlFancyCoverFlow.width = LayoutParams.MATCH_PARENT;
	        rlFancyCoverFlow.height = LayoutParams.WRAP_CONTENT;
	        rlFancyCoverFlow.bottomMargin = (int) (0.103 * Common.sessionDeviceHeight);
	        fancyCoverFlow.setLayoutParams(rlFancyCoverFlow);
	        
	        if(strFlagForClientCarPds.equals("main") || strFlagForClientCarPds.equals("sub")|| strFlagForClientCarPds.equals("subGroup") ){
						String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/clientproducts/"+catClientId;
						
						aq.ajax(productUrl, JSONArray.class, new AjaxCallback<JSONArray>(){			
							@Override
							public void callback(String url, JSONArray json, AjaxStatus status) {
								try{
									
				    				if(json!=null){
				    					
				    					prodFinalArray = new String[json.length()];
				    					arrPdInfoForClientProdList = new ArrayList<HashMap<String,String>>();
				    					for(int i=0; i<json.length(); i++){	
					    					if(json.getJSONObject(i).optString("active").equals("1")){						    					
					    						aq.cache(json.getJSONObject(i).getString("image").replaceAll(" ", "%20"), 1440000);
					    						HashMap<String, String> hMapForPdInfo = new HashMap<String, String>();
					    						hMapForPdInfo.put("image", (json.getJSONObject(i).getString("image").replaceAll(" ", "%20")));
					    						hMapForPdInfo.put("catClientId", ""+catClientId);
					    						hMapForPdInfo.put("id", json.getJSONObject(i).getString("id"));
					    						hMapForPdInfo.put("title", json.getJSONObject(i).getString("title"));
					    						hMapForPdInfo.put("isTryOn", json.getJSONObject(i).getString("isTryOn"));
					    						if(json.getJSONObject(i).optJSONObject("publicClient").length()>0){
					    							hMapForPdInfo.put("backgroundColor", json.getJSONObject(i).getJSONObject("publicClient").getString("backgroundColor"));
							    					if(!json.getJSONObject(i).getJSONObject("publicClient").getString("logo").equals("")){
							    						hMapForPdInfo.put("logo", json.getJSONObject(i).getJSONObject("publicClient").getString("logo"));					    						
							    					} 
							    					hMapForPdInfo.put("lightColor", json.getJSONObject(i).getJSONObject("publicClient").getString("lightColor"));
							    					hMapForPdInfo.put("darkColor", json.getJSONObject(i).getJSONObject("publicClient").getString("darkColor"));
							    				}
					    						hMapForPdInfo.put("buyButtonName", json.getJSONObject(i).getString("buyButtonName"));
					    						hMapForPdInfo.put("price", json.getJSONObject(i).getString("price"));
					    						hMapForPdInfo.put("name", json.getJSONObject(i).getJSONObject("publicClient").getString("name"));
					    						hMapForPdInfo.put("catId", json.getJSONObject(i).getString("catId"));
					    						hMapForPdInfo.put("url", json.getJSONObject(i).getString("url"));
					    						hMapForPdInfo.put("isSeeMoreCart",json.getJSONObject(i).getString("isSeeMoreCart"));
					    						arrPdInfoForClientProdList.add(hMapForPdInfo);	    						
					    						
					    					if(clientSubCatId2 ==0){	
					    						prodResArrays = new ArrayList<String>();
						    					prodResArrays.add(json.getJSONObject(i).getString("image").replaceAll(" ", "%20"));
						    					prodResArrays.add(""+catClientId);
						    					prodResArrays.add(json.getJSONObject(i).getString("id"));
						    					prodResArrays.add(json.getJSONObject(i).getString("title"));
						    					prodResArrays.add("0");// closet selection status value
						    					prodResArrays.add(json.getJSONObject(i).getString("isTryOn"));
						    					if(json.getJSONObject(i).optJSONObject("publicClient").length()>0){
							    					prodResArrays.add(json.getJSONObject(i).getJSONObject("publicClient").getString("backgroundColor"));
							    					
							    					if(!json.getJSONObject(i).getJSONObject("publicClient").getString("logo").equals("")){
							    						prodResArrays.add(json.getJSONObject(i).getJSONObject("publicClient").getString("logo"));
							    					} else{
							    						prodResArrays.add("");
							    					}
							    					prodResArrays.add(json.getJSONObject(i).getJSONObject("publicClient").getString("lightColor"));
							    					prodResArrays.add(json.getJSONObject(i).getJSONObject("publicClient").getString("darkColor"));
						    					}
						    					prodResArrays.add(json.getJSONObject(i).getString("buyButtonName"));
						    					prodResArrays.add(json.getJSONObject(i).getString("price"));
						    					prodResArrays.add(json.getJSONObject(i).getJSONObject("publicClient").getString("name"));
						    					prodResArrays.add(json.getJSONObject(i).getString("catId"));
						    					prodResArrays.add(json.getJSONObject(i).getString("url"));
						    					prodResArrays.add(json.getJSONObject(i).getString("description"));
						    					prodResArrays.add(json.getJSONObject(i).getString("isSeeMoreCart"));						    					
						    					prodFinalArray[i] = prodResArrays.toString();
					    						}
					    						}
					    					
				    					}
				    					if(clientSubCatId2 !=0){

				    			        	if(arrPdInfoForClientProdList.size() > 0){				    			        		
				    			        		int prodFinalArraySize = 0;
				    			        		prodFinalArray = new String[prodFinalArraySize];
				    			        		for(int i=0;i<arrPdInfoForClientProdList.size();i++){
				    			        			HashMap<String, String> hashMapListValues = arrPdInfoForClientProdList.get(i);
				    			        			//Log.e("hashMapListValues new ",""+hashMapListValues.toString());
				    			        			int catID = Integer.parseInt(hashMapListValues.get("catId").toString());
				    			        			int catNewClientID = Integer.parseInt(hashMapListValues.get("catClientId").toString());
				    			        			
				    			        			Boolean subFlag=true;				    			        			
				    			        			if(strFlagForClientCarPds.equals("subGroup")){
				    			        				if(catID == clientSubCatId2){
				    			        					 subFlag = true;
				    			        				}else{
				    			        					subFlag = false;
				    			        				}
				    			        					
				    			        				
				    			        			}else{
				    			        				if(subClientCatIdArrays2.size() > 0){					    			        				
					    			        				boolean subExistFlag = subClientCatIdArrays2.contains(""+catID);					    			        				
					    			        				if(subExistFlag)
					    			        					subFlag = true;
					    			        				else
					    			        					subFlag = false;
					    			        			}else{
					    			        				if(catID == clientSubCatId2){
					    			        					 subFlag = true;
					    			        				}else{
					    			        					subFlag = false;
					    			        				}
					    			        			}
				    			        			}
				    			        			
				    			        			if(catClientId == catNewClientID && subFlag == true){	
				    			        				
				    			        				prodFinalArraySize++;
				    			        				prodFinalArray = Arrays.copyOf(prodFinalArray,prodFinalArraySize);
				    			        				prodResArrays = new ArrayList<String>();
				    			    					prodResArrays.add(hashMapListValues.get("image").replaceAll(" ", "%20"));
				    			    					prodResArrays.add(""+catClientId);
				    			    					prodResArrays.add(hashMapListValues.get("id"));
				    			    					prodResArrays.add(hashMapListValues.get("title"));
				    			    					prodResArrays.add("0");// closet selection status value
				    			    					prodResArrays.add(hashMapListValues.get("isTryOn"));
				    			    					prodResArrays.add(hashMapListValues.get("backgroundColor"));
				    				    					if(!hashMapListValues.get("logo").equals("")){
				    				    						prodResArrays.add(hashMapListValues.get("logo"));
				    				    					} else{
				    				    						prodResArrays.add("");
				    				    					}
				    				    					prodResArrays.add(hashMapListValues.get("lightColor"));
				    				    					prodResArrays.add(hashMapListValues.get("darkColor"));
				    			    					
				    			    					prodResArrays.add(hashMapListValues.get("buyButtonName"));
				    			    					prodResArrays.add(hashMapListValues.get("price"));
				    			    					prodResArrays.add(hashMapListValues.get("name"));
				    			    					prodResArrays.add(hashMapListValues.get("catId"));
				    			    					prodResArrays.add(hashMapListValues.get("url"));
				    			    					prodResArrays.add(hashMapListValues.get("description"));
				    			    					prodResArrays.add(hashMapListValues.get("isSeeMoreCart"));
				    			    					prodFinalArray[prodFinalArraySize -1] = prodResArrays.toString();
				    		    					
				    			        			}
				    			        		}
				    			        		
				    			        		if(prodFinalArray.length>0){
				    	    						txtvMessgae.setText("");
				    	    						fancyCoverFlow.setVisibility(View.VISIBLE);
				    	    			        	fancyCoverFlow.setAdapter(renderForCoverFlow(prodFinalArray));
				    	    				        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
				    	    				        fancyCoverFlow.setMaxRotation(45);
				    		    			        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
				    		    						@Override
				    		    						public void onItemClick(AdapterView<?> arg0, View arg1,
				    		    								int arg2, long arg3) {
				    		    							try{
				    			    							if(fancyCoverFlow.getSelectedView()!=null){	
				    			    								fancyCoverFlowOnItemClickForSelectedView(prodFinalArray[arg2]);
				    			    							} else {
				    		    									//txtvProdNotAvail.setVisibility(View.VISIBLE);
				    		    			            		}
				    		    							}catch(Exception e){
				    		    								e.printStackTrace();
				    		    								String errorMsg = className+" | fancyCoverFlow onItemClick |   " +e.getMessage();
				    		    					       	 	Common.sendCrashWithAQuery(Products.this,errorMsg);
				    		    							}
				    		    						}  		    			
				    		    		    		});	
				    	    					} else {
				    	    						fancyCoverFlow.setVisibility(View.INVISIBLE);
				    	    						txtvMessgae.setText("No products available to display");
				    	    					}
				    			        	} else {
				        						fancyCoverFlow.setVisibility(View.INVISIBLE);
				        						txtvMessgae.setText("No products available to display");
				        					}
				    			        
				    					}
				    					if(json.length()>0){
				    						txtvMessgae.setText("");
				    						fancyCoverFlow.setVisibility(View.VISIBLE);
				    			        	fancyCoverFlow.setAdapter(renderForCoverFlow(prodFinalArray));
				    				        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
				    				        fancyCoverFlow.setMaxRotation(45);
					    			        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
					    						@Override
					    						public void onItemClick(AdapterView<?> arg0, View arg1,
					    								int arg2, long arg3) {
					    							try{
						    							if(fancyCoverFlow.getSelectedView()!=null){	
						    								fancyCoverFlowOnItemClickForSelectedView(prodFinalArray[arg2]);
						    							} else {
					    									//txtvProdNotAvail.setVisibility(View.VISIBLE);
					    			            		}
					    							}catch(Exception e){
					    								e.printStackTrace();
					    								String errorMsg = className+" | fancyCoverFlow onItemClick |   " +e.getMessage();
					    					       	 	Common.sendCrashWithAQuery(Products.this,errorMsg);
					    							}
					    						}  		    			
					    		    		});	
				    					} else {
				    						fancyCoverFlow.setVisibility(View.INVISIBLE);
				    						txtvMessgae.setText("No products available to display");
				    					}
				    				} else {
			    						fancyCoverFlow.setVisibility(View.INVISIBLE);
			    						txtvMessgae.setText("No products available to display");
			    					}
								}catch(Exception e){
									e.printStackTrace();
						    		String errorMsg = className+" | aq.ajax ClientProducts callback  click  |   " +e.getMessage();
									Common.sendCrashWithAQuery(Products.this, errorMsg);
								}
							}
						});
			        
				
	        } else {/*
				Log.e("prodFinalArray.length", ""+prodFinalArray.length+" "+strFlagForClientCarPds+" "+subClientCatIdArrays2);
				Log.e("prodResIndexArrays.size", ""+prodResIndexArrays.size());
				
				if(prodFinalArray.length>0){	
					for(int p=0; p<prodFinalArray.length; p++){
						if(strFlagForClientCarPds.equals("subGroup")){
							Log.e("subClientCatIdArrays2", ""+subClientCatIdArrays2.size()+" "+subClientCatIdArrays2);
							for(int a=0; a<subClientCatIdArrays2.size(); a++){
								if(prodFinalArray[p].lastIndexOf(", "+subClientCatIdArrays2.get(a)+"]")!=-1){
									prodResIndexArrays.add(""+p);							
								}
							}
						} else {
							if(prodFinalArray[p].lastIndexOf(", "+clientSubCatId2+"]")!=-1){
								prodResIndexArrays.add(""+p);							
							}
						}
					}
					Log.e("prodResIndexArrays", ""+prodResIndexArrays.size()+" "+prodResIndexArrays);
					HashSet<String> hashSet = new HashSet<String>();
					hashSet.addAll(prodResIndexArrays);
					prodResIndexArrays.clear();
					prodResIndexArrays.addAll(hashSet);
					
					Log.e("prodResIndexArrays.size", ""+prodResIndexArrays.size()+" "+prodResIndexArrays);
					if(prodResIndexArrays.size()>0){
						prodFinalArray2 = new String[prodResIndexArrays.size()];
						for(int u=0; u<prodResIndexArrays.size(); u++){
							prodFinalArray2[u] = prodFinalArray[Integer.parseInt(prodResIndexArrays.get(u).toString())];
							Log.e("prodFinalArray2[u]"+u, ""+prodFinalArray2[u]);
						}
						if(prodFinalArray2.length>0){
							fancyCoverFlow.setVisibility(View.VISIBLE);
							fancyCoverFlow.setAdapter(renderForCoverFlow(prodFinalArray2));
					        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
					        fancyCoverFlow.setMaxRotation(45);
					        prodResIndexArrays.clear();
	    			        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
	    						@Override
	    						public void onItemClick(AdapterView<?> arg0, View arg1,
	    								int arg2, long arg3) {
	    							try{
		    							if(fancyCoverFlow.getSelectedView()!=null){	
		    								fancyCoverFlowOnItemClickForSelectedView(prodFinalArray2[arg2]);	
		    							} else {
	    									//txtvProdNotAvail.setVisibility(View.VISIBLE);
	    			            		}
	    							}catch(Exception e){
	    								e.printStackTrace();
	    								String errorMsg = className+" | fancyCoverFlow onitem click |   " +e.getMessage();
	    					       	 	Common.sendCrashWithAQuery(Products.this,errorMsg);
	    							}
	    						}	    		    			
	    		    		});	
						} else {
							fancyCoverFlow.setVisibility(View.INVISIBLE);
					        prodResIndexArrays.clear();
						}
					} else {
						fancyCoverFlow.setVisibility(View.INVISIBLE);
				        prodResIndexArrays.clear();
					}
				} else {
					fancyCoverFlow.setVisibility(View.INVISIBLE);
			        prodResIndexArrays.clear();
				}
	        */}
		}catch(Exception e){
			e.printStackTrace();
    		String errorMsg = className+" | clientBasedProductsInfo  click  |   " +e.getMessage();
			Common.sendCrashWithAQuery(Products.this, errorMsg);
		}
	}

	private void fancyCoverFlowOnItemClickForSelectedView(String strFlagForPdArrays) {
		try{	
			String s ="[";
			String q ="]";
			String w ="";
			String strReplaceSymbol = String.valueOf(strFlagForPdArrays).replace(s, w).replace(q, w);
			
			String[] expClosetArray = strReplaceSymbol.split(",");
			String expBgColorCode = expClosetArray[6].trim();		
			String expClientLogo = expClosetArray[7].trim();
			String expPdButtonName = expClosetArray[10].trim();				
			//Log.e("expClosetArray", ""+expClosetArray);
			Common.sessionClientBgColor = expBgColorCode;
			Common.sessionClientLogo = expClientLogo;
	
			LinearLayout ll = (LinearLayout)fancyCoverFlow.getSelectedView();		
			RelativeLayout ll2 = (RelativeLayout) ll.getChildAt(0);		
			RelativeLayout ll3 = (RelativeLayout) ll2.getChildAt(0);
			ImageView prodImage = (ImageView)ll3.getChildAt(0);
			
			Intent intent;
			intent = new Intent(Products.this, ProductDetails.class);
			intent.putExtra("prodStrArr",  expClosetArray);
			if(getIntent().getExtras() != null){
				shopFlag = getIntent().getStringExtra("shopFlag");
				orderId  = getIntent().getStringExtra("orderId");
				
				intent.putExtra("shopFlag",  shopFlag);
				intent.putExtra("orderId",  orderId);
			}
				
			intent.putExtra("tapOnImage", false);	
			intent.putExtra("pageRedirectFlag", "Products");		
			//intent.putExtra("image", b);		
			intent.putExtra("productId",  ""+prodImage.getTag());
			intent.putExtra("clientId", ""+prodImage.getTag(R.string.clientId));		
			startActivity(intent);
			overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | fancyCoverFlowOnItemClickForSelectedView  |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(Products.this,errorMsg);		
		}
	}	  
    int gridItemLayout = 0;
	private ArrayAdapter<String> renderForCoverFlow(final String[] prodFinalArray2){	    	
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.coverflowitem_products;
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, prodFinalArray2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, gridItemLayout, parent);
					}	
					AQuery aq2 = new AQuery(convertView);						
					if(prodFinalArray2[position]!=null){
						String s ="[";
						String q ="]";
						String w ="";
						
						String strReplaceSymbol = String.valueOf(prodFinalArray2[position]).replace(s, w).replace(q, w);					
						String[] expProductsArray = strReplaceSymbol.split(",");
						
						
						
						String expImageUrl = expProductsArray[0].trim();		
						String expClientId = expProductsArray[1].trim();		
						String expProductId = expProductsArray[2].trim();		
						String expProductName = expProductsArray[3].trim();		
						String expProductPrice = expProductsArray[11].trim();		
						String expProductIsTryOn = expProductsArray[5].trim();		
						String expBgColorCode = expProductsArray[6].trim();			
						String expClientLogo = expProductsArray[7].trim();			
						String expLightBgColorCode = expProductsArray[8].trim();			
						String expDarkBgColorCode = expProductsArray[9].trim();		
						//String symbol = new Common().getCurrencySymbol(pdXml1.text("country_languages").toString(), pdXml1.text("country_code_char2").toString());
						
						Bitmap placeholder = aq2.getCachedImage(expImageUrl);
						if(placeholder==null){
							aq2.cache(expImageUrl, 1440000);					
						}
						//Log.e("name n price", ""+expProductName+" "+expProductPrice);
						TextView txtProdName =(TextView) convertView.findViewById(R.id.txtvProdName);
						txtProdName.setText(expProductName);
						RelativeLayout.LayoutParams RlForProdName = (RelativeLayout.LayoutParams) txtProdName.getLayoutParams();
						//RlForProdName.width = (int) (0.467 * Common.sessionDeviceWidth);
						//RlForProdName.height = (int) (0.41 * Common.sessionDeviceHeight);
						RlForProdName.rightMargin = (int) (0.0167 * Common.sessionDeviceWidth);
						txtProdName.setLayoutParams(RlForProdName);
						txtProdName.setTextSize((float) ((0.0334 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));

						TextView txtLblPrice =(TextView) convertView.findViewById(R.id.txtLblPrice);
						txtLblPrice.setTextSize((float) ((0.0334 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
						
						TextView txtProdPrice =(TextView) convertView.findViewById(R.id.txtvPrice);
						txtProdPrice.setText("$"+expProductPrice);
						RelativeLayout.LayoutParams RlForProdPrice = (RelativeLayout.LayoutParams) txtProdPrice.getLayoutParams();
						//RlForProdPrice.width = (int) (0.467 * Common.sessionDeviceWidth);
						//RlForProdPrice.height = (int) (0.41 * Common.sessionDeviceHeight);
						RlForProdPrice.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
						RlForProdPrice.leftMargin = (int) (0.0167 * Common.sessionDeviceWidth);
						txtProdPrice.setLayoutParams(RlForProdPrice);
						txtProdPrice.setTextSize((float) ((0.0334 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
						
						ImageView img =(ImageView) convertView.findViewById(R.id.coverFlowImage);						
						img.setImageBitmap(placeholder);
						img.setTag(expProductId);
						img.setTag(R.string.productId, expProductId);
						img.setTag(R.string.imageUrl, expImageUrl);	
						img.setTag(R.string.clientId, expClientId);	
	
						RelativeLayout coverflowLlayout1 = (RelativeLayout) convertView.findViewById(R.id.coverflowLlayoutImage);
						LinearLayout.LayoutParams llpForLl = (LinearLayout.LayoutParams) coverflowLlayout1.getLayoutParams();
						llpForLl.width = (int) (0.7 * Common.sessionDeviceWidth);
						llpForLl.height = (int) (0.4611 * Common.sessionDeviceHeight);
						coverflowLlayout1.setLayoutParams(llpForLl);
						//new Common().gradientDrawableCorners(Products.this, null, coverflowLlayout1, 0.0334, 0.0167);
						
						RelativeLayout bigImageWithName = (RelativeLayout) convertView.findViewById(R.id.bigImageWithName);
						RelativeLayout.LayoutParams RlForBigImageRl = (RelativeLayout.LayoutParams) bigImageWithName.getLayoutParams();
						RlForBigImageRl.width = (int) (0.667 * Common.sessionDeviceWidth);
						RlForBigImageRl.height = (int) (0.41 * Common.sessionDeviceHeight);
						RlForBigImageRl.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
						bigImageWithName.setLayoutParams(RlForBigImageRl);
						
						RelativeLayout.LayoutParams llForCoverFlowImg1 = (RelativeLayout.LayoutParams) img.getLayoutParams();						
						llForCoverFlowImg1.width = LayoutParams.MATCH_PARENT;
						llForCoverFlowImg1.height =  (int) (0.404 * Common.sessionDeviceHeight);;
						img.setLayoutParams(llForCoverFlowImg1);
	
						/*Button btnSeeItLive = (Button) convertView.findViewById(R.id.btnSeeItLive);
						new Common().btnForSeeItLiveWithAllColors(
								Products.this, btnSeeItLive, "relative",
								"width", "products", expProductId, expClientId,
								Integer.parseInt(expProductIsTryOn),
								expBgColorCode, expLightBgColorCode,
								expDarkBgColorCode);*/
					}						
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForCoverFlow  |   " +e.getMessage();
			       	Common.sendCrashWithAQuery(Products.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		//aq.id(R.id.grid_view).adapter(aa);
		return aa;
	}	


	ArrayList<String> subClientCatIdArrays = new ArrayList<String>();
	String strFlagForPds = "F", strFlagForSubCats = "F";
	int subGrpHorScrViewId = 0;
	public void mainClientSubCategoryListWithDynamically(int indexBtnClientCat3, final JSONArray jsonClientSubCate2, int resIdForHorizScrView, final int catClientId2, int resIdForHorizLinearLayout, String flag) {		
		try{
			final int resIdForHorizScrViewNew = (resIdForHorizScrView+indexBtnClientCat3+1);
			final int resIdForLinearLayoutNew = (resIdForHorizLinearLayout+20+indexBtnClientCat3);
			
			RelativeLayout mainRlayout = (RelativeLayout) findViewById(R.id.bgRelativeLayout);
			if(flag.equals("subGrp"))
			{
				subGrpHorScrViewId = resIdForHorizScrViewNew;
			}
			dynamicHorizScrView = new HorizontalScrollView(getApplicationContext());
			dynamicHorizScrView.setId(resIdForHorizScrViewNew);
			dynamicHorizScrView.setLayoutParams(new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT));
			mainRlayout.addView(dynamicHorizScrView);
			RelativeLayout.LayoutParams rlpForLlHorizScr = (RelativeLayout.LayoutParams) dynamicHorizScrView.getLayoutParams();
			rlpForLlHorizScr.addRule(RelativeLayout.BELOW, resIdForHorizScrView);
			dynamicHorizScrView.setLayoutParams(rlpForLlHorizScr);
			dynamicHorizScrView.setBackgroundResource(R.drawable.pd_client_horiz_bg);
			
			llForHSV = new LinearLayout(getApplicationContext());
			llForHSV.setId(resIdForLinearLayoutNew);
			llForHSV.setLayoutParams(new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT));
			llForHSV.setOrientation(LinearLayout.HORIZONTAL);
			llForHSV.setScrollContainer(true);
			dynamicHorizScrView.addView(llForHSV);

			//Log.e("checkvalues", indexBtnClientCat3+" len "+jsonClientSubCate2.length()+" "+jsonClientSubCate2);			
			final Button[] btnClientSubCategory = new Button[jsonClientSubCate2.length()];
			dynamicHorizScrView.removeAllViews();
			llForHSV.removeAllViews();
			for(int k=0; k<jsonClientSubCate2.length(); k++){				
				if(jsonClientSubCate2.getJSONObject(k).optString("catName")!=null){
					indexBtnClientSubCatNew = k;
					final int indexBtnClientSubCat = k;	
					final int clientSubCatId2 = jsonClientSubCate2.getJSONObject(k).getInt("catID");
					final String cleintSubCatName = jsonClientSubCate2.getJSONObject(k).getString("catName");
					
					btnClientSubCategory[indexBtnClientSubCat] = new Button(Products.this);
					btnClientSubCategory[indexBtnClientSubCat].setId(indexBtnClientSubCat);
					btnClientSubCategory[indexBtnClientSubCat].setText(cleintSubCatName);
					btnClientSubCategory[indexBtnClientSubCat].setWidth((int) (0.334 * Common.sessionDeviceWidth));
					btnClientSubCategory[indexBtnClientSubCat].setHeight((int) (0.0614 * Common.sessionDeviceHeight));
					btnClientSubCategory[indexBtnClientSubCat].setTextSize((float)(0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
					btnClientSubCategory[indexBtnClientSubCat].setTypeface(null, Typeface.BOLD);
					btnClientSubCategory[indexBtnClientSubCat].setBackgroundResource(R.drawable.pd_client_horiz_bg);
					btnClientSubCategory[0].setBackgroundResource(R.drawable.pd_client_bg_enable);
					//llForHSV.removeAllViews();
					btnClientSubCategory[indexBtnClientSubCat].setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View arg0) {							
							try{
								if(llForHSV.getId()<resIdForLinearLayoutNew){
									llForHSV.removeView(btnClientSubCategory[resIdForLinearLayoutNew]);									
								}
								flagForBackButton = ""+resIdForHorizScrViewNew;
								horizontalScrollView1.setVisibility(View.GONE);
								horizontalScrollView2.setVisibility(View.GONE);
								
								if(jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optString("prodCount").equals("0") && 
										jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optJSONArray("subCat").length()==0){
									strFlagForPds = "F";
									strFlagForSubCats = "F";
								} else if(!jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optString("prodCount").equals("0") && 
										jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optJSONArray("subCat").length()==0){
									strFlagForPds = "T";
									strFlagForSubCats = "F";
								} else if(jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optString("prodCount").equals("0") && 
										jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optJSONArray("subCat").length()>0){
									strFlagForPds = "F";
									strFlagForSubCats = "T";
								} else if(!jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optString("prodCount").equals("0") && 
										jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).optJSONArray("subCat").length()>0){
									strFlagForPds = "T";
									strFlagForSubCats = "T";
								}
								
								for(int s=0; s<jsonClientSubCate2.length(); s++){
									
									//Products.this.findViewById(s).setBackgroundResource(R.drawable.pd_client_horiz_bg);
									if(s==indexBtnClientSubCat){
										btnClientSubCategory[indexBtnClientSubCat].setBackgroundResource(R.drawable.pd_client_bg_enable);
									}else{
										btnClientSubCategory[s].setBackgroundResource(R.drawable.pd_client_horiz_bg);
									}
								}
								
								if(strFlagForPds.equals("F") && strFlagForSubCats.equals("F")){
									//Log.e("condition 1", ""+strFlagForPds+" "+strFlagForSubCats+" "+dynamicHorizScrView.getId());
									Products.this.findViewById(dynamicHorizScrView.getId()).setVisibility(View.INVISIBLE);
									fancyCoverFlow.setVisibility(View.INVISIBLE);
									//llForHSV.removeAllViews();
									//dynamicHorizScrView.removeAllViews();
								} else if(strFlagForPds.equals("T") && strFlagForSubCats.equals("F")){									
									//if(subGrpHorScrViewId!=0)
									//if(subGrpHorScrViewId!=0 &&  subGrpHorScrViewId != resIdForHorizScrViewNew)
									//	Products.this.findViewById(subGrpHorScrViewId).setVisibility(View.INVISIBLE);
																		 
									 int parentId = ((HorizontalScrollView) btnClientSubCategory[indexBtnClientSubCat].getParent().getParent()).getId();
									
									 if(subGrpHorScrViewId > resIdForHorizScrViewNew){										
										 RelativeLayout mainRlayout = (RelativeLayout) findViewById(R.id.bgRelativeLayout);
										// Products.this.findViewById(subGrpHorScrViewId).setVisibility(View.INVISIBLE);										 
										 HorizontalScrollView subGrpHorScrView = (HorizontalScrollView)findViewById(subGrpHorScrViewId);
										 mainRlayout.removeView(subGrpHorScrView);
									 }
									
									subClientCatIdArrays.clear();
									clientBasedProductsInfo(catClientId2, "sub", clientSubCatId2, subClientCatIdArrays);
									
								}else if(strFlagForPds.equals("F") && strFlagForSubCats.equals("T")){									
									if(subGrpHorScrViewId > resIdForHorizScrViewNew){										
										 RelativeLayout mainRlayout = (RelativeLayout) findViewById(R.id.bgRelativeLayout);
										// Products.this.findViewById(subGrpHorScrViewId).setVisibility(View.INVISIBLE);										 
										 HorizontalScrollView subGrpHorScrView = (HorizontalScrollView)findViewById(subGrpHorScrViewId);
										 mainRlayout.removeView(subGrpHorScrView);
									 }
									JSONArray jsonClientSubSubSubCate = jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).getJSONArray("subCat");
									
									if(jsonClientSubSubSubCate.length()>0){
										JSONArray ja = new JSONArray();
										for(int l=0;l<jsonClientSubSubSubCate.length();l++){											
											if(Integer.parseInt(jsonClientSubSubSubCate.getJSONObject(l).getString("prodCount")) >0){												
												ja.put(jsonClientSubSubSubCate.getJSONObject(l));
											}
										}
										mainClientSubCategoryListWithDynamically(indexBtnClientSubCat, ja, resIdForHorizScrViewNew, catClientId2, resIdForLinearLayoutNew,"subGrp");
										subClientCatIdArrays.clear();
										for(int l=0; l<jsonClientSubSubSubCate.length(); l++){
											subClientCatIdArrays.add(""+jsonClientSubSubSubCate.getJSONObject(l).getInt("catID"));
										}
										
										clientBasedProductsInfo(catClientId2, "subGroup", jsonClientSubSubSubCate.getJSONObject(0).getInt("catID"), subClientCatIdArrays);
										//llForHSV.removeAllViews();
									}
								} else if(strFlagForPds.equals("T") && strFlagForSubCats.equals("T")){
									 if(subGrpHorScrViewId > resIdForHorizScrViewNew){
										 RelativeLayout mainRlayout = (RelativeLayout) findViewById(R.id.bgRelativeLayout);
										// Products.this.findViewById(subGrpHorScrViewId).setVisibility(View.INVISIBLE);										 
										 HorizontalScrollView subGrpHorScrView = (HorizontalScrollView)findViewById(subGrpHorScrViewId);
										 mainRlayout.removeView(subGrpHorScrView);
									 }
									
									JSONArray jsonClientSubSubSubCate = jsonClientSubCate2.getJSONObject(indexBtnClientSubCat).getJSONArray("subCat");
									if(jsonClientSubSubSubCate.length()>0){
										JSONArray ja = new JSONArray();
										for(int l=0;l<jsonClientSubSubSubCate.length();l++){											
											if(Integer.parseInt(jsonClientSubSubSubCate.getJSONObject(l).getString("prodCount")) >0){												
												ja.put(jsonClientSubSubSubCate.getJSONObject(l));
											}
										}
										mainClientSubCategoryListWithDynamically(indexBtnClientSubCat, ja, resIdForHorizScrViewNew, catClientId2, resIdForLinearLayoutNew,"subGrp");
										//clientBasedProductsInfo(catClientId2, "sub", clientSubCatId2);
										subClientCatIdArrays.clear();
										for(int l=0; l<jsonClientSubSubSubCate.length(); l++){
											//clientBasedProductsInfo(catClientId2, "sub", jsonClientSubSubSubCate.getJSONObject(l).getInt("catID"));
											subClientCatIdArrays.add(""+jsonClientSubSubSubCate.getJSONObject(l).getInt("catID"));
										}
										
										clientBasedProductsInfo(catClientId2, "subGroup", clientSubCatId2, subClientCatIdArrays);
										//llForHSV.removeAllViews();
									}
								}
								
							} catch(Exception e){
								e.printStackTrace();
					    		String errorMsg = className+" | btnClientSubCategory  click  |   " +e.getMessage();
								Common.sendCrashWithAQuery(Products.this, errorMsg);
							}
						}
					});
					llForHSV.addView(btnClientSubCategory[indexBtnClientSubCat]);
				} 
			}
			dynamicHorizScrView.removeAllViews();
			dynamicHorizScrView.addView(llForHSV);
		
		} catch(Exception e){
			e.printStackTrace();
    		String errorMsg = className+" | mainClientSubCategoryListWithDynamically  click  |   " +e.getMessage();
			Common.sendCrashWithAQuery(Products.this, errorMsg);
		}		
	}
	@Override
	protected void onNewIntent(Intent intent) {
	    super.onNewIntent(intent);
	    overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
	}
}
