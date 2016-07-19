package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.os.Build;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.AbsListView;
import android.widget.AbsListView.OnScrollListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.SimpleAdapter;
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

@TargetApi(Build.VERSION_CODES.HONEYCOMB)
public class ByBrands extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;	
	public Boolean alertErrorType = true;
	String getProductId, getProductName, getProductPrice, getClientLogo,
			getClientId, getClientBackgroundImage, getClientBackgroundColor;
	SessionManager session;
	AQuery aq;
	HashSet<String> arrproductIds;
	String arrProdID="",pageRedirectFlag; 
	FancyCoverFlow fancyCoverFlow;
	TextView txtProdNotAvail;
	View lastview =null;
	int lastPos =-1;
	FileTransaction file;
	String className =this.getClass().getSimpleName();
	@SuppressLint("NewApi")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_by_brands);
		try{	
			
			if(getIntent().getExtras()!=null){
				pageRedirectFlag = getIntent().getStringExtra("pageRedirectFlag");
			}
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "By Brands", "");
			
			txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
			txtProdNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);				

	        fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlowBrands);
	        RelativeLayout.LayoutParams rlpForFancyCover = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();
	        rlpForFancyCover.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
	        rlpForFancyCover.height = (int) (0.44 * Common.sessionDeviceHeight);
	        rlpForFancyCover.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
	        fancyCoverFlow.setLayoutParams(rlpForFancyCover);
	        
	        ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
	        imgvBtnCloset.setImageAlpha(0);
	        ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			//imgvBtnShare.setImageAlpha(0);
	        imgvBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{
	            		if(fancyCoverFlow.getSelectedView()!=null){	
							LinearLayout rl = (LinearLayout) fancyCoverFlow.getSelectedView();
							RelativeLayout ll =(RelativeLayout) rl.getChildAt(0);     		      		
	                		ImageView prodImage = (ImageView)ll.getChildAt(0);							
							Intent intent = new Intent(ByBrands.this, ShareActivity.class);   
							intent.putExtra("pageRedirectFlag", "ByBrands");
	    					intent.putExtra("tapOnImage", false);	
	    					intent.putExtra("image", ""+prodImage.getTag(R.string.imageUrl));
	    					intent.putExtra("productId",  ""+prodImage.getTag(R.string.productId));
							intent.putExtra("clientId",""+prodImage.getTag(R.string.clientId));		
							startActivity(intent);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
						} else {
							TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
							txtProdNotAvail.setVisibility(View.VISIBLE);
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnShare click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
					}
				}			
			});
			
	        aq = new AQuery(this);
	        file = new FileTransaction(); 
			// Session class instance
	        session = new SessionManager(context);
	        if(session.isLoggedIn()){
		        // get user data from session	        	
	        	if(Common.isNetworkAvailable(this)){
			        String closetUrl = Constants.Closet_Url+"brands/xml/"+Common.sessionIdForUserLoggedIn;
			        getYourClosetResultsFromDBTable(closetUrl);
		        	txtProdNotAvail.setVisibility(View.INVISIBLE);	            
				}else{
					getYourClosetResultsFromFile();
		        	txtProdNotAvail.setVisibility(View.INVISIBLE);	
				}
	        }
	    	ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);        
	        imgBackButton.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						Intent intent;
			    		if(pageRedirectFlag==null){
			    			intent = new Intent(getApplicationContext(), Closet.class);							
			    		} else if(pageRedirectFlag.equals("Closet")){
			    			intent = new Intent(getApplicationContext(), Closet.class);
			    		} else {
			    			intent = new Intent(getApplicationContext(), Closet.class);
			    		}
			    		startActivity(intent);
			        	finish();
			        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception e) {
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: ByBrands imgBackButton.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgBackButton click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
					}
				}
			});
	
	    	ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);        
	    	imgBtnCart.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						if(fancyCoverFlow.getSelectedView()!=null){	
							LinearLayout rl = (LinearLayout) fancyCoverFlow.getSelectedView();
							RelativeLayout ll =(RelativeLayout) rl.getChildAt(0);		            				            		      		
	                		ImageView prodImage = (ImageView)ll.getChildAt(0);	
							Intent intent = new Intent(ByBrands.this, ProductInfo.class);
							intent.putExtra("tapOnImage", false);											
							intent.putExtra("productId",  ""+prodImage.getTag(R.string.productId));
							intent.putExtra("clientId",""+prodImage.getTag(R.string.clientId));		
							
							startActivity(intent);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
						}
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: ByBrands imgBtnCart.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCart click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
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
		            	} catch (Exception e) {
							Toast.makeText(getApplicationContext(), "Error: ByBrands imgFooterMiddle.", Toast.LENGTH_LONG).show();
							String errorMsg = className+" | imgFooterMiddle click |   " +e.getMessage();
				       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
						}
		            }
		    });

		} catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: ByBrands onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onCreate  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
		}		
	}

	ArrayList<String> closetResArrays;
	String[] closetFinalArray;
	List<UserCloset> userCloset;
	public List<Bitmap> imagesList;
	public ArrayList<String> imagesUrlArrList;
	public ArrayList<String> imagesClientIdArrList;
	public ArrayList<String> ClientArrList;
	
	private void getYourClosetResultsFromFile() {
		try{			
			ClosetModel closetmodel = file.getCloset();
			ProductModel  getProdDetail = file.getProduct();
			
			if(closetmodel.size() >0){
					closetFinalArray = new String[closetmodel.size()];
					userCloset = closetmodel.getProductsByBrand();
					ClientArrList = new ArrayList<String>();
					ArrayList<String> ClientIDArrList = new ArrayList<String>();
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
		                		closetResArrays.add(chkProdExist.getClientName());
		                		closetResArrays.add(""+chkProdExist.getProdIsTryOn());
		                		closetResArrays.add(chkProdExist.getClientBackgroundColor());
		                		closetResArrays.add(chkProdExist.getClientLogo());
		                		closetResArrays.add(chkProdExist.getClientLightColor());
		                		closetResArrays.add(chkProdExist.getClientDarkColor());
	                		
	                		if(ClientIDArrList.contains(""+chkProdExist.getClientId())){		                		
    							if(ClientArrList.size() > 0){
    								for(int k=0;k<ClientArrList.size();k++){
    									String[] id = ClientArrList.get(k).split(",");
    									if(chkProdExist.getClientId() == Integer.parseInt(id[0])){
    										String pid = id[2];
    										ClientArrList.remove(k);										
    										ClientArrList.add(chkProdExist.getClientId()+","+chkProdExist.getClientName()+","+(pid+"|"+chkProdExist.getProductId()));
    									}
    								}
    							}else{
    								ClientArrList.add(chkProdExist.getClientId()+","+chkProdExist.getClientName()+","+chkProdExist.getProductId());
    							}
    						}else{    							
    							ClientIDArrList.add(""+chkProdExist.getClientId());
    							ClientArrList.add(chkProdExist.getClientId()+","+chkProdExist.getClientName()+","+chkProdExist.getProductId());
    						}
    						//get the bitmap for a previously fetched thumbnail	  
	                		aq = new AQuery(ByBrands.this);
    		    			Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
    		    			if(bitmap1==null) {
			    				aq.cache(curveImagesUrl, 14400000);		    			    			
    		    			}
    		    			closetFinalArray[c] = closetResArrays.toString();
    		    			c++;
							}
						}
						 ArrayList<HashMap<String, String>> aList = new ArrayList<HashMap<String,String>>(); 
	    			        if(ClientArrList.size() > 0){
								for(int k=0;k<ClientArrList.size();k++){
									String[] clientStrArr =ClientArrList.get(k).split(",");
									HashMap<String, String> map = new HashMap<String, String>();
				    				map.put("Id", clientStrArr[2]);
									map.put("Name", clientStrArr[1]);
						            aList.add(map);
								}
							}						
						
							String[] from = { "Id","Name"};					        
					        // Ids of views in listview_layout
					        int[] to = { R.id.brandId,R.id.brandName};	
					        
					       final SimpleAdapter adapter = new SimpleAdapter(getApplicationContext(), aList, R.layout.listview_brands_layout, from, to);				

	                       final ListView lvWishLists = (ListView) findViewById(R.id.listViewBrands);						 	
						   lvWishLists.setAdapter(adapter);
						   
						   lvWishLists.setOnItemClickListener(new OnItemClickListener() {
								@Override
								public void onItemClick(AdapterView<?> arg0,
										View arg1, final int arg2, long arg3) {
									try{
											if(lastview !=arg1 ){
												if(lastview != null){
												 ImageView lastarrowImage = (ImageView) lastview.findViewById(R.id.imgarrow);
												 lastarrowImage.setVisibility(View.INVISIBLE);
												}
												lastview = arg1;
												lastPos = arg2;
										        ImageView arrowImage = (ImageView) arg1.findViewById(R.id.imgarrow);
											    arrowImage.setVisibility(View.VISIBLE);
												String bransSelectedId = ((TextView) arg1.findViewById(R.id.brandId)).getText().toString();
												String brandProdId = bransSelectedId.replace("|", ",");
												getYourClosetResultsFromFileByBrand(brandProdId);												
											}
									}catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | getYourClosetResultsFromFile | lvWishLists   | setOnItemClickListener |   " +e.getMessage();
							       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
									}
								}
					       });
						   
						   String[] clientStrArr =ClientArrList.get(0).split(",");
					       String jsonAjaxUrl = Constants.Closet_Url+"bybrands/xml/"+clientStrArr[2].replace("|", ",");
					       getYourClosetResultsFromFileByBrand(clientStrArr[2].replace("|", ","));
					       
						   lvWishLists.setOnScrollListener(new OnScrollListener() {
						        @Override
						        public void onScroll(AbsListView arg0, int arg1, int arg2, int arg3) {
						        	try{
						        		int j= arg1;
						        		for (int i = 0; i < arg2; i++)  {						        			
						                    View v = lvWishLists.getChildAt(i);
						                    if (j == lastPos && v != null){						                    	 
						                    	 ImageView arrowImage = (ImageView) v.findViewById(R.id.imgarrow);
										         arrowImage.setVisibility(View.VISIBLE);
						                    }
						                    else {						                    	 
						                    	 ImageView arrowImage = (ImageView) v.findViewById(R.id.imgarrow);
										         arrowImage.setVisibility(View.INVISIBLE);
						                    }		
						                    j++;
									    }
						        	}catch(Exception e){
						        		e.printStackTrace();
						        		String errorMsg = className+" | getYourClosetResultsFromFile | lvWishLists   | setOnScrollListener |   " +e.getMessage();
							       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
						        	}  
						        }
								@Override
								public void onScrollStateChanged(
										AbsListView view, int scrollState) {									
								}
						    });
					}		
			} else {
				TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
				txtProdNotAvail.setVisibility(View.VISIBLE);
			}
			
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | getYourClosetResultsFromFile  |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
			}
			
		
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			if(requestCode == 1){
				if(!Common.isNetworkAvailable(ByBrands.this)){
					if(data != null){
						 String activity=data.getStringExtra("activity");						 
						 if(activity.equals("menu")){
							 new Common().instructionBox(ByBrands.this, R.string.title_case7, R.string.instruction_case7);
						 }
					 }
				}
			}			
		}catch (Exception e){	
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);		
		}
		
	}
	
	private void getYourClosetResultsFromFileByBrand(String pdids) {
		try{
			ClosetModel closetmodel = file.getCloset();			
			if(closetmodel.size() >0){				
				userCloset = closetmodel.getProductByBrands(pdids);
				closetFinalArray = new String[userCloset.size()];
				int c =0;
				if(userCloset != null){
					for ( final UserCloset usercloset : userCloset) {
						closetResArrays = new ArrayList<String>();
						
						ProductModel  getProdDetail = file.getProduct();
						UserProduct chkProdExist = getProdDetail.getUserProductById(usercloset.getProductId());
						if(chkProdExist != null){

               		
                		String curveImagesUrl = chkProdExist.getImageFile().replaceAll(" ", "%20");	
                		closetResArrays.add(curveImagesUrl);
                		closetResArrays.add(""+chkProdExist.getClientId());
                		closetResArrays.add(""+chkProdExist.getProductId());
                		closetResArrays.add(chkProdExist.getProductName());
                		closetResArrays.add(chkProdExist.getClientName());
                		closetResArrays.add(""+chkProdExist.getProdIsTryOn());
                		closetResArrays.add(chkProdExist.getClientBackgroundColor());
                		closetResArrays.add(chkProdExist.getClientLogo());
                		closetResArrays.add(chkProdExist.getClientLightColor());
                		closetResArrays.add(chkProdExist.getClientDarkColor());
                		
                		//get the bitmap for a previously fetched thumbnail	  
                		aq = new AQuery(ByBrands.this);
		    			Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
		    			if(bitmap1==null) {
		    				aq.cache(curveImagesUrl, 14400000);		    			    			
		    			}
		    			closetFinalArray[c] = closetResArrays.toString();
		    			c++;		    			
		    			
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
        								Common.sessionClientBgColor = expClosetArray[6].trim();
        						        Common.sessionClientBackgroundLightColor = expClosetArray[8].trim();
        						        Common.sessionClientBackgroundDarkColor = expClosetArray[9].trim();
        								Common.sessionClientLogo = expClosetArray[7].trim();
        								Common.sessionClientName = expClosetArray[4].trim();
        							
        								LinearLayout rl = (LinearLayout) fancyCoverFlow.getSelectedView();
        								RelativeLayout ll =(RelativeLayout) rl.getChildAt(0);
        			            		ImageView prodImage = (ImageView)ll.getChildAt(0);
        			            		
        			            		BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
    	    							Bitmap bitmap = test.getBitmap();
    	    							ByteArrayOutputStream baos = new ByteArrayOutputStream();
    	    							bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
    	    							byte[] b = baos.toByteArray();
    	    							Intent intent = new Intent(ByBrands.this, ProductInfo.class);
    	    							intent.putExtra("tapOnImage", false);		
    	    							//intent.putExtra("image", b);		
    	    							intent.putExtra("productId",  expClosetArray[2].trim());
    	    							intent.putExtra("clientId", expClosetArray[1].trim());		
    	    							startActivity(intent);
    	    							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
        							}
        							}catch(Exception e){
        								e.printStackTrace();
        							}
    							}
    						});
					}
					}
				} else {
					TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
					txtProdNotAvail.setVisibility(View.VISIBLE);
				}
		}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getYourClosetResultsFromFileByBrand  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
		}
	}

	public void getYourClosetResultsFromWishListTable(String ajaxUrl, String compareTab) {
		try{
		aq = new AQuery(this);
		aq.ajax(ajaxUrl, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
    				if(xml!=null){
    					List<XmlDom> closetXmlTag = xml.tags("prodCloset");
    					closetFinalArray = new String[closetXmlTag.size()];
						int c =0;
                    	for(XmlDom closetXml: closetXmlTag){
                    		if(closetXml.tag("pd_id")!=null){
	                    		String curveImagesUrl = closetXml.text("image").toString().replaceAll(" ", "%20");
	                    			    			   
	                    		closetResArrays = new ArrayList<String>();			    	    			   
	                    		closetResArrays.add(curveImagesUrl);
	                    		closetResArrays.add(closetXml.text("client_id").toString());
	                    		closetResArrays.add(closetXml.text("pd_id").toString());
	                    		closetResArrays.add(closetXml.text("pd_name").toString());
	                    		closetResArrays.add(closetXml.text("client_name").toString());
	                    		closetResArrays.add(closetXml.text("pd_istryon").toString());
	                    		closetResArrays.add(closetXml.text("background_color").toString());
	                    		closetResArrays.add(closetXml.text("client_logo").toString());
	                    		closetResArrays.add(closetXml.text("light_color").toString());
	                    		closetResArrays.add(closetXml.text("dark_color").toString());
	                    		
								aq = new AQuery(ByBrands.this); 			
        		    			Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
        		    			if(bitmap1==null) {
        		    				aq.cache(curveImagesUrl, 14400000);			    		
        		    			}
        		    			closetFinalArray[c] = closetResArrays.toString();
        		    			c++;	        		    		
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
	            								Common.sessionClientBgColor = expClosetArray[6].trim();
	            						        Common.sessionClientBackgroundLightColor = expClosetArray[8].trim();
	            						        Common.sessionClientBackgroundDarkColor = expClosetArray[9].trim();
	            								Common.sessionClientLogo = expClosetArray[7].trim();
	            								Common.sessionClientName = expClosetArray[4].trim();
	            							
	            								
	        	    							Intent intent = new Intent(ByBrands.this, ProductInfo.class);
	        	    							intent.putExtra("tapOnImage", false);		
	        	    							intent.putExtra("productId",  expClosetArray[2].trim());
	        	    							intent.putExtra("clientId", expClosetArray[1].trim());		
	        	    							startActivity(intent);
	        	    							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
	            							}
	        							}catch(Exception e){
	        								e.printStackTrace();
	        							}	        						
	        						}
	        					});	        					
                    	}
	    				} else {
	    					TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
	    					txtProdNotAvail.setVisibility(View.VISIBLE);
	    				}
    				
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | getYourClosetResultsFromWishListTable  |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
				}
			}
		});
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getYourClosetResultsFromWishListTable  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
		}
	}
	
	public void getYourClosetResultsFromDBTable(String closetUrl) {
		try{
		aq = new AQuery(this);
		aq.ajax(closetUrl, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
    				if(xml!=null){
    					List<XmlDom> closetXmlTag = xml.tags("prodCloset");
    					if(closetXmlTag.size()>0){
    						closetFinalArray = new String[closetXmlTag.size()];
    						ClientArrList = new ArrayList<String>();
    						ArrayList<String> ClientIDArrList = new ArrayList<String>();
    						int c =0;
	                    	for(XmlDom closetXml: closetXmlTag){
	                    		if(closetXml.tag("pd_id")!=null){
		                    		String curveImagesUrl = closetXml.text("image").toString().replaceAll(" ", "%20");
		                    		
		                    		closetResArrays = new ArrayList<String>();			    	    			   
		                    		closetResArrays.add(curveImagesUrl);
		                    		closetResArrays.add(closetXml.text("client_id").toString());
		                    		closetResArrays.add(closetXml.text("pd_id").toString());
		                    		closetResArrays.add(closetXml.text("pd_name").toString());
		                    		closetResArrays.add(closetXml.text("client_name").toString());
		                    		closetResArrays.add(closetXml.text("pd_istryon").toString());
		                    		closetResArrays.add(closetXml.text("background_color").toString());
		                    		closetResArrays.add(closetXml.text("client_logo").toString());
		                    		closetResArrays.add(closetXml.text("light_color").toString());
		                    		closetResArrays.add(closetXml.text("dark_color").toString());

									Common.arrOfferIdsForUserAnalytics.add(closetXml.text("pd_id").toString());
		    						if(ClientIDArrList.contains(closetXml.text("client_id").toString())){
		    							if(ClientArrList.size() > 0){
		    								for(int k=0;k<ClientArrList.size();k++){
		    									String[] id = ClientArrList.get(k).split(",");
		    									if(closetXml.text("client_id").toString().equals(id[0])){
		    										String pid = id[2];
		    										ClientArrList.remove(k);										
		    										ClientArrList.add(closetXml.text("client_id").toString()+","+closetXml.text("client_name").toString()+","+(pid+"|"+closetXml.text("pd_id").toString()));
		    									}
		    								}
		    							}else{
		    								ClientArrList.add(closetXml.text("client_id").toString()+","+closetXml.text("client_name").toString()+","+closetXml.text("pd_id").toString());
		    							}
		    						}else{
		    							ClientIDArrList.add(closetXml.text("client_id").toString());
		    							ClientArrList.add(closetXml.text("client_id").toString()+","+closetXml.text("client_name").toString()+","+closetXml.text("pd_id").toString());
		    						}
		    						//get the bitmap for a previously fetched thumbnail	    			
		    		    			Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
		    		    			if(bitmap1==null) {
					    				aq.cache(curveImagesUrl, 14400000);		    			    			
		    		    			}
		    		    			closetFinalArray[c] = closetResArrays.toString();
		    		    			c++;
	                    		} else {
	        						TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
	        						txtProdNotAvail.setVisibility(View.VISIBLE);
	        					}
	                    	}
	                    	
	    			        ArrayList<HashMap<String, String>> aList = new ArrayList<HashMap<String,String>>(); 
	    			        if(ClientArrList.size() > 0){
								for(int k=0;k<ClientArrList.size();k++){
									String[] clientStrArr =ClientArrList.get(k).split(",");
									HashMap<String, String> map = new HashMap<String, String>();
				    				map.put("Id", clientStrArr[2]);
									map.put("Name", clientStrArr[1]);
						            aList.add(map);
								}
							}						
						
							String[] from = { "Id","Name"};			
					        int[] to = { R.id.brandId,R.id.brandName};	
					        
					        final SimpleAdapter adapter = new SimpleAdapter(getApplicationContext(), aList, R.layout.listview_brands_layout, from, to);				

	                    	final ListView lvWishLists = (ListView) findViewById(R.id.listViewBrands);
						    lvWishLists.setAdapter(adapter);
						   
						   String[] clientStrArr =ClientArrList.get(0).split(",");
					       String jsonAjaxUrl = Constants.Closet_Url+"bybrands/xml/"+clientStrArr[2].replace("|", ",");
					       getYourClosetResultsFromWishListTable(jsonAjaxUrl, "bybrand");
					       
					       
						   lvWishLists.setOnItemClickListener(new OnItemClickListener() {
								@Override
								public void onItemClick(AdapterView<?> arg0,
										View arg1, final int arg2, long arg3) {
									try{
										if(lastview !=arg1 ){
											if(lastview != null){
											 ImageView lastarrowImage = (ImageView) lastview.findViewById(R.id.imgarrow);
											 lastarrowImage.setVisibility(View.INVISIBLE);											
											}
											lastview = arg1;
											lastPos = arg2;
									        ImageView arrowImage = (ImageView) arg1.findViewById(R.id.imgarrow);
										    arrowImage.setVisibility(View.VISIBLE);
											String bransSelectedId = ((TextView) arg1.findViewById(R.id.brandId)).getText().toString();
											String brandProdId = bransSelectedId.replace("|", ",");
											String jsonAjaxUrl = Constants.Closet_Url+"bybrands/xml/"+brandProdId;
											getYourClosetResultsFromWishListTable(jsonAjaxUrl, "bybrand");											
										}
									}catch(Exception e){
										e.printStackTrace();
						        		String errorMsg = className+" | getYourClosetResultsFromDBTable | lvWishLists   | setOnItemClickListener |   " +e.getMessage();
							       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
									}
								}
					       });
						   lvWishLists.setOnScrollListener(new OnScrollListener() {
						        @Override
						        public void onScroll(AbsListView arg0, int arg1, int arg2, int arg3) {				          
						        	try{							        	
						        		int j= arg1;
						        		for (int i = 0; i < arg2; i++)  {						        			
						                    View v = lvWishLists.getChildAt(i);
						                    if (j == lastPos && v != null){						                    	 
						                    	 ImageView arrowImage = (ImageView) v.findViewById(R.id.imgarrow);
										         arrowImage.setVisibility(View.VISIBLE);
						                    }
						                    else {						                    	 
						                    	 ImageView arrowImage = (ImageView) v.findViewById(R.id.imgarrow);
										         arrowImage.setVisibility(View.INVISIBLE);
						                    }		
						                    j++;
									    }
						        	}catch(Exception e){
						        		e.printStackTrace();
						        		String errorMsg = className+" | getYourClosetResultsFromDBTable | lvWishLists   | setOnScrollListener |   " +e.getMessage();
							       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
						        	}
						        }
								@Override
								public void onScrollStateChanged(
										AbsListView view, int scrollState) {									
								}
						    });
					       
	    				}
    					String screenName = "/mycloset/brands";
						String productIds = "";
						String offerIds = "";
    					Common.sendJsonWithAQuery(ByBrands.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
    				}
				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | getYourClosetResultsFromDBTable  |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
				}
			}			            			
		});	
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getYourClosetResultsFromDBTable  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
		}
	}	
	
	@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
    	try{
	        if (keyCode == KeyEvent.KEYCODE_BACK) {
	            onBackPressed();
	            isBackPressed = true;
	        }
	        return super.onKeyDown(keyCode, event);
    	} catch (Exception e) {			
			String errorMsg = className+" | onKeyDown  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
			return false;
		}
    }
	
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
    int gridItemLayout = 0;
    private ArrayAdapter<String> renderForCoverFlow(final String[] closetFinalArray2){	    	
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.coverflowitem_bybrands;	
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, closetFinalArray2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, gridItemLayout, parent);
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
						//String expProductName = expClosetArray[3].trim();		
						String expClientName = expClosetArray[4].trim();		
						String expProductIsTryOn = expClosetArray[5].trim();		
						String expBgColorCode = expClosetArray[6].trim();		
						String expClientLogo = expClosetArray[7].trim();
						String expBgLightColor = expClosetArray[8].trim();
						String expBgDarkColor = expClosetArray[9].trim();	
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
						
						ImageView img =(ImageView) convertView.findViewById(R.id.coverFlowImage);
						img.setImageBitmap(placeholder);
						img.setTag(expProductId);	
						img.setTag(R.string.productId, expProductId);	
						img.setTag(R.string.clientId, expClientId);
						img.setTag(R.string.imageUrl,expImageUrl);
						RelativeLayout coverflowLlayout1 = (RelativeLayout) convertView.findViewById(R.id.coverflowLlayoutImage);
						LinearLayout.LayoutParams llpForLl = (LinearLayout.LayoutParams) coverflowLlayout1.getLayoutParams();
						llpForLl.width = (int) (0.667 * Common.sessionDeviceWidth);
						llpForLl.height = (int) (0.4611 * Common.sessionDeviceHeight);
						coverflowLlayout1.setLayoutParams(llpForLl);
						
						RelativeLayout.LayoutParams llForCoverFlowImg1 = (RelativeLayout.LayoutParams) img.getLayoutParams();
						llForCoverFlowImg1.width = LayoutParams.MATCH_PARENT;
						llForCoverFlowImg1.height = LayoutParams.MATCH_PARENT;
						img.setLayoutParams(llForCoverFlowImg1);
	
						Button btnSeeItLive = (Button) convertView.findViewById(R.id.btnSeeItLive);

						new Common().btnForSeeItLiveWithAllColors(ByBrands.this, btnSeeItLive, "relative", "width", "products", 
								expProductId, expClientId, Integer.parseInt(expProductIsTryOn), expBgColorCode, expBgLightColor, 
								expBgDarkColor);
						
						 	Common.sessionClientBgColor = expBgColorCode;
					        Common.sessionClientBackgroundLightColor = expBgLightColor;
					        Common.sessionClientBackgroundDarkColor = expBgDarkColor;
							Common.sessionClientLogo = expClientLogo;
							Common.sessionClientName = expClientName;
							Common.sessionClientId = expClientId;
							
							new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
									ByBrands.this, Common.sessionClientBgColor,
									Common.sessionClientBackgroundLightColor,
									Common.sessionClientBackgroundDarkColor,
									Common.sessionClientLogo, "", "");
					    	
					}
						
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForCoverFlow  |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		//aq.id(R.id.grid_view).adapter(aa);
		return aa;
	}

    @Override
	public void onBackPressed() {
    	try{
			//new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, WishListPage.class, "0");
    		Intent intent;
    		if(pageRedirectFlag==null){
    			intent = new Intent(getApplicationContext(), Closet.class);							
    		} else if(pageRedirectFlag.equals("Closet")){
    			intent = new Intent(getApplicationContext(), Closet.class);
    		} else {
    			intent = new Intent(getApplicationContext(), Closet.class);
    		}
    		startActivity(intent);
        	finish();
        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
	        return;
    	} catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: By Brands onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
		}
    }
	 @Override
	public void onStart() {
		 try{
		    super.onStart();
		    Tracker easyTracker = EasyTracker.getInstance(this);
			easyTracker.set(Fields.SCREEN_NAME, "/mycloset/brands");
			easyTracker.send(MapBuilder
			    .createAppView()
			    .build()
			);
			 String[] segments = new String[1];
			 segments[0] = "By Brands"; 
			 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart  |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
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
	       	 Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
		 }
	}
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ByBrands.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
			}
			
		}
	 
	 @Override
		protected void onResume() 
		{
			try{
				super.onResume();					
				if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(ByBrands.this);			
					Common.isAppBackgrnd = false;
				}
			}catch (Exception e){		
				e.printStackTrace();
				String errorMsg = className+" | onResume | " +e.getMessage();
	        	Common.sendCrashWithAQuery(ByBrands.this,errorMsg);
			}
		}
}
