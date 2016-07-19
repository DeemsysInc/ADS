package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.net.URL;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONObject;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.ClipData;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.GradientDrawable;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.DragEvent;
import android.view.KeyEvent;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.DragShadowBuilder;
import android.view.View.OnClickListener;
import android.view.View.OnDragListener;
import android.view.View.OnTouchListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.ViewAnimator;

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
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.flip3D.AnimationFactory;
import com.seemoreinteractive.seemoreinteractive.flip3D.AnimationFactory.FlipDirection;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(17)

public class ProductList extends Activity implements OnTouchListener, OnDragListener {
	/** Called when the activity is first created. */
	public boolean isBackPressed = false;

	final Context context = this;
	View lastSelectedView = null;

	SessionManager session;
	String className = this.getClass().getSimpleName();
	ImageView imgBig;
	ArrayList<String> bitmapList;
	public Boolean alertErrorType = true;
	TextView txtProdName, txtProdPrice, txtProdId, txtProdShortDesc, txtProdIdCurves;
	int prodIsTryOn = 0;
	
	JSONObject cj;
	JSONObject cj1;
	JSONObject cj2;
	JSONObject cj3;
	JSONObject cj4;
	JSONArray clientsJsonArr, clientsJsonArr1;
	JSONArray clientsJsonArrRelatedProd = null;
	
	boolean flag = false;
	String[] storeArrayValues = {};
	private boolean insideOfMe = false;
	private boolean insideOfMe1 = false;
	ArrayList<String> stringArrayList;
	//Common Common;
	String getProductImageUrl;
    
    View bigImgOnTouch;
    int getDefaultImgWidth = 0, getDefaultImgHeight = 0;
	AQuery aq;
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;

	Button btnSeeItLive;
	ViewAnimator viewAnimator; 
	String getProductUrl,getAllProductUrl;
	FileTransaction file;

	int newGeneratedWidth = 0;
	int newGeneratedHeight = 0;
	int p=0, pdIsTryOn = 0, pdIsTryOn1 = 0, pdIsTryOn2=0, pdIsTryOn3=0, pdIsTryOn4=0, pdIsTryOn5=0;
	String pdid1 ="",pdid2 ="",pdid3 ="",pdid4 ="",pdid5 ="",pageRedirectFlag="null";
	View viewLeft1, viewLeft2, viewMiddle, viewRight2, viewRight1, imgDragedToBigImgLayout;
	XmlDom pdXml1, pdXml2, pdXml3, pdXml4, pdXml5;
	int flagForPds = 0;
	Boolean changeFlag = false;
	Boolean downloadFlag = false;
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_product_list);	    
		try{
			
			aq = new AQuery(ProductList.this);
			flag = true;
			
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "");

			getProductUrl = Constants.Client_Url+Common.sessionClientId+"/related_products/";
			
			file = new FileTransaction();
			// Session class instance
			session = new SessionManager(ProductList.this);
	        			
			String screenName = "/product/"+Common.sessionClientId+"/"+Common.sessionProductId;
			String productIds = Common.sessionProductId;
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
			
			if (android.os.Build.VERSION.SDK_INT > 9) {
				StrictMode.ThreadPolicy policy = 
				        new StrictMode.ThreadPolicy.Builder().permitAll().build();
				StrictMode.setThreadPolicy(policy);
			}
			btnSeeItLive = (Button)findViewById(R.id.btnSeeItLive);
			
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
					intent.putExtra("screen_name", "product");
					startActivity(intent);			
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvHelp   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductList.this,errorMsg);
					}
				}
			});
			viewAnimator = (ViewAnimator) findViewById(R.id.viewFlipperProd);	
			 try {
				
	    		Intent prointent = getIntent();	   
	    		if(prointent.getExtras()!=null){
	    			pageRedirectFlag = getIntent().getStringExtra("pageRedirectFlag");	    			
	    			getProductImageUrl = prointent.getStringExtra("product_image_url");
					txtProdId = new TextView(this);
					txtProdId.setText(Common.sessionProductId);
					txtProdId.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					
					txtProdName = (TextView)findViewById(R.id.txtvProductName);
					//txtProdName.setText(Common.sessionProductName);
					txtProdName.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					
					txtProdPrice = (TextView)findViewById(R.id.txtvProductPrice);
					//txtProdPrice.setText(Common.sessionProductPrice);
					txtProdPrice.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					RelativeLayout.LayoutParams rlpProdPrice = (RelativeLayout.LayoutParams) txtProdPrice.getLayoutParams();
					rlpProdPrice.width = (int) (0.29 * Common.sessionDeviceWidth);
					txtProdPrice.setLayoutParams(rlpProdPrice);
					
					txtProdShortDesc = (TextView)findViewById(R.id.txtvShortDescription);
					if(!Common.sessionProductShortDesc.equals("null")){
						//txtProdShortDesc.setText(Common.sessionProductShortDesc);						
					} else {
						//txtProdShortDesc.setText("");						
					}
					txtProdShortDesc.setTextSize((float) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					RelativeLayout.LayoutParams rlpProdShortDesc = (RelativeLayout.LayoutParams) txtProdShortDesc.getLayoutParams();
					rlpProdShortDesc.width = (int) (0.782 * Common.sessionDeviceWidth);
					rlpProdShortDesc.height = LayoutParams.WRAP_CONTENT;
					txtProdShortDesc.setLayoutParams(rlpProdShortDesc);
					
					prodIsTryOn = Common.sessionProdIsTryOn;
					
	    			bitmapList = new ArrayList<String>();
	    			imgBig = (ImageView) findViewById(R.id.imgvBigImg);
	    			bigImageLinearLayoutWidth = imgBig.getLayoutParams().width;
	    			bigImageLinearLayoutHeight = imgBig.getLayoutParams().height;
	    			
	    			
	    			if(Common.isNetworkAvailable(ProductList.this)){	  
	    				getInstruction(5);
	    				
	    				String productUrl = Constants.Client_Url+Common.sessionClientId+"/products/"+Common.sessionProductId;
	    				Log.e("productUrl",productUrl);
	    				ProductModel  getProdDetail = file.getProduct();
	    				UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(Common.sessionProductId));
	    				if(chkProdExist == null){
	    					getProductDetailsFromServer(productUrl);    					
	    				}else{	    					
	    					checkProductExistinChangeLog(Common.sessionProductId);
	    				}
	    				
	    				/* String closetUrl = Constants.Closet_Url+"xml/"+Common.sessionIdForUserLoggedIn;
	    					aq = new AQuery(this);
	    					aq.ajax(closetUrl+"/", XmlDom.class, new AjaxCallback<XmlDom>(){			
	    						@Override
	    						public void callback(String url, XmlDom xml, AjaxStatus status) {
	    							try{
	    			    				if(xml!=null){
	    			    					List<XmlDom> closetXmlTag = xml.tags("prodCloset");
	    			    					//Log.e("closetXmlTag",""+closetXmlTag);
	    			    					if(closetXmlTag.size()>0){
	    			    						new Common().showDrawableImageFromAquery(ProductList.this, R.drawable.btn_closetwithitem, R.id.imgvBtnCloset);
	    			    					}else{
	    			    						new Common().showDrawableImageFromAquery(ProductList.this, R.drawable.btn_closet, R.id.imgvBtnCloset);
	    			    					}
	    			    				}
	    							}catch(Exception e){
	    								e.printStackTrace();
	    								 String errorMsg = className+" | closetUrl click    |   " +e.getMessage();
	    			        			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
	    								
	    							}
	    						}
	    						});	    	*/		
		    		
	    			}else{
							getInstruction(5);
							Bitmap bitmap = aq.getCachedImage(getProductImageUrl);
							if(bitmap==null){
								aq.cache(getProductImageUrl, 14400000);								
								Intent returnIntent = new Intent();
								returnIntent.putExtra("activity","menu");
								setResult(RESULT_OK,returnIntent);
								finish();	
							}
							imgBig.setImageBitmap(bitmap);
							
							/*ClosetModel closetmodel = file.getCloset();
							if(closetmodel.size() >0){	
								new Common().showDrawableImageFromAquery(ProductList.this, R.drawable.btn_closetwithitem, R.id.imgvBtnCloset);
	    					}else{
	    						new Common().showDrawableImageFromAquery(ProductList.this, R.drawable.btn_closet, R.id.imgvBtnCloset);
	    					}*/
							
	    			}
	    			
	    			
	    			LinearLayout bigImageLayout = (LinearLayout) findViewById(R.id.bigImageLayout);
					new Common().gradientDrawableCorners(ProductList.this, bigImageLayout, null, 0.0334, 0.0167);

	    			RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) bigImageLayout.getLayoutParams();
	    			rlpForLlImg.width = (int) (0.797 * Common.sessionDeviceWidth);
	    			rlpForLlImg.height = (int) (0.5072 * Common.sessionDeviceHeight);
	    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
	    			bigImageLayout.setLayoutParams(rlpForLlImg);
	    			
	    			LinearLayout.LayoutParams llParamsForBigImg = (LinearLayout.LayoutParams) imgBig.getLayoutParams();
	    			llParamsForBigImg.width = (int) (0.717 * Common.sessionDeviceWidth);
	    			llParamsForBigImg.height = (int) (0.462 * Common.sessionDeviceHeight);
	    			imgBig.setLayoutParams(llParamsForBigImg);
	    			imgBig.setScaleType(ScaleType.FIT_CENTER);
	    			
	    			if(Common.sessionProductHideImage.equals("1")){
	    				bigImageLayout.setBackgroundColor(Color.TRANSPARENT);
	    			}
	    			Bitmap bitmap = aq.getCachedImage(getProductImageUrl);
	    			if(bitmap!=null){
		    			
		    			if(bitmap.getWidth()<=bitmap.getHeight()){
			    			newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);			    			
		    			}
		    			else if(bitmap.getWidth()>=bitmap.getHeight()){
			    			newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);			    			
		    			}
	    			} else {
	    				if(Common.isNetworkAvailable(ProductList.this)){    	    			
	    				URL url = new URL(getProductImageUrl);
	    				aq.cache(getProductImageUrl, 14400000);
		    			Bitmap bitmap1 = BitmapFactory.decodeStream(url.openStream());		    			
		    			if(bitmap1.getWidth()<=bitmap1.getHeight()){
			    			newGeneratedWidth = createNewWidthForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutHeight);
		    			}
		    			else if(bitmap1.getWidth()>=bitmap1.getHeight()){
			    			newGeneratedHeight = createNewHeightForImage(bitmap1.getWidth(), bitmap1.getHeight(), bigImageLinearLayoutWidth);
		    			}
		    			bitmap = bitmap1;
	    				}
	    			}
	    		}
				ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
				imgBackButton.setOnClickListener(new OnClickListener() {		
					@Override
					public void onClick(View v) {	
						try{
							Log.e("pageRedirectFlag","pageRedirectFlag"+pageRedirectFlag);
						if(pageRedirectFlag == null || pageRedirectFlag.equals("null")){
							 new Common().deleteFiles(Constants.Products_Location);
					    	 Intent Home = new Intent(getApplicationContext(), ARDisplayActivity.class);
					         setResult(1,Home);
						     finish();
						     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
						}else{
							if(pageRedirectFlag.equals("RecentlyScanned")){
								Intent intent = new Intent(ProductList.this, RecentlyScanned.class);									
								startActivityForResult(intent, 1);
								finish();
								overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							}
				    	
						}
						}catch(Exception e){
							e.printStackTrace();
							 String errorMsg = className+" | imgBackButton click    |   " +e.getMessage();
		        			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
							
						}
					}
				});
				
				ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle);
				imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View view) {
						/*MenuOptions showOptionsObj = new MenuOptions(context);
						showOptionsObj.showMenuOptions();*/
						try{	
						Intent intent = new Intent(getApplicationContext(), MenuOptions.class);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
						//hideInstruction(view);
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
						}catch(Exception e){
							e.printStackTrace();
							 String errorMsg = className+" | imgFooterMiddle click    |   " +e.getMessage();
		        			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
						}
					}
				});
				ImageView imgBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
				imgBtnCloset.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View v) {
						try{
						//Toast.makeText(activityThis, ""+redirectedActivityClass, Toast.LENGTH_LONG).show();
						new Common().getLoginDialog(ProductList.this, Closet.class, "Closet", new ArrayList<String>());
						}catch(Exception e){
							e.printStackTrace();
							 String errorMsg = className+" | imgBtnCloset click    |   " +e.getMessage();
		        			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
						}
					}
				});
				
				
		    	ImageView imgBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
		    	imgBtnShare.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View arg0) {
						try{
							ImageView bigImage = (ImageView) findViewById(R.id.imgvBigImg);
							BitmapDrawable test = (BitmapDrawable) bigImage.getDrawable();
							Bitmap bitmap = test.getBitmap();
							ByteArrayOutputStream baos = new ByteArrayOutputStream();
							bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
							byte[] b = baos.toByteArray();
							Intent intent = new Intent(ProductList.this, ShareActivity.class);
							intent.putExtra("tapOnImage", false);
							//Log.i("image", ""+b);
							intent.putExtra("image", b);		
							intent.putExtra("productId",  txtProdId.getText());
							intent.putExtra("clientId", Common.sessionClientId);						
							startActivityForResult(intent, 1);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);					
							//hideInstruction(arg0);
						}catch(Exception e){
							e.printStackTrace();
							 String errorMsg = className+" | imgBtnShare click    |   " +e.getMessage();
		        			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
						}
					}
				});
		    	if(Common.isNetworkAvailable(ProductList.this)){					
		    		getTapForDetailResultsFromServerWithXml(Constants.TapForDetials_Url+Common.sessionClientId+"/"+Common.sessionProductId+"/");		
		    	}else{
		    		productsListXmlResultFromFile(Common.sessionProductId);
		    	}
	    		
				dragToButtonCart();
				
				if (android.os.Build.VERSION.SDK_INT > 10) {
					//Log.i("productId", ""+txtProdId.getText());
					bigImgOnTouch = findViewById(R.id.imgvBigImg);
					bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
					bigImgOnTouch.setTag(R.string.bigImgTouchKeyDragDown, "drag to down");
					bigImgOnTouch.setTag(R.string.bigImgTouchKeyDragUp, "drag to up");
					bigImgOnTouch.setTag(R.string.bigImgTouchKeyDragTo, "drag to");
					bigImgOnTouch.setOnTouchListener(this);
					
					View imgDragedToCart = findViewById(R.id.imgvBtnCart);
					imgDragedToCart.setOnDragListener(this);
					
					View imgDragedToShare = findViewById(R.id.imgvBtnShare);
					imgDragedToShare.setOnDragListener(this);
					
					View imgDragedToCloset = findViewById(R.id.imgvBtnCloset);
					imgDragedToCloset.setOnDragListener(this);
					
				}		
				
				new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLive, "relative", "width", "products", 
						txtProdId.getText().toString(), Common.sessionClientId, Common.sessionProdIsTryOn, 
						Common.sessionClientBgColor, Common.sessionClientBackgroundLightColor, Common.sessionClientBackgroundDarkColor);
				
				ImageView imgTapForDetails = (ImageView)findViewById(R.id.imgvTapForDetails);
				RelativeLayout.LayoutParams rlpImgTapForDetails = (RelativeLayout.LayoutParams) imgTapForDetails.getLayoutParams();
				rlpImgTapForDetails.width = (int) (0.024 * Common.sessionDeviceWidth);
				rlpImgTapForDetails.height = (int) (0.144 * Common.sessionDeviceHeight);
				imgTapForDetails.setLayoutParams(rlpImgTapForDetails);
				if(Common.sessionTapProdId.equals(Common.sessionProductId)){
					imgTapForDetails.setVisibility(View.VISIBLE);
					findViewById(R.id.imgvBigImg).setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View arg0) {
							AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);
						//	hideInstruction(arg0);
						}
					});
					imgTapForDetails.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View arg0) {
							AnimationFactory.flipTransition(viewAnimator, FlipDirection.LEFT_RIGHT, 700);			
							//hideInstruction(arg0);
						}
					});
				} else {
					imgTapForDetails.setVisibility(View.INVISIBLE);	
					Common.sessionTapDetailId = "null";	
					Common.sessionTapProdId = "null";
				}
				
			/*	Runnable mRunnable;
				Handler mHandler=new Handler();
				mRunnable=new Runnable() {
		            @Override
		            public void run() {
		                // TODO Auto-generated method stub				            	
		            	 View instructionLayout = findViewById(R.id.include_layout);
		            	 instructionLayout.setVisibility(View.INVISIBLE);
		            }
		        };
				mHandler.postDelayed(mRunnable,5000);*/
		    } catch (Exception e) {
				//Log.i("productlist oncreate exception ", ""+e.getMessage());
				e.printStackTrace();
				String errorMsg = className+" | oncreate   |   " +e.getMessage();
				Common.sendCrashWithAQuery(ProductList.this,errorMsg);
			}
		} catch (Exception e) {
			//Log.i("ProductList error", ""+e.getMessage());
			e.printStackTrace();
			String errorMsg = className+" | oncreate   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		}
	}
	

	  @Override
	  public void onResume() {
		try{
	    super.onResume();
	    if(Common.isAppBackgrnd){
			new Common().storeChangeLogResultFromServer(ProductList.this);			
			Common.isAppBackgrnd = false;
		}
	    } catch(Exception e){
	    	e.printStackTrace();
	    	String errorMsg = className+" | onResume   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
	    }
	  }
	    
	  @Override
	  public void onPause() {
		  try{
			  super.onPause();
			  Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ProductList.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}	

	    } catch(Exception e){
	    	e.printStackTrace();
	    	String errorMsg = className+" | onPause   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
	    } 
	    
	  }
	  
	 
	 
		
	  public void getProductDetailsFromServer(String productUrl){
		  try{
		  final AQuery aq1 = new AQuery(ProductList.this);
		 
		  aq1.ajax(productUrl,  XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try {  
	        			//Log.e("xml",""+xml);
	        		    if(!xml.tags("products").equals(null)){
	        		    	ProductModel  getProdDetail = file.getProduct();
	        		    	 List<XmlDom> entries = xml.tags("products");
	       	    			if(entries.size() > 0){
	       	    				for(XmlDom entry: entries){  		        				
			        				if(getProdDetail.size() == 0){
			        					getProdDetail  = new ProductModel();
			        				}
			        				 String curveImagesUrl = entry.text("pdImage").toString().replaceAll(" ", "%20");
			        				 new Common().DownloadImageFromUrl(ProductList.this, curveImagesUrl, R.id.imgvBigImg);
			        				
			        				 String symbol = new Common().getCurrencySymbol(entry.text("country_languages").toString(), entry.text("country_code_char2").toString());
			        				 String price ="";
			    					txtProdId.setText(entry.text("prodId").toString());					    					
			    					txtProdName.setText(entry.text("prodName").toString());	
			    					if(entry.text("pdPrice").toString().equals("0") || entry.text("pdPrice").toString().equals("0.00")){
			    						price ="Free";
			    						txtProdPrice.setText(price);
			    					}
			    					else if(!entry.text("pdPrice").toString().equals("null") || !entry.text("pdPrice").toString().equals("")){
			    						price =symbol+entry.text("pdPrice").toString();
			    						txtProdPrice.setText(price);
			    					} else{
			    						price ="";
			    						txtProdPrice.setText(price);
			    					}
			    					if(!entry.text("pd_short_description").toString().equals("null") || !entry.text("pd_short_description").toString().equals("")){
			    						txtProdShortDesc.setText(entry.text("pd_short_description"));						
			    					} else {
			    						txtProdShortDesc.setText("");						
			    					}
			    					
			    					
			    					prodIsTryOn = Integer.parseInt(entry.text("pd_istryon").toString());
			    					getProductResultsFromServerWithXml(getProductUrl+Common.sessionProductId+"/");
			    					getAllProductUrl = Constants.Client_Url+Common.sessionClientId+"/all_products";
		       	    				getAllClientProductDetailsFromServer(getAllProductUrl);
		       	    				if(changeFlag){
		       	    					prdArr = entry.text("related_id").toString().split(",");
		       	    					//Log.e("prdArr","len"+prdArr.length);
		       	    					prdArr = Arrays.copyOf(prdArr,prdArr.length+1);
		       	    					//Log.e("prdArr","len"+prdArr.length);
		       	    					int size = prdArr.length-1;
		       	    					prdArr[size]=entry.text("prodId").toString();
		       	    					//Log.e("prdArr","len"+prdArr.length+prdArr);
		       	    					for(int i=0;i<prdArr.length;i++){
		       	    						UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(prdArr[i]));
		       	    						if(chkProdExist != null){
		       	    							getProdDetail.removeItem(chkProdExist);
		       	    						}
			                    			 UserProduct userProduct = new UserProduct();
			                    			 userProduct.setClientId(Integer.parseInt(entry.text("clientId").toString()));
			                    			 userProduct.setClientName(entry.text("name").toString());
			                    			 userProduct.setClientUrl(entry.text("clientUrl").toString());
			                    			 userProduct.setImageFile(curveImagesUrl);
			                    			 userProduct.setProductId(Integer.parseInt(entry.text("prodId").toString()));
			                    			 userProduct.setProductName(entry.text("prodName").toString());
			                    			 userProduct.setProductPrice(price);
			                    			 userProduct.setProductShortDesc(entry.text("pd_short_description").toString());
			                    			 userProduct.setProductUrl(entry.text("productUrl").toString());
			                    			 userProduct.setProdIsTryOn(Integer.parseInt(entry.text("pd_istryon").toString()));
			                    			 userProduct.setClientBackgroundColor(entry.text("background_color").toString());
			                    			 userProduct.setClientLightColor(entry.text("light_color").toString());
			                    			 userProduct.setClientDarkColor(entry.text("dark_color").toString());
			                    			 userProduct.setClientLogo(entry.text("clientLogo").toString());
			                    			 userProduct.setProductRelatedId(entry.text("related_id").toString());
			                    			 userProduct.setOfferRelatedId(entry.text("related_offerid").toString());			                    			 
			                    			 getProdDetail.add(userProduct);
		                    			 
		       	    					}
	       	    				}	       	    			
	                			 if(getProdDetail.size() >0){	                           		
	                           		file.setProduct(getProdDetail);
	                           		getProductResultsFromServerWithXml(getProductUrl+Common.sessionProductId+"/");	                           		
	                           	}                		 
	                			new Common().deleteChangeLogFields("product",Integer.parseInt(Common.sessionProductId));
	       	    				}
	       	    			}							
	        		    }
	        		   }catch(Exception e){
	        			   e.printStackTrace();
	        			   String errorMsg = className+" | getProductDetailsFromServer ajax call back    |   " +e.getMessage();
	        			   Common.sendCrashWithAQuery(ProductList.this,errorMsg);
	        			   
	        		   }
	        		
	        	
	        	}
		  });
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getProductDetailsFromServer    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		  }
	  }
	  public void getAllClientProductDetailsFromServer(String productUrl){
		  try{
		  final AQuery aq1 = new AQuery(ProductList.this);
		 
		  aq1.ajax(productUrl,  XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try {  
	        			//Log.e("xml",""+xml);
	        		    if(!xml.tags("products").equals(null)){
	        		    	ProductModel  getProdDetail = file.getProduct();
	        		    	 List<XmlDom> entries = xml.tags("products");
	        		    	 if(getProdDetail.size() == 0){
		        					getProdDetail  = new ProductModel();
		        				}
	       	    			if(entries.size() > 0){
	       	    				for(XmlDom entry: entries){  			
			        				
			        				 //Log.e("pdId",""+entry.text("prodId").toString());
			        				 String curveImagesUrl = entry.text("pdImage").toString().replaceAll(" ", "%20");
			        				 Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
										if(bitmap == null){
											aq.cache(curveImagesUrl, 1440000);
										}
			        				 String symbol = new Common().getCurrencySymbol(entry.text("country_languages").toString(), entry.text("country_code_char2").toString());
			        				 String price ="";
			    						
			    					if(entry.text("pdPrice").toString().equals("0") || entry.text("pdPrice").toString().equals("0.00")){
			    						price ="Free";
			    						
			    					}
			    					else if(!entry.text("pdPrice").toString().equals("null") || !entry.text("pdPrice").toString().equals("")){
			    						price =symbol+entry.text("pdPrice").toString();			    						
			    					} else{
			    						price ="";			    						
			    					}
			    					
			    					prodIsTryOn = Integer.parseInt(entry.text("pd_istryon").toString());
			        				UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(entry.text("prodId").toString()));
		                			 if(chkProdExist == null){    				 
		                			 
		                				 UserProduct userProduct = new UserProduct();
		                    			 userProduct.setClientId(Integer.parseInt(entry.text("clientId").toString()));
		                    			 userProduct.setClientName(entry.text("name").toString());
		                    			 userProduct.setClientUrl(entry.text("clientUrl").toString());
		                    			 userProduct.setImageFile(curveImagesUrl);
		                    			 userProduct.setProductId(Integer.parseInt(entry.text("prodId").toString()));
		                    			 userProduct.setProductName(entry.text("prodName").toString());
		                    			 userProduct.setProductPrice(price);
		                    			 userProduct.setProductShortDesc(entry.text("pd_short_description").toString());
		                    			 userProduct.setProductUrl(entry.text("productUrl").toString());
		                    			 userProduct.setProdIsTryOn(Integer.parseInt(entry.text("pd_istryon").toString()));
		                    			 userProduct.setClientBackgroundColor(entry.text("background_color").toString());
		                    			 userProduct.setClientLightColor(entry.text("light_color").toString());
		                    			 userProduct.setClientDarkColor(entry.text("dark_color").toString());
		                    			 userProduct.setClientLogo(entry.text("clientLogo").toString());
		                    			 userProduct.setProductRelatedId(entry.text("related_id").toString());
		                    			 getProdDetail.add(userProduct);
	       	    				}
	       	    			
	                			 if(getProdDetail.size() >0){	                           		
	                           		file.setProduct(getProdDetail);
	                           		
	                           	}
	                									
	        		    }
	       	    			}
	        		    }
	        		   }catch(Exception e){
	        			   e.printStackTrace();
	        			   String errorMsg = className+" | getProductDetailsFromServer ajax call back    |   " +e.getMessage();
	        			   Common.sendCrashWithAQuery(ProductList.this,errorMsg);
	        			   
	        		   }
	        		
	        	
	        	}
		  });
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getProductDetailsFromServer    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		  }
	  }
	  
	  
	public void getProductResultsFromServerWithXml(String getProductUrl){
		p=0;
		Log.i("getProductUrl 1", ""+getProductUrl);
		//aq = new AQuery(ProductList.this);
	    aq.ajax(getProductUrl, XmlDom.class, ProductList.this, "productsListXmlResultFromServer");        
	}

	public void productsListXmlResultFromServer(String url, XmlDom xml, AjaxStatus status){
	  	try {  
		    if(!xml.tags("products").equals(null)){
			    final List<XmlDom> products = xml.tags("products");
			   /* ProductModel  productModel= new ProductModel();				
				ProductModel  getProdDetail = file.getProduct();
				if(getProdDetail == null){
					 getProdDetail = new ProductModel();
				}*/
			   
				if(products.size()>0){	    		
				    for(final XmlDom pd : products){			
				    	if(pd.tag("prodId")!=null){
				    		flagForPds++;
							LinearLayout llpLeft1 = (LinearLayout) findViewById(R.id.rlvLeft1); 
							new Common().gradientDrawableCorners(ProductList.this, llpLeft1, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpLeft1 = (RelativeLayout.LayoutParams) llpLeft1.getLayoutParams();
							rlpForLlpLeft1.width = (int) (0.17 * Common.sessionDeviceWidth);
							rlpForLlpLeft1.height = (int) (0.133 * Common.sessionDeviceHeight);
							rlpForLlpLeft1.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
							llpLeft1.setLayoutParams(rlpForLlpLeft1);
							
							ImageView imgLeft1 = (ImageView) findViewById(R.id.imgvLeftFirst);
							RelativeLayout.LayoutParams llpImgLeft1 = (RelativeLayout.LayoutParams) imgLeft1.getLayoutParams();
							llpImgLeft1.width = (int) (0.15 * Common.sessionDeviceWidth);
							llpImgLeft1.height = (int) (0.123 * Common.sessionDeviceHeight);
							imgLeft1.setLayoutParams(llpImgLeft1);
	
							LinearLayout llpLeft2 = (LinearLayout) findViewById(R.id.rlvLeft2); 
							new Common().gradientDrawableCorners(ProductList.this, llpLeft2, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpLeft2 = (RelativeLayout.LayoutParams) llpLeft2.getLayoutParams();
							rlpForLlpLeft2.width = (int) (0.1834 * Common.sessionDeviceWidth);
							rlpForLlpLeft2.height = (int) (0.144 * Common.sessionDeviceHeight);
							rlpForLlpLeft2.leftMargin = (int) (0.0084 * Common.sessionDeviceWidth);
							rlpForLlpLeft2.topMargin = (int) (0.031 * Common.sessionDeviceHeight);
							llpLeft2.setLayoutParams(rlpForLlpLeft2);
	
					    	//Log.i("leftMargin llpLeft2", p+" && "+rlpForLlpLeft2.leftMargin);
							
							ImageView imgLeft2 = (ImageView) findViewById(R.id.imgvLeftSecond);
							RelativeLayout.LayoutParams llpImgLeft2 = (RelativeLayout.LayoutParams) imgLeft2.getLayoutParams();
							llpImgLeft2.width = (int) (0.167 * Common.sessionDeviceWidth);
							llpImgLeft2.height = (int) (0.1332 * Common.sessionDeviceHeight);
							imgLeft2.setLayoutParams(llpImgLeft2);
							
							LinearLayout llpMiddle = (LinearLayout) findViewById(R.id.rlvMiddle); 
							new Common().gradientDrawableCorners(ProductList.this, llpMiddle, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpMiddle = (RelativeLayout.LayoutParams) llpMiddle.getLayoutParams();
							rlpForLlpMiddle.width = (int) (0.25 * Common.sessionDeviceWidth);
							rlpForLlpMiddle.height = (int) (0.195 * Common.sessionDeviceHeight);
							rlpForLlpMiddle.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
							rlpForLlpMiddle.bottomMargin = (int) (0.0871 * Common.sessionDeviceHeight);
							llpMiddle.setLayoutParams(rlpForLlpMiddle);
							
							ImageView imgMiddle = (ImageView) findViewById(R.id.imgvMiddle);
							RelativeLayout.LayoutParams llpImgMiddle = (RelativeLayout.LayoutParams) imgMiddle.getLayoutParams();
							llpImgMiddle.width = (int) (0.2334 * Common.sessionDeviceWidth);
							llpImgMiddle.height = (int) (0.1845 * Common.sessionDeviceHeight);
							imgMiddle.setLayoutParams(llpImgMiddle);
							
							LinearLayout llpRight2 = (LinearLayout) findViewById(R.id.rlvRight2); 
							new Common().gradientDrawableCorners(ProductList.this, llpRight2, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpRight2 = (RelativeLayout.LayoutParams) llpRight2.getLayoutParams();
							rlpForLlpRight2.width = (int) (0.184 * Common.sessionDeviceWidth);
							rlpForLlpRight2.height = (int) (0.144 * Common.sessionDeviceHeight);
							rlpForLlpRight2.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
							llpRight2.setLayoutParams(rlpForLlpRight2);
							
							ImageView imgRight2 = (ImageView) findViewById(R.id.imgvRightSecond);
							RelativeLayout.LayoutParams llpImgRight2 = (RelativeLayout.LayoutParams) imgRight2.getLayoutParams();
							llpImgRight2.width = (int) (0.167 * Common.sessionDeviceWidth);
							llpImgRight2.height = (int) (0.1332 * Common.sessionDeviceHeight);
							imgRight2.setLayoutParams(llpImgRight2);
	
							LinearLayout llpRight1 = (LinearLayout) findViewById(R.id.rlvRight1); 
							new Common().gradientDrawableCorners(ProductList.this, llpRight1, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpRight1 = (RelativeLayout.LayoutParams) llpRight1.getLayoutParams();
							rlpForLlpRight1.width = (int) (0.17 * Common.sessionDeviceWidth);
							rlpForLlpRight1.height = (int) (0.133 * Common.sessionDeviceHeight);
							llpRight1.setLayoutParams(rlpForLlpRight1);
							
							ImageView imgRight1 = (ImageView) findViewById(R.id.imgvRightFirst);
							RelativeLayout.LayoutParams llpImgRight1 = (RelativeLayout.LayoutParams) imgRight1.getLayoutParams();
							llpImgRight1.width = (int) (0.15 * Common.sessionDeviceWidth);
							llpImgRight1.height = (int) (0.123 * Common.sessionDeviceHeight);
							imgRight1.setLayoutParams(llpImgRight1);
							
					    	if(products.size()<=5 && flagForPds!=0){
					    		if(products.size()==5){
									findViewById(R.id.imgvLeftFirst).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.VISIBLE);
									findViewById(R.id.imgvLeftSecond).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvLeft2).setVisibility(View.VISIBLE);	
									findViewById(R.id.imgvMiddle).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvMiddle).setVisibility(View.VISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.VISIBLE);	
									findViewById(R.id.imgvRightFirst).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.VISIBLE);	
					    		}
					    		if(products.size()==0){
									findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvMiddle).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvMiddle).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);	
					    		}
					    		if(products.size()==1){
					    			findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);				    			
					    		}
					    		if(products.size()==2){
					    			findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);				    			
					    		}
					    		if(products.size()==3){
					    			findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);			    			
					    		}
					    		if(products.size()==4){
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);				    			
					    		}
							}
							
					    	if(p==3 && !pd.text("prodId").toString().equals("")){
						    	pdXml1 = pd;
	
								Common.sessionClientBgColor = pdXml1.text("background_color").toString();
								Common.sessionClientBackgroundLightColor = pdXml1.text("light_color").toString();
								Common.sessionClientBackgroundDarkColor = pdXml1.text("dark_color").toString();
								Common.sessionClientLogo = pdXml1.text("clientLogo").toString();
						    	
								new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
										this, Common.sessionClientBgColor,
										Common.sessionClientBackgroundLightColor,
										Common.sessionClientBackgroundDarkColor,
										Common.sessionClientLogo, "", "");
						    	pdid4=","+pdXml1.text("prodId").toString();
								String curveImagesUrl1 = pdXml1.text("pdImage").toString().replaceAll(" ", "%20");
					    		pdIsTryOn1 = Integer.parseInt(pdXml1.text("pd_istryon").toString());
						    	findViewById(R.id.imgvLeftFirst).setVisibility(View.VISIBLE);
								
								Button btnSeeItLiveLeft1 = (Button) findViewById(R.id.btnSeeItLiveLeft1);
								new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveLeft1, "relative", "1", "products_curve", 
										pdXml1.text("prodId").toString(), pdXml1.text("clientId").toString(), pdIsTryOn1, 
										pdXml1.text("background_color").toString(), pdXml1.text("light_color").toString(),
										pdXml1.text("dark_color").toString());
								aq.cache(curveImagesUrl1, 1440000);
								new Common().DownloadImageFromUrl(this, curveImagesUrl1, R.id.imgvLeftFirst);
						    	String curveImagesUrl = pdXml1.text("pdImage").toString().replaceAll(" ", "%20");
	                    		/* UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(pdXml1.text("prodId").toString()));
	                			 if(chkProdExist == null){
	                				 String symbol = new Common().getCurrencySymbol(pdXml1.text("country_languages").toString(), pdXml1.text("country_code_char2").toString());
	                    			 UserProduct userProduct = new UserProduct();
	                    			 userProduct.setClientId(Integer.parseInt(pdXml1.text("clientId").toString()));
	                    			 userProduct.setClientName(pdXml1.text("name").toString());
	                    			 userProduct.setClientUrl(pdXml1.text("clientUrl").toString());
	                    			 userProduct.setImageFile(curveImagesUrl);
	                    			 userProduct.setProductId(Integer.parseInt(pdXml1.text("prodId").toString()));
	                    			 userProduct.setProductName(pdXml1.text("prodName").toString());
	                    			 userProduct.setProductPrice(symbol+pdXml1.text("pdPrice").toString());
	                    			 userProduct.setProductShortDesc(pdXml1.text("pd_short_description").toString());
	                    			 userProduct.setProductUrl(pdXml1.text("productUrl").toString());
	                    			 userProduct.setProdIsTryOn(Integer.parseInt(pdXml1.text("pd_istryon").toString()));
	                    			 userProduct.setClientBackgroundColor(pdXml1.text("background_color").toString());
	                    			 userProduct.setClientLightColor(pdXml1.text("light_color").toString());
	                    			 userProduct.setClientDarkColor(pdXml1.text("dark_color").toString());
	                    			 userProduct.setClientLogo(pdXml1.text("clientLogo").toString());
	                    			 userProduct.setProductRelatedId(pdXml1.text("related_id").toString());
	                    			 productModel.add(userProduct);
	                			 }*/
								
					    	}
					    	if(p==1 && !pd.text("prodId").toString().equals("")){
						    	pdXml2 = pd;
						    
								Common.sessionClientBgColor = pdXml2.text("background_color").toString();
								Common.sessionClientBackgroundLightColor = pdXml2.text("light_color").toString();
								Common.sessionClientBackgroundDarkColor = pdXml2.text("dark_color").toString();
								Common.sessionClientLogo = pdXml2.text("clientLogo").toString();
						    	
								new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
										this, Common.sessionClientBgColor,
										Common.sessionClientBackgroundLightColor,
										Common.sessionClientBackgroundDarkColor,
										Common.sessionClientLogo, "", "");
	
								
								String curveImagesUrl2 = pdXml2.text("pdImage").toString().replaceAll(" ", "%20");
					    		pdIsTryOn2 = Integer.parseInt(pdXml2.text("pd_istryon").toString());
								findViewById(R.id.imgvLeftSecond).setVisibility(View.VISIBLE);
								
								Button btnSeeItLiveLeft2 = (Button) findViewById(R.id.btnSeeItLiveLeft2);
								new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveLeft2, "relative", "2", "products_curve", 
										pdXml2.text("prodId").toString(), pdXml2.text("clientId").toString(), pdIsTryOn2, 
										pdXml2.text("background_color").toString(), pdXml2.text("light_color").toString(),
										pdXml2.text("dark_color").toString());
								aq.cache(curveImagesUrl2, 1440000);
								new Common().DownloadImageFromUrl(this, curveImagesUrl2, R.id.imgvLeftSecond);
								
						    	pdid2=","+pdXml2.text("prodId").toString();
						    	 String curveImagesUrl = pdXml2.text("pdImage").toString().replaceAll(" ", "%20");
	                    		/* UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(pdXml2.text("prodId").toString()));
	                			 if(chkProdExist == null){
	                				 String symbol = new Common().getCurrencySymbol(pdXml2.text("country_languages").toString(), pdXml2.text("country_code_char2").toString());
	                    			 UserProduct userProduct = new UserProduct();
	                    			 userProduct.setClientId(Integer.parseInt(pdXml2.text("clientId").toString()));
	                    			 userProduct.setClientName(pdXml2.text("name").toString());
	                    			 userProduct.setClientUrl(pdXml2.text("prodName").toString());
	                    			 userProduct.setImageFile(curveImagesUrl);
	                    			 userProduct.setProductId(Integer.parseInt(pdXml2.text("prodId").toString()));
	                    			 userProduct.setProductName(pdXml2.text("prodName").toString());
	                    			 userProduct.setProductPrice(symbol+pdXml2.text("pdPrice").toString());
	                    			 userProduct.setProductShortDesc(pdXml2.text("pd_short_description").toString());
	                    			 userProduct.setProductUrl(pdXml2.text("productUrl").toString());
	                    			 userProduct.setProdIsTryOn(Integer.parseInt(pdXml2.text("pd_istryon").toString()));
	                    			 userProduct.setClientBackgroundColor(pdXml2.text("background_color").toString());
	                    			 userProduct.setClientLightColor(pdXml2.text("light_color").toString());
	                    			 userProduct.setClientDarkColor(pdXml2.text("dark_color").toString());
	                    			 userProduct.setClientLogo(pdXml2.text("clientLogo").toString());
	                    			 userProduct.setProductRelatedId(pdXml2.text("related_id").toString());
	                    			 productModel.add(userProduct);
	                			 }*/
								
					    	}
					    	if(p==2 && !pd.text("prodId").toString().equals("")){
						    	pdXml3 = pd;
	
								Common.sessionClientBgColor = pdXml3.text("background_color").toString();
								Common.sessionClientBackgroundLightColor = pdXml3.text("light_color").toString();
								Common.sessionClientBackgroundDarkColor = pdXml3.text("dark_color").toString();
								Common.sessionClientLogo = pdXml3.text("clientLogo").toString();
								
								new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
										this, Common.sessionClientBgColor,
										Common.sessionClientBackgroundLightColor,
										Common.sessionClientBackgroundDarkColor,
										Common.sessionClientLogo, "", "");
						    	pdid1 = pdXml3.text("prodId").toString();
								String curveImagesUrl3 = pdXml3.text("pdImage").toString().replaceAll(" ", "%20");
					    		pdIsTryOn3 = Integer.parseInt(pdXml3.text("pd_istryon").toString());
								findViewById(R.id.imgvMiddle).setVisibility(View.VISIBLE);
								Button btnSeeItLiveMiddle = (Button) findViewById(R.id.btnSeeItLiveMiddle);
								new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveMiddle, "relative", "3", "products_curve", 
										pdXml3.text("prodId").toString(), pdXml3.text("clientId").toString(), pdIsTryOn3, 
										pdXml3.text("background_color").toString(), pdXml3.text("light_color").toString(), 
										pdXml3.text("dark_color").toString());
								aq.cache(curveImagesUrl3, 1440000);
								new Common().DownloadImageFromUrl(this, curveImagesUrl3, R.id.imgvMiddle);
						    	
						    	 String curveImagesUrl = pdXml3.text("pdImage").toString().replaceAll(" ", "%20");
	                    		/* UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(pdXml3.text("prodId").toString()));
	                			 if(chkProdExist == null){
	                				 String symbol = new Common().getCurrencySymbol(pdXml3.text("country_languages").toString(), pdXml3.text("country_code_char2").toString());
	                    			 UserProduct userProduct = new UserProduct();
	                    			 userProduct.setClientId(Integer.parseInt(pdXml3.text("clientId").toString()));
	                    			 userProduct.setClientName(pdXml3.text("name").toString());
	                    			 userProduct.setClientUrl(pdXml3.text("clientUrl").toString());
	                    			 userProduct.setImageFile(curveImagesUrl);
	                    			 userProduct.setProductId(Integer.parseInt(pdXml3.text("prodId").toString()));
	                    			 userProduct.setProductName(pdXml3.text("prodName").toString());
	                    			 userProduct.setProductPrice(symbol+pdXml3.text("pdPrice").toString());
	                    			 userProduct.setProductShortDesc(pdXml3.text("pd_short_description").toString());
	                    			 userProduct.setProductUrl(pdXml3.text("productUrl").toString());
	                    			 userProduct.setProdIsTryOn(Integer.parseInt(pdXml3.text("pd_istryon").toString()));
	                    			 userProduct.setClientBackgroundColor(pdXml3.text("background_color").toString());
	                    			 userProduct.setClientLightColor(pdXml3.text("light_color").toString());
	                    			 userProduct.setClientDarkColor(pdXml3.text("dark_color").toString());
	                    			 userProduct.setClientLogo(pdXml3.text("clientLogo").toString());
	                    			 userProduct.setProductRelatedId(pdXml3.text("related_id").toString());
	                    			 productModel.add(userProduct);
	                			 }*/
					    	}
					    	if(p==0 && !pd.text("prodId").toString().equals("")){
						    	pdXml4 = pd;
	
								Common.sessionClientBgColor = pdXml4.text("background_color").toString();
								Common.sessionClientBackgroundLightColor = pdXml4.text("light_color").toString();
								Common.sessionClientBackgroundDarkColor = pdXml4.text("dark_color").toString();
								Common.sessionClientLogo = pdXml4.text("clientLogo").toString();
						    	
								new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
										this, Common.sessionClientBgColor,
										Common.sessionClientBackgroundLightColor,
										Common.sessionClientBackgroundDarkColor,
										Common.sessionClientLogo, "", "");
						    	
						    	pdid3=","+pdXml4.text("prodId").toString();
								String curveImagesUrl4 = pdXml4.text("pdImage").toString().replaceAll(" ", "%20");
					    		pdIsTryOn4 = Integer.parseInt(pdXml4.text("pd_istryon").toString());
					    		findViewById(R.id.rlvRight2).setVisibility(View.VISIBLE);
								findViewById(R.id.imgvRightSecond).setVisibility(View.VISIBLE);
								
								Button btnSeeItLiveRight2 = (Button) findViewById(R.id.btnSeeItLiveRight2);
								new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveRight2, "relative", "4", "products_curve", 
										pdXml4.text("prodId").toString(), pdXml4.text("clientId").toString(), pdIsTryOn4, 
										pdXml4.text("background_color").toString(), pdXml4.text("light_color").toString(),
										pdXml4.text("dark_color").toString());
								aq.cache(curveImagesUrl4, 1440000);
								new Common().DownloadImageFromUrl(this, curveImagesUrl4, R.id.imgvRightSecond);
						    
						    	String curveImagesUrl = pdXml4.text("pdImage").toString().replaceAll(" ", "%20");
	                    		 /*UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(pdXml4.text("prodId").toString()));
	                			 if(chkProdExist == null){
	                				 String symbol = new Common().getCurrencySymbol(pdXml4.text("country_languages").toString(), pdXml4.text("country_code_char2").toString());
	                    			 UserProduct userProduct = new UserProduct();
	                    			 userProduct.setClientId(Integer.parseInt(pdXml4.text("clientId").toString()));
	                    			 userProduct.setClientName(pdXml4.text("name").toString());
	                    			 userProduct.setClientUrl(pdXml4.text("clientUrl").toString());
	                    			 userProduct.setImageFile(curveImagesUrl);
	                    			 userProduct.setProductId(Integer.parseInt(pdXml4.text("prodId").toString()));
	                    			 userProduct.setProductName(pdXml4.text("prodName").toString());
	                    			 userProduct.setProductPrice(symbol+pdXml4.text("pdPrice").toString());
	                    			 userProduct.setProductShortDesc(pdXml4.text("pd_short_description").toString());
	                    			 userProduct.setProductUrl(pdXml4.text("productUrl").toString());
	                    			 userProduct.setProdIsTryOn(Integer.parseInt(pdXml4.text("pd_istryon").toString()));
	                    			 userProduct.setClientBackgroundColor(pdXml4.text("background_color").toString());
	                    			 userProduct.setClientLightColor(pdXml4.text("light_color").toString());
	                    			 userProduct.setClientDarkColor(pdXml4.text("dark_color").toString());
	                    			 userProduct.setClientLogo(pdXml4.text("clientLogo").toString());
	                    			 userProduct.setProductRelatedId(pdXml4.text("related_id").toString());
	                    			 productModel.add(userProduct);
	                			 }*/
					    	}
					    	if(p==4 && !pd.text("prodId").toString().equals("")){
						    	pdXml5 = pd;
	
								Common.sessionClientBgColor = pdXml5.text("background_color").toString();
								Common.sessionClientBackgroundLightColor = pdXml5.text("light_color").toString();
								Common.sessionClientBackgroundDarkColor = pdXml5.text("dark_color").toString();
								Common.sessionClientLogo = pdXml5.text("clientLogo").toString();
						    	
								new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
										this, Common.sessionClientBgColor,
										Common.sessionClientBackgroundLightColor,
										Common.sessionClientBackgroundDarkColor,
										Common.sessionClientLogo, "", "");
						    	
						    	pdid5 =","+pdXml5.text("prodId").toString();
								String curveImagesUrl5 = pdXml5.text("pdImage").toString().replaceAll(" ", "%20");
					    		pdIsTryOn5 = Integer.parseInt(pdXml5.text("pd_istryon").toString());
					    		findViewById(R.id.rlvRight1).setVisibility(View.VISIBLE);
								findViewById(R.id.imgvRightFirst).setVisibility(View.VISIBLE);
								Button btnSeeItLiveRight1 = (Button) findViewById(R.id.btnSeeItLiveRight1);
								new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveRight1, "relative", "5", "products_curve", 
										pdXml5.text("prodId").toString(), pdXml5.text("clientId").toString(), pdIsTryOn5, 
										pdXml5.text("background_color").toString(), pdXml5.text("light_color").toString(),
										pdXml5.text("dark_color").toString());
								aq.cache(curveImagesUrl5, 1440000);
								new Common().DownloadImageFromUrl(this, curveImagesUrl5, R.id.imgvRightFirst);
						    	
						    	 String curveImagesUrl = pdXml5.text("pdImage").toString().replaceAll(" ", "%20");
	                    		 /*UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(pdXml5.text("prodId").toString()));
	                			 if(chkProdExist == null){
	                				 String symbol = new Common().getCurrencySymbol(pdXml5.text("country_languages").toString(), pdXml5.text("country_code_char2").toString());
	                    			 UserProduct userProduct = new UserProduct();
	                    			 userProduct.setClientId(Integer.parseInt(pdXml5.text("clientId").toString()));
	                    			 userProduct.setClientName(pdXml5.text("name").toString());
	                    			 userProduct.setClientUrl(pdXml5.text("clientUrl").toString());
	                    			 userProduct.setImageFile(curveImagesUrl);
	                    			 userProduct.setProductId(Integer.parseInt(pdXml5.text("prodId").toString()));
	                    			 userProduct.setProductName(pdXml5.text("prodName").toString());
	                    			 userProduct.setProductPrice(symbol+pdXml5.text("pdPrice").toString());
	                    			 userProduct.setProductShortDesc(pdXml5.text("pd_short_description").toString());
	                    			 userProduct.setProductUrl(pdXml5.text("productUrl").toString());
	                    			 userProduct.setProdIsTryOn(Integer.parseInt(pdXml5.text("pd_istryon").toString()));
	                    			 userProduct.setClientBackgroundColor(pdXml5.text("background_color").toString());
	                    			 userProduct.setClientLightColor(pdXml5.text("light_color").toString());
	                    			 userProduct.setClientDarkColor(pdXml5.text("dark_color").toString());
	                    			 userProduct.setClientLogo(pdXml5.text("clientLogo").toString());
	                    			 userProduct.setProductRelatedId(pdXml5.text("related_id").toString());
	                    			 productModel.add(userProduct);
	                			 }*/
								
	                			 
					    	}
					    	
                    		/* UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(pd.text("relatedfrom_id").toString()));
                			 if(chkProdExist != null){                				 
                				 chkProdExist.setProductRelatedId(pdid1+pdid2+pdid3+pdid4+pdid5);
                				 productModel.add(chkProdExist);
                			 }
                			 if(productModel.size() >0){
                          		getProdDetail.mergeWith(productModel);
                          		file.setProduct(getProdDetail);
                          	}
     		    		*/
					    	
							if (android.os.Build.VERSION.SDK_INT > 10) {
								if(p==3 && products.size()>=5){
									viewLeft1 = findViewById(R.id.imgvLeftFirst);
									viewLeft1.setTag(pdXml1.text("prodId").toString());
									viewLeft1.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewLeft1", pdXml1.text("prodId").toString());
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
											//	hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
												String errorMsg = className+" | viewLeft1  click  |   " +je.getMessage();
												Common.sendCrashWithAQuery(ProductList.this,errorMsg);
											}
											return false;
										}
									});
								}
								if(p==1 && products.size()>=3){	
									//Log.i("p 1", ""+p+" "+products.size());
									viewLeft2 = findViewById(R.id.imgvLeftSecond);
									viewLeft2.setTag(pdXml2.text("prodId").toString());
									viewLeft2.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewLeft2", pdXml2.text("prodId").toString());
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
												String errorMsg = className+" | viewLeft2  click  |   " +je.getMessage();
												Common.sendCrashWithAQuery(ProductList.this,errorMsg);
											}
											return false;
										}
									});
								}
								
								if(p==2 && products.size()>=1){
									viewMiddle = findViewById(R.id.imgvMiddle);
									viewMiddle.setTag(pdXml3.text("prodId").toString());
									viewMiddle.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewMiddle", pdXml3.text("prodId").toString());
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
												String errorMsg = className+" | viewMiddle  click  |   " +je.getMessage();
												Common.sendCrashWithAQuery(ProductList.this,errorMsg);
											}
											return false;
										}
									});
								}
								
								if(p==0 && products.size()>=2){
									//Log.i("p", ""+p+" "+products.size());
									viewRight2 = findViewById(R.id.imgvRightSecond);
									viewRight2.setTag(pdXml4.text("prodId").toString());
									viewRight2.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewRight2", pdXml4.text("prodId").toString());
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
												String errorMsg = className+" | viewRight2  click  |   " +je.getMessage();
												Common.sendCrashWithAQuery(ProductList.this,errorMsg);
											}
											return false;
										}
									});
								}
								if(p==4 && products.size()>=4){
									viewRight1 = findViewById(R.id.imgvRightFirst);
									viewRight1.setTag(pdXml5.text("prodId").toString());
									viewRight1.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewRight1", pdXml5.text("prodId").toString());
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
												String errorMsg = className+" | viewRight1  click  |   " +je.getMessage();
												Common.sendCrashWithAQuery(ProductList.this,errorMsg);
											}
											return false;
										}
									});
								}
								
								imgDragedToBigImgLayout = findViewById(R.id.bigImageLayout);
								imgDragedToBigImgLayout.setOnDragListener(new OnDragListener() {					
									@Override
									public boolean onDrag(View self, DragEvent event) {
										if (event.getAction() == DragEvent.ACTION_DRAG_STARTED) {
											
										} else if (event.getAction() == DragEvent.ACTION_DRAG_ENTERED) {
											insideOfMe1 = true;
										} else if (event.getAction() == DragEvent.ACTION_DRAG_EXITED) {
											insideOfMe1 = false;
										} else if (event.getAction() == DragEvent.ACTION_DROP) {
											if (insideOfMe1) {										
												//hideInstruction(self);				
												ClipData.Item item = event.getClipData().getItemAt(0);
			
												ImageView imgTapForDetails = (ImageView)findViewById(R.id.imgvTapForDetails);
												RelativeLayout.LayoutParams rlpImgTapForDetails = (RelativeLayout.LayoutParams) imgTapForDetails.getLayoutParams();
												rlpImgTapForDetails.width = (int) (0.024 * Common.sessionDeviceWidth);
												rlpImgTapForDetails.height = (int) (0.144 * Common.sessionDeviceHeight);
												imgTapForDetails.setLayoutParams(rlpImgTapForDetails);
											
												ImageView getOldImg = (ImageView) findViewById(R.id.imgvBigImg);
												if(!item.getText().equals(bigImgOnTouch.getTag(R.string.bigImgTouchKeyProdId))){
													imgTapForDetails.setVisibility(View.INVISIBLE);
													Common.sessionTapDetailId = "null";	
													Common.sessionTapProdId = "null";
													try{
														if(viewLeft1!=null && item.getText().equals(viewLeft1.getTag()) && products.size()>=4){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvLeft1);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
																
												    			txtProdId.setText(pdXml1.text("prodId").toString());
                                                                 String symbol = new Common().getCurrencySymbol(pdXml1.text("country_languages").toString(), pdXml1.text("country_code_char2").toString());
																txtProdName.setText(pdXml1.text("prodName").toString());
																	if (pdXml1.text("pdPrice").toString().equals("null") || 
																		pdXml1.text("pdPrice").toString().equals("") || 
																		pdXml1.text("pdPrice").toString().equals("0") || 
																		pdXml1.text("pdPrice").toString().equals("0.00") || 
																		pdXml1.text("pdPrice").toString() == null) {
																	txtProdPrice.setText("");
																} else {
																	txtProdPrice.setText(symbol+pdXml1.text("pdPrice").toString());
																}
																if(!pdXml1.text("pd_short_description").toString().equals("null"))
																	txtProdShortDesc.setText(pdXml1.text("pd_short_description").toString());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = Integer.parseInt(pdXml1.text("pd_istryon").toString());
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
																String errorMsg = className+" | productsListXmlResultFromServer viewLeft1    |   " +e.getMessage();
																Common.sendCrashWithAQuery(ProductList.this,errorMsg);
															}
														}
														if(viewLeft2!=null && item.getText()!=null && item.getText().equals(viewLeft2.getTag()) && products.size()>=3){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvLeft2);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    			
												    			}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    				getOldImg.setImageBitmap(bitmap);
	
												    			txtProdId.setText(pdXml2.text("prodId").toString());
                                                                 String symbol = new Common().getCurrencySymbol(pdXml2.text("country_languages").toString(), pdXml2.text("country_code_char2").toString());                            
																txtProdName.setText(pdXml2.text("prodName").toString());
																	if (pdXml2.text("pdPrice").toString().equals("null") || 
																		pdXml2.text("pdPrice").toString().equals("") || 
																		pdXml2.text("pdPrice").toString().equals("0") || 
																		pdXml2.text("pdPrice").toString().equals("0.00") || 
																		pdXml2.text("pdPrice").toString() == null) {
																	txtProdPrice.setText("");
																} else {
																	txtProdPrice.setText(symbol+pdXml2.text("pdPrice").toString());
																}
																if(!pdXml2.text("pd_short_description").toString().equals("null"))
																	txtProdShortDesc.setText(pdXml2.text("pd_short_description").toString());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = Integer.parseInt(pdXml2.text("pd_istryon").toString());
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
																String errorMsg = className+" | productsListXmlResultFromServer viewLeft2    |   " +e.getMessage();
																Common.sendCrashWithAQuery(ProductList.this,errorMsg);
															}
														}
														if(viewMiddle!=null && item.getText()!=null && item.getText().equals(viewMiddle.getTag()) && products.size()>=1){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvMiddle);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    			
												    			}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    			
												    			}
																getOldImg.setImageBitmap(bitmap);
																String symbol = new Common().getCurrencySymbol(pdXml3.text("country_languages").toString(), pdXml3.text("country_code_char2").toString());
																txtProdId.setText(pdXml3.text("prodId").toString());
																txtProdName.setText(pdXml3.text("prodName").toString());
															txtProdName.setText(pdXml3.text("prodName").toString());
																if (pdXml3.text("pdPrice").toString().equals("null") || 
																		pdXml3.text("pdPrice").toString().equals("") || 
																		pdXml3.text("pdPrice").toString().equals("0") || 
																		pdXml3.text("pdPrice").toString().equals("0.00") || 
																		pdXml3.text("pdPrice").toString() == null) {
																	txtProdPrice.setText("");
																} else {
																	txtProdPrice.setText(symbol+pdXml3.text("pdPrice").toString());
																}
																if(!pdXml3.text("pd_short_description").toString().equals("null"))
																	txtProdShortDesc.setText(pdXml3.text("pd_short_description").toString());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = Integer.parseInt(pdXml3.text("pd_istryon").toString());
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
																String errorMsg = className+" | productsListXmlResultFromServer viewMiddle    |   " +e.getMessage();
																Common.sendCrashWithAQuery(ProductList.this,errorMsg);
															}
														}
														if(viewRight2!=null && item.getText()!=null && item.getText().equals(viewRight2.getTag()) && products.size()>=2){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvRight2);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
												    			txtProdId.setText(pdXml4.text("prodId").toString());
																String symbol = new Common().getCurrencySymbol(pdXml4.text("country_languages").toString(), pdXml4.text("country_code_char2").toString());
												    			txtProdName.setText(pdXml4.text("prodName").toString());
															if (pdXml4.text("pdPrice").toString().equals("null") || 
																		pdXml4.text("pdPrice").toString().equals("") || 
																		pdXml4.text("pdPrice").toString().equals("0") || 
																		pdXml4.text("pdPrice").toString().equals("0.00") || 
																		pdXml4.text("pdPrice").toString() == null) {
																	txtProdPrice.setText("");
																} else {
																	txtProdPrice.setText(symbol+pdXml4.text("pdPrice").toString());
																}
																if(!pdXml4.text("pd_short_description").toString().equals("null"))
																	txtProdShortDesc.setText(pdXml4.text("pd_short_description").toString());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = Integer.parseInt(pdXml4.text("pd_istryon").toString());
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
																String errorMsg = className+" | productsListXmlResultFromServer viewRight2    |   " +e.getMessage();
																Common.sendCrashWithAQuery(ProductList.this,errorMsg);
															}
														}
														if(viewRight1!=null && item.getText()!=null && item.getText().equals(viewRight1.getTag()) && products.size()>=4){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvRight1);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
												    			txtProdId.setText(pdXml5.text("prodId").toString());
																String symbol = new Common().getCurrencySymbol(pdXml5.text("country_languages").toString(), pdXml5.text("country_code_char2").toString());
												    			txtProdName.setText(pdXml5.text("prodName").toString());
															if (pdXml5.text("pdPrice").toString().equals("null") || 
																		pdXml5.text("pdPrice").toString().equals("") || 
																		pdXml5.text("pdPrice").toString().equals("0") || 
																		pdXml5.text("pdPrice").toString().equals("0.00") || 
																		pdXml5.text("pdPrice").toString() == null) {
																	txtProdPrice.setText("");
																} else {
																	txtProdPrice.setText(symbol+pdXml5.text("pdPrice").toString());
																}
																if(!pdXml5.text("pd_short_description").toString().equals("null"))
																	txtProdShortDesc.setText(pdXml5.text("pd_short_description").toString());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = Integer.parseInt(pdXml5.text("pd_istryon").toString());
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
																String errorMsg = className+" | productsListXmlResultFromServer viewRight1    |   " +e.getMessage();
																Common.sendCrashWithAQuery(ProductList.this,errorMsg);
															}
														}
														LinearLayout bigImageLayout = (LinearLayout) findViewById(R.id.bigImageLayout);
														new Common().gradientDrawableCorners(ProductList.this, bigImageLayout, null, 0.0334, 0.0167);
													
	
										    			RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) bigImageLayout.getLayoutParams();
										    			rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
										    			rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
										    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
										    			bigImageLayout.setLayoutParams(rlpForLlImg);
										    			
										    			LinearLayout.LayoutParams llParamsForBigImg = (LinearLayout.LayoutParams) getOldImg.getLayoutParams();
										    			llParamsForBigImg.width = (int) (0.72 * Common.sessionDeviceWidth);
										    			llParamsForBigImg.height = (int) (0.46 * Common.sessionDeviceHeight);
										    			getOldImg.setLayoutParams(llParamsForBigImg);
										    			getOldImg.setScaleType(ScaleType.FIT_CENTER);									    			
	
														txtProdId.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
														txtProdName.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
														
														txtProdPrice.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
														RelativeLayout.LayoutParams rlpProdPrice = (RelativeLayout.LayoutParams) txtProdPrice.getLayoutParams();
														rlpProdPrice.width = (int) (0.29 * Common.sessionDeviceWidth);
														txtProdPrice.setLayoutParams(rlpProdPrice);
														txtProdShortDesc.setTextSize((float) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
	
										    			getProductResultsFromServerWithXml(getProductUrl+item.getText());
													} catch (Exception e){
														e.printStackTrace();
														String errorMsg = className+" | productsListXmlResultFromServer    |   " +e.getMessage();
														Common.sendCrashWithAQuery(ProductList.this,errorMsg);
													}
												} else {
													if(!Common.sessionTapProdId.equals("null")){
														imgTapForDetails.setVisibility(View.VISIBLE);												
													} else {
														imgTapForDetails.setVisibility(View.INVISIBLE);
													}
												}
												insideOfMe1 = false;
											}
										}
										return true;
									}
								});
							} else {
								p=0;
							}
					    	p++;
				    	}
				    }
				   /* if(productModel.size() >0){
                		getProdDetail.mergeWith(productModel);
                		file.setProduct(getProdDetail);
                	}*/

			    	
		    	}else{
		    		findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
					findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);	
					findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
					findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
					findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
					findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);	
					findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
					findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
					findViewById(R.id.imgvMiddle).setVisibility(View.INVISIBLE);
					findViewById(R.id.rlvMiddle).setVisibility(View.INVISIBLE);	
		    	}
		    }
		} catch (Exception e) {
			e.printStackTrace();			
			String errorMsg = className+" | productsListXmlResultFromServer    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		} 
	}

	  String [] prdArr=null;
	  UserProduct chkProdExist0,chkProdExist1,chkProdExist2,chkProdExist3,chkProdExist4;
	  public void mainProductsResultFromFile(String prodId){
		  	try {
		  			 aq = new AQuery(ProductList.this);
					 ProductModel  getProdDetail = file.getProduct();
					 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(prodId));
					 if(chkProdExist != null){
						 
						 String curveImagesUrl = chkProdExist.getImageFile();
	    				 new Common().DownloadImageFromUrl(ProductList.this, curveImagesUrl, R.id.imgvBigImg);  
    				 
						txtProdId.setText(""+chkProdExist.getProductId());					    					
						txtProdName.setText(chkProdExist.getProductName());					
						txtProdPrice.setText(chkProdExist.getProductPrice());				
						txtProdShortDesc.setText(chkProdExist.getProductDesc());				
						prodIsTryOn = chkProdExist.getProdIsTryOn();
					 }    				
		  	}catch(Exception e){
		  		e.printStackTrace();
		  	}
	  }
	public void productsListXmlResultFromFile(String prodId){
	  	try {
	  			 aq = new AQuery(ProductList.this);
				 ProductModel  getProdDetail = file.getProduct();
				 UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(prodId));
				
    			 if(chkProdExist != null){
    				 if(chkProdExist.getProductRelatedId() != null){
    					 prdArr = chkProdExist.getProductRelatedId().split(",");
    				 }
    			 }
//    			 Log.e("prdArr.length",""+prdArr.length);
    			 if(prdArr != null){
    			if(prdArr.length>0){	    		
				    	flagForPds++;
							LinearLayout llpLeft1 = (LinearLayout) findViewById(R.id.rlvLeft1); 
							new Common().gradientDrawableCorners(ProductList.this, llpLeft1, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpLeft1 = (RelativeLayout.LayoutParams) llpLeft1.getLayoutParams();
							rlpForLlpLeft1.width = (int) (0.17 * Common.sessionDeviceWidth);
							rlpForLlpLeft1.height = (int) (0.133 * Common.sessionDeviceHeight);
							rlpForLlpLeft1.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
							llpLeft1.setLayoutParams(rlpForLlpLeft1);
							
							ImageView imgLeft1 = (ImageView) findViewById(R.id.imgvLeftFirst);
							RelativeLayout.LayoutParams llpImgLeft1 = (RelativeLayout.LayoutParams) imgLeft1.getLayoutParams();
							llpImgLeft1.width = (int) (0.15 * Common.sessionDeviceWidth);
							llpImgLeft1.height = (int) (0.123 * Common.sessionDeviceHeight);
							imgLeft1.setLayoutParams(llpImgLeft1);
	
							LinearLayout llpLeft2 = (LinearLayout) findViewById(R.id.rlvLeft2); 
							new Common().gradientDrawableCorners(ProductList.this, llpLeft2, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpLeft2 = (RelativeLayout.LayoutParams) llpLeft2.getLayoutParams();
							rlpForLlpLeft2.width = (int) (0.1834 * Common.sessionDeviceWidth);
							rlpForLlpLeft2.height = (int) (0.144 * Common.sessionDeviceHeight);
							rlpForLlpLeft2.leftMargin = (int) (0.0084 * Common.sessionDeviceWidth);
							rlpForLlpLeft2.topMargin = (int) (0.031 * Common.sessionDeviceHeight);
							llpLeft2.setLayoutParams(rlpForLlpLeft2);
	
					    	
							ImageView imgLeft2 = (ImageView) findViewById(R.id.imgvLeftSecond);
							RelativeLayout.LayoutParams llpImgLeft2 = (RelativeLayout.LayoutParams) imgLeft2.getLayoutParams();
							llpImgLeft2.width = (int) (0.167 * Common.sessionDeviceWidth);
							llpImgLeft2.height = (int) (0.1332 * Common.sessionDeviceHeight);
							imgLeft2.setLayoutParams(llpImgLeft2);
							
							LinearLayout llpMiddle = (LinearLayout) findViewById(R.id.rlvMiddle); 
							new Common().gradientDrawableCorners(ProductList.this, llpMiddle, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpMiddle = (RelativeLayout.LayoutParams) llpMiddle.getLayoutParams();
							rlpForLlpMiddle.width = (int) (0.25 * Common.sessionDeviceWidth);
							rlpForLlpMiddle.height = (int) (0.195 * Common.sessionDeviceHeight);
							rlpForLlpMiddle.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
							rlpForLlpMiddle.bottomMargin = (int) (0.0871 * Common.sessionDeviceHeight);
							llpMiddle.setLayoutParams(rlpForLlpMiddle);
							
							ImageView imgMiddle = (ImageView) findViewById(R.id.imgvMiddle);
							RelativeLayout.LayoutParams llpImgMiddle = (RelativeLayout.LayoutParams) imgMiddle.getLayoutParams();
							llpImgMiddle.width = (int) (0.2334 * Common.sessionDeviceWidth);
							llpImgMiddle.height = (int) (0.1845 * Common.sessionDeviceHeight);
							imgMiddle.setLayoutParams(llpImgMiddle);
							
							LinearLayout llpRight2 = (LinearLayout) findViewById(R.id.rlvRight2); 
							new Common().gradientDrawableCorners(ProductList.this, llpRight2, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpRight2 = (RelativeLayout.LayoutParams) llpRight2.getLayoutParams();
							rlpForLlpRight2.width = (int) (0.184 * Common.sessionDeviceWidth);
							rlpForLlpRight2.height = (int) (0.144 * Common.sessionDeviceHeight);
							rlpForLlpRight2.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
							llpRight2.setLayoutParams(rlpForLlpRight2);
							
							ImageView imgRight2 = (ImageView) findViewById(R.id.imgvRightSecond);
							RelativeLayout.LayoutParams llpImgRight2 = (RelativeLayout.LayoutParams) imgRight2.getLayoutParams();
							llpImgRight2.width = (int) (0.167 * Common.sessionDeviceWidth);
							llpImgRight2.height = (int) (0.1332 * Common.sessionDeviceHeight);
							imgRight2.setLayoutParams(llpImgRight2);
	
							LinearLayout llpRight1 = (LinearLayout) findViewById(R.id.rlvRight1); 
							new Common().gradientDrawableCorners(ProductList.this, llpRight1, null, 0.0134, 0.0167);
							RelativeLayout.LayoutParams rlpForLlpRight1 = (RelativeLayout.LayoutParams) llpRight1.getLayoutParams();
							rlpForLlpRight1.width = (int) (0.17 * Common.sessionDeviceWidth);
							rlpForLlpRight1.height = (int) (0.133 * Common.sessionDeviceHeight);
							llpRight1.setLayoutParams(rlpForLlpRight1);
							
							ImageView imgRight1 = (ImageView) findViewById(R.id.imgvRightFirst);
							RelativeLayout.LayoutParams llpImgRight1 = (RelativeLayout.LayoutParams) imgRight1.getLayoutParams();
							llpImgRight1.width = (int) (0.15 * Common.sessionDeviceWidth);
							llpImgRight1.height = (int) (0.123 * Common.sessionDeviceHeight);
							imgRight1.setLayoutParams(llpImgRight1);
							
							
							if(prdArr.length<=5 && flagForPds!=0){
					    		if(prdArr.length==5){
									findViewById(R.id.imgvLeftFirst).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.VISIBLE);
									findViewById(R.id.imgvLeftSecond).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvLeft2).setVisibility(View.VISIBLE);	
									findViewById(R.id.imgvMiddle).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvMiddle).setVisibility(View.VISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.VISIBLE);	
									findViewById(R.id.imgvRightFirst).setVisibility(View.VISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.VISIBLE);	
					    		}
					    		if(prdArr.length==0){
									findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvMiddle).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvMiddle).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);	
					    		}
					    		if(prdArr.length==1){
					    			findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);				    			
					    		}
					    		if(prdArr.length==2){
					    			findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);				    			
					    		}
					    		if(prdArr.length==3){
					    			findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);	
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);			    			
					    		}
					    		if(prdArr.length==4){
									findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
									findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);				    			
					    		}
							}
					    	
							
					    	if(prdArr.length >3 && prdArr[3] != null){
					    		  chkProdExist3 = getProdDetail.getUserProductById(Integer.parseInt(prdArr[3]));							
				    			 if(chkProdExist3 != null){	
									Common.sessionClientBgColor =chkProdExist3.getClientBackgroundColor();
									Common.sessionClientBackgroundLightColor = chkProdExist3.getClientLightColor();
									Common.sessionClientBackgroundDarkColor = chkProdExist3.getClientDarkColor();
									Common.sessionClientLogo = chkProdExist3.getClientLogo();							    	
									new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
											this, Common.sessionClientBgColor,
											Common.sessionClientBackgroundLightColor,
											Common.sessionClientBackgroundDarkColor,
											Common.sessionClientLogo, "", "");
									
									String curveImagesUrl1 = chkProdExist3.getImageFile();
						    		pdIsTryOn1 = chkProdExist3.getProdIsTryOn();
									
									Bitmap bitmap = aq.getCachedImage(curveImagesUrl1);
									if(bitmap != null){
										ImageView imgvLeftFirst=(ImageView) findViewById(R.id.imgvLeftFirst);
										imgvLeftFirst.setImageBitmap(bitmap);
											
										findViewById(R.id.rlvLeft1).setVisibility(View.VISIBLE);
										findViewById(R.id.imgvLeftFirst).setVisibility(View.VISIBLE);
									
									Button btnSeeItLiveLeft1 = (Button) findViewById(R.id.btnSeeItLiveLeft1);
									new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveLeft1, "relative", "1", "products_curve", 
											""+chkProdExist3.getProductId(),""+chkProdExist3.getClientId(), pdIsTryOn1, 
											chkProdExist3.getClientBackgroundColor(), chkProdExist3.getClientLightColor(),
											chkProdExist3.getClientDarkColor());
									}else{
										findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
										
									}
								 }else{
									 	downloadFlag = true;
										findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
										
									}
					    	}
					    	
					    	
					    	if(prdArr.length >1 &&  prdArr[1]  != null){
					    		  chkProdExist1 = getProdDetail.getUserProductById(Integer.parseInt(prdArr[1]));	
				    			 if(chkProdExist1 != null){	
										Common.sessionClientBgColor =chkProdExist1.getClientBackgroundColor();
										Common.sessionClientBackgroundLightColor = chkProdExist1.getClientLightColor();
										Common.sessionClientBackgroundDarkColor = chkProdExist1.getClientDarkColor();
										Common.sessionClientLogo = chkProdExist1.getClientLogo();
								    	
										new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
												this, Common.sessionClientBgColor,
												Common.sessionClientBackgroundLightColor,
												Common.sessionClientBackgroundDarkColor,
												Common.sessionClientLogo, "", "");
										String curveImagesUrl1 = chkProdExist1.getImageFile();
							    		pdIsTryOn1 = chkProdExist1.getProdIsTryOn();										
										Bitmap bitmap = aq.getCachedImage(curveImagesUrl1);										
										if(bitmap !=null){
										
										ImageView imgvLeftSecond=(ImageView) findViewById(R.id.imgvLeftSecond);
										imgvLeftSecond.setImageBitmap(bitmap);
	
										findViewById(R.id.rlvLeft2).setVisibility(View.VISIBLE);
										findViewById(R.id.imgvLeftSecond).setVisibility(View.VISIBLE);	
										
										Button btnSeeItLiveLeft2 = (Button) findViewById(R.id.btnSeeItLiveLeft2);
										new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveLeft2, "relative", "2", "products_curve", 
												""+chkProdExist1.getProductId(),""+chkProdExist1.getClientId(), pdIsTryOn1, 
												chkProdExist1.getClientBackgroundColor(), chkProdExist1.getClientLightColor(),
										chkProdExist1.getClientDarkColor());
									 }else{
											findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
											
										}
								 }else{
									 	downloadFlag = true;
										findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
										
									}								
					    	}
		    			
					    	
					    	if(prdArr.length >0 && prdArr[0] != null){
					    		//Log.e("prdArr[0])","prdArr[0])"+prdArr[0]);
					    		 chkProdExist0 = getProdDetail.getUserProductById(Integer.parseInt(prdArr[0]));						    		 
				    			 if(chkProdExist0 != null){
				    				 
									Common.sessionClientBgColor =chkProdExist0.getClientBackgroundColor();
									Common.sessionClientBackgroundLightColor = chkProdExist0.getClientLightColor();
									Common.sessionClientBackgroundDarkColor = chkProdExist0.getClientDarkColor();
									Common.sessionClientLogo = chkProdExist0.getClientLogo();	
									
									new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
											this, Common.sessionClientBgColor,
											Common.sessionClientBackgroundLightColor,
											Common.sessionClientBackgroundDarkColor,
											Common.sessionClientLogo, "", "");
									
									String curveImagesUrl1 = chkProdExist0.getImageFile();
						    		pdIsTryOn1 = chkProdExist0.getProdIsTryOn();						    	
						    	
									Bitmap bitmap = aq.getCachedImage(curveImagesUrl1);									
									if(bitmap !=null){
										
										ImageView imgvMiddle=(ImageView) findViewById(R.id.imgvMiddle);
										imgvMiddle.setImageBitmap(bitmap);
										
										findViewById(R.id.rlvMiddle).setVisibility(View.VISIBLE);
								    	findViewById(R.id.imgvMiddle).setVisibility(View.VISIBLE);
								    	
										Button btnSeeItLiveMiddle = (Button) findViewById(R.id.btnSeeItLiveMiddle);
										new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveMiddle, "relative", "3", "products_curve", 
												""+chkProdExist0.getProductId(),""+chkProdExist0.getClientId(), pdIsTryOn1, 
												chkProdExist0.getClientBackgroundColor(), chkProdExist0.getClientLightColor(),
												chkProdExist0.getClientDarkColor());
									}
									else{
										findViewById(R.id.rlvMiddle).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvMiddle).setVisibility(View.INVISIBLE);
										
									}
								 }else{
									 	downloadFlag = true;
										findViewById(R.id.rlvMiddle).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvMiddle).setVisibility(View.INVISIBLE);
										
									}
					    	}
					    	
					    	
					    	if(prdArr.length >2 && prdArr[2] != null  ){
					    		 chkProdExist2 = getProdDetail.getUserProductById(Integer.parseInt(prdArr[2]));		
				    			 if(chkProdExist2 != null){
	
									Common.sessionClientBgColor =chkProdExist2.getClientBackgroundColor();
									Common.sessionClientBackgroundLightColor = chkProdExist2.getClientLightColor();
									Common.sessionClientBackgroundDarkColor = chkProdExist2.getClientDarkColor();
									Common.sessionClientLogo = chkProdExist2.getClientLogo();
							    	
									new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
											this, Common.sessionClientBgColor,
											Common.sessionClientBackgroundLightColor,
											Common.sessionClientBackgroundDarkColor,
											Common.sessionClientLogo, "", "");
							    	
									String curveImagesUrl1 = chkProdExist2.getImageFile();
						    		pdIsTryOn1 = chkProdExist2.getProdIsTryOn();	
						    		
									Bitmap bitmap = aq.getCachedImage(curveImagesUrl1);
									//Log.e("bitmap 2",""+bitmap);
									if(bitmap != null){
										
									ImageView imgvRightSecond=(ImageView) findViewById(R.id.imgvRightSecond);
									imgvRightSecond.setImageBitmap(bitmap);	
										
									findViewById(R.id.rlvRight2).setVisibility(View.VISIBLE);
									findViewById(R.id.imgvRightSecond).setVisibility(View.VISIBLE);
									
									Button btnSeeItLiveRight2 = (Button) findViewById(R.id.btnSeeItLiveRight2);
										new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveRight2, "relative", "4", "products_curve", 
											""+chkProdExist2.getProductId(),""+chkProdExist2.getClientId(), pdIsTryOn1, 
											chkProdExist2.getClientBackgroundColor(), chkProdExist2.getClientLightColor(),
										chkProdExist2.getClientDarkColor());
									}
									else{
										findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
										
									}
								 }	else{
									 	downloadFlag = true;
										findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
										
									}							
					    	}
					    	
					   
					    	if(prdArr.length >4 && prdArr[4] != null){
					    		  chkProdExist4 = getProdDetail.getUserProductById(Integer.parseInt(prdArr[4]));							
				    			 if(chkProdExist4 != null){	
										Common.sessionClientBgColor =chkProdExist4.getClientBackgroundColor();
										Common.sessionClientBackgroundLightColor = chkProdExist4.getClientLightColor();
										Common.sessionClientBackgroundDarkColor = chkProdExist4.getClientDarkColor();
										Common.sessionClientLogo = chkProdExist4.getClientLogo();
								    	
										new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
												this, Common.sessionClientBgColor,
												Common.sessionClientBackgroundLightColor,
												Common.sessionClientBackgroundDarkColor,
												Common.sessionClientLogo, "", "");
										
										String curveImagesUrl1 = chkProdExist4.getImageFile();
							    		pdIsTryOn1 = chkProdExist4.getProdIsTryOn();
							    		
							    		Bitmap bitmap = aq.getCachedImage(curveImagesUrl1);						    		
										if(bitmap != null){
											
											ImageView imgvRightFirst=(ImageView) findViewById(R.id.imgvRightFirst);
											imgvRightFirst.setImageBitmap(bitmap);
											
											findViewById(R.id.rlvRight1).setVisibility(View.VISIBLE);
											findViewById(R.id.imgvRightFirst).setVisibility(View.VISIBLE);
											
											Button btnSeeItLiveRight1 = (Button) findViewById(R.id.btnSeeItLiveRight1);
											new Common().btnForSeeItLiveWithAllColors(this, btnSeeItLiveRight1, "relative", "5", "products_curve", 
													""+chkProdExist4.getProductId(),""+chkProdExist4.getClientId(), pdIsTryOn1, 
													chkProdExist4.getClientBackgroundColor(), chkProdExist4.getClientLightColor(),
													chkProdExist4.getClientDarkColor());										
										}else{
											findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
											
										}
								 }else{
									 	downloadFlag = true;
										findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
										
									}								
					    	}
							if (android.os.Build.VERSION.SDK_INT > 10) {
								if(prdArr.length >3 && prdArr[3] != null){
									viewLeft1 = findViewById(R.id.imgvLeftFirst);
									viewLeft1.setTag(prdArr[3]);
									viewLeft1.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewLeft1", prdArr[3]);
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
											//	hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
											}
											return false;
										}
									});
								}
								if(prdArr.length >1 &&  prdArr[1]  != null){	
									viewLeft2 = findViewById(R.id.imgvLeftSecond);
									viewLeft2.setTag(prdArr[1]);
									viewLeft2.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewLeft2", prdArr[1]);
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
											}
											return false;
										}
									});
								}
								
								if(prdArr.length >0 && prdArr[0] != null){
									viewMiddle = findViewById(R.id.imgvMiddle);
									viewMiddle.setTag(prdArr[0]);
									viewMiddle.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewMiddle", prdArr[0]);
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
											}
											return false;
										}
									});
								}
								
								if(prdArr.length >2 && prdArr[2] != null){
									viewRight2 = findViewById(R.id.imgvRightSecond);
									viewRight2.setTag(prdArr[2]);
									viewRight2.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewRight2", prdArr[2]);
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
											}
											return false;
										}
									});
								}
								if(prdArr.length >4 && prdArr[4] != null){
									viewRight1 = findViewById(R.id.imgvRightFirst);
									viewRight1.setTag(prdArr[4]);
									viewRight1.setOnTouchListener(new OnTouchListener() {					
										@Override
										public boolean onTouch(View v, MotionEvent event) {
											try{
												ClipData data = ClipData.newPlainText("viewRight1", prdArr[4]);
												DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
												v.startDrag(data, shadowBuilder, v, 100);					
												//hideInstruction(v);
											} catch(Exception je){
												je.printStackTrace();
											}
											return false;
										}
									});
								}
								
								imgDragedToBigImgLayout = findViewById(R.id.bigImageLayout);								
								imgDragedToBigImgLayout.setOnDragListener(new OnDragListener() {					
									@Override
									public boolean onDrag(View self, DragEvent event) {
										if (event.getAction() == DragEvent.ACTION_DRAG_STARTED) {
											
										} else if (event.getAction() == DragEvent.ACTION_DRAG_ENTERED) {
											insideOfMe1 = true;
										} else if (event.getAction() == DragEvent.ACTION_DRAG_EXITED) {
											insideOfMe1 = false;
										} else if (event.getAction() == DragEvent.ACTION_DROP) {
											if (insideOfMe1) {										
											//	hideInstruction(self);				
												ClipData.Item item = event.getClipData().getItemAt(0);
			
												ImageView imgTapForDetails = (ImageView)findViewById(R.id.imgvTapForDetails);
												RelativeLayout.LayoutParams rlpImgTapForDetails = (RelativeLayout.LayoutParams) imgTapForDetails.getLayoutParams();
												rlpImgTapForDetails.width = (int) (0.024 * Common.sessionDeviceWidth);
												rlpImgTapForDetails.height = (int) (0.144 * Common.sessionDeviceHeight);
												imgTapForDetails.setLayoutParams(rlpImgTapForDetails);
												
												ImageView getOldImg = (ImageView) findViewById(R.id.imgvBigImg);
												if(!item.getText().equals(bigImgOnTouch.getTag(R.string.bigImgTouchKeyProdId))){
													imgTapForDetails.setVisibility(View.INVISIBLE);
													Common.sessionTapDetailId = "null";	
													Common.sessionTapProdId = "null";
													try{
														if(viewLeft1!=null && item.getText().equals(viewLeft1.getTag()) && prdArr.length>=4){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvLeft1);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
																getOldImg.setImageBitmap(bitmap);
																txtProdId.setText(""+chkProdExist3.getProductId());																
																txtProdName.setText(chkProdExist3.getProductName());
																if(!chkProdExist3.getProductPrice().equals("null"))
																	txtProdPrice.setText(chkProdExist3.getProductPrice());
																if(!chkProdExist3.getProductShortDesc().equals("null"))
																	txtProdShortDesc.setText(chkProdExist3.getProductShortDesc());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = chkProdExist3.getProdIsTryOn();
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																// TODO Auto-generated catch block
																e.printStackTrace();
															}
														}
														if(viewLeft2!=null && item.getText()!=null && item.getText().equals(viewLeft2.getTag()) && prdArr.length>=3){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvLeft2);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																//Log.i("bitmap", bitmap.getWidth()+"<="+bitmap.getHeight());
																
												    			//Log.i("default", bigImageLinearLayoutWidth+">="+bigImageLinearLayoutHeight);
												    			if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
												    			txtProdId.setText(""+chkProdExist1.getProductId());
																txtProdName.setText(chkProdExist1.getProductName());
																if(!chkProdExist1.getProductPrice().equals("null"))
																	txtProdPrice.setText(chkProdExist1.getProductPrice());
																if(!chkProdExist1.getProductShortDesc().equals("null"))
																	txtProdShortDesc.setText(chkProdExist1.getProductShortDesc());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = chkProdExist1.getProdIsTryOn();
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																// TODO Auto-generated catch block
																e.printStackTrace();
															}
														}
														if(viewMiddle!=null && item.getText()!=null && item.getText().equals(viewMiddle.getTag()) && prdArr.length>=1){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvMiddle);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																		newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
	
																txtProdId.setText(""+chkProdExist0.getProductId());
																txtProdName.setText(chkProdExist0.getProductName());
																if(!chkProdExist0.getProductPrice().equals("null"))
																	txtProdPrice.setText(chkProdExist0.getProductPrice());
																if(!chkProdExist0.getProductShortDesc().equals("null"))
																	txtProdShortDesc.setText(chkProdExist0.getProductShortDesc());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = chkProdExist0.getProdIsTryOn();
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
															}
														}
														if(viewRight2!=null && item.getText()!=null && item.getText().equals(viewRight2.getTag()) && prdArr.length>=2){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvRight2);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
												    			txtProdId.setText(""+chkProdExist2.getProductId());
																txtProdName.setText(chkProdExist2.getProductName());
																if(!chkProdExist2.getProductPrice().equals("null"))
																	txtProdPrice.setText(chkProdExist2.getProductPrice());
																if(!chkProdExist2.getProductShortDesc().equals("null"))
																	txtProdShortDesc.setText(chkProdExist2.getProductShortDesc());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = chkProdExist2.getProdIsTryOn();
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
															}
														}
														if(viewRight1!=null && item.getText()!=null && item.getText().equals(viewRight1.getTag()) && prdArr.length>=4){
															View view = (View) event.getLocalState();
															ViewGroup owner = (ViewGroup) view.getParent();
															owner.removeView(view);
															RelativeLayout container = (RelativeLayout) findViewById(R.id.llInRlvRight1);
															container.addView(view);
															view.setVisibility(View.VISIBLE);
															try {
																ImageView dropped = (ImageView) view;																		
																BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
																Bitmap bitmap = test.getBitmap();
																if(bitmap.getWidth()<=bitmap.getHeight()){
																	newGeneratedWidth = createNewWidthForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutHeight);
													    		}
												    			else if(bitmap.getWidth()>=bitmap.getHeight()){
																	newGeneratedHeight = createNewHeightForImage(bitmap.getWidth(), bitmap.getHeight(), bigImageLinearLayoutWidth);
													    		}
												    			getOldImg.setImageBitmap(bitmap);
																txtProdId.setText(""+chkProdExist4.getProductId());
																txtProdName.setText(chkProdExist4.getProductName());
																if(!chkProdExist4.getProductPrice().equals("null"))
																	txtProdPrice.setText(chkProdExist4.getProductPrice());
																if(!chkProdExist4.getProductShortDesc().equals("null"))
																	txtProdShortDesc.setText(chkProdExist4.getProductShortDesc());
																bigImgOnTouch.setTag(R.string.bigImgTouchKeyProdId, txtProdId.getText());
																prodIsTryOn = chkProdExist4.getProdIsTryOn();
																if(prodIsTryOn==1){
																	btnSeeItLive.setVisibility(View.VISIBLE);																
																}
																 else {
																	btnSeeItLive.setVisibility(View.INVISIBLE);
																}
															} catch (Exception e) {
																e.printStackTrace();
															}
														}
														LinearLayout bigImageLayout = (LinearLayout) findViewById(R.id.bigImageLayout);
														new Common().gradientDrawableCorners(ProductList.this, bigImageLayout, null, 0.0334, 0.0167);
													
										    			RelativeLayout.LayoutParams rlpForLlImg = (RelativeLayout.LayoutParams) bigImageLayout.getLayoutParams();
										    			rlpForLlImg.width = (int) (0.8 * Common.sessionDeviceWidth);
										    			rlpForLlImg.height = (int) (0.51 * Common.sessionDeviceHeight);
										    			rlpForLlImg.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
										    			bigImageLayout.setLayoutParams(rlpForLlImg);
										    			
										    			LinearLayout.LayoutParams llParamsForBigImg = (LinearLayout.LayoutParams) getOldImg.getLayoutParams();
										    			llParamsForBigImg.width = (int) (0.72 * Common.sessionDeviceWidth);
										    			llParamsForBigImg.height = (int) (0.46 * Common.sessionDeviceHeight);
										    			getOldImg.setLayoutParams(llParamsForBigImg);
										    			getOldImg.setScaleType(ScaleType.FIT_CENTER);									    			
	
														txtProdId.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
														txtProdName.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
														
														txtProdPrice.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
														RelativeLayout.LayoutParams rlpProdPrice = (RelativeLayout.LayoutParams) txtProdPrice.getLayoutParams();
														rlpProdPrice.width = (int) (0.29 * Common.sessionDeviceWidth);
														txtProdPrice.setLayoutParams(rlpProdPrice);
														txtProdShortDesc.setTextSize((float) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
	
										    			productsListXmlResultFromFile(item.getText().toString());
													} catch (Exception e){
														e.printStackTrace();
													}
												} else {
													if(!Common.sessionTapProdId.equals("null")){
														imgTapForDetails.setVisibility(View.VISIBLE);												
													} else {
														imgTapForDetails.setVisibility(View.INVISIBLE);
													}
												}
												insideOfMe1 = false;
											}
										}
										return true;
									}
								});
							} else {
								p=0;
							}
					    	p++;
					    	//Log.e("downloadFlag",""+downloadFlag);
					    if(downloadFlag){
			    				if(Common.isNetworkAvailable(ProductList.this)){
					    			getProductResultsFromServerWithXml(getProductUrl+prodId+"/");
					    		}
		    			}
		    	}else{
		    		if(Common.isNetworkAvailable(ProductList.this)){
		    			getProductResultsFromServerWithXml(getProductUrl+prodId+"/");
		    		}
		    	}
    			 }else{    				 
							findViewById(R.id.imgvLeftFirst).setVisibility(View.INVISIBLE);
							findViewById(R.id.rlvLeft1).setVisibility(View.INVISIBLE);
							findViewById(R.id.imgvLeftSecond).setVisibility(View.INVISIBLE);
							findViewById(R.id.rlvLeft2).setVisibility(View.INVISIBLE);	
							findViewById(R.id.imgvMiddle).setVisibility(View.INVISIBLE);
							findViewById(R.id.rlvMiddle).setVisibility(View.INVISIBLE);	
							findViewById(R.id.imgvRightSecond).setVisibility(View.INVISIBLE);
							findViewById(R.id.rlvRight2).setVisibility(View.INVISIBLE);	
							findViewById(R.id.imgvRightFirst).setVisibility(View.INVISIBLE);
							findViewById(R.id.rlvRight1).setVisibility(View.INVISIBLE);	
			    		
    			 }
		} catch (Exception e) {
			e.printStackTrace();
		} 
	}

	ClipData data;
	@Override
	public boolean onTouch(View v, MotionEvent event) {
		DragShadowBuilder shadowBuilder = new DragShadowBuilder(v);
		switch(event.getAction())
        {
	        case MotionEvent.ACTION_DOWN:
	    		data = ClipData.newPlainText("bigImageTouchPdId", bigImgOnTouch.getTag(R.string.bigImgTouchKeyProdId).toString());		       
	    		v.startDrag(data, shadowBuilder, v, 0);
	    		break;
        }
		
		
		return false;
	}

	@Override
	public boolean onDrag(View self, DragEvent event) {
		try{
		if (event.getAction() == DragEvent.ACTION_DRAG_STARTED) {
		} else if (event.getAction() == DragEvent.ACTION_DRAG_ENTERED) {
			insideOfMe = true;
		} else if (event.getAction() == DragEvent.ACTION_DRAG_EXITED) {
			insideOfMe = false;
		}
		else if (event.getAction() == DragEvent.ACTION_DRAG_ENDED) {
			insideOfMe = false;
		}
		else if (event.getAction() == DragEvent.ACTION_DROP ) {
			if (insideOfMe) {
				ClipData clipData = event.getClipData();
				ClipData.Item item = clipData.getItemAt(0);
				if(item.getText().equals(bigImgOnTouch.getTag(R.string.bigImgTouchKeyProdId))){
					View view = (View) event.getLocalState();
					ViewGroup owner = (ViewGroup) view.getParent();
					owner.removeView(view);
					
					LinearLayout container = (LinearLayout) findViewById(R.id.bigImageLayout);
					new Common().gradientDrawableCorners(ProductList.this, container, null, 0.0334, 0.0167);
					container.addView(view);
					view.setVisibility(View.VISIBLE);
					
					ImageView dropped = (ImageView) view;
					BitmapDrawable test = (BitmapDrawable) dropped.getDrawable();
					Bitmap bitmap = test.getBitmap();
					ByteArrayOutputStream baos = new ByteArrayOutputStream();
					bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
					byte[] b = baos.toByteArray();
				
					if(self.getTag().equals("Cart")){				
						Intent intent = new Intent(ProductList.this, ProductInfo.class);
						intent.putExtra("tapOnImage", false);		
						intent.putExtra("image", b);		
						intent.putExtra("productId", txtProdId.getText());
						intent.putExtra("clientId", Common.sessionClientId);
						startActivityForResult(intent, 1);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						insideOfMe = false;								
					}else if(self.getTag().equals("Closet")){
						ArrayList<String> arrayListCloset = new ArrayList<String>(); 
						if(session.isLoggedIn())
				        {				        
					        String insertClosetUrl = Constants.Closet_Url+"insert/"+Common.sessionIdForUserLoggedIn+"/"+txtProdId.getText()+"/";
							//Log.i("insertClosetUrl", ""+insertClosetUrl);
							new Closet().insertUpdateDeleteProductsToClosetDbUsingXml(insertClosetUrl, "insert");
					        
							Intent intent = new Intent(getApplicationContext(), Closet.class);
							intent.putExtra("userId", ""+Common.sessionIdForUserLoggedIn);
							//intent.putExtra("productId", txtProdId.getText());
							startActivity(intent);
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						}else{
							arrayListCloset.add(txtProdId.getText().toString());
							new Common().getLoginDialog(ProductList.this, Closet.class, "Closet", arrayListCloset);
						}
						
			
					}else if(self.getTag().equals("Share")){		
						Intent intent = new Intent(ProductList.this, ShareActivity.class);
						intent.putExtra("tapOnImage", false);
						intent.putExtra("image", b);		
						intent.putExtra("productId",  txtProdId.getText());
						intent.putExtra("clientId", Common.sessionClientId);						
						startActivityForResult(intent, 1);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}
				}
			}
		}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onDrag  click  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		}
		return true;
	}
	
	public void dragToButtonCart(){
		try{
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
						ImageView bigImage = (ImageView) findViewById(R.id.imgvBigImg);
						BitmapDrawable test = (BitmapDrawable) bigImage.getDrawable();
						Bitmap bitmap = test.getBitmap();
						ByteArrayOutputStream baos = new ByteArrayOutputStream();
						bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
						byte[] b = baos.toByteArray();

						Intent intent = new Intent(ProductList.this, ProductInfo.class);
						intent.putExtra("tapOnImage", false);		
						intent.putExtra("image", b);		
						intent.putExtra("productId", txtProdId.getText());
						intent.putExtra("clientId", Common.sessionClientId);
						//Log.i("prodIsTryOn", ""+prodIsTryOn);
						intent.putExtra("prodIsTryOn", prodIsTryOn);
						startActivity(intent);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);					
					//	hideInstruction(view);
					} catch (Exception e) {
						e.printStackTrace();
						String errorMsg = className+" | dragToButtonCart  imgBtnCart click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductList.this,errorMsg);
					}
				}
			});
		} catch (Exception e) {
			Log.i("dragToButtonCart error", ""+e.getMessage());
			e.printStackTrace();
			String errorMsg = className+" | dragToButtonCart    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		}
	}	
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			// Log.i("Press Back", "BACK PRESSED EVENT");
			onBackPressed();
			isBackPressed = true;
		}

		// Call super code so we dont limit default interaction
		return super.onKeyDown(keyCode, event);
	}

	@Override
	public void onBackPressed() {
		try{
		super.onBackPressed();
		new Common().deleteFiles(Constants.Products_Location);
		//Intent returnIntent = new Intent(this, ARDisplayActivity.class);
		//returnIntent.putExtra("instruction_type", "0");
		//returnIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
		//setResult(1, returnIntent);
		//finish();
		//overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		//new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, ARDisplayActivity.class,"0");
		finish();
		overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
        return;
        
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onBackPressed    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		}
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		try{
			if(resultCode==1){
				getInstruction(Integer.parseInt(data.getStringExtra("instruction_type")));
				/*Runnable mRunnable;
				Handler mHandler=new Handler();
	
				mRunnable=new Runnable() {
		            @Override
		            public void run() {
		                // TODO Auto-generated method stub				            	
		            	 View instructionLayout = findViewById(R.id.include_layout);
		            	 instructionLayout.setVisibility(View.INVISIBLE);
		            }
		        };
				mHandler.postDelayed(mRunnable,5000);*/
			}
	    	
		}
		catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult    |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		}
	}

	String title = null;
	String message;
	String imageName;
	public void getInstruction(int type){
	 View instructionLayout = findViewById(R.id.include_layout);
       	 instructionLayout.setVisibility(View.VISIBLE);
       	 
       	/*	 	RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
		RelativeLayout.LayoutParams rlInstrLayout = (RelativeLayout.LayoutParams) rlInstruction.getLayoutParams();
		rlInstrLayout.height = (int) (0.291 * Common.sessionDeviceHeight);
		rlInstrLayout.topMargin = (int) (0.103 * Common.sessionDeviceHeight);
		rlInstruction.setLayoutParams(rlInstrLayout);
		rlInstruction.setVisibility(View.VISIBLE);
		
		TextView instruction = (TextView)findViewById(R.id.instruction);
		RelativeLayout.LayoutParams rlInstrMsg = (RelativeLayout.LayoutParams) instruction.getLayoutParams();
		rlInstrMsg.leftMargin = (int) (0.119 * Common.sessionDeviceWidth);
		rlInstrMsg.bottomMargin = (int) (0.0861 * Common.sessionDeviceHeight);
		rlInstrMsg.width = (int) (0.67 * Common.sessionDeviceWidth);
		rlInstrMsg.height = (int) (0.082 * Common.sessionDeviceHeight);
		instruction.setLayoutParams(rlInstrMsg);
		instruction.setTextSize((float) ((0.0334 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
		

		TextView label = (TextView)findViewById(R.id.label);
		label.setTextSize((float) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));

		ImageView imgClose = (ImageView)findViewById(R.id.closeinc);
		RelativeLayout.LayoutParams rlInstrClose = (RelativeLayout.LayoutParams) imgClose.getLayoutParams();
		rlInstrClose.width = (int) (0.084 * Common.sessionDeviceWidth);
		rlInstrClose.height = (int) (0.052 * Common.sessionDeviceHeight);
		rlInstrClose.bottomMargin = (int) (0.021 * Common.sessionDeviceHeight);
		//rlInstrClose.leftMargin = (int) (0.012 * Common.sessionDeviceWidth);
		imgClose.setLayoutParams(rlInstrClose);

		/*ImageView imgIcon= (ImageView)findViewById(R.id.icon);
		RelativeLayout.LayoutParams rlInstrIcon = (RelativeLayout.LayoutParams) imgIcon.getLayoutParams();
		rlInstrIcon.width = (int) (0.134 * Common.sessionDeviceWidth);
		rlInstrIcon.height = (int) (0.082 * Common.sessionDeviceHeight);
		rlInstrIcon.topMargin = (int) (0.0154 * Common.sessionDeviceWidth);
		imgIcon.setLayoutParams(rlInstrIcon);
		rlInstruction.setLayoutParams(rlInstrLayout);
		rlInstruction.setVisibility(View.VISIBLE);*/
		switch (type) {
	       case 1:	          
	   			new Common().instructionBox(ProductList.this,R.string.title_case1,R.string.instruction_case1);	   		
	           break;	           
	       case 2:
	    	   new Common().instructionBox(ProductList.this,R.string.title_case2,R.string.instruction_case2);
	           break;	           
	       case 3:
	    	   new Common().instructionBox(ProductList.this,R.string.title_case3,R.string.instruction_case3);
	           break;	           
	       case 4:
	    	   new Common().instructionBox(ProductList.this,R.string.title_case4,R.string.instruction_case4);
	           break;	           
	       case 5:	         
	    	   new Common().instructionBox(ProductList.this,R.string.title_case5,R.string.instruction_case5);	   			
	           break;	           
	       case 6:	          
	    	   new Common().instructionBox(ProductList.this,R.string.title_case6,R.string.instruction_case6);
	           break;
	       case 7:	         
	    	   new Common().instructionBox(ProductList.this,R.string.title_case7,R.string.instruction_case7);
	           break;
	   }
	 }

	public void hideInstruction(View v){
		RelativeLayout rlInstruction = (RelativeLayout)v.findViewById(R.id.rlInstruction);
		rlInstruction.setVisibility(View.GONE);
	}
	public void checkProductExistinChangeLog(String prodId){
	  	try{ 
	  		   //function 2 to check whether product exist in change log
	  			Log.i("trigger checkProductExistinChangeLog","trigger checkProductExistinChangeLog");	 					
				/*ChangeLogModel changeLogModelData = file.getChangeLog();		
    			final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
    			if(userChangeLogList.size() > 0){
    	  			Log.e("userChangeLogList",""+userChangeLogList.size());    	 			
	    			for(UserChangeLog userChangeLog : userChangeLogList){	    				
		    			if(userChangeLog.getProdtId() == Integer.parseInt(prodId)){
		    				changeFlag = true;		    				
		    				String productUrl = Constants.Client_Url+Common.sessionClientId+"/products/"+prodId;
		    				getProductDetailsFromServer(productUrl);			
		    				}    			 
		    			}	    			
	    			}*/
	  			
	  			changeFlag = new Common().checkOfferExistinChangeLog("product",prodId);
    			if(!changeFlag){
    				mainProductsResultFromFile(Common.sessionProductId);
    				productsListXmlResultFromFile(Common.sessionProductId);
    			}else{
    				String productUrl = Constants.Client_Url+Common.sessionClientId+"/products/"+prodId;
    				getProductDetailsFromServer(productUrl);
    			}
	    			
    		}catch(Exception e){
    			e.printStackTrace();
    			String errorMsg = className+" | changeLogResultFromServer |   " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ProductList.this,errorMsg);
    		}    	
	}
	
	public int createNewWidthForImage(int originalWidth, int originalHeight, int newHeight){
		//original width / original height x new height = new width
		double widthByHeight = (double) originalWidth/originalHeight;
		double newWidth = roundTwoDecimals(widthByHeight) * newHeight;
		return (int) newWidth;
	}
	
	public int createNewHeightForImage(int originalWidth, int originalHeight, int newWidth){
		//original height / original width x new width = new height
		double heightByWidth = (double) originalHeight/originalWidth;
		double newHeight = roundTwoDecimals(heightByWidth) * newWidth;
		return (int) newHeight;
	}
	double roundTwoDecimals(double d)
	{
	    DecimalFormat twoDForm = new DecimalFormat("#.##");
	    return Double.valueOf(twoDForm.format(d));
	}
	 @Override
	public void onStart() {
		 try{
		 super.onStart();	   
	    Tracker easyTracker = EasyTracker.getInstance(this);
		easyTracker.set(Fields.SCREEN_NAME, "/product/"+Common.sessionClientId+"/"+Common.sessionProductId);
		easyTracker.send(MapBuilder
		    .createAppView()
		    .build()
		);
		 String[] segments = new String[1];
		 segments[0] = "Shopping Page"; 
		 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart    |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
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
			 Common.sendCrashWithAQuery(ProductList.this,errorMsg);
		}
	}

	public List<Bitmap> imagesList;   						
	public ArrayList<String> imagesUrlArrList;
	public void getTapForDetailResultsFromServerWithXml(String getProductUrl){
		TextView prdName = (TextView)findViewById(R.id.txtHeadTitle);
		TextView prdDesc = (TextView)findViewById(R.id.txtPdDesc);
		TextView prdPrice = (TextView)findViewById(R.id.txtPdPrice);
		
		prdName.setText(Common.sessionProductName);
		prdName.setTextSize((float) ((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
		prdPrice.setText(Common.sessionProductPrice);	
		prdPrice.setTextSize((float) ((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
		if(!Common.sessionProductShortDesc.equals("null")){
			prdDesc.setText(Common.sessionProductShortDesc);	
		}
		prdDesc.setTextSize((float) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
		
		ImageView imgvBtnBack1 = (ImageView) findViewById(R.id.imgvBtnBack1);
		imgvBtnBack1.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				viewAnimator.showPrevious();
			}
		});
		ImageView imgvBtnCart1 = (ImageView) findViewById(R.id.imgvBtnCart1); 
		imgvBtnCart1.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(ProductList.this, ProductInfo.class);
				intent.putExtra("tapOnImage", false);			
				intent.putExtra("productId", txtProdId.getText());
				intent.putExtra("clientId", Common.sessionClientId);
				intent.putExtra("prodIsTryOn", prodIsTryOn);
				startActivityForResult(intent, 1);
				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
			}
		});
		
		int bgColor = Color.parseColor("#"+Common.sessionClientBackgroundLightColor);
		int bgColor1 = Color.parseColor("#"+Common.sessionClientBackgroundDarkColor);
		int colors[] = { bgColor, bgColor1 };
		//float ratioColors[] = { 0, 0.4f, 0.6f };

		GradientDrawable gradientDrawable = new GradientDrawable(GradientDrawable.Orientation.TOP_BOTTOM, colors);		
		imgvBtnBack1.setBackgroundDrawable(gradientDrawable);		
		imgvBtnCart1.setBackgroundDrawable(gradientDrawable);
		
		RelativeLayout rlForHeaderMiddle = (RelativeLayout) findViewById(R.id.rlForHeaderMiddle1);
        RelativeLayout.LayoutParams rlpHeaderPanel = (RelativeLayout.LayoutParams) rlForHeaderMiddle.getLayoutParams();
        rlpHeaderPanel.height = (int) (0.082 * Common.sessionDeviceHeight);
        rlForHeaderMiddle.setLayoutParams(rlpHeaderPanel);
        rlForHeaderMiddle.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
        
        aq = new AQuery(ProductList.this);
	    aq.ajax(getProductUrl, XmlDom.class, ProductList.this, "TapForDetailXmlResultFromServer");        
	}
	Bitmap convertToBitmap;
	public void TapForDetailXmlResultFromServer(String url, XmlDom xml, AjaxStatus status){
	  	try {
		      
		    if(!xml.tags("products").equals(null)){
			    final List<XmlDom> products = xml.tags("products");
		    	if(products.size()>0){	   
	    			imagesList = new ArrayList<Bitmap>();
	    			imagesUrlArrList = new ArrayList<String>(); 		
				    for(XmlDom pdXml : products){
				    	List<XmlDom> productsInner = pdXml.tags("tapForDetailsImgs"); 
					    for(XmlDom pdXmlInner : productsInner){
					    	try {	
							    String imageurl = pdXmlInner.text("images").toString().replaceAll(" ", "%20");
							    imagesUrlArrList.add(imageurl);
							    convertToBitmap = aq.getCachedImage(imageurl);   
							     if(convertToBitmap == null) {
				    				URL url1 = new URL(imageurl);   
				    				convertToBitmap = BitmapFactory.decodeStream(url1.openStream());
				                    aq.cache(imageurl, 14400000);
				                }
							    imagesList.add(convertToBitmap);			    		
					    	} catch (Exception e) {
								e.printStackTrace();
							} 
					    }
				    }
					FancyCoverFlow fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlow1);
			        RelativeLayout.LayoutParams rlpForFancyCover = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();
			        rlpForFancyCover.width = LayoutParams.MATCH_PARENT;
			        rlpForFancyCover.height = (int) (0.414 * Common.sessionDeviceHeight);
			        rlpForFancyCover.topMargin = (int) (0.01 * Common.sessionDeviceHeight);
			        fancyCoverFlow.setLayoutParams(rlpForFancyCover);		        
			        fancyCoverFlow.setAdapter(renderForCoverFlow(imagesUrlArrList));
			        fancyCoverFlow.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
		    	} else {
					/*TextView txtProdNotAvail = (TextView) findViewById(R.id.txtvProdNotAvail);
					txtProdNotAvail.setVisibility(View.VISIBLE);
					txtProdNotAvail.setTextSize((float)((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));*/
				}
		    }
	  	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | TapForDetailXmlResultFromServer  click  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductList.this,errorMsg);
	  	}
	}	

	int gridItemLayout = 0;
	private ArrayAdapter<String> renderForCoverFlow(final ArrayList<String> imagesUrlArrList2) {
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.coverflowitem_bybrands;
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, imagesUrlArrList2){				
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, gridItemLayout, parent);
					}			
					//Log.i("position", ""+position);
					String photo = getItem(position);	
					//Log.i("photo", ""+photo);
					AQuery aq2 = new AQuery(convertView);		
					String tbUrl = photo;					
					Bitmap placeholder = aq2.getCachedImage(tbUrl);
					if(placeholder==null){
						aq2.cache(tbUrl, 14400000);					
					}
					LinearLayout coverflowLlayout = (LinearLayout) convertView.findViewById(R.id.coverflowLlayout);
					RelativeLayout.LayoutParams rlpForLl = (RelativeLayout.LayoutParams) coverflowLlayout.getLayoutParams();
					rlpForLl.width = (int) (0.58 * Common.sessionDeviceWidth);
					rlpForLl.height = (int) (0.34 * Common.sessionDeviceHeight);
					coverflowLlayout.setLayoutParams(rlpForLl);
					
					ImageView img =(ImageView) convertView.findViewById(R.id.coverFlowImage);					
					aq2.id(R.id.coverFlowImage).image(tbUrl, true, true, 0, 0, placeholder, 0, 0);
					LinearLayout.LayoutParams llForCoverFlowImg = (LinearLayout.LayoutParams) img.getLayoutParams();
					llForCoverFlowImg.width = (int) (0.53 * Common.sessionDeviceWidth);
					llForCoverFlowImg.height = (int) (0.29 * Common.sessionDeviceHeight);
					img.setLayoutParams(llForCoverFlowImg);
								
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForCoverFlow  getView  |   " +e.getMessage();
					Common.sendCrashWithAQuery(ProductList.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		return aa;
	}
	
	
}