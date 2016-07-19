package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

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
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
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
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Model.UserWishList;
import com.seemoreinteractive.seemoreinteractive.Model.WishListModel;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.GINGERBREAD)
public class WishListPage extends Activity {

	public boolean isBackPressed = false;
	final Context context = this;
	ImageView image;	
	public Boolean alertErrorType = true;

	String getProductId = "null", getProductName = "null", getProductPrice = "null", getClientLogo = "null", getClientId = "null", 
			getClientBackgroundImage = "null", getFinalWebSiteUrl = "null", getClientBackgroundColor = "null", 
			getClientImageName = "null", getClientUrl = "null", getProductUrl = "null", getProductShortDesc = "null", 
			getProductDesc = "null";	
	SessionManager session; 
	JSONArray clientsJsonArr;
	JSONObject json;
	String className = this.getClass().getSimpleName();

	public List<Bitmap> imagesList;
	public ArrayList<String> imagesUrlArrList, imagesClientWithProdIdArrList, imagesProdIsTryOnArrList;
	public ArrayList<String> imagesProdIdArrList, imagesBgColorCodeArrList;
	public ArrayList<String> imagesProdNameArrList, imagesBgLightColorCodeArrList, imagesBgDarkColorCodeArrList;
	public ArrayList<String> imagesUrlList;
	public JSONArray userJsonArray = null;
	public JSONArray wishListJsonArray = null;
	public JSONArray userWishlistJsonArray = null;
	public JSONArray userAddWishValJsonArray = null;
	JSONParser getWishListArray;
	List<NameValuePair> userParams = null, onlyUserIdParams = null;
	List<HashMap<String,String>> aList;
	String[] wishListId = null;
	String wishListName;
	ListView lvSelectWishLists;
	String shared;
	AQuery aq;
	TextView txtWishListName;
	TextView txtProdNotAvail;
	FancyCoverFlow fancyCoverFlow;
	String pageRedirectFlag = null;
	WishListModel wishListModel = null;
	 int wishlistSingleArrSize=0;
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_wishlist);
		try{
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Wish List", "");

       
			// Session class instance
	        session = new SessionManager(context);
	    	txtWishListName = (TextView) findViewById(R.id.offerLabel);
			RelativeLayout.LayoutParams rlpForWishListName = (RelativeLayout.LayoutParams) txtWishListName.getLayoutParams();
			rlpForWishListName.width = (int) (0.767 * Common.sessionDeviceWidth);
			rlpForWishListName.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			rlpForWishListName.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			txtWishListName.setLayoutParams(rlpForWishListName);
			txtWishListName.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
	
			txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
			txtProdNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);				

    		fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlow1);
	        RelativeLayout.LayoutParams rlpForFancyCover = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();
	        rlpForFancyCover.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
	        rlpForFancyCover.height = (int) (0.513 * Common.sessionDeviceHeight);
	        fancyCoverFlow.setLayoutParams(rlpForFancyCover);
	        
	        
	     	
	        
	        ImageView imgvHelp =  (ImageView) findViewById(R.id.imgvHelp); 
 			RelativeLayout.LayoutParams rlpHelp = (RelativeLayout.LayoutParams) imgvHelp.getLayoutParams();
 			rlpHelp.width = (int) (0.0834 * Common.sessionDeviceWidth);
 			rlpHelp.height = (int) (0.0513 * Common.sessionDeviceHeight);
 			imgvHelp.setLayoutParams(rlpHelp);
 			imgvHelp.setOnClickListener(new OnClickListener() { 				
 				@Override
 				public void onClick(View v) {			
 					try{
 					Intent intent = new Intent(getApplicationContext(),HelpActivity.class);
 					intent.putExtra("screen_name", "wishlist");
 					startActivity(intent);	
 					} catch(Exception e){
 						e.printStackTrace();
 						String errorMsg = className+" | imgvHelp click      |   " +e.getMessage();
 						 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
 					}
 				}
 			});

			aq = new AQuery(WishListPage.this);
			if(Common.isNetworkAvailable(WishListPage.this))
			{				        
				if(session.isLoggedIn())
		        {			        
			     	getWishListResultsFromServerWithXml(Constants.WishList_Url+Common.sessionIdForUserLoggedIn+"/", WishListPage.this);						  
		        }
			} else {
				getWishListResultsFromFile("null");
			}

			new Common().showDrawableImageFromAquery(this, R.drawable.btn_trash, R.id.imgvBtnCloset);
			
			//new Common().clickingOnBackButtonWithAnimation(this, Closet.class,"0");
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
						//prodListPage.putExtra("finish", true);
						//prodListPage.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
			        	startActivity(intent);
			        	finish();
			        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: ByBrands imgBackButton.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgBackButton click      |   " +e.getMessage();
						 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
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
			            		String[] arrStringId = prodImage.getTag().toString().split(",");
								BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
								Bitmap bitmap = test.getBitmap();
								ByteArrayOutputStream baos = new ByteArrayOutputStream();
								bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
								byte[] b = baos.toByteArray();
								Intent intent = new Intent(WishListPage.this, ProductInfo.class);
								intent.putExtra("activityFlag", "wishlist");
								intent.putExtra("pageRedirectFlag", "wishlist");
								intent.putExtra("tapOnImage", false);		
								//intent.putExtra("image", b);
								intent.putExtra("wishListName", txtWishListName.getText().toString());
								intent.putExtra("image", ""+prodImage.getTag(R.string.imageUrl));		
								intent.putExtra("productId",  ""+arrStringId[1]);
								intent.putExtra("clientId",""+arrStringId[0]);		
								startActivity(intent);
								overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
							}
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: WishListPage imgBtnCart.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCart click      |   " +e.getMessage();
						 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
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
		        			Toast.makeText(getApplicationContext(), "Error: WishListPage imgFooterMiddle.", Toast.LENGTH_LONG).show();
		        			String errorMsg = className+" | imgFooterMiddle click      |   " +e.getMessage();
							 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
		        		}
		            }
		    });

			ImageView imgBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgBtnCloset.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
					if(Common.isNetworkAvailable(WishListPage.this))
					{
					 AlertDialog.Builder alertDialog = new AlertDialog.Builder(WishListPage.this);					 
				     alertDialog.setTitle("Confirm Delete...");
				     alertDialog.setMessage("Are you sure you want delete "+txtWishListName.getText()+ " ?");
				     alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
				            @Override
							public void onClick(DialogInterface dialog,int which) {
				        		aq.ajax(Constants.DeleteWishList_Url+Common.sessionIdForUserLoggedIn+"/"+txtWishListName.getText().toString()+"/", XmlDom.class, new AjaxCallback<XmlDom>(){			
				        			@Override
				        			public void callback(String url, XmlDom xml, AjaxStatus status) {
				        				try{
				            				if(xml!=null){
				            					if(xml.text("msg").equals("already")){
				        	    					//Toast.makeText(context, "Already deleted.", Toast.LENGTH_LONG).show();
				        	    				} else if(xml.text("msg").equals("success")){
				        	    					//Toast.makeText(context, "Deleted successfully.", Toast.LENGTH_LONG).show();
				        	    					getWishListResultsFromServerWithXml(Constants.WishList_Url+Common.sessionIdForUserLoggedIn+"/", WishListPage.this);
				        	    				} else {
				        	    					Toast.makeText(context, "Delete failed. Please try again!", Toast.LENGTH_LONG).show();
				        	    				}
				            				}
				        				} catch(Exception e){
				        					e.printStackTrace();
				        					String errorMsg = className+" | imgBtnCloset click  ajax call    |   " +e.getMessage();
											 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
				        				}
				        			}			            			
				        		});	
				            }
				        });
				        alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
				            @Override
							public void onClick(DialogInterface dialog, int which) {			         
				            dialog.cancel();
				            }
				        });
				        alertDialog.show();
						String screenName = "/mycloset/wishlists/removed/"+txtWishListName.getTag()+"/"+txtWishListName.getText();
						String productIds = "";
	    		        String offerIds = "";
						Common.sendJsonWithAQuery(WishListPage.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
				}else{
					
					new Common().instructionBox(WishListPage.this,R.string.title_case7,R.string.instruction_case7);
				}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCloset click      |   " +e.getMessage();
						 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
					}

				}
			});
			
			ImageView imgAddWishlist = (ImageView) findViewById(R.id.imgvAddWishList);
			RelativeLayout.LayoutParams rlpForImgAddWishList = (RelativeLayout.LayoutParams) imgAddWishlist.getLayoutParams();
			rlpForImgAddWishList.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgAddWishList.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgAddWishList.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgAddWishList.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			imgAddWishlist.setLayoutParams(rlpForImgAddWishList);
			imgAddWishlist.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(WishListPage.this))
						{
			        	AlertDialog.Builder alertDialogBuilder3 = new AlertDialog.Builder(context);
			        	alertDialogBuilder3.setTitle("Add Wish List"); //Set Alert dialog title here
			        	alertDialogBuilder3.setMessage("Provide name of the wish list:"); //Message here

			            // Set an EditText view to get user input 
			            final EditText input = new EditText(context);
			            alertDialogBuilder3.setView(input);				 
				        alertDialogBuilder3.setPositiveButton("Ok", new DialogInterface.OnClickListener() {				 
				            @Override
				            public void onClick(DialogInterface dialog, int whichButton) {
				                final String getAddWishValue = input.getText().toString();
					            
				                if(getAddWishValue.equals("")){
				                	input.setError("Please enter wish list name.");
				                	input.requestFocus();
									Toast.makeText(getApplicationContext(), "Please enter wish list name.", Toast.LENGTH_LONG).show();
									return;
				                }
				                else {    
				                	aq.ajax(Constants.WishList_Url+"addtowishlist/"+Common.sessionIdForUserLoggedIn+"/"+getAddWishValue, XmlDom.class, new AjaxCallback<XmlDom>(){
				            		@Override
									public void callback(String url, XmlDom xml, AjaxStatus status) {
				            				try{
				            					if(xml!=null){
								    				if(xml.text("msg").equals("already")){
								    					return;
								    				} else if(xml.text("msg").equals("success")){
								    					getWishListResultsFromServerWithXml(Constants.WishList_Url+Common.sessionIdForUserLoggedIn+"/"+getAddWishValue, WishListPage.this);
								    					return;
								    				} else {
								    					Toast.makeText(getApplicationContext(), "Add wish list name failed.", Toast.LENGTH_LONG).show();
								    					return;
								    				}				            					
					            				}
				            				} catch(Exception e){
				            					e.printStackTrace();
				            				}
				            			}			            			
				            		});			                	
					            }
				                
				            }
				        });							 
				         alertDialogBuilder3.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
				 
				            @Override
				            public void onClick(DialogInterface dialog, int which) {
				            	dialog.cancel();
				            }
				        });
				       //End of alert.setNegativeButton
			        	AlertDialog alertDialog3 = alertDialogBuilder3.create();
			        	alertDialog3.show();
						}else{
							new Common().instructionBox(WishListPage.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch (Exception e) {
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: ProductInfo AddWishlist onClick.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | AddWishlist click      |   " +e.getMessage();
						 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
					}
				}
			});

	    	ImageView imgSelectWishlist = (ImageView) findViewById(R.id.imgvSelectWishList);
			RelativeLayout.LayoutParams rlpForImgSelectWishList = (RelativeLayout.LayoutParams) imgSelectWishlist.getLayoutParams();
			rlpForImgSelectWishList.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgSelectWishList.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgSelectWishList.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgSelectWishList.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgSelectWishlist.setLayoutParams(rlpForImgSelectWishList);
	    	imgSelectWishlist.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
					new loadWishListSelection().execute();
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgSelectWishlist click      |   " +e.getMessage();
						 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
					}
				}
			});
			
			ImageView imgvAddItemToWishList = (ImageView) findViewById(R.id.imgvAddItemToWishList);
			RelativeLayout.LayoutParams rlpForImgAddItemToWishList = (RelativeLayout.LayoutParams) imgvAddItemToWishList.getLayoutParams();
			rlpForImgAddItemToWishList.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgAddItemToWishList.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgAddItemToWishList.rightMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgAddItemToWishList.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgvAddItemToWishList.setLayoutParams(rlpForImgAddItemToWishList);
	    	imgvAddItemToWishList.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(), WishItemSelectionActivity.class);
						intent.putExtra("loginuserId", ""+Common.sessionIdForUserLoggedIn);
						intent.putExtra("wishListId", ""+txtWishListName.getTag());
						intent.putExtra("wishListName", txtWishListName.getText());
						Bundle b = new Bundle();
						b.putStringArrayList("imagesUrlList", imagesUrlList);
						intent.putExtras(b);
						intent.putExtra("imagesUrlList", imagesUrlList);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvAddItemToWishList click      |   " +e.getMessage();
						Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
					}
				}
			});
			
	    	ImageView imgBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
	    	imgBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{
						if(!txtWishListName.getText().equals("")){
							Intent intent = new Intent(WishListPage.this, ShareMultipleImages.class);
							intent.putExtra("wishListId",  ""+txtWishListName.getTag());
							intent.putExtra("wishListName",  txtWishListName.getText());
							startActivity(intent);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnShare click      |   " +e.getMessage();
						Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
					}
				}
			});

			ImageView imgSeeFrndsWishList = (ImageView) findViewById(R.id.imgvSeeFrndWishList);
			RelativeLayout.LayoutParams rlpForImgSeeFrndsWishList = (RelativeLayout.LayoutParams) imgSeeFrndsWishList.getLayoutParams();
			rlpForImgSeeFrndsWishList.width = (int) (0.467 * Common.sessionDeviceWidth);
			rlpForImgSeeFrndsWishList.height = (int) (0.066 * Common.sessionDeviceHeight);
			rlpForImgSeeFrndsWishList.rightMargin = (int) (0.025 * Common.sessionDeviceWidth);
			rlpForImgSeeFrndsWishList.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			//rlpForImgSeeFrndsWishList.addRule(RelativeLayout.ABOVE, R.id.include2);
			imgSeeFrndsWishList.setLayoutParams(rlpForImgSeeFrndsWishList);
			imgSeeFrndsWishList.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Intent intent = new Intent(getApplicationContext(),UnderConstruction.class);
						intent.putExtra("loggedInUserId", ""+Common.sessionIdForUserLoggedIn);
						startActivity(intent);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgSeeFrndsWishList click      |   " +e.getMessage();
						Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
					}  
				}
			});
			
		} catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: WishListPage onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();

			 String errorMsg = className+" | onCreate      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
		}
	}

	private void getWishListResultsFromFile(String wishlistname) {		
		try{
			aq = new AQuery(WishListPage.this);
			FileTransaction file = new FileTransaction();
			WishListModel wishlistmodel = file.getWishList();
			ProductModel  getProdDetail = file.getProduct();
			if(wishlistmodel.size() >0){

				TextView offerLabel = (TextView)findViewById(R.id.offerLabel);
				//userCloset = closetmodel.getClosetList();
				if(!wishlistname.equals("null"))
					userWishlist = wishlistmodel.getUserWishlistByName(wishlistname);
				else
					userWishlist = wishlistmodel.getAllWishlistDesc();
							
				imagesUrlList  = new ArrayList<String>();
				imagesList = new ArrayList<Bitmap>(); 
				int w=0;
				wishlistFinalArray = new String[userWishlist.size()];
				if(userWishlist != null){
					for ( final UserWishList userWishList : userWishlist) {
						
						if(w== 0){
							offerLabel.setText(userWishList.getWishListName());
							offerLabel.setTag(userWishList.getId());
							wishlistSingleArrSize =0;
							wishlistFinalArray = new String[wishlistSingleArrSize];
			    			}
						// Log.e("userWishlist",userWishList.getWishListName());
						// Log.e("userWishlist label",offerLabel.getText().toString());
						if(offerLabel.getText().toString().equals(userWishList.getWishListName())){							
							
	            			 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(userWishList.getProductId()));
	            			 if(chkProdExist != null){
	            				 String curveImagesUrl = chkProdExist.getImageFile().replaceAll(" ", "%20");	              	
	            				// Log.e("curveImagesUrl",chkProdExist.getProductName());
								wishlistResArrays = new ArrayList<String>();
		                		wishlistResArrays.add(curveImagesUrl);
		                		wishlistResArrays.add(""+userWishList.getClientId());
		 	    			    wishlistResArrays.add(""+userWishList.getProductId());
		 	    			    wishlistResArrays.add(chkProdExist.getProductName());
			    			    wishlistResArrays.add(""+chkProdExist.getProdIsTryOn());
			    			    wishlistResArrays.add(chkProdExist.getClientBackgroundColor());
			    			    wishlistResArrays.add(chkProdExist.getClientLogo());
			    			    wishlistResArrays.add(chkProdExist.getClientLightColor());
			    			    wishlistResArrays.add(chkProdExist.getClientDarkColor());
			    			    wishlistSingleArrSize++;		    				
			    				wishlistFinalArray = Arrays.copyOf(wishlistFinalArray,wishlistSingleArrSize);
			    				
			    				Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
			    				if(bitmap1==null) {
			    					aq.cache(curveImagesUrl, 144000);
			    				}
			    				if(offerLabel.getText().toString().equals(userWishList.getWishListName())){											    			
			    					wishlistFinalArray[wishlistSingleArrSize-1] = wishlistResArrays.toString();			    				
				    				imagesList.add(bitmap1);
				    				imagesUrlList.add(curveImagesUrl);
					    		}
		    				
		    				w++;
	            			 }
	            			 
						}
					}	
					    fancyCoverFlow.setAdapter(renderForCoverFlow(wishlistFinalArray));
				        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
				        fancyCoverFlow.setMaxRotation(120);
				        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
							@Override
							public void onItemClick(AdapterView<?> arg0, View arg1,
									int arg2, long arg3) {
								try{
									LinearLayout rl = (LinearLayout) fancyCoverFlow.getSelectedView();
				            		RelativeLayout ll =(RelativeLayout) rl.getChildAt(0);
				            		ImageView prodImage = (ImageView)ll.getChildAt(0);
				            		String[] arrStringId = prodImage.getTag().toString().split(",");
									BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
									Bitmap bitmap = test.getBitmap();
									ByteArrayOutputStream baos = new ByteArrayOutputStream();
									bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
									byte[] b = baos.toByteArray();
									Intent intent = new Intent(WishListPage.this, ProductInfo.class);
									intent.putExtra("activityFlag", "wishlist");
									intent.putExtra("pageRedirectFlag", "wishlist");
									intent.putExtra("tapOnImage", false);		
									//intent.putExtra("image", b);		
									intent.putExtra("wishListName", txtWishListName.getText().toString());	
									intent.putExtra("image", ""+prodImage.getTag(R.string.imageUrl));	
									intent.putExtra("productId",  ""+arrStringId[1]);
									intent.putExtra("clientId",""+arrStringId[0]);		
									startActivity(intent);
									overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
								}catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | fancyCoverFlow click      |   " +e.getMessage();
									Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
								}
							}	    			
			    		});
			    	}else {
						txtProdNotAvail.setVisibility(View.VISIBLE);
					}					
				}
		}catch(Exception e){
			e.printStackTrace();
			 String errorMsg = className+" | getWishListResultsFromFile      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
		}
		
	}

	public void getWishListResultsFromServerWithXml(String getProductUrl, Activity activityThis){
		try{
		    aq.ajax(getProductUrl, XmlDom.class, activityThis, "wishListXmlResultFromServer");     
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getWishListResultsFromServerWithXml      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
		}   
	}
	

	ArrayList<String> wishlistResArrays;
	String[] wishlistFinalArray;
	 List<UserWishList> userWishlist;
	public void wishListXmlResultFromServer(String url, XmlDom xml, AjaxStatus status){
	  	try {

	  		 final FileTransaction file = new FileTransaction();
	  		 ProductModel productModel = new ProductModel();
			 ProductModel  getProdDetail = file.getProduct();
			
	  		aq= new AQuery(WishListPage.this);
			TextView offerLabel = (TextView)findViewById(R.id.offerLabel);
			if(xml != null){
		    final List<XmlDom> wishLishtXml = xml.tags("prodWishList");
			final ArrayList<String> wishlistIDArrays  = new ArrayList<String>();
	    	if(wishLishtXml.size()>=1){	    		
	    		
				    final WishListModel getWishListModel = file.getWishList();
				    if(getWishListModel != null){
				        if(getWishListModel.size() >0){
				        	getWishListModel.removeAll();
				        }
				    }
			        
			        if(getWishListModel.size() ==0){
			        	wishListModel = new WishListModel();
			        }
			        wishlistFinalArray = new String[wishLishtXml.size()];
			        imagesUrlList  = new ArrayList<String>();
					imagesList = new ArrayList<Bitmap>(); 
					 ClosetModel closetModelObj = new ClosetModel();
					 ClosetModel closetmodel = file.getCloset();
			        int w=0;
			        for(final XmlDom wlXml : wishLishtXml){
			    	try {
			    		if(wlXml.tag("wishlist_name")!=null){
			    			
			    			wishlistResArrays = new ArrayList<String>();
			    			if(w== 0){
							offerLabel.setText(wlXml.text("wishlist_name").toString());
							offerLabel.setTag(wlXml.text("wishlist_id").toString());
							wishlistSingleArrSize =0;
							wishlistFinalArray = new String[wishlistSingleArrSize];
			    			}
							if(!wlXml.text("prodId").toString().equals("")){
								txtProdNotAvail.setVisibility(View.INVISIBLE);
			    				String imageurl = wlXml.text("pd_image").toString().replaceAll(" ", "%20");
			    				if(offerLabel.getText().toString().equals(wlXml.text("wishlist_name").toString())){
				    				wishlistResArrays.add(imageurl);
				    			    wishlistResArrays.add(wlXml.text("client_id").toString());
				    			    wishlistResArrays.add(wlXml.text("prodId").toString());
				    			    wishlistResArrays.add(wlXml.text("pd_name").toString());
				    			    wishlistResArrays.add(wlXml.text("pd_istryon").toString());
				    			    wishlistResArrays.add(wlXml.text("background_color").toString());
				    			    wishlistResArrays.add(wlXml.text("client_logo").toString());
				    			    wishlistResArrays.add(wlXml.text("light_color").toString());
				    			    wishlistResArrays.add(wlXml.text("dark_color").toString());
				    			    wishlistSingleArrSize++;
				    				//Log.e("wishlistSingleArrSize",""+wishlistSingleArrSize);				    				
				    				wishlistFinalArray = Arrays.copyOf(wishlistFinalArray,wishlistSingleArrSize);
			    				}
			    				Common.arrPdIdsForUserAnalytics.add(wlXml.text("prodId").toString());
			    			  	Bitmap bitmap1 = aq.getCachedImage(imageurl);
				    			if(bitmap1==null) {					    			
				    				aq.cache(imageurl, 14400000);
				    			}			
				    			UserWishList userWishList = new UserWishList();
				    			userWishList.setId(Integer.parseInt(wlXml.text("wishlist_id").toString()));
				    			userWishList.setClientId(Integer.parseInt(wlXml.text("client_id").toString()));
				    			userWishList.setWishListName(wlXml.text("wishlist_name").toString());	
				    			userWishList.setProductId(wlXml.text("prodId").toString());
				    			userWishList.setWishListCreatedDate(wlXml.text("wishlist_created_date").toString());	
				    			 
				    			wishlistIDArrays.add(wlXml.text("wishlist_id").toString());
	    		    			wishListModel.add(userWishList);
	    		    	
	    		    			 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(wlXml.text("prodId").toString()));
                    			 if(chkProdExist == null){	
                    				 UserProduct userProduct = new UserProduct();
		                    		
                    				 userProduct.setClientId(Integer.parseInt(wlXml.text("client_id").toString()));
                    				 userProduct.setClientName(wlXml.text("client_name").toString());
                    				 userProduct.setClientUrl(wlXml.text("client_url").toString());
                    				 userProduct.setImageFile(imageurl);
                    				 userProduct.setProductId(Integer.parseInt(wlXml.text("prodId").toString()));
                    				 userProduct.setProductName(wlXml.text("pd_name").toString());
                    				 userProduct.setProductPrice(wlXml.text("pd_price").toString());
                    				 userProduct.setProductShortDesc(wlXml.text("pd_short_description").toString());
                    				 userProduct.setProductDesc(wlXml.text("pd_description").toString());
                    				 userProduct.setProductUrl(wlXml.text("pd_url").toString());
                    				 userProduct.setProdIsTryOn(Integer.parseInt(wlXml.text("pd_istryon").toString()));
                    				 userProduct.setClientBackgroundColor(wlXml.text("background_color").toString());
                    				 userProduct.setClientLightColor(wlXml.text("light_color").toString());
                    				 userProduct.setClientDarkColor(wlXml.text("dark_color").toString());
                    				 userProduct.setClientLogo(wlXml.text("client_logo").toString());
	                    			 productModel.add(userProduct);
	    		    				}
				    			if(offerLabel.getText().toString().equals(wlXml.text("wishlist_name").toString())){					    			
				    			wishlistFinalArray[wishlistSingleArrSize-1] = wishlistResArrays.toString();
	    		    			imagesList.add(bitmap1);
				    			imagesUrlList.add(imageurl);
				    			}
	    		    			w++;
					        }
			    		}
						else {
							txtProdNotAvail.setVisibility(View.VISIBLE);
						}
			    	} catch(Exception e){
			    		e.printStackTrace();
			    	}
			    }
			        
			    		
				 if(getWishListModel.size() ==0){
	                	file.setWishList(wishListModel);
	                }
				 if(productModel.size() >0){
                		getProdDetail.mergeWith(productModel);
                		file.setProduct(getProdDetail);
                	}
                	
				         		
				    String joinedWithPipe = TextUtils.join("|", Common.arrPdIdsForUserAnalytics);
				    Log.i("joined", ""+joinedWithPipe);
				    String screenName = "/mycloset/wishlists";
				    String productIds = joinedWithPipe;
		    		String offerIds = "";
				    Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
				    
			        fancyCoverFlow.setAdapter(renderForCoverFlow(wishlistFinalArray));
			        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
			        fancyCoverFlow.setMaxRotation(120);
			        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
						@Override
						public void onItemClick(AdapterView<?> arg0, View arg1,
								int arg2, long arg3) {
							// TODO Auto-generated method stub
							try{
								LinearLayout rl = (LinearLayout) fancyCoverFlow.getSelectedView();
			            		RelativeLayout ll =(RelativeLayout) rl.getChildAt(0);
			            		ImageView prodImage = (ImageView)ll.getChildAt(0);
			            		String[] arrStringId = prodImage.getTag().toString().split(",");
								BitmapDrawable test = (BitmapDrawable) prodImage.getDrawable();
								Bitmap bitmap = test.getBitmap();
								ByteArrayOutputStream baos = new ByteArrayOutputStream();
								bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
								byte[] b = baos.toByteArray();
								Intent intent = new Intent(WishListPage.this, ProductInfo.class);
								intent.putExtra("tapOnImage", false);	
								intent.putExtra("activityFlag", "wishlist");
								intent.putExtra("pageRedirectFlag", "wishlist");
								//intent.putExtra("image", b);	
								intent.putExtra("wishListName", txtWishListName.getText().toString());
								intent.putExtra("image",  ""+prodImage.getTag(R.string.imageUrl));
								intent.putExtra("productId",  ""+arrStringId[1]);
								intent.putExtra("clientId",""+arrStringId[0]);		
								startActivity(intent);
								overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
							}catch(Exception e){
								e.printStackTrace();
							}
						}	    			
		    		});
			        closetmodel.mergeWith(closetModelObj);	   		   				
	   				file.setCloset(closetmodel);
			     }
			else {
				txtProdNotAvail.setVisibility(View.VISIBLE);
			}
			}else {
				txtProdNotAvail.setVisibility(View.VISIBLE);
			}
	  	} catch(Exception e){
	  		e.printStackTrace();
	  		String errorMsg = className+" | wishListXmlResultFromServer      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
    	}
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, final Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			//Log.e("result code wishlist",""+requestCode);
			if(requestCode==1){
				//Log.e("result code activity",""+data);
				if(Common.isNetworkAvailable(WishListPage.this))
					getWishListResultsFromServerWithXml(Constants.WishList_Url+Common.sessionIdForUserLoggedIn+"/"+data.getStringExtra("wishListName"), WishListPage.this);
				else{
					if(data != null){
					 String activity=data.getStringExtra("activity");
					 //Log.e("result code activity",""+activity);	
					 if(activity.equals("menu")){
						 new Common().instructionBox(WishListPage.this, R.string.title_case7, R.string.instruction_case7);						 
					 }
					getWishListResultsFromFile(data.getStringExtra("wishListName"));
					}
				}
				
			}
			if(resultCode==1){
				//Log.e("result code activity",""+data);
				if(Common.isNetworkAvailable(WishListPage.this))
					getWishListResultsFromServerWithXml(Constants.WishList_Url+Common.sessionIdForUserLoggedIn+"/"+data.getStringExtra("wishListName"), WishListPage.this);
				else{
					if(data != null){
					 String activity=data.getStringExtra("activity");
					 if( activity != null  && activity.equals("menu")  ){
						 new Common().instructionBox(WishListPage.this, R.string.title_case7, R.string.instruction_case7);						 
					 }
					getWishListResultsFromFile(data.getStringExtra("wishListName"));
					}
				}
			}
		}catch (Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
			Toast.makeText(getApplicationContext(), "Error: Wishlist onActivityResult", Toast.LENGTH_LONG).show();
		}
		
	}

	 @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
    	try{
	        if (keyCode == KeyEvent.KEYCODE_BACK) {
	            onBackPressed();
	            isBackPressed = true;
	        }
	        // Call super code so we dont limit default interaction
	        return super.onKeyDown(keyCode, event);
    	} catch (Exception e) {
    		e.printStackTrace();
    		String errorMsg = className+" | onKeyDown      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
			Toast.makeText(getApplicationContext(), "Error: WishListPage onKeyDown.", Toast.LENGTH_LONG).show();
			return false;
		}
    }
    
    @Override
	public void onBackPressed() {
    	try{
    	Intent intent;
		if(pageRedirectFlag==null){
			intent = new Intent(getApplicationContext(), Closet.class);							
		} else if(pageRedirectFlag.equals("Closet")){
			intent = new Intent(getApplicationContext(), Closet.class);
		} else {
			intent = new Intent(getApplicationContext(), Closet.class);
		}
		//prodListPage.putExtra("finish", true);
		//prodListPage.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	startActivity(intent);
    		 finish();
			 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
    	} catch (Exception e) {
			// TODO: handle exception
    		e.printStackTrace();
    		String errorMsg = className+" | onBackPressed      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
			Toast.makeText(getApplicationContext(), "Error: WishListPage onBackPressed.", Toast.LENGTH_LONG).show();
		}
    }
    
    
    
    class loadWishListSelection extends AsyncTask<String, String, String> {
    	 
        /**
         * Before starting background thread Show Progress Dialog
         * */
		@Override
		protected void onPreExecute() {
			try {
				super.onPreExecute();
			} catch (Exception e) {
				e.printStackTrace();
				Toast.makeText(
						getApplicationContext(),
						"Error: WishlistPage loadWishListSelection  onPreExecute",
						Toast.LENGTH_LONG).show();
				String errorMsg = className+" | loadWishListSelection   onPreExecute   |   " +e.getMessage();
				 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
			}
		}
        /**
         * Creating product
         * */
        @Override
		protected String doInBackground(String... args) {
             try {
		
             }catch(Exception e){
            	 e.printStackTrace();
            	 Toast.makeText(getApplicationContext(),"Error: WishlistPage loadWishListSelection doInBackground", Toast.LENGTH_LONG).show();
            	 String errorMsg = className+" | loadWishListSelection  doInBackground    |   " +e.getMessage();
				 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
             }
			return null;
        }
        
		/**
         * After completing background task Dismiss the progress dialog
         * **/
        @Override
		protected void onPostExecute(String file_url) {
            // dismiss the dialog once done
        	try{
        		Intent intent = new Intent(getApplicationContext(), WishListSelectionActivity.class);  
        		intent.putExtra("id", ""+Common.sessionIdForUserLoggedIn);
        		intent.putExtra("wishlistname", txtWishListName.getText());
				int requestCode = 0;
				startActivityForResult(intent, requestCode);
        	 } catch (Exception e) {
                 e.printStackTrace();   
                 String errorMsg = className+" | loadWishListSelection  onPostExecute    |   " +e.getMessage();
				 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
      			Toast.makeText(getApplicationContext(),"Error: WishlistPage loadWishListSelection onPostExecute", Toast.LENGTH_LONG).show();
      		
             }
        }          
    }	
    
  
    
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
    int gridItemLayout = 0;
	    private ArrayAdapter<String> renderForCoverFlow(final String[] wishlistFinalArray2){	    	
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.coverflowitem_wishlist;	
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, wishlistFinalArray2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, gridItemLayout, parent);
					}	
					AQuery aq2 = new AQuery(convertView);		
				//	Log.e("position",""+position);
				//	Log.e("expClosetArray",""+wishlistFinalArray2+" "+wishlistFinalArray2[position]);
					if(wishlistFinalArray2[position]!=null){
						String s ="[";
						String q ="]";
						String w ="";
						String strReplaceSymbol = String.valueOf(wishlistFinalArray2[position]).replace(s, w).replace(q, w);
						//Log.e("expClosetArray",""+wishlistFinalArray2.length);
						String[] expClosetArray = strReplaceSymbol.split(",");
						//Log.e("expClosetArray",""+expClosetArray[0].trim());
						
						String expImageUrl = expClosetArray[0].trim();		
						String expClientId = expClosetArray[1].trim();		
						String expProductId = expClosetArray[2].trim();		
						String expProductName = expClosetArray[3].trim();			
						String expProductIsTryOn = expClosetArray[4].trim();		
						//String expClientLogo = expClosetArray[7].trim();	
						
						//0 is image url
						//1 is client id
						//2 is product id
						//3 is product name
						//4 is product is try on
						//Log.e("expClosetArray",""+expClosetArray[0].trim());
						Bitmap placeholder = aq2.getCachedImage(expImageUrl);
						if(placeholder==null){
							aq2.cache(expImageUrl, 1440000);					
						}
						
						ImageView img =(ImageView) convertView.findViewById(R.id.coverFlowImage);
						aq2.id(R.id.coverFlowImage).image(expImageUrl, true, true, 0, 0, placeholder, 0, 0);
						//Log.e("set tag",expClientId+","+expProductId);
						img.setTag(expClientId+","+expProductId);		
						img.setTag(R.string.imageUrl,expImageUrl);
						//String[] splitIds = imagesClientWithProdIdArrList2.get(position).split(",");

						RelativeLayout coverflowLlayout1 = (RelativeLayout) convertView.findViewById(R.id.coverflowLlayoutImage);
						LinearLayout.LayoutParams llpForLl = (LinearLayout.LayoutParams) coverflowLlayout1.getLayoutParams();
						llpForLl.width = (int) (0.667 * Common.sessionDeviceWidth);
						llpForLl.height = (int) (0.4611 * Common.sessionDeviceHeight);
						coverflowLlayout1.setLayoutParams(llpForLl);
						new Common().gradientDrawableCorners(WishListPage.this, null, coverflowLlayout1, 0.0334, 0.0167);
						
						RelativeLayout.LayoutParams llForCoverFlowImg1 = (RelativeLayout.LayoutParams) img.getLayoutParams();
						llForCoverFlowImg1.width = LayoutParams.MATCH_PARENT;
						llForCoverFlowImg1.height = LayoutParams.MATCH_PARENT;
						img.setLayoutParams(llForCoverFlowImg1);

						Button btnSeeItLive = (Button) convertView.findViewById(R.id.btnSeeItLive);	

						new Common().btnForSeeItLiveWithAllColors(WishListPage.this, btnSeeItLive, "relative", "width", "products", 
								expProductId, expClientId, Integer.parseInt(expProductIsTryOn), expClosetArray[5].trim(), expClosetArray[7].trim(), 
								expClosetArray[8].trim());
						
					
					}
						
				} catch (Exception e){
					e.printStackTrace();
	                 String errorMsg = className+" | renderForCoverFlow      |   " +e.getMessage();
					 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
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
	    // The rest of your onStart() code..
	    Tracker easyTracker = EasyTracker.getInstance(this);
		easyTracker.set(Fields.SCREEN_NAME, "/mycloset/wishlists");

		easyTracker.send(MapBuilder
		    .createAppView()
		    .build()
		);
		 String[] segments = new String[1];
			segments[0] = "Wishlist"; 
			QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
	  
		}catch(Exception e){
			e.printStackTrace();
			 String errorMsg = className+" | onStart      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
		}
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		//The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.
		 QuantcastClient.activityStop();
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(WishListPage.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(WishListPage.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(WishListPage.this,errorMsg);
			}
		}
}
