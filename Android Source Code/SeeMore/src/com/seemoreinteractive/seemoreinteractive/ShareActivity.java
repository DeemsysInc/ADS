package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Parcelable;
import android.text.Html;
import android.text.Spanned;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.auth.FacebookHandle;
import com.androidquery.auth.TwitterHandle;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.facebook.android.DialogError;
import com.facebook.android.Facebook;
import com.facebook.android.FacebookError;
import com.facebook.android.Facebook.DialogListener;
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
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class ShareActivity extends Activity {

	final Context context = this;
	String className = this.getClass().getSimpleName();
	public boolean isBackPressed = false;
	
	ImageView image;
	
	public byte[] rb;
	public Boolean alertErrorType = true;
	TextView txtClientName, txtProdPrice, txtProdId, txtProdName, txtProductDesc;
	public String getProductId = "0", getProductName = "null", getProductPrice = "null", getClientLogo = "null", getClientId = "null", 
			getClientBackgroundImage = "null", getFinalWebSiteUrl = "null", getClientBackgroundColor = "null", 
			getClientImageName = "null", getClientUrl = "null", getProductUrl = "null", getProductShortDesc = "null", 
			getProductDesc = "null",getPageFlag="null",offerId="null",offerName="null";
	
	String tryOn = "", getImageUrl=""; 

	SessionManager session;
	AQuery aq;
	//int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
	TextView txtvProdNotAvail;
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_share);			
		try{	
				
            aq = new AQuery(ShareActivity.this);
            session = new SessionManager(context);
		//	new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_2, R.id.imgvBtnCart);
            
            new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo,"Share", "");	
			
			
			txtvProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
			txtvProdNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
			
	    	ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);      
	    	imgBtnCart.setImageBitmap(null);
	    	
	    	ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{						
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: SeeMore Login imgvBtnBack.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgBackButton click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
					}
				}
			});
			image = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
			LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
			new Common().gradientDrawableCorners(ShareActivity.this, llBigImageWithName, null, 0.0334, 0.0167);
			RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
			rlpForLlImg.width = (int) (0.797 * Common.sessionDeviceWidth);
			rlpForLlImg.height = (int) (0.5072 * Common.sessionDeviceHeight);
			rlpForLlImg.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			llBigImageWithName.setLayoutParams(rlpForLlImg);
			
			LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) image.getLayoutParams();
			llParamsForShareImg.width = (int) (0.717 * Common.sessionDeviceWidth);
			llParamsForShareImg.height = (int) (0.4611 * Common.sessionDeviceHeight);
			image.setLayoutParams(llParamsForShareImg);
			image.setScaleType(ScaleType.FIT_CENTER);
			txtProdId = new TextView(this);
			txtProdName = (TextView)findViewById(R.id.txtvProductName);
			txtProdPrice = (TextView)findViewById(R.id.txtvProductPrice);
			txtProductDesc = (TextView)findViewById(R.id.txtProductDesc);
            
			if(!Common.sessionProductId.equals("null")){
				txtProdId.setText(Common.sessionProductId);
			}
			if(!Common.sessionProductName.equals("null")){
				txtProdName.setText(Common.sessionProductName);
			}
			if(!Common.sessionProductPrice.equals("null") && !Common.sessionProductPrice.equals("") ){
				txtProdPrice.setText(Common.sessionProductPrice);
			}
			if(!getProductDesc.equals("null")){
				txtProductDesc.setText(getProductDesc);	
			}
			
            RelativeLayout.LayoutParams rlpForProdName = (RelativeLayout.LayoutParams) txtProdName.getLayoutParams();
            rlpForProdName.topMargin = (int) (0.025 * Common.sessionDeviceHeight);
            txtProdName.setLayoutParams(rlpForProdName);
            txtProdName.setTextSize((float) (0.036 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
            
            
            txtProdPrice.setTextSize((float) (0.036 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
            
          
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
					intent.putExtra("screen_name", "share");
					startActivity(intent);	
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvHelp click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
						
					}
				}
			});            
            
			
			ImageView email =(ImageView) findViewById(R.id.email);
			RelativeLayout.LayoutParams rlpForEmail = (RelativeLayout.LayoutParams) email.getLayoutParams();
			rlpForEmail.width = (int) (0.2 * Common.sessionDeviceWidth);
			rlpForEmail.height = (int) (0.123 * Common.sessionDeviceHeight);
			rlpForEmail.leftMargin = (int) (0.04 * Common.sessionDeviceWidth);
			
			
			ImageView sms =(ImageView) findViewById(R.id.im);
			RelativeLayout.LayoutParams rlpForSms = (RelativeLayout.LayoutParams) sms.getLayoutParams();
			rlpForSms.width = (int) (0.2 * Common.sessionDeviceWidth);
			rlpForSms.height = (int) (0.123 * Common.sessionDeviceHeight);
			rlpForSms.leftMargin = (int) (0.04 * Common.sessionDeviceWidth);
			sms.setLayoutParams(rlpForSms);
			
			ImageView facebook =(ImageView) findViewById(R.id.facebook);
			RelativeLayout.LayoutParams rlpForFacebook = (RelativeLayout.LayoutParams) facebook.getLayoutParams();
			rlpForFacebook.width = (int) (0.2 * Common.sessionDeviceWidth);
			rlpForFacebook.height = (int) (0.123 * Common.sessionDeviceHeight);
			rlpForFacebook.leftMargin = (int) (0.04 * Common.sessionDeviceWidth);
			rlpForFacebook.bottomMargin = (int) (0.01 * Common.sessionDeviceHeight);
			facebook.setLayoutParams(rlpForFacebook);
			
			ImageView twitter =(ImageView) findViewById(R.id.twitter);
			RelativeLayout.LayoutParams rlpForTwitter = (RelativeLayout.LayoutParams) twitter.getLayoutParams();
			rlpForTwitter.width = (int) (0.2 * Common.sessionDeviceWidth);
			rlpForTwitter.height = (int) (0.123 * Common.sessionDeviceHeight);
			rlpForTwitter.leftMargin = (int) (0.04 * Common.sessionDeviceWidth);
			twitter.setLayoutParams(rlpForTwitter);
			
			Intent getIntVals = getIntent();
			if(Common.isNetworkAvailable(ShareActivity.this)){
			if(getIntVals.getExtras()!=null){
				getProductId = getIntVals.getStringExtra("productId");
				getClientId = getIntVals.getStringExtra("clientId");
				
				
				getPageFlag = getIntVals.getStringExtra("pageFlag");
				aq = new AQuery(ShareActivity.this);
				
				if(getPageFlag != null && getPageFlag.equals("myOffers")){
					 offerId = getIntVals.getStringExtra("offerId");	
					 offerName = getIntVals.getStringExtra("offerName");	
					 
					 String getOfferUrl = Constants.MyOffers_Url+Common.sessionIdForUserLoggedIn+"/"+offerId+"/";
					 getMyOffersRedeemOfferResultsFromServerWithXml(getOfferUrl);
				}else{
					getProductInfoResultsFromServerWithXml(Constants.ClientProdDetails_Url+getClientId+"/"+getProductId+"/");
				}		
			} else {
				image.setImageBitmap(null);
				txtvProdNotAvail.setVisibility(View.VISIBLE);
			}
			}else{
						
						if(getIntVals.getExtras()!=null){
							getProductId 	= getIntVals.getStringExtra("productId");
							getClientId 	= getIntVals.getStringExtra("clientId");
							Bitmap bmp = aq.getCachedImage(getIntent().getStringExtra("image"));
							getPageFlag = getIntVals.getStringExtra("pageFlag");
							image.setImageBitmap(bmp);
							
							if(getPageFlag != null && getPageFlag.equals("myOffers")){
								 offerId = getIntVals.getStringExtra("offerId");	
								 offerName = "null";	
								 getOfferResultsFromFile(Integer.parseInt(offerId));
							}else{
								getProductInfoResultsFromFile(getProductId);
							}	
				}
			}
			email.setLayoutParams(rlpForEmail);
			email.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					
					File file = createTempFile();						
					try {
						if(Common.isNetworkAvailable(ShareActivity.this)){
						if (file != null) {
							String screenName = "";
							String productIds = "";
  							String offerIds = "";
							if(offerId.equals("null") || offerId.equals("")){
								screenName = "/product/shareproduct/email/"+getProductId+"/"+getProductName;
								productIds = getProductId;
	  							offerIds = "";
							} else {
								screenName = "/myoffers/shareoffer/email/"+offerId+"/"+offerName;
								productIds = "";
	  							offerIds = offerId;
							}
							Common.sendJsonWithAQuery(ShareActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
							
							Intent intent = new Intent(Intent.ACTION_SEND);
							intent.setType("message/rfc822");							
							if(getPageFlag != null && getPageFlag.equals("myOffers"))
								intent.putExtra(Intent.EXTRA_SUBJECT, "Check this out");
							else
								intent.putExtra(Intent.EXTRA_SUBJECT, getProductName);
							String prodInfoText = "";
							prodInfoText += "<p>&nbsp;</p><p>Download SeeMore Interactive at iTunes/Google Play.</p>";
							prodInfoText += "<p><a href='https://itunes.apple.com/us/app/seemore-interactive/id591304180'>https://itunes.apple.com/us/app/seemore-interactive/id591304180</a></p>";
							prodInfoText += "<p><a href='https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive'>https://play.google.com/store/apps/details?id=com.seemoreinteractive.seemoreinteractive</a></p>";
							prodInfoText += "<p>Create your own list or gift a friend</p>";

							prodInfoText += "<p>&nbsp;</p>Powered By<br/>";
							prodInfoText += "Seemore Interactive.<br/>";
							prodInfoText += "<a href='http://www.seemoreinteractive.com/'>http://www.seemoreinteractive.com/<a><br/>";
							if(getPageFlag != null && getPageFlag.equals("myOffers")){
							
							intent.putExtra(
							         Intent.EXTRA_TEXT,
							         Html.fromHtml(new StringBuilder()
							             .append("<p>Check out what I'm seeing! Brought to you by SeeMore Interactive.</p>")
							             .append("<p><b>"+'"'+offerName+'"'+"</b></p>")
							             .append(prodInfoText)
							             .toString())
							         );
							}else{
								intent.putExtra(
										Intent.EXTRA_TEXT,
						         Html.fromHtml(new StringBuilder()
						             .append("<p>Check out what I'm seeing! Brought to you by SeeMore Interactive.</p>")
						             .append("<p><b>"+getProductName+"</b></p>")
						             .append("<a>"+getFinalWebSiteUrl+"</a>")
						             .append("<small><p>"+getProductDesc+"</p></small>")
						             .append(prodInfoText)
						             .toString())
						         );
							}
							intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(file));
							startActivity(intent);
						}
						}else{							
							new Common().instructionBox(ShareActivity.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch (Exception e) {
						Toast.makeText(ShareActivity.this, "Error: ShareActivity Unable to open installed email apps", Toast.LENGTH_SHORT).show();
					      e.printStackTrace();
					      String errorMsg = className+" | email click   |   " +e.getMessage();
						  Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
					      
					}
				}
					
			});
			
			
			sms.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						if(Common.isNetworkAvailable(ShareActivity.this)){								
							File file = createTempFile();
							if (file != null) {
								String screenName = "";
								String productIds = "";
	  							String offerIds = "";
								if(offerId.equals("null") || offerId.equals("")){
									screenName = "/product/shareproduct/sms/"+getProductId+"/"+getProductName;
									productIds = getProductId;
		  							offerIds = "";
								} else {
									screenName = "/myoffers/shareoffer/sms/"+offerId+"/"+offerName;
									productIds = "";
		  							offerIds = offerId;
								}
								
								Intent intent = new Intent(Intent.ACTION_SEND);
								intent.putExtra("sms_body",  Html.fromHtml(new StringBuilder()
					             .append("<p>Check out what I'm seeing! Brought to you by SeeMore Interactive.</p>")
					             .append("<p><b>"+getProductName+"</b></p>")
					             .append("<a>"+getFinalWebSiteUrl+"</a>")
					             .append("<small><p>"+getProductDesc+"</p></small>")
					             .toString())
								);
								intent.setType("image/jpeg");
								intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(file));
								startActivity(Intent.createChooser(intent, "Share"));
							}
							
							Intent intent = new Intent(Intent.ACTION_SEND);
							intent.putExtra("sms_body",  Html.fromHtml(new StringBuilder()
				             .append("<p>Check out what I'm seeing! Brought to you by SeeMore Interactive.</p>")
				             .append("<p><b>"+getProductName+"</b></p>")
				             .append("<a>"+getFinalWebSiteUrl+"</a>")
				             .append("<small><p>"+getProductDesc+"</p></small>")
				             .toString())
							);
							intent.setType("image/jpeg");
							intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(file));
							startActivity(Intent.createChooser(intent, "Share"));
						}else{
							new Common().instructionBox(ShareActivity.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: ShareActivity clicked on share.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | sms click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
					}
				}
			});
		
			facebook.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						if(Common.isNetworkAvailable(ShareActivity.this)){
						//Toast.makeText(getApplicationContext(), "fb", Toast.LENGTH_LONG).show();
						File file = createTempFile();
						if (file != null) {
							String screenName = "";
							String productIds = "";
  							String offerIds = "";
							
							String imageName = file.toString().substring(file.toString().lastIndexOf('/') + 1, file.toString().length());
							File filePath = new File(getApplicationContext().getExternalCacheDir()+ "/"+imageName);
							auth_facebook_sso(filePath.toString());
							
							if(offerId.equals("null") || offerId.equals("")){
								screenName = "/product/shareproduct/facebook/"+getProductId+"/"+getProductName;
								productIds = getProductId;
	  							offerIds = "";
							} else {
								screenName = "/myoffers/shareoffer/facebook/"+offerId+"/"+offerName;
								productIds = "";
	  							offerIds = offerId;
							}
							Common.sendJsonWithAQuery(ShareActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						}
						}else{
							new Common().instructionBox(ShareActivity.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: ShareActivity clicked on share.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | facebook click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
					}
				}
			});

		
			twitter.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						if(Common.isNetworkAvailable(ShareActivity.this)){							
						File file = createTempFile();
						if (file != null) {
							String screenName = "";
							String productIds = "";
  							String offerIds = "";
							if(offerId.equals("null") || offerId.equals("")){
								screenName = "/product/shareproduct/twitter/"+getProductId+"/"+getProductName;
								productIds = getProductId;
	  							offerIds = "";
							} else {
								screenName = "/myoffers/shareoffer/twitter/"+offerId+"/"+offerName;
								productIds = "";
	  							offerIds = offerId;
							}
							Common.sendJsonWithAQuery(ShareActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
							
							String imageName = file.toString().substring(file.toString().lastIndexOf('/') + 1, file.toString().length());
							final File filePath = new File(getApplicationContext().getExternalCacheDir()+ "/"+imageName);
							LayoutInflater li = LayoutInflater.from(ShareActivity.this);
							View promptsView = li.inflate(R.layout.twitter_dialog, null);
							AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(ShareActivity.this);
							alertDialogBuilder.setView(promptsView);
							final EditText tweet = (EditText) promptsView.findViewById(R.id.twitter_tweet);
							tweet.setText("@By SeemoreInteractive");
							alertDialogBuilder.setCancelable(true).setPositiveButton("OK", new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog, int id) {
									try{
										 Bitmap bitmap = BitmapFactory.decodeFile(filePath.toString());
									        byte[] dataByte = null;
									        ByteArrayOutputStream baos = new ByteArrayOutputStream();
									        bitmap.compress(Bitmap.CompressFormat.PNG, 100, baos);
									        dataByte = baos.toByteArray();
											Intent intent = new Intent(ShareActivity.this, TwitterActivity.class);
											intent.putExtra("status",tweet.getText().toString());
											intent.putExtra("image",dataByte);
											startActivity(intent);
									} catch (Exception e) {
										Toast.makeText(getApplicationContext(), "Error: ShareActivty clicked on twitter alertDialogBuilder.", Toast.LENGTH_LONG).show();
									}
								}
							}).setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog, int id) {
									dialog.cancel();
								}
							});
							AlertDialog alertDialog = alertDialogBuilder.create();
							alertDialog.setTitle("Enter Tweet");
							alertDialog.show();
						}
						}else{
							new Common().instructionBox(ShareActivity.this,R.string.title_case7,R.string.instruction_case7);
						}
					} catch (Exception e) {
						//Toast.makeText(getApplicationContext(), "Error: ShareActivity clicked on share.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | twitter click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
					}
				}
			});

			
		} catch (Exception e) {
			// TODO: handle exception
			Log.i("ProdInfo OnCreate: ", "Error: "+e.getMessage());
			e.printStackTrace();
			//Toast.makeText(getApplicationContext(), "Error: ProductInfo oncreate.", Toast.LENGTH_LONG).show();
		}
	}
	
	public void getOfferResultsFromFile(int offerId){
	  	try {
	  		FileTransaction file = new FileTransaction();
	        Offers offers = file.getOffers();
	        List<UserOffers> userReddemOffer = offers.getUserRedeemOffers(offerId);
	       
	      	if(userReddemOffer != null){
			for ( final UserOffers userOffers : userReddemOffer) {
				    	
					String offerImagesUrl = userOffers.getOfferImage().replaceAll(" ", "%20");
					new Common().DownloadImageFromUrl(ShareActivity.this, offerImagesUrl, R.id.imgvProdInfoBigImg);

					ImageView shareImg = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
					
					LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
					new Common().gradientDrawableCorners(ShareActivity.this, llBigImageWithName, null, 0.0334, 0.0167);
					RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
					rlpForLlImg.width = (int) (0.797 * Common.sessionDeviceWidth);
	    			rlpForLlImg.height = (int) (0.5072 * Common.sessionDeviceHeight);
	    			rlpForLlImg.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
	    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
	    			llBigImageWithName.setLayoutParams(rlpForLlImg);
	    			
					LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) shareImg.getLayoutParams();
					llParamsForShareImg.width = (int) (0.717 * Common.sessionDeviceWidth);
	    			llParamsForShareImg.height = (int) (0.4611 * Common.sessionDeviceHeight);
	    			shareImg.setLayoutParams(llParamsForShareImg);
	    			shareImg.setScaleType(ScaleType.FIT_CENTER);
	    			
		    		Bitmap bitmap = aq.getCachedImage(offerImagesUrl);
	    			if(bitmap==null){
	    				aq.cache(offerImagesUrl, 14400000);
	    			}
	    			offerName =userOffers.getOfferName();
	    			offerId =userOffers.getOfferId();
	    			txtProdName.setText(offerName);
	    			txtProductDesc.setText("Exp. "+userOffers.getOfferValidDate());
	    			getClientId =userOffers.getOfferClientId();
	    			getClientImageName =userOffers.getOfferClientName();
	    			getProductName = userOffers.getOfferName();
					
				}
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getOfferResultsFromFile    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
	  	}
	}
	
	private void getProductInfoResultsFromFile(String getProductId2) {
		try{
			
			FileTransaction file = new FileTransaction();
			ProductModel productmodel = file.getProduct();
			if(getProductId2 != null){
				UserProduct productDetails = productmodel.getUserProductById(Integer.parseInt(getProductId));
				
				if(productDetails != null){
				aq= new AQuery(this);
				ImageView shareImg = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
				
				LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
				new Common().gradientDrawableCorners(ShareActivity.this, llBigImageWithName, null, 0.0334, 0.0167);
				RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
				rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
				rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
				rlpForLlImg.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
				rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
				llBigImageWithName.setLayoutParams(rlpForLlImg);
				
				LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) shareImg.getLayoutParams();
				llParamsForShareImg.width = (int) (0.72 * Common.sessionDeviceWidth);
				llParamsForShareImg.height = (int) (0.46 * Common.sessionDeviceHeight);
				shareImg.setLayoutParams(llParamsForShareImg);
				shareImg.setScaleType(ScaleType.FIT_CENTER);
	
				//get the bitmap for a previously fetched thumbnail	    			
				String curveImagesUrl = productDetails.getImageFile().replaceAll(" ", "%20");	
				Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
				if(bitmap==null) {
					aq.cache(curveImagesUrl, 144000);
				}
				shareImg.setImageBitmap(bitmap);
				getProductName = productDetails.getProductName();
				getProductPrice = productDetails.getProductPrice();
				getClientLogo = productDetails.getClientLogo();
				getClientId = ""+productDetails.getClientId();
				getProductShortDesc =productDetails.getProductShortDesc();
				getProductDesc =productDetails.getProductDesc();
				getClientBackgroundImage = productDetails.getClientBackgroundImage();
				getClientBackgroundColor = productDetails.getClientBackgroundColor();
				getClientImageName =productDetails.getClientName();
				getClientUrl = productDetails.getClientUrl();
				getProductUrl =productDetails.getProductUrl();
	
				if(getProductUrl.equals("null")){
					getFinalWebSiteUrl = getClientUrl;
				} else {
					getFinalWebSiteUrl = getProductUrl;
				}
				txtProdId.setText(getProductId);
				txtProdName.setText(getProductName);
				if(!getProductPrice.equals("null") && !getProductPrice.equals("") )
					txtProdPrice.setText(getProductPrice);
				if(!getProductDesc.equals("null")){
					txtProductDesc.setText(getProductDesc);
				}
			 }
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getProductInfoResultsFromFile    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
		}
		
	}

	public void getProductInfoResultsFromServerWithXml(String getProductUrl){
	    aq.ajax(getProductUrl, XmlDom.class, this, "productInfoXmlResultFromServer");        
	}
	public void productInfoXmlResultFromServer(String url, XmlDom xml, AjaxStatus status){
	  	try {
	  		   
		    final List<XmlDom> products = xml.tags("products");
		    if(products.size()>0){	    		
			    for(final XmlDom pdXml : products){
			    	try {
						String productImagesUrl = pdXml.text("pdImage").toString().replaceAll(" ", "%20");
						new Common().DownloadImageFromUrl(ShareActivity.this, productImagesUrl, R.id.imgvProdInfoBigImg);
						
						ImageView shareImg = (ImageView) findViewById(R.id.imgvProdInfoBigImg);

						LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
						new Common().gradientDrawableCorners(ShareActivity.this, llBigImageWithName, null, 0.0334, 0.0167);
						RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
						rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
		    			rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
		    			rlpForLlImg.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
		    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
		    			llBigImageWithName.setLayoutParams(rlpForLlImg);
		    			
						LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) shareImg.getLayoutParams();
						llParamsForShareImg.width = (int) (0.72 * Common.sessionDeviceWidth);
		    			llParamsForShareImg.height = (int) (0.46 * Common.sessionDeviceHeight);
		    			shareImg.setLayoutParams(llParamsForShareImg);
		    			shareImg.setScaleType(ScaleType.FIT_CENTER);

						//get the bitmap for a previously fetched thumbnail	    			
		    			Bitmap bitmap = aq.getCachedImage(productImagesUrl);
		    			if(bitmap==null){
		    				aq.cache(productImagesUrl, 14400000);
		    			}
		    			String symbol = new Common().getCurrencySymbol(pdXml.text("country_languages").toString(), pdXml.text("country_code_char2").toString());
						getProductName = pdXml.text("prodName").toString();
						if(pdXml.text("pdPrice").toString().equals("") || 
								pdXml.text("pdPrice").toString().equals("null") || 
								pdXml.text("pdPrice").toString().equals("0") || 
								pdXml.text("pdPrice").toString().equals("0.00") || 
								pdXml.text("pdPrice").toString()==null){
							getProductPrice = "";
						} else {
							getProductPrice = symbol+pdXml.text("pdPrice").toString();
						}
						getClientLogo = pdXml.text("clientLogo").toString();
						getClientId = pdXml.text("clientId").toString();
						getProductShortDesc = pdXml.text("pd_short_description").toString();
						getProductDesc = pdXml.text("pd_description").toString();
						getClientBackgroundImage = pdXml.text("background_image").toString();
						getClientBackgroundColor = pdXml.text("background_color").toString();
						getClientImageName = pdXml.text("clientName").toString();
						getClientUrl = pdXml.text("clientUrl").toString();
						getProductUrl = pdXml.text("productUrl").toString();
						getImageUrl = productImagesUrl;
						
						if(getProductUrl.equals("null") || getProductUrl.contains("tel")){
							getFinalWebSiteUrl = getClientUrl;
						} else {
							getFinalWebSiteUrl = getProductUrl;
						}
						txtProdId.setText(getProductId);
						txtProdName.setText(getProductName);
						if(!getProductPrice.equals("null") && !getProductPrice.equals("") )
							txtProdPrice.setText(getProductPrice);
						if(!getProductDesc.equals("null")){
							txtProductDesc.setText(getProductDesc);
						}
						String screenName = "/product/shareproduct/"+getClientId+"/"+getClientImageName+"/"+getProductId+"/"+txtProdName.getText();
						String productIds = getProductId;
			    		String offerIds = "";
						Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
						
						
						 Tracker easyTracker = EasyTracker.getInstance(this);

							// This screen name value will remain set on the tracker and sent with
							// hits until it is set to a new value or to null.
						// /product/shareproduct/<clientId>/<clientname>/<productId>/<productname>
							easyTracker.set(Fields.SCREEN_NAME, " /product/shareproduct/"+getClientId+"/"+getClientImageName+"/"+getProductId+"/"+getProductName);
							easyTracker.send(MapBuilder
							    .createAppView()
							    .build()
							);
					} catch (Exception e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} 
			    }
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | productInfoXmlResultFromServer    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
	  	}
	}
	
	
	public void getMyOffersRedeemOfferResultsFromServerWithXml(String xmlUrl){
		aq.ajax(xmlUrl, XmlDom.class, this, "myOffersRedeemOfferResultsFromServerWithXml");
	}
	public void myOffersRedeemOfferResultsFromServerWithXml(String url, XmlDom xml, AjaxStatus status){
	  	try {
		    final List<XmlDom> myOffers = xml.tags("myOffers");
		    //Log.i("myOffers", myOffers.size()+" "+myOffers);   
	    	if(myOffers.size()>0){	    		
			    for(final XmlDom myOfferXml : myOffers){
			    	try {
				    	if(myOfferXml.tag("offer_image")!=null){

							String offerImagesUrl = myOfferXml.text("offer_image").toString().replaceAll(" ", "%20");
							new Common().DownloadImageFromUrl(ShareActivity.this, offerImagesUrl, R.id.imgvProdInfoBigImg);

							ImageView shareImg = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
							
							LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
							new Common().gradientDrawableCorners(ShareActivity.this, llBigImageWithName, null, 0.0334, 0.0167);
							RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
							rlpForLlImg.width = (int) (0.797 * Common.sessionDeviceWidth);
			    			rlpForLlImg.height = (int) (0.5072 * Common.sessionDeviceHeight);
			    			rlpForLlImg.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			    			llBigImageWithName.setLayoutParams(rlpForLlImg);
			    			
							LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) shareImg.getLayoutParams();
							llParamsForShareImg.width = (int) (0.717 * Common.sessionDeviceWidth);
			    			llParamsForShareImg.height = (int) (0.4611 * Common.sessionDeviceHeight);
			    			shareImg.setLayoutParams(llParamsForShareImg);
			    			shareImg.setScaleType(ScaleType.FIT_CENTER);
			    					
			    			Bitmap bitmap = aq.getCachedImage(offerImagesUrl);
			    			//Log.i("bitmap", ""+bitmap);
			    			if(bitmap==null){
			    				aq.cache(offerImagesUrl, 14400000);
			    			}
			    			offerName =myOfferXml.text("offer_name").toString();
			    			offerId =myOfferXml.text("offer_id").toString();
			    			txtProdName.setText(offerName);
			    			txtProductDesc.setText("Exp. "+myOfferXml.text("offer_valid_to").toString());
			    			getClientId =myOfferXml.text("client_id").toString();
			    			getClientImageName =myOfferXml.text("name").toString();
			    			getImageUrl = offerImagesUrl;
			    			String getOfferPurchaseUrl = myOfferXml.text("offer_purchase_url").toString();
							if(getOfferPurchaseUrl.equals("null")){
								getFinalWebSiteUrl = myOfferXml.text("client_url").toString();
							} else {
								getFinalWebSiteUrl = getOfferPurchaseUrl;
							}
			    			//Log.e("getImageUrl 1", ""+getImageUrl);
			    			String screenName = "/myoffers/shareoffer/"+offerId+"/"+offerName;
			    			String productIds = "";
			        		String offerIds = offerId;
			    			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
							
				    	}
					} catch (Exception e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} 
			    }
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | myOffersRedeemOfferResultsFromServerWithXml    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
	  	}
	}
	private FacebookHandle handle;
	private static String PERMISSIONS = "read_stream,read_friendlists,manage_friendlists,manage_notifications,publish_stream,publish_checkins,offline_access,user_photos,user_likes,user_groups,friends_photos";
	private final int ACTIVITY_SSO = 1000;
	private static String APP_ID = Constants.FACEBOOK_APP_ID;
	Facebook fb = new Facebook(Constants.FACEBOOK_APP_ID);
	public void auth_facebook_sso(final String imageUrlPath){	 
		
		
		 boolean installed  =   appInstalledOrNot("com.facebook.android");  
         if(installed)
         {
        	 handle = new FacebookHandle(this, APP_ID, PERMISSIONS);
             handle.sso(ACTIVITY_SSO);
             
             String url = "https://graph.facebook.com/me/photos";
             Map<String, Object> params = new HashMap<String, Object>();             
     	     params.put("name", txtProdName.getText());             
             params.put("description", txtProductDesc.getText());
             
             Bitmap bitmap = BitmapFactory.decodeFile(imageUrlPath);
             byte[] dataByte = null;
             ByteArrayOutputStream baos = new ByteArrayOutputStream();
             bitmap.compress(Bitmap.CompressFormat.PNG, 100, baos);
             dataByte = baos.toByteArray();
     	     params.put("image", dataByte);
     	     
             aq.auth(handle).progress(R.id.progress).ajax(url, params, JSONObject.class, this, "facebookCb");
         }
         else
         {
        	 SharedPreferences mPrefs = getSharedPreferences("fb_prefs", Context.MODE_PRIVATE);
        	 final String access_token = mPrefs.getString("access_token", null);
    		 long expires = mPrefs.getLong("access_expires", 0);    		
    		 if (access_token != null && !access_token.equals("")) {
    				fb.setAccessToken(access_token);	
    				try {
    					Bundle params = new Bundle();
    		     	    params.putString("name", txtProdName.getText().toString());    		          
    		            params.putString("description", txtProductDesc.getText().toString());
    		             
    		             Bitmap bitmap = BitmapFactory.decodeFile(imageUrlPath);
    		             byte[] dataByte = null;
    		             ByteArrayOutputStream baos = new ByteArrayOutputStream();
    		             bitmap.compress(Bitmap.CompressFormat.PNG, 100, baos);
    		             dataByte = baos.toByteArray();
    		     	     params.putByteArray("image", dataByte);							                
    		             String response = fb.request("me/photos?access_token="+access_token,
    	                            params, "POST");
    		               
    		              params.clear();
    		                if(response!=null && response.length()>0){
    		        			Toast.makeText(getApplicationContext(), "Successfully posted on facebook. ", Toast.LENGTH_LONG).show();
    		        		} else {
    		        			Toast.makeText(getApplicationContext(), "Failed to post on facebook. ", Toast.LENGTH_LONG).show();			
    		        		}
    		        } catch (Exception e) {
    		            e.printStackTrace();
    		            String errorMsg = className+" | access_token   |   " +e.getMessage();
    		       	 	Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
    		        }									
    			}
    		 
    		 if (!fb.isSessionValid() && access_token.equals("")) {
				   Log.i("access_token", ""+access_token);
		            Log.i("fb.isSessionValid()", ""+fb.isSessionValid());
		            Log.i("expires", ""+expires);
				fb.authorize(this, new String[] {  
						 "publish_stream",
							"publish_actions",
						},
						new DialogListener() {
							@Override
							public void onComplete(Bundle values) {								
								try {
			    					Bundle params = new Bundle();
			    		     	    params.putString("name", txtProdName.getText().toString());
			    		            params.putString("description", txtProductDesc.getText().toString());
			    		             
			    		             Bitmap bitmap = BitmapFactory.decodeFile(imageUrlPath);
			    		             byte[] dataByte = null;
			    		             ByteArrayOutputStream baos = new ByteArrayOutputStream();
			    		             bitmap.compress(Bitmap.CompressFormat.PNG, 100, baos);
			    		             dataByte = baos.toByteArray();
			    		     	      params.putByteArray("image", dataByte);							                
			    		              String response = fb.request("me/photos?access_token="+access_token,
			    	                            params, "POST");
			    		               
			    		                params.clear();
			    		                if(response!=null && response.length()>0){
			    		        			Toast.makeText(getApplicationContext(), "Successfully posted on facebook. ", Toast.LENGTH_LONG).show();
			    		        		} else {
			    		        			Toast.makeText(getApplicationContext(), "Failed to post on facebook. ", Toast.LENGTH_LONG).show();			
			    		        		}
			    		        } catch (Exception e) {
			    		            e.printStackTrace();
			    		            String errorMsg = className+" | access_token   |   " +e.getMessage();
			    		       	 	Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
			    		        }		
							        
							   
							}
							@Override
							public void onError(DialogError e) {
								e.printStackTrace();								
							}


							@Override
							public void onFacebookError(FacebookError e) {
								}
							@Override
							public void onCancel() {
							}
				});
			}	       
         }
     
        
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {	  
		boolean installed  =   appInstalledOrNot("com.facebook.android");  
        if(installed)
        {
	        switch(requestCode) {                
		        case ACTIVITY_SSO: {
	                if(handle != null){
	                    handle.onActivityResult(requestCode, resultCode, data);   
	                }
	                break;
		        }        
	        }
        }else{
        	fb.authorizeCallback(requestCode, resultCode, data);
        }
	}
	public void facebookCb(String url, JSONObject jo, AjaxStatus status){		
		if(jo!=null && jo.length()>0){
			Toast.makeText(getApplicationContext(), "Successfully posted on facebook. ", Toast.LENGTH_LONG).show();
		} else {
			Toast.makeText(getApplicationContext(), "Failed to post on facebook. ", Toast.LENGTH_LONG).show();			
		}
	}

	private static String CONSUMER_KEY = "QEtZ8wBGuw752ffqWoRWA";
	private static String CONSUMER_SECRET = "IMdpooAynW5nsbQQ7wqGKGFEJscSng2BpggPIFeCw";
	
	
	public void auth_twitter_update(){
		
		TwitterHandle handle = new TwitterHandle(this, CONSUMER_KEY, CONSUMER_SECRET);
		
		//1/statuses/update.format
		//https://upload.twitter.com/1/statuses/update_with_media.format

		String url = "http://twitter.com/statuses/update.json";
		
		Map<String, String> params = new HashMap<String, String>();
		params.put("status", "Testing 123");
		
		aq.auth(handle).progress(R.id.progress).ajax(url, params, JSONObject.class, this, "twitterCb");
		
	}
	public void twitterCb(String url, JSONObject jo, AjaxStatus status){
		if(jo!=null && jo.length()>0){
			Toast.makeText(getApplicationContext(), "Successfully posted on twitter. ", Toast.LENGTH_LONG).show();
		} else {
			Toast.makeText(getApplicationContext(), "Failed to post on twitter. ", Toast.LENGTH_LONG).show();			
		}
	}
	/**
	* To share photo with text on facebook , twitter and email etc.@param nameApp
	 * @param imageName 
	* @param nameApp
	* @param imagePath
	* */

	void share(String nameApp, String imagePath) {
	  try
	  {
	      List<Intent> targetedShareIntents = new ArrayList<Intent>();
	      Intent share = new Intent(android.content.Intent.ACTION_SEND);
	      share.setType("image/*");
	      List<ResolveInfo> resInfo = getPackageManager().queryIntentActivities(share, 0);
	      if (!resInfo.isEmpty()){
	          for (ResolveInfo info : resInfo) {
	              Intent targetedShare = new Intent(android.content.Intent.ACTION_SEND);
	              targetedShare.setType("image/*"); // put here your mime type
	              //Log.i("info ", ""+info.activityInfo.packageName.toLowerCase()+" == "+nameApp);
	              //Log.i("info1 ", ""+info.activityInfo.name.toLowerCase()+" == "+nameApp);
	              if (info.activityInfo.packageName.toLowerCase().contains(nameApp) || info.activityInfo.name.toLowerCase().contains(nameApp)) {
	                  targetedShare.putExtra(Intent.EXTRA_SUBJECT, "Sample Photo");
	                  targetedShare.putExtra(Intent.EXTRA_TEXT,"This photo is created by App Name");
	                  targetedShare.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(new File(imagePath)) );
	                  targetedShare.setPackage(info.activityInfo.packageName);
	                  targetedShareIntents.add(targetedShare);
	              }
	          }
	          Intent chooserIntent = Intent.createChooser(targetedShareIntents.remove(0), "Select app to share");
	          chooserIntent.putExtra(Intent.EXTRA_INITIAL_INTENTS, targetedShareIntents.toArray(new Parcelable[]{}));
	          startActivity(chooserIntent);
	      }
	  }
	  catch(Exception e){
	      Log.v("VM","Exception while sending image on" + nameApp + " "+  e.getMessage());
	      e.printStackTrace();
	      String errorMsg = className+" | share    |   " +e.getMessage();
		  Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
	  }
	}
	
	
	private File createTempFile() {
			try {
				
				//File file = createTempFile(bitmap);
					ImageView bigIm = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
					LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
					new Common().gradientDrawableCorners(ShareActivity.this, llBigImageWithName, null, 0.0334, 0.0167);
					RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
					rlpForLlImg.width = (int) (0.797 * Common.sessionDeviceWidth);
	    			rlpForLlImg.height = (int) (0.5072 * Common.sessionDeviceHeight);
	    			rlpForLlImg.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
	    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
	    			llBigImageWithName.setLayoutParams(rlpForLlImg);
	    			
					LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) bigIm.getLayoutParams();
					llParamsForShareImg.width = (int) (0.717 * Common.sessionDeviceWidth);
	    			llParamsForShareImg.height = (int) (0.4611 * Common.sessionDeviceHeight);
	    			bigIm.setLayoutParams(llParamsForShareImg);
	    			bigIm.setScaleType(ScaleType.FIT_CENTER);
					BitmapDrawable bitmapDrawable = (BitmapDrawable)bigIm.getDrawable();
			        Bitmap bitmap = bitmapDrawable.getBitmap();
	 
			        // Save this bitmap to a file.
			        File cache = getApplicationContext().getExternalCacheDir();
			        File sharefile = new File(cache, "product.png");
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
			Toast.makeText(getApplicationContext(), "Error: ShareActivity createTempFile.", Toast.LENGTH_LONG).show();
		      e.printStackTrace();
		      String errorMsg = className+" | createTempFile    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
		}
		return null;
	}

	 
	/*@Override
	protected void onResume() {
		// animateIn this activity
		ActivitySwitcher.animationIn(findViewById(R.id.scrollView1), getWindowManager());
		super.onResume();
	}*/

	 @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
		 try{
	        if (keyCode == KeyEvent.KEYCODE_BACK) {
	            //Log.i("Press Back", "BACK PRESSED EVENT");
	            isBackPressed = true;
	        }
	        // Call super code so we dont limit default interaction
	        return super.onKeyDown(keyCode, event);
		 } catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: Share  onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
			return false;
		}
    }
	 
	 @Override
	public void onBackPressed(){
		 try{
        	finish();
        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		 } catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: ProductInfo onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
		}
	 }

	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	     EasyTracker.getInstance(this).activityStart(this);  // Add this method.
	     String[] segments = new String[1];
		 segments[0] = "Share Product Page"; 
		 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
			 super.onStop();
			 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
			 QuantcastClient.activityStop();
		 }catch(Exception e){
			 String errorMsg = className+" | onStop    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
		 }
	}
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ShareActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(ShareActivity.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(ShareActivity.this,errorMsg);
			}
		}
		
		
     private boolean appInstalledOrNot(String uri)
     {
    	 
         PackageManager pm = getPackageManager();
         boolean app_installed = false;
        /* try
         {
                pm.getPackageInfo(uri, PackageManager.GET_ACTIVITIES);
                app_installed = true;
         }
         catch (PackageManager.NameNotFoundException e)
         {
                app_installed = false;
         }*/
        
        final String[] FacebookApps = {"com.facebook.android", "com.facebook.katana"};
 	    Intent facebookIntent = new Intent();
 	    facebookIntent.setType("text/plain");
 	    final PackageManager packageManager = getPackageManager();
 	    List<ResolveInfo> list = packageManager.queryIntentActivities(facebookIntent, PackageManager.MATCH_DEFAULT_ONLY);

 	    for (int i = 0; i < FacebookApps.length; i++)
 	    {
 	        for (ResolveInfo resolveInfo : list)
 	        {
 	            String p = resolveInfo.activityInfo.packageName;
 	            
 	            if (p != null && p.startsWith(FacebookApps[i]))
 	            {
 	            	//Log.i("resolveinfo",p);
 	                //facebookIntent.setPackage(p);
 	                //return facebookIntent;
 	            	app_installed = true;
 	           
 	            }
 	        }
 	    }
         return app_installed ;
     }
	 
}
