package com.seemoreinteractive.seemoreinteractive;

import java.net.URL;
import java.util.ArrayList;
import java.util.Random;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.wheel.Carousel;
import com.seemoreinteractive.seemoreinteractive.wheel.CarouselItem;

public class WheelOfDeals extends Activity {
	String className = getClass().getSimpleName();
	String gameId;
	public  ArrayList<String> arrImagesList;
	public  ArrayList<String> arrImagesOfferId;
	public  ArrayList<Bitmap> arrImagesBitmap;
	ArrayList<JSONObject>arrOfferDetails;
	Carousel carousel; 
	ProgressBar progressBar;
	Handler mHandler = new Handler();
	public static int numval = 0;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		try{
		    setContentView(R.layout.activity_wheel_of_deals);		
			/*new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					WheelOfDeals.this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Wheel Of Deals", "");*/
		    Bundle b = getIntent().getExtras();
			String jsons=b.getString("json");
			JSONArray jsonObject = new JSONArray(jsons);
		    JSONArray clientInfoArray = new JSONArray(jsonObject.getJSONObject(0).getString("client_info"));
			if(clientInfoArray.length() > 0){
				for(int i=0;i<clientInfoArray.length();i++){
					
					 JSONObject json_obj = clientInfoArray.getJSONObject(i);	
					 Common.sessionClientLogo      = json_obj.getString("logo");
					 Common.sessionClientBgColor   = json_obj.getString("background_color");
					 Common.sessionClientBackgroundLightColor  = json_obj.getString("light_color");
					 Common.sessionClientBackgroundDarkColor   = json_obj.getString("dark_color");
				}				
			}
			
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					WheelOfDeals.this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, "", "");
			
			progressBar = (ProgressBar)findViewById(R.id.progressBar);
			gameId = getIntent().getStringExtra("gameId");
			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgBtnCameraIcon click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(WheelOfDeals.this,errorMsg);
					}
				}
			});
			ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);
			imgvBtnBack.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{
						finish();
						overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					}catch(Exception  e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnBack click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(WheelOfDeals.this,errorMsg);
					}
				}
			});
			
			ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			imgvBtnShare.setImageAlpha(0);
			
			ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);
			
			ImageView pointer = (ImageView) findViewById(R.id.pointer);
			RelativeLayout.LayoutParams rlpPointer = (RelativeLayout.LayoutParams) pointer.getLayoutParams();
			rlpPointer.width  = (int) (0.25* Common.sessionDeviceWidth);
			rlpPointer.height = (int) (0.154 * Common.sessionDeviceHeight);		
			rlpPointer.topMargin = (int) (0.2623 * Common.sessionDeviceHeight);		
			rlpPointer.rightMargin = (int) (0.03334 * Common.sessionDeviceWidth);		
			pointer.setLayoutParams(rlpPointer);
			
			final ImageView btnSpin = (ImageView) findViewById(R.id.btnSpin);
			RelativeLayout.LayoutParams rlpBtnSpin = (RelativeLayout.LayoutParams) btnSpin.getLayoutParams();
			rlpBtnSpin.width  = (int) (0.5* Common.sessionDeviceWidth);
			rlpBtnSpin.height = (int) (0.082 * Common.sessionDeviceHeight);			
			//rlpBtnSpin.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);	
			btnSpin.setLayoutParams(rlpBtnSpin);
			
			carousel = (Carousel)findViewById(R.id.carousel);
			RelativeLayout.LayoutParams rlpCarousel= (RelativeLayout.LayoutParams) carousel.getLayoutParams();		
			rlpCarousel.height = (int) (0.77 * Common.sessionDeviceHeight);			
			carousel.setLayoutParams(rlpCarousel);
			
			btnSpin.setOnClickListener(new OnClickListener() {
					
					public void onClick(View v) {
						try{
							 
						   // carousel.mFlingRunnable.startUsingVelocity(6000);
							Random rand = new Random();
							 
						    //int num = (int)(Math.random() + 5 * 500 );
							int num ;
							if(numval == 0 ){
							  num = (int)(rand.nextInt(6) + 5 * 335);
							  numval = num;
							}else{
								num    = numval + 1000;
								numval =  (int)(rand.nextInt(6) + 3 * 305);
							}
					    	if(num == 0 ){
					    		num = 6000;
					    	}else{
					    		num  = num + 6000;
					    	}
					    	//Log.e("num",""+num);
					    	carousel.mFlingRunnable.startUsingVelocity(num);
					    	/*
					    	Log.e("carousel.mFlingRunnable.mRotator.isFinished()",""+carousel.mFlingRunnable.mRotator.isFinished());
						  if(carousel.mFlingRunnable.mRotator.isFinished()){
							Toast.makeText(getApplicationContext(), "rotate finish", Toast.LENGTH_LONG).show();
						  }*/
					    	btnSpin.setVisibility(View.INVISIBLE);
					    	
					    	mHandler.postDelayed(new Runnable() {
					            @Override
						            public void run() {
					            	    Log.e("pos",""+carousel.getSelectedItemPosition()+"id"+carousel.getSelectedItemId());
					            	    Intent intent = new Intent(WheelOfDeals.this,GameOffer.class);
						            	intent.putExtra("jsonObject", arrOfferDetails.get(carousel.getSelectedItemPosition()).toString());
						            	intent.putExtra("game_rules_url", getIntent().getStringExtra("game_rules_url"));
										startActivity(intent);
						            	startActivity(intent);
						            	finish();
						            	overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
						            }
						        }, 3000);
						}catch(Exception e){
							e.printStackTrace();
						}
						
					}
				});
			
			displayGameOffer();		    
			
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	ImageAdapterNew adapter;
	JSONArray jsonOfferDetails;
	public void displayGameOffer(){
		try{	
			AQuery aq = new AQuery(WheelOfDeals.this);
			Bundle b = getIntent().getExtras();
			String jsons=b.getString("json");
			jsonOfferDetails = new JSONArray(jsons);
			arrImagesList    = new ArrayList<String>();
			arrImagesBitmap  = new ArrayList<Bitmap>();
			arrImagesOfferId = new ArrayList<String>();
			arrOfferDetails  = new ArrayList<JSONObject>();
			if(jsonOfferDetails.length() > 0){															
					for(int s=0; s<jsonOfferDetails.length(); s++){					
						 JSONObject json_obj = jsonOfferDetails.getJSONObject(s);	
						 final String curveImagesUrl = json_obj.getString("game_image").replaceAll(" ", "%20");			
						 Log.e("curveImagesUrl",curveImagesUrl);
						 arrImagesOfferId.add(json_obj.getString("offer_id"));
						 arrOfferDetails.add(json_obj);
						 Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
						 if(bitmap == null){
							 URL scrImageUrl = new URL(curveImagesUrl); 
							 Bitmap bm = BitmapFactory.decodeStream(scrImageUrl.openConnection().getInputStream());
							 arrImagesBitmap.add(bm);	
							 aq.cache(curveImagesUrl, 1440000);
							}else{
							 arrImagesBitmap.add(bitmap);	
							}			 
					}
					progressBar.setVisibility(View.INVISIBLE);
					adapter = new ImageAdapterNew(WheelOfDeals.this);
					adapter.SetImages(arrImagesBitmap, arrImagesOfferId);
		            carousel.setAdapter(adapter);
			}
					  
				
	}catch(Exception e){
		e.printStackTrace();
		String errorMsg = className+" | displayGameOffer callback  |   " +e.getMessage();
		Common.sendCrashWithAQuery(WheelOfDeals.this, errorMsg);
	}
 }
	
	
	public void displayGameOfferold(){
		try{	
			adapter = new ImageAdapterNew(WheelOfDeals.this);
			Bundle b = getIntent().getExtras();
			String jsons=b.getString("json");
			jsonOfferDetails = new JSONArray(jsons);
			final AQuery aq = new AQuery(WheelOfDeals.this);
			arrImagesList    = new ArrayList<String>();
			arrImagesBitmap  = new ArrayList<Bitmap>();
			arrImagesOfferId = new ArrayList<String>();
			arrOfferDetails  = new ArrayList<JSONObject>();
			if(jsonOfferDetails.length() > 0){															
					for(int s=0; s<jsonOfferDetails.length(); s++){
					{
						 JSONObject json_obj = jsonOfferDetails.getJSONObject(s);	
						 final String curveImagesUrl = json_obj.getString("game_image").replaceAll(" ", "%20");			
						 Log.e("curveImagesUrl",curveImagesUrl);
						 arrImagesOfferId.add(json_obj.getString("offer_id"));
						 arrOfferDetails.add(json_obj);
						 Bitmap bitmap = aq.getCachedImage(curveImagesUrl);
						// if(bitmap == null){										 
							aq.cache(curveImagesUrl, 1440000);
							aq.ajax(curveImagesUrl, Bitmap.class, new AjaxCallback<Bitmap>() {
						         @Override
						         public void callback(String url, Bitmap bm, AjaxStatus status) {
						        	 arrImagesBitmap.add(bm);
					        		 Log.e("arrImagesBitmap", ""+arrImagesBitmap.size()+" "+jsonOfferDetails.length());
						        	 if(arrImagesBitmap.size() == jsonOfferDetails.length()){
						        		 Log.e("arrImagesBitmap", ""+arrImagesBitmap.size()+" "+arrImagesBitmap);
						        		/* RelativeLayout 	caroselLayout = (RelativeLayout)findViewById(R.id.caroselLayout);
						   			        RelativeLayout.LayoutParams rlpCarousel= (RelativeLayout.LayoutParams) caroselLayout.getLayoutParams();		
							    			rlpCarousel.height = (int) (0.718 * Common.sessionDeviceHeight);			
							    			caroselLayout.setLayoutParams(rlpCarousel);
						   			       // caroselLayout.addView(carousel);
						   			     FrameLayout barFrameLayout = new FrameLayout(WheelOfDeals.this);
						   		        FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
						   		                LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT,
						   		                Gravity.CENTER);
						   		        barFrameLayout.setLayoutParams(params);
						   		     barFrameLayout.addView(carousel);
						   		  caroselLayout.addView(barFrameLayout);*/
											//final Handler handler = new Handler();
									        /*handler.postDelayed(new Runnable() {
									            @Override
									            public void run() {		
									            	//carousel.setSelection(0, true);
									            	 Log.e("if curveImagesUrl",curveImagesUrl);*/
									            	 //ImageAdapter adapter = new ImageAdapter(WheelOfDeals.this);
									        		//adapter.SetImages(arrImagesBitmap, arrImagesOfferId, false);	
									               // carousel = (Carousel)findViewById(R.id.carousel);
									               //carousel.setAdapter(adapter);
									        		
									        		//Carousel carousel = new Carousel(WheelOfDeals.this);
									        		//carousel.setAdapter(adapter);
									        		/* RelativeLayout.LayoutParams rlpCarousel = new RelativeLayout.LayoutParams(LayoutParams.WRAP_CONTENT,LayoutParams.WRAP_CONTENT);
								   			     	 carousel.setLayoutParams(rlpCarousel);	*/
								   			     	
								   			     	

									        		
									            ///}
									        //}, 1000);
										}
						         }
						     });
						/*}else{
							  arrImagesBitmap.add(bitmap);
							  if(arrImagesBitmap.size() == jsonOfferDetails.length()){
							 	final Handler handler = new Handler();
								handler.postDelayed(new Runnable() {
						            @Override
							            public void run() {
						            	 Log.e("else curveImagesUrl",curveImagesUrl);
						            	ImageAdapter adapter = new ImageAdapter(WheelOfDeals.this);
						        		adapter.SetImages(arrImagesBitmap, arrImagesOfferId, false);	
						                carousel = (Carousel)findViewById(R.id.carousel);
						                carousel.setAdapter(adapter);    
							            }
							        }, 1000);		
					               }
   							   }*/
									 arrImagesList.add(curveImagesUrl);
								  
						      }
							}
					  carousel = (Carousel)findViewById(R.id.carousel);
		              carousel.setAdapter(adapter);
						}
				
	}catch(Exception e){
		e.printStackTrace();
		String errorMsg = className+" | displayGameOffer callback  |   " +e.getMessage();
		Common.sendCrashWithAQuery(WheelOfDeals.this, errorMsg);
	}
 }
	
	private class ImageAdapterNew extends BaseAdapter {

		private Context mContext;
		private CarouselItem[] mImages;		
		
		public ImageAdapterNew(Context c) {
			mContext = c;
		}		
						
		@SuppressWarnings("unused")
		public void SetImages(ArrayList<Bitmap> images, ArrayList<String> names){
			SetImages(images, names, true);
		}
		
		public void SetImages(ArrayList<Bitmap> images, ArrayList<String> names, boolean reflected){
			try{
			if(names != null)
				if(images.size() != names.size())
					throw new RuntimeException("Images and names arrays length doesn't match");
			
			mImages = new CarouselItem[images.size()];
			
			for(int i = 0; i< images.size(); i++)
			{
				//drawables[i] = images.getDrawable(i);
				//Bitmap originalImage = ((BitmapDrawable)drawables[i]).getBitmap();
				
				Bitmap originalImage =  images.get(i);
				Log.e("originalImage",""+originalImage);
				Log.e("name",""+names.get(i));
			
				CarouselItem item = new CarouselItem(mContext);
				item.setIndex(i);
				item.setImageBitmap(originalImage);
				if(names != null)
					item.setText(names.get(i));
				mImages[i] = item;				
				
			}
			
			}catch(Exception e){
				e.printStackTrace();
			}
		}

		public int getCount() {
			if(mImages == null)
				return 0;
			else
				return mImages.length;
		}

		public Object getItem(int position) {
			return position;
		}

		public long getItemId(int position) {
			return position;
		}

		public View getView(int position, View convertView, ViewGroup parent) {		
			return mImages[position];
		}

	}		
	
	
	@Override
	protected void onDestroy() {
		super.onDestroy();
		try{
			//Log.i("ondestroy","WheelOfDeals");
			mHandler.removeCallbacksAndMessages(null);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onDestroy |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(WheelOfDeals.this,errorMsg);
		}

		
	}
	
}

