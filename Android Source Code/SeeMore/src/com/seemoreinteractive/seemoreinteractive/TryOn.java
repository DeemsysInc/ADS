package com.seemoreinteractive.seemoreinteractive;

import java.io.File;
import java.util.ArrayList;
import java.util.List;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.PixelFormat;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.hardware.Camera;
import android.hardware.Camera.PictureCallback;
import android.hardware.Camera.ShutterCallback;
import android.hardware.Camera.Size;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore.MediaColumns;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.view.Window;
import android.view.WindowManager;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.metaio.sdk.MetaioDebug;
import com.metaio.tools.io.AssetsManager;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.Image;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.multitouch.PhotoSortrView;

@TargetApi(Build.VERSION_CODES.HONEYCOMB)
public class TryOn extends Activity {

	boolean previewing = false;
	LayoutInflater controlInflater = null;
	int currentCameraId = 0;
    int numberOfCamera = 0;
    ListView lvForRightVerticalImages;
    AQuery aq;
    RelativeLayout rlForRightVerticalScroll;
    ImageView imgvCameraCapture;
    Context mContext = this;
    ImageView imgvMultiImagesOnScreen = null, imgvShowSelectedPhoto = null;
    String className = this.getClass().getSimpleName();
    public static int captureImageRotate = 90;
    private static final int SELECT_PHOTO = 1;
    private static int BROWSE_FROM_GALLERY_FLAG = 0;
    String pdUrl="";
    private boolean safeToTakePicture = false;
    private SurfaceView preview=null;
    private SurfaceHolder previewHolder=null;
    private Camera camera=null;
    private boolean inPreview=false;
    private boolean cameraConfigured=false;
	String checkClientId = "0";
	String checkProdId = "0";
	LinearLayout llForChangeCamera, llForChangeItems, llForSelectPhoto;
	ImageView imgvForChangeCamera;
	TextView txtvChange, txtvSelectPhoto;
    String getFinalWebSiteUrl = "null",getProdName;
	PhotoSortrView photoSorter;
	ProgressBar progressBar;
	FileTransaction file;
	long startnow,endnow;
	@SuppressLint("NewApi")
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		getWindow().setFormat(PixelFormat.TRANSLUCENT);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
        WindowManager.LayoutParams.FLAG_FULLSCREEN);
		setContentView(R.layout.activity_try_on);
		try{
			
			llForChangeCamera 			= (LinearLayout) findViewById(R.id.llForChangeCamera);
			imgvForChangeCamera 		= (ImageView) findViewById(R.id.imgvForChangeCamera);
	        lvForRightVerticalImages 	= (ListView) findViewById(R.id.lvForRightVerScr);
			imgvCameraCapture 			= (ImageView) findViewById(R.id.imgvCameraCapture);
			RelativeLayout rlTryOnImage = (RelativeLayout) findViewById(R.id.rlTryOnImage);
            imgvShowSelectedPhoto 		= (ImageView) findViewById(R.id.imgvShowSelectedPhoto);
			llForChangeItems 			= (LinearLayout) findViewById(R.id.llForChangeItems);
			txtvChange 					= (TextView) findViewById(R.id.txtvChange);
			llForSelectPhoto 			= (LinearLayout) findViewById(R.id.llForSelectPhoto);
			txtvSelectPhoto				= (TextView) findViewById(R.id.txtvSelectPhoto);	
			progressBar 				= (ProgressBar)findViewById(R.id.progressBar); 

			aq = new AQuery(this);
			file = new FileTransaction();
			
			
			RelativeLayout.LayoutParams rlpForRlTryOnImage = (RelativeLayout.LayoutParams) rlTryOnImage.getLayoutParams();
			rlpForRlTryOnImage.topMargin = (int) (0.082 * Common.sessionDeviceHeight);
			rlpForRlTryOnImage.bottomMargin = (int) (0.082 * Common.sessionDeviceHeight);
			rlTryOnImage.setLayoutParams(rlpForRlTryOnImage);			
			
			lvForRightVerticalImages.setVisibility(View.VISIBLE);
			imgvCameraCapture.setVisibility(View.INVISIBLE);
			Intent getIntVals = getIntent();
	        Log.i("checkProdId", ""+getIntVals.getStringExtra("productId"));
	        Log.i("checkClientId", ""+getIntVals.getStringExtra("clientId"));
	        if(getIntVals.getStringExtra("clientId")!=null){
	        	checkClientId = getIntVals.getStringExtra("clientId").toString();
	        	checkProdId = getIntVals.getStringExtra("productId").toString();
		        Log.i("checkClientId", ""+checkClientId);
	        } else {
	        	checkClientId = Common.sessionClientId;
	        	checkProdId = Common.sessionProductId;
	        }
	        Common.sessionClientId = checkClientId;

			/*new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					"null", "TryOn", "");*/
	        
	        new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this,Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					"null", "TryOn","");
	        
	      

			ImageView imgClientLogo = (ImageView) findViewById(R.id.imgvHeadTitle);
			TextView txtHeadTitle = (TextView) findViewById(R.id.txtvHeadTitle);
			imgClientLogo.setImageResource(R.drawable.shopparvision_white);
			imgClientLogo.setVisibility(View.VISIBLE);
			txtHeadTitle.setVisibility(View.GONE);
			RelativeLayout.LayoutParams rlpImgClientLogo = (RelativeLayout.LayoutParams) imgClientLogo.getLayoutParams();
			rlpImgClientLogo.height = (int) (0.056 * Common.sessionDeviceHeight);
			imgClientLogo.setLayoutParams(rlpImgClientLogo);
			
	        pdUrl = Constants.Client_Url+checkClientId+"/related_products/"+checkProdId+"/tryon/";
	        Log.i("pdUrl", ""+pdUrl);
	        if(Common.isNetworkAvailable(TryOn.this)){
	        	getVerticalImgPdResultsFromServerWithXml(pdUrl);			  
	        }else{
	        	getVerticalImgPdResultsFromFile(checkProdId);
	        }
			
			String screenName = "/shoppervision/"+checkClientId+"/"+checkProdId;
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);	
	    	
	        RelativeLayout.LayoutParams rlForRightVerScr = (RelativeLayout.LayoutParams) lvForRightVerticalImages.getLayoutParams();
	        rlForRightVerScr.width = (int) (0.5 * Common.sessionDeviceWidth);
	        rlForRightVerScr.height = LayoutParams.WRAP_CONTENT;
	        lvForRightVerticalImages.setLayoutParams(rlForRightVerScr);

            //imgvShowSelectedPhoto.setImageBitmap(null);
			Log.i("BROWSE_FROM_GALLERY_FLAG 1", ""+BROWSE_FROM_GALLERY_FLAG);
            if(BROWSE_FROM_GALLERY_FLAG==0){
				Log.i("BROWSE_FROM_GALLERY_FLAG if", ""+BROWSE_FROM_GALLERY_FLAG);
            	imgvShowSelectedPhoto.setImageBitmap(null);
            }
	        
			RelativeLayout.LayoutParams rlForImgCameraCapture = (RelativeLayout.LayoutParams) imgvCameraCapture.getLayoutParams();
			rlForImgCameraCapture.width = (int) (0.0884 * Common.sessionDeviceWidth);
			rlForImgCameraCapture.height = (int) (0.039 * Common.sessionDeviceHeight);
			rlForImgCameraCapture.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			imgvCameraCapture.setLayoutParams(rlForImgCameraCapture);			
			imgvCameraCapture.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					
					try{
						lvForRightVerticalImages.setVisibility(View.INVISIBLE);
						
						//View view = findViewById(R.id.photoSortrView1);
						photoSorter = (PhotoSortrView)findViewById(R.id.photoSortrView1);
						photoSorter.setDrawingCacheEnabled(true);
						Bitmap bitmap = Bitmap.createBitmap(photoSorter.getDrawingCache());
						Image.setCharacterBitmap(bitmap);		
						//bitmap.recycle();
						photoSorter.setDrawingCacheEnabled(false);
						
						Log.i("BROWSE_FROM_GALLERY_FLAG", ""+BROWSE_FROM_GALLERY_FLAG);
						if(BROWSE_FROM_GALLERY_FLAG==1){
							BROWSE_FROM_GALLERY_FLAG = 0;
							try{
								Drawable drawable2 = imgvShowSelectedPhoto.getDrawable();
								Bitmap bmpForGalleryImg = ((BitmapDrawable) drawable2).getBitmap();
								Image.setCameraBitmap(bmpForGalleryImg);
								
				    			/*Bitmap bmpSetCombined = mergeImages(Image.getCameraBitmap(), Image.getCharacterBitmap());
				    			Image.setCombinedBitmap(bmpSetCombined);
	                               */
						     	Intent intent = new Intent(TryOn.this, ShareTryOn.class);
				    			intent.putExtra("tryOn",  "true");	
				    			intent.putExtra("productId", checkProdId);
				    			intent.putExtra("clientId", checkClientId);	
				    			intent.putExtra("imageName", imgvMultiImagesOnScreen.getTag(R.string.try_on_img_name).toString());	
				    			intent.putExtra("imagePrice", imgvMultiImagesOnScreen.getTag(R.string.try_on_img_price).toString());	
				    			intent.putExtra("flag", "gallery");
				    			//intent.putExtra("imageDesc", "");	
				                startActivityForResult(intent, 1);
				    			overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
							}catch(Exception e){
								e.printStackTrace();
							}
						} else {
							try{
								if (safeToTakePicture) {
									if(camera == null){
										camera=Camera.open(currentCameraId);
									    startPreview();
									}
								    camera.takePicture(myShutterCallback, myPictureCallback_RAW, myPictureCallback_JPG); 
								    safeToTakePicture = false;								
								}
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | OnCreate  imgvCameraCapture click  |   " +e.getMessage();
								Common.sendCrashWithAQuery(TryOn.this,errorMsg);
							}
						}
					} catch (Exception e){
						e.printStackTrace();
						String errorMsg = className+" | OnCreate  imgvCameraCapture click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
				}
			});

			Image.setCameraBitmap(null);
			Image.setCharacterBitmap(null);
			Image.setCombinedBitmap(null);
			Image.setLegalBitmap(null);
			//new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_2, R.id.imgvBtnCart);
			
			ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
	        imgvBtnCloset.setImageAlpha(0);
	        ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			imgvBtnShare.setImageAlpha(0);
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
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: TryOn imgFooterMiddle.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | OnCreate  imgFooterMiddle click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
	            }
		    });
	    	ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);        
	        imgBackButton.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						System.gc();
						finish();
					} catch (Exception e) {
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: TryOn imgBackButton.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | OnCreate  imgBackButton click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
				}
			});
	        ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
						if(Common.isNetworkAvailable(TryOn.this))
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
									Intent intent = new Intent(TryOn.this, PurchaseProductWithClientUrl.class);
									intent.putExtra("productName",getProdName);
									intent.putExtra("finalWebSiteUrl", getFinalWebSiteUrl);
									startActivityForResult(intent, 1);
									overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);		
								}
							}
						}else{
							 new Common().instructionBox(TryOn.this,R.string.title_case7,R.string.instruction_case7);
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | OnCreate  imgBtnCart click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
				}
			});
			
	        getWindow().setFormat(PixelFormat.UNKNOWN);
	        preview=(SurfaceView)findViewById(R.id.surfaceviewForCameraView);
	        previewHolder=preview.getHolder();
	        previewHolder.addCallback(surfaceCallback);
	        previewHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
			
			File externalStorage = Environment.getExternalStorageDirectory();
			File file = new File(externalStorage.getAbsolutePath() + "/" + "tempImgName");
			File tempImage = new File(file.getAbsolutePath());
			if (tempImage.isFile()) {
				tempImage.delete();
			}
		
			RelativeLayout.LayoutParams rlForLlImgChangeCamera = (RelativeLayout.LayoutParams) llForChangeCamera.getLayoutParams();
			rlForLlImgChangeCamera.width = (int) (0.225 * Common.sessionDeviceWidth);
			rlForLlImgChangeCamera.height = (int) (0.0441 * Common.sessionDeviceHeight);
			rlForLlImgChangeCamera.leftMargin = (int) (0.0167 * Common.sessionDeviceWidth);
			rlForLlImgChangeCamera.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			llForChangeCamera.setLayoutParams(rlForLlImgChangeCamera);
			llForChangeCamera.setBackgroundResource(R.drawable.round_corners);
			llForChangeCamera.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
			
			
			LinearLayout.LayoutParams rlForImgChangeCamera = (LinearLayout.LayoutParams) imgvForChangeCamera.getLayoutParams();
			//rlForImgChangeCamera.width = (int) ((0.0934 * Common.sessionDeviceWidth));
			//rlForImgChangeCamera.height = (int) ((0.041 * Common.sessionDeviceHeight));
			rlForImgChangeCamera.width = LayoutParams.WRAP_CONTENT;
			rlForImgChangeCamera.height = LayoutParams.WRAP_CONTENT;
			imgvForChangeCamera.setLayoutParams(rlForImgChangeCamera);
			
			llForChangeCamera.setOnClickListener(new OnClickListener() {
				@SuppressLint("NewApi")
				@Override
				public void onClick(View v) {
					try{
						Log.i("check camera", ""+Camera.CameraInfo.CAMERA_FACING_BACK);
						Log.i("check camera", ""+Camera.CameraInfo.CAMERA_FACING_FRONT);
						//swap the id of the camera to be used
				        numberOfCamera = Camera.getNumberOfCameras();
					     if(numberOfCamera==2){
							if(currentCameraId == Camera.CameraInfo.CAMERA_FACING_BACK){
								Log.i("numberOfCamera if", ""+numberOfCamera);								
							    currentCameraId = Camera.CameraInfo.CAMERA_FACING_FRONT;
							    captureImageRotate = 270;
							}
							else {
								Log.i("numberOfCamera else", ""+numberOfCamera);	
							    currentCameraId = Camera.CameraInfo.CAMERA_FACING_BACK;
							    captureImageRotate = 90;
							}
			                camera.release();
			                camera = Camera.open(currentCameraId);
							camera.setPreviewDisplay(previewHolder);
							camera.setDisplayOrientation(90);
							camera.startPreview();
							previewing = true;
				        }
					} catch (Exception e){
						e.printStackTrace();
						String errorMsg = className+" | OnCreate  llForChangeCamera click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
				}
			});
			
			RelativeLayout.LayoutParams rlForLlChangeItems = (RelativeLayout.LayoutParams) llForChangeItems.getLayoutParams();
			rlForLlChangeItems.width = (int) ((0.292 * Common.sessionDeviceWidth));
			rlForLlChangeItems.height = (int) ((0.0441 * Common.sessionDeviceHeight));
			rlForLlChangeItems.leftMargin = (int) (0.0134 * Common.sessionDeviceWidth);
			llForChangeItems.setLayoutParams(rlForLlChangeItems);
			llForChangeItems.setBackgroundResource(R.drawable.round_corners);
			llForChangeItems.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
			
			txtvChange.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			llForChangeItems.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						lvForRightVerticalImages.setVisibility(View.VISIBLE);
						imgvCameraCapture.setVisibility(View.INVISIBLE);
	
						llForChangeCamera.setVisibility(View.INVISIBLE);
						llForChangeItems.setVisibility(View.INVISIBLE);
						llForSelectPhoto.setVisibility(View.INVISIBLE);
				     	getVerticalImgPdResultsFromServerWithXml(pdUrl);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | OnCreate  llForChangeItems click  |   " +e.getMessage();
						 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
				}
			});
		
			RelativeLayout.LayoutParams rlForLlSelectPhoto = (RelativeLayout.LayoutParams) llForSelectPhoto.getLayoutParams();
			rlForLlSelectPhoto.width = (int) ((0.425 * Common.sessionDeviceWidth));
			Log.i("rlForLlSelectPhoto.width", ""+rlForLlSelectPhoto.width);
			rlForLlSelectPhoto.height = (int) ((0.0441 * Common.sessionDeviceHeight));
			Log.i("rlForLlSelectPhoto.height", ""+rlForLlSelectPhoto.height);
			rlForLlSelectPhoto.leftMargin = (int) (0.0134 * Common.sessionDeviceWidth);
			llForSelectPhoto.setLayoutParams(rlForLlSelectPhoto);
			llForSelectPhoto.setBackgroundResource(R.drawable.round_corners);
			llForSelectPhoto.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
				
			txtvSelectPhoto.setTextSize((float) ((0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
			llForSelectPhoto.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						Intent photoPickerIntent = new Intent(Intent.ACTION_PICK);
						photoPickerIntent.setType("image/*");
						startActivityForResult(photoPickerIntent, SELECT_PHOTO);
					}catch(Exception e){
						e.printStackTrace();
						 String errorMsg = className+" | OnCreate  llForSelectPhoto click  |   " +e.getMessage();
						 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
					}
				}
			});

			llForChangeCamera.setVisibility(View.INVISIBLE);
			llForChangeItems.setVisibility(View.INVISIBLE);
			llForSelectPhoto.setVisibility(View.INVISIBLE);
		} catch (Exception e){
			e.printStackTrace();
			 String errorMsg = className+" | OnCreate   |   " +e.getMessage();
			 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
		}
	}
	  private void getVerticalImgPdResultsFromFile(String checkProdId2) {
		  try{
			progressBar.setVisibility(View.INVISIBLE);
			FileTransaction file = new FileTransaction();  
			ProductModel productmodel = file.getProduct();
			UserProduct productDetails = productmodel.getUserProductById(Integer.parseInt(checkProdId2));
			if(productDetails != null){
				arrForClientId = new ArrayList<String>();
				arrForPdId = new ArrayList<String>();
				arrForPdImage = new ArrayList<String>();
				arrForPdName = new ArrayList<String>();   	
				arrForPdPrice = new ArrayList<String>();   		
				arrForClBgColor = new ArrayList<String>();  
				arrForClBgLightColor = new ArrayList<String>();  
				arrForClBgDarkColor = new ArrayList<String>();   
				arrForClLogo = new ArrayList<String>();   
				arrForClName = new ArrayList<String>();  
				arrForClUrl = new ArrayList<String>(); 
				if(productDetails.getProductRelatedId() != null){
					UserProduct chkProdMainExist = productmodel.getUserProductById(Integer.parseInt(checkProdId));
       			 	if(chkProdMainExist != null){
	       			 	arrForClientId.add(""+chkProdMainExist.getClientId());
						arrForPdId.add(""+chkProdMainExist.getProductId());
						arrForPdImage.add(chkProdMainExist.getImageFile());
						arrForPdName.add(chkProdMainExist.getProductName());
						arrForClBgColor.add(chkProdMainExist.getClientBackgroundColor());
						arrForClBgLightColor.add(chkProdMainExist.getClientLightColor());
						arrForClBgDarkColor.add(chkProdMainExist.getClientDarkColor());
						if (chkProdMainExist.getProductPrice().equals("null") || 
								chkProdMainExist.getProductPrice().equals("") || 
								chkProdMainExist.getProductPrice().equals("0") || 
								chkProdMainExist.getProductPrice().equals("0.00") || 
								chkProdMainExist.getProductPrice() == null) {
							arrForPdPrice.add("");
						} else {
							arrForPdPrice.add(chkProdMainExist.getProductPrice());
						}
						arrForClLogo.add(chkProdMainExist.getClientLogo());
						arrForClName.add(chkProdMainExist.getClientName());
						arrForClUrl.add(chkProdMainExist.getClientUrl());
       			 	}
					List<UserProduct> relProds = productmodel.getRelatedProduct(productDetails.getProductRelatedId());
					for(UserProduct userprod :relProds){
						if(userprod.getProductId() !=0){
							if(!arrForPdId.contains(userprod.getProductId())){
								arrForClientId.add(""+userprod.getClientId());
								arrForPdId.add(""+userprod.getProductId());
								arrForPdImage.add(""+userprod.getImageFile());
								arrForPdName.add(""+userprod.getProductName());
								arrForClBgColor.add(""+userprod.getClientBackgroundColor());
								arrForClBgLightColor.add(userprod.getClientLightColor());
								arrForClBgDarkColor.add(userprod.getClientDarkColor());
								if (userprod.getProductPrice().equals("null") || 
										userprod.getProductPrice().equals("") || 
										userprod.getProductPrice().equals("0") || 
										userprod.getProductPrice().equals("0.00") || 
										userprod.getProductPrice() == null) {
									arrForPdPrice.add("");
								} else {
									arrForPdPrice.add(userprod.getProductPrice());
								}
								
								arrForClLogo.add(userprod.getClientLogo());
								arrForClName.add(userprod.getClientName());
							}
							
						}
					}
					Common.sessionClientBgColor = arrForClBgColor.get(0).toString();
					Common.sessionClientBackgroundLightColor = arrForClBgLightColor.get(0).toString();
					Common.sessionClientBackgroundDarkColor = arrForClBgDarkColor.get(0).toString();
				}
			    if(arrForPdId.size()>0){
			        lvForRightVerticalImages.setAdapter(renderForTryOnVerticalScroll(arrForPdId, arrForPdImage, 
			        		arrForPdName, arrForPdPrice, arrForClientId, arrForClLogo, arrForClName));
			        lvForRightVerticalImages.setOnItemClickListener(new OnItemClickListener() {
						@Override
						public void onItemClick(
								AdapterView<?> arg0, View v, int position, long arg3) {
							try{
								Common.sessionClientBgColor = arrForClBgColor.get(position).toString();
								Common.sessionClientBackgroundLightColor = arrForClBgLightColor.get(position).toString();
								Common.sessionClientBackgroundDarkColor = arrForClBgDarkColor.get(position).toString();
								
								Bitmap placeholder = aq.getCachedImage(arrForPdImage.get(position));
								//Log.e("arrForPdImage.get(position)",arrForPdImage.get(position));
								//Log.e("placeholder",""+placeholder);
								if(placeholder==null){
									aq.cache(arrForPdImage.get(position), 14400000);					
								}
								imgvMultiImagesOnScreen = new ImageView(TryOn.this);
								imgvMultiImagesOnScreen.setId(position);
								Log.i("ScaleType.MATRIX", ""+ScaleType.MATRIX);
								imgvMultiImagesOnScreen.setImageBitmap(placeholder);
						        photoSorter = (PhotoSortrView)findViewById(R.id.photoSortrView1);
					            photoSorter.loadImages1(TryOn.this, imgvMultiImagesOnScreen.getDrawable());  
																														
								lvForRightVerticalImages.setVisibility(View.INVISIBLE);
								imgvCameraCapture.setVisibility(View.VISIBLE);	

								llForChangeCamera.setVisibility(View.VISIBLE);
								llForChangeItems.setVisibility(View.VISIBLE);
								llForSelectPhoto.setVisibility(View.VISIBLE);
								llForSelectPhoto.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
								llForChangeItems.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
								llForChangeCamera.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));

								// Set this class as touchListener to the ImageView
								imgvMultiImagesOnScreen.setTag(R.string.try_on_img_pd_id, arrForPdId.get(position));
								imgvMultiImagesOnScreen.setTag(R.string.try_on_img_cl_id, arrForClientId.get(position));
								imgvMultiImagesOnScreen.setTag(R.string.try_on_img_name, arrForPdName.get(position));
								imgvMultiImagesOnScreen.setTag(R.string.try_on_img_price, arrForPdPrice.get(position));
								
							} catch(Exception e){
								e.printStackTrace();
							}									
						}
					});
			    }
			}
			 
		  }catch(Exception e){
			  e.printStackTrace();
		  }
	}
	@Override
	  public void onResume() {
	    super.onResume();
	    
	    try{
	    	if(!previewing){
		    camera=Camera.open(currentCameraId);
		    startPreview();
	    	}
	    	  
	    	if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(TryOn.this);			
				Common.isAppBackgrnd = false;
			}
	    } catch(Exception e){
	    	e.printStackTrace();
	    }
	  }
	    
	  @Override
	  public void onPause() {
		  try{
		    if (inPreview) {
		      camera.stopPreview();
		    }
		    

		    if(camera!=null){
		    	camera.release();
		    }
		    camera=null;
		    inPreview=false;

		    
		    Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(TryOn.this);
			if(appInBackgrnd){
				 Common.isAppBackgrnd = true;
			}
	    } catch(Exception e){
	    	e.printStackTrace();
	    } 
	    super.onPause();
	  }
	  
	  private void initPreview(int width, int height) {
		try {

			if (camera != null && previewHolder.getSurface() != null) {
				try {
					camera.setPreviewDisplay(previewHolder);
					camera.setDisplayOrientation(90);
				} catch (Throwable t) {
					Log.i("PreviewDemo-surfaceCallback",
							"Exception in setPreviewDisplay()", t);
					/*Toast.makeText(TryOn.this, t.getMessage(),
							Toast.LENGTH_LONG).show();*/
				}

				if (!cameraConfigured) {
					Camera.Parameters parameters = camera.getParameters();
					/*camera.setParameters(parameters);
					cameraConfigured = true;
					List<Size> sizes = parameters.getSupportedPreviewSizes();
					Log.i("optimalSize", "" +sizes);
					Log.i("optimalSize", "" + width + " " + height);
					parameters.setPreviewSize(width, height);
					parameters.setPictureSize(width, height);*/
					List<Size> sizes = parameters.getSupportedPreviewSizes();
					Log.i("optimalSize", "" + width + " " + height);
					Size size = getOptimalPreviewSize(sizes, width, height);
					parameters.setPreviewSize(size.width, size.height);
					parameters.setPictureSize(size.width, size.height);

					if (size != null) {
						parameters.setPreviewSize(size.width, size.height);
						//camera.setParameters(parameters);
						cameraConfigured = true;
					}
				}
			}
		} catch (Exception e) {
			e.printStackTrace();
			 String errorMsg = className+" |  initPreview    |   " +e.getMessage();
			 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
		}
	}
	  
	  private void startPreview() {
		  try{
	    if (cameraConfigured && camera!=null) {
	      camera.startPreview();
	      inPreview=true;
	      safeToTakePicture = true;
	      
	    }
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" |  startPreview    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(TryOn.this,errorMsg);
		  }
	  }
	  
	  SurfaceHolder.Callback surfaceCallback=new SurfaceHolder.Callback() {
	    @Override
		public void surfaceCreated(SurfaceHolder holder) {
	     }
	    
	    @Override
		public void surfaceChanged(SurfaceHolder holder,
	                               int format, int width,
	                               int height) {
	      initPreview(width, height);
	      startPreview();
	    }
	    
	    @Override
		public void surfaceDestroyed(SurfaceHolder holder) {
	   
	    }
	  };
	Bitmap bmpSelectedImg;
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent imageReturnedIntent) { 
	    super.onActivityResult(requestCode, resultCode, imageReturnedIntent);
	    try{
	    	Log.i("resultCode", ""+resultCode);
		   if (requestCode == SELECT_PHOTO && resultCode == RESULT_OK && null != imageReturnedIntent) {
		    	try{
					Log.i("BROWSE_FROM_GALLERY_FLAG 3", ""+BROWSE_FROM_GALLERY_FLAG);
		            Uri selectedImage = imageReturnedIntent.getData();
		            String[] filePathColumn = { MediaColumns.DATA };
		 
		            Cursor cursor = getContentResolver().query(selectedImage,
		                    filePathColumn, null, null, null);
		            cursor.moveToFirst();
		 
		            int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
		            String picturePath = cursor.getString(columnIndex);
		            cursor.close();            
		            
		            bmpSelectedImg = BitmapFactory.decodeFile(picturePath);
		            imgvShowSelectedPhoto.setImageBitmap(bmpSelectedImg);
		            BROWSE_FROM_GALLERY_FLAG = 1;
		    	}catch(Exception e){
					e.printStackTrace();
				}
	        } else {
	        	try{
					BROWSE_FROM_GALLERY_FLAG = 0;
		        	
		        	getWindow().setFormat(PixelFormat.UNKNOWN);
			        preview=(SurfaceView)findViewById(R.id.surfaceviewForCameraView);
			        previewHolder=preview.getHolder();
			        previewHolder.addCallback(surfaceCallback);
			        previewHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
	                //camera.release();
	                camera = Camera.open(currentCameraId);
					camera.setPreviewDisplay(previewHolder);
					camera.setDisplayOrientation(90);
					camera.startPreview();
					previewing = true;
	        	}catch(Exception e){
					e.printStackTrace();
				}
	        }
		   if(resultCode == 1){
			   BROWSE_FROM_GALLERY_FLAG =0;			   
			   imgvShowSelectedPhoto.setImageBitmap(null);
			   imgvShowSelectedPhoto.destroyDrawingCache();
			   imgvShowSelectedPhoto.invalidate();
			   photoSorter = (PhotoSortrView)findViewById(R.id.photoSortrView1);
			   photoSorter.unloadImages();
			   imgvMultiImagesOnScreen = null;
			   lvForRightVerticalImages.setVisibility(View.VISIBLE);
			   imgvCameraCapture.setVisibility(View.INVISIBLE);
			   llForChangeCamera.setVisibility(View.INVISIBLE);
			   llForChangeItems.setVisibility(View.INVISIBLE);
			   llForSelectPhoto.setVisibility(View.INVISIBLE);
			   
		   }
		   
	    	
	    } catch(OutOfMemoryError e) {
       	 	e.printStackTrace();
       	 String errorMsg = className+" |  onActivityResult OutOfMemoryError    |   " +e.getMessage();
		 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
        } catch(Exception e){
	    	e.printStackTrace();
	    	 String errorMsg = className+" |  onActivityResult    |   " +e.getMessage();
			 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
	    }
	}
	ShutterCallback myShutterCallback = new ShutterCallback(){
	    @Override
		public void onShutter() {
	    }};

	PictureCallback myPictureCallback_RAW = new PictureCallback(){
	    @Override
		public void onPictureTaken(byte[] arg0, Camera arg1) {
	        
	    }};
	    
	    
	    public static Bitmap getResizedBitmap(Bitmap image, int newHeight, int newWidth) {
	    	 Bitmap resizedBitmap = null;
	    	try{
	        int width = image.getWidth();
	        int height = image.getHeight();
	        float scaleWidth = ((float) newWidth) / width;
	        float scaleHeight = ((float) newHeight) / height;
	        Matrix matrix = new Matrix();
	        matrix.postScale(scaleWidth, scaleHeight);
	        resizedBitmap = Bitmap.createBitmap(image, 0, 0, width, height,
	                matrix, false);
	    	}catch(Exception e){
	    		e.printStackTrace();
	    	}
	        return resizedBitmap;	    	
	    }  
    Bitmap finalBitmap = null;
    public static final int MEDIA_TYPE_IMAGE = 1;
	PictureCallback myPictureCallback_JPG = new PictureCallback(){
	    @Override
		public void onPictureTaken(byte[] data, Camera camera) {	        
	         try {
	        	 
	        	System.gc();
	        	/*BitmapFactory.Options opt = new BitmapFactory.Options();
	        	opt.inDensity = 300;
	        	opt.inTargetDensity = 300;*/
	        	/*BitmapFactory.Options options=new BitmapFactory.Options();
	        	options.inDither=false;                     //Disable Dithering mode
	        	options.inPurgeable=true;                   //Tell to gc that whether it needs free memory, the Bitmap can be cleared
	        	options.inInputShareable=true;              //Which kind of reference will be used to recover the Bitmap data after being clear, when it will be used in the future
	        	options.inTempStorage=new byte[32 * 1024]; 
	        	*/
	        	    BitmapFactory.Options options=new BitmapFactory.Options();
	        	    options.inJustDecodeBounds = true;
	        	    
	        	    Bitmap captureBmpImg1 = BitmapFactory.decodeByteArray(data, 0, data.length,options);

	        	    // Calculate inSampleSize
	        	    options.inSampleSize = calculateInSampleSize(options, Common.sessionDeviceHeight,Common.sessionDeviceWidth);

	        	    // Decode bitmap with inSampleSize set
	        	    options.inJustDecodeBounds = false;
	        	    captureBmpImg1 = BitmapFactory.decodeByteArray(data, 0, data.length,options);
	        	
	        	
	        	
        	 	
        	 	// startPreview();
        	 	if(captureBmpImg1 != null){        
        	 		
		        	 	 Bitmap captureBmpImg = getResizedBitmap(captureBmpImg1,Common.sessionDeviceHeight,Common.sessionDeviceWidth);
		       	 		 Matrix mat = new Matrix();
		       	 	//Bitmap captureBmpImg = 	Bitmap.createBitmap(captureBmpImg1, 0, 0, captureBmpImg1.getWidth(), captureBmpImg1.getHeight(), mat, true);
		       	 	
		       	     mat.postRotate(captureImageRotate);
		           	 //Log.e("captureBmpImg.getHeight()", ""+captureBmpImg.getHeight()+" "+captureBmpImg.getWidth());
		
		            	
		        	    Bitmap rotatedBMP = Bitmap.createBitmap(captureBmpImg, 0, 0, captureBmpImg.getWidth(), captureBmpImg.getHeight(), mat, true);  
		            	Log.e("rotatedBMP.getHeight()", ""+rotatedBMP.getHeight()+" "+rotatedBMP.getWidth());
		    			Image.setCameraBitmap(rotatedBMP);
		       	     
		       	     
		           	if(captureBmpImg1 != null){
			            	captureBmpImg1.recycle();
			            	captureBmpImg1 = null;
			            	System.gc();
		           	}
		           	
		           	
		   			
		   			/*Bitmap bmpSetCombined = mergeImages(Image.getCameraBitmap(), Image.getCharacterBitmap());
		   			Image.setCombinedBitmap(bmpSetCombined);*/
		   			
		   			/*if(captureBmpImg != null){
		   				captureBmpImg.recycle();
		   				captureBmpImg = null;
			            	System.gc();
		           	}*/
		   			safeToTakePicture = true;  			
		       	 	Intent intent = new Intent(TryOn.this, ShareTryOn.class);
		   			intent.putExtra("tryOn",  "true");	
		   			intent.putExtra("productId", checkProdId);
		   			intent.putExtra("clientId", checkClientId);	
		   			intent.putExtra("imageName", imgvMultiImagesOnScreen.getTag(R.string.try_on_img_name).toString());	
		   			intent.putExtra("imagePrice", imgvMultiImagesOnScreen.getTag(R.string.try_on_img_price).toString());
		   			intent.putExtra("flag", "camera");
		   			//intent.putExtra("imageDesc", "");	
		   			//finish();
		   			
		               startActivityForResult(intent, 1);
		   			overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);	
        	 	}
	         }
	         catch(OutOfMemoryError e) {
	             //fail
	        	 e.printStackTrace();
	        	 String errorMsg = className+" |  PictureCallback OutOfMemoryError    |   " +e.getMessage();
				 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
	         } catch(Exception e){
	        	 e.printStackTrace();
	        	 String errorMsg = className+" |  PictureCallback    |   " +e.getMessage();
				 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
	         }
	    }};    

	    public Bitmap mergeImages(Bitmap bottomImage, Bitmap topImage) {
	    	Bitmap bmOverlay = null ;
	    	try{
		        final Canvas canvas = new Canvas(bottomImage);	
		    	Bitmap scaledBorder = Bitmap.createScaledBitmap(topImage, bottomImage.getWidth(), (bottomImage.getHeight()-(int)(0.082*Common.sessionDeviceHeight)/Common.sessionDeviceDensity), false);
		    	canvas.drawBitmap(scaledBorder, 0, 0, null);
		        
		    	
		    	
		    	
		    	
		    	if(scaledBorder!=null){
		    		scaledBorder.recycle();
		    		scaledBorder = null;	    		
		    		System.gc();
		    		topImage.recycle();
		    		topImage = null;
		    	}
	    	
	    		/*
	    	
	    		bmOverlay = Bitmap.createBitmap(Common.sessionDeviceWidth, (bottomImage.getHeight()-(int)(0.081*Common.sessionDeviceHeight)/Common.sessionDeviceDensity), bottomImage.getConfig());
	    	//	Bitmap scaledBorder = Bitmap.createScaledBitmap(topImage, bottomImage.getWidth(), (bottomImage.getHeight()-(int)(0.081*Common.sessionDeviceHeight)/Common.sessionDeviceDensity), false);
	    		int imageWidth =  bottomImage.getWidth();
		         int imageHeight =  bottomImage.getHeight();	
		        int newWidth = imageWidth;
		        int newHeight = imageHeight;

	             if(imageWidth > topImage.getWidth()){
	            	newWidth  = topImage.getWidth();
	            	newHeight =  newWidth * imageHeight/imageWidth;
	             }
	             if(newHeight > topImage.getHeight()){
	            	newHeight = topImage.getHeight();
	            	newWidth  = newHeight * imageWidth/imageHeight;					            	
	             }
   
		         Bitmap resizedBitmap = Bitmap.createScaledBitmap(topImage, newWidth, (bottomImage.getHeight()-(int)(0.081*Common.sessionDeviceHeight)/Common.sessionDeviceDensity), false);
		    	
				Canvas canvas = new Canvas(bottomImage);
				//canvas.drawBitmap(bottomImage, new Matrix(), null);
				canvas.drawBitmap(resizedBitmap,0,0, null);
	    	
	    	
	    	*/
	    	
	    	
	    	} catch(OutOfMemoryError oe){
	    		oe.printStackTrace();
	    		String errorMsg = className+" |  mergeImages OutOfMemoryError     |   " +oe.getMessage();
				 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
	    	} catch(Exception e){
	    		e.printStackTrace();
	    		String errorMsg = className+" |  mergeImages      |   " +e.getMessage();
				Common.sendCrashWithAQuery(TryOn.this,errorMsg);
	    	}
	    	captureImageRotate = 90;
	        return bottomImage;
	    }
	    
	    
	    List<XmlDom> listAllXmlVals;
	    ArrayList<String> arrForPdId, arrForClientId, arrForPdImage, arrForPdName, arrForPdPrice, arrForClBgColor,
	arrForClLogo, arrForClName, arrForClBgLightColor, arrForClBgDarkColor,arrForClUrl;
	private void getVerticalImgPdResultsFromServerWithXml(String pdUrl) {
		startnow = android.os.SystemClock.uptimeMillis();
		 Log.e("MYTAG startnow ", "start time: "+0+" s");
		aq.ajax(pdUrl, XmlDom.class, new AjaxCallback<XmlDom>(){			
			@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
				try{
					if(xml!=null){
						endnow = android.os.SystemClock.uptimeMillis();
   		  			    Log.e("MYTAG closet endnow ", "Excution time: "+(endnow-startnow)/1000+" s");
    			  		listAllXmlVals = xml.tags("products");
    			  		if(listAllXmlVals.size()>0){ 
       					    ProductModel  getProdDetail = file.getProduct();
       					   	progressBar.setVisibility(View.INVISIBLE);
    			  			arrForClientId = new ArrayList<String>();
    						arrForPdId = new ArrayList<String>();
    						arrForPdImage = new ArrayList<String>();
    						arrForPdName = new ArrayList<String>();   	
    						arrForPdPrice = new ArrayList<String>();   		
    						arrForClBgColor = new ArrayList<String>();  
    						arrForClBgLightColor = new ArrayList<String>();  
    						arrForClBgDarkColor = new ArrayList<String>();   
    						arrForClLogo = new ArrayList<String>();   
    						arrForClName = new ArrayList<String>();  
    						arrForClUrl = new ArrayList<String>(); 
    						UserProduct chkProdMainExist = getProdDetail.getUserProductById(Integer.parseInt(checkProdId));
               			 	if(chkProdMainExist != null){
	               			 	arrForClientId.add(""+chkProdMainExist.getClientId());
								arrForPdId.add(""+chkProdMainExist.getProductId());
								arrForPdImage.add(chkProdMainExist.getImageFile());
								arrForPdName.add(chkProdMainExist.getProductName());
								arrForClBgColor.add(chkProdMainExist.getClientBackgroundColor());
								arrForClBgLightColor.add(chkProdMainExist.getClientLightColor());
								arrForClBgDarkColor.add(chkProdMainExist.getClientDarkColor());
								if (chkProdMainExist.getProductPrice().equals("null") || 
										chkProdMainExist.getProductPrice().equals("") || 
										chkProdMainExist.getProductPrice().equals("0") || 
										chkProdMainExist.getProductPrice().equals("0.00") || 
										chkProdMainExist.getProductPrice() == null) {
									arrForPdPrice.add("");
								} else {
									arrForPdPrice.add(chkProdMainExist.getProductPrice());
								}
								arrForClLogo.add(chkProdMainExist.getClientLogo());
								arrForClName.add(chkProdMainExist.getClientName());
								arrForClUrl.add(chkProdMainExist.getClientUrl());
               			 	}
               			 //   new LoadTryOnProducts().execute(); 
    					    for(final XmlDom listEachXmlVal : listAllXmlVals){
    					    	try { 
    								if(listEachXmlVal.tag("prodId")!=null){
    									
    									if(!arrForPdId.contains(listEachXmlVal.text("prodId").toString())){
    									String symbol = new Common().getCurrencySymbol(listEachXmlVal.text("country_languages").toString(), listEachXmlVal.text("country_code_char2").toString());
    									arrForClientId.add(listEachXmlVal.text("clientId").toString());
    									arrForPdId.add(listEachXmlVal.text("prodId").toString());
    									arrForPdImage.add(listEachXmlVal.text("pdImage").toString());
    									arrForPdName.add(listEachXmlVal.text("prodName").toString());
    									arrForClBgColor.add(listEachXmlVal.text("background_color").toString());
    									arrForClBgLightColor.add(listEachXmlVal.text("light_color").toString());
    									arrForClBgDarkColor.add(listEachXmlVal.text("dark_color").toString());
    									
    									Common.sessionClientBgColor              = listEachXmlVal.text("background_color").toString();
										Common.sessionClientBackgroundLightColor = listEachXmlVal.text("light_color").toString();
										Common.sessionClientBackgroundDarkColor  = listEachXmlVal.text("dark_color").toString();
										
										if (listEachXmlVal.text("pdPrice").toString().equals("null") || 
												listEachXmlVal.text("pdPrice").toString().equals("") || 
												listEachXmlVal.text("pdPrice").toString().equals("0") || 
												listEachXmlVal.text("pdPrice").toString().equals("0.00") || 
												listEachXmlVal.text("pdPrice").toString() == null) {
											arrForPdPrice.add("");
										} else {
											arrForPdPrice.add(symbol+listEachXmlVal.text("pdPrice").toString());
										}
    									//arrForPdPrice.add(symbol+listEachXmlVal.text("pdPrice").toString());
    									arrForClLogo.add(listEachXmlVal.text("clientLogo").toString());
    									arrForClName.add(listEachXmlVal.text("name").toString());
    									arrForClUrl.add(listEachXmlVal.text("clientUrl").toString());
 										
    									}
    								}
    					    	} catch (Exception e){
    					    		e.printStackTrace();
    					    		String errorMsg = className+" |  getVerticalImgPdResultsFromServerWithXml ajax call   |   " +e.getMessage();
									Common.sendCrashWithAQuery(TryOn.this,errorMsg);
    					    	}
    					    }
    					    
    					   
    					    if(arrForPdId.size()>0){
    					        lvForRightVerticalImages.setAdapter(renderForTryOnVerticalScroll(arrForPdId, arrForPdImage, 
    					        		arrForPdName, arrForPdPrice, arrForClientId, arrForClLogo, arrForClName));
    					        new LoadTryOnProducts().execute(); 
    					        lvForRightVerticalImages.setOnItemClickListener(new OnItemClickListener() {
									@Override
									public void onItemClick(
											AdapterView<?> arg0, View v, int position, long arg3) {
										
										try{
											Log.i("arrForClBgLightColor", ""+arrForClBgLightColor.get(position).toString());
											Log.i("arrForClBgDarkColor", ""+arrForClBgDarkColor.get(position).toString());
											Common.sessionClientBgColor = arrForClBgColor.get(position).toString();
											Common.sessionClientBackgroundLightColor = arrForClBgLightColor.get(position).toString();
											Common.sessionClientBackgroundDarkColor = arrForClBgDarkColor.get(position).toString();
											
											Bitmap placeholder = aq.getCachedImage(arrForPdImage.get(position));
											if(placeholder==null){
												aq.cache(arrForPdImage.get(position), 14400000);					
											}
											imgvMultiImagesOnScreen = new ImageView(TryOn.this);
											imgvMultiImagesOnScreen.setId(position);
											Log.i("ScaleType.MATRIX", ""+ScaleType.MATRIX);
											imgvMultiImagesOnScreen.setImageBitmap(placeholder);
											
											getProdName = arrForPdName.get(position);
											getFinalWebSiteUrl = arrForClUrl.get(position);		
									        photoSorter = (PhotoSortrView)findViewById(R.id.photoSortrView1);
								            photoSorter.loadImages1(TryOn.this, imgvMultiImagesOnScreen.getDrawable());  
																																	
											lvForRightVerticalImages.setVisibility(View.INVISIBLE);
											imgvCameraCapture.setVisibility(View.VISIBLE);	

											llForChangeCamera.setVisibility(View.VISIBLE);
											llForChangeItems.setVisibility(View.VISIBLE);
											llForSelectPhoto.setVisibility(View.VISIBLE);
											llForSelectPhoto.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
											llForChangeItems.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
											llForChangeCamera.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));

											// Set this class as touchListener to the ImageView
											imgvMultiImagesOnScreen.setTag(R.string.try_on_img_pd_id, arrForPdId.get(position));
											imgvMultiImagesOnScreen.setTag(R.string.try_on_img_cl_id, arrForClientId.get(position));
											imgvMultiImagesOnScreen.setTag(R.string.try_on_img_name, arrForPdName.get(position));
											imgvMultiImagesOnScreen.setTag(R.string.try_on_img_price, arrForPdPrice.get(position));
											
											//scaleImageToFitCenter(placeholder);
										} catch(Exception e){
											e.printStackTrace();
											String errorMsg = className+" |   lvForRightVerticalImages click   |   " +e.getMessage();
											Common.sendCrashWithAQuery(TryOn.this,errorMsg);
										}									
									}
								});
    					    }
    					    
    			  		}
    				}
				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" |   getVerticalImgPdResultsFromServerWithXml   |   " +e.getMessage();
					Common.sendCrashWithAQuery(TryOn.this,errorMsg);
				}
			}
		});
	}
    int gridItemLayout = 0;
	private ArrayAdapter<String> renderForTryOnVerticalScroll(final ArrayList<String> arrForPdId2, 
			final ArrayList<String> arrForPdImage2, final ArrayList<String> arrForPdName2, 
			final ArrayList<String> arrForPdPrice2, final ArrayList<String> arrForClientId2, ArrayList<String> arrForClLogo2, ArrayList<String> arrForClName2){	    	
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.listview_for_try_on;	
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, arrForPdId2){				
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, gridItemLayout, parent);
					}			
					String tbUrl = arrForPdImage2.get(position);	
					AQuery aq2 = new AQuery(convertView);			
					Bitmap placeholder = aq2.getCachedImage(tbUrl);
					if(placeholder==null){
						aq2.cache(tbUrl, 14400000);					
					}
					ImageView img =(ImageView) convertView.findViewById(R.id.imgvForPds);
					aq2.id(R.id.imgvForPds).image(tbUrl, true, true, 0, 0, placeholder, 0, 0);
					img.setTag(R.string.productId, arrForPdId2.get(position));		
					img.setTag(R.string.clientId, arrForClientId2.get(position));
					RelativeLayout.LayoutParams llForImgPd = (RelativeLayout.LayoutParams) img.getLayoutParams();
					llForImgPd.width = (int) (0.5 * Common.sessionDeviceWidth);
					llForImgPd.height = (int) (0.3074 * Common.sessionDeviceHeight);
					img.setLayoutParams(llForImgPd);
					
					RelativeLayout rlForTxtBg = (RelativeLayout) convertView.findViewById(R.id.rlForTxtOverlay);
					rlForTxtBg.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));
					RelativeLayout.LayoutParams rlpForTxtBgColor = (RelativeLayout.LayoutParams) rlForTxtBg.getLayoutParams();
					rlpForTxtBgColor.width = LayoutParams.MATCH_PARENT;
					rlpForTxtBgColor.height = (int) (0.072 * Common.sessionDeviceHeight);
					rlForTxtBg.setLayoutParams(rlpForTxtBgColor);

					TextView txtvPdPrice =(TextView) convertView.findViewById(R.id.txtvPdPrice);
					txtvPdPrice.setText(arrForPdPrice2.get(position));
					txtvPdPrice.setTextSize((float) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					RelativeLayout.LayoutParams rlpForPdPrice = (RelativeLayout.LayoutParams) txtvPdPrice.getLayoutParams();
					rlpForPdPrice.leftMargin = (int) (0.034 * Common.sessionDeviceWidth);
					txtvPdPrice.setLayoutParams(rlpForPdPrice);
					
					TextView txtvPdName =(TextView) convertView.findViewById(R.id.txtvPdName);
					txtvPdName.setText(arrForPdName2.get(position));
					txtvPdName.setTextSize((float) ((0.03 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					RelativeLayout.LayoutParams rlpForPdName = (RelativeLayout.LayoutParams) txtvPdName.getLayoutParams();
					rlpForPdName.leftMargin = (int) (0.034 * Common.sessionDeviceWidth);
					txtvPdName.setLayoutParams(rlpForPdName);					
					
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | OnCreate  renderForTryOnVerticalScroll click  |   " +e.getMessage();
					Common.sendCrashWithAQuery(TryOn.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		return aa;
	}
	
	public Size getOptimalPreviewSize(List<Size> sizes, int w, int h) {           
	    final double ASPECT_TOLERANCE = 0.1;           
	    double targetRatio = (double) w / h;           
	    if (sizes == null) return null;             
	    Size optimalSize = null;           
	    double minDiff = Double.MAX_VALUE;             
	    int targetHeight = h;             
	    try{
		    // Try to find an size match aspect ratio and size           
		    for (Size size : sizes) {               
		        double ratio = (double) size.width / size.height;               
		        if (Math.abs(ratio - targetRatio) > ASPECT_TOLERANCE) continue;              
		        if (Math.abs(size.height - targetHeight) < minDiff) {                   
		            optimalSize = size;                   
		            minDiff = Math.abs(size.height - targetHeight);               
		        }           
		    }             
		  
		    // Cannot find the one match the aspect ratio, ignore the requirement           
		    if (optimalSize == null) {               
		        minDiff = Double.MAX_VALUE;               
		        for (Size size : sizes) {                   
		            if (Math.abs(size.height - targetHeight) < minDiff) {                      
		                optimalSize = size;                       
		                minDiff = Math.abs(size.height - targetHeight);                   
		            }               
		        }           
		    }           
	    } catch(Exception e){
	    	e.printStackTrace();
	    	 String errorMsg = className+" |  getOptimalPreviewSize     |   " +e.getMessage();
			 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
	    }
	    return optimalSize;       
	}
	
 

    /*@Override
	public void onBackPressed() {
    	try{
    		super.onBackPressed();
    		Log.i("onbackpressed", "onbackpressed");
			finish();
			//overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
    	} catch (Exception e) {
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: onBackPressed.", Toast.LENGTH_LONG).show();
		}
    }*/
    
    /*@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
         if (keyCode == KeyEvent.KEYCODE_BACK) {
         //preventing default implementation previous to android.os.Build.VERSION_CODES.ECLAIR
         return true;
         }
         return super.onKeyDown(keyCode, event);    
    }*/

    public Bitmap mergePhotoImages(Bitmap bottomImage, Bitmap topImage) {
    	Bitmap output = null;
    	Bitmap mutableBitmap = null;
    	try{
			//Log.e("bottomImage.getWidth()",""+bottomImage.getWidth()+" "+bottomImage.getHeight()+" - "+((int)(0.0547*Common.sessionDeviceHeight)/Common.sessionDeviceDensity)+" "+(bottomImage.getHeight()-(int)(0.041*Common.sessionDeviceHeight)/Common.sessionDeviceDensity));
			//Log.e("topImage.getHeight()",""+topImage.getWidth()+" "+topImage.getHeight());
	        output = Bitmap.createScaledBitmap(bottomImage, bottomImage.getWidth(), bottomImage.getHeight(), false);
	        //ByteArrayOutputStream out = new ByteArrayOutputStream();
	        //output.compress(Bitmap.CompressFormat.PNG, 100, out);
	        mutableBitmap = output.copy(Bitmap.Config.ARGB_8888, true);
	    	
	        final Canvas canvas = new Canvas(mutableBitmap);
	    	/*if(mutableBitmap!=null){
	    		mutableBitmap.recycle();
	    		mutableBitmap = null;
	    	}*/
	        
	    	Bitmap scaledBorder = Bitmap.createScaledBitmap(topImage, bottomImage.getWidth(), (bottomImage.getHeight()-(int)(0.0547*Common.sessionDeviceHeight)/Common.sessionDeviceDensity), false);
	    	//ByteArrayOutputStream out1 = new ByteArrayOutputStream();
	    	//scaledBorder.compress(Bitmap.CompressFormat.PNG, 100, out1);
	    	canvas.drawBitmap(scaledBorder, 0, 0, null);
	    	if(scaledBorder!=null){
	    		scaledBorder.recycle();
	    		scaledBorder = null;
	    		if(bottomImage!=null){
	    			bottomImage.recycle();
	    		}
	    		if(topImage!=null){
	    			topImage.recycle();
	    		}	    		
	    		System.gc();
	    		if(output!=null){
	    			output.recycle();
	    			output = null;
		    	}
	    	}
    	} catch(OutOfMemoryError oe){
    		oe.printStackTrace();
    		String errorMsg = className+" |  mergeImages OutOfMemoryError     |   " +oe.getMessage();
			 Common.sendCrashWithAQuery(TryOn.this,errorMsg);
    	} catch(Exception e){
    		e.printStackTrace();
    		String errorMsg = className+" |  mergeImages      |   " +e.getMessage();
			Common.sendCrashWithAQuery(TryOn.this,errorMsg);
    	}
    	captureImageRotate = 90;
        return mutableBitmap;
    }
    
    
	 @Override
	public void onStart() {
	    super.onStart();	    
	    try{
	    	  // The rest of your onStart() code.
		    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		    String[] segments = new String[1];
			segments[0] = "Try on"; 
			QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
	    } catch(Exception e){
	    	e.printStackTrace();
	    	String errorMsg = className+" |  onStart      |   " +e.getMessage();
			Common.sendCrashWithAQuery(TryOn.this,errorMsg);
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
			 String errorMsg = className+" |  onStop      |   " +e.getMessage();
			Common.sendCrashWithAQuery(TryOn.this,errorMsg);
		 }
		 
	 }
	 class LoadTryOnProducts extends AsyncTask<String, String, String> {		
			@Override
			protected String doInBackground(final String... args) {
				try {
						ProductModel productModel = new ProductModel();
					    ProductModel  getProdDetail = file.getProduct();
					    for(final XmlDom listEachXmlVal : listAllXmlVals){
					    	
							if(listEachXmlVal.tag("prodId")!=null){	
								String symbol = new Common().getCurrencySymbol(listEachXmlVal.text("country_languages").toString(), listEachXmlVal.text("country_code_char2").toString());									
								UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(listEachXmlVal.text("prodId").toString()));
                    			 if(chkProdExist == null){
	                    			 UserProduct userProduct = new UserProduct();
	                    			 userProduct.setClientId(Integer.parseInt(listEachXmlVal.text("clientId").toString()));
	                    			 userProduct.setClientName(listEachXmlVal.text("name").toString());
	                    			 userProduct.setClientUrl(listEachXmlVal.text("clientUrl").toString());
	                    			 userProduct.setImageFile(listEachXmlVal.text("pdImage").toString());
	                    			 userProduct.setProductId(Integer.parseInt(listEachXmlVal.text("prodId").toString()));
	                    			 userProduct.setProductName(listEachXmlVal.text("prodName").toString());
	                    			 if (listEachXmlVal.text("pdPrice").toString().equals("null") || 
												listEachXmlVal.text("pdPrice").toString().equals("") || 
												listEachXmlVal.text("pdPrice").toString().equals("0") || 
												listEachXmlVal.text("pdPrice").toString().equals("0.00") || 
												listEachXmlVal.text("pdPrice").toString() == null) {
	                    				 userProduct.setProductPrice(listEachXmlVal.text("pdPrice").toString());
	                    			 }
	                    			 else
	                    				 userProduct.setProductPrice(symbol+listEachXmlVal.text("pdPrice").toString());
	                    				 
	                    			 userProduct.setProductShortDesc(listEachXmlVal.text("pd_short_description").toString());
	                    			 userProduct.setProductUrl(listEachXmlVal.text("productUrl").toString());
	                    			 userProduct.setProdIsTryOn(Integer.parseInt(listEachXmlVal.text("pd_istryon").toString()));
	                    			 userProduct.setClientBackgroundColor(listEachXmlVal.text("background_color").toString());
	                    			 userProduct.setClientLightColor(listEachXmlVal.text("light_color").toString());
	                    			 userProduct.setClientDarkColor(listEachXmlVal.text("dark_color").toString());
	                    			 userProduct.setClientLogo(listEachXmlVal.text("clientLogo").toString());
	                    			 productModel.add(userProduct);
                    			 }
								}
				   
				    }
					    if(productModel.size() >0){
	                		getProdDetail.mergeWith(productModel);
	                		file.setProduct(getProdDetail);
	                	}
					} catch (Exception e) {				
						e.printStackTrace();
						String errorMsg = className+" | LoadTryOnProducts | doInBackground |  " +e.getMessage();
		           	 	Common.sendCrashWithAQuery(TryOn.this,errorMsg);
				    }
				return null;			
				
			}
	 }
	 
	 public static int calculateInSampleSize(
	            BitmapFactory.Options options, int reqWidth, int reqHeight) {
	    // Raw height and width of image
	    final int height = options.outHeight;
	    final int width = options.outWidth;
	    int inSampleSize = 1;

	    if (height > reqHeight || width > reqWidth) {

	        final int halfHeight = height / 2;
	        final int halfWidth = width / 2;

	        // Calculate the largest inSampleSize value that is a power of 2 and keeps both
	        // height and width larger than the requested height and width.
	        while ((halfHeight / inSampleSize) > reqHeight
	                && (halfWidth / inSampleSize) > reqWidth) {
	            inSampleSize *= 2;
	        }
	    }

	    return inSampleSize;
	}
}
