package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.net.URL;
import java.util.List;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
public class ProductInfo extends Activity {

	final Context context = this;
	String className =this.getClass().getSimpleName();
	public boolean isBackPressed = false;
	
	ImageView image;
	
	public byte[] rb;
	public Boolean alertErrorType = true;
	TextView txtClientName, txtProdPrice, txtProdId, txtProdName, txtProductDesc;
	public String getProductId = "0", getProductName = "null", getProductPrice = "null", getClientLogo = "null", getClientId = "0", 
			getClientBackgroundImage = "null", getFinalWebSiteUrl = "null", getClientBackgroundColor = "null", 
			getClientImageName = "null", getClientUrl = "null", getProductUrl = "null", getProductShortDesc = "null", getClientBackgroundLightColor="", 
			getClientBackgroundDarkColor="", getProductDesc = "null",getProductImage = "null",pageRedirectFlag="";

	SessionManager session;
	int prodIsTryOn = 0;

	AQuery aq;
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_product_info);
		try{	
			session = new SessionManager(context);
			//new Common().clientBgImgWithColor(ProductInfo.this);
			//new Common().pageHeaderTitle(ProductInfo.this, "Product Info");
			TextView txtvProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
			txtvProdNotAvail.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);	
			new Common().showDrawableImageFromAquery(this, R.drawable.btn_trash, R.id.imgvBtnCloset);
			
			 ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);			
			
			//imgvBtnCloset.setImageAlpha(0);
			ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			imgvBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					// TODO Auto-generated method stub
					try{
					ImageView bigImage = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
					BitmapDrawable test = (BitmapDrawable) bigImage.getDrawable();
					Bitmap bitmap = test.getBitmap();
					ByteArrayOutputStream baos = new ByteArrayOutputStream();
					bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
					byte[] b = baos.toByteArray();
					Intent intent = new Intent(ProductInfo.this, ShareActivity.class);
					intent.putExtra("tapOnImage", false);
					//Log.i("image", ""+b);
					//intent.putExtra("image", b);
					intent.putExtra("image", getProductImage);
					intent.putExtra("productId",  txtProdId.getText());
					intent.putExtra("clientId", Common.sessionClientId);						
					startActivityForResult(intent, 1);
					overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);					
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnShare  click  |   " +e.getMessage();
						 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
					}
				}
			});
			//imgvBtnShare.setImageAlpha(0);	
			
			//ArrayList<String> stringArrayList = new ArrayList<String>();	
			image = (ImageView) findViewById(R.id.imgvProdInfoBigImg);
			bigImageLinearLayoutWidth = image.getLayoutParams().width;
			bigImageLinearLayoutHeight = image.getLayoutParams().height;
			txtProdId = new TextView(this);
			txtClientName = (TextView)findViewById(R.id.txtvClientName);
			txtProdName = (TextView)findViewById(R.id.txtvProductName);
			txtProdPrice = (TextView)findViewById(R.id.txtvProductPrice);
			txtProductDesc = (TextView)findViewById(R.id.txtProductDesc);
			
			if(!Common.sessionClientName.equals("null")){
				txtClientName.setText(Common.sessionClientName);
			}
			if(!Common.sessionProductId.equals("null")){
				txtProdId.setText(Common.sessionProductId);
			}
			if(!Common.sessionProductName.equals("null")){
				txtProdName.setText(Common.sessionProductName);
			}
			if(!Common.sessionProductPrice.equals("null") || !Common.sessionProductPrice.equals("")){
				txtProdPrice.setText(Common.sessionProductPrice);
			}
			if(!getProductDesc.equals("null")){
				txtProductDesc.setText(getProductDesc);	
			}
			txtClientName.setTextSize((float) (0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtProdPrice.setTextSize((float) (0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtProductDesc.setTextSize((float) (0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			
            RelativeLayout.LayoutParams rlpForProdName = (RelativeLayout.LayoutParams) txtProdName.getLayoutParams();
            rlpForProdName.topMargin = (int) (0.025 * Common.sessionDeviceHeight);
            txtProdName.setLayoutParams(rlpForProdName);
            txtProdName.setTextSize((float) (0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
            
            /*RelativeLayout.LayoutParams rlpForProdPrice = (RelativeLayout.LayoutParams) txtProdPrice.getLayoutParams();
            rlpForProdPrice.topMargin = (int) (0.025 * Common.sessionDeviceHeight);
            txtProdPrice.setLayoutParams(rlpForProdPrice);*/
            txtProdPrice.setTextSize((float) (0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
            
            RelativeLayout.LayoutParams rlpForProdDesc = (RelativeLayout.LayoutParams) txtProductDesc.getLayoutParams();
            rlpForProdDesc.width = (int) (0.802 * Common.sessionDeviceWidth);
            rlpForProdDesc.height = (int) (0.08334 * Common.sessionDeviceHeight);
            txtProductDesc.setLayoutParams(rlpForProdDesc);
            
			
			Intent getIntVals = getIntent();
			
			if(getIntVals.getExtras()!=null){
				Bundle bundle = getIntVals.getExtras();
				prodIsTryOn = bundle.getInt("prodIsTryOn");	
				pageRedirectFlag = getIntVals.getStringExtra("pageRedirectFlag");	
				getProductId = getIntVals.getStringExtra("productId");
				getClientId = getIntVals.getStringExtra("clientId");
			
			if(Common.isNetworkAvailable(this))
				{
					if(session.isLoggedIn()){
						
						aq = new AQuery(ProductInfo.this);
						getProductInfoResultsFromServerWithXml(Constants.ClientProdDetails_Url+getClientId+"/"+getProductId+"/");
						
						
						// get user data from session
						String screenName = "/product/details/"+getClientId+"/"+Common.sessionClientName+"/"+getProductId+"/"+txtProdName.getText();
						String productIds = "";
			    		String offerIds = "";
						Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);					
						
						
					}
				}else{
					getProductInfoResultsFromFile();
					}
				//SessionManager session = new SessionManager(context);
				/*// Load the icon as drawable object
				Drawable d = getResources().getDrawable(R.drawable.see_it_live);				 
				// Get the color of the icon depending on system state
				int iconColor = Color.parseColor("#"+Common.sessionClientBgColor);				
				// Set the correct new color
				d.setColorFilter( iconColor, Mode.MULTIPLY );				 
				// Load the updated drawable to the image viewer
				btnSeeItLive.setBackground(d);
				RelativeLayout.LayoutParams rlpImgSeeItLive = (RelativeLayout.LayoutParams) btnSeeItLive.getLayoutParams();
				rlpImgSeeItLive.width = (int) (0.25 * Common.sessionDeviceWidth);
				rlpImgSeeItLive.height = (int) (0.072 * Common.sessionDeviceHeight);
				rlpImgSeeItLive.rightMargin = (int) (0.1 * Common.sessionDeviceWidth);
				rlpImgSeeItLive.topMargin = (int) (0.082 * Common.sessionDeviceHeight);
				btnSeeItLive.setLayoutParams(rlpImgSeeItLive);
				//btnSeeItLive.setTextSize((float) ((0.03*Common.sessionDeviceHeight)/Common.sessionDeviceDensity));
				if(prodIsTryOn==1){
					btnSeeItLive.setVisibility(View.VISIBLE);
					btnSeeItLive.setOnClickListener(new OnClickListener() {						
						@Override
						public void onClick(View v) {
							// TODO Auto-generated method stub
							Intent intent = new Intent(getApplicationContext(), TryOn.class);
							intent.putExtra("clientId", Common.sessionClientId);
							intent.putExtra("productId", txtProdId.getText());
							startActivity(intent);
							finish();
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}
					});
				} else {
					btnSeeItLive.setVisibility(View.INVISIBLE);
				}*/
				
				
				
				
			 imgvBtnCloset.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View v) {
						try{
						deleteProduct();
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | imgvBtnCloset  click  |   " +e.getMessage();
							 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
						}
					}
				});
				ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
				imgBtnCart.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View view) {
						try{
							if(Common.isNetworkAvailable(ProductInfo.this))
							{
								if(getFinalWebSiteUrl.equals("null")){
									Toast.makeText(getApplicationContext(), "Product is not available.", Toast.LENGTH_LONG).show();
					            	//pDialog.dismiss();
								} else {
									String[] separated = getFinalWebSiteUrl.split(":");
									if(separated[0]!=null && separated[0].equals("tel")){
									Intent callIntent = new Intent(Intent.ACTION_CALL);
									callIntent.setData(Uri.parse("tel://"+separated[1]));
				                   	startActivity(callIntent);
									} else if(separated[0]!=null && separated[0].equals("telprompt")){
										Intent callIntent = new Intent(Intent.ACTION_CALL);
					                    callIntent.setData(Uri.parse("tel://"+separated[1]));
				                    	startActivity(callIntent);
									} else {
										Intent intent = new Intent(ProductInfo.this, PurchaseProductWithClientUrl.class);
										intent.putExtra("productName", txtProdName.getText().toString());
										intent.putExtra("finalWebSiteUrl", getFinalWebSiteUrl);
										startActivity(intent);
										overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);		
									}
								}
							}else{
								 new Common().instructionBox(ProductInfo.this,R.string.title_case7,R.string.instruction_case7);
							}
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | imgBtnCart  click  |   " +e.getMessage();
							 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
						}
					}
				});
			} else {
				image.setImageBitmap(null);
				txtvProdNotAvail.setVisibility(View.VISIBLE);
			}
			LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
			new Common().gradientDrawableCorners(ProductInfo.this, llBigImageWithName, null, 0.0334, 0.0167);
			RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
			rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
			rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
			rlpForLlImg.topMargin = (int) (0.005 * Common.sessionDeviceHeight);
			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
			llBigImageWithName.setLayoutParams(rlpForLlImg);
			
			LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) image.getLayoutParams();
			llParamsForShareImg.width = (int) (0.72 * Common.sessionDeviceWidth);
			llParamsForShareImg.height = (int) (0.46 * Common.sessionDeviceHeight);
			image.setLayoutParams(llParamsForShareImg);
			image.setScaleType(ScaleType.FIT_CENTER);


			new Common().clickingOnBackButtonWithAnimation(ProductInfo.this, ProductList.class,"6");
	        
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
							 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
						}
		            }
		    });
		    
		    new Common().instructionBox(ProductInfo.this,R.string.title_case8,R.string.instruction_case8);
		
		} catch (Exception e) {
			Log.i("ProdInfo OnCreate: ", "Error: "+e.getMessage());
			e.printStackTrace();
			String errorMsg = className+" | onCreate |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
		}
	}
	 
	private void getProductInfoResultsFromFile() {
		try{
		FileTransaction file = new FileTransaction();
		ProductModel productmodel = file.getProduct();
		Log.e("getProductId",""+getProductId);
		
		UserProduct productDetails = productmodel.getUserProductById(Integer.parseInt(getProductId));
		Log.e("productDetails",""+productDetails);
		if(productDetails != null){
		aq= new AQuery(this);
		String curveImagesUrl = productDetails.getImageFile().replaceAll(" ", "%20");	
		Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
		if(bitmap==null) {
			aq.cache(curveImagesUrl, 144000);
		}
		
		LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
		new Common().gradientDrawableCorners(ProductInfo.this, llBigImageWithName, null, 0.0334, 0.0167);
		RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
		rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
		rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
		rlpForLlImg.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
		rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
		llBigImageWithName.setLayoutParams(rlpForLlImg);
		
		LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) image.getLayoutParams();
		llParamsForShareImg.width = (int) (0.72 * Common.sessionDeviceWidth);
		llParamsForShareImg.height = (int) (0.46 * Common.sessionDeviceHeight);
		image.setLayoutParams(llParamsForShareImg);
		image.setScaleType(ScaleType.FIT_CENTER);
		image.setImageBitmap(bitmap);
		getProductName = productDetails.getProductName();
		getProductPrice = productDetails.getProductPrice();
		getClientLogo = productDetails.getClientLogo();
		getClientId = ""+productDetails.getClientId();
		getProductShortDesc =productDetails.getProductShortDesc();
		getProductDesc =productDetails.getProductDesc();
		getClientBackgroundImage = productDetails.getClientBackgroundImage();
		getClientBackgroundColor = productDetails.getClientBackgroundColor();
		getClientBackgroundLightColor = productDetails.getClientLightColor();
		getClientBackgroundDarkColor =productDetails.getClientDarkColor();
		getClientImageName =productDetails.getClientName();
		getClientUrl = productDetails.getClientUrl();
		getProductUrl =productDetails.getProductUrl();
		prodIsTryOn =productDetails.getProdIsTryOn();
		getProductImage =curveImagesUrl;
		Common.sessionClientBgColor = getClientBackgroundColor;
		Common.sessionClientBackgroundLightColor = getClientBackgroundLightColor;
		Common.sessionClientBackgroundDarkColor = getClientBackgroundDarkColor;
		Common.sessionClientLogo = getClientLogo;
		Common.sessionClientName = getClientImageName;
		Common.sessionClientId = getClientId;
		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, Common.sessionClientBgColor,
				Common.sessionClientBackgroundLightColor,
				Common.sessionClientBackgroundDarkColor,
				Common.sessionClientLogo, "", "");	
    	Button btnSeeItLive = (Button)findViewById(R.id.btnSeeItLive);
		
		new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLive, "relative", "width", "product_info", getProductId, 
				getClientId, prodIsTryOn, getClientBackgroundColor, getClientBackgroundLightColor, getClientBackgroundDarkColor);
		if(getProductUrl.equals("null")){
			getFinalWebSiteUrl = getClientUrl;
		} else {
			getFinalWebSiteUrl = getProductUrl;
		}
		txtClientName.setText(getClientImageName);
		txtProdId.setText(getProductId);
		txtProdName.setText(getProductName);
		if(getProductPrice.equals("0.00") || getProductPrice.equals("0"))
			txtProdPrice.setText("Free");
		else if(!getProductPrice.equals("null") || !getProductPrice.equals("") )
			txtProdPrice.setText(getProductPrice);
		if(getProductDesc != null){
				if( !getProductDesc.equals("null") || !getProductDesc.equals("")){
					txtProductDesc.setText(getProductDesc);
				}
			}
		}
		
		
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getProductInfoResultsFromFile |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
		}
	}

	String bgColorCode = "";
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
						new Common().DownloadImageFromUrl(ProductInfo.this, productImagesUrl, R.id.imgvProdInfoBigImg);

						//get the bitmap for a previously fetched thumbnail	    			
		    			/*Bitmap bitmap = aq.getCachedImage(productImagesUrl);
		    			if(bitmap!=null){
			    			Log.i("aquery bitmap", bitmap.getWidth()+"<="+bitmap.getHeight());
			    		
		    			} else {
		    				URL url1 = new URL(productImagesUrl);
		    				aq.cache(productImagesUrl, 14400000);
			    			Bitmap bitmap1 = BitmapFactory.decodeStream(url1.openStream());			    		
			    			bitmap = bitmap1;
		    			}*/
		    			LinearLayout llBigImageWithName = (LinearLayout) findViewById(R.id.bigImageWithName);
		    			new Common().gradientDrawableCorners(ProductInfo.this, llBigImageWithName, null, 0.0334, 0.0167);
		    			RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) llBigImageWithName.getLayoutParams();
		    			rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
		    			rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
		    			rlpForLlImg.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
		    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
		    			llBigImageWithName.setLayoutParams(rlpForLlImg);
		    			
		    			LinearLayout.LayoutParams llParamsForShareImg = (LinearLayout.LayoutParams) image.getLayoutParams();
		    			llParamsForShareImg.width = (int) (0.72 * Common.sessionDeviceWidth);
		    			llParamsForShareImg.height = (int) (0.46 * Common.sessionDeviceHeight);
		    			image.setLayoutParams(llParamsForShareImg);
		    			image.setScaleType(ScaleType.FIT_CENTER);
		    			
		    			
		    			
		    			
						Button imgvBtnFinancial = (Button) findViewById(R.id.imgvBtnFinancial);
						RelativeLayout.LayoutParams rlForBtnFinancial = (RelativeLayout.LayoutParams) imgvBtnFinancial.getLayoutParams();
						rlForBtnFinancial.width = (int) (0.312 * Common.sessionDeviceWidth);
						rlForBtnFinancial.height = (int) (0.0707 * Common.sessionDeviceHeight);
						rlForBtnFinancial.bottomMargin = (int) (0.091 * Common.sessionDeviceHeight);
						imgvBtnFinancial.setLayoutParams(rlForBtnFinancial);
						imgvBtnFinancial.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
						imgvBtnFinancial.setText(pdXml.text("pd_button_name"));
						
						
						if(pdXml.text("client_vertical_id").toString().equals("1")){
							
							imgvBtnFinancial.setVisibility(View.VISIBLE);		
							if(pdXml.text("pd_button_name").toString().equals("null")){
								imgvBtnFinancial.setVisibility(View.INVISIBLE);
							}
							imgvBtnFinancial.setOnClickListener(new OnClickListener() {					
								@Override
								public void onClick(View v) {
									// TODO Auto-generated method stub
									try{
										String[] separated = pdXml.text("productUrl").toString().split(":");
										Log.e("separated", ""+separated[0]+" "+separated[1]);
										if(separated[0]!=null && separated[0].equals("tel")){
											Intent callIntent = new Intent(Intent.ACTION_CALL);
											callIntent.setData(Uri.parse("tel://"+separated[1]));
						                   	startActivity(callIntent);
										} else if(separated[0]!=null && separated[0].equals("telprompt")){
											Intent callIntent = new Intent(Intent.ACTION_CALL);
						                    callIntent.setData(Uri.parse("tel://"+separated[1]));
					                    	startActivity(callIntent);
										} else {
											Intent intent = new Intent(ProductInfo.this, PurchaseProductWithClientUrl.class);
											intent.putExtra("productName", txtProdName.getText().toString());
											intent.putExtra("finalWebSiteUrl", getFinalWebSiteUrl);
											startActivity(intent);
											overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);		
										}
									}catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | imgvBtnFinancial click |   " +e.getMessage();
										Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
									}
								}
							});
						
						}
						
		    			
		    			
		    			String symbol = new Common().getCurrencySymbol(pdXml.text("country_languages").toString(), pdXml.text("country_code_char2").toString());
						getProductName = pdXml.text("prodName").toString();
						if (pdXml.text("pdPrice").toString().equals("null") || 
								pdXml.text("pdPrice").toString().equals("") || 
								pdXml.text("pdPrice").toString().equals("0") || 
								pdXml.text("pdPrice").toString().equals("0.00") || 
								pdXml.text("pdPrice").toString() == null) {
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
						getClientBackgroundLightColor = pdXml.text("light_color").toString();
						getClientBackgroundDarkColor = pdXml.text("dark_color").toString();
						getClientImageName = pdXml.text("clientName").toString();
						getClientUrl = pdXml.text("clientUrl").toString();
						getProductUrl = pdXml.text("productUrl").toString();
						prodIsTryOn = Integer.parseInt(pdXml.text("pd_istryon").toString());
						getProductImage = productImagesUrl;
						
						Common.sessionClientBgColor = getClientBackgroundColor;
						Common.sessionClientBackgroundLightColor = getClientBackgroundLightColor;
						Common.sessionClientBackgroundDarkColor = getClientBackgroundDarkColor;
						Common.sessionClientLogo = getClientLogo;
						Common.sessionClientName = getClientImageName;
						Common.sessionClientId = getClientId;
						new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
								this, Common.sessionClientBgColor,
								Common.sessionClientBackgroundLightColor,
								Common.sessionClientBackgroundDarkColor,
								Common.sessionClientLogo, "", "");	
				    	Button btnSeeItLive = (Button)findViewById(R.id.btnSeeItLive);
						
						new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLive, "relative", "width", "product_info", getProductId, 
								getClientId, prodIsTryOn, getClientBackgroundColor, getClientBackgroundLightColor, getClientBackgroundDarkColor);
						
						
					
						if(getProductUrl.equals("null")){
							getFinalWebSiteUrl = getClientUrl;
						} else {
							getFinalWebSiteUrl = getProductUrl;
						}
						txtClientName.setText(getClientImageName);
						txtProdId.setText(getProductId);
						txtProdName.setText(getProductName);
						if(getProductPrice.equals("0.00") || getProductPrice.equals("0"))
							txtProdPrice.setText("Free");
						
						else if(!getProductPrice.equals("null") || !getProductPrice.equals(""))
							txtProdPrice.setText(getProductPrice);
						if(!getProductDesc.equals("null") || !getProductDesc.equals("")){
							txtProductDesc.setText(getProductDesc);
						}
					
					} catch (Exception e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
						String errorMsg = className+" | productInfoXmlResultFromServer for loop |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
					} 
			    }
	    	}
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | productInfoXmlResultFromServer |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
	  	}
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			if(requestCode == 1){
				if(!Common.isNetworkAvailable(ProductInfo.this)){
					if(data != null){
						 String activity=data.getStringExtra("activity");						 
						 if(activity.equals("menu")){
							 new Common().instructionBox(ProductInfo.this, R.string.title_case7, R.string.instruction_case7);
						 }
					 }
				}
			}
			
		}catch (Exception e){	
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
			
		}
		
	}
	 @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
		 try{
	        if (keyCode == KeyEvent.KEYCODE_BACK) {
	            isBackPressed = true;
	        }
	        return super.onKeyDown(keyCode, event);
		 } catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: ProductInfo onKeyDown.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onKeyDown  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
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
			String errorMsg = className+" | onBackPressed  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
		}
	 }
	 @Override
	public void onStart() {
		 try{
	    super.onStart();	    
	    Tracker easyTracker = EasyTracker.getInstance(this);
		easyTracker.set(Fields.SCREEN_NAME, "/product/details"+Common.sessionClientId+"/"+Common.sessionClientName+"/"+Common.sessionProductId+"/"+Common.sessionProductName);
		easyTracker.send(MapBuilder
		    .createAppView()
		    .build()
		);
		 String[] segments = new String[1];
		 segments[0] = "Product details page"; 
		 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
			 
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
			 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
		 }
	}
	 public void hideInstruction(View v){
			RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
			rlInstruction.setVisibility(View.GONE);
		}
	 public void deleteProduct()
	 {
		 try{
		 if(Common.isNetworkAvailable(ProductInfo.this)){
 			
         	
			 AlertDialog.Builder alertDialog = new AlertDialog.Builder(ProductInfo.this);					 
		        // Setting Dialog Title
		        alertDialog.setTitle("Confirm Delete...");			 
		        // Setting Dialog Message
		        alertDialog.setMessage("Are you sure you want delete the product?");						 
		        // Setting Positive "Yes" Button
		        alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
		            @Override
					public void onClick(DialogInterface dialog,int which) {			           
						try{		
							if(pageRedirectFlag.equals("Closet")){
			            		new Closet().insertUpdateDeleteProductsToClosetDbUsingXml(Constants.Closet_Url+"delete/"+Common.sessionIdForUserLoggedIn+"/"+getProductId.toString()+"/2/", "delete");
			            		Intent intent = new Intent(ProductInfo.this,Closet.class);
			            		startActivity(intent);
			            		finish();
			                	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							}else{
								
								String WishListName = getIntent().getStringExtra("wishListName");
								AQuery aq= new AQuery(ProductInfo.this);
								Log.i("url", ""+Constants.DeleteWishListPd_Url+Common.sessionIdForUserLoggedIn+"/"+WishListName+"/"+getProductId.toString());
								aq.ajax(Constants.DeleteWishListPd_Url+Common.sessionIdForUserLoggedIn+"/"+WishListName+"/"+getProductId.toString(), XmlDom.class, new AjaxCallback<XmlDom>(){			
				        			@Override
				        			public void callback(String url, XmlDom xml, AjaxStatus status) {
				        				try{
				        					Log.i("XmlDom", ""+xml);
				            				if(xml!=null){
				            					  if(xml.text("msg").equals("success")){
				            						  Intent intent = new Intent(ProductInfo.this,WishListPage.class);
				      			            		  startActivity(intent);
				      			            		  finish();
				      			                	  overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				            					  } else {
				        	    					Toast.makeText(context, "Delete failed. Please try again!", Toast.LENGTH_LONG).show();
				        	    				}
				            				}
				        				} catch(Exception e){
				        					e.printStackTrace();
				        				}
				        			}			            			
				        		});	
				           
							}
						} catch (Exception e) {
							e.printStackTrace();	
							 String errorMsg = className+" | deleteProduct yes clck  |   " +e.getMessage();
							 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
						}
		            }
		        });			 
		        // Setting Negative "NO" Button
		        alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
		            @Override
					public void onClick(DialogInterface dialog, int which) {					         
		            dialog.cancel();
		            }
		        });	 
		        // Showing Alert Message
		        alertDialog.show();
		 }else{
		 new Common().instructionBox(ProductInfo.this,R.string.title_case7,R.string.instruction_case7);
		 }
	 }catch(Exception e){
		 e.printStackTrace();
		 String errorMsg = className+" | deleteProduct   |   " +e.getMessage();
		 Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
	 }
	 }
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ProductInfo.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(ProductInfo.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(ProductInfo.this,errorMsg);
			}
		}
}
