package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.Typeface;
import android.net.Uri;
import android.os.Bundle;
import android.text.InputFilter;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewAnimator;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.callback.ImageOptions;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.idautomation.LinearFontEncoder;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.ClientStores;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.Stores;
import com.seemoreinteractive.seemoreinteractive.Model.UserMyOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.flip3D.AnimationFactory;
import com.seemoreinteractive.seemoreinteractive.flip3D.AnimationFactory.FlipDirection;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class MyOffers extends Activity {
	
	Context context = this;
	String className = this.getClass().getSimpleName();
	AQuery aq;
	SessionManager session;
	String offerId = "",offerPurchaseUrl="";
	boolean flag = false;
	TextView txtNoOfferMsg;
	Button btnMyOffersCloset, btnMyOffersValue, btnMyOffersExpiration, btnMyOffersBrand, btnMyOffersMostRecent;
	ArrayList<String> clientOfferArrListImages, clientOfferArrListOfferIds,
			imagesArrListTitles, imagesArrListClientName, imagesArrListPrice,
			imagesArrListExpDates, clientOfferPurchaseUrlArrLists,
			clientOfferArrVerticalIds, clientOfferButtonName, clientBackgroundColor, 
			clientBackgroundLigtColor, clientBackgroundDarkColor,clientArrOfferName,clientArrOfferIsharable,clientArrOfferBackImage,clientArrOfferMultiRedeem;	
	ViewAnimator viewAnimator; 
	String offerImageId = "",offerIssharble="1";
	//ProgressBar progressBarForMyOffer;
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
	String pageRedirectFlag = null;
	ImageView imgOfferImage;	
	public static String changeOfferIds = "";
	
	String offerSelUrl,offerSelId,offerName,offerBackImage; 
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_my_offers);
		try{
		Intent getIntVals = getIntent();
		if(getIntVals.getExtras()!=null){
			pageRedirectFlag = getIntVals.getStringExtra("pageRedirectFlag");			
		}
		
		new Common().showDrawableImageFromAquery(this, R.drawable.seemore_back_button, R.id.imgvBtnBack);
		new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);	
		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, "ff2600",
				"ff2600",
				"ff2600",
				Common.sessionClientLogo, "My Offers", "");
		
		txtNoOfferMsg = (TextView) findViewById(R.id.txtNoOfferMsg);
		txtNoOfferMsg.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	       
		imgOfferImage = (ImageView) findViewById(R.id.imgvOfferImage);					
		bigImageLinearLayoutWidth = imgOfferImage.getLayoutParams().width;
		bigImageLinearLayoutHeight = imgOfferImage.getLayoutParams().height;
		
		
		new Common().showDrawableImageFromAquery(this, R.drawable.btn_trash, R.id.imgvBtnCloset);
		ImageView imgvBtnClosetTrash = (ImageView) findViewById(R.id.imgvBtnCloset);
		
		btnMyOffersCloset = (Button) findViewById(R.id.btnCloset);
		btnMyOffersValue = (Button) findViewById(R.id.btnValue);
		btnMyOffersExpiration = (Button) findViewById(R.id.btnExpiration);
		btnMyOffersBrand = (Button) findViewById(R.id.btnBrand);
		btnMyOffersMostRecent = (Button) findViewById(R.id.btnMostRecent);	
		Button myButtons[] = new Button[5];
		myButtons[0] = btnMyOffersBrand;
		myButtons[1] = btnMyOffersExpiration;
		myButtons[2] = btnMyOffersValue;
		myButtons[3] = btnMyOffersMostRecent;
		myButtons[4] = btnMyOffersCloset;

		RelativeLayout rlForMyOffers = (RelativeLayout) findViewById(R.id.bgRelativeLayout);	
		new Common().onGlobalLayoutForMyOffersButtons(MyOffers.this, 0.185, 0.04, myButtons, rlForMyOffers);
		
		viewAnimator = (ViewAnimator) findViewById(R.id.viewFlipper1);	
		
		ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
		imgBackButton.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
					if(viewAnimator.getDisplayedChild()==0){
						Intent intent;
						Log.i("pageRedirectFlag", ""+pageRedirectFlag);
						if(pageRedirectFlag!=null && pageRedirectFlag.equals("Closet")){
							intent = new Intent(getApplicationContext(), Closet.class);
							startActivity(intent);
							finish();
							overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
						} else {
							intent = new Intent(MyOffers.this, ARDisplayActivity.class);	
		    				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);						
		    				startActivity(intent); // Launch the HomescreenActivity
		    				finish(); 
		    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}
					} else {
						viewAnimator.showPrevious();
						offerSelUrl=clientOfferArrListImages.get(0);
						offerSelId=clientOfferArrListOfferIds.get(0);
						offerIssharble = clientArrOfferIsharable.get(0);
						offerBackImage = clientArrOfferBackImage.get(0);
					}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate   imgBackButton click |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
			}
		});	
		ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
		imgBtnCameraIcon.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{	
					//new Common().clickingOnBackButtonWithAnimationWithBackPressed(MyOffers.this, ARDisplayActivity.class, "0");
					Intent intent = new Intent(MyOffers.this, ARDisplayActivity.class);	
    				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);						
    				startActivity(intent); // Launch the HomescreenActivity
    				finish(); 
    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate   imgBtnCameraIcon click |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
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
	            		e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: MyOffers imgFooterMiddle.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | onCreate   imgFooterMiddle click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
					}
	            }
	    });

		aq = new AQuery(this);		
		 File file = new File(Constants.LOCATION+"userOffers");
		 Uri uri =Uri.parse(Constants.LOCATION+"userOffers");
		 if(!file.exists()){
			 sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, uri));
		 }
        session = new SessionManager(context);
		if(session.isLoggedIn())
        {
			if(Common.isNetworkAvailable(MyOffers.this)){
				getUserOffersDetails();
			}else{
				getMyOfferResultsFromFile("recent");
			}
			final String getOfferUrl = Constants.MyOffers_Url+Common.sessionIdForUserLoggedIn+"/";
			//Log.i("getOfferUrl", ""+getOfferUrl);
		//	getMyOfferResultsFromServerWithXml(getOfferUrl);
			MyOffersAllButtons(""+Common.sessionIdForUserLoggedIn);
			
			imgvBtnClosetTrash.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{
					FileTransaction file = new FileTransaction();
			        Offers myoffers = file.getMyOffers();				        
			        userMyOffers = myoffers.getAllMyOffers();
					if(Common.isNetworkAvailable(MyOffers.this))
					{		
				         if((userMyOffers.size() > 0)){
							AlertDialog.Builder alertDialog = new AlertDialog.Builder(MyOffers.this);		
					        alertDialog.setTitle("Confirm Delete...");
					        alertDialog.setMessage("Are you sure you want delete this My Offers?");
					        alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
					            @Override
					            public void onClick(DialogInterface dialog,int which) {
					            	Map<String, String> params = new HashMap<String, String>();
					            	 params.put("userId", ""+Common.sessionIdForUserLoggedIn);
					 			     params.put("offerId", offerSelId);
					 			     aq.ajax(Constants.DeleteMyOffers_Url+Common.sessionIdForUserLoggedIn, params,XmlDom.class, new AjaxCallback<XmlDom>(){			
					        			@Override
					        			public void callback(String url, XmlDom xml, AjaxStatus status) {
					        				try{
					            				if(xml!=null){
					            					if(xml.text("msg").equals("success")){
					        	    				//File file = new File(Constants.LOCATION+"myoffers");
				            	    			//	boolean deleted = file.delete();
				            						
				            						 FileTransaction fileTran = new FileTransaction();
				            						 Offers offers = fileTran.getMyOffers();
							    			         UserMyOffers userOffers = offers.getUserMyOffers(Integer.parseInt(offerSelId));
							    			         offers.removeOffer(userOffers);
							    			         offers.updateMyOffers(offers.getAllMyOffers());
							    			         fileTran.setMyOffers(offers);
							    			         
							    			        enableDisableButtonImagesWithText("most_recent");
				            	    				getMyOfferResultsFromFile("recent");
				        	    					MyOffersAllButtons(""+Common.sessionIdForUserLoggedIn);
				        	    					
				        	    				} else {
				        	    					Toast.makeText(context, "Delete failed. Please try again!", Toast.LENGTH_LONG).show();
				        	    				}
				            				}
				        				} catch(Exception e){
				        					e.printStackTrace();
				        					String errorMsg = className+" | onCreate   imgvBtnClosetTrash click |   " +e.getMessage();
				    			       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
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
				         }
				}else{
					 new Common().instructionBox(MyOffers.this,R.string.title_case7,R.string.instruction_case7);
				}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate   imgvBtnClosetTrash click |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
				}
			});
			
			final GridView myOffersGridView1 = (GridView) findViewById(R.id.myOffersGridView1);			
			ImageView imgBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			imgBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
						 
					try{
						if(offerIssharble.equals("1")){
							RelativeLayout abtLayoutMyOffers = (RelativeLayout) myOffersGridView1.getChildAt(0);		
							if(abtLayoutMyOffers!=null){		
								ImageView imgGridImage =(ImageView) abtLayoutMyOffers.getChildAt(0);
								//Bitmap bitmap = aq.getCachedImage(imgGridImage.getTag(R.string.img_offer_url).toString());
								Bitmap bitmap = aq.getCachedImage(offerSelUrl.toString());
								
								if(bitmap != null){
									ByteArrayOutputStream baos = new ByteArrayOutputStream();
									bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
									byte[] b = baos.toByteArray();
									Intent intent = new Intent(MyOffers.this, ShareActivity.class);
									intent.putExtra("pageFlag", "myOffers");		
									intent.putExtra("image", b);		
									intent.putExtra("offerUrl",  ""+offerSelUrl);
									intent.putExtra("offerId",""+offerSelId);
									intent.putExtra("offerName",""+offerName);
									startActivity(intent);
									overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	  
								}
							}	
					}else{
						AlertDialog.Builder alertDialog = new AlertDialog.Builder(MyOffers.this);		
				        alertDialog.setTitle("Share Offer");
				        alertDialog.setMessage("You cannot share this offer");
				        alertDialog.setPositiveButton("OK", new DialogInterface.OnClickListener() {
				            @Override
							public void onClick(DialogInterface dialog,int which) {
				            	dialog.dismiss();
				            }
				        });			 
				       	 
				        alertDialog.show();
				         
					}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnShare click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
					}
				}
			});
        }
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate    |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		}
	}

	 List<UserOffers> userOffers;
	 List<UserMyOffers> userMyOffers;
	public void getMyOfferResultsFromFile(String type){
		try{
				clientOfferArrListImages = new ArrayList<String>();
				clientOfferPurchaseUrlArrLists = new ArrayList<String>();	
				clientOfferArrListOfferIds = new ArrayList<String>();
				clientOfferArrVerticalIds = new ArrayList<String>();
				clientOfferButtonName = new ArrayList<String>();
				imagesArrListTitles = new ArrayList<String>();
				imagesArrListClientName = new ArrayList<String>();
				imagesArrListPrice = new ArrayList<String>();
				imagesArrListExpDates = new ArrayList<String>();
				clientBackgroundColor = new ArrayList<String>();
				clientBackgroundLigtColor = new ArrayList<String>();
				clientBackgroundDarkColor = new ArrayList<String>();
				clientArrOfferName  = new ArrayList<String>();
				clientArrOfferIsharable = new ArrayList<String>();
				clientArrOfferBackImage = new ArrayList<String>();
				clientArrOfferMultiRedeem = new ArrayList<String>();
				
				FileTransaction file = new FileTransaction();
		        Offers myoffers = file.getMyOffers();
		        Offers offers = file.getOffers();
		        
				if(type.equals("recent")){
		        	userMyOffers = myoffers.getAllMyOffers();
		        }
		        if(type.equals("brand")){
		        	userMyOffers = myoffers.getAllMyOffersByBrand();
		        }
		        if(type.equals("value")){
		        	userMyOffers = myoffers.getAllMyOffersByValue();
		        }
		        if(type.equals("expiration")){
		        	userMyOffers = myoffers.getAllMyOffersByExpiration();
		        }
				if(userMyOffers != null){
					int i =0;
					for ( final UserMyOffers usermyOffer : userMyOffers) {
						
						i++;
						
						offerId = ""+usermyOffer.getOfferId();		
						UserOffers userOffer = offers.getUserOffers(usermyOffer.getOfferId());
						
						offerPurchaseUrl = userOffer.getofferPurchaseUrl();
						txtNoOfferMsg.setVisibility(View.INVISIBLE);
						
						String offerImagesUrl = userOffer.getOfferImage().replaceAll(" ", "%20");
						Bitmap bitmap = aq.getCachedImage(offerImagesUrl);
						if(bitmap==null){
							aq.cache(offerImagesUrl, 14400000);
						}
						Common.arrOfferIdsForUserAnalytics.add(offerId);
						if(i==1){
							offerSelUrl    =  offerImagesUrl;
							offerSelId     = offerId;
							offerIssharble = userOffer.getOfferIsSharable();
							offerBackImage = userOffer.getOfferBackImage();
						}
						clientOfferArrListImages.add(offerImagesUrl);
						clientOfferArrListOfferIds.add(offerId);
						clientOfferArrVerticalIds.add(userOffer.getClientVerticalId()); 
						clientOfferButtonName.add(userOffer.getOfferButtonName());
						clientOfferPurchaseUrlArrLists.add(userOffer.getofferPurchaseUrl());
						imagesArrListTitles.add(userOffer.getOfferName());
						imagesArrListClientName.add(userOffer.getOfferClientName());		    			
		    			clientBackgroundColor.add(userOffer.getOfferClientBgColor());
		    			clientBackgroundLigtColor.add(userOffer.getOfferClientBgLightColor());
		    			clientBackgroundDarkColor.add(userOffer.getOfferClientBgDarkColor());
		    			clientArrOfferIsharable.add(userOffer.getOfferIsSharable());
		    			clientArrOfferBackImage.add(userOffer.getOfferBackImage());
		    			clientArrOfferMultiRedeem.add(userOffer.getOfferMultiRedeem());
		    			
						if(userOffer.getOfferDiscountType().equals("A")){							
							if(userOffer.getOfferDiscountValue().equals(""))
								imagesArrListPrice.add("");
							else if(userOffer.getOfferDiscountValue().equals("0") || userOffer.getOfferDiscountValue().equals("0.00"))
								imagesArrListPrice.add("Free");
							else if(!userOffer.getOfferDiscountValue().equals("null"))
								imagesArrListPrice.add(userOffer.getCurrencySymbol()+userOffer.getOfferDiscountValue()+" Off");
							else
								imagesArrListPrice.add("");
						} 
						else if(userOffer.getOfferDiscountType().equals("P")){
							if(userOffer.getOfferDiscountValue().equals(""))
								imagesArrListPrice.add("");
							else if(userOffer.getOfferDiscountValue().equals("0") || userOffer.getOfferDiscountValue().equals("0.00"))
								imagesArrListPrice.add("Free");
							else if(!userOffer.getOfferDiscountValue().equals("null"))
								imagesArrListPrice.add(userOffer.getOfferDiscountValue()+" % Off");
							else
								imagesArrListPrice.add("");
						}else if(userOffer.getOfferDiscountType().equals("R")){
							if(userOffer.getOfferDiscountValue().equals(""))
								imagesArrListPrice.add("");							
							else if(userOffer.getOfferDiscountValue().equals("0") || userOffer.getOfferDiscountValue().equals("0.00"))
								imagesArrListPrice.add("Free");
							else if(!userOffer.getOfferDiscountValue().equals("null"))								
								imagesArrListPrice.add(userOffer.getOfferDiscountValue()+" Reward Points");
							else
								imagesArrListPrice.add("");
						}else{
							imagesArrListPrice.add(userOffer.getOfferDiscountValue());
						}
							
						imagesArrListExpDates.add(userOffer.getOfferValidDate());
						
					}
				} else {
					findViewById(R.id.myOffersGridView1).setVisibility(View.INVISIBLE);
					txtNoOfferMsg.setVisibility(View.VISIBLE);
				}

				String joinedWithPipe = TextUtils.join("|", Common.arrOfferIdsForUserAnalytics);
				String screenName = "/myoffers";
				String productIds = "";
				String offerIds = joinedWithPipe;
				Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
		
				if(!offerId.equals("")){	
					render(clientOfferArrListImages,
							clientOfferArrListOfferIds, ""+Common.sessionIdForUserLoggedIn,
							imagesArrListTitles,
							imagesArrListClientName,
							imagesArrListExpDates, imagesArrListPrice,
							clientOfferPurchaseUrlArrLists,
							clientOfferArrVerticalIds,
							clientOfferButtonName, clientBackgroundColor, 
							clientBackgroundLigtColor, clientBackgroundDarkColor);
				}
		
		} catch (Exception e) {
			e.printStackTrace();
			String errorMsg = className+" | getMyOfferResultsFromFile    |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		} 
		
	}
	
	
		
	int myOffersGridItem = 0;

	private void render(final ArrayList<String> clientOfferArrListImages2, final ArrayList<String> clientOfferArrListOfferIds2, 
			String loggedInUserId2, final ArrayList<String> imagesArrListTitles2, final ArrayList<String> imagesArrListClientName2, 
			final ArrayList<String> imagesArrListExpDates2, final ArrayList<String> imagesArrListPrice2,final ArrayList<String> clientOfferPurchaseUrlArrLists, final ArrayList<String> clientOfferArrVerticalIds2, final ArrayList<String> clientOfferButtonName2, final ArrayList<String> clientBackgroundColor2, final ArrayList<String> clientBackgroundLigtColor2, final ArrayList<String> clientBackgroundDarkColor2){
		
		myOffersGridItem = R.layout.my_offers_grid_item;
		try{
			ArrayAdapter<String> aa = new ArrayAdapter<String>(this, myOffersGridItem, clientOfferArrListImages2){				
				@Override
				public View getView(final int position, View convertView, ViewGroup parent) {			
					try{
						if(convertView == null){
							convertView = aq.inflate(convertView, myOffersGridItem, parent);
						}			
						String photo = getItem(position);	
						AQuery aq2 = new AQuery(convertView);	
						
						String tbUrl = photo;			
					
						Bitmap bitmap1 = aq2.getCachedImage(tbUrl);
		    			if(bitmap1==null) {
		    				aq2.cache(tbUrl, 144000);
		    			}
						final ImageView imgGridImage =(ImageView) convertView.findViewById(R.id.imgGridImage);
				       
				        int mLayoutWidthX = (int) (0.02 * Common.sessionDeviceWidth);
				        int mLayoutHeightY = (int) (0.02 * Common.sessionDeviceHeight);
				       
						ImageOptions options = new ImageOptions();
						options.fileCache = true;
						options.memCache = true;
						options.targetWidth = 0;
						options.fallback = 0;
						options.preset = null;
						options.ratio = 0;
						options.animation = com.androidquery.util.Constants.FADE_IN;	

						TextView txtImgTitle = (TextView) convertView.findViewById(R.id.txtImageTitle);
						TextView txtImgClientName = (TextView) convertView.findViewById(R.id.txtImageClientName);
						TextView txtImagePrice = (TextView) convertView.findViewById(R.id.txtImagePrice);
						TextView txtImgExpDate = (TextView) convertView.findViewById(R.id.txtImageExpDate);
		    			aq2.id(R.id.imgGridImage).progress(R.id.progressBarForMyOffers).image(tbUrl, options);
						
						RelativeLayout.LayoutParams rlpForOfferGridImage = (RelativeLayout.LayoutParams) imgGridImage.getLayoutParams();
						rlpForOfferGridImage.width = LayoutParams.MATCH_PARENT;
						rlpForOfferGridImage.height = (int) (0.25 * Common.sessionDeviceHeight);
				        imgGridImage.setLayoutParams(rlpForOfferGridImage);
				        
						LinearLayout llOverlayText = (LinearLayout) convertView.findViewById(R.id.llOverlayText);
						RelativeLayout.LayoutParams rlptOverlay = (RelativeLayout.LayoutParams) llOverlayText.getLayoutParams();	
						rlptOverlay.width = LayoutParams.MATCH_PARENT;
						rlptOverlay.height = (int) (0.0615 * Common.sessionDeviceHeight);
						
						txtImgTitle.setText(imagesArrListTitles2.get(position));
						txtImgTitle.setTextSize((float)(0.024 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
						txtImgClientName.setText(imagesArrListClientName2.get(position));
						txtImgClientName.setTextSize((float)(0.024 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
						txtImagePrice.setText(imagesArrListPrice2.get(position));
						txtImagePrice.setTextSize(((float)(0.024 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity)-2);
						txtImgExpDate.setText("Exp. "+imagesArrListExpDates2.get(position));
						txtImgExpDate.setTextSize(((float)(0.024 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity)-2);
						
						int maxLength = (int) (0.06 * Common.sessionDeviceWidth);
						InputFilter[] fArray = new InputFilter[1];
						fArray[0] = new InputFilter.LengthFilter(maxLength);
						txtImgTitle.setFilters(fArray);
						txtImgClientName.setFilters(fArray);
						
						llOverlayText.setLayoutParams(rlptOverlay);
						
						Common.sessionClientBgColor = clientBackgroundColor2.get(position);
						Common.sessionClientBackgroundLightColor = clientBackgroundLigtColor2.get(position);
						Common.sessionClientBackgroundDarkColor = clientBackgroundDarkColor2.get(position);

						new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
								MyOffers.this, "ff2600",
								"ff2600",
								"ff2600",
								Common.sessionClientLogo, "My Offers", "");
						
						
		    			imgGridImage.setTag(R.string.img_offer_url, photo);
						imgGridImage.setTag(R.string.img_offer_id, clientOfferArrListOfferIds2.get(position));
						imgGridImage.setOnClickListener(new OnClickListener() {					
							@Override
							public void onClick(View arg0) {								
							  try{
									AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);
									viewAnimator.setTag(R.string.img_offer_id, clientOfferArrListOfferIds2.get(position));
									if(viewAnimator.getDisplayedChild()==1){
										offerImageId = viewAnimator.getTag(R.string.img_offer_id).toString();
										String getOfferUrl = Constants.MyOffers_Url+Common.sessionIdForUserLoggedIn+"/"+offerImageId+"/";
										offerSelUrl    =  clientOfferArrListImages2.get(position);
										offerSelId     =  clientOfferArrListOfferIds2.get(position);
										offerIssharble = clientArrOfferIsharable.get(position);
										offerBackImage = clientArrOfferBackImage.get(position);
										if(offerImageId != null)
											getMyOffersRedeemOfferResultsFromFile(Integer.parseInt(offerImageId));
									}
								
									Button imgvRedeemOfferImage = (Button) findViewById(R.id.imgvRedeemOfferImage);
									RelativeLayout.LayoutParams rlpRedeemOffer = (RelativeLayout.LayoutParams) imgvRedeemOfferImage.getLayoutParams();
					    			rlpRedeemOffer.bottomMargin = (int) (0.015 * Common.sessionDeviceHeight);
					    			
					    			imgvRedeemOfferImage.setLayoutParams(rlpRedeemOffer);
					    			//Log.i("offerBackImage",offerBackImage+"clientArrOfferMultiRedeem.get(position)"+clientArrOfferMultiRedeem.get(position));
					    			if(offerBackImage != null &&!offerBackImage.equals("null") && !clientArrOfferMultiRedeem.get(position).equals("null") && clientArrOfferMultiRedeem.get(position).equals("1")){
					    				imgvRedeemOfferImage.setText("Details");
					    				myOffersBackImage(Integer.parseInt(offerImageId));
					    			}
					    			else if(!clientOfferButtonName2.get(position).equals("") && clientOfferButtonName2.get(position)!= null){
								    	imgvRedeemOfferImage.setText(clientOfferButtonName2.get(position));
								    }else{
								    	imgvRedeemOfferImage.setText("Redeem Offer");
								    }
								    imgvRedeemOfferImage.setOnClickListener(new View.OnClickListener() {
								        @Override
								        public void onClick(View view) {
								        	try{
								        		if(!offerBackImage.equals("null")){
								        			AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);
								        			viewAnimator.setTag(R.string.img_offer_id, clientOfferArrListOfferIds2.get(position));
								        			Button imgRedeemOffer = (Button) findViewById(R.id.imgvBackRedeemOfferImage);
								        			imgRedeemOffer.setOnClickListener(new View.OnClickListener() {
												        @Override
												        public void onClick(View view) {
												        	try{												        		
												        		if(!clientOfferPurchaseUrlArrLists.get(position).equals("null")){
											        			 String[] separated = clientOfferPurchaseUrlArrLists.get(position).split(":");												
																if(separated[0]!=null && separated[0].equals("tel")){
																	Intent callIntent = new Intent(Intent.ACTION_CALL);
																	callIntent.setData(Uri.parse("tel://"+separated[1]));
												                   	startActivity(callIntent);
																} else if(separated[0]!=null && separated[0].equals("telprompt")){
																	Intent callIntent = new Intent(Intent.ACTION_CALL);
												                    callIntent.setData(Uri.parse("tel://"+separated[1]));
											                    	startActivity(callIntent);
																} else {
																	if(Common.isNetworkAvailable(MyOffers.this))
																	{													
																		Intent intent = new Intent(getApplicationContext(), OfferWebUrlActivity.class);		
																    	intent.putExtra("offerPurchseUrl", clientOfferPurchaseUrlArrLists.get(position));
																    	intent.putExtra("offerTitle", imagesArrListTitles2.get(position));
																    	finish();
																    	startActivity(intent);		
																	}else{
																	 new Common().instructionBox(MyOffers.this, R.string.title_case7, R.string.instruction_case7);
																	}
																}
														    	
															}else{
																if(Common.isNetworkAvailable(MyOffers.this))
																{
																 AlertDialog.Builder alertDialog = new AlertDialog.Builder(MyOffers.this);
																 alertDialog.setTitle("Offer Redeem");
															     alertDialog.setMessage("Would you like to redeem this offer?");
															     alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
															     @Override
														 		 public void onClick(DialogInterface dialog,int which) {
															      	aq = new AQuery(MyOffers.this); 
														           	aq.ajax(Constants.MyOffersReddem_Url+Common.sessionIdForUserLoggedIn+"/"+offerImageId+"/", XmlDom.class, new AjaxCallback<XmlDom>(){
														            			@Override
																				public void callback(String url, XmlDom xml, AjaxStatus status) {
														            				try{
														            					if(xml!=null){
																		    				if(xml.text("msg").equals("success")){
																		    					FileTransaction file = new FileTransaction();
																		    			        Offers offers = file.getMyOffers();
																		    			         UserMyOffers userOffers = offers.getUserMyOffers(Integer.parseInt(offerImageId));
																		    			         offers.removeOffer(userOffers);
																		    			         offers.updateMyOffers(offers.getAllMyOffers());
																					    		 file.setMyOffers(offers);
																		    			        														    					
																		    					if(viewAnimator.getDisplayedChild()==0){
																		    						Intent intent;
																		    						if(pageRedirectFlag!=null && pageRedirectFlag.equals("Closet")){
																		    							intent = new Intent(getApplicationContext(), Closet.class);							
																		    						} else {
																		    							intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
																		    						}
																		    						startActivity(intent);
																		    						finish();
																		    						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
																		    					} else {
																		    						 getMyOfferResultsFromFile("recent");
																		    						viewAnimator.showPrevious();
																		    						offerSelUrl    = clientOfferArrListImages.get(0);
																		    						offerSelId     = clientOfferArrListOfferIds.get(0);
																		    						offerIssharble = clientArrOfferIsharable.get(0);
																		    						offerBackImage = clientArrOfferBackImage.get(0);
																		    					}
																		    					
																							} else {
																								Toast.makeText(MyOffers.this, "Offer redeem failed. Please try again!", Toast.LENGTH_LONG).show();
																								
																							}			            					
															            				}
														            				} catch(Exception e){
														            					e.printStackTrace();
														            					String errorMsg = className+" | render getView  MyOffersReddem_Url   |   " +e.getMessage();
																			       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
														            				}
														            			}			            			
														            		});		
															            }
															        });
															 
															        // Setting Negative "NO" Button
															        alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
															            @Override
																		public void onClick(DialogInterface dialog, int which) {
															            // Write your code here to invoke NO event							         
															            dialog.cancel();
															            }
															        });
															 
															        // Showing Alert Message
															        alertDialog.show();
																}else{
																	 new Common().instructionBox(MyOffers.this, R.string.title_case7, R.string.instruction_case7);
																}												
															}
											        	} catch (Exception e) {
															// TODO: handle exception
											        		e.printStackTrace();
															Toast.makeText(getApplicationContext(), "Error: MyOffers imgvRedeemOfferImage.", Toast.LENGTH_LONG).show();
															String errorMsg = className+" | render getView  imgvRedeemOfferImage click  |   " +e.getMessage();
												       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
														}
											        }
											    });	
								        		}else{
								        		if(!clientOfferPurchaseUrlArrLists.get(position).equals("null")){
							        			 String[] separated = clientOfferPurchaseUrlArrLists.get(position).split(":");												
												if(separated[0]!=null && separated[0].equals("tel")){
													Intent callIntent = new Intent(Intent.ACTION_CALL);
													callIntent.setData(Uri.parse("tel://"+separated[1]));
								                   	startActivity(callIntent);
												} else if(separated[0]!=null && separated[0].equals("telprompt")){
													Intent callIntent = new Intent(Intent.ACTION_CALL);
								                    callIntent.setData(Uri.parse("tel://"+separated[1]));
							                    	startActivity(callIntent);
												} else {
													if(Common.isNetworkAvailable(MyOffers.this))
													{													
														Intent intent = new Intent(getApplicationContext(), OfferWebUrlActivity.class);		
												    	intent.putExtra("offerPurchseUrl", clientOfferPurchaseUrlArrLists.get(position));
												    	intent.putExtra("offerTitle", imagesArrListTitles2.get(position));
												    	finish();
												    	startActivity(intent);		
													}else{
													 new Common().instructionBox(MyOffers.this, R.string.title_case7, R.string.instruction_case7);
													}
												}
										    	
											}else{
												if(Common.isNetworkAvailable(MyOffers.this))
												{
												 AlertDialog.Builder alertDialog = new AlertDialog.Builder(MyOffers.this);
												 alertDialog.setTitle("Offer Redeem");
											     alertDialog.setMessage("Would you like to redeem this offer?");
											     alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
											     @Override
										 		 public void onClick(DialogInterface dialog,int which) {
											      	aq = new AQuery(MyOffers.this); 
										           	aq.ajax(Constants.MyOffersReddem_Url+Common.sessionIdForUserLoggedIn+"/"+offerImageId+"/", XmlDom.class, new AjaxCallback<XmlDom>(){
										            			@Override
																public void callback(String url, XmlDom xml, AjaxStatus status) {
										            				try{
										            					if(xml!=null){
														    				if(xml.text("msg").equals("success")){
														    					FileTransaction file = new FileTransaction();
														    			        Offers offers = file.getMyOffers();
														    			         UserMyOffers userOffers = offers.getUserMyOffers(Integer.parseInt(offerImageId));
														    			         offers.removeOffer(userOffers);
														    			         offers.updateMyOffers(offers.getAllMyOffers());
																	    		 file.setMyOffers(offers);
														    			        														    					
														    					if(viewAnimator.getDisplayedChild()==0){
														    						Intent intent;
														    						if(pageRedirectFlag!=null && pageRedirectFlag.equals("Closet")){
														    							intent = new Intent(getApplicationContext(), Closet.class);							
														    						} else {
														    							intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
														    						}
														    						startActivity(intent);
														    						finish();
														    						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
														    					} else {
														    						 getMyOfferResultsFromFile("recent");
														    						viewAnimator.showPrevious();
														    						offerSelUrl    = clientOfferArrListImages.get(0);
														    						offerSelId     = clientOfferArrListOfferIds.get(0);
														    						offerIssharble = clientArrOfferIsharable.get(0);
														    						offerBackImage = clientArrOfferBackImage.get(0);
														    					}
														    					
																			} else {
																				Toast.makeText(MyOffers.this, "Offer redeem failed. Please try again!", Toast.LENGTH_LONG).show();
																				
																			}			            					
											            				}
										            				} catch(Exception e){
										            					e.printStackTrace();
										            					String errorMsg = className+" | render getView  MyOffersReddem_Url   |   " +e.getMessage();
															       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
										            				}
										            			}			            			
										            		});		
											            }
											        });
											 
											        // Setting Negative "NO" Button
											        alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
											            @Override
														public void onClick(DialogInterface dialog, int which) {
											            // Write your code here to invoke NO event							         
											            dialog.cancel();
											            }
											        });
											 
											        // Showing Alert Message
											        alertDialog.show();
												}else{
													 new Common().instructionBox(MyOffers.this, R.string.title_case7, R.string.instruction_case7);
												}												
											}											
								        }
							        	} catch (Exception e) {
											// TODO: handle exception
							        		e.printStackTrace();
											Toast.makeText(getApplicationContext(), "Error: MyOffers imgvRedeemOfferImage.", Toast.LENGTH_LONG).show();
											String errorMsg = className+" | render getView  imgvRedeemOfferImage click  |   " +e.getMessage();
								       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
										}
							        }
							    });	
								    if(Common.isNetworkAvailable(MyOffers.this)){
									    String joinedWithPipe = TextUtils.join("|", Common.arrOfferIdsForUserAnalytics);
										String screenName = "/myoffers/details/"+offerImageId+"/"+imagesArrListTitles2.get(position);
										String productIds = "";
										String offerIds = "";
										Common.sendJsonWithAQuery(MyOffers.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
								    }
									
								}catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | render getView  imgGridImage click  |   " +e.getMessage();
						       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
								}
							}
						});	
						
					}catch(Exception e)
					{
						e.printStackTrace();
						String errorMsg = className+" | render getView    |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
					}
					return convertView;					
				}
			};
			aq.id(R.id.myOffersGridView1).adapter(aa); 	
		
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | render    |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		}
	}
	public void MyOffersAllButtons(final String loggedInUserId) {
		

		btnMyOffersBrand.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				try{
				enableDisableButtonImagesWithText("brand");
				getMyOfferResultsFromFile("brand");
				if(viewAnimator.getDisplayedChild()==1){
					viewAnimator.showPrevious();
				}
				if(viewAnimator.getDisplayedChild()==2){
	        		AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);
	        		Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
	        		easyTracker.set(Fields.SCREEN_NAME, "/myoffers/brands");
	        		easyTracker.send(MapBuilder
	        		    .createAppView()
	        		    .build()
	        		);
				}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | btnMyOffersBrand  click  |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
			}
		});
		btnMyOffersExpiration.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				try{
				enableDisableButtonImagesWithText("expiration");
				getMyOfferResultsFromFile("expiration");

				if(viewAnimator.getDisplayedChild()==1){
					viewAnimator.showPrevious();
				}
				if(viewAnimator.getDisplayedChild()==2){
	        		AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);
	        		
	        		Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
	        		easyTracker.set(Fields.SCREEN_NAME, "/myoffers/expiration");
	        		easyTracker.send(MapBuilder
	        		    .createAppView()
	        		    .build()
	        		);
				}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | btnMyOffersExpiration click    |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
			}
		});
		btnMyOffersValue.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				try{
				enableDisableButtonImagesWithText("value");
				getMyOfferResultsFromFile("value");
				if(viewAnimator.getDisplayedChild()==1){
					viewAnimator.showPrevious();
				}
				if(viewAnimator.getDisplayedChild()==2){
	        		AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);	        		
	        		Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
	        		easyTracker.set(Fields.SCREEN_NAME, "/myoffers/value");
	        		easyTracker.send(MapBuilder
	        		    .createAppView()
	        		    .build()
	        		);
				}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | btnMyOffersValue click    |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
			}
		});
		btnMyOffersMostRecent.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				try{
				enableDisableButtonImagesWithText("most_recent");
				getMyOfferResultsFromFile("recent");
				if(viewAnimator.getDisplayedChild()==1){
					viewAnimator.showPrevious();
				}
				if(viewAnimator.getDisplayedChild()==2){
	        		AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);	        		
	        		Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
	        		easyTracker.set(Fields.SCREEN_NAME, "/myoffers/mostrecent");
	        		easyTracker.send(MapBuilder
	        		    .createAppView()
	        		    .build()
	        		);
				}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | btnMyOffersMostRecent click    |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
			}
		});
		btnMyOffersCloset.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {						
				try{
				Intent intent = new Intent(MyOffers.this, Closet.class);	
				intent.putExtra("productId", Common.sessionProductId);
				intent.putExtra("productName", Common.sessionProductName);
				intent.putExtra("productPrice", Common.sessionProductPrice);
				intent.putExtra("productShortDesc", Common.sessionProductShortDesc);
				intent.putExtra("clientLogo", Common.sessionClientLogo);
				intent.putExtra("clientId", Common.sessionClientId);
				intent.putExtra("clientBackgroundImage", Common.sessionClientBgImage);
				intent.putExtra("clientImageName", Common.sessionClientName);
				intent.putExtra("clientBackgroundColor", Common.sessionClientBgColor);
				startActivity(intent);
				finish();
				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | btnMyOffersCloset click    |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				}
			}
		});
	}

	public void enableDisableButtonImagesWithText(String checkFlagFotBtn){
		try{
		if(checkFlagFotBtn.equals("brand")){
			btnMyOffersBrand.setBackgroundResource(R.drawable.offer_tab_left_enable);
			btnMyOffersBrand.setTextColor(Color.parseColor("#FFFFFF"));	
		} else {
			btnMyOffersBrand.setBackgroundResource(R.drawable.offer_tab_left);
			btnMyOffersBrand.setTextColor(Color.parseColor("#000000"));			
		}
		if(checkFlagFotBtn.equals("expiration")){
			btnMyOffersExpiration.setBackgroundResource(R.drawable.offer_tab_center_enable);
			btnMyOffersExpiration.setTextColor(Color.parseColor("#FFFFFF"));	
		} else {
			btnMyOffersExpiration.setBackgroundResource(R.drawable.offer_tab_center);
			btnMyOffersExpiration.setTextColor(Color.parseColor("#000000"));			
		}
		if(checkFlagFotBtn.equals("value")){
			btnMyOffersValue.setBackgroundResource(R.drawable.offer_tab_center_enable);
			btnMyOffersValue.setTextColor(Color.parseColor("#FFFFFF"));
		} else {
			btnMyOffersValue.setBackgroundResource(R.drawable.offer_tab_center);
			btnMyOffersValue.setTextColor(Color.parseColor("#000000"));			
		}
		if(checkFlagFotBtn.equals("most_recent")){
			btnMyOffersMostRecent.setBackgroundResource(R.drawable.offer_tab_center_enable);
			btnMyOffersMostRecent.setTextColor(Color.parseColor("#FFFFFF"));	
		} else {
			btnMyOffersMostRecent.setBackgroundResource(R.drawable.offer_tab_center);
			btnMyOffersMostRecent.setTextColor(Color.parseColor("#000000"));			
		}
		if(checkFlagFotBtn.equals("closet")){
			btnMyOffersCloset.setBackgroundResource(R.drawable.offer_tab_right_enable);
			btnMyOffersCloset.setTextColor(Color.parseColor("#FFFFFF"));	
		} else {
			btnMyOffersCloset.setBackgroundResource(R.drawable.offer_tab_right);
			btnMyOffersCloset.setTextColor(Color.parseColor("#000000"));			
		}	
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | enableDisableButtonImagesWithText     |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		}
	}
	public void getMyOffersRedeemOfferResultsFromServerWithXml(String xmlUrl){
		aq = new AQuery(this);
		aq.ajax(xmlUrl, XmlDom.class, this, "myOffersRedeemOfferResultsFromServerWithXml");
	}
	public void myOffersRedeemOfferResultsFromServerWithXml(String url, XmlDom xml, AjaxStatus status){
	  	try {
		    final List<XmlDom> myOffers = xml.tags("myOffers");
	    	if(myOffers.size()>0){	    		
			    for(final XmlDom myOfferXml : myOffers){
			    	try {
				    	if(myOfferXml.tag("offer_image")!=null){

							String offerImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
				    		Bitmap bitmap = aq.getCachedImage(offerImagesUrl);
			    			int newGeneratedWidth = 0;
			    			int newGeneratedHeight = 0;
			    			if(bitmap!=null){
				    			if(bitmap.getWidth()<=bitmap.getHeight())
					    			newGeneratedWidth = new ProductList().createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
					    		else if(bitmap.getWidth()>=bitmap.getHeight())
					    			newGeneratedHeight = new ProductList().createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
					    	} else {
			    				URL url1 = new URL(offerImagesUrl);
			    				aq.cache(offerImagesUrl, 14400000);
				    			Bitmap bitmap1 = BitmapFactory.decodeStream(url1.openStream());
				    			if(bitmap1.getWidth()<=bitmap1.getHeight())
					    			newGeneratedWidth = new ProductList().createNewWidthForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutHeight);
					    		else if(bitmap1.getWidth()>=bitmap1.getHeight())
					    			newGeneratedHeight = new ProductList().createNewHeightForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutWidth);
					    	}
			    			new Common().DownloadImageFromUrlBitmap(this, offerImagesUrl, bitmap, R.id.imgvOfferImage);

			        		RelativeLayout.LayoutParams rlParamsForRedeemPage = (RelativeLayout.LayoutParams) imgOfferImage.getLayoutParams();
			        		rlParamsForRedeemPage.width = LayoutParams.MATCH_PARENT;
			    			rlParamsForRedeemPage.height = (int) (0.27 * Common.sessionDeviceHeight);
			    			rlParamsForRedeemPage.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			    			rlParamsForRedeemPage.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
			    			imgOfferImage.setLayoutParams(rlParamsForRedeemPage);
			    			imgOfferImage.setScaleType(ScaleType.FIT_CENTER);
			    			
			    			TextView txtOfferName = (TextView) findViewById(R.id.txtvRedeemOfferName);
			    			RelativeLayout.LayoutParams rlpOfferName = (RelativeLayout.LayoutParams) txtOfferName.getLayoutParams();
			    			rlpOfferName.topMargin = (int) (0.031 * Common.sessionDeviceHeight);
			    			rlpOfferName.leftMargin = (int) (0.034 * Common.sessionDeviceWidth);
			    			txtOfferName.setText(myOfferXml.text("offer_name").toString());
			    			int maxLength = (int) (0.06 * Common.sessionDeviceWidth);
			    			InputFilter[] fArray = new InputFilter[1];
			    			fArray[0] = new InputFilter.LengthFilter(maxLength);			    			
			    			txtOfferName.setFilters(fArray);
			    			txtOfferName.setTextSize((float)(0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			    			txtOfferName.setLayoutParams(rlpOfferName);
			    			
			    			TextView txtOfferDesc = (TextView) findViewById(R.id.txtvRedeemOfferDesc1);
			    			RelativeLayout.LayoutParams rlpOfferDesc = (RelativeLayout.LayoutParams) txtOfferDesc.getLayoutParams();
			    			rlpOfferDesc.topMargin = (int) (0.015 * Common.sessionDeviceHeight);
					    	txtOfferDesc.setText(myOfferXml.text("offer_description").toString());
					    	txtOfferDesc.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
					    	txtOfferDesc.setLayoutParams(rlpOfferDesc);

					    	TextView txtOfferExpTimerIcon = (TextView) findViewById(R.id.txtvOfferExpTimerIcon);
					    	View viewLine = findViewById(R.id.viewLine);
					    	
					    	RelativeLayout.LayoutParams rlpForViewLine = (RelativeLayout.LayoutParams) viewLine.getLayoutParams();
					    	rlpForViewLine.width = (int) (0.92 * Common.sessionDeviceWidth);
					    	rlpForViewLine.height = 1;
					    	viewLine.setLayoutParams(rlpForViewLine);

					    	RelativeLayout.LayoutParams rlpExpTimer = (RelativeLayout.LayoutParams) txtOfferExpTimerIcon.getLayoutParams();
					    	txtOfferExpTimerIcon.setText("Exp. "+myOfferXml.text("offer_valid_to").toString());
					    	txtOfferExpTimerIcon.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
					    	txtOfferExpTimerIcon.setLayoutParams(rlpExpTimer);
			    			
					    	ImageView imgRedeemOffer = (ImageView) findViewById(R.id.imgvRedeemOfferImage);
			    			RelativeLayout.LayoutParams rlpRedeemOffer = (RelativeLayout.LayoutParams) imgRedeemOffer.getLayoutParams();
			    			rlpRedeemOffer.bottomMargin = (int) (0.015 * Common.sessionDeviceHeight);
			    			imgRedeemOffer.setScaleType(ScaleType.FIT_CENTER);
			    			imgRedeemOffer.setLayoutParams(rlpRedeemOffer);
					    	
					    	Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
					    	easyTracker.set(Fields.SCREEN_NAME, "/myoffers/redeem");
			        		easyTracker.send(MapBuilder
			        		    .createAppView()
			        		    .build()
			        		);
				    	}
					} catch (Exception e) {
						e.printStackTrace();
					} 
			    }
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | myOffersRedeemOfferResultsFromServerWithXml     |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
	  	}
	}
	
	public void getMyOffersRedeemOfferResultsFromFile(int offerId){
	  	try {
	  		FileTransaction file = new FileTransaction();
	        Offers offers = file.getOffers();
	        List<UserOffers> userReddemOffer = offers.getUserRedeemOffers(offerId);	       
	      	if(userReddemOffer != null){
			for ( final UserOffers userOffers : userReddemOffer) {				    	
					String offerImagesUrl = userOffers.getOfferImage().replaceAll(" ", "%20");
	    			Bitmap bitmap = aq.getCachedImage(offerImagesUrl);
	    			int newGeneratedWidth = 0;
	    			int newGeneratedHeight = 0;
	    			if(bitmap!=null){
		    			if(bitmap.getWidth()<=bitmap.getHeight())			    		
			    			newGeneratedWidth = new ProductList().createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
		    			else if(bitmap.getWidth()>=bitmap.getHeight())
			    			newGeneratedHeight = new ProductList().createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
			    	
	    			} else {
	    				URL url1 = new URL(offerImagesUrl);
	    				aq.cache(offerImagesUrl, 14400000);
		    			Bitmap bitmap1 = BitmapFactory.decodeStream(url1.openStream());
		    			if(bitmap1.getWidth()<=bitmap1.getHeight())
			    			newGeneratedWidth = new ProductList().createNewWidthForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutHeight);
			    		else if(bitmap1.getWidth()>=bitmap1.getHeight())
			    			newGeneratedHeight = new ProductList().createNewHeightForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutWidth);
	    			 }
	    			
	    			new Common().DownloadImageFromUrlBitmap(this, offerImagesUrl, bitmap, R.id.imgvOfferImage);

	        		RelativeLayout.LayoutParams rlParamsForRedeemPage = (RelativeLayout.LayoutParams) imgOfferImage.getLayoutParams();
	        		rlParamsForRedeemPage.width = LayoutParams.MATCH_PARENT;
	    			rlParamsForRedeemPage.height = (int) (0.27 * Common.sessionDeviceHeight);
	    			rlParamsForRedeemPage.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
	    			rlParamsForRedeemPage.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
	    			imgOfferImage.setLayoutParams(rlParamsForRedeemPage);
	    			imgOfferImage.setScaleType(ScaleType.FIT_CENTER);
	    			
	    			TextView txtOfferName = (TextView) findViewById(R.id.txtvRedeemOfferName);
	    			RelativeLayout.LayoutParams rlpOfferName = (RelativeLayout.LayoutParams) txtOfferName.getLayoutParams();
	    			rlpOfferName.topMargin = (int) (0.031 * Common.sessionDeviceHeight);
	    			rlpOfferName.leftMargin = (int) (0.034 * Common.sessionDeviceWidth);
	    			txtOfferName.setText(userOffers.getOfferName());
	    			int maxLength = (int) (0.06 * Common.sessionDeviceWidth);
	    			InputFilter[] fArray = new InputFilter[1];
	    			fArray[0] = new InputFilter.LengthFilter(maxLength);
	    			txtOfferName.setFilters(fArray);
	    			txtOfferName.setTextSize((float)(0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
	    			txtOfferName.setLayoutParams(rlpOfferName);
	    			
	    			TextView txtOfferDesc = (TextView) findViewById(R.id.txtvRedeemOfferDesc1);
	    			RelativeLayout.LayoutParams rlpOfferDesc = (RelativeLayout.LayoutParams) txtOfferDesc.getLayoutParams();
	    			rlpOfferDesc.topMargin = (int) (0.015 * Common.sessionDeviceHeight);
			    	txtOfferDesc.setText(userOffers.getOfferDescription());
			    	txtOfferDesc.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
			    	txtOfferDesc.setLayoutParams(rlpOfferDesc);

			    	TextView txtOfferExpTimerIcon = (TextView) findViewById(R.id.txtvOfferExpTimerIcon);
			    	View viewLine = findViewById(R.id.viewLine);
			    	
			    	RelativeLayout.LayoutParams rlpForViewLine = (RelativeLayout.LayoutParams) viewLine.getLayoutParams();
			    	rlpForViewLine.width = (int) (0.92 * Common.sessionDeviceWidth);
			    	rlpForViewLine.height = 1;
			    	viewLine.setLayoutParams(rlpForViewLine);

			    	RelativeLayout.LayoutParams rlpExpTimer = (RelativeLayout.LayoutParams) txtOfferExpTimerIcon.getLayoutParams();
			    	txtOfferExpTimerIcon.setText("Exp. "+userOffers.getOfferValidDate());
			    	txtOfferExpTimerIcon.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
			    	txtOfferExpTimerIcon.setLayoutParams(rlpExpTimer);
	    			
			    	Button imgRedeemOffer = (Button) findViewById(R.id.imgvRedeemOfferImage);
	    			RelativeLayout.LayoutParams rlpRedeemOffer = (RelativeLayout.LayoutParams) imgRedeemOffer.getLayoutParams();
	    			rlpRedeemOffer.width = (int)(0.84 * Common.sessionDeviceWidth);
	    			rlpRedeemOffer.height = LayoutParams.WRAP_CONTENT;
	    			rlpRedeemOffer.bottomMargin = (int) (0.015 * Common.sessionDeviceHeight);
	    			imgRedeemOffer.setLayoutParams(rlpRedeemOffer);
			    	
			    	Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
			    	easyTracker.set(Fields.SCREEN_NAME, "/myoffers/redeem");
	        		easyTracker.send(MapBuilder
	        		    .createAppView()
	        		    .build()
	        		);

					String screenName = "/myoffers/redeem/"+offerId;
					String productIds = "";
					String offerIds = ""+offerId;
					Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
					
				}
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getMyOffersRedeemOfferResultsFromFile     |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
	  	}
	}
	public void getMyOffersMarkAsRedeemedResultsFromServerWithXml(String xmlUrl){
		aq.ajax(xmlUrl, XmlDom.class, this, "myOffersMarkAsRedeemedXmlResultFromServer");
	}
	public void myOffersMarkAsRedeemedXmlResultFromServer(String url, XmlDom xml, AjaxStatus status){
	  	try {
		    final List<XmlDom> myOffers = xml.tags("myOffers");
		    if(myOffers.size()>0){	    		
			    for(final XmlDom myOfferXml : myOffers){
			    	try {/*
				    	if(myOfferXml.tag("offer_image")!=null){
							String offerImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
							String offerBarcodeImg = myOfferXml.text("offer_barcode_image").toString();
							String offerBarcodeNumber = myOfferXml.text("offer_barcode_number").toString();			    		
							
							Bitmap bitmap = aq.getCachedImage(offerImagesUrl);	    			
			    			Bitmap bitmap1 = aq.getCachedImage(offerBarcodeImg);
			    			//Log.i("bitmap", ""+bitmap);
			    			if(bitmap==null){
			    				aq.cache(offerImagesUrl, 14400000);
			    			}
			    			if(bitmap1==null){
			    				aq.cache(offerBarcodeImg, 14400000);
			    			}
							if(!offerBarcodeImg.equals("")){
								findViewById(R.id.imgvRedeemOfferImageBarCode).setVisibility(View.VISIBLE);
								findViewById(R.id.txtvBarCode).setVisibility(View.INVISIBLE);
								findViewById(R.id.txtvBarCodeNumber).setVisibility(View.INVISIBLE);
								new Common().DownloadImageFromUrlBitmap(this, offerBarcodeImg, bitmap1, R.id.imgvRedeemOfferImageBarCode);
								
								ImageView redeemOfferBarCodeImg = (ImageView) findViewById(R.id.imgvRedeemOfferImageBarCode);
								RelativeLayout.LayoutParams rlParamsForRedeemBarCodeImg = (RelativeLayout.LayoutParams) redeemOfferBarCodeImg.getLayoutParams();
								rlParamsForRedeemBarCodeImg.width = (int)(0.72 * Common.sessionDeviceWidth);
				    			rlParamsForRedeemBarCodeImg.height = (int) (0.36 * Common.sessionDeviceHeight);
				    			rlParamsForRedeemBarCodeImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
				    			rlParamsForRedeemBarCodeImg.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
				    			redeemOfferBarCodeImg.setLayoutParams(rlParamsForRedeemBarCodeImg);
				    			redeemOfferBarCodeImg.setScaleType(ScaleType.FIT_CENTER);
							} else if(!offerBarcodeNumber.equals("")){
								findViewById(R.id.imgvRedeemOfferImageBarCode).setVisibility(View.INVISIBLE);
								TextView txtBarCode = (TextView) findViewById(R.id.txtvBarCode);
								txtBarCode.setVisibility(View.VISIBLE);
								TextView txtBarCodeNumber = (TextView) findViewById(R.id.txtvBarCodeNumber);
								txtBarCodeNumber.setVisibility(View.VISIBLE);
								Typeface BarcodeFontFace = Typeface.createFromAsset(this.getAssets(),"fonts/IDAutomationSC128M.ttf");
								txtBarCode.setTypeface(BarcodeFontFace);
								LinearFontEncoder BCEnc = new LinearFontEncoder();
								txtBarCode.setText(String.format("%s",BCEnc.Code128(offerBarcodeNumber,true)));
								txtBarCodeNumber.setText(offerBarcodeNumber);
								
								RelativeLayout.LayoutParams rlParamsForRedeemBarCodeText = (RelativeLayout.LayoutParams) txtBarCode.getLayoutParams();
								rlParamsForRedeemBarCodeText.width = (int)(0.72 * Common.sessionDeviceWidth);
				    			rlParamsForRedeemBarCodeText.height = (int) (0.36 * Common.sessionDeviceHeight);
				    			rlParamsForRedeemBarCodeText.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
				    			rlParamsForRedeemBarCodeText.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
				    			txtBarCode.setLayoutParams(rlParamsForRedeemBarCodeText);
				    			txtBarCode.setTextSize((float)(0.12 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
								
								RelativeLayout.LayoutParams rlParamsForRedeemBarCodeNum = (RelativeLayout.LayoutParams) txtBarCodeNumber.getLayoutParams();
								rlParamsForRedeemBarCodeNum.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
								txtBarCodeNumber.setLayoutParams(rlParamsForRedeemBarCodeNum);
								txtBarCodeNumber.setTextSize((float)(0.065 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
								
							} else {
				    			
								findViewById(R.id.imgvRedeemOfferImageBarCode).setVisibility(View.VISIBLE);
								findViewById(R.id.txtvBarCode).setVisibility(View.INVISIBLE);
								findViewById(R.id.txtvBarCodeNumber).setVisibility(View.INVISIBLE);
								new Common().DownloadImageFromUrlBitmap(this, offerImagesUrl, bitmap, R.id.imgvRedeemOfferImageBarCode);
								
								ImageView redeemOfferBarCodeImg = (ImageView) findViewById(R.id.imgvRedeemOfferImageBarCode);
								RelativeLayout.LayoutParams rlParamsForRedeemBarCodeImg = (RelativeLayout.LayoutParams) redeemOfferBarCodeImg.getLayoutParams();
								rlParamsForRedeemBarCodeImg.width = (int)(0.72 * Common.sessionDeviceWidth);
				    			rlParamsForRedeemBarCodeImg.height = (int) (0.36 * Common.sessionDeviceHeight);
				    			rlParamsForRedeemBarCodeImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
				    			rlParamsForRedeemBarCodeImg.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
				    			redeemOfferBarCodeImg.setLayoutParams(rlParamsForRedeemBarCodeImg);
				    			redeemOfferBarCodeImg.setScaleType(ScaleType.FIT_CENTER);
							}
							
			    			TextView txtOfferName1 = (TextView) findViewById(R.id.txtvRedeemOfferName1);
			    			RelativeLayout.LayoutParams rlpOfferName1 = (RelativeLayout.LayoutParams) txtOfferName1.getLayoutParams();
			    			rlpOfferName1.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			    			txtOfferName1.setText(myOfferXml.text("offer_name").toString());
			    			int maxLength = (int) (0.06 * Common.sessionDeviceWidth);
			    			InputFilter[] fArray = new InputFilter[1];
			    			fArray[0] = new InputFilter.LengthFilter(maxLength);
			    			txtOfferName1.setFilters(fArray);
			    			txtOfferName1.setTextSize((float)(0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			    			txtOfferName1.setLayoutParams(rlpOfferName1);

			    			TextView txtOfferExpTimerIcon1 = (TextView) findViewById(R.id.txtvOfferExpTimerIcon1);
					    	View viewLine1 = findViewById(R.id.viewLine1);
					    	txtOfferExpTimerIcon1.setText("Exp. "+myOfferXml.text("offer_valid_to").toString());
					    	
					    	RelativeLayout.LayoutParams rlpForViewLine1 = (RelativeLayout.LayoutParams) viewLine1.getLayoutParams();
					    	rlpForViewLine1.width = (int) (0.92 * Common.sessionDeviceWidth);
					    	rlpForViewLine1.height = 1;
					    	viewLine1.setLayoutParams(rlpForViewLine1);

					    	RelativeLayout.LayoutParams rlpExpTimer = (RelativeLayout.LayoutParams) txtOfferExpTimerIcon1.getLayoutParams();
					    	txtOfferExpTimerIcon1.setText("Exp. "+myOfferXml.text("offer_valid_to").toString());
					    	txtOfferExpTimerIcon1.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
					    	txtOfferExpTimerIcon1.setLayoutParams(rlpExpTimer);
					    	
					    	Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
					    	easyTracker.set(Fields.SCREEN_NAME, "/myoffers/redeem/claim");
			        		easyTracker.send(MapBuilder
			        		    .createAppView()
			        		    .build()
			        		);
				    	}
					*/} catch (Exception e) {
						e.printStackTrace();
						String errorMsg = className+" | myOffersMarkAsRedeemedXmlResultFromServer  for loop   |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
					} 
			    }
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | myOffersMarkAsRedeemedXmlResultFromServer     |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
	  	}
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			if(requestCode == 1){
				if(!Common.isNetworkAvailable(MyOffers.this)){
					if(data != null){
						 String activity=data.getStringExtra("activity");						 
						 if(activity.equals("menu")){
							 new Common().instructionBox(MyOffers.this, R.string.title_case7, R.string.instruction_case7);
						 }
					 }
				}
			}
			
		}catch (Exception e){	
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult     |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
			
		}
		
	}
	
	

	@Override
	public void onBackPressed() 
	{
		try{
			if(viewAnimator.getDisplayedChild()==0){
				Intent intent;
				if(pageRedirectFlag!=null && pageRedirectFlag.equals("Closet")){
					intent = new Intent(getApplicationContext(), Closet.class);
					startActivity(intent);
					finish();
					overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
				}else if(pageRedirectFlag!=null && pageRedirectFlag.equals("Financial")){
					    intent = new Intent(getApplicationContext(), FinancialActivity.class);
					    intent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
					   // intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
					    finish();
					    startActivity(intent);
					    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					   
				}
				else {
					new Common().clickingOnBackButtonWithAnimationWithBackPressed(MyOffers.this, ARDisplayActivity.class, "0");
				}
				 super.onBackPressed();
				//viewAnimator.setDisplayedChild(0);
			} else {
				viewAnimator.showPrevious();
				offerSelUrl=clientOfferArrListImages.get(0);
				offerSelId=clientOfferArrListOfferIds.get(0);
				offerIssharble = clientArrOfferIsharable.get(0);
				offerBackImage = clientArrOfferBackImage.get(0);
			}	
		}catch (Exception e){	
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: MyOffers onBackPressed", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed     |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		}
			
	}
	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    Tracker easyTracker = EasyTracker.getInstance(this);
	  		easyTracker.set(Fields.SCREEN_NAME, "/myoffers");
	  		easyTracker.send(MapBuilder
	  		    .createAppView()
	  		    .build()
	  		);
	  		String[] segments = new String[1];
			segments[0] = "My Offers"; 
			QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart     |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
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
			 String errorMsg = className+" | onStop     |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(MyOffers.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(MyOffers.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
			}
		}
		
		
		public void getUserOffersDetails(){
			try{
				getMyOfferResultsFromFile("recent");
				clientOfferArrListImages = new ArrayList<String>();
				clientOfferPurchaseUrlArrLists = new ArrayList<String>();	
				clientOfferArrListOfferIds = new ArrayList<String>();
				clientOfferArrVerticalIds = new ArrayList<String>();
				clientOfferButtonName = new ArrayList<String>();
				imagesArrListTitles = new ArrayList<String>();
				imagesArrListClientName = new ArrayList<String>();
				imagesArrListPrice = new ArrayList<String>();
				imagesArrListExpDates = new ArrayList<String>();
				clientBackgroundColor = new ArrayList<String>();
				clientBackgroundLigtColor = new ArrayList<String>();
				clientBackgroundDarkColor = new ArrayList<String>();
				clientArrOfferName  = new ArrayList<String>();
				clientArrOfferIsharable = new ArrayList<String>();
				clientArrOfferBackImage = new ArrayList<String>();
				clientArrOfferMultiRedeem = new ArrayList<String>();
				
				final FileTransaction file = new FileTransaction();
				final String getOfferUrl = Constants.MyOffers_Url+"recent/"+Common.sessionIdForUserLoggedIn+"/";
				//final String loggedInUser = userId;
				Log.i("getOfferUrl", ""+getOfferUrl);
				aq = new AQuery(MyOffers.this);
				aq.progress(R.id.registerProgressBar).ajax(getOfferUrl, XmlDom.class, new AjaxCallback<XmlDom>(){
					@Override
					public void callback(String url, XmlDom xml, AjaxStatus status){
					  	try {
					  		Log.i("url", ""+url+" "+status);
					  		if(xml!=null){
							    final List<XmlDom> myOffers = xml.tags("myOffers");
							    Log.i("myOffers", ""+myOffers+" ");
							    if(myOffers.size()>0){	    
							    	Offers offers      = file.getOffers();
							    	Offers myoffers    = file.getMyOffers();
							    	Offers newoffers   = new Offers();
							    	Offers offersModel = new Offers();	
							    	
							    	ArrayList<String>  clientIdsList= new ArrayList<String>();
							    	int j = 0;
								    for(final XmlDom myOfferXml : myOffers){
								    	try {
										     
											if(myOfferXml.tag("offer_id")!=null){
												j++;
												
											
												/*offerId = myOfferXml.text("offer_id").toString();		
												offerPurchaseUrl = myOfferXml.text("offer_purchase_url").toString();
												txtNoOfferMsg.setVisibility(View.INVISIBLE);
												
												
												Common.arrOfferIdsForUserAnalytics.add(offerId);
												if(j==1){
												offerSelUrl    =  curveImagesUrl;
												offerSelId     = offerId;
												offerIssharble = myOfferXml.text("offer_is_sharable").toString();
												}
												clientOfferArrListImages.add(curveImagesUrl);
												clientOfferArrListOfferIds.add(myOfferXml.text("offer_id").toString());
												clientOfferArrVerticalIds.add(myOfferXml.text("client_vertical_id").toString()); 
												clientOfferButtonName.add(myOfferXml.text("offer_button_name").toString());
												if(myOfferXml.text("offer_purchase_url").toString().equals("")){											   		
											   		clientOfferPurchaseUrlArrLists.add("null");
											   	}else{
											   		clientOfferPurchaseUrlArrLists.add(myOfferXml.text("offer_purchase_url").toString());
											   	}
												
												imagesArrListTitles.add(myOfferXml.text("offer_name").toString());
												imagesArrListClientName.add(myOfferXml.text("name").toString());	
								    			clientBackgroundColor.add(myOfferXml.text("background_color").toString());
								    			clientBackgroundLigtColor.add(myOfferXml.text("light_color").toString());
								    			clientBackgroundDarkColor.add(myOfferXml.text("light_color").toString());
								    			clientArrOfferIsharable.add(myOfferXml.text("offer_is_sharable").toString());
								    			

								    			String joinedWithPipe = TextUtils.join("|", Common.arrOfferIdsForUserAnalytics);
												String screenName = "/myoffers";
												String productIds = "";
												String offerIds = joinedWithPipe;
												Common.sendJsonWithAQuery(MyOffers.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
										
												if(!offerId.equals("")){	
													render(clientOfferArrListImages,
															clientOfferArrListOfferIds, ""+Common.sessionIdForUserLoggedIn,
															imagesArrListTitles,
															imagesArrListClientName,
															imagesArrListExpDates, imagesArrListPrice,
															clientOfferPurchaseUrlArrLists,
															clientOfferArrVerticalIds,
															clientOfferButtonName, clientBackgroundColor, 
															clientBackgroundLigtColor, clientBackgroundDarkColor);
												}
								    			
								    			
								    			*/
													UserMyOffers usermyOffers= new UserMyOffers();
													usermyOffers.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));
													usermyOffers.setOfferClientId(myOfferXml.text("client_id").toString());
													usermyOffers.setOfferClientName(myOfferXml.text("name").toString());
													usermyOffers.setOfferName(myOfferXml.text("offer_name").toString());			
													if(myOfferXml.text("offer_discount_type").toString().equals("A")){
													   	String symbol = new Common().getCurrencySymbol(myOfferXml.text("country_languages").toString(), myOfferXml.text("country_code_char2").toString());
													   	usermyOffers.setCurrencySymbol(symbol);
														if (myOfferXml.text("offer_discount_value").toString().equals("null") || 
																myOfferXml.text("offer_discount_value").toString().equals("") || 
																myOfferXml.text("offer_discount_value").toString().equals("0") || 
																myOfferXml.text("offer_discount_value").toString().equals("0.00") || 
																myOfferXml.text("offer_discount_value").toString() == null) {
															usermyOffers.setOfferDiscountValue("0");
														} else {
															//userOffer.setOfferDiscountValue(symbol+myOfferXml.text("offer_discount_value").toString());
															usermyOffers.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
														}
												   	} else {
												   		usermyOffers.setCurrencySymbol("");
												   		usermyOffers.setOfferDiscountValue(myOfferXml.text("offer_discount_value").toString());
												   	}
												   	
													usermyOffers.setOfferValidDate(myOfferXml.text("offer_valid_to").toString());
													usermyOffers.setOfferDiscountType(myOfferXml.text("offer_discount_type").toString());
													   
													offersModel.addUserMyOffers(usermyOffers);
										    		
										    		UserOffers offerExist = offers.getUserOffers(Integer.parseInt(myOfferXml.text("offer_id").toString()));
													if(offerExist == null){															
														UserOffers userOffer = new UserOffers();
														userOffer.setOfferId(Integer.parseInt(myOfferXml.text("offer_id").toString()));										   	
													   	userOffer.setOfferImage(myOfferXml.text("offer_image").toString().replaceAll(" ", "%20"));
													   	userOffer.setOfferClientName(myOfferXml.text("name").toString());
													   	userOffer.setOfferName(myOfferXml.text("offer_name").toString());
													   	if(myOfferXml.text("offer_discount_type").toString().equals("A")){
														   	String symbol = new Common().getCurrencySymbol(myOfferXml.text("country_languages").toString(), myOfferXml.text("country_code_char2").toString());
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
														//userOffer.setProdRelatedId(myOfferXml.text("related_prodid").toString());
														//userOffer.setOfferRelatedId(myOfferXml.text("related_offerid").toString());
														userOffer.setOfferIsSharable(myOfferXml.text("offer_is_sharable").toString());
														//userOffer.setClientLocationBased(myOfferXml.text("is_location_based").toString());
														newoffers.add(userOffer);
													   	if(!clientIdsList.contains(myOfferXml.text("client_id").toString())){										   		
													   		clientIdsList.add(myOfferXml.text("client_id").toString());
													   	}
										   				j++;
										   					
										   				String curveImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
														Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
										    			if(bitmap1==null) {
										    				aq.cache(curveImagesUrl, 144000);
										    			}
										   				
										    		}
											}
											

								    		if(offers.size() >0){
								    			offers.mergeWithOffers(newoffers);
								    			file.setOffers(offers);
								    			file.setMyOffers(offersModel);
										   	}else{
										   		file.setOffers(newoffers);
										   		file.setMyOffers(offersModel);
										   	}		
											//file.setMyOffers(offersModel);
											//file.setOffers(offers);
											
								    	}catch(Exception e){
								    		e.printStackTrace();
								    	}
								    }
								    
								    getMyOfferResultsFromFile("recent");
								    
							    }
					  		}
						} catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | getUserOffersDetails ajax  call back |   " +e.getMessage();
		               	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
						}
					}
				});
				
				
			} catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | getUserOffersDetails |   " +e.getMessage();
				Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
				
			}
		}
		
		public void myOffersBackImage(int offerId){
		  	try {
		  		FileTransaction file = new FileTransaction();
		        Offers offers = file.getOffers();
		        List<UserOffers> userReddemOffer = offers.getUserRedeemOffers(offerId);	       
		      	if(userReddemOffer != null){
				for ( final UserOffers userOffers : userReddemOffer) {				    	
						String offerImagesUrl = userOffers.getOfferBackImage().replaceAll(" ", "%20");
		    			Bitmap bitmap = aq.getCachedImage(offerImagesUrl);
		    			int newGeneratedWidth = 0;
		    			int newGeneratedHeight = 0;
		    			if(bitmap!=null){
			    			if(bitmap.getWidth()<=bitmap.getHeight())			    		
				    			newGeneratedWidth = new ProductList().createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
			    			else if(bitmap.getWidth()>=bitmap.getHeight())
				    			newGeneratedHeight = new ProductList().createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
				    	
		    			} else {
		    				URL url1 = new URL(offerImagesUrl);
		    				aq.cache(offerImagesUrl, 14400000);
			    			Bitmap bitmap1 = BitmapFactory.decodeStream(url1.openStream());
			    			if(bitmap1.getWidth()<=bitmap1.getHeight())
				    			newGeneratedWidth = new ProductList().createNewWidthForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutHeight);
				    		else if(bitmap1.getWidth()>=bitmap1.getHeight())
				    			newGeneratedHeight = new ProductList().createNewHeightForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutWidth);
		    			 }
		    			
		    			new Common().DownloadImageFromUrlBitmap(this, offerImagesUrl, bitmap, R.id.imgvBackOfferImage);
		    			ImageView imgvBackOfferImage = (ImageView)findViewById(R.id.imgvBackOfferImage);
		        		RelativeLayout.LayoutParams rlParamsForRedeemPage = (RelativeLayout.LayoutParams) imgvBackOfferImage.getLayoutParams();
		        		rlParamsForRedeemPage.width = LayoutParams.MATCH_PARENT;
		    			rlParamsForRedeemPage.height = (int) (0.27 * Common.sessionDeviceHeight);
		    			rlParamsForRedeemPage.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
		    			rlParamsForRedeemPage.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
		    			imgvBackOfferImage.setLayoutParams(rlParamsForRedeemPage);
		    			imgvBackOfferImage.setScaleType(ScaleType.FIT_CENTER);
		    			
		    			TextView txtOfferName = (TextView) findViewById(R.id.txtvBackRedeemOfferName);
		    			RelativeLayout.LayoutParams rlpOfferName = (RelativeLayout.LayoutParams) txtOfferName.getLayoutParams();
		    			rlpOfferName.topMargin = (int) (0.031 * Common.sessionDeviceHeight);
		    			rlpOfferName.leftMargin = (int) (0.034 * Common.sessionDeviceWidth);
		    			txtOfferName.setText(userOffers.getOfferName());
		    			int maxLength = (int) (0.06 * Common.sessionDeviceWidth);
		    			InputFilter[] fArray = new InputFilter[1];
		    			fArray[0] = new InputFilter.LengthFilter(maxLength);
		    			txtOfferName.setFilters(fArray);
		    			txtOfferName.setTextSize((float)(0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		    			txtOfferName.setLayoutParams(rlpOfferName);
		    			
		    			TextView txtOfferDesc = (TextView) findViewById(R.id.txtvBackRedeemOfferDesc1);
		    			RelativeLayout.LayoutParams rlpOfferDesc = (RelativeLayout.LayoutParams) txtOfferDesc.getLayoutParams();
		    			rlpOfferDesc.topMargin = (int) (0.015 * Common.sessionDeviceHeight);
				    	txtOfferDesc.setText(userOffers.getOfferDescription());
				    	txtOfferDesc.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
				    	txtOfferDesc.setLayoutParams(rlpOfferDesc);

				    	TextView txtOfferExpTimerIcon = (TextView) findViewById(R.id.txtvBackOfferExpTimerIcon);
				    	View viewLine = findViewById(R.id.viewLine);
				    	
				    	RelativeLayout.LayoutParams rlpForViewLine = (RelativeLayout.LayoutParams) viewLine.getLayoutParams();
				    	rlpForViewLine.width = (int) (0.92 * Common.sessionDeviceWidth);
				    	rlpForViewLine.height = 1;
				    	viewLine.setLayoutParams(rlpForViewLine);

				    	RelativeLayout.LayoutParams rlpExpTimer = (RelativeLayout.LayoutParams) txtOfferExpTimerIcon.getLayoutParams();
				    	txtOfferExpTimerIcon.setText("Exp. "+userOffers.getOfferValidDate());
				    	txtOfferExpTimerIcon.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
				    	txtOfferExpTimerIcon.setLayoutParams(rlpExpTimer);
		    			
				    	Button imgRedeemOffer = (Button) findViewById(R.id.imgvBackRedeemOfferImage);
		    			RelativeLayout.LayoutParams rlpRedeemOffer = (RelativeLayout.LayoutParams) imgRedeemOffer.getLayoutParams();
		    			rlpRedeemOffer.width = (int)(0.84 * Common.sessionDeviceWidth);
		    			rlpRedeemOffer.height = LayoutParams.WRAP_CONTENT;
		    			rlpRedeemOffer.bottomMargin = (int) (0.015 * Common.sessionDeviceHeight);
		    			imgRedeemOffer.setLayoutParams(rlpRedeemOffer);
		    			imgRedeemOffer.setText(userOffers.getOfferButtonName());
		    			
				    	Tracker easyTracker = EasyTracker.getInstance(MyOffers.this);
				    	easyTracker.set(Fields.SCREEN_NAME, "/myoffers/redeem");
		        		easyTracker.send(MapBuilder
		        		    .createAppView()
		        		    .build()
		        		);

						String screenName = "/myoffers/redeem/"+offerId;
						String productIds = "";
						String offerIds = ""+offerId;
						Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						
					}
		    	}
		  	} catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | myOffersMarkAsRedeemedXmlResultFromServer     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(MyOffers.this,errorMsg);
		  	}
		}
		
}
