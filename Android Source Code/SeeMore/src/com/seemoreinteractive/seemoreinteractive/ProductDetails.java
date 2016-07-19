package com.seemoreinteractive.seemoreinteractive;

import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

import org.json.JSONArray;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Paint.Align;
import android.graphics.Typeface;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.LayerDrawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.Gravity;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;
import android.widget.ZoomButton;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.seemoreinteractive.seemoreinteractive.Model.OrderModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserOrder;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@SuppressLint("NewApi") @TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
public class ProductDetails extends Activity {
	public static Activity prdsDetail;
	String className = this.getClass().getSimpleName();
	AQuery aq;
	ImageView imgvProdDetailsBigImg;
	TextView txtProdImageName, txtProdImagePrice;
	Button btnBuyMore, btnBuyNow;
	//Button btnNumQuantities, btnValSizes, btnValColors;
	ZoomButton zoomImg;
	HorizontalScrollView horizScrViewQuantity, horizScrViewSize, horizScrViewColor;
	LinearLayout horizScrViewQuantityLinearLayout, horizScrViewSizeLinearLayout, horizScrViewColorLinearLayout;
	//boolean flagForBtnQuantity = false, flagForBtnSize = false, flagForBtnColor = false;
	public static String storedQuantityVal = "null", storedSizeVal = "null",
			storedColorVal = "null", storedClientId = "", storedProdId = "",
			storedProdName = "", storedProdPrice = "", storedProdImage = "", 
			storedShippingMethodName = "null", storedShippingMethodDetails = "null", 
			storedShippingMethodRateForTitle = "null", storedShippingMethodRate = "null",storedProdUrl="null",storedProdDesc="null",storedIsSeemoreCart="0";
	String[] prodStrArr = null, strArrFlags = null;
	String finalShippingRateVal = "",listQuantity="1";
	ViewFlipper viewFlipperAttributes; 
	int listPos,listCurrentPos;
	String editActionStr ,quantity; 
	public static Boolean editAction = false; 
	
	public static ArrayList<String> arrForClientIds = new ArrayList<String>();
	public  ArrayList<String> arrForClientColorSizeIds;
	public static ArrayList<HashMap<String, String>> arrPdInfoForCartList = new ArrayList<HashMap<String,String>>();
	int countFlag = 0, countFlagForClient = 0;
	public static ArrayList<HashMap<String, String>> arrListHashMapForClientInfo = new ArrayList<HashMap<String,String>>();
	public static JSONArray jsonShippingMethodAttriButes;
	 SessionManager session;
	 Button btnQuantity;
	 RelativeLayout btnSizeLayout,btnColorLayout;
	 TextView txtvColor, txtvSize,txtvColorCode,txtvSizeCode,txtvQuantity,txtvProdDesc;
	 EditText editvQuantity;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_product_details);
		try{					
			prdsDetail = this;
			session = new SessionManager(getApplicationContext());				
			aq = new AQuery(ProductDetails.this);
						
			ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);
			ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);	
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			
	        ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle); 
		    imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
	            @Override
	            public void onClick(View view) {
	            	try{
						Intent intent = new Intent(ProductDetails.this, MenuOptions.class);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
	            	} catch (Exception e) {
						//Toast.makeText(getApplicationContext(), "Error: ProductInfo imgFooterMiddle.", Toast.LENGTH_LONG).show();
	            		e.printStackTrace();
	            		String errorMsg = getClass().getSimpleName()+" | imgFooterMiddle  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
					}
	            }
		    });
			ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {		
				@Override
				public void onClick(View v) {	
					try{
				    	 Intent intent;
				    	 if(getClass().getSimpleName().equals("OrderConfirmation")){
				    		 intent = new Intent(ProductDetails.this, ProductDetails.class);
				    	 } else if(getClass().getSimpleName().equals("ShippingListInfo")){
				    		 intent = new Intent(ProductDetails.this, OrderConfirmation.class);
				    	 } else {
				    		 intent = new Intent(ProductDetails.this, Closet.class);
				    	 }
				    	 setResult(1, intent);
				    	 finish();
				    	 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception e){
						e.printStackTrace();
						String errorMsg = getClass().getSimpleName()+" | imgBackButton click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ProductDetails.this,errorMsg);
					}	
				}
			});			
			zoomImg = (ZoomButton) findViewById(R.id.zoomButton1);
			RelativeLayout.LayoutParams RlForZoomBtn = (RelativeLayout.LayoutParams) zoomImg.getLayoutParams();
			RlForZoomBtn.width  = (int) (0.0667 * Common.sessionDeviceWidth);
			RlForZoomBtn.height = (int) (0.031 * Common.sessionDeviceHeight);
			RlForZoomBtn.bottomMargin = (int) (0.0051 * Common.sessionDeviceHeight);
			zoomImg.setLayoutParams(RlForZoomBtn);
			
			txtProdImageName = (TextView) findViewById(R.id.txtProdImageName);
			RelativeLayout.LayoutParams RlForTxtProdName = (RelativeLayout.LayoutParams) txtProdImageName.getLayoutParams();
			RlForTxtProdName.width = (int) (0.5 * Common.sessionDeviceWidth);
			RlForTxtProdName.height = LayoutParams.WRAP_CONTENT;
			RlForTxtProdName.leftMargin = (int) (0.05 * Common.sessionDeviceWidth);
			RlForTxtProdName.topMargin = (int) (0.031 * Common.sessionDeviceHeight);
			txtProdImageName.setLayoutParams(RlForTxtProdName);
			txtProdImageName.setTextSize((float)(0.0417 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			txtProdImagePrice = (TextView) findViewById(R.id.txtProdImagePrice);
			RelativeLayout.LayoutParams RlForTxtProdPrice = (RelativeLayout.LayoutParams) txtProdImagePrice.getLayoutParams();
			RlForTxtProdPrice.width = LayoutParams.WRAP_CONTENT;
			RlForTxtProdPrice.height = LayoutParams.WRAP_CONTENT;
			RlForTxtProdName.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			txtProdImagePrice.setLayoutParams(RlForTxtProdPrice);
			txtProdImagePrice.setTextSize((float)(0.0367 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			txtvQuantity = (TextView) findViewById(R.id.txtvQuantity);
			RelativeLayout.LayoutParams RlForTxtQuantity = (RelativeLayout.LayoutParams) txtvQuantity.getLayoutParams();
			RlForTxtQuantity.topMargin = (int) (0.0225 * Common.sessionDeviceHeight);
			txtvQuantity.setLayoutParams(RlForTxtQuantity);
			txtvQuantity.setTextSize((float)(0.0367 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
		
			RelativeLayout bigImageWithName = (RelativeLayout) findViewById(R.id.bigImageWithName);
			RelativeLayout.LayoutParams RlForBigImageRl = (RelativeLayout.LayoutParams) bigImageWithName.getLayoutParams();
			RlForBigImageRl.width = (int) (0.334 * Common.sessionDeviceWidth);
			RlForBigImageRl.height = (int) (0.205 * Common.sessionDeviceHeight);
			RlForBigImageRl.leftMargin = (int) (0.05 * Common.sessionDeviceWidth);
			RlForBigImageRl.topMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			RlForBigImageRl.bottomMargin = (int) (0.03074 * Common.sessionDeviceHeight);
			bigImageWithName.setLayoutParams(RlForBigImageRl);
			
			imgvProdDetailsBigImg = (ImageView) findViewById(R.id.imgvProdDetailsBigImg);
			RelativeLayout.LayoutParams RlForBigProdImage = (RelativeLayout.LayoutParams) imgvProdDetailsBigImg.getLayoutParams();
			RlForBigProdImage.width = (int) (0.334 * Common.sessionDeviceWidth);
			RlForBigProdImage.height = (int) (0.205 * Common.sessionDeviceHeight);
			imgvProdDetailsBigImg.setLayoutParams(RlForBigProdImage);
			
			btnBuyMore = (Button) findViewById(R.id.btnBuyMore);
			RelativeLayout.LayoutParams RlForBtnBuyMore = (RelativeLayout.LayoutParams) btnBuyMore.getLayoutParams();
			RlForBtnBuyMore.width = (int) (0.417 * Common.sessionDeviceWidth);
			RlForBtnBuyMore.height = (int) (0.0615 * Common.sessionDeviceHeight);
			RlForBtnBuyMore.leftMargin = (int) (0.0583 * Common.sessionDeviceWidth);
			btnBuyMore.setLayoutParams(RlForBtnBuyMore);
			btnBuyMore.setTextSize((float)(0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));

			btnBuyNow = (Button) findViewById(R.id.btnBuyNow);
			RelativeLayout.LayoutParams RlForBtnBuyNow = (RelativeLayout.LayoutParams) btnBuyNow.getLayoutParams();
			RlForBtnBuyNow.width = (int) (0.417 * Common.sessionDeviceWidth);
			RlForBtnBuyNow.height = (int) (0.0615 * Common.sessionDeviceHeight);
			RlForBtnBuyNow.bottomMargin = (int) (0.059 * Common.sessionDeviceHeight);
			btnBuyNow.setLayoutParams(RlForBtnBuyNow);
			btnBuyNow.setTextSize((float)(0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			
			btnQuantity = (Button) findViewById(R.id.btnQuantity);
			LinearLayout.LayoutParams llForBtnQuantity = (LinearLayout.LayoutParams) btnQuantity.getLayoutParams();
			llForBtnQuantity.width = (int) (0.334 * Common.sessionDeviceWidth);
			llForBtnQuantity.height = (int) (0.0615 * Common.sessionDeviceHeight);
			btnQuantity.setLayoutParams(llForBtnQuantity);
			btnQuantity.setTextSize((float) (0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			editvQuantity = (EditText) findViewById(R.id.editvQuantity);
			RelativeLayout.LayoutParams rlForEditvQuantity = (RelativeLayout.LayoutParams) editvQuantity.getLayoutParams();
			rlForEditvQuantity.width = (int)(0.1667 * Common.sessionDeviceWidth);
			rlForEditvQuantity.height = (int) (0.0512* Common.sessionDeviceHeight);	
			rlForEditvQuantity.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			editvQuantity.setLayoutParams(rlForEditvQuantity);
			editvQuantity.setTextSize((float) (0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));		
			
			
			editvQuantity.addTextChangedListener(new TextWatcher() { 
				@Override public void onTextChanged(CharSequence s, int start, int before, int count) { 
					
					if (editvQuantity.getText().toString().startsWith("0")) 
						editvQuantity.setText(""); 
					}

				@Override
				public void beforeTextChanged(CharSequence s, int start,
						int count, int after) {
					
				}

				@Override
				public void afterTextChanged(Editable s) {
					if (editvQuantity.getText().toString().startsWith("0"))
						editvQuantity.setText(""); 
					
				} 
				}); 
			btnSizeLayout = (RelativeLayout) findViewById(R.id.btnSizeLayout);
			LinearLayout.LayoutParams llForBtnSize = (LinearLayout.LayoutParams) btnSizeLayout.getLayoutParams();
			llForBtnSize.width = (int) (0.334 * Common.sessionDeviceWidth);
			llForBtnSize.height = (int) (0.0615 * Common.sessionDeviceHeight);
			btnSizeLayout.setLayoutParams(llForBtnSize);
			
			
			txtvSize = (TextView)findViewById(R.id.txtvSize);	
			txtvSize.setTextSize((float) (0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			
			txtvSizeCode = (TextView)findViewById(R.id.txtvSizeCode);
			RelativeLayout.LayoutParams llForTxtvSizeCode = (RelativeLayout.LayoutParams) txtvSizeCode.getLayoutParams();
			llForTxtvSizeCode.topMargin = (int) (0.0051 * Common.sessionDeviceHeight);
			txtvSizeCode.setLayoutParams(llForTxtvSizeCode);
			
			
			
			
			
			btnColorLayout = (RelativeLayout) findViewById(R.id.btnColorLayout);
			LinearLayout.LayoutParams llForBtnColor = (LinearLayout.LayoutParams) btnColorLayout.getLayoutParams();
			llForBtnColor.width = (int) (0.334 * Common.sessionDeviceWidth);
			llForBtnColor.height = (int) (0.0615 * Common.sessionDeviceHeight);
			btnColorLayout.setLayoutParams(llForBtnColor);
			
			
			txtvColor = (TextView)findViewById(R.id.txtvColor);
			txtvColor.setTextSize((float) (0.034 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			
			txtvColorCode = (TextView)findViewById(R.id.txtvColorCode);
			RelativeLayout.LayoutParams llForTxtvColorCode = (RelativeLayout.LayoutParams) txtvColorCode.getLayoutParams();
			llForTxtvColorCode.width = (int) (0.0334 * Common.sessionDeviceWidth);
			llForTxtvColorCode.height =  (int) (0.02 * Common.sessionDeviceHeight);		
			llForTxtvColorCode.topMargin = (int) (0.0051 * Common.sessionDeviceHeight);
			llForTxtvColorCode.leftMargin = (int )(0.00834 * Common.sessionDeviceWidth);
			txtvColorCode.setLayoutParams(llForTxtvColorCode);
			
			viewFlipperAttributes = (ViewFlipper) findViewById(R.id.viewFlipperAttributeVals);
			RelativeLayout.LayoutParams rlForViewFlipper = (RelativeLayout.LayoutParams) viewFlipperAttributes.getLayoutParams();
			rlForViewFlipper.width = LayoutParams.MATCH_PARENT;
			rlForViewFlipper.height = (int) (0.0615 * Common.sessionDeviceHeight);
			viewFlipperAttributes.setLayoutParams(rlForViewFlipper);
			
			
			horizScrViewSize = (HorizontalScrollView) findViewById(R.id.horizScrViewSize);
			horizScrViewSizeLinearLayout = (LinearLayout) findViewById(R.id.horizScrViewSizeLinearLayout);			
			horizScrViewColor = (HorizontalScrollView) findViewById(R.id.horizScrViewColor);
			horizScrViewColorLinearLayout = (LinearLayout) findViewById(R.id.horizScrViewColorLinearLayout);
			horizScrViewColorLinearLayout.setGravity(Gravity.CENTER);
			horizScrViewColorLinearLayout.setOrientation(LinearLayout.HORIZONTAL);
			txtvProdDesc = (TextView) findViewById(R.id.txtvProdDesc);
			
			Intent getIntVals = getIntent();			
			if(getIntVals.getExtras()!=null){
				Bundle bundleVals = getIntVals.getExtras();
				 prodStrArr    = bundleVals.getStringArray("prodStrArr");
				 editActionStr = getIntVals.getStringExtra("editAction");
				 quantity = getIntVals.getStringExtra("quantity");
				 if(editActionStr != null){
					 if(editActionStr.equals("true")){
						 	listCurrentPos = Integer.parseInt(getIntVals.getStringExtra("pos"));
							arrPdInfoForCartList        = OrderConfirmation.arrPdInfoForCartEditList;
							arrListHashMapForClientInfo = OrderConfirmation.arrListHashMapForEditClientInfo;
							arrForClientIds             = OrderConfirmation.arrForClientIds;
					 }
				 } 
				
		    	 storedQuantityVal = "null";
		    	 storedSizeVal = "null";
		    	 storedColorVal = "null";
		    	 
		    	 
				Common.sessionClientBgColor = prodStrArr[6].trim();
				Common.sessionClientLogo = prodStrArr[7].trim();
				Common.sessionClientBackgroundLightColor = prodStrArr[8].trim();
				Common.sessionClientBackgroundDarkColor = prodStrArr[9].trim();
				//Common.sessionClientName = prodStrArr[1].trim();
				Common.sessionClientId = prodStrArr[1].trim();
				
				
				storedProdImage      = prodStrArr[0].trim();		
				storedClientId       = prodStrArr[1].trim();		
				storedProdId         = prodStrArr[2].trim();		
				storedProdName       = prodStrArr[3].trim();		
				storedProdPrice      = prodStrArr[11].trim();	
				storedProdUrl 		 = prodStrArr[14].trim();
				storedProdDesc       = prodStrArr[15].trim();
				storedIsSeemoreCart  = prodStrArr[16].trim();
				
				new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
						this, Common.sessionClientBgColor,
						Common.sessionClientBackgroundLightColor,
						Common.sessionClientBackgroundDarkColor,
						Common.sessionClientLogo, storedProdName, "");
				
				Bitmap bmpImage = null;				
				if(aq.getCachedImage(storedProdImage)==null){
					URL url = new URL(storedProdImage);
					aq.cache(storedProdImage, 14400000);
	    			bmpImage = BitmapFactory.decodeStream(url.openStream());
				} else {
					bmpImage = aq.getCachedImage(storedProdImage);
				}
				imgvProdDetailsBigImg.setImageBitmap(bmpImage);
				txtProdImageName.setText(storedProdName);
				txtProdImagePrice.setText("$"+storedProdPrice);
				if(!storedProdDesc.equals("null"))
					txtvProdDesc.setText(storedProdDesc);
				
				imgvProdDetailsBigImg.setOnClickListener(new View.OnClickListener() {				
					@Override
					public void onClick(View v) {	
						try{
							if(!storedProdName.equals("")){
								Intent i = new Intent(getApplicationContext(), FullImage.class);	
				                i.putExtra("storedProdName",storedProdName);
				                i.putExtra("storedProdImage",storedProdImage);
				                i.putExtra("storedClientId",storedClientId);
				                i.putExtra("storedProdUrl",storedProdUrl);
				                i.putExtra("storedProdId",storedProdId);
				                startActivity(i);
							}
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | imgvProddetailsBigImg onclick  |   " +e.getMessage();
					       	Common.sendCrashWithAQuery(ProductDetails.this,errorMsg);
						}
					}
				});       
				zoomImg.setOnClickListener(new View.OnClickListener() {				
					@Override
					public void onClick(View v) {		
						try{
							if(!storedProdName.equals("")){
								Intent i = new Intent(getApplicationContext(), FullImage.class);	
				                i.putExtra("storedProdName",storedProdName);
				                i.putExtra("storedProdImage",storedProdImage);
				                i.putExtra("storedClientId",storedClientId);
				                i.putExtra("storedProdUrl",storedProdUrl);
				                i.putExtra("storedProdId",storedProdId);
				                startActivity(i);
							}
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | zoomimage onclick  |   " +e.getMessage();
					       	Common.sendCrashWithAQuery(ProductDetails.this,errorMsg);
						}
					}
				});    

				
				imgvBtnShare.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View arg0) {
						try{					
							Intent intent = new Intent(ProductDetails.this, ShareActivity.class);
							intent.putExtra("tapOnImage", false);
							intent.putExtra("image", storedProdImage);						
							intent.putExtra("productId",  storedProdId);
							intent.putExtra("clientId", storedClientId);						
							startActivityForResult(intent, 1);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);					
						}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | imgvBtnShare  click  |   " +e.getMessage();
							    Common.sendCrashWithAQuery(ProductDetails.this,errorMsg);
							}
					  }
				});
			}
			
			imgBtnCart.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
						if(Common.isNetworkAvailable(ProductDetails.this))
						{
							if(storedIsSeemoreCart.equals("1")){
								if(!session.isLoggedIn()){
									new Common().getLoginDialog(ProductDetails.this, ProductDetails.class, "ProductDetails", new ArrayList<String>());
								} else {
									Intent intent = new Intent(getApplicationContext(), OrderConfirmation.class);
									startActivityForResult(intent, 1);
									overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);						
								}
							}else{
								if(storedProdUrl.equals("null") || storedProdUrl.equals("") || storedProdUrl == null){
									Toast.makeText(getApplicationContext(), "Product is not available.", Toast.LENGTH_LONG).show();					            	
								} else{
									String[] separated = storedProdUrl.split(":");
									if(separated[0]!=null && separated[0].equals("tel")){
									Intent callIntent = new Intent(Intent.ACTION_CALL);
									callIntent.setData(Uri.parse("tel://"+separated[1]));
				                   	startActivity(callIntent);
									} else if(separated[0]!=null && separated[0].equals("telprompt")){
										Intent callIntent = new Intent(Intent.ACTION_CALL);
					                    callIntent.setData(Uri.parse("tel://"+separated[1]));
				                    	startActivity(callIntent);
									} else {
										Intent intent = new Intent(ProductDetails.this, PurchaseProductWithClientUrl.class);
										intent.putExtra("productName", storedProdName);
										intent.putExtra("finalWebSiteUrl", storedProdUrl);
										startActivity(intent);
										overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);		
									}
								}
							}
						}else{
							 new Common().instructionBox(ProductDetails.this,R.string.title_case7,R.string.instruction_case7);
						  }
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCart  click  |   " +e.getMessage();
						 Common.sendCrashWithAQuery(ProductDetails.this,errorMsg);
					}
				}
			});
			
			//get Color size values for product
			getColorSizeValues();
			
			
			btnSizeLayout.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {				
					try{
						btnSizeLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
						txtvSize.setTextColor(Color.WHITE);
						
						btnColorLayout.setBackgroundResource(R.drawable.pd_client_horiz_bg);
						txtvColor.setTextColor(Color.parseColor("#747474"));
						
						//horizScrViewQuantity.setVisibility(View.INVISIBLE);
						horizScrViewSize.setVisibility(View.VISIBLE);
						horizScrViewColor.setVisibility(View.INVISIBLE);
		        	} catch(Exception e){
						e.printStackTrace();
			    		String errorMsg = className+" | btnSize  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
					}					
				}
			});
			
				btnColorLayout.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View v) {
						try{
							btnColorLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));							
							txtvColor.setTextColor(Color.WHITE);
							btnSizeLayout.setBackgroundResource(R.drawable.pd_client_horiz_bg);
							txtvSize.setTextColor(Color.parseColor("#747474"));
							
                    	
							horizScrViewSize.setVisibility(View.INVISIBLE);
							horizScrViewColor.setVisibility(View.VISIBLE);	
			        	} catch(Exception e){
			        		e.printStackTrace();
		            		String errorMsg = className+" | btnColor  click  |   " +e.getMessage();
							Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
						}				
					}
				});	
			
			btnBuyMore.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {					
					try{					
				    	 Intent Home = new Intent(getApplicationContext(), Closet.class);
				         setResult(1,Home);
					     finish();
					     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				    	 /*storedQuantityVal = "";
				    	 storedSizeVal = "";
				    	 storedColorVal = "";*/	
					} catch(Exception e){
						e.printStackTrace();
	            		String errorMsg = className+" | btnBuyMore  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
					}
				}
			});		
			btnBuyNow.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{	
						if(storedIsSeemoreCart.equals("1")){
						Thread.sleep(1000);
						HashMap<String, String> hMapForPdInfo = new HashMap<String, String>();												
						storedQuantityVal = editvQuantity.getText().toString();
						hMapForPdInfo.put("Quantity", (storedQuantityVal.isEmpty() ? "1" : storedQuantityVal));
						hMapForPdInfo.put("Size", (storedSizeVal.isEmpty() ? "null" : storedSizeVal));
						hMapForPdInfo.put("Color", (storedColorVal.isEmpty() ? "null" : storedColorVal));
						hMapForPdInfo.put("ClientId", storedClientId);
						hMapForPdInfo.put("ProdId", storedProdId);
						hMapForPdInfo.put("ProdName", storedProdName);
						hMapForPdInfo.put("ProdPrice", storedProdPrice);
						hMapForPdInfo.put("ProdImage", storedProdImage);
						hMapForPdInfo.put("ClosetSeleStatus", prodStrArr[4].trim());
						hMapForPdInfo.put("ProdIsTryOn", prodStrArr[5].trim());
						hMapForPdInfo.put("BgColorCode", prodStrArr[6].trim());
						hMapForPdInfo.put("LightBgColorCode", prodStrArr[8].trim());
						hMapForPdInfo.put("DarkBgColorCode", prodStrArr[9].trim());
						hMapForPdInfo.put("ClientLogo", (prodStrArr[7].trim().isEmpty() ? "null" : prodStrArr[7].trim()));
						hMapForPdInfo.put("ProdButtonName", prodStrArr[10].trim());
						hMapForPdInfo.put("ClientName", prodStrArr[12].trim());
						hMapForPdInfo.put("isSeemoreCart", prodStrArr[16].trim());
						hMapForPdInfo.put("ShippingMethodName", storedShippingMethodName);
						hMapForPdInfo.put("ShippingMethodDetails", storedShippingMethodDetails);
						hMapForPdInfo.put("ShippingMethodRateTitle", storedShippingMethodRateForTitle);
						hMapForPdInfo.put("ShippingMethodRate", storedShippingMethodRate);
						
						HashMap<String, String> hMapForClientInfo = new HashMap<String, String>();
						hMapForClientInfo.put("ClientId", storedClientId);
						hMapForClientInfo.put("ClientName", prodStrArr[12].trim());
						hMapForClientInfo.put("ClientLogo", prodStrArr[7].trim());
						hMapForClientInfo.put("ShippingMethodName", storedShippingMethodName);
						hMapForClientInfo.put("ShippingMethodDetails", storedShippingMethodDetails);
						hMapForClientInfo.put("ShippingMethodRateTitle", storedShippingMethodRateForTitle);
						hMapForClientInfo.put("ShippingMethodRate", storedShippingMethodRate);
						hMapForClientInfo.put("offer_id","null");
						hMapForClientInfo.put("offer_name","null");
						hMapForClientInfo.put("offer_discount","null");
						hMapForClientInfo.put("offer_discount_type","null");
						arrForClientColorSizeIds = new ArrayList<String>();
				        if(arrPdInfoForCartList.size()>0){
							for(int i = 0; i < arrPdInfoForCartList.size(); i++){								
								String clientId = getValueFromKey(arrPdInfoForCartList.get(i), "ClientId");								
								String colorValue = getValueFromKey(arrPdInfoForCartList.get(i), "Color");								
								String sizeValue = getValueFromKey(arrPdInfoForCartList.get(i), "Size");								
								String pdIdValue = getValueFromKey(arrPdInfoForCartList.get(i), "ProdId");
								
								if(pdIdValue.equals(storedProdId) && colorValue.equals(storedColorVal) && sizeValue.equals(storedSizeVal)){
									listPos      = i;
									listQuantity = getValueFromKey(arrPdInfoForCartList.get(i), "Quantity");
								} 
								
								arrForClientColorSizeIds.add(pdIdValue+"~"+colorValue+"~"+sizeValue);
							}
								//String QuantityValue = getValueFromKey(arrPdInfoForCartList.get(i), "Quantity");
								String QuantityNewValue = editvQuantity.getText().toString().isEmpty() ? "1" : editvQuantity.getText().toString();
									if(arrForClientIds.contains(storedClientId)){	
										
										if(arrForClientColorSizeIds.contains(storedProdId+"~"+storedColorVal+"~"+storedSizeVal)){											 
											if(editAction){
												hMapForPdInfo.put("Quantity", String.valueOf(Integer.parseInt(QuantityNewValue)));																										
												if(listPos != listCurrentPos){
													if(arrPdInfoForCartList.size() > listCurrentPos)
														arrPdInfoForCartList.remove(listCurrentPos);
												}
												if(editActionStr == null)
													editAction = false;
											}else{
												hMapForPdInfo.put("Quantity", String.valueOf(Integer.parseInt(listQuantity)+Integer.parseInt(QuantityNewValue)));
											}
											arrPdInfoForCartList.set(listPos, hMapForPdInfo);
											countFlag = 0;
											
										}else{
											 
											if(!editAction){													
												countFlag++;												  
											}else{
												arrPdInfoForCartList.set(listCurrentPos, hMapForPdInfo);
												if(editActionStr == null){													
													editAction = false;
												}
												countFlag =0;
											}											
										}
									
								} else {									
									countFlag++;
									 
								}
								if(arrForClientIds.contains(storedClientId)){									
										countFlagForClient = 0;									
								} else {
									countFlagForClient++;
								}
								
					        if(countFlag>0){
					        	if(!arrForClientIds.contains(storedClientId)){
					        		arrForClientIds.add(storedClientId);
					        	}
								arrPdInfoForCartList.add(hMapForPdInfo);
					        }
					       
							if(countFlagForClient>0){
								arrListHashMapForClientInfo.add(hMapForClientInfo);
							}
						} else{
				        	if(!arrForClientIds.contains(storedClientId)){
				        		arrForClientIds.add(storedClientId);
				        	}
				        	
							arrPdInfoForCartList.add(hMapForPdInfo);
							arrListHashMapForClientInfo.add(hMapForClientInfo);
							
						}
				      
						if(!session.isLoggedIn()){
							new Common().getLoginDialog(ProductDetails.this, ProductDetails.class, "ProductDetails", new ArrayList<String>());
						} else {
							
							Intent intent = new Intent(getApplicationContext(), OrderConfirmation.class);
							if(editAction== true){
								editAction = false;
								
								intent.putExtra("shopFlag", "Edit");
								intent.putExtra("editOrderId", getIntent().getStringExtra("orderId"));									
								setResult(3,intent);
								finish();
							    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);

								/*Bundle bundle = new Bundle();  
								bundle.putSerializable("arrPdInfoForCartList", OrderConfirmation.arrPdInfoForCartEditList);
								bundle.putSerializable("arrListHashMapForClientInfo", OrderConfirmation.arrListHashMapForEditClientInfo);		
								intent.putExtras(bundle);*/
							}else{
								editAction = false;
								if( getIntent().getStringExtra("shopFlag") != null){
									 FileTransaction file  = new FileTransaction();
									 OrderModel orderModel = file.getOrder();
									 UserOrder userOrder = orderModel.getUserOrder(Integer.parseInt(getIntent().getStringExtra("orderId")));													
									 ArrayList<HashMap<String, String>> arrPdInfoForCartList	      = userOrder.getCartInfoList();
									 ArrayList<HashMap<String, String>> arrListHashMapForClientInfo  = userOrder.getCartClientInfoList();
									 ArrayList<String>  arrForClientIds           = userOrder.getClientIds();
									
									   if(arrPdInfoForCartList.size()>0){
											for(int i = 0; i < arrPdInfoForCartList.size(); i++){								
												String clientId = getValueFromKey(arrPdInfoForCartList.get(i), "ClientId");								
												String colorValue = getValueFromKey(arrPdInfoForCartList.get(i), "Color");								
												String sizeValue = getValueFromKey(arrPdInfoForCartList.get(i), "Size");								
												String pdIdValue = getValueFromKey(arrPdInfoForCartList.get(i), "ProdId");
												
												if(pdIdValue.equals(storedProdId) && colorValue.equals(storedColorVal) && sizeValue.equals(storedSizeVal)){
													listPos      = i;
													listQuantity = getValueFromKey(arrPdInfoForCartList.get(i), "Quantity");
												} 
												
												arrForClientColorSizeIds.add(pdIdValue+"~"+colorValue+"~"+sizeValue);
											}
												//String QuantityValue = getValueFromKey(arrPdInfoForCartList.get(i), "Quantity");
												String QuantityNewValue = editvQuantity.getText().toString().isEmpty() ? "1" : editvQuantity.getText().toString();
													if(arrForClientIds.contains(storedClientId)){	
														if(arrForClientColorSizeIds.contains(storedProdId+"~"+storedColorVal+"~"+storedSizeVal)){
															
															if(editAction){
																hMapForPdInfo.put("Quantity", String.valueOf(Integer.parseInt(QuantityNewValue)));
																														
																if(listPos != listCurrentPos){
																	if(arrPdInfoForCartList.size() > listCurrentPos)
																		arrPdInfoForCartList.remove(listCurrentPos);
																}
																if(editActionStr == null)
																	editAction = false;
															}else{
																hMapForPdInfo.put("Quantity", String.valueOf(Integer.parseInt(listQuantity)+Integer.parseInt(QuantityNewValue)));
															}
															arrPdInfoForCartList.set(listPos, hMapForPdInfo);
															countFlag = 0;
															
														}else{
															 
															if(!editAction){
																countFlag++;																  
															}else{
																arrPdInfoForCartList.set(listCurrentPos, hMapForPdInfo);
																if(editActionStr == null){													
																	editAction = false;
																}
																countFlag =0;
															}											
														}
													
												} else {
													countFlag++;													
												}
												if(arrForClientIds.contains(storedClientId)){									
														countFlagForClient = 0;									
												} else {
													countFlagForClient++;
												}
												
									        if(countFlag>0){
									        	if(!arrForClientIds.contains(storedClientId)){
									        		arrForClientIds.add(storedClientId);
									        	}
												arrPdInfoForCartList.add(hMapForPdInfo);
									        }
									       
											if(countFlagForClient>0){
												arrListHashMapForClientInfo.add(hMapForClientInfo);
											}
										} else{
								        	if(!arrForClientIds.contains(storedClientId)){
								        		arrForClientIds.add(storedClientId);
								        	}
								        	
											arrPdInfoForCartList.add(hMapForPdInfo);
											arrListHashMapForClientInfo.add(hMapForClientInfo);
											
										}
									ArrayList<String> arrObjForShippingAddress        = new ArrayList<String>();
									arrObjForShippingAddress.add(userOrder.getUserShipId());
									arrObjForShippingAddress.add(userOrder.getAddress1());
									arrObjForShippingAddress.add(userOrder.getAddress2());
									arrObjForShippingAddress.add(userOrder.getCity());
									arrObjForShippingAddress.add(userOrder.getState());
									arrObjForShippingAddress.add(userOrder.getZip());
									arrObjForShippingAddress.add(userOrder.getCountry());
									
									Bundle bundle = new Bundle();  
									bundle.putSerializable("arrPdInfoForCartList", arrPdInfoForCartList);
									bundle.putSerializable("arrListHashMapForClientInfo", arrListHashMapForClientInfo);						
									bundle.putSerializable("arrForClientIds", arrForClientIds);
									intent.putExtras(bundle);
									intent.putExtra("shopFlag", "Edit");
									intent.putExtra("editOrderId", ""+userOrder.getId());										
									intent.putStringArrayListExtra("arrObjForShippingAddress", arrObjForShippingAddress);
									ProductDetails.arrPdInfoForCartList.clear();
				                    ProductDetails.arrForClientIds.clear();
				                    ProductDetails.arrListHashMapForClientInfo.clear();
				                    OrderConfirmation.arrListJsonObjMySavedOrders.clear();
								}
								startActivityForResult(intent,1);
								overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
							}
							/*storedQuantityVal = "";
							storedSizeVal = "";
							storedColorVal = "";*/
						}
						
						}else{
							if(storedProdUrl.equals("null") || storedProdUrl.equals("") || storedProdUrl == null){
								Toast.makeText(getApplicationContext(), "Product is not available.", Toast.LENGTH_LONG).show();					            	
							} else{
								String[] separated = storedProdUrl.split(":");
								if(separated[0]!=null && separated[0].equals("tel")){
								Intent callIntent = new Intent(Intent.ACTION_CALL);
								callIntent.setData(Uri.parse("tel://"+separated[1]));
			                   	startActivity(callIntent);
								} else if(separated[0]!=null && separated[0].equals("telprompt")){
									Intent callIntent = new Intent(Intent.ACTION_CALL);
				                    callIntent.setData(Uri.parse("tel://"+separated[1]));
			                    	startActivity(callIntent);
								} else {
									Intent intent = new Intent(ProductDetails.this, PurchaseProductWithClientUrl.class);
									intent.putExtra("productName", storedProdName);
									intent.putExtra("finalWebSiteUrl", storedProdUrl);
									startActivity(intent);
									overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);		
								}
							}
						}
					} catch(Exception e){
						e.printStackTrace();
	            		String errorMsg = className+" | btnBuyNow  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
					}
				}
			});
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | oncreate   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
		}
	}
	public void  getColorSizeValues(){
		try{
			String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/products/"+storedProdId;
			aq.ajax(productUrl, JSONObject.class, new AjaxCallback<JSONObject>(){			
				@Override
				public void callback(String url, JSONObject json, AjaxStatus status) {
					try{
						if(json!=null && !json.optString("shippingMethods").equals("")){
							jsonShippingMethodAttriButes = new JSONArray(json.getString("shippingMethods"));
							
							if(jsonShippingMethodAttriButes.length()>0){
								for(int s=0; s<jsonShippingMethodAttriButes.length(); s++){
									
									JSONObject jsonShippingMethod = new JSONObject(jsonShippingMethodAttriButes.getString(s));
									if(!jsonShippingMethod.optString("shipMethodName").equals("")){
										storedShippingMethodName = jsonShippingMethod.getString("shipMethodName");
									}else{
										storedShippingMethodName = "null";
									}
									if(!jsonShippingMethod.optString("shipMethodDetails").equals("")){
										storedShippingMethodDetails = jsonShippingMethod.getString("shipMethodDetails");
									}
									else{
										storedShippingMethodDetails = "null";
									}
									if(!jsonShippingMethod.optString("shipMethodRate").equals("")){
										JSONObject jsonShippingMethodRate = new JSONObject(jsonShippingMethod.getString("shipMethodRate"));
										if(jsonShippingMethodRate.optString("A").equals("")){
	    									storedShippingMethodRate = jsonShippingMethodRate.getString("P");
	    									storedShippingMethodRateForTitle = "P";
										} else if(jsonShippingMethodRate.optString("P").equals("")){
	    									storedShippingMethodRate = jsonShippingMethodRate.getString("A");
	    									storedShippingMethodRateForTitle = "A";
										}
										
									}else{
										storedShippingMethodRateForTitle = "null";
										storedShippingMethodRate="null";
									}
								}
							}
						}else{
							storedShippingMethodName = "null";
							storedShippingMethodRateForTitle = "null";
							storedShippingMethodRate="null";
							storedShippingMethodDetails = "null";
						}
	    				if(json!=null && !json.optString("attrib").equals("")){
	    					final JSONObject jsonAttriButes = new JSONObject(json.getString("attrib"));     								
	    					
	    					if(jsonAttriButes.optString("Color").equals("") && jsonAttriButes.optString("Size").equals("")){
	    						LinearLayout llCategoryForAttributes = (LinearLayout) findViewById(R.id.llCategoryForAttributes);
	    						llCategoryForAttributes.setVisibility(View.INVISIBLE);
	    						viewFlipperAttributes.setVisibility(View.INVISIBLE);
	    					}
	    				
	    					if(!jsonAttriButes.optString("Size").equals("")){
	    						LinearLayout llCategoryForAttributes = (LinearLayout) findViewById(R.id.llCategoryForAttributes);
	    						llCategoryForAttributes.setVisibility(View.VISIBLE);
	    						viewFlipperAttributes.setVisibility(View.VISIBLE);
		    					final JSONArray jsonSizeAttriButes = new JSONArray(jsonAttriButes.getString("Size"));
		    					if(jsonSizeAttriButes.length()>0){
			    					for (int i=0; i<jsonSizeAttriButes.length(); i++){	
			    						String strSetValuesForSize = "sizeInc"+i+"prod"+storedProdId;
						        		int btnSizeIds = Integer.parseInt(strSetValuesForSize.replaceAll("[\\D]", ""));
						        		
						        		int llSizeBgId = (R.string.llForSizeBg+i+5);
			    						final LinearLayout linearLayoutForSizePdNumBg = new LinearLayout(ProductDetails.this);
			    						linearLayoutForSizePdNumBg.setId(llSizeBgId);
			    						linearLayoutForSizePdNumBg.setGravity(Gravity.CENTER);
			    						linearLayoutForSizePdNumBg.setOrientation(LinearLayout.HORIZONTAL);
			    						
			    						LayoutParams linLayoutParam = new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT); 
			    						linLayoutParam.width =(int) (0.25 * Common.sessionDeviceWidth);
			    						linearLayoutForSizePdNumBg.setLayoutParams(linLayoutParam);
			    						linearLayoutForSizePdNumBg.setBackgroundResource(R.drawable.pd_quantity_num_bg);
			    						
						        		
			    						final TextView btnValSizes = new TextView(ProductDetails.this);
			    						btnValSizes.setId(btnSizeIds);
			    						btnValSizes.setText(jsonSizeAttriButes.getString(i));
			    						btnValSizes.setTag(jsonSizeAttriButes.getString(i));
			    						btnValSizes.setWidth((int) (0.167 * Common.sessionDeviceWidth));
			    						btnValSizes.setHeight((int)(0.04 * Common.sessionDeviceHeight));
			    						btnValSizes.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			    						//btnValSizes.setTypeface(null, Typeface.BOLD);
			    						btnValSizes.setTextColor(Color.BLACK);
			    						btnValSizes.setGravity(Gravity.CENTER);
			    						btnValSizes.setPadding(5,5,5,5);
			    						if(i==0 && storedSizeVal.equals("null")){				    							
			    							storedSizeVal = jsonSizeAttriButes.getString(i);				    							
				    						btnValSizes.setBackgroundResource(R.drawable.button_border_grey);
				    						txtvSizeCode.setVisibility(View.VISIBLE);
					                    	txtvSizeCode.setText(storedSizeVal);
					                    	RelativeLayout.LayoutParams llForTxtvSize = (RelativeLayout.LayoutParams) txtvSize.getLayoutParams();
					                		llForTxtvSize.addRule(RelativeLayout.ALIGN_PARENT_TOP);
					                		txtvSize.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
					                		txtvSize.setLayoutParams(llForTxtvSize);
					                		
			    						} else if(storedSizeVal.equals(btnValSizes.getText().toString())){	
			    							btnValSizes.setBackgroundResource(R.drawable.button_border_grey);
				    						txtvSizeCode.setVisibility(View.VISIBLE);
					                    	txtvSizeCode.setText(storedSizeVal);
					                    	RelativeLayout.LayoutParams llForTxtvSize = (RelativeLayout.LayoutParams) txtvSize.getLayoutParams();
					                		llForTxtvSize.addRule(RelativeLayout.ALIGN_PARENT_TOP);
					                		txtvSize.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
					                		txtvSize.setLayoutParams(llForTxtvSize);
			    					    }      	
			    					    btnValSizes.setOnClickListener(new OnClickListener() {
			    					        @Override
											public void onClick(View v) {
			    					        	try{
			    					        		//btnValSizes.setBackgroundResource(R.drawable.button_border_grey);
			    					        		RelativeLayout.LayoutParams llForTxtvSize = (RelativeLayout.LayoutParams) txtvSize.getLayoutParams();
	    					                		llForTxtvSize.addRule(RelativeLayout.ALIGN_PARENT_TOP);
	    					                		txtvSize.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
	    					                		txtvSize.setLayoutParams(llForTxtvSize);
	    					                		btnSizeLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
	    					                		txtvSize.setTextColor(Color.WHITE);
			    					        		for(int s=0; s<jsonSizeAttriButes.length(); s++){
			    					        			String strSetValuesForSizeNew = "sizeInc"+s+"prod"+storedProdId;
										        		int btnSizeIdsNew = Integer.parseInt(strSetValuesForSizeNew.replaceAll("[\\D]", ""));
										        		ProductDetails.this.findViewById(btnSizeIdsNew).setBackgroundResource(0);
			    					        			if(jsonSizeAttriButes.getString(s).equals(btnValSizes.getText().toString())){
			    						        			storedSizeVal = btnValSizes.getText().toString();
			    						        			ProductDetails.this.findViewById(btnSizeIdsNew).setBackgroundResource(R.drawable.button_border_grey);
			    						        			txtvSizeCode.setVisibility(View.VISIBLE);
			    					                    	txtvSizeCode.setText(storedSizeVal);
														} 
			    					        		}
			    					        	} catch(Exception e){
			    									e.printStackTrace();
			    						    		String errorMsg = className+" | btnValSizes  click  |   " +e.getMessage();
			    									Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
			    								}
			    					        }
			    					    });
			    					    
			    					    linearLayoutForSizePdNumBg.setOnClickListener(new OnClickListener() {
			    					        @Override
											public void onClick(View v) {
			    					        	try{
			    					        		RelativeLayout.LayoutParams llForTxtvSize = (RelativeLayout.LayoutParams) txtvSize.getLayoutParams();
	    					                		llForTxtvSize.addRule(RelativeLayout.ALIGN_PARENT_TOP);
	    					                		txtvSize.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
	    					                		txtvSize.setLayoutParams(llForTxtvSize);
	    					                		btnSizeLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
	    					                		txtvSize.setTextColor(Color.WHITE);
			    					        		for(int s=0; s<jsonSizeAttriButes.length(); s++){
			    					        			String strSetValuesForSizeNew = "sizeInc"+s+"prod"+storedProdId;
										        		int btnSizeIdsNew = Integer.parseInt(strSetValuesForSizeNew.replaceAll("[\\D]", ""));
										        		ProductDetails.this.findViewById(btnSizeIdsNew).setBackgroundResource(0);
			    					        			if(jsonSizeAttriButes.getString(s).equals(btnValSizes.getText().toString())){
			    						        			storedSizeVal = btnValSizes.getText().toString();
			    						        			ProductDetails.this.findViewById(btnSizeIdsNew).setBackgroundResource(R.drawable.button_border_grey);
			    					                        txtvSizeCode.setVisibility(View.VISIBLE);
			    					                        txtvSizeCode.setText(storedSizeVal);
														} 
			    					        		}
			    					        	} catch(Exception e){
			    									e.printStackTrace();
			    						    		String errorMsg = className+" | linearLayoutPdNumBg  click  |   " +e.getMessage();
			    									Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
			    								}
			    					        }
			    					    });
			    					    linearLayoutForSizePdNumBg.addView(btnValSizes);
			    					    horizScrViewSizeLinearLayout.addView(linearLayoutForSizePdNumBg);
			    					}
			    					horizScrViewSize.removeAllViews();
			    					horizScrViewSize.addView(horizScrViewSizeLinearLayout);
		    					}
		    				}
	    							    					
	    					if(!jsonAttriButes.optString("Color").equals("")){
	    						LinearLayout llCategoryForAttributes = (LinearLayout) findViewById(R.id.llCategoryForAttributes);
	    						llCategoryForAttributes.setVisibility(View.VISIBLE);
	    						viewFlipperAttributes.setVisibility(View.VISIBLE);
		    					final JSONArray jsonColorAttriButes = new JSONArray(jsonAttriButes.getString("Color"));
		    					if(jsonColorAttriButes.length()>0){
			    					for(int i=0; i<jsonColorAttriButes.length(); i++){
			    						int llColorBgId = (R.string.llForColorBg+i+5);
			    						//int llColorBgButtonId = (R.string.llForColorBgButton+i+10);
			    						int llForColorButtonId  = (R.string.llForColorBgButton+i+20);
			    						//Log.e("llColorBgButtonId",""+llColorBgButtonId);
			    						final LinearLayout linearLayoutForPdNumBg = new LinearLayout(ProductDetails.this);
			    						linearLayoutForPdNumBg.setId(llColorBgId);
			    						linearLayoutForPdNumBg.setGravity(Gravity.CENTER);
			    						linearLayoutForPdNumBg.setOrientation(LinearLayout.HORIZONTAL);
			    						
			    						LayoutParams linLayoutParam = new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT); 
			    						linLayoutParam.width =(int) (0.334 * Common.sessionDeviceWidth);
			    						linearLayoutForPdNumBg.setLayoutParams(linLayoutParam);
			    						final Button btnValColors = new Button(ProductDetails.this);				    						
			    						btnValColors.setId(llForColorButtonId);	
			    						btnValColors.setWidth((int) (0.2 * Common.sessionDeviceWidth));
			    						btnValColors.setHeight((int) (0.041 * Common.sessionDeviceHeight));
			    						btnValColors.setGravity(Gravity.CENTER);
			    						btnValColors.setBackgroundColor(Color.parseColor(jsonColorAttriButes.getString(i)));
			    						btnValColors.setTag(jsonColorAttriButes.getString(i));
			    						
			    						if(i==0 && storedColorVal.equals("null")){
			    							storedColorVal = jsonColorAttriButes.getString(i);
			    							btnValColors.setBackgroundColor(Color.parseColor(storedColorVal));
			    							linearLayoutForPdNumBg.setBackgroundResource(R.drawable.pd_quantity_num_bg);
			    							Drawable backgroundRes = btnValColors.getBackground();    
					        				Drawable drawableRes = getResources().getDrawable(R.drawable.button_border_red);
				    						Drawable[] drawableLayers = { backgroundRes, drawableRes };
				    						LayerDrawable ld = new LayerDrawable(drawableLayers);
				    						btnValColors.setBackgroundDrawable(ld);
				    						RelativeLayout.LayoutParams llForTxtvColor = (RelativeLayout.LayoutParams) txtvColor.getLayoutParams();
					                		llForTxtvColor.addRule(RelativeLayout.ALIGN_PARENT_TOP);
					                		txtvColor.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
					                		txtvColor.setLayoutParams(llForTxtvColor);
					                    	btnColorLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
					                    	txtvColor.setTextColor(Color.WHITE);
				    						txtvColorCode.setVisibility(View.VISIBLE);
					                    	txtvColorCode.setBackgroundColor(Color.parseColor(storedColorVal));
					                    	
											horizScrViewSize.setVisibility(View.INVISIBLE);
											horizScrViewColor.setVisibility(View.VISIBLE);	
			    						} else if(storedColorVal.equals(jsonColorAttriButes.getString(i))){	
			    							btnValColors.setBackgroundColor(Color.parseColor(storedColorVal));
			    							linearLayoutForPdNumBg.setBackgroundResource(R.drawable.pd_quantity_num_bg);
			    							Drawable backgroundRes = btnValColors.getBackground();    
					        				Drawable drawableRes = getResources().getDrawable(R.drawable.button_border_red);
				    						Drawable[] drawableLayers = { backgroundRes, drawableRes };
				    						LayerDrawable ld = new LayerDrawable(drawableLayers);
				    						btnValColors.setBackgroundDrawable(ld);
				    						RelativeLayout.LayoutParams llForTxtvColor = (RelativeLayout.LayoutParams) txtvColor.getLayoutParams();
					                		llForTxtvColor.addRule(RelativeLayout.ALIGN_PARENT_TOP);
					                		txtvColor.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
					                		txtvColor.setLayoutParams(llForTxtvColor);
					                    	btnColorLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
					                    	txtvColor.setTextColor(Color.WHITE);
				    						txtvColorCode.setVisibility(View.VISIBLE);
					                    	txtvColorCode.setBackgroundColor(Color.parseColor(storedColorVal));
			    					    } else {
			    							btnValColors.setBackgroundColor(Color.parseColor(jsonColorAttriButes.getString(i)));
			    					    	linearLayoutForPdNumBg.setBackgroundResource(R.drawable.pd_quantity_num_bg);		    							
			    						}   	    
			    						Drawable backgroundRes = btnValColors.getBackground();    
			    						/*Drawable drawableRes = getResources().getDrawable(R.drawable.button_border);
			    						Drawable[] drawableLayers = { backgroundRes, drawableRes };
			    						LayerDrawable ld = new LayerDrawable(drawableLayers);
			    						btnValColors.setBackgroundDrawable(ld);*/
			    						btnValColors.setOnClickListener(new OnClickListener() {
			    					        @Override
											public void onClick(View v) {
			    					        	try{	
			    					        		RelativeLayout.LayoutParams llForTxtvColor = (RelativeLayout.LayoutParams) txtvColor.getLayoutParams();
	    					                		llForTxtvColor.addRule(RelativeLayout.ALIGN_PARENT_TOP);
	    					                		txtvColor.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
	    					                		txtvColor.setLayoutParams(llForTxtvColor);
	    					                    	btnColorLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
	    					                    	txtvColor.setTextColor(Color.WHITE);
	    					                    	
			    					        		for(int c=0; c<jsonColorAttriButes.length(); c++){
			    					        			
			    					        			int llColorBgIdNew = (R.string.llForColorBg+c+5);
			    					        			
			    					        			(ProductDetails.this.findViewById(llColorBgIdNew)).setBackgroundResource(R.drawable.pd_quantity_num_bg);
			    					        			if(jsonColorAttriButes.get(c).toString().equals(btnValColors.getTag().toString())){
			    					        				storedColorVal = jsonColorAttriButes.getString(c);
			    					        				//linearLayoutForPdNumBg.setBackgroundResource(R.drawable.pd_quantity_num_bg_enabled);		
			    					        				//(ProductDetails.this.findViewById(llForColorBgButton)).setBackgroundResource(R.drawable.button_border_red);
			    					        				Drawable backgroundRes = btnValColors.getBackground();    
			    					        				Drawable drawableRes = getResources().getDrawable(R.drawable.button_border_red);
			    				    						Drawable[] drawableLayers = { backgroundRes, drawableRes };
			    				    						LayerDrawable ld = new LayerDrawable(drawableLayers);
			    				    						btnValColors.setBackgroundDrawable(ld);
			    					                    	txtvColorCode.setVisibility(View.VISIBLE);
			    					                    	txtvColorCode.setBackgroundColor(Color.parseColor(storedColorVal));
			    						        		}else{
			    						        			
			    						        			int llForColorButtonId  = (R.string.llForColorBgButton+c+20);
			    						        			findViewById(llForColorButtonId).setBackgroundResource(0);
			    						        			findViewById(llForColorButtonId).setBackgroundColor(Color.parseColor(jsonColorAttriButes.getString(c)));
			    						        			
			    						        		}	
			    								    }		        		
			    					        	} catch(Exception e){
			    									e.printStackTrace();
			    						    		String errorMsg = className+" | btnValColors  click  |   " +e.getMessage();
			    									Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
			    								}
			    					        }
			    					    });
			    						linearLayoutForPdNumBg.setOnClickListener(new OnClickListener() {
			    					        @Override
											public void onClick(View v) {
			    					        	try{	
			    					        		RelativeLayout.LayoutParams llForTxtvColor = (RelativeLayout.LayoutParams) txtvColor.getLayoutParams();
	    					                		llForTxtvColor.addRule(RelativeLayout.ALIGN_PARENT_TOP);
	    					                		txtvColor.setGravity(Gravity.CENTER_VERTICAL | Gravity.CENTER_HORIZONTAL);
	    					                		txtvColor.setLayoutParams(llForTxtvColor);
	    					                    	btnColorLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));
	    					                    	txtvColor.setTextColor(Color.WHITE);
	    					                    	
			    					        		for(int c=0; c<jsonColorAttriButes.length(); c++){
			    					        			
			    					        			int llColorBgIdNew = (R.string.llForColorBg+c+5);
			    					        			int llForColorBgButton = (R.string.llForColorBgButton+c+10);
			    					        			
			    					        			(ProductDetails.this.findViewById(llColorBgIdNew)).setBackgroundResource(R.drawable.pd_quantity_num_bg);
			    					        			if(jsonColorAttriButes.get(c).toString().equals(btnValColors.getTag().toString())){
			    					        				storedColorVal = jsonColorAttriButes.getString(c);
			    					        				
			    					        				Drawable backgroundRes = btnValColors.getBackground();    
			    					        				Drawable drawableRes = getResources().getDrawable(R.drawable.button_border_red);
			    				    						Drawable[] drawableLayers = { backgroundRes, drawableRes };
			    				    						LayerDrawable ld = new LayerDrawable(drawableLayers);
			    				    						btnValColors.setBackgroundDrawable(ld);
			    					                    	txtvColorCode.setVisibility(View.VISIBLE);
			    					                    	txtvColorCode.setBackgroundColor(Color.parseColor(storedColorVal));
			    						        		}else{			    						        			
			    						        			int llForColorButtonId  = (R.string.llForColorBgButton+c+20);
			    						        			findViewById(llForColorButtonId).setBackgroundResource(0);
			    						        			findViewById(llForColorButtonId).setBackgroundColor(Color.parseColor(jsonColorAttriButes.getString(c)));
			    						        			
			    						        		}	
			    								    }		        		
			    					        	} catch(Exception e){
			    									e.printStackTrace();
			    						    		String errorMsg = className+" | linearLayoutPdNumBg  click  |   " +e.getMessage();
			    									Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);
			    								}
			    					        }
			    					    });
			    					    linearLayoutForPdNumBg.addView(btnValColors);
			    					    horizScrViewColorLinearLayout.addView(linearLayoutForPdNumBg);				    						
			    					}
			    					horizScrViewColor.removeAllViews();
		    					    horizScrViewColor.addView(horizScrViewColorLinearLayout); 
		    					}
	    					}
	    					if(jsonAttriButes.optString("quantity").equals("")){
	    						editvQuantity.setText("1");	    						
	    					}
	    					if(editActionStr != null && editActionStr.equals("true")){
	    						editAction = true;
	    						editvQuantity.setText(quantity);
	    					}
	    				}else{
	    						LinearLayout llCategoryForAttributes = (LinearLayout) findViewById(R.id.llCategoryForAttributes);
	    						llCategoryForAttributes.setVisibility(View.INVISIBLE);
	    						viewFlipperAttributes.setVisibility(View.INVISIBLE);
	    						if(editActionStr != null && editActionStr.equals("true")){
	        						editAction = true;
	        						editvQuantity.setText(quantity);
	        					}
	    				}
					}catch(Exception e){
						e.printStackTrace();	
			    		String errorMsg = className+" | aq.ajax Products  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);						
					}
				}
			});	
			}catch(Exception e){
			e.printStackTrace();	
    		String errorMsg = className+" | getColorSizeValues  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductDetails.this, errorMsg);						
		}
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data)
	{
	    super.onActivityResult(requestCode, resultCode, data);
	    try{
	    	if(resultCode == 1){
		    	if(data.getSerializableExtra("list") != null){
		    		listCurrentPos = Integer.parseInt(data.getStringExtra("pos"));
		    		
			    	HashMap<String, String> hashMap = (HashMap<String, String>) data.getSerializableExtra("list");
		    		storedQuantityVal = hashMap.get("Quantity");
			    	storedSizeVal = hashMap.get("Size");
			    	storedColorVal = hashMap.get("Color");
			    	horizScrViewSizeLinearLayout.removeAllViews();
			    	horizScrViewColorLinearLayout.removeAllViews();
			    	
			    	storedProdId    = hashMap.get("ProdId");	
			    	storedProdImage = hashMap.get("ProdImage");		
					storedClientId  = hashMap.get("ClientId");					
					storedProdName  = hashMap.get("ProdName");		
					storedProdPrice = hashMap.get("ProdPrice");	
					storedProdUrl   = hashMap.get("ClientId");
			    	
			    	getColorSizeValues();
					Common.sessionClientBgColor = hashMap.get("BgColorCode");
					Common.sessionClientLogo = hashMap.get("ClientLogo");
					Common.sessionClientBackgroundLightColor = hashMap.get("LightBgColorCode");
					Common.sessionClientBackgroundDarkColor = hashMap.get("DarkBgColorCode");
					//Common.sessionClientName = prodStrArr[1].trim();
					Common.sessionClientId = hashMap.get("ClientId");
					
					
					new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
							this, Common.sessionClientBgColor,
							Common.sessionClientBackgroundLightColor,
							Common.sessionClientBackgroundDarkColor,
							Common.sessionClientLogo, storedProdName, "");
					
					Bitmap bmpImage = null;				
					if(aq.getCachedImage(storedProdImage)==null){
						URL url = new URL(storedProdImage);
						aq.cache(storedProdImage, 14400000);
		    			bmpImage = BitmapFactory.decodeStream(url.openStream());
					} else {
						bmpImage = aq.getCachedImage(storedProdImage);
					}
					imgvProdDetailsBigImg.setImageBitmap(bmpImage);
					txtProdImageName.setText(storedProdName);
					txtProdImagePrice.setText("$"+storedProdPrice);
					editvQuantity.setText(storedQuantityVal);
					
					btnColorLayout.setBackgroundColor(Color.parseColor("#AAAAAA"));							
					txtvColor.setTextColor(Color.WHITE);
					btnSizeLayout.setBackgroundResource(R.drawable.pd_client_horiz_bg);
					txtvSize.setTextColor(Color.parseColor("#747474"));				
	        	
					horizScrViewSize.setVisibility(View.INVISIBLE);
					horizScrViewColor.setVisibility(View.VISIBLE);	
					
					
		    	}
	    	
	    	}
	    }catch(Exception e){
		   e.printStackTrace();
	    }
	}

	public static <T, E> T getKeyFromValue(Map<T, E> map, E value) {
	    for (Entry<T, E> entry : map.entrySet()) {
	        if (value.equals(entry.getValue())) {
	            return entry.getKey();
	        }
	    }
	    return null;
	}
	public static <T, E> String getValueFromKey(Map<T, E> map, E key) {
	    for (Entry<T, E> entry : map.entrySet()) {
	        if (key.equals(entry.getKey())) {
	            return entry.getValue().toString();
	        }
	    }
	    return null;
	}
	public static Bitmap addWaterMark(Bitmap src, String watermark) {
		try{
	        int w = src.getWidth();
	        int h = src.getHeight();
	        Bitmap result = Bitmap.createBitmap(w, h, src.getConfig());
	        Canvas canvas = new Canvas(result);
	        canvas.drawBitmap(src, 0, 0, null);
	        Paint paint = new Paint();
	        paint.setColor(Color.BLACK);
	        paint.setTextSize(18);
	        paint.setTypeface(Typeface.defaultFromStyle(Typeface.BOLD));
	        paint.setAntiAlias(true);
	        paint.setUnderlineText(false);
	        paint.setTextAlign(Align.CENTER);
	        float x = w/2;
	        float y = h-((float)0.0205 * Common.sessionDeviceHeight);
	        if(watermark.startsWith("#")){
	        	Paint paint1 = new Paint();
	        	paint1.setColor(Color.parseColor(watermark));
	        	canvas.drawText(watermark, x, y, paint1);
	        } else {
	        	canvas.drawText(watermark, x, y, paint);	        	
	        }
	        return result;
		
		}catch(Exception e){
			e.printStackTrace();
	       	return null;
		}
    }
}
