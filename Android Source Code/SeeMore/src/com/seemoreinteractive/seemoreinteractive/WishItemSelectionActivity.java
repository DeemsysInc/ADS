package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.Model.ClosetModel;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserCloset;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class WishItemSelectionActivity extends Activity {
	public boolean isBackPressed = false;
	ArrayList<String> arrListForProdImages, arrListForProdIds, arrListForCheckedProdImages, arrListForProdNames, 
	arrListForClientIds, arrListForClosetSeleStatus, arrListForWishListIds;
	String id = "";
	String wishListName, wishListId;
	public Context context = this;
	SessionManager session;
	HashSet<String> arrproductIds =  new HashSet<String>();
	String arrProdID = ""; 
	AQuery aq;
	int alreadyCountFlag = 0;
	int successCountFlag = 0;
	int failedCountFlag = 0;
	ArrayList<String> imagesUrlList;
	public List<Bitmap> arrListForBitmapImages;
	String getProductIdsWithSymbol = "0", getWishListIdsWithSymbol = "0";
	String getProductIdsWithSymbolUnChecked = "0", getWishListIdsWithSymbolUnChecked = "0";
	FileTransaction file; 
	String className = this.getClass().getSimpleName(); 
	 
	@Override
	protected void onCreate(Bundle savedInstanceState){
		try{
			super.onCreate(savedInstanceState);
			setContentView(R.layout.grid_layout);
			arrListForCheckedProdImages = new ArrayList<String>();
			arrListForProdImages = new ArrayList<String>();
			arrListForProdIds = new ArrayList<String>();
			arrListForProdNames = new ArrayList<String>();
			arrListForClientIds = new ArrayList<String>();
			arrListForClosetSeleStatus = new ArrayList<String>();
			arrListForWishListIds = new ArrayList<String>();
			arrListForBitmapImages = new ArrayList<Bitmap>();
			
	    	//aq2 = new AQuery(this);
			Intent prointent = getIntent();	       
    		id = prointent.getStringExtra("loginuserId");
    		wishListId = prointent.getStringExtra("wishListId");
    		wishListName = prointent.getStringExtra("wishListName");
    		Bundle b = prointent.getExtras(); 
    		imagesUrlList = b.getStringArrayList("imagesUrlList");
    		TextView offerLabel = (TextView)findViewById(R.id.offerLabel);
    		offerLabel.setText(wishListName);
    		offerLabel.setTextSize((float) (0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
    		file = new FileTransaction();

       	 	session = new SessionManager(context);	    		
			aq = new AQuery(this);
			if(Common.isNetworkAvailable(this))
			{
			String closetUrl = Constants.ClosetWishlist_Url+"xml/"+id;
	 		getYourClosetResultsFromDBTable(closetUrl, 0);
			}else{
				getYourClosetResultsFromFile();
			}
	 		Log.i("arrListForProdIds ", ""+arrListForWishListIds+" "+arrListForProdIds);
	 		
    		ImageView imgGoBack = (ImageView) findViewById(R.id.go_back);
			RelativeLayout.LayoutParams rlpForImgGoBack = (RelativeLayout.LayoutParams) imgGoBack.getLayoutParams();
			rlpForImgGoBack.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForImgGoBack.height = (int) (0.066 * Common.sessionDeviceHeight);
			imgGoBack.setLayoutParams(rlpForImgGoBack);
    		imgGoBack.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View arg0) {
					try{
						finish();
					}catch(Exception e){
						e.printStackTrace();
					}
				}
			});
    		ImageView imgSave= (ImageView) findViewById(R.id.save_it);
			RelativeLayout.LayoutParams rlpForImgSave = (RelativeLayout.LayoutParams) imgSave.getLayoutParams();
			rlpForImgSave.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForImgSave.height = (int) (0.066 * Common.sessionDeviceHeight);
			imgSave.setLayoutParams(rlpForImgSave);
    		imgSave.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View arg0) {
					// TODO Auto-generated method stub
					try{
						if(Common.isNetworkAvailable(WishItemSelectionActivity.this))
						{
						Log.i("arrListForCheckedProdImages.size() save", ""+arrListForCheckedProdImages.size());
						Log.i("arrListForCheckedProdImages.save ", ""+arrListForCheckedProdImages);
						Log.i("arrListForProdImages.save ", ""+arrListForProdImages);
						if(arrListForCheckedProdImages.size()>0){
							Log.i("arrListForProdIds ", ""+arrListForWishListIds+" "+arrListForProdIds);
							for(int w=0; w<arrListForProdImages.size(); w++){
								Log.i("arrListForProdImages "+w, ""+arrListForCheckedProdImages.contains(arrListForProdImages.get(w)));
								if(arrListForCheckedProdImages.contains(arrListForProdImages.get(w))){
									if(getProductIdsWithSymbol.equals("0")){
										getProductIdsWithSymbol = arrListForProdIds.get(w);
									} else {
										getProductIdsWithSymbol += ","+arrListForProdIds.get(w);
									}
								} else {							
									if(getProductIdsWithSymbolUnChecked.equals("0")){
										getProductIdsWithSymbolUnChecked = arrListForProdIds.get(w);
									} else {
										getProductIdsWithSymbolUnChecked += ","+arrListForProdIds.get(w);
									}
								}
							}
							Log.i("getProductIdsWithSymbol", ""+getProductIdsWithSymbol);
							Log.i("getWishListIdsWithSymbol", ""+getWishListIdsWithSymbol);
							Log.i("getProductIdsWithSymbolUnChecked", ""+getProductIdsWithSymbolUnChecked);
							Log.i("getWishListIdsWithSymbolUnChecked", ""+getWishListIdsWithSymbolUnChecked);
							Log.i("additem url", ""+Constants.AddItemtoWishList_Url+"adding/"+id+"/"+getProductIdsWithSymbol+"/"+
									wishListId+"/"+getProductIdsWithSymbolUnChecked+"/");
							if(!getProductIdsWithSymbol.equals("")){
								Map<String, Object> params = new HashMap<String, Object>();
							    params.put("loggedInUserId", id);
							    params.put("getProductIds", getProductIdsWithSymbol);
							    params.put("wishListIds", wishListId);
							    params.put("getProductIdsUnChecked", getProductIdsWithSymbolUnChecked);
							    params.put("wishListIdsUnChecked", wishListId);
								aq.ajax(Constants.AddItemtoWishList_Url+"adding/", params, XmlDom.class, new AjaxCallback<XmlDom>() {			            			
			            			@Override
									public void callback(String url, XmlDom xml, AjaxStatus status) {
			            				try{
			            					Log.i("XmlDom", ""+xml);
				            				if(xml!=null){
				            					Log.i("url", ""+url);
				    	                    	arrListForCheckedProdImages.clear();
				            					if(xml.text("msg").equals("update")){
				            						//alreadyCountFlag++;
							    					//Toast.makeText(getApplicationContext(), "Product(s) updated successfully to Wish List.", Toast.LENGTH_LONG).show();
							    					getProductIdsWithSymbol = "0";
							    					getProductIdsWithSymbolUnChecked = "0";
							    					Intent intent = new Intent(getApplicationContext(), WishListPage.class);
													intent.putExtra("offerViewActivity", false);
													startActivity(intent);
							    					finish();
							    					overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							    					/*new WishListPage().getWishListResultsFromServerWithXml(Constants.WishList_Url+"/"+id+"/", WishItemSelectionActivity.this);
							    					finish();*/
							    				} else if(xml.text("msg").equals("both")){
				            					//	Toast.makeText(getApplicationContext(), "Product(s) added/updated successfully to Wish List.", Toast.LENGTH_LONG).show();
							    					getProductIdsWithSymbol = "0";
							    					getProductIdsWithSymbolUnChecked = "0";
							    					Intent intent = new Intent(getApplicationContext(), WishListPage.class);
													intent.putExtra("offerViewActivity", false);
													startActivity(intent);
							    					finish();
							    					overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							    					/*new WishListPage().getWishListResultsFromServerWithXml(Constants.WishList_Url+"/"+id+"/", WishItemSelectionActivity.this);
							    					finish();*/
							    				} else if(xml.text("msg").equals("success")){
							    					//Toast.makeText(getApplicationContext(), "Product(s) added successfully to Wish List.", Toast.LENGTH_LONG).show();
							    					getProductIdsWithSymbol = "0";
							    					getProductIdsWithSymbolUnChecked = "0";
							    					Intent intent = new Intent(getApplicationContext(), WishListPage.class);
													intent.putExtra("offerViewActivity", false);
													startActivity(intent);
							    					finish();
													overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							    					/*new WishListPage().getWishListResultsFromServerWithXml(Constants.WishList_Url+"/"+id+"/", WishItemSelectionActivity.this);
							    					finish();*/
							    				} else {
							    					Toast.makeText(getApplicationContext(), "Product(s) adding failed. Please try again!", Toast.LENGTH_LONG).show();
							    					getProductIdsWithSymbol = "0";
							    					getProductIdsWithSymbolUnChecked = "0";
							    					return;
							    				}
				            				}
			            				} catch(Exception e){
			            					e.printStackTrace();
			            				}
			            			}			            			
			            		});	
							}
	                    	arrListForCheckedProdImages.clear();
						} else {
	            			Toast.makeText(aq.getContext(), "Please check atleast one product.", Toast.LENGTH_LONG).show();
	            		}
						}else{
							new Common().instructionBox(WishItemSelectionActivity.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch(Exception e){
						e.printStackTrace();
					}
				}
			});
		}catch(Exception e){
			e.printStackTrace();
			 String errorMsg = className+" | onCreate      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
			 runOnUiThread(new Runnable() {
					@Override
					public void run() {	
						Toast.makeText(getApplicationContext(), "Error: WishItemSelection Activity onCreate.", Toast.LENGTH_LONG).show();
					}
			 });
			
		}
	}

	ArrayList<String> closetResArrays;
	String[] closetFinalArray;
	 List<UserCloset> userCloset;
	private void getYourClosetResultsFromFile() {
		try{
		// TODO Auto-generated method stub
		ClosetModel closetmodel = file.getCloset();
		ProductModel  getProdDetail = file.getProduct();
		if(closetmodel.size() >0){
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
            		closetResArrays.add(chkProdExist.getClosetSelectionStatus());
            		closetResArrays.add(""+chkProdExist.getProdIsTryOn());
            		closetResArrays.add(chkProdExist.getClientBackgroundColor());
            		closetResArrays.add(chkProdExist.getClientLogo());
            		closetResArrays.add(chkProdExist.getClientLightColor());
            		closetResArrays.add(chkProdExist.getClientDarkColor());
            		Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
	    			if(bitmap1==null) {
	    				aq.cache(curveImagesUrl, 144000);
	    			}
	    			arrListForBitmapImages.add(bitmap1);	   
                	closetFinalArray[c] = closetResArrays.toString();
	    			c++;
       			 }
				}		
				if(arrListForBitmapImages.size()>0){
					render(closetFinalArray);	
					
					GridView gridView = (GridView) findViewById(R.id.grid_view);      
					RelativeLayout.LayoutParams rlpForGridView = (RelativeLayout.LayoutParams) gridView.getLayoutParams();
					rlpForGridView.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
					rlpForGridView.height = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
					rlpForGridView.bottomMargin = (int) (0.066 * Common.sessionDeviceHeight);
					gridView.setLayoutParams(rlpForGridView);     	
					// Instance of ImageAdapter Class	
					gridView.setOnItemClickListener(new OnItemClickListener() {
						@Override
						public void onItemClick(AdapterView<?> parent, View v,
								final int position, long id) {
							try{
							ImageView check = (ImageView)v.findViewById(R.id.check);
							ImageView imageThumb = (ImageView)v.findViewById(R.id.image);
							RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
							rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
							rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
							check.setLayoutParams(rlpForImgCheck);
							Log.e("imageThumb.getTag().toString()",imageThumb.getTag().toString());
							if(check.getVisibility()==View.INVISIBLE){
			    				check.setVisibility(View.VISIBLE);    
			    				Log.e("imageThumb.getTag().toString()",imageThumb.getTag().toString());
			    				arrListForCheckedProdImages.add(imageThumb.getTag().toString());			    				  				
							} else {
			    				check.setVisibility(View.INVISIBLE);   
			    				arrListForCheckedProdImages.remove(imageThumb.getTag().toString());			
							}
							Log.i("arrListForCheckedProdImages check", arrListForCheckedProdImages+" ");
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
			 String errorMsg = className+" | getYourClosetResultsFromFile      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
		}
		
	}

	public void getYourClosetResultsFromDBTable(String closetUrl, int closetSelectionStatus) {
		// TODO Auto-generated method stub
		Log.i("closetUrl", ""+closetUrl);
		aq = new AQuery(this);
		aq.ajax(closetUrl+"/"+closetSelectionStatus, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
					Log.i("XmlDom", ""+xml);
    				if(xml!=null){
    					List<XmlDom> closetXmlTag = xml.tags("prodCloset");
    					Log.i("closetXmlTag", ""+closetXmlTag.size()+" "+closetXmlTag.get(0)+" ");
    					int c=0;
    					if(closetXmlTag.size()>0){
    						closetFinalArray = new String[closetXmlTag.size()];
	                    	
	                    	for(XmlDom closetXml: closetXmlTag){
	                    		if(closetXml.tag("pd_id")!=null){
		                    		String curveImagesUrl = closetXml.text("image").toString().replaceAll(" ", "%20");		                    		
					    			/*arrListForProdImages.add(curveImagesUrl);
					    			arrListForProdIds.add(closetXml.text("pd_id").toString());
		    	    				arrListForClientIds.add(closetXml.text("client_id").toString());
		    	    			    arrListForProdNames.add(closetXml.text("pd_name").toString());
		    	    			    arrListForClosetSeleStatus.add(closetXml.text("closet_selection_status").toString());
		    	    			    arrListForWishListIds.add(closetXml.text("wishlist_id").toString());
		    						*
		    						*
		    						*
		    						*/
		                    		closetResArrays = new ArrayList<String>();
		                    		
		                    		closetResArrays.add(curveImagesUrl);
		                    		closetResArrays.add(closetXml.text("client_id").toString());
		                    		closetResArrays.add(closetXml.text("pd_id").toString());
		                    		closetResArrays.add(closetXml.text("pd_name").toString());
		                    		closetResArrays.add(closetXml.text("closet_selection_status").toString());
		                    	//	closetResArrays.add(closetXml.text("wishlist_id").toString());
		                    		Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
		        	    			if(bitmap1==null) {
		        	    				aq.cache(curveImagesUrl, 144000);
		        	    			}
		                        	closetFinalArray[c] = closetResArrays.toString();
		        	    			c++;
		        	    			// arrListForWishListIds.add(closetXml.text("wishlist_id").toString());
		                    		 arrListForProdImages.add(curveImagesUrl);
		                    		 arrListForProdIds.add(closetXml.text("pd_id").toString());
		                    		//get the bitmap for a previously fetched thumbnail	    			
		    		    			/*Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
		    		    			//Log.i("bitmap", ""+bitmap1+" "+curveImagesUrl);
		    		    			if(bitmap1==null) {
		    			    			try {
		    			    				URL url1 = new URL(curveImagesUrl);
		    			    				aq.cache(curveImagesUrl, 14400000);
		    								bitmap1 = BitmapFactory.decodeStream(url1.openStream());
		    							} catch (Exception e) {
		    								// TODO Auto-generated catch block
		    								e.printStackTrace();
		    							}
		    		    			}*/
		    		    			arrListForBitmapImages.add(bitmap1);	                    		
	                    		}
	                    	}
	                    	
	                    	if(arrListForBitmapImages.size()>0){
	    						render(closetFinalArray);	
	    						
	    						GridView gridView = (GridView) findViewById(R.id.grid_view);      
	    						RelativeLayout.LayoutParams rlpForGridView = (RelativeLayout.LayoutParams) gridView.getLayoutParams();
	    						rlpForGridView.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
	    						rlpForGridView.height = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
	    						rlpForGridView.bottomMargin = (int) (0.066 * Common.sessionDeviceHeight);
	    						gridView.setLayoutParams(rlpForGridView);     	
	    						// Instance of ImageAdapter Class	
	    						gridView.setOnItemClickListener(new OnItemClickListener() {
	    							@Override
	    							public void onItemClick(AdapterView<?> parent, View v,
	    									final int position, long id) {
	    								try{
	    								ImageView check = (ImageView)v.findViewById(R.id.check);
	    								ImageView imageThumb = (ImageView)v.findViewById(R.id.image);
	    								RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
	    								rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
	    								rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
	    								check.setLayoutParams(rlpForImgCheck);
	    								//Log.i("check", check.getVisibility()+"=="+View.INVISIBLE);
	    								Log.i("arrListForCheckedProdImages", arrListForCheckedProdImages.size()+" ");
	    								if(check.getVisibility()==View.INVISIBLE){
	    				    				check.setVisibility(View.VISIBLE);    
	    				    				arrListForCheckedProdImages.add(imageThumb.getTag().toString());
	    				    				Log.i("arrListForCheckedProdImages", imageThumb.getTag().toString());
	    				    				//clientProdArrListImages.add(imagesProdIdArrList.get(position));   				
	    								} else {
	    				    				check.setVisibility(View.INVISIBLE);   
	    				    				arrListForCheckedProdImages.remove(imageThumb.getTag().toString());
	    				    				//clientProdArrListImages.remove(imagesProdIdArrList.get(position));  				
	    								}
	    								Log.i("arrListForCheckedProdImages check", arrListForCheckedProdImages+" ");
	    							}catch(Exception e){
	    								e.printStackTrace();
	    							}
	    							}
	    						});							
	    					} 
    					}else {
    						 Toast.makeText(getApplicationContext(), "No items to select",Toast.LENGTH_LONG).show();
    						 finish();
    					}		
    				}
				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | getYourClosetResultsFromDBTable      |   " +e.getMessage();
					 Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
				}
			}			            			
		});	
	}

    int gridItemLayout = 0;
	private void render(final String[] closetFinalArray2){	  
		try{			
		    AQUtility.debug("render setup");
			gridItemLayout = R.layout.griditem;	
			ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, closetFinalArray2){				
				@Override
				public View getView(int position, View convertView, ViewGroup parent) {		
					try{
						if(convertView == null){
							convertView = aq.inflate(convertView, gridItemLayout, parent);
						}			
						
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
							//String expWishlistId = expClosetArray[5].trim();
							
							AQuery aq2 = new AQuery(convertView);				
							Bitmap placeholder = aq2.getCachedImage(expImageUrl);
							if(placeholder==null){
								aq2.cache(expImageUrl, 1440000);					
							}
						
						ImageView img =(ImageView) convertView.findViewById(R.id.image);
						aq2.id(R.id.image).progress(R.id.progressBarGrid).image(expImageUrl, true, true, 0, 0, placeholder, com.androidquery.util.Constants.FADE_IN_NETWORK, 0);
						img.setTag(expImageUrl);		
						RelativeLayout.LayoutParams rlpForImg = (RelativeLayout.LayoutParams) img.getLayoutParams();
						rlpForImg.width = (int) (0.2 * Common.sessionDeviceWidth);
						rlpForImg.height = (int) (0.123 * Common.sessionDeviceHeight);
						img.setLayoutParams(rlpForImg);
						
						ImageView check = (ImageView)convertView.findViewById(R.id.check);
						RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
						rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
						rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
						check.setLayoutParams(rlpForImgCheck);
					/*	Log.i("arrListForWishListIds render", ""+arrListForWishListIds2.get(position));
						Log.i("arrListForCheckedProdImages render", imagesUrlList.size()+" "+arrListForCheckedProdImages.size());
						Log.i("check condition", ""+imagesUrlList.contains(img.getTag().toString()));
					*/	if(!imagesUrlList.contains(img.getTag().toString())){
							check.setVisibility(View.INVISIBLE);
							arrListForCheckedProdImages.remove(img.getTag().toString());					
						}else{
							check.setVisibility(View.VISIBLE);
							if(!arrListForCheckedProdImages.contains(img.getTag().toString()))
								arrListForCheckedProdImages.add(img.getTag().toString());
						}
						}
					} catch (Exception e){
						e.printStackTrace();
					}
					return convertView;					
				}			
			};			
			aq.id(R.id.grid_view).adapter(aa);
		} catch (Exception e){
			e.printStackTrace();
			String errorMsg = className+" | render      |   " +e.getMessage();
			Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
		}
	}
	
	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    // The rest of your onStart() code.
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart      |   " +e.getMessage();
				Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		//The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop      |   " +e.getMessage();
				Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(WishItemSelectionActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(WishItemSelectionActivity.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(WishItemSelectionActivity.this,errorMsg);
			}
		}
}