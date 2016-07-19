package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.text.Html;
import android.text.Spanned;
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
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.facebook.android.Facebook;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Model.UserWishList;
import com.seemoreinteractive.seemoreinteractive.Model.WishListModel;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class ShareMultipleImages extends Activity {
	ImageView image;
	ArrayList<String> checkedArrListImages, checkedArrListImageNames, checkedArrListImageLinks, checkedArrListImageDesc, checkedArrListImagePrice;
	ArrayList<String> imagesArrList, imageNamesArrList, imageLinksArrList, imageProdDescArrList, imageProdPriceArrList;
	AQuery aq;
	SessionManager session;
	String wishListName = "", userFName="", userLName="", wishListId;
	public String getProductName = "null", getClientBackgroundImage = "null", getFinalWebSiteUrl = "null", getProductDesc = "null";
	Context context = this;
	String htmlString = "<img src='seemore'>";
	TextView txtvProdNotAvail;
	Facebook facebook = new Facebook(Constants.FACEBOOK_APP_ID);
	 String className = this.getClass().getSimpleName();
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_share_multiple_images);
		
		try{
		new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);		
		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, "ff2600",
				"ff2600",
				"ff2600",
				Common.sessionClientLogo, "Share", "");
		
		
		txtvProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
		txtvProdNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
		
    	ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);        
        imgBackButton.setOnClickListener(new OnClickListener() {				
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				try{
					Intent prodListPage = new Intent(getApplicationContext(), WishListPage.class);
		        	startActivity(prodListPage);
		        	finish();
		        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				} catch (Exception e) {
					// TODO: handle exception
					e.printStackTrace();
					Toast.makeText(getApplicationContext(), "Error: Share Multiple imgBackButton.", Toast.LENGTH_LONG).show();
					String errorMsg = className+" | imgBackButton    |   " +e.getMessage();
					Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
				}
			}
		});

    	ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);        
    	imgBtnCart.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				try{
					Log.i("backbutton", "back butto for ar");/*
					Intent prodInfo = new Intent(ShareMultipleImages.this, ARDisplayActivity.class);    
					startActivityForResult(prodInfo, 1);
					finish();
					overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);*/
					//new Common().clickingOnBackButtonWithAnimationWithBackPressed(ShareMultipleImages.this, ARDisplayActivity.class, "0");
					Intent intent = new Intent(ShareMultipleImages.this, ARDisplayActivity.class);			
    				//intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
					intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
    				startActivity(intent); // Launch the HomescreenActivity
    				finish(); 
    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
				} catch (Exception e) {
					// TODO: handle exception
					Toast.makeText(getApplicationContext(), "Error: WishListPage imgBtnCart.", Toast.LENGTH_LONG).show();
					String errorMsg = className+" | imgBtnCart    |   " +e.getMessage();
					Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
				}
			}
		});
     
		aq = new AQuery(this);
		checkedArrListImages = new ArrayList<String>();
		checkedArrListImageNames = new ArrayList<String>();
		checkedArrListImageLinks = new ArrayList<String>();
		checkedArrListImageDesc = new ArrayList<String>();
		checkedArrListImagePrice = new ArrayList<String>();
		imagesArrList = new ArrayList<String>();
		imageNamesArrList = new ArrayList<String>();
		imageLinksArrList = new ArrayList<String>();
		imageProdDescArrList = new ArrayList<String>();
		imageProdPriceArrList = new ArrayList<String>();
		GridView gridView = (GridView) findViewById(R.id.shareMultiGridView);
		RelativeLayout.LayoutParams rlpForGridView = (RelativeLayout.LayoutParams) gridView.getLayoutParams();
		rlpForGridView.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
		rlpForGridView.height = (int) (0.8 * Common.sessionDeviceHeight);
		gridView.setLayoutParams(rlpForGridView);
		gridView.setColumnWidth((int) (0.2 * Common.sessionDeviceWidth));
		Intent prointent = getIntent();
		wishListName = prointent.getStringExtra("wishListName");
		wishListId = prointent.getStringExtra("wishListId");
		session = new SessionManager(this);		        
		if(session.isLoggedIn())
        {
	        userFName = Common.sessionIdForUserLoggedFname;	
	        userLName = Common.sessionIdForUserLoggedLname;	
	        //Log.i("userLoggedInId", ""+userLoggedInId);
			Log.i("userFName", ""+userFName+" "+userLName);
			if(Common.isNetworkAvailable(ShareMultipleImages.this))
			{
			Map<String, Object> params = new HashMap<String, Object>();
		    params.put("loggedInUserId", ""+Common.sessionIdForUserLoggedIn);
		    params.put("wishListName", wishListName);
			aq.ajax(Constants.ShareWishList_Url, params, XmlDom.class, new AjaxCallback<XmlDom>() {
	            @Override
	            public void callback(String url, XmlDom xml, AjaxStatus status) {
	            	try{
	                    if(xml != null){
	                    	List<XmlDom> entries = xml.tags("prodWishList"); 
	                    	if(entries.size()>0){
	                    		int testcount=0;
		                    	for(XmlDom entry: entries){
		                    		if(entry.tag("wishlist_id")!=null){
		                    			testcount++;
		                    			Log.i("pd_image "+testcount, ""+entry.text("pd_image").toString());
		                    			String symbol = new Common().getCurrencySymbol(entry.text("country_languages").toString(), entry.text("country_code_char2").toString());
		                    			imagesArrList.add(entry.text("pd_image").toString());
		                    			imageNamesArrList.add(entry.text("pd_name").toString());
		                    			imageLinksArrList.add(entry.text("pd_url").toString());
		                    			imageProdDescArrList.add(entry.text("pd_description").toString());
										if (entry.text("pd_price").toString().equals("null") || 
												entry.text("pd_price").toString().equals("") || 
												entry.text("pd_price").toString().equals("0") || 
												entry.text("pd_price").toString().equals("0.00") || 
												entry.text("pd_price").toString() == null) {
											imageProdPriceArrList.add("");
										} else {
											imageProdPriceArrList.add(symbol+entry.text("pd_price").toString());
										}
		                    		}
		                    	} 			                    			
	                		}    
	            			if(imagesArrList.size()>0)
	            			{
	            				render(imagesArrList, imageNamesArrList, imageLinksArrList, imageProdDescArrList, imageProdPriceArrList);
	            			} else {
	            				TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
	            				txtProdNotAvail.setVisibility(View.VISIBLE);
	            			}
	                    }else{
	                    	try{
	                    		Toast.makeText(aq.getContext(), "Error:" + status.getCode(), Toast.LENGTH_LONG).show();
	                    	} catch(Exception e){
	                    		e.printStackTrace();
	                    		String errorMsg = className+" | ajax call    |   " +e.getMessage();
	        					Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
	                    	}
	                    }
	            	} catch(Exception e){
	            		e.printStackTrace();
	            		String errorMsg = className+" | ajax call    |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
	            	}
	            }
			});
			String screenName = "/wishlist/sharewishlist/"+wishListId+"/"+wishListName;
			String productIds = "";
		    String offerIds = "";
			String strMapValues  = Common.aMap.toString();
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
			
			}else{
				
				
				ArrayList<String> wishlistResArrays;
				String[] wishlistFinalArray;
				List<UserWishList> userWishlist;
						
					try{
						aq = new AQuery(ShareMultipleImages.this);
						FileTransaction file = new FileTransaction();
						WishListModel wishlistmodel = file.getWishList();
						if(wishlistmodel.size() >0){
							userWishlist = wishlistmodel.getUserWishlistByName(wishListName);
							int w=0;
							int wishlistSingleArrSize=0;
							wishlistFinalArray = new String[userWishlist.size()];
							if(userWishlist != null){
								for ( final UserWishList userWishList : userWishlist) {
									
									if(w== 0){
										wishlistSingleArrSize =0;
										wishlistFinalArray = new String[wishlistSingleArrSize];
						    			}
									if(wishListName.equals(userWishList.getWishListName())){
						    			
										wishlistResArrays = new ArrayList<String>();
										 ProductModel  getProdDetail = file.getProduct();
				            			 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(userWishList.getProductId()));
				            			 if(chkProdExist != null){

				            			 String curveImagesUrl = chkProdExist.getImageFile().replaceAll(" ", "%20");	
				     	                	
				                							    			    
					    			    imagesArrList.add(curveImagesUrl);
		                    			imageNamesArrList.add(chkProdExist.getProductName());
		                    			imageLinksArrList.add(chkProdExist.getProductUrl());
		                    			imageProdDescArrList.add(chkProdExist.getProductDesc());
		                    			imageProdPriceArrList.add(chkProdExist.getProductPrice());
		                    								    			    
					    			    }
									}
								}	
							}
							if(imagesArrList.size()>0)
	            			{
	            				render(imagesArrList, imageNamesArrList, imageLinksArrList, imageProdDescArrList, imageProdPriceArrList);
	            			} else {
	            				TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
	            				txtProdNotAvail.setVisibility(View.VISIBLE);
	            			}
						}
						}catch (Exception e) {
							// TODO: handle exception
							e.printStackTrace();
						}

			}
    }
		
		gridView.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View v, final int position, long id) {
				try{
				if(Common.isNetworkAvailable(ShareMultipleImages.this)){
					ImageView check = (ImageView)v.findViewById(R.id.check);
					RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
					rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
					rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
					check.setLayoutParams(rlpForImgCheck);
					image = (ImageView)v.findViewById(R.id.image);
					if(check.getVisibility()==View.INVISIBLE){
	    				check.setVisibility(View.VISIBLE);    
	    				checkedArrListImages.add(image.getTag(R.string.imageUrl).toString().replaceAll("%20", " "));
	    				checkedArrListImageNames.add(image.getTag(R.string.imageName).toString().replaceAll("%20", " "));
	    				checkedArrListImageLinks.add(image.getTag(R.string.imageLink).toString());
	    				checkedArrListImageDesc.add(image.getTag(R.string.imageDesc).toString());
	    				checkedArrListImagePrice.add(image.getTag(R.string.imagePrice).toString());				
					} else {
	    				check.setVisibility(View.INVISIBLE);   
	    				checkedArrListImages.remove(image.getTag(R.string.imageUrl).toString().replaceAll("%20", " "));		
	    				checkedArrListImageNames.remove(image.getTag(R.string.imageName).toString().replaceAll("%20", " "));
	    				checkedArrListImageLinks.remove(image.getTag(R.string.imageLink).toString());
	    				checkedArrListImageDesc.remove(image.getTag(R.string.imageDesc).toString());
	    				checkedArrListImagePrice.remove(image.getTag(R.string.imagePrice).toString());		
					}
			}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | gridView click   |   " +e.getMessage();
					Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
				}
				
			}    				
		});		

		ImageView email =(ImageView) findViewById(R.id.email);
		RelativeLayout.LayoutParams rlpForEmail = (RelativeLayout.LayoutParams) email.getLayoutParams();
		rlpForEmail.width = (int) (0.2 * Common.sessionDeviceWidth);
		rlpForEmail.height = (int) (0.123 * Common.sessionDeviceHeight);
		rlpForEmail.rightMargin = (int) (0.04 * Common.sessionDeviceWidth);
		email.setLayoutParams(rlpForEmail);
		email.setOnClickListener(new OnClickListener() {			
			@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
			@Override
			public void onClick(View v) {
				try{
				if(Common.isNetworkAvailable(ShareMultipleImages.this))
				{
				Log.i("userFName", ""+userFName+" "+userLName);
				Log.i("checkedArrListImages", ""+checkedArrListImages+" "+checkedArrListImages.size());
				Log.i("imagesArrList", ""+imagesArrList+" "+imagesArrList.size());
				if(checkedArrListImages.size()>0){
					String screenName = "/wishlist/sharewishlist/email/"+wishListId+"/"+wishListName;
					String productIds = "";
	    		    String offerIds = "";
					Common.sendJsonWithAQuery(ShareMultipleImages.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
					
					//need to "send multiple" to get more than one attachment
				    final Intent emailIntent = new Intent(android.content.Intent.ACTION_SEND_MULTIPLE);
				    emailIntent.setType("text/html");
				    emailIntent.putExtra(Intent.EXTRA_SUBJECT, "Shared wish list '"+wishListName+"'"); 
				    
				    String prodInfoText = "";
				    prodInfoText += "<p>Hi,</p>" +
				    		"<p>"+userLName+" "+userFName+" wants to share a wish list <b>"+wishListName+"</b> with you.</p>";
					prodInfoText += "<ul>";
				    for (int c=0; c<checkedArrListImageNames.size(); c++) {
				    	if(checkedArrListImageLinks.get(c)!=null){
				    		prodInfoText += "<li><p><a href='"+checkedArrListImageLinks.get(c)+"'>"+checkedArrListImageNames.get(c)+" ("+checkedArrListImagePrice.get(c)+")</a>: "+checkedArrListImageDesc.get(c)+"</p></li>";
				    	} else {
				    		prodInfoText += "<li><p><b>"+checkedArrListImageNames.get(c)+"</b> ("+checkedArrListImagePrice.get(c)+"): "+checkedArrListImageDesc.get(c)+"</p></li>";
				    	}
				    }
					prodInfoText += "</ul>";
					prodInfoText += "<p>&nbsp;</p><p>Download SeeMore Interactive at iTunes/Google Play.</p>";
					prodInfoText += "<p><a href='https://itunes.apple.com/us/app/seemore-interactive/id591304180'>https://itunes.apple.com/us/app/seemore-interactive/id591304180</a></p>";
					prodInfoText += "<p><a href='https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive'>https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive</a></p>";
					prodInfoText += "<p>Create your own list or gift a friend</p>";

					prodInfoText += "<p>&nbsp;</p>Powered By<br/>";
					prodInfoText += "Seemore Interactive.<br/>";
					prodInfoText += "<a href='http://www.seemoreinteractive.com/'>http://www.seemoreinteractive.com/<a><br/>";
				   
				 
				    Spanned emailText2 = Html.fromHtml(new StringBuilder().append(prodInfoText).toString());
				    //has to be an ArrayList
				    ArrayList<Uri> uris = new ArrayList<Uri>();
				    //convert from paths to Android friendly Parcelable Uri's
				    for (int c=0; c<checkedArrListImages.size(); c++) {
						Log.i("file", ""+checkedArrListImages.get(c));
						File fileIn = createTempFile(checkedArrListImages.get(c), checkedArrListImageNames.get(c), 
								checkedArrListImageLinks.get(c), checkedArrListImageDesc.get(c), checkedArrListImagePrice.get(c));
						Uri u = Uri.fromFile(fileIn);
				        uris.add(u);
				    }
				    emailIntent.putExtra(Intent.EXTRA_TEXT, emailText2);
				    emailIntent.putParcelableArrayListExtra(Intent.EXTRA_STREAM, uris);
				    startActivity(emailIntent);
				} else {
					Toast.makeText(ShareMultipleImages.this, "Please select atleast one item to share.", Toast.LENGTH_SHORT).show();
				}
			}else{
				  new Common().instructionBox(ShareMultipleImages.this,R.string.title_case7,R.string.instruction_case7);					
			}
			}catch(Exception e){
				String errorMsg = className+" | email    |   " +e.getMessage();
				Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
			}
			}
		});
		ImageView imgFacebook =(ImageView) findViewById(R.id.facebook);
		RelativeLayout.LayoutParams rlpForFacebook = (RelativeLayout.LayoutParams) imgFacebook.getLayoutParams();
		rlpForFacebook.width = (int) (0.2 * Common.sessionDeviceWidth);
		rlpForFacebook.height = (int) (0.123 * Common.sessionDeviceHeight);
		rlpForFacebook.leftMargin = (int) (0.04 * Common.sessionDeviceWidth);
		rlpForFacebook.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
		imgFacebook.setLayoutParams(rlpForFacebook);
		imgFacebook.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				try {
					if(Common.isNetworkAvailable(ShareMultipleImages.this))
					{
					if (checkedArrListImages.size()>0) {
						String screenName = "/wishlist/sharewishlist/facebook/"+wishListId+"/"+wishListName;
						String productIds = "";
	    		        String offerIds = "";
						Common.sendJsonWithAQuery(ShareMultipleImages.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						ArrayList<Uri> arrProdImage = new ArrayList<Uri>();
					//	ArrayList<String> arrProdImage = new ArrayList<String>();
						ArrayList<String> arrProdImageName = new ArrayList<String>();
						ArrayList<String> arrProdImageLink = new ArrayList<String>();
						ArrayList<String> arrProdImagePrice = new ArrayList<String>();
						ArrayList<String> arrProdImageDesc = new ArrayList<String>();
					    //convert from paths to Android friendly Parcelable Uri's
					    for (int c=0; c<checkedArrListImages.size(); c++) {
							File fileIn = createTempFile(checkedArrListImages.get(c), checkedArrListImageNames.get(c), 
									checkedArrListImageLinks.get(c), checkedArrListImageDesc.get(c), checkedArrListImagePrice.get(c));
							
							arrProdImage.add(Uri.fromFile(fileIn));
							//arrProdImage.add(checkedArrListImages.get(c));
							arrProdImageName.add(checkedArrListImageNames.get(c));
							if(!checkedArrListImageLinks.get(c).equals("")){
								arrProdImageLink.add(checkedArrListImageLinks.get(c));								
							} else {
								arrProdImageLink.add("null");								
							}
							if(!checkedArrListImagePrice.get(c).equals("")){
								arrProdImagePrice.add(checkedArrListImagePrice.get(c));								
							} else {
								arrProdImagePrice.add("0");								
							}
							if(!checkedArrListImageDesc.get(c).equals("")){
								arrProdImageDesc.add(checkedArrListImageDesc.get(c));								
							} else {
								arrProdImageDesc.add(checkedArrListImageNames.get(c));								
							}	
					    }	
					    Intent intent = new Intent(getApplicationContext(), FacebookMultipleShareActivity.class);
						intent.putExtra("wishListName", wishListName);
						intent.putExtra("arrProdImage", arrProdImage);
						intent.putExtra("arrProdImageName", arrProdImageName);
						intent.putExtra("arrProdImageLink", arrProdImageLink);
						intent.putExtra("arrProdImagePrice", arrProdImagePrice);
						intent.putExtra("arrProdImageDesc", arrProdImageDesc);
						startActivity(intent);
					} else {
						Toast.makeText(ShareMultipleImages.this, "Please select atleast one item to share.", Toast.LENGTH_SHORT).show();
					}
					}else{
						  new Common().instructionBox(ShareMultipleImages.this,R.string.title_case7,R.string.instruction_case7);					
					}
				} catch (Exception e) {
					Toast.makeText(getApplicationContext(), "Error: ShareActivity clicked on share.", Toast.LENGTH_LONG).show();
					e.printStackTrace();
					String errorMsg = className+" | imgFacebook    |   " +e.getMessage();
					Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
				}
			}
		});
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
		}
	}
	
	public byte[] getBytes(InputStream inputStream) throws IOException {
      ByteArrayOutputStream byteBuffer = new ByteArrayOutputStream();
      int bufferSize = 1024;
      byte[] buffer = new byte[bufferSize];

      int len = 0;
      while ((len = inputStream.read(buffer)) != -1) {
        byteBuffer.write(buffer, 0, len);
      }
      return byteBuffer.toByteArray();
    }
	

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {	     

		super.onActivityResult(requestCode, resultCode, data);
		facebook.authorizeCallback(requestCode, resultCode, data);
        /*switch(requestCode) {                
	        case ACTIVITY_SSO: {
                if(handle != null){
                    handle.onActivityResult(requestCode, resultCode, data);   
                }
                break;
	        }        
        }*/
	}
	@Override
	public void onResume() {
		try{
			super.onResume();
			facebook.extendAccessTokenIfNeeded(this, null);
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(ShareMultipleImages.this);			
				Common.isAppBackgrnd = false;
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onResume.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onResume    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
		}
	}
	
	public Object fetch(String address) throws MalformedURLException,IOException {
		URL url = new URL(address);
		Object content = url.getContent();
		return content;
	}
	public File createTempFile(String file, String name, String link, String desc, String price) {	
		try {			
			Bitmap bitmap = aq.getCachedImage(file);	
			// Save this bitmap to a file.
	        File cache = getApplicationContext().getExternalCacheDir();
	        File sharefile = new File(cache, name+".png");
	        try {
	            FileOutputStream out = new FileOutputStream(sharefile);
	            bitmap.compress(Bitmap.CompressFormat.PNG, 100, out);
	            out.flush();
	            out.close();
	            return sharefile;
	        } catch (IOException e) {
	        	Toast.makeText(getApplicationContext(), "Error: ShareActivity IOException.", Toast.LENGTH_LONG).show();
	        }
			
		} catch (Exception e) {
			//Toast.makeText(getApplicationContext(), "Error: ShareActivity createTempFile.", Toast.LENGTH_LONG).show();
		      e.printStackTrace();
		      String errorMsg = className+" | createTempFile    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
		}
		return null;
	}
	
	private void render(ArrayList<String> imagesArrList2, final ArrayList<String> imageNamesArrList2, 
			final ArrayList<String> imageLinksArrList2, final ArrayList<String> imageProdDescArrList2, 
			final ArrayList<String> imageProdPriceArrList2){	    	
	    try{
			AQUtility.debug("render setup");	    	
			ArrayAdapter<String> aa = new ArrayAdapter<String>(this, R.layout.share_griditem, imagesArrList2){				
				@Override
				public View getView(int position, View convertView, ViewGroup parent) {			
					try{
						if(convertView == null){
							convertView = aq.inflate(convertView, R.layout.share_griditem, parent);
						}			
						String photo = getItem(position);	
						AQuery aq2 = new AQuery(convertView);		
						String tbUrl = photo;		
						Bitmap placeholder = aq2.getCachedImage(tbUrl);
						if(placeholder==null){
							aq2.cache(tbUrl, 14400000);					
						}
						aq2.id(R.id.image).progress(R.id.progressBarGrid).image(tbUrl, true, true, 0, 0, placeholder, 0, 0);
						
						ImageView img =(ImageView) convertView.findViewById(R.id.image);
						RelativeLayout.LayoutParams rlpForImg = (RelativeLayout.LayoutParams) img.getLayoutParams();
						rlpForImg.width = (int)(0.2 * Common.sessionDeviceWidth);
						rlpForImg.height = (int) (0.123 * Common.sessionDeviceHeight);
						img.setLayoutParams(rlpForImg);
						img.setTag(R.string.imageUrl, photo);
						img.setTag(R.string.imageName, imageNamesArrList2.get(position));
						img.setTag(R.string.imageLink, imageLinksArrList2.get(position));
						img.setTag(R.string.imageDesc, imageProdDescArrList2.get(position));
						img.setTag(R.string.imagePrice, imageProdPriceArrList2.get(position));
						
						if(Common.isNetworkAvailable(ShareMultipleImages.this)){
							aq2.id(R.id.image).progress(R.id.progressBarGrid).image(tbUrl, true, true, 0, 0, placeholder, 0, 0);
						}else{
							Bitmap bm = aq2.getCachedImage(tbUrl);							
							if(bm != null){		
								ProgressBar progressBarGrid =(ProgressBar) convertView.findViewById(R.id.progressBarGrid);
								progressBarGrid.setVisibility(View.INVISIBLE);
								img.setImageBitmap(bm);
							}else{
								ProgressBar progressBarGrid =(ProgressBar) convertView.findViewById(R.id.progressBarGrid);
								progressBarGrid.setVisibility(View.INVISIBLE);
								img.setVisibility(View.INVISIBLE);
							}
						}
						
						
						ImageView check = (ImageView) convertView.findViewById(R.id.check);
						RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
						rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
						rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
						check.setLayoutParams(rlpForImgCheck);
						check.setVisibility(View.VISIBLE);    
						if(!checkedArrListImages.contains(img.getTag(R.string.imageUrl).toString())){
							Log.i("render checkedArrListImages", checkedArrListImages.size()+""+checkedArrListImages+" "+img.getTag(R.string.imageUrl).toString());
							checkedArrListImages.add(img.getTag(R.string.imageUrl).toString().replaceAll("%20", " "));
							checkedArrListImageNames.add(img.getTag(R.string.imageName).toString().replaceAll("%20", " "));
							checkedArrListImageLinks.add(img.getTag(R.string.imageLink).toString());
							checkedArrListImageDesc.add(img.getTag(R.string.imageDesc).toString());
							checkedArrListImagePrice.add(img.getTag(R.string.imagePrice).toString());
						}										
					} catch (Exception e){
						e.printStackTrace();
					}
					return convertView;					
				}			
			};			
			aq.id(R.id.shareMultiGridView).adapter(aa);
		} catch (Exception e){
			e.printStackTrace();
			 String errorMsg = className+" | render    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
		}
	}
    
    @Override
	public void onBackPressed() {
    	try{
			Intent prodListPage = new Intent(getApplicationContext(), WishListPage.class);
        	startActivity(prodListPage);
        	finish();
        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
    	} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: Share Multiple onBackPressed.", Toast.LENGTH_LONG).show();
			 String errorMsg = className+" | onBackPressed    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
		}
    }

	 @Override
	public void onStart() {
	    super.onStart();
	    try{
	     Tracker easyTracker = EasyTracker.getInstance(this);
	  		easyTracker.set(Fields.SCREEN_NAME, " /wishlist/sharewishlist/"+wishListName);
	  		easyTracker.send(MapBuilder
	  			    .createAppView()
	  			    .build()
	  			);
	  		String[] segments = new String[1];
			 segments[0] = "Share Wishlist Page"; 
			 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
	    }catch(Exception e){
	    	e.printStackTrace();
	    	 String errorMsg = className+" | onStart    |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
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
	    	 String errorMsg = className+" | onStop    |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);	
			 
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ShareMultipleImages.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(ShareMultipleImages.this,errorMsg);
			}
			
		}
	 
		
	 
}
