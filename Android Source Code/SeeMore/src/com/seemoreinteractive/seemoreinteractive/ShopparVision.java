package com.seemoreinteractive.seemoreinteractive;

import java.io.File;

import android.R.color;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.helper.mMagnifier;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN) public class ShopparVision extends Activity{

	final Context context = this;
	String className = this.getClass().getSimpleName();
	public boolean isBackPressed = false;
	
	ImageView image,imgSearch;
	
	String getClientId, imageName, imagePrice, getProductId;
	byte[] selectedImage;
	Bitmap finalImageBitmap;
	
	String tryOn = ""; 
	Intent getIntVals; 
	TextView txtColorView,txtColorCode,txtChooseColor,txtChooseType;
	Bitmap bitmap;	
	AQuery aq;
	Spinner chooseTypeSpinner;
	mMagnifier imgMagnifier;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);		
		setContentView(R.layout.activity_shoppar_vision);
		try{
            aq = new AQuery(ShopparVision.this);
			image = new ImageView (this);
			
			txtColorView =(TextView)findViewById(R.id.txtColor);
			txtColorCode =(TextView)findViewById(R.id.txtColorCode);
			txtChooseColor =(TextView)findViewById(R.id.txtChooseColor);
			txtChooseType =(TextView)findViewById(R.id.txtChooseType);
			imgSearch = (ImageView)findViewById(R.id.imgSearch);
			
			getIntVals = getIntent();
			chooseTypeSpinner = (Spinner)findViewById(R.id.chooseTypeSpinner);
			
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					ShopparVision.this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "ShopparVision", "");
			
			 new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			 
			 ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
		     imgvBtnCloset.setImageAlpha(0);
		     
		     ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			 imgvBtnShare.setImageAlpha(0);
			 
			 ImageView imgBtnCamera = (ImageView) findViewById(R.id.imgvBtnCart);        
			 imgBtnCamera.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
							finish();
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						} catch (Exception e) {							
							String errorMsg = className+" | imgBtnCamera click   |   " +e.getMessage();
						    Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);
						}
					}
				});
			 ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			 imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{						
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnBack click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);
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
						String errorMsg = className+" | imgFooterMiddle click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);
					}
	            }
			 });
			 
			 
			 RelativeLayout.LayoutParams rlForTxtChooseType = (RelativeLayout.LayoutParams) txtChooseType.getLayoutParams();
			 rlForTxtChooseType.topMargin = (int) (0.0144 * Common.sessionDeviceHeight);
			 rlForTxtChooseType.leftMargin = (int) (0.02 * Common.sessionDeviceWidth);			
			 txtChooseType.setLayoutParams(rlForTxtChooseType);
			 txtChooseType.setTextSize( (int) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			 
			 
			 RelativeLayout.LayoutParams rlForChooseTypeSpinner = (RelativeLayout.LayoutParams) chooseTypeSpinner.getLayoutParams();
			 rlForChooseTypeSpinner.width = (int) (0.5 * Common.sessionDeviceWidth);
			 rlForChooseTypeSpinner.height = (int) (0.062 * Common.sessionDeviceHeight);
			 rlForChooseTypeSpinner.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
			 rlForChooseTypeSpinner.rightMargin = (int) (0.017 * Common.sessionDeviceWidth);
			 chooseTypeSpinner.setLayoutParams(rlForChooseTypeSpinner);
			 
			 RelativeLayout.LayoutParams rlForTxtChooseColor = (RelativeLayout.LayoutParams) txtChooseColor.getLayoutParams();
			 rlForTxtChooseColor.topMargin = (int) (0.041 * Common.sessionDeviceHeight);
			 rlForTxtChooseColor.leftMargin = (int) (0.02 * Common.sessionDeviceWidth);
			 txtChooseColor.setLayoutParams(rlForTxtChooseColor);
			 txtChooseColor.setTextSize( (int) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			 
			 RelativeLayout.LayoutParams rlForColorView = (RelativeLayout.LayoutParams) txtColorView.getLayoutParams();
			 rlForColorView.width = (int) (0.075 * Common.sessionDeviceWidth);
			 rlForColorView.height = (int) (0.047 * Common.sessionDeviceHeight);	
			 rlForColorView.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
			 txtColorView.setLayoutParams(rlForColorView);
			 
			 
			 RelativeLayout.LayoutParams rlForImgSearch = (RelativeLayout.LayoutParams) imgSearch.getLayoutParams();
			 rlForImgSearch.width = (int) (0.334 * Common.sessionDeviceWidth);
			 rlForImgSearch.height = (int) (0.062 * Common.sessionDeviceHeight);
			 rlForImgSearch.topMargin = (int) (0.0267 * Common.sessionDeviceHeight);			 
			 imgSearch.setLayoutParams(rlForImgSearch);
			 

			 
			 
			 imgSearch.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						Log.e("colorCode",""+txtColorCode.getText().toString()); 
						Log.e("prodType",""+chooseTypeSpinner.getSelectedItem().toString());
						
						Intent intent = new Intent(ShopparVision.this, ShopparList.class);
						intent.putExtra("colorCode", txtColorCode.getText().toString());
						intent.putExtra("prodType", chooseTypeSpinner.getSelectedItem().toString());
		    			startActivity(intent);
		    			overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnSearch click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);
					}
				
				}
			});
	    	
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate | "+e.getMessage();
        	Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
		}
	}
	private void getProjectedColor(int positionX, int positionY){
		try{
		if(positionX<0 || positionY<0 || positionX> imgMagnifier.getWidth() || positionY > imgMagnifier.getHeight() ){		   
			txtColorView.setBackgroundColor(color.background_light);		         		     
		  }else{    
		   int projectedX = (int)(positionX * ((double)bitmap.getWidth()/(double)imgMagnifier.getWidth()));
		   int projectedY = (int)(positionY * ((double)bitmap.getHeight()/(double)imgMagnifier.getHeight()));
	
		       //Color_Selected is the returned color at specified location.
		       int Color_Selected = bitmap.getPixel(projectedX, projectedY);  		             
		       
		       txtColorView.setBackgroundColor(Color.parseColor("#"+Integer.toHexString(Color_Selected).toUpperCase().substring(2)));
		       txtColorCode.setText("#"+Integer.toHexString(Color_Selected).toUpperCase().substring(2));
   		  }            
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" | getProjectedColor | "+e.getMessage();
	          Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
		  }
		 }
	OnTouchListener touchListener = new OnTouchListener() {
		   @Override
		  public boolean onTouch(View v, MotionEvent event) {			  
           try{
           int positionX = (int)event.getX();
           int positionY = (int)event.getY();
           if(event.getAction() == MotionEvent.ACTION_DOWN){		                 
		                Log.e("positionY",""+positionY);
		                Log.e("bitmap.getHeight()",""+bitmap.getHeight());		               
		                getProjectedColor(positionX,positionY);
		    }else if (event.getAction() == MotionEvent.ACTION_MOVE){	
		   		getProjectedColor(positionX,positionY);
		      }
           }catch(Exception e){
			   e.printStackTrace();
			   String errorMsg = className+" | touchListener | "+e.getMessage();
		       Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
		   }
		   return false; 		   
		   }
		 };

	 @Override
	public void onStart() {
		 try{
		    super.onStart();
		    
		 	Handler mHandler=new Handler();
   			Runnable mRunnable = new Runnable() {
   	            @Override
   	            public void run() {
   	            	String filepath = Constants.Trigger_Location+"shopparvision.png";			
	   	 			File imgFile = new  File(filepath);
	   	 			if(imgFile.exists()){	   	 		     
	   	             bitmap = BitmapFactory.decodeFile(imgFile.getAbsolutePath());						 
	   				 if(bitmap != null){
	   					 
	   					// Bitmap resizedBitmap = Bitmap.createScaledBitmap(bitmap,  Common.sessionDeviceWidth, Common.sessionDeviceHeight, false);
	   					 image.setImageBitmap(bitmap);
	   					 imgMagnifier = new mMagnifier(getApplicationContext(), null,image);	  
	   					 
	   			     	 RelativeLayout mainRlLayout = (RelativeLayout) findViewById(R.id.relativeImgLayout);
	   			     	 RelativeLayout.LayoutParams rlForMainRlLayout = (RelativeLayout.LayoutParams) mainRlLayout.getLayoutParams();
	   			     	 rlForMainRlLayout.width = LayoutParams.MATCH_PARENT;
	   			     	 rlForMainRlLayout.height = (int) (0.512 * Common.sessionDeviceHeight);   	
	   			     	 rlForMainRlLayout.leftMargin =(int) (0.0167 * Common.sessionDeviceWidth);	
	   			         rlForMainRlLayout.rightMargin=(int) (0.0167 * Common.sessionDeviceWidth);	
	   			         rlForMainRlLayout.topMargin=(int) (0.011 * Common.sessionDeviceHeight);		
	   			     	 mainRlLayout.setLayoutParams(rlForMainRlLayout);	   			         
	   			         mainRlLayout.addView(imgMagnifier);
	   					 /*RelativeLayout.LayoutParams rlForImgMagnifier = (RelativeLayout.LayoutParams) imgMagnifier.getLayoutParams();
	   					 rlForImgMagnifier.width = rlForImgMagnifier.WRAP_CONTENT;
	   					 rlForImgMagnifier.height = rlForImgMagnifier.WRAP_CONTENT;		
	   					   
	   					 imgMagnifier.setLayoutParams(rlForImgMagnifier);*/
	   					 imgMagnifier.setOnTouchListener(touchListener);		   					 
	   					
   				     }
   	 			   }
   	            }
   	        };
   			mHandler.postDelayed(mRunnable,3000); 
			
			 
			 Tracker easyTracker = EasyTracker.getInstance(this);
				easyTracker.set(Fields.SCREEN_NAME, "/shopparvision");
				easyTracker.send(MapBuilder
				    .createAppView()
				    .build()
				);
			 String[] segments = new String[1];
			 segments[0] = "ShopparVision"; 
			 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		  
			  
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart | "+e.getMessage();
		     Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
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
			 String errorMsg = className+" | onStop | "+e.getMessage();
		     Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
		 }
		
	}
	 @Override
	public void onPause(){
		 try{
			 super.onPause();	
			 Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ShopparVision.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}	
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onPause | "+e.getMessage();
		     Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
		 }
		
	 }
	 @Override
	public void onDestroy(){
		 try{
		super.onDestroy();
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onDestroy | "+e.getMessage();
		     Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
			 
		 }
	 }
	 @Override
	public void onResume(){
		 try{
			 super.onResume();		
			 if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(ShopparVision.this);			
					Common.isAppBackgrnd = false;
				}
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onResume | "+e.getMessage();
		     Common.sendCrashWithAQuery(ShopparVision.this,errorMsg);	
		 }
	 }
	
}
