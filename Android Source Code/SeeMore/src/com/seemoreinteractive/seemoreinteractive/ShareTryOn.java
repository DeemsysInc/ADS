package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.text.SimpleDateFormat;
import java.util.Date;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.Image;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class ShareTryOn extends Activity {

	final Context context = this;
	String className = this.getClass().getSimpleName();
	public boolean isBackPressed = false;
	
	ImageView image;
	
	String getClientId, imageName, imagePrice, getProductId;
	byte[] selectedImage;
	Bitmap finalImageBitmap;
	
	String tryOn = ""; 
	Intent getIntVals; 
	//AQuery aq;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		//Log.e("ShareTryOn", "On Create");
		setContentView(R.layout.activity_share_try_on);
		try{
            //aq = new AQuery(ShareTryOn.this);
			image = (ImageView) findViewById(R.id.imgvProdInfoBigImg);

			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			System.gc();


			
			String screenName = "/shoppervision/share/"+getClientId+"/"+getProductId;
			String productIds = "";
	    	String offerIds = "";
			Common.sendJsonWithAQuery(this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
			
			getIntVals = getIntent();
			
			
			Button saveImg =(Button) findViewById(R.id.btnTryOnSaveImage);
			RelativeLayout.LayoutParams rlpForSave = (RelativeLayout.LayoutParams) saveImg.getLayoutParams();
			rlpForSave.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			saveImg.setLayoutParams(rlpForSave);
			saveImg.setTextSize((float)((0.0584 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			saveImg.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						/*Drawable drawableImg = image.getDrawable();
		                Bitmap saveFinalBmpImg = ((BitmapDrawable) drawableImg).getBitmap();
						ByteArrayOutputStream stream = new ByteArrayOutputStream();
						saveFinalBmpImg.compress(Bitmap.CompressFormat.PNG, 100, stream);
		                byte[] byteArray = stream.toByteArray();
						savePicture(byteArray);*/
						
						  image.setDrawingCacheEnabled(true); 
						  Bitmap bitmap = Bitmap.createBitmap(image.getDrawingCache());
						  image.setDrawingCacheEnabled(false);
						  ByteArrayOutputStream bytes = new ByteArrayOutputStream();
						  bitmap.compress(Bitmap.CompressFormat.JPEG, 40, bytes);
						  /*File f = new File(Environment.getExternalStorageDirectory()
						                    + File.separator + "test.jpg");
						  f.createNewFile();
						  FileOutputStream fo = new FileOutputStream(f);
						  fo.write(bytes.toByteArray()); 
						  fo.close();*/
						
						  savePicture(bytes.toByteArray());
						  
						  
					} catch (Exception e) {
						//Toast.makeText(getApplicationContext(), "Error: ShareActivity clicked on share.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | saveImg click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
					}
				}
			});
			Button cancel =(Button) findViewById(R.id.btnTryOnCancel);
			RelativeLayout.LayoutParams rlpForcancel = (RelativeLayout.LayoutParams) cancel.getLayoutParams();
			rlpForcancel.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			cancel.setLayoutParams(rlpForcancel);
			cancel.setTextSize((float)((0.0584 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			cancel.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						if(Image.getCameraBitmap()!= null){
						 Image.getCameraBitmap().recycle();
						}
						if(Image.getCombinedBitmap()!= null){
							 Image.getCombinedBitmap().recycle();
						}
						if(Image.getCombinedBitmap()!= null){
							 Image.getCombinedBitmap().recycle();
						}	
						Image.setCameraBitmap(null);
						Image.setCharacterBitmap(null);
						Image.setCombinedBitmap(null);
						Image.setLegalBitmap(null);
						System.gc();
						/*Intent prodListPage = new Intent(getApplicationContext(), TryOn.class);
						//Log.i("onback getProductId", ""+getProductId);
						prodListPage.putExtra("instruction_type", "0");
						prodListPage.putExtra("clientId", getClientId);
						prodListPage.putExtra("productId", getProductId);						
						startActivity(prodListPage);*/
						Intent prodListPage = new Intent(getApplicationContext(), TryOn.class);
						setResult(1);
			        	finish();
			        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: ShareActivity clicked on share.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
						String errorMsg = className+" | cancel click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
					}
				}
			});

	    	ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);        
	        imgBackButton.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						   if(Image.getCameraBitmap()!= null){
							 Image.getCameraBitmap().recycle();
							}
							if(Image.getCombinedBitmap()!= null){
								 Image.getCombinedBitmap().recycle();
							}
							if(Image.getCombinedBitmap()!= null){
								 Image.getCombinedBitmap().recycle();
							}						
						Image.setCameraBitmap(null);
						Image.setCharacterBitmap(null);
						Image.setCombinedBitmap(null);
						Image.setLegalBitmap(null);
						System.gc();
						/*Intent prodListPage = new Intent(getApplicationContext(), TryOn.class);
						Log.i("onback getProductId", ""+getProductId);
						prodListPage.putExtra("instruction_type", "0");
						prodListPage.putExtra("clientId", getClientId);
						prodListPage.putExtra("productId", getProductId);
						//setResult(1, prodListPage);
						startActivity(prodListPage);*/
						Intent prodListPage = new Intent(getApplicationContext(), TryOn.class);
						setResult(1);
			        	finish();
			        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);			        	
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: TryOn imgBackButton.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgBackButton click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
					}
				}
			});
	    	ImageView imgvBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);        
	    	imgvBtnCart.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						Intent intent = new Intent(ShareTryOn.this, ARDisplayActivity.class);			
	    				//intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
	    				//intent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);			
	    				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);						
	    				startActivity(intent); // Launch the HomescreenActivity
	    				finish(); 
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: TryOn imgBackButton.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | imgvBtnCart click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
					}
				}
			});
						
		} catch(Exception e){
			e.printStackTrace();
		}
	}
	/*
     * created savePicture(byte [] data) for testing 
     */
    private int counter = 1; 
    public void savePicture(byte[] data)
    {
    	try{
	        Log.i( "ScanVinFromBarcodeActivity " , "savePicture(byte [] data)");
	        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyymmddhhmmss");
	        String date = dateFormat.format(new Date());
	        String photoFile = "Picture_"+counter+"_"+ date + ".jpg";
	        String folderName = "SeeMore Images";
	        File sdDir = new File(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_PICTURES)+File.separator+folderName);	      
	        Log.i( "sdDir " , ""+sdDir);
	        String filename = sdDir + File.separator + photoFile;
	        Log.i( "filename " , ""+filename);
	        File pictureFile = new File(filename);
	        Log.i( "pictureFile " , ""+pictureFile);
        	if(!pictureFile.exists()){
	        	if(!sdDir.mkdirs()){
	        		Log.i("sdDir", "Directory created.");
	        	}
	        }
	        try {
	        	/*ByteArrayOutputStream bytes = new ByteArrayOutputStream();
				Image.getCombinedBitmap().compress(Bitmap.CompressFormat.JPEG, 100, bytes);
	            FileOutputStream fos = new FileOutputStream(pictureFile);
	            fos.write(bytes.toByteArray());
	            fos.close();*/

	        	//ByteArrayOutputStream bytes = new ByteArrayOutputStream();
				//Image.getCombinedBitmap().compress(Bitmap.CompressFormat.JPEG, 100, bytes);
	            FileOutputStream fos = new FileOutputStream(pictureFile);
	            fos.write(data);
	            fos.close();
	          Log.i( "File saved: " , "file saved");
	          if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.KITKAT) {
                  Intent mediaScanIntent = new Intent(
                          Intent.ACTION_MEDIA_SCANNER_SCAN_FILE);
                  Uri contentUri = Uri.fromFile(pictureFile);
                  mediaScanIntent.setData(contentUri);
                  this.sendBroadcast(mediaScanIntent);
              } else {
                  sendBroadcast(new Intent(
                          Intent.ACTION_MEDIA_MOUNTED,
                          Uri.parse("file://"
                                  + Environment.getExternalStorageDirectory())));
              }
	          Toast.makeText(getApplicationContext(), "Image saved in Pictures.", Toast.LENGTH_LONG).show();
	        } catch (Exception error) {
	        	error.printStackTrace();
	            Log.i( "File not saved: " , error.getMessage());
	        }    		
    	} catch(Exception e){
    		e.printStackTrace();
    		String errorMsg = className+" | savePicture click   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
    	}
        counter++;
    }
    @Override
	public void onBackPressed() {
    	try{
    		//Log.e("ShareTryOn", "onBackPressed");
    		   if(Image.getCameraBitmap()!= null){
				 Image.getCameraBitmap().recycle();
				}
				if(Image.getCombinedBitmap()!= null){
					 Image.getCombinedBitmap().recycle();
				}
				if(Image.getCombinedBitmap()!= null){
					 Image.getCombinedBitmap().recycle();
				}
			Image.setCameraBitmap(null);
			Image.setCharacterBitmap(null);
			Image.setCombinedBitmap(null);
			Image.setLegalBitmap(null);	
			System.gc();
			Intent prodListPage = new Intent(getApplicationContext(), TryOn.class);
			setResult(1);
        	finish();
        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);			
    	} catch (Exception e) {
			// TODO: handle exception
    		e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: onBackPressed.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed click   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
		}
    }
	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, Common.sessionClientBgColor,
				Common.sessionClientBackgroundLightColor,
				Common.sessionClientBackgroundDarkColor,
				Common.sessionClientLogo, "Share","");
		//Log.e("ShareTryOn", "onStart");
		if(getIntVals.getExtras()!=null){
			getProductId = getIntVals.getStringExtra("productId");
			getClientId = getIntVals.getStringExtra("clientId");
			tryOn = getIntVals.getStringExtra("tryOn");
			imageName = getIntVals.getStringExtra("imageName");
			imagePrice = getIntVals.getStringExtra("imagePrice");
			String flag = getIntVals.getStringExtra("flag"); 
			/*byte[] byteArray = getIntent().getByteArrayExtra("tryOnFinalImage");
			Bitmap finalBmpImage = BitmapFactory.decodeByteArray(byteArray, 0, byteArray.length);*/
			
			Log.i("getClientId", "getClientId "+getClientId);
			Log.i("getProductId", "getProductId "+getProductId);
			Log.i("tryOn", "tryOn"+tryOn);
			Log.i("imageName", "imageName "+imageName);
			Log.i("imagePrice", "imagePrice "+imagePrice);
			if(tryOn.equals("true")){
				Bitmap bmpSetCombined;
				if(flag.equals("camera")){
					 bmpSetCombined = new TryOn().mergeImages(Image.getCameraBitmap(), Image.getCharacterBitmap());
				}else{
					 bmpSetCombined = new TryOn().mergePhotoImages(Image.getCameraBitmap(), Image.getCharacterBitmap());
				}
    			Image.setCombinedBitmap(bmpSetCombined);
				image.setImageBitmap(Image.getCombinedBitmap());		
				}
		}
	    // The rest of your onStart() code.
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		 String[] segments = new String[1];
		 segments[0] = "Share Try on"; 
		 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart click   |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		//Log.e("ShareTryOn", "onStop");
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.		
		QuantcastClient.activityStop();
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop click   |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
		 }
	}
	 @Override
	public void onPause(){
		 try{
			 super.onPause();
			//Log.e("ShareTryOn", "onPause");
			Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ShareTryOn.this);
			if(appInBackgrnd){
				 Common.isAppBackgrnd = true;
			}	
		 }catch(Exception e){
			 String errorMsg = className+" | onPause click   |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
		 }
		
	 }
	 @Override
	public void onDestroy(){
		 try{
		super.onDestroy();
		//Log.e("ShareTryOn", "onDestroy");
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onDestroy click   |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
		 }
	 }
	 @Override
	public void onResume(){
		 try{
		super.onResume();
		//Log.e("ShareTryOn", "onResume");
		if(Common.isAppBackgrnd){
			new Common().storeChangeLogResultFromServer(ShareTryOn.this);			
			Common.isAppBackgrnd = false;
		}
		 }catch(Exception e){
			 String errorMsg = className+" | onResume click   |   " +e.getMessage();
			 Common.sendCrashWithAQuery(ShareTryOn.this,errorMsg);
		 }
	 }
	
}
